$(document).ready(function() {
    mainLoad();
    loadValue();
    token = getCookie(token_name);

    const urlParams = new URLSearchParams(window.location.search);
    const host = urlParams.get('host');
    const protocol = urlParams.get('protocol');
    const port = urlParams.get('port');

    orgin_domain = protocol + '//' + host + ':' + port;
    console.log('orgin_domain:' + orgin_domain);
    
    if(token){
        info = parseJwt(token);
        getOtherAccounts(token);
    }
    
    if(info){
        user_name = info.given_name;
        user_email = info.email;
        user_first_name = info.given_name;
        user_last_name =  info.family_name;
        user_avatar = getAvatarAuto(user_first_name + user_last_name); 
    }

    var ai_chatbox_avatars = {
        header_avatar: path + "dongthap.jpg",
        powerby_avatar: path + "power.png",
        vistor_avatar: path + "user.png", 
        ai_avatar: path + "answer.png",
        buddle_avatar: path + 'logochat.png'
    };

    ai_initLogin(ai_chatbox_avatars, '', '');
})

function mainLoad(){
    path = getPathScript();
    const div1 = document.createElement('div');
    div1.innerHTML = `
    <link rel='stylesheet' href='` + path + `login-commons.css'>
    <link rel='stylesheet' href='` + path + `login-menu.css'>
  
    <div id="ai_body_chat" style="display: block">
        <div id="chat-box" data-v-1a213425="" class="chat-view chat-box" style="--chat-view-font-size: 14px; background: #fff; display: block;">
        </div>
    </div>`;

    document.body.appendChild(div1);
}

function ai_scalingAccountBox(){
    // ai_chat_box.style.width = '100%';
    ai_chat_box.style.border = '0';
    ai_chat_box.style.bottom = '0px';
    ai_chat_box.style.height = 'auto';
    ai_chat_box.style.backgroundColor = '#E7ECF4';   
    ai_chat_box.style.borderShadow = '0';
    ai_chat_box.style.borderRadius = '0';
}

function ai_openAccountBox(){
    // ai_buddle.style.display = 'none';
    box_display = ai_chat_box.style.display;
    if(box_display != 'block'  || ai_box_login != 'AccountBox'){
        ai_chat_box.style.display = 'block';
        var html = '';
        html = `
            <div id="top_account_box"  class="top_account_box" onscroll="topAccountBoxScroll();">
                <button class="top_account_button_close" onclick="ai_openAccountBox()">
                    <svg viewBox="1 1 22 22" class="top_login_svg_close">
                        <path d="M19 6.41L17.59 5 12 10.59 6.41 5 5 6.41 10.59 12 5 17.59 6.41 19 12 13.41 17.59 19 19 17.59 13.41 12 19 6.41z"></path>
                    </svg>
                </button>
                <div id="top_account_user_name_scroll" class="top_account_user_name_scroll">
                    <img style="width:25px;" src="` + user_avatar + `"><span>` + user_email + `</span>
                </div>
                <div id="top_account_user_name" class="top_account_user_name">` + user_email + `</div>
                
                <div class="top_account_icon_box"><img class="top_account_icon" src="` + user_avatar + `"/></div>
                <div class="top_account_hi"> Hi, ` + user_first_name + `!</div>
                <div class="text-center">
                    <button class="top_account_manage_button">
                        Manager your Account
                    </button>
                </div>
                `

                + getBoxMoreAccount() +
                
                `<div class="top_account_signout_box" onClick="top_account_signout_click()">
                    <div>
                        <svg focusable="false" height="24" viewBox="0 0 24 24" width="24" class=" NMm5M" style="vertical-align:middle"><path d="M17 7l-1.41 1.41L18.17 11H8v2h10.17l-2.58 2.58L17 17l5-5zM4 5h8V3H4c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h8v-2H4V5z"></path><path d="M0 0h24v24H0z" fill="none"></path></svg>
                        <span >Sign out</span>
                    </div>
                    
                </div> 
            </div>
        `;
        ai_chat_box.innerHTML = html;
        ai_scalingAccountBox();
        ai_box_login = 'AccountBox';
    }else{
        ai_chat_box.style.display = 'none';
        ai_box_login = '';
        
        const p_orgin_domain = getOrginDomain();
        window.parent.postMessage("close_iframe_account", p_orgin_domain);
        console.log(orgin_domain);
        
    }
    
}

function ai_initLogin(avatars, project, project_chatbot){
    ai_openAccountBox();
}

function topAccountBoxScroll(){
    var top_account_box = document.getElementById("top_account_box");
    var top_account_user_name_scroll = document.getElementById("top_account_user_name_scroll");
    var top_account_user_name = document.getElementById("top_account_user_name");

    if(top_account_box.scrollTop == 0){
        top_account_user_name_scroll.style.display = "none";
        top_account_user_name.style.display = "block";
    }else{
        top_account_user_name_scroll.style.display = "block";
        top_account_user_name.style.display = "none";
    }
}


