<?php
class FileUtils{
    public static function readTextFile($path, $name){
        
        $filename = $path . $name;
        if (! file_exists($filename)){
            return false ;
        }
        clearstatcache();
        
        $handle = fopen($filename, "r") or die('not found file');
        
        $contents = false;
        if(filesize($filename) > 0){
            $contents = fread($handle, filesize($filename));
        }
        
        fclose($handle);
        return $contents;
    }
        
    public static function readTextFileByFullName($fullname){
        if (! file_exists($fullname)){
            return false ;
        }
        clearstatcache();
        
        $handle = fopen($fullname, "r");
        if($handle == false){
            return false;
        }
        $contents = false;
        if(filesize($fullname) > 0){
            $contents = fread($handle, filesize($fullname));
        }
        fclose($handle);
        return $contents;
    }
    
    public static function readUrlFile($url){
        $handle = fopen($url, "rb");
        $contents = stream_get_contents($handle);
        fclose($handle);
        return $contents;
    }
    
    public static function writeTextFile($path, $name, $contents){
        echo 2;
        print_r($path . $name);
        echo "\n";
        try{
            ini_set("display_errors", "on");
            $filename = $path . $name;
            $fp = fopen($filename, 'w+');
            fwrite($fp, $contents);
            fclose($fp);
            return true;
        }catch(\Exception $e){
            echo $e->getMessage();
        }
        
    }
    
    public static function rewriteTextFile($path, $name, $contents){
        $filename = $path . $name;
        if (! file_exists($filename)){
            return false ;
        }
        $fp = fopen($filename, 'w');
        fwrite($fp, $contents);
        fclose($fp);
    }
    public static function rewriteTextFileByFullName($full_name, $contents){
        if (! file_exists($full_name)){
            return false ;
        }
        $fp = fopen($full_name, 'w');
        fwrite($fp, $contents);
        fclose($fp);
    }
    
    public static function appendEndOfTextFile($path, $name, $contents){
        $fp = fopen($path . $name, 'a');
        fwrite($fp, $contents);
        fclose($fp);
    }
    
    public static function appendEndOfTextFileByFullName($full_name, $contents){
        $fp = fopen($full_name, 'a');
        fwrite($fp, $contents);
        fclose($fp);
    }
    
    public static function deleteAllFileInFolder($path){
        foreach(glob($path ."*.*") as $file) {
            unlink($file); // Delete each file through the loop
        }
    }
    
    public static function deleteFile($file){
        unlink($file); // Delete each file through the loop
    }
    
    public static function uploadFileFromPC($name,$tagert){
        if($_FILES[$name]["name"] != ""){
            move_uploaded_file($_FILES[$name]["tmp_name"], $tagert);
        }
    }
    
    public static function deleteAllFileInFolder2($dirname) {
        if (is_dir($dirname))
            $dir_handle = opendir($dirname);
            if (!$dir_handle)
                return false;
                while($file = readdir($dir_handle)) {
                    if ($file != "." && $file != "..") {
                        if (!is_dir($dirname."/".$file))
                            unlink($dirname."/".$file);
                            else
                                delete_directory($dirname.'/'.$file);
                    }
                }
                closedir($dir_handle);
                //rmdir($dirname);
                return true;
    }
    
    public static function firstFile($path){
        $firstFile = scandir($path)[2];
        return $firstFile;
    }
    
    public static function allFile($path){
        $files = scandir($path);
        $files = array_diff(scandir($path), array('.', '..'));
        return $files;
    }
    
    
    
}
