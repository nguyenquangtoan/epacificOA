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

    if(token != ''){
        info = parseJwt(token);
    }
    
    if(info != ''){
        user_name = info.given_name;
        user_email = info.email;
        user_first_name = info.given_name;
        user_last_name =  info.family_name;
        user_avatar = getAvatarAuto(user_first_name + user_last_name); 
        menu_item.account.image = user_avatar;
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
        <div id="chat-box" style="--chat-view-font-size: 14px; background: #fff; display: block;">
        </div>
    </div>`;

    document.body.appendChild(div1);
}

function ai_scalingMenuBox(){
    // ai_chat_box.style.width = '100%';
    ai_chat_box.style.minWidth = '200px';
    ai_chat_box.style.maxWidth = '500px';
    ai_chat_box.style.width = '240px';
    ai_chat_box.style.height = '250px';
    ai_chat_box.style.backgroundColor = '#F8FAFD';
    ai_chat_box.style.border = '0px';
    ai_chat_box.style.borderRadius = '0px';

}

function ai_openMenuBox(){
    // ai_buddle.style.display = 'none';
    box_display = ai_chat_box.style.display;
    if(box_display != 'block' || ai_box_login != 'MenuBox'){
        ai_chat_box.style.display = 'block';
        var html = '';
        var account_item = topMenuItem(menu_item.account);
        var omni_agents_item = topMenuItem(menu_item.omni_agents);
        var data_studio_item = topMenuItem(menu_item.data_studio);
        var workflow_ai_item = topMenuItem(menu_item.workflow_ai);
        html = account_item + data_studio_item + omni_agents_item  + workflow_ai_item;
        ai_chat_box.innerHTML = html + '<div class="clear-both"></div>';
        ai_scalingMenuBox();
        ai_box_login = 'MenuBox';
    }else{
        ai_chat_box.style.display = 'none';
        ai_box_login = ''; 
    }
    
}

function topMenuItem(item){
    var html = `<a class="top_menu_item" href="` + item.link + `" target="_top" style="color:#1B1B1D;text-decoration:none;">
                    <div class="top_menu_item_image_outbound">
                        <img class="top_menu_item_image" src="` + item.image + `"/>
                    </div>
                    <div class="top_menu_item_title" style="margin-top:10px;">
                    ` + item.name + `
                    </div>
                </a>
                `;
    return html;
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

function ai_initLogin(avatars, project, project_chatbot){
    ai_openMenuBox();
}

function top_menu_item_click(item_id){
    const p_orgin_domain = getOrginDomain();
    window.parent.postMessage("redirect_" + item_id + "_iframe_component", p_orgin_domain);
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