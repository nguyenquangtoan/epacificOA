<?php

class NetUtils{
    public static function getDataFromUrl($url, $post, $header_type = null){
        if (function_exists('curl_init')) {
            $options = array(
                CURLOPT_RETURNTRANSFER => true,     // return web page
                CURLOPT_HEADER         => false,    // don't return headers
                //CURLOPT_FOLLOWLOCATION => true,     // follow redirects
                CURLOPT_ENCODING       => "",       // handle all encodings
                CURLOPT_USERAGENT      => "spider", // who am i
                CURLOPT_AUTOREFERER    => true,     // set referer on redirect
                CURLOPT_CONNECTTIMEOUT => 120,      // timeout on connect
                CURLOPT_TIMEOUT        => 120,      // timeout on response
                CURLOPT_MAXREDIRS      => 10       // stop after 10 redirects
            );
            if($post != ''){
                $options[CURLOPT_POST] = 1;
                $options[CURLOPT_POSTFIELDS] = $post;
            }else{
                $options[CURLOPT_POST] = 0;
            }
            if($header_type == 'json'){
                $options[CURLOPT_HTTPHEADER] = array('Content-Type:application/json');
            }
            // print_r($options);die;
            $ch      = curl_init( $url );
            curl_setopt_array( $ch, $options );
            $content = curl_exec( $ch );
            $err     = curl_errno( $ch );
            $errmsg  = curl_error( $ch );
            $header  = curl_getinfo( $ch );
            curl_close( $ch );
            $header['errno']   = $err;
            $header['errmsg']  = $errmsg;
            $header['content'] = $content;
            return $header;
        } else {
            return false;
        }
    }
    
    public static function extractCookiesByFile($cookie_file) {
        $text = FileUtils::readTextFileByFullName($cookie_file);
        $cookies = self::extractCookies($text);
        return $cookies;
    }
    public static function extractCookies($string) {
        $lines = explode(PHP_EOL, $string);
        $cookies = array();
        foreach ($lines as $line) {
            // detect httponly cookies and remove #HttpOnly prefix
            $cookie = array();
            if (substr($line, 0, 10) == '#HttpOnly_') {
                $line = substr($line, 10);
                $cookie['httponly'] = true;
            } else {
                $cookie['httponly'] = false;
            }
            
            // we only care for valid cookie def lines
            if( strlen( $line ) > 0 && $line[0] != '#' && substr_count($line, "\t") == 6) {
                
                // get tokens in an array
                $tokens = explode("\t", $line);
                
                // trim the tokens
                $tokens = array_map('trim', $tokens);
                
                // Extract the data
                $cookie['domain'] = $tokens[0]; // The domain that created AND can read the variable.
                $cookie['flag'] = $tokens[1];   // A TRUE/FALSE value indicating if all machines within a given domain can access the variable.
                $cookie['path'] = $tokens[2];   // The path within the domain that the variable is valid for.
                $cookie['secure'] = $tokens[3]; // A TRUE/FALSE value indicating if a secure connection with the domain is needed to access the variable.
                
                $cookie['expiration-epoch'] = $tokens[4];  // The UNIX time that the variable will expire on.
                $cookie['name'] = urldecode($tokens[5]);   // The name of the variable.
                $cookie['value'] = urldecode($tokens[6]);  // The value of the variable.
                
                // Convert date to a readable format
                $cookie['expiration'] = date('Y-m-d h:i:s', $tokens[4]);
                
                // Record the cookie.
                $cookies[] = $cookie;
            }
        }
        
        return $cookies;
    }
    public static function curlWithCookie($url, $post, $cookie_file, $header_type, $proxy=false, $time_out = 120){
        if (function_exists('curl_init')) {
            $ckfile = $cookie_file;
            $options = array(
                CURLOPT_RETURNTRANSFER => true,     // return web page
                CURLOPT_HEADER         => false,    // don't return headers
                //CURLOPT_FOLLOWLOCATION => true,     // follow redirects
                CURLOPT_ENCODING       => "",       // handle all encodings
                CURLOPT_USERAGENT      => "spider", // who am i
                CURLOPT_AUTOREFERER    => true,     // set referer on redirect
                CURLOPT_CONNECTTIMEOUT => $time_out,      // timeout on connect
                CURLOPT_TIMEOUT        => $time_out,      // timeout on response
                CURLOPT_MAXREDIRS      => 10
            );
            if($ckfile != ''){
                $options[CURLOPT_COOKIEJAR] = $ckfile;
                $options[CURLOPT_COOKIEFILE] = $ckfile;
            }
            if($post != ''){
                $options[CURLOPT_POST] = 1;
                $options[CURLOPT_POSTFIELDS] = $post;
                
            }else{
                $options[CURLOPT_POST] = 0;
            }
            if($header_type == 'json'){
                $options[CURLOPT_HTTPHEADER] = array('Content-Type:application/json');
            }else if(is_array($header_type) && count($header_type)){
                $options[CURLOPT_HTTPHEADER] = $header_type;
            }
            
            if($proxy != false || $proxy != ''){
                $proxy = explode(':', $proxy);
                $options[CURLOPT_PROXY] = $proxy[0];
                $options[CURLOPT_PROXYPORT] = $proxy[1];
            }
            $ch      = curl_init( $url );
            curl_setopt_array( $ch, $options );
            $content = curl_exec( $ch );
            $err     = curl_errno( $ch );
            $errmsg  = curl_error( $ch );
            $header  = curl_getinfo( $ch );
            curl_close( $ch );
            $header['errno']   = $err;
            $header['errmsg']  = $errmsg;
            $header['content'] = $content;
            return $header;
        } else {
            return false;
        }
    }
    /**
     * Get Domain of a given URL
     */
    public static function getDomainInUrl($url) {
        if($url == "http://"){
            return $url;
        }
        $url = get_real_link2($url);
        $slashPos = strpos($url,"/");// find last "/"
        if ($slashPos!==false) {
            $url = substr($url,0,$slashPos);
        }
        return $url;
    }
    
