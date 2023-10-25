var ai_buddle = document.getElementById("chat-circle");
var ai_chat_box = document.getElementById("chat-box");
var ai_header_avatar = document.getElementById("ai-header-avatar");
var ai_project_name = document.getElementById("ai-project-name");
var ai_footer_for_brand = document.getElementById("ai-footer-for-brand");

var ai_body_chat = document.getElementById("ai_body_chat");

var ai_chat_list = document.getElementById("ai-chat-list");
var ai_anq_button = document.getElementById("ai-answer-question-button");
var ai_search_button = document.getElementById("ai-search-button");
var ai_chat_input = document.getElementById("ai-chat-input");
var ai_button_recommemd = document.getElementById("svg-button-recommend");

var ai_box_login = "";
var account_name = "Cao Hoang Long";
var account_name = "";
var user_email = "caohoanglonghn@gmail.com";
var user_email = "";
var user_name = "caohoanglonghn";
var user_name = "";
var user_first_name = "Long";
var user_first_name = "";
var user_last_name = "Cao Hoang";
var user_last_name = "";
var token_name = "ai_open_access_token";
var refresh_token_name = "ai_open_refesh_token";

// var domain_name = 'platform.epacific.net';
var domain_name = "https://cdn.jsdelivr.net";
var main_domain_name = "epacific.net";
var orgin_domain = "";
var ssl = "s";
var root_url = domain_name + "/gh/nguyenquangtoan/epacificOA@prod/";

var top_account_url = root_url + "loginbox_account.html";
var top_component_url = root_url + "loginbox_component.html";
var user_avatar = root_url + "account.svg";
var menu_logo = root_url + "menu.svg";
var token = "";
var refresh_token = "";
var info = "";

var identity_url = "https://identity-stg.epacific.net";
var datastudio_url = "https://datastudio-stg.epacific.net";
var eflowai_url = "https://eflowai-stg.epacific.net";
var etouch_url = "https://etouch-stg.epacific.net";

var sign_in_url = identity_url;
var sign_out_url = identity_url;

var account_more = [
  {
    name: "Vu Anh Tuan",
    email: "tuananh@visc.com.vn",
    user_name: "caohoanglonghn",
    first_name: "uuu",
    last_name: "Vu Tuan",
    token: "",
    refresh_token: "",
  },
  {
    name: "Vu Anh Tuan",
    email: "aa@visc.com.vn",
    user_name: "caohoanglonghn",
    first_name: "aaa",
    last_name: "Nguyen",
    token: "",
    refresh_token: "",
  },
];

account_more = [];

var menu_item = {
  account: {
    name: "Account",
    link: identity_url,
    image: root_url + "account.svg",
    id: "account",
  },
  data_studio: {
    name: "Data Studio",
    link: datastudio_url,
    image: root_url + "datastudio.svg",
    id: "data_studio",
  },
  workflow_ai: {
    name: "Workflow AI",
    link: eflowai_url,
    image: root_url + "workflowai.svg",
    id: "workflow_ai",
  },
  omni_agents: {
    name: "Omni Agents",
    link: etouch_url,
    image: root_url + "omniagents.svg",
    id: "omni_agents",
  },
};

function loadValue() {
  ai_buddle = document.getElementById("chat-circle");
  ai_chat_box = document.getElementById("chat-box");
  ai_header_avatar = document.getElementById("ai-header-avatar");
  ai_project_name = document.getElementById("ai-project-name");
  ai_footer_for_brand = document.getElementById("ai-footer-for-brand");

  ai_chat_list = document.getElementById("ai-chat-list");
  ai_anq_button = document.getElementById("ai-answer-question-button");
  ai_search_button = document.getElementById("ai-search-button");
  ai_chat_input = document.getElementById("ai-chat-input");
  ai_like_button = document.getElementById("ai-like-button");
  ai_dislike_button = document.getElementById("ai-dislike-button");
  ai_button_recommemd = document.getElementById("svg-button-recommend");
}

function setCookie2(cname, cvalue, exdays) {
  const d = new Date();
  d.setTime(d.getTime() + exdays * 24 * 60 * 60 * 1000);
  let expires = "expires=" + d.toUTCString();
  document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
  console.log(cname);
  console.log("document.cookie");
  console.log(document.cookie);
}

function setCookie(name, value, days) {
  var expires = "";
  if (days) {
    var date = new Date();
    date.setTime(date.getTime() + days * 24 * 60 * 60 * 1000);
    expires = "; expires=" + date.toUTCString();
  }
  document.cookie = name + "=" + (value || "") + expires + "; path=/";
  console.log("document.cookie");
  console.log(document.cookie);
}

function setCrossSubdomainCookie(name, value, days, parent_domain) {
  const assign = name + "=" + escape(value) + ";";
  const d = new Date();
  d.setTime(d.getTime() + days * 24 * 60 * 60 * 1000);
  const expires = "expires=" + d.toUTCString() + ";";
  const path = "path=/;";
  const domain = "domain=." + parent_domain;
  document.cookie = assign + expires + path + domain;
}

