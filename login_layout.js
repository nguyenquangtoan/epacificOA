$(function () {
  mainLoad();
});

function mainLoad() {
  path = getPathScript();
  const div1 = document.createElement("div");
  div1.innerHTML =
    `
    <link rel='stylesheet' href='` +
    path +
    `login.css'>
  
    <div id="ai_body_chat" style="display: none">
        <div id="chat-circle" class="btn btn-raised" style=""><div id="chat-overlay"></div></div>
        <div id="chat-box" data-v-1a213425="" class="chat-view chat-box" style="--chat-view-font-size: 14px; background: #fff; display: none;">
        </div>
    </div>`;

  document.body.appendChild(div1);

  loadValue();

  token = getCookie(token_name);
  refresh_token = getCookie(refresh_token_name);

  if (token != "" && token != undefined) {
    validateToken();
    info = parseJwt(token);
    saveMoreAccounts(token, refresh_token);
  }

  if (info) {
    user_name = info.given_name;
    user_email = info.email;
    user_first_name = info.given_name;
    user_last_name = info.family_name;
  }

  var ai_chatbox_avatars = {
    header_avatar: path + "dongthap.jpg",
    powerby_avatar: path + "power.png",
    vistor_avatar: path + "user.png",
    ai_avatar: path + "answer.png",
    buddle_avatar: path + "logochat.png",
  };

  ai_initLogin(ai_chatbox_avatars, "", "");
}

function ai_viewBuddle(id_div, type, image, title) {
  var html = "";
  var top_login_image =
    `
        <button class="top_logout_button" onclick="location.href = '` +
    sign_in_url +
    `'">    
                Sign In
        </button>
    `;
  console.log("token check sigin:");
  console.log(token);
  if (token && token != "undefined") {
    ai_buddle.style.width = "90px";
    top_login_image =
      `
            <div class="top_login_image" onclick="ai_openAccountBox();">
                <div>
                    <img id="top_account_icon" src="` +
      user_avatar +
      `" style="width:36px; height:36px;padding:2px;">
                </div>
            </div>`;
    arvatar_auto_gen = 1;
    if (arvatar_auto_gen == 1) {
      user_avatar = getAvatarAuto(user_first_name + user_last_name);
      top_login_image =
        `
                    <div class="top_login_image" onclick="ai_openAccountBox();">
                        <div>
                            <img id="top_account_icon" src="` +
        user_avatar +
        `" style="width:36px; height:36px;padding:2px;border-radius:20px;">
                        </div>
                    </div>`;
    }
  }

  html =
    top_login_image +
    `
                <div class="top_login_image">
                    <div style="">       
                        <img id="top_menu_icon" src="` +
    menu_logo +
    `" style="width:24px; height:24px;padding:8px;" onclick="ai_openMenuBox();">
                    </div>
                </div>
                <div class="clear-both"></div>
        `;
  ai_buddle.innerHTML = html;
}

function ai_scalingMenuBox() {
  // ai_chat_box.style.width = '100%';
  ai_chat_box.style.minWidth = "200px";
  ai_chat_box.style.maxWidth = "500px";
  ai_chat_box.style.width = "240px";
  ai_chat_box.style.height = "250px";
  ai_chat_box.style.backgroundColor = "#F8FAFD";
  ai_chat_box.style.border = "10px solid #E9EEF6";

  var top_account_iframe = document.getElementById("top_component_iframe");
  console.log("width:" + ai_chat_box.style.width);
  console.log("height:" + ai_chat_box.style.width);

  top_account_iframe.width = "100%";
  top_account_iframe.height = "100%";
  top_account_iframe.style.border = "0";
  top_account_iframe.style.margin = "0";
  top_account_iframe.style.padding = "0";
}