    /**
     * Get Root Domain
     */
    
    public static function getRootDomainInUrl($url)	{
        $full_domain = get_url_domain($url);
        $full_domain_ar = explode(".",$full_domain);
        $count = count($full_domain_ar);
        $sample = $full_domain_ar[$count-2] . "." . $full_domain_ar[$count-1];
        $domain_level_two = explode(',','ac.cn,ac.jp,ac.uk,ad.jp,adm.br,adv.br,agr.br,ah.cn,am.br,arq.br,art.br,asn.au,ato.br,av.tr,bel.tr,bio.br,biz.tr,bj.cn,bmd.br,cim.br,cng.br,cnt.br,co.at,co.jp,co.uk,com.au,com.br,com.cn,com.eg,com.hk,com.mx,com.ru,com.tr,com.tw,conf.au,cq.cn,csiro.au,dr.tr,ecn.br,edu.au,edu.br,edu.tr,emu.id.au,eng.br,esp.br,etc.br,eti.br,eun.eg,far.br,fj.cn,fm.br,fnd.br,fot.br,fst.br,g12.br,gb.com,gb.net,gd.cn,gen.tr,ggf.br,gob.mx,gov.au,gov.br,gov.cn,gov.hk,gov.tr,gr.jp,gs.cn,gx.cn,gz.cn,ha.cn,hb.cn,he.cn,hi.cn,hk.cn,hl.cn,hn.cn,id.au,idv.tw,imb.br,ind.br,inf.br,info.au,info.tr,jl.cn,jor.br,js.cn,jx.cn,k12.tr,lel.br,ln.cn,ltd.uk,mat.br,me.uk,med.br,mil.br,mil.tr,mo.cn,mus.br,name.tr,ne.jp,net.au,net.br,net.cn,net.eg,net.hk,net.lu,net.mx,net.ru,net.tr,net.tw,net.uk,nm.cn,no.com,nom.br,not.br,ntr.br,nx.cn,odo.br,oop.br,or.at,or.jp,org.au,org.br,org.cn,org.hk,org.lu,org.ru,org.tr,org.tw,org.uk,plc.uk,pol.tr,pp.ru,ppg.br,pro.br,psc.br,psi.br,qh.cn,qsl.br,rec.br,sc.cn,sd.cn,se.com,se.net,sh.cn,slg.br,sn.cn,srv.br,sx.cn,tel.tr,tj.cn,tmp.br,trd.br,tur.br,tv.br,tw.cn,uk.com,uk.net,vet.br,wattle.id.au,web.tr,xj.cn,xz.cn,yn.cn,zj.cn,zlg.br,co.nr,co.nz,com.fr,com.vn,net.vn,org.vn,biz.vn,gov.vn,edu.vn,int.vn,ac.vn,pro.vn,info.vn,health.vn,name.vn');
        $rs = "";
        if(in_array($sample,$domain_level_two)){
            $rs = $full_domain_ar[$count-3] . "." . $sample;
        }else{
            $rs = $sample;
        }
        return $rs;
    }
    
    // DUC-NH: VIẾT LẠI ĐỂ DÙNG TRONG TEMPLATE
    function insert_get_url_domain($arr) {
        if($arr['url'] == "http://"){
            return $arr['url'];
        }
        // get "real" link
        $arr['url'] = get_real_link($arr['url']);
        // remove any character after /
        $slashPos = strpos($arr['url'],"/");// find last "/"
        if ($slashPos!==false) {
            $arr['url'] = substr($arr['url'],0,$slashPos);
        }
        return $arr['url'];
    }
    