function getCookie(cname) {
  let name = cname + "=";
  let decodedCookie = decodeURIComponent(document.cookie);
  let ca = decodedCookie.split(";");
  for (let i = 0; i < ca.length; i++) {
    let c = ca[i];
    while (c.charAt(0) == " ") {
      c = c.substring(1);
    }
    if (c.indexOf(name) == 0) {
      return c.substring(name.length, c.length);
    }
  }
  return "";
}

function deleteCookie(cname) {
  document.cookie = cname + "=; Path=/; Expires=Thu, 01 Jan 1970 00:00:01 GMT;";
}

function deleteCookieAllSubDomain(cookie_name, parentdomain) {
  document.cookie =
    cookie_name +
    "=; expires=Thu, 01 Jan 1970 00:00:01 GMT; path=/; domain=." +
    parentdomain +
    ";";
}

function generateAvatar(text, foregroundColor, backgroundColor) {
  const canvas = document.createElement("canvas");
  const context = canvas.getContext("2d");

  canvas.width = 200;
  canvas.height = 200;
  // Draw background
  context.fillStyle = backgroundColor;
  context.roundRect(0, 0, canvas.width, canvas.height, 100);
  context.fill();

  // Draw text
  context.font = "100px Arial";
  context.fillStyle = foregroundColor;
  context.textAlign = "center";
  context.textBaseline = "middle";
  context.fillText(text, canvas.width / 2, canvas.height / 2);

  return canvas.toDataURL("image/png");
}

function parseJwt(token) {
  if (token != "undefined") {
    var base64Url = token.split(".")[1];
    var base64 = base64Url.replace(/-/g, "+").replace(/_/g, "/");
    var jsonPayload = decodeURIComponent(
      window
        .atob(base64)
        .split("")
        .map(function (c) {
          return "%" + ("00" + c.charCodeAt(0).toString(16)).slice(-2);
        })
        .join("")
    );

    return JSON.parse(jsonPayload);
  }
}

function getAvatarAuto(string) {
  color = 0;
  for (j = 0; j < string.length; j++) {
    c = string.charCodeAt(j);
    console.log("c: " + c);
    if (c % 2 != 0 && color > c * 5) {
      color -= c * 5;
    } else {
      color += c * 10;
    }
  }
  color = color.toString(16);
  var short_name = string.charAt(0);
  user_avar = generateAvatar(short_name, "#fff", "#" + color);
  return user_avar;
}

function saveMoreAccounts(p_access_token, p_refresh_token) {
  if (p_access_token) {
    let data_json = localStorage.getItem("ai_login_account_list");
    let data_array = [];
    let data_array_new = [];
    let account_more_item;
    if (data_json == null) {
      data_array = [
        {
          access_token: p_access_token,
          refresh_token: p_refresh_token,
        },
      ];
    } else {
      data_array = JSON.parse(data_json);
      is_exist = false;
      for (i = 0; i < data_array.length; i++) {
        account_more_item = parseJwt(data_array[i].access_token);
        account_current = parseJwt(p_access_token);

        if (account_current && account_current != "undefined") {
          if (account_more_item && account_more_item != "undefined") {
            if (account_more_item.email != account_current.email) {
              data_array_new.push({
                access_token: data_array[i].access_token,
                refresh_token: data_array[i].refresh_token,
              });
            }
          }
        }
      }
      if (p_access_token && p_refresh_token) {
        data_array_new.push({
          access_token: p_access_token,
          refresh_token: p_refresh_token,
        });
      }
    }
    localStorage.setItem(
      "ai_login_account_list",
      JSON.stringify(data_array_new)
    );
  }
}

function getOtherAccounts(p_access_token) {
  let data_json = localStorage.getItem("ai_login_account_list");
  let data_array = [];
  let account_more_item;
  account_more = [];
  if (data_json != null) {
    data_array = JSON.parse(data_json);
    for (i = 0; i < data_array.length; i++) {
      account_more_item = parseJwt(data_array[i].access_token);
      account_current = parseJwt(p_access_token);
      if (account_more_item.email != account_current.email) {
        account_more.push({
          name: info.given_name,
          email: account_more_item.email,
          user_name: account_more_item.given_name,
          first_name: account_more_item.given_name,
          last_name: account_more_item.family_name,
          token: data_array[i].access_token,
          refresh_token: data_array[i].refresh_token,
        });
      }
    }
  }
}

function removeMoreAccount(p_access_token) {
  let data_json = localStorage.getItem("ai_login_account_list");
  let data_array = [];
  let data_array_new = [];
  let account_more_item;
  account_current_1 = parseJwt(p_access_token);
  console.log(account_current_1.email);

  account_more = [];
  if (data_json != null) {
    data_array = JSON.parse(data_json);
    for (i = 0; i < data_array.length; i++) {
      account_more_item = parseJwt(data_array[i].access_token);
      console.log(account_more_item.email);

      if (data_array[i].access_token != p_access_token) {
        data_array_new.push(data_array[i]);
      }
    }
  }
  localStorage.setItem("ai_login_account_list", JSON.stringify(data_array_new));
}
