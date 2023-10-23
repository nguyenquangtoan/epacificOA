/* Update 23:51 06/08/2023 */
$(document).ready(function() {
    js_onload ();
    
})

function js_onload (){
    path = getPathScript();
    
    var script = document.createElement('script');
    script.src = path + 'loginbox_component_layout.js?v=' + Math.random();
    document.head.appendChild(script);
    console.log('path:' + path + 'loginbox_component_layout.js');

}

function getPathScript(){
    scripts = document.querySelectorAll('script[src*="loginbox_component.js"]');
    var url = scripts[0].src;
    var path = url.replace("loginbox_component.js", "");
    console.log("scripts:" + path);
    return path;
}