    /**
     * Log date, time, IP address and rule which triggered the spam
     */
    function logSpam($from, $message) {
        require_once (PACKAGE_LIBS . 'check_behind_proxy.php');
        $ip = check_ip_behind_proxy ();
        if (! empty ( $_SERVER ["REMOTE_ADDR"] )) {
            $ip = $_SERVER ["REMOTE_ADDR"];
        }
        $date = date ( 'd-M-Y H:i:s' );
        $timestamp = time ();
        $message = $date . "\t" . $timestamp . "\t" . $from . "\t" . $ip . "\t" . $message . "\n";
        $file = fopen ( SPAM_LOG_FILE, "a" );
        fwrite ( $file, $message );
        fclose ( $file );
    }
    
    /**
     * Get a web file (HTML, XHTML, XML, image, etc.) from a URL.  Return an
     * array containing the HTTP server response header fields and content.
     * On success, "errno" is 0, "http_code" is 200, and "content" contains the web page.
     *
     * See more at: http://nadeausoftware.com/articles/2007/06/php_tip_how_get_web_page_using_curl
     */
    public static function curl_file_get_contents($url,$cookie=false,$cookie_fie='',$header=false) {
        // make sure curl is installed
        if (function_exists('curl_init')) {
            $options = array(
                CURLOPT_RETURNTRANSFER => true,     // return web page
                CURLOPT_HEADER         => $header,    // don't return headers
                CURLOPT_FOLLOWLOCATION => false,     // follow redirects
                CURLOPT_ENCODING       => "UTF-8",       // handle all encodings
                CURLOPT_USERAGENT      => "spider", // who am i
                CURLOPT_AUTOREFERER    => true,     // set referer on redirect
                CURLOPT_CONNECTTIMEOUT => 3600,      // timeout on connect
                CURLOPT_TIMEOUT        => 3600,      // timeout on response
                CURLOPT_MAXREDIRS      => 10,       // stop after 10 redirects
                CURLOPT_SSL_VERIFYPEER => false,
                //	        curl_setopt($ch, CURLOPT_HEADER, true);
            );
            if($cookie == true){
                $options[CURLOPT_COOKIEFILE] = $cookie_fie;
            }
            
            $ch      = curl_init( $url );
            curl_setopt_array( $ch, $options );
            $content = curl_exec( $ch );
            $err     = curl_errno( $ch );
            $errmsg  = curl_error( $ch );
            $header  = curl_getinfo( $ch );
            curl_close( $ch );
            
            $header['errno']   = $err;
            $header['errmsg']  = $errmsg;
            $header['content'] = $content;
            return $header;
        } else {
            return false;
        }
    }
    
    public static function curl_post($url,$cookie=false,$cookie_fie='',$header=false) {
        // make sure curl is installed
        if (function_exists('curl_init')) {
            $options = array(
                CURLOPT_RETURNTRANSFER => true,     // return web page
                CURLOPT_HEADER         => $header,    // don't return headers
                CURLOPT_FOLLOWLOCATION => false,     // follow redirects
                CURLOPT_ENCODING       => "UTF-8",       // handle all encodings
                CURLOPT_USERAGENT      => "spider", // who am i
                CURLOPT_AUTOREFERER    => true,     // set referer on redirect
                CURLOPT_CONNECTTIMEOUT => 3600,      // timeout on connect
                CURLOPT_TIMEOUT        => 3600,      // timeout on response
                CURLOPT_MAXREDIRS      => 10,       // stop after 10 redirects
                CURLOPT_SSL_VERIFYPEER => false,
                CURLOPT_POST => true
                //	        curl_setopt($ch, CURLOPT_HEADER, true);
            );
            if($cookie == true){
                $options[CURLOPT_COOKIEFILE] = $cookie_fie;
            }
            
            $ch      = curl_init( $url );
            curl_setopt_array( $ch, $options );
            $content = curl_exec( $ch );
            $err     = curl_errno( $ch );
            $errmsg  = curl_error( $ch );
            $header  = curl_getinfo( $ch );
            curl_close( $ch );
            
            $header['errno']   = $err;
            $header['errmsg']  = $errmsg;
            $header['content'] = $content;
            return $header;
        } else {
            return false;
        }
    }
    
    
    function hitForm($loginURL, $loginFields, $referer="", $domain="") {
        $post=$loginFields;
        $head[0] = "POST /$loginURL HTTP/1.1";
        $head[] = "Host: $domain";
        $head[] = "User-Agent: Mozilla/5.0 (Windows NT 6.1; WOW64; rv:8.0.1) Gecko/20100101 Firefox/8.0.1";
        $head[] = "Referer: $loginURL";
        $url = $loginURL;
        $cookie = 'Cookie'.'Socks-'.time().'.txt';
        fclose(fopen($cookie,'w'));
        $ch = curl_init();
        curl_setopt($ch,CURLOPT_URL,$url);
        curl_setopt($ch,CURLOPT_POST,true);
        curl_setopt($ch,CURLOPT_POSTFIELDS,$post);
        curl_setopt($ch,CURLOPT_HTTPHEADER,$head);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie);
        curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        $a = curl_exec($ch);
        curl_close($ch);
        