function ai_openMenuBox() {
  // ai_buddle.style.display = 'none';
  box_display = ai_chat_box.style.display;
  if (box_display != "block" || ai_box_login != "MenuBox") {
    ai_chat_box.style.display = "block";
    var html = "";
    html =
      `
        <iframe id="top_component_iframe" src="` +
      top_component_url +
      `?protocol=` +
      location.protocol +
      `&host=` +
      location.host +
      `&port=` +
      location.port +
      `" title="description" allowfullscreen
            style="background:transparent;margin:10px;"
        ></iframe>
        `;
    ai_chat_box.innerHTML = html;
    ai_scalingMenuBox();
    ai_box_login = "MenuBox";
  } else {
    ai_chat_box.style.display = "none";
    ai_box_login = "";
  }
}

function ai_scalingAccountBox() {
  // ai_chat_box.style.width = '100%';
  ai_chat_box.style.width = "400px";

  ai_chat_box.style.bottom = "20px";
  ai_chat_box.style.height = "auto";
  ai_chat_box.style.backgroundColor = "#F8FAFD";
  ai_chat_box.style.border = "1px solid #E9EEF6";
  ai_chat_box.style.margin = "0px";
  ai_chat_box.style.padding = "0px";

  // ai_chat_box.style.backgroundColor = '#E7ECF4';
  var top_account_iframe = document.getElementById("top_account_iframe");
  console.log("width:" + ai_chat_box.style.width);
  console.log("height:" + ai_chat_box.style.width);

  top_account_iframe.width = "100%";
  top_account_iframe.height = "100%";
  top_account_iframe.style.border = "0";
  top_account_iframe.style.margin = "0";
  top_account_iframe.style.padding = "0";
}

function ai_openAccountBox() {
  // ai_buddle.style.display = 'none';
  console.log("port:" + location.port);

  box_display = ai_chat_box.style.display;
  if (box_display != "block" || ai_box_login != "AccountBox") {
    ai_chat_box.style.display = "block";
    var html = "";
    html =
      `
        <iframe id="top_account_iframe" src="` +
      top_account_url +
      `?protocol=` +
      location.protocol +
      `&host=` +
      location.host +
      `&port=` +
      location.port +
      `" title="description" allowfullscreen
            style="background:transparent;margin:10px;"
        ></iframe>
        `;
    ai_chat_box.innerHTML = html;
    ai_scalingAccountBox();
    ai_box_login = "AccountBox";
  } else {
    ai_chat_box.style.display = "none";
    ai_box_login = "";
  }
}

function ai_initLogin(avatars, project, project_chatbot) {
  var ai_body_chat = document.getElementById("ai_body_chat");
  ai_body_chat.style.display = "block";
  ai_viewBuddle(
    "chat-overlay",
    project_chatbot.widget_bubble_type,
    avatars.buddle_avatar,
    project_chatbot.widget_bubble_title
  );
}

function topAccountBoxScroll() {
  var top_account_box = document.getElementById("top_account_box");
  var top_account_user_name_scroll = document.getElementById(
    "top_account_user_name_scroll"
  );
  var top_account_user_name = document.getElementById("top_account_user_name");

  if (top_account_box.scrollTop == 0) {
    top_account_user_name_scroll.style.display = "none";
    top_account_user_name.style.display = "block";
  } else {
    top_account_user_name_scroll.style.display = "block";
    top_account_user_name.style.display = "none";
  }
}

window.addEventListener("message", recAccountBox, true);
window.addEventListener("click", closeAccountBoxFromOtherClick, true);

function recAccountBox(event) {
  if (/^react-devtools/gi.test(event.data.source)) {
    return;
  }
  console.log(event.origin);
  if (event.origin != "http" + ssl + "://" + domain_name) {
    return;
  }

  console.log(event.data);
  if (event.data == "close_iframe_account") {
    ai_chat_box.style.display = "none";
    ai_box_login = "";
    // validateToken();
    // refreshToken();
  } else if (event.data == "sigout_iframe_account") {
    sigout();
    console.log("reload");
  } else if (event.data == "sigin_iframe_account") {
    mainLoad();
    ai_chat_box.style.display = "none";
    ai_box_login = "";
  } else if (event.data == "redirect_account_iframe_component") {
    window.location.href = menu_item.account.link;
  } else if (event.data == "redirect_data_studio_iframe_component") {
    window.location.href = menu_item.data_studio.link;
  } else if (event.data == "redirect_workflow_ai_iframe_component") {
    window.location.href = menu_item.workflow_ai.link;
  } else if (event.data == "redirect_omni_agents_iframe_component") {
    window.location.href = menu_item.omni_agents.link;
  } else if (event.data == "add_account_iframe_account") {
    sigout();
  }
}