/* More Account */
function getBoxMoreAccount(){
    var html = ''
    html = `<div id="top_account_hide_more_accounts_box" class="top_account_hide_more_accounts_box" onclick="topMoreAccountsClick()" alt="more on">
                    <span> 
                        <span id="top_account_hide_more_accounts_head_title">Hide more Accounts</span> 
                        <div>
                        <svg id="top_account_hide_more_accounts_head_svg" focusable="false" width="22" height="22" viewBox="0 0 24 24" class="cllK4d NMm5M">
                            <path d="M12 16.41l-6.71-6.7 1.42-1.42 5.29 5.3 5.29-5.3 1.42 1.42z"></path>
                        </svg>
                        </div>
                    </span>
                    <div class="clear-both;"></div>
                </div>
                ` + listMoreAccount() + `
                <div class="top_account_add_account_box" onclick="topAddAccountClick()" >
                    <div>
                        <svg focusable="false" width="21" height="21" viewBox="0 0 24 24" class="aFCkf NMm5M" style="vertical-align:middle">
                            <path d="M20 13h-7v7h-2v-7H4v-2h7V4h2v7h7v2z"></path></svg>
                        <span >Add other account</span>
                    </div>
                </div>`;
    return html;
}

function topMoreAccountsClick(){
    var status = document.getElementById("top_account_hide_more_accounts_head_title").innerHTML;
    var round = document.getElementById("top_account_hide_more_accounts_head_svg").style.transform;
    console.log(round);
    var display = '';
    if(status == 'Hide more Accounts'){
        display = 'none';
        status = 'Show more Accounts';
        document.getElementById("top_account_hide_more_accounts_head_svg").style.transform = "rotate(180deg)";
    }else{
        display = 'block';
        status = 'Hide more Accounts';
        document.getElementById("top_account_hide_more_accounts_head_svg").style.transform = "rotate(0deg)";
    }
    for(i = 0; i < account_more.length; i ++){
        document.getElementById("top_account_more_accounts_box[" + i + "]").style.display = display;
    }
    
    document.getElementById("top_account_hide_more_accounts_head_title").innerHTML = status;
    
    
}
function removeMoreAccountClick(i, p_access_token){
    document.getElementById("top_account_more_accounts_box[" + i + "]").remove();
    removeMoreAccount(p_access_token);

}

function listMoreAccount(){
    console.log(account_more);
    var html = '';
    for(i = 0; i < account_more.length; i ++){
        more_avatar = getAvatarAuto(account_more[i].first_name + account_more[i].last_name );
        html += `<div id="top_account_more_accounts_box[` + i + `]" class="top_account_more_accounts_box">
    <div style="float:left;margin:15px 0 0 20px;width:40px;">
        <img style="width:40px; height:40px;" class="top_login_image" src="` + more_avatar + `"/>
    </div>
    <div style="float:left;margin:15px 0 0 10px;width:150px;">
        <div style="font-weight:bold">` + account_more[i].first_name + ' ' + account_more[i].last_name + `</div>
        <div style="margin-top:7px;color:#5A5D5D;font-size:12px">` + account_more[i].email + `</div>
    </div>
    <div style="float:right;margin:15px 20px 0 10px;">
        <span style="background-color:#E1E3E1;font-size:12px;display:block;padding:3px;border-radius:3px;color:#2E3433">Signed out</span>
    </div>
    <div class="clear-both"></div>
    <div style="margin-left:70px">
        <button class="other_account_sigin_button" onclick="other_account_sigin_click('` + account_more[i].token + `', '` + account_more[i].refresh_token + `')">
            Sign In
        </button>
        <button class="top_account_other_account_remove_button" onclick="removeMoreAccountClick('` + i + `', '` + account_more[i].token + `')">
            Remove
        </button>
    </div>
    </div>`;
    }
    

    return html;

}

function top_account_signout_click(){
    const p_orgin_domain = getOrginDomain();
    window.parent.postMessage("sigout_iframe_account", p_orgin_domain);
}

function other_account_sigin_click(p_access_token, p_refresh_token){
    console.log('change account accesstoken');
    console.log(p_access_token);
    console.log('change account refreshtoken');
    console.log(p_refresh_token);

    setCrossSubdomainCookie(token_name,p_access_token,30, main_domain_name);
    setCrossSubdomainCookie(refresh_token_name,p_refresh_token,30, main_domain_name);
    
    const p_orgin_domain = getOrginDomain();
    window.parent.postMessage("sigin_iframe_account", p_orgin_domain);
}

function getOrginDomain(){
    const urlParams = new URLSearchParams(window.location.search);
    const host = urlParams.get('host');
    const protocol = urlParams.get('protocol');
    const port = urlParams.get('port');

    const p_orgin_domain = protocol + '//' + host + ':' + port;
    console.log('orgin_domain:' + p_orgin_domain);
    return p_orgin_domain;
}

function topAddAccountClick(){
    const p_orgin_domain = getOrginDomain();
    window.parent.postMessage("add_account_iframe_account", p_orgin_domain);
}