        //	//$fp = fopen("danhba24dotcomcookies.txt", "w");
        //	//fclose($fp);
        //	PRINT_R(ROOT_PATH."/cookie.txt");
        //	 $ch = curl_init();
        //
        //        //set the url, number of POST vars, POST data
        //        curl_setopt($ch,CURLOPT_URL, $loginURL);
        //        curl_setopt($ch,CURLOPT_POSTFIELDS, $loginFields);
        //        curl_setopt($ch,CURLOPT_USERAGENT, "Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1)");
        //        curl_setopt($ch,CURLOPT_SSL_VERIFYPEER, false);
        ////        curl_setopt($ch,CURLOPT_PROXY, $proxy);
        //        curl_setopt($ch,CURLOPT_COOKIEJAR, ROOT_PATH."/cookie.txt");
        //        curl_setopt($ch,CURLOPT_COOKIEFILE, ROOT_PATH."/cookie.txt");
        //        curl_setopt($ch, CURLOPT_POST, 1);
        //		curl_setopt($ch, CURLOPT_REFERER, $referer);
        //		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        //		curl_setopt($ch, CURLOPT_COOKIESESSION, true);
        //
        //    //curl_setopt($ch,CURLOPT_RETURNTRANSFER, true);
        //        //execute post
        //        $result = curl_exec($ch);
        //
        ////        print_r(curl_error($ch));
        //        print_r(curl_getinfo($ch));
        ////        print_r(curl_errno($ch));
        //
        //        //close connection
        //        curl_close($ch);
        ////	$ch = curl_init();
        //	curl_setopt($ch, CURLOPT_COOKIESESSION, true);
        //	curl_setopt($ch, CURLOPT_COOKIEJAR, "danhba24dotcomcookies.txt");
        //	curl_setopt($ch, CURLOPT_COOKIEFILE, "danhba24dotcomcookies.txt");
        //	curl_setopt($ch, CURLOPT_URL, $loginURL);
        //	curl_setopt($ch, CURLOPT_POST, 1);
        //	curl_setopt($ch, CURLOPT_REFERER, $referer);
        //	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        //	curl_setopt($ch, CURLOPT_POSTFIELDS, $loginFields);
        //	$ret = curl_exec($ch);
        //	curl_close($ch);
        //	curl_setopt($ch,    CURLOPT_AUTOREFERER,         true);
        //	curl_setopt($ch,    CURLOPT_COOKIESESSION,         true);
        //	curl_setopt($ch,    CURLOPT_FAILONERROR,         false);
        //	curl_setopt($ch,    CURLOPT_FOLLOWLOCATION,        false);
        //	curl_setopt($ch,    CURLOPT_FRESH_CONNECT,         true);
        //	curl_setopt($ch,    CURLOPT_HEADER,             true);
        //	curl_setopt($ch,    CURLOPT_POST,                 true);
        //	curl_setopt($ch,    CURLOPT_RETURNTRANSFER,        true);
        //	curl_setopt($ch,    CURLOPT_CONNECTTIMEOUT,     30);
        //	curl_setopt($ch,    CURLOPT_POSTFIELDS,         $loginFields);
        //	$result = curl_exec($ch);
        //	curl_close($ch);
        