function closeAccountBoxFromOtherClick(event) {
  if (
    event.target.id != "top_menu_icon" &&
    event.target.id != "top_account_icon"
  ) {
    ai_chat_box.style.display = "none";
    ai_box_login = "";
  }
}

function sigout() {
  ai_chat_box.style.display = "none";
  ai_box_login = "";

  const p_access_token = getCookie(token_name);
  const p_refresh_token = getCookie(refresh_token_name);

  console.log("p_access_token:" + p_access_token);
  console.log("p_refresh_token:" + p_refresh_token);

  var details = {
    client_id: "workflowai",
    refresh_token: p_refresh_token,
  };

  var formBody = [];
  for (var property in details) {
    var encodedKey = encodeURIComponent(property);
    var encodedValue = encodeURIComponent(details[property]);
    formBody.push(encodedKey + "=" + encodedValue);
  }
  formBody = formBody.join("&");

  fetch(
    "https://identity-stg.epacific.net/realms/epacific/protocol/openid-connect/logout",
    {
      method: "POST",
      headers: {
        "Content-Type": "application/x-www-form-urlencoded;",
        Authorization: "Bearer " + p_access_token,
      },
      body: formBody,
    }
  ).then(function () {
    setCrossSubdomainCookie(token_name, token, -30, main_domain_name);
    setCrossSubdomainCookie(
      refresh_token_name,
      refresh_token,
      -30,
      main_domain_name
    );
    localStorage.removeItem("token");
    // localStorage.removeItem("refreshToken");
    location.reload();
  });
}

function refreshToken() {
  const p_refresh_token = getCookie(refresh_token_name);
  console.log("p_refresh_token:" + p_refresh_token);

  var details = {
    client_id: "workflowai",
    refresh_token: p_refresh_token,
    grant_type: "refresh_token",
  };

  var formBody = [];
  for (var property in details) {
    var encodedKey = encodeURIComponent(property);
    var encodedValue = encodeURIComponent(details[property]);
    formBody.push(encodedKey + "=" + encodedValue);
  }
  formBody = formBody.join("&");

  fetch(
    "https://identity-stg.epacific.net/realms/epacific/protocol/openid-connect/token",
    {
      method: "POST",
      headers: {
        "Content-Type": "application/x-www-form-urlencoded",
      },
      body: formBody,
    }
  )
    .then((response) => response.json())
    .then((data) => {
      console.log("access_token:");
      console.log(data.access_token);
      console.log("refresh_token:");
      console.log(data.refresh_token);
      setCrossSubdomainCookie(
        token_name,
        data.access_token,
        30,
        main_domain_name
      );
      token = data.access_token;
      setCrossSubdomainCookie(
        refresh_token_name,
        data.refresh_token,
        30,
        main_domain_name
      );
      refresh_token = data.refresh_token;
      location.reload();
      return true;
    })
    .catch((error) => {
      console.log("refresh token error:");
      setCrossSubdomainCookie(token_name, token, -30, main_domain_name);
      setCrossSubdomainCookie(
        refresh_token_name,
        refresh_token,
        -30,
        main_domain_name
      );
      localStorage.removeItem("token");
      // localStorage.removeItem("refreshToken");
      location.reload();
    });
}

function validateToken() {
  info = parseJwt(token);
  console.log(info);
  if (!info) {
    return false;
  }
  console.log(info.exp);
  const now = new Date();
  const now_time = Math.ceil(now.getTime() / 1000);
  console.log(now_time);
  if (now_time <= info.exp) {
    console.log("token validate: ok");
    return true;
  } else {
    refreshToken();
  }
}
