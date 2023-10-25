$(function(){
    js_onload();
})

function js_onload (){
    path = getPathScript();
    var script = document.createElement('script');
    script.src = path + 'login_layout.js?v=' + Math.random();
    document.head.appendChild(script);
    console.log('path:' + path + 'login_layout.js');
}

function getPathScript(){
    scripts = document.querySelectorAll('script[src*="login_prod.js"]');
    var url = scripts[0].src;
    var path = url.replace("login_prod.js", "");
    console.log("scripts:" + path);
    return path;
}