        //	print_r($ret);die;
        //	return $ret;
        
    }
    
    
    
    function curl_file_postdata($url,$datatopost) {
        // make sure curl is installed
        if (function_exists('curl_init')) {
            
            //		$options = array(
            //	        CURLOPT_RETURNTRANSFER => true,     // return web page
            ////	        CURLOPT_HEADER         => false,    // don't return headers
            //	        //CURLOPT_FOLLOWLOCATION => true,     // follow redirects
            //	        CURLOPT_POST => true,
            //	        CURLOPT_POSTFIELDS => $datatopost,
            ////	        CURLOPT_ENCODING       => "",       // handle all encodings
            ////	        CURLOPT_USERAGENT      => "spider", // who am i
            ////	        CURLOPT_AUTOREFERER    => true,     // set referer on redirect
            ////	        CURLOPT_CONNECTTIMEOUT => 120,      // timeout on connect
            ////	        CURLOPT_TIMEOUT        => 120,      // timeout on response
            ////	        CURLOPT_MAXREDIRS      => 10,       // stop after 10 redirects
            //	    );
            
            $ch = curl_init ($url);
            curl_setopt ($ch, CURLOPT_POST, true);
            curl_setopt ($ch, CURLOPT_POSTFIELDS, $datatopost);
            curl_setopt ($ch, CURLOPT_RETURNTRANSFER, true);
            $content = curl_exec ($ch);
            
            
            
            //	    $ch      = curl_init( $url );
            //	    curl_setopt_array( $ch, $options );
            //	    $content = curl_exec( $ch );
            $err     = curl_errno( $ch );
            $errmsg  = curl_error( $ch );
            $header  = curl_getinfo( $ch );
            curl_close( $ch );
            
            $header['errno']   = $err;
            $header['errmsg']  = $errmsg;
            $header['content'] = $content;
            return $header;
        } else {
            return false;
        }
    }
    
    
    /**
     * Get RSS link from a given URL using meta extraction
     *
     * Issues: 	- Can not handle page which is redirected to another page
     * 			- Can not get RSS link from
     * Found: return RSS link, Not found return empty string ""
     */
    function get_rss_link_by_url($url){
        $rss_url = "";
        try {
            $ch = curl_init();
            // set URL and other appropriate options
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_HEADER, false);
            curl_setopt($ch, CURLOPT_MAXREDIRS, 10);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
            curl_setopt($ch, CURLOPT_ENCODING, "");
            curl_setopt($ch, CURLOPT_RETURNTRANSFER,true);
            curl_setopt($ch, CURLOPT_USERAGENT,"spider");
            curl_setopt($ch, CURLOPT_AUTOREFERER,true);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT,120);
            curl_setopt($ch, CURLOPT_TIMEOUT,120);
            // grab URL and pass it to the browser
            $content = curl_exec($ch);
            if(!$content){
                throw new \Exception("Could not get file content!");
            }
            
            // find RSS link
            $start = mb_strpos($content, 'type="application/rss+xml"');
            if ($start) {
                $content = substr($content, $start);
                
                $end = mb_strpos($content, '/>');
                $content = substr($content, 0, $end);
                
                $start1 = mb_strpos($content, 'http');
                $content = substr($content, $start1);
                
                $start2 = mb_strpos($content, '"');
                $content = substr($content, 0, $start2);
                $rss_url = $content;
            } else {
                $start = mb_strpos($content, 'type="application/atom+xml"');
                if ($start) {
                    $content = substr($content, $start);
                    
                    $end = mb_strpos($content, '/>');
                    $content = substr($content, 0, $end);
                    
                    $start1 = mb_strpos($content, 'http');
                    $content = substr($content, $start1);
                    
                    $start2 = mb_strpos($content, '"');
                    $content = substr($content, 0, $start2);
                    $rss_url = $content;
                }
            }
            
            // close cURL resource, and free up system resources
            curl_close($ch);
        } catch (\Exception $ex) {
        }
        return $rss_url;
    }
    
    function isIP($ip_address){
        if(filter_var($ip_address, FILTER_VALIDATE_IP)) {
            return  TRUE;
        } else {
            return  FALSE;
        }
    }
    
    function isDomain($name){
        if (preg_match ("/^[a-z0-9][a-z0-9\-]+[a-z0-9](\.[a-z]{2,4})+$/i", $name)) {
            return  TRUE;
        } else {
            return  FALSE;
        }
    }
    
    /**
     * Longch - 4.8.2012
     * @param $url
     */
    function getContentJson($url){
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $content = curl_exec($ch);
        curl_close($ch);
        $content = json_decode($content);
        return $content;
    }
    /**
     * Longch - 4.8.2012
     */
    function curPageURL() {
        $pageURL = 'http';
        if (isset($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] == "on") {$pageURL .= "s";}
        $pageURL .= "://";
        if ($_SERVER["SERVER_PORT"] != "80") {
            $pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
        } else {
            $pageURL .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
        }
        return $pageURL;
    }
    
    
    public static function get_inner_html( $node ) {
        $innerHTML= '';
        $children = $node->childNodes;
        foreach ($children as $child) {
            $innerHTML .= $child->ownerDocument->saveXML( $child );
        }
        
        return $innerHTML;
    }
    
    public static function getInnerHTMLByID($id, $html) {
        //	$innerHTML = '';
        $dom = new \DOMDocument;
        libxml_use_internal_errors(true);
        $searchPage = mb_convert_encoding($html, 'HTML-ENTITIES', "UTF-8");
        $dom->loadHTML($searchPage);
        $node = $dom->getElementById($id);
        return self::get_inner_html($node);
    }
    
    public static function getHTMLByID($id, $html) {
        $dom = new \DOMDocument();
        libxml_use_internal_errors(true);
        $searchPage = mb_convert_encoding($html, 'HTML-ENTITIES', "UTF-8");
        $dom->loadHTML($searchPage);
        $node = $dom->getElementById($id);
        if ($node) {
            return $dom->saveXML($node);
        }
        return FALSE;
    }
    public static function getHTMLByClass($class, $html) {
        $dom = new \DomDocument();
        libxml_use_internal_errors(true);
        $searchPage = mb_convert_encoding($html, 'HTML-ENTITIES', "UTF-8");
        $dom->loadHTML($searchPage);
        $classname = $class;
        $finder = new \DomXPath($dom);
        $nodes = $finder->query("//*[contains(concat(' ', normalize-space(@class), ' '), ' $classname ')]");
        
        $tmp_dom = new \DOMDocument();
        foreach ($nodes as $node)
        {
            $tmp_dom->appendChild($tmp_dom->importNode($node,true));
            
        }
        $innerHTML = html_entity_decode(trim($tmp_dom->saveHTML()),ENT_COMPAT,'UTF-8');
        return $innerHTML;
    }
    
    public static function removeHTMLById($id, $html){
        $doc = new \DOMDocument();
        libxml_use_internal_errors(true);
        $searchPage = mb_convert_encoding($html, 'HTML-ENTITIES', "UTF-8");
        $doc->loadHTML($searchPage);
        $element = $doc->getElementById($id);
        $element->parentNode->removeChild($element);
        $html_new = $doc->saveHTML();
        return $html_new;
    }
    public static function get_site_name($url){
        global $memcache;
        include_once (PACKAGE_LIBS.'utf8/utf8.php');
        $official_sites = get_official_site_array();
        if(!empty($official_sites)){
            foreach ($official_sites as $site){
                if (utf8_strpos(strtolower($url),$site["domain"])) {
                    return $site["name"];
                }
            }
        }
        return get_url_domain($url);
    }
    
    function get_official_site_array(){
        global $memcache;
        $sites=null;
        if(connect_memcache()){
            $sites = $memcache->get("linkhay_official_site_array");
        }
        $sites=null;
        if($sites==null){
            if (file_exists ( official_sites_file )) {
                $handle = fopen ( official_sites_file, "r" );
                require_once(PACKAGE_LIBS.'utf8/mbstring/core.php');
                require_once(PACKAGE_LIBS.'utf8/trim.php');
                while (!feof($handle)) {
                    $line = fgets($handle, 4096);
                    $pos=utf8_strpos($line,"=");
                    if($pos===false)
                        continue;
                        $site=utf8_trim(utf8_substr($line,0,$pos));
                        $name=utf8_trim(utf8_substr($line,$pos+1));
                        $sites[]= array("domain"=>$site,"name"=>$name);
                }
                fclose($handle);
            }
            $memcache->set("linkhay_official_site_array",$sites,MEMCACHE_COMPRESSED,24*60*60);
        }
        return $sites;
    }
    
    /**
     * get title of a page using curl
     *
     */
    function getSiteTitleByURL($url){
        try{
            // create a new cURL resource
            $ch = curl_init();
            // set URL and other appropriate options
            curl_setopt($ch, CURLOPT_URL, $url);
            //curl_setopt($ch, CURLOPT_HEADER, true);
            curl_setopt($ch, CURLOPT_HEADER, false);
            curl_setopt($ch, CURLOPT_MAXREDIRS, 10);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
            curl_setopt($ch, CURLOPT_ENCODING, "");
            curl_setopt($ch, CURLOPT_RETURNTRANSFER,true);
            curl_setopt($ch, CURLOPT_USERAGENT,"spider");
            curl_setopt($ch, CURLOPT_AUTOREFERER,true);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT,120);
            curl_setopt($ch, CURLOPT_TIMEOUT,120);
            
            // grab URL and pass it to the browser
            $content = curl_exec($ch);
            if(!$content){
                throw new \Exception("Could not get file content!");
            }
            $re = "/(?i)<title[^>]*>(.*?)<\/title>/sm";
            if(preg_match($re, $content, $matches)) {
                $url_title = trim($matches[1]);
            }else{
                $url_title = $url;
            }
            // close cURL resource, and free up system resources
            curl_close($ch);
        } catch(\Exception $ex){
            return $url;
        }
        return $url_title;
    }
    
    /**
     * Get the real link of a given URL
     * - A "real link" is a link that exclude all chars after # and all pre-fix likes *://www*.
     * - Use to check duplicate Link submission
     * @param url
     */
    function get_real_link($url) {
        $result = trim($url);
        //remove any character after #
        $sharpPos = strpos($result,"#");// find last "#"
        if ($sharpPos!==false) {
            $result = substr($url,0,$sharpPos);
        }
        
        //prefix to be cut off
        $wwwPos = strpos($result,"//www");// find first "//www"
        if ($wwwPos!==false) {//has "//www" then remove any chars before first dot
            $firstDotPos = strpos($result,".");
            $result = substr($result,$firstDotPos+1,strlen($result));
        } else {// does not has "//www" then remove any chars before "//" and also remove "//"
            $dbSlashPos = strpos($result,"//");// find first "//"
            if ($dbSlashPos!==false) {
                $result = substr($result,$dbSlashPos+2,strlen($result));
            }
        }
        return $result;
    }
    
    function get_real_link2($url) {
        $result = trim($url);
        //remove any character after #
        $sharpPos = strpos($result,"#");// find last "#"
        if ($sharpPos!==false) {
            $result = substr($url,0,$sharpPos);
        }
        //remove any character after ?
        $sharpPos = strpos($result,"?");// find last "?"
        if ($sharpPos!==false) {
            $result = substr($url,0,$sharpPos);
        }
        //prefix to be cut off
        $wwwPos = strpos($result,"//www");// find first "//www"
        if ($wwwPos!==false) {//has "//www" then remove any chars before first dot
            $firstDotPos = strpos($result,".");
            $result = substr($result,$firstDotPos+1,strlen($result));
        } else {// does not has "//www" then remove any chars before "//" and also remove "//"
            $dbSlashPos = strpos($result,"//");// find first "//"
            if ($dbSlashPos!==false) {
                $result = substr($result,$dbSlashPos+2,strlen($result));
            }
        }
        return $result;
    }
    
    function add_white_list($domain) {
        $domain = "\n" . $domain;
        $file = fopen(white_list_domain,"a");
        fwrite($file,$domain);
        fclose($file);
    }
    
    /**
     * Rewrite get_headers() function using curl
     *
     * 			0 if fail or URL is invalid,
     * 			$header array if success
     */
    function getSiteHeader2($url,$connection_time_out=10,$execution_time_out=20 ) {
        //require_once PACKAGE_UTILS.'NetUtils.php';
        // set maximum excution time to 2 minutes
        ini_set("max_execution_time","120");
        
        include_once PACKAGE_UTILS."ValidateUtils.php";
        if (!is_valid_url($url)) {// check for valid URL form first
            return 0;
        }
        try {
            $url_info = parse_url($url);
            if (isset($url_info['scheme']) && $url_info['scheme'] == 'https') {
                $port = 443;
                @$fp=fsockopen('ssl://'.$url_info['host'], $port, $errno, $errstr, $connection_time_out);
            } else {
                $port = isset($url_info['port']) ? $url_info['port'] : 80;
                @$fp = fsockopen($url_info['host'], $port, $errno, $errstr, $connection_time_out);
            }
            if($fp) {
                $head = "HEAD ".@$url_info['path']."?".@$url_info['query'];
                $head .= " HTTP/1.0\r\nHost: ".@$url_info['host']."\r\n\r\n";
                fputs($fp, $head);
                //set options to this stream
                stream_set_blocking($fp, TRUE);
                stream_set_timeout($fp,$execution_time_out);
                $info = stream_get_meta_data($fp);
                while((!feof($fp))&& (!$info['timed_out'])) {
                    try {
                        $header = trim(fgets($fp, 4096));
                        $info = stream_get_meta_data($fp);
                        if($header) {
                            $sc_pos = strpos( $header, ':' );
                            if( $sc_pos === false ) {
                                $headers['status'] = $header;
                            } else {
                                $label = substr( $header, 0, $sc_pos );
                                $value = substr( $header, $sc_pos+1 );
                                $headers[$label] = trim($value);
                            }
                        }
                    } catch(\Exception $e) {
                        return 0;
                    }
                }
                fclose($fp);
                //throw exception if exists
                if ($info['timed_out']) {
                    throw new \Exception("Connection timeout when getting data from ".$url);
                    //if time-out, return 1
                    return 1;
                }
                return $headers;
            } else {
                //if can not connect to the url, return 0
                return 0;
            }
        } catch(\Exception $ex){
            return 0;
        }
    }
    
    /**
     * Make a tinyurl for a link
     */
    function make_tinyurl($url) {
        try {
            $html = file_get_contents("http://tinyurl.com/create.php?url=".$url);
            preg_match('/http:\/\/preview\.tinyurl\.com\/(.*)<\/b>/', $html, $matches);
            return "http://tinyurl.com/".$matches[1];
        } catch (\Exception $ex) {
            return	$url;
        }
    }
    
    /**
     * Reverse a link from it's tinyurl
     */
    function reverse_tinyurl($url){
        $url = explode('.com/', $url);
        $url = 'http://preview.tinyurl.com/'.$url[1];
        $preview = file_get_contents($url);
        preg_match('/redirecturl" href="(.*)">/', $preview, $matches);
        return $matches[1];
    }
    
    /**
     * Make is.gd URL
     */
    function make_isgd_url($url) {
        try {
            $ch = curl_init();
            $timeout = 5;
            curl_setopt($ch,CURLOPT_URL,'http://is.gd/api.php?longurl='.$url);
            curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
            curl_setopt($ch,CURLOPT_CONNECTTIMEOUT,$timeout);
            $content = curl_exec($ch);
            curl_close($ch);
            
            if ($content=="Error: database query failed") {
                $content = $url;
            }
            return $content;
            //return str_replace("http://","",$content);
        } catch (\Exception $ex) {
            return	$url;
        }
    }
    
    public static function downloadFileCurl($url, $file){
        // (A) SETTINGS
        set_time_limit(0); // No script timeout, if expecting large download
        $source = $url; // Target file to download
        $destination = $file; // Save to this file
        $timeout = 30; // 30 seconds CURL timeout, increase if downloading large file
        
        // (B) FILE HANDLER
        $fh = fopen($destination, "w") or die("ERROR opening " . $destination);
        
        // (C) CURL INIT
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $source);
        curl_setopt($ch, CURLOPT_FILE, $fh);
        curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
        
        // (D) CURL RUN
        curl_exec($ch);
        if (curl_errno($ch)) {
            // (D1) CURL ERROR
            echo "CURL ERROR - " . curl_error($ch);
        } else {
            // (D2) CURL OK
            // NOTE: HTTP STATUS CODE 200 = OK
            // BAD DOWNLOAD IF SERVER RETURNS 401 (UNAUTHORIZED), 403 (FORBIDDEN), 404 (NOT FOUND)
            $status = curl_getinfo($ch);
            // print_r($status);
            echo $status["http_code"] == 200 ? "OK" : "ERROR - " . $status["http_code"] ;
        }
        
        // (D3) CLOSE CURL & FILE
        curl_close($ch);
        fclose($fh);
    }
    
    public static function downloadFileLarge($url, $file){
        // (A) SETTINGS
        set_time_limit(0);
        $source = $url;
        $destination = $file;
        $block = 4096; // Read 4096 bytes per block to prevent memory
        
        // (B) FILE HANDLERS
        $sh = fopen($source, "rb") or die("ERROR opening $source");
        $dh = fopen($destination, "w") or die("ERROR opening $destination");
        
        // (C) DOWNLOAD, BLOCK-BY-BLOCK
        while (!feof($sh)) {
            if (fwrite($dh, fread($sh, $block)) === false) { echo "FWRITE ERROR."; }
            flush();
        }
        // (D) DONE!
        echo "DONE.";
        fclose($sh);
        fclose($dh);
    }
    
    public static function downloadFileSimple($url, $file){
        // (A) SETTINGS
        $source = $url;
        // var_dump($source);die;
        $destination = $file;
        try {
            file_put_contents($destination, file_get_contents($source));
        }
        catch (Exception $e) {
            return false;
        }
        
        
        return 1;
    }
    
    
    
    public static function uploadFileCurl($target_url, $file_name_with_full_path, $post_arr){
        if (function_exists('curl_file_create')) { // php 5.5+
            $cFile = curl_file_create($file_name_with_full_path);
        } else { //
            $cFile = '@' . realpath($file_name_with_full_path);
        }
        $post_arr['upload'] = $cFile;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $target_url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post_arr);
        $result = curl_exec ($ch);
        curl_close ($ch);
        
        return $result;
    }
    
    public static function receiverFileFromClient($uploadpath){
        // SERVER B - RECEIVE FILE UPLOAD
        // Nhận thông tin
        $filedata = $_FILES['upload']['tmp_name'];
        $filename = $_FILES['upload']['name'];
        
        if ($filedata != '' && $filename != ''){
            // Dùng hàm copy để lưu vào thay vì hàm move_upload_file như thông thường
            copy($filedata, $uploadpath . $filename);
            return $filename;
        }
        
    }
    
    public static function get_client_ip() {
        $ipaddress = '';
        if (getenv('HTTP_CLIENT_IP'))
            $ipaddress = getenv('HTTP_CLIENT_IP');
            else if(getenv('HTTP_X_FORWARDED_FOR'))
                $ipaddress = getenv('HTTP_X_FORWARDED_FOR');
                else if(getenv('HTTP_X_FORWARDED'))
                    $ipaddress = getenv('HTTP_X_FORWARDED');
                    else if(getenv('HTTP_FORWARDED_FOR'))
                        $ipaddress = getenv('HTTP_FORWARDED_FOR');
                        else if(getenv('HTTP_FORWARDED'))
                            $ipaddress = getenv('HTTP_FORWARDED');
                            else if(getenv('REMOTE_ADDR'))
                                $ipaddress = getenv('REMOTE_ADDR');
                                else
                                    $ipaddress = 'UNKNOWN';
        return $ipaddress;
    }
    
    
    
}



/**
 * Get all Source name of some Official Media sites
 * from Dictionary
 */

?>