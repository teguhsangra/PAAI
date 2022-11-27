var url=window.location.href;
if (url.includes('meeting'))
    url = url.replace(location.search, "")

var hotspot_hidden = false;
var combobox_open = false;
/*Mixpanel event
mixpanel.track("space_url_loaded", {
    "url": url,
    "distinct_id": space_name
});*/

var krpano = document.getElementById("krpanoViewer");
var defaultShowMenu = 'false';
query_params = {};
const urlParams = new URLSearchParams(location.search);
for (const [key, value] of urlParams) {
    if ((key == 'showmenu') && (value == 'true'))
        defaultShowMenu = true;
    else if(key == 'tourview'){
        if (value == 'pannini')
            krpano.call('skin_view_pannini()');
        else if (value == 'architectural')
            krpano.call('skin_view_architectural()');
        else if (value == 'fisheye')
            krpano.call('skin_view_fisheye();');
        else if (value == 'stereographic')
            krpano.call('skin_view_stereographic()');
        else if(value == 'littleplanet')
            krpano.call("skin_view_littleplanet();");
        else
            krpano.call('skin_view_normal()');
    }
}

function set_default_menu_visibility(){
    if (defaultShowMenu == 'false'){
        var krpano = document.getElementById("krpanoViewer");
        krpano.call('hide_bottom()');
    }
}

function set_space_url_js(){
    var krpano = document.getElementById("krpanoViewer");
    krpano.call("set(layer[space_url].html, "+url+");");
}

function copy_to_clipboard(){
    var tempInput = document.createElement("input");
    tempInput.style = "position: absolute; left: -1000px; top: -1000px";
    tempInput.value = url;
    document.body.appendChild(tempInput);
    tempInput.select();
    document.execCommand("copy");
    document.body.removeChild(tempInput);

    var krpano = document.getElementById("krpanoViewer");
}

function set_screen_space_name_js(){
    var space_name = document.head.querySelector("[name=space_name]").content;
    var krpano = document.getElementById("krpanoViewer");
    krpano.call("set(layer[space_name].html, "+space_name+");");
}

function set_screen_space_desc_js(){
    var space_desc = document.head.querySelector("[name=space_desc]").content;
    var krpano = document.getElementById("krpanoViewer");
    krpano.call("set(layer[space_desc].html, "+space_desc+");");
}

function sendViewPoints(){
	if ((typeof user_type === "undefined") || (user_type == 'member'))
		return;
    var krpano = document.getElementById("krpanoViewer");
    var hVal= krpano.get("view.hlookat");
    var vVal= krpano.get("view.vlookat");
    var data = { type:'view_points', h_val: hVal, v_val: vVal};
    //Firebase push view
    firebase_send_view(data);
}

function sendNewScene(){
    if (hotspot_hidden)
       hideHotSpots();
    else
       showHotSpots();
    if (typeof user_type === "undefined")
        return;
    else if(user_type == 'member'){
        disable_all_hotspot();
        return;
    }
    //Firebase push new scene
    var krpano = document.getElementById("krpanoViewer");
    var scene_name = krpano.get("xml.scene");
    firebase_send_new_scene(scene_name);

   //hide_scene_combobox();
}

function hideHotSpots(){
    var krpano = document.getElementById("krpanoViewer");
    krpano.call("set(layer[btn_hi].url, '%CURRENTXML%/skin/sentio/show_info.png')");
    var hotspot_count = krpano.get("hotspot.count");
    i = 0;
    while(i < hotspot_count){
        hs_name = krpano.get("hotspot["+i+"].name");
        if (hs_name.startsWith('hs') || hs_name.startsWith('note') || (hs_name.startsWith('audio')) || (hs_name.startsWith('video'))){
            krpano.call("set(hotspot[" + hs_name + "].visible, false);");
            //krpano.call("set(hotspot[" + hs_name + "].enabled, false);");
        }
        i++;
    }
}

function showHotSpots(){
    var krpano = document.getElementById("krpanoViewer");
    krpano.call("set(layer[btn_hi].url, '%CURRENTXML%/skin/sentio/hide_info.png')");
    var hotspot_count = krpano.get("hotspot.count");
    i = 0;
    while(i < hotspot_count){
        hs_name = krpano.get("hotspot["+i+"].name");
        if ((hs_name.startsWith('hs') || hs_name.startsWith('note') || hs_name.startsWith('video') || hs_name.startsWith('audio')) && (!(hs_name.endsWith('text') || (hs_name.endsWith('desc'))))){
            if((!(hs_name.endsWith('text'))) && (!(hs_name.endsWith('container'))) && (!(hs_name.endsWith('title'))) && (!(hs_name.endsWith('desc'))) && (!(hs_name.endsWith('image')))){
                krpano.call("set(hotspot[" + hs_name + "].visible, true);");
                //krpano.call("set(hotspot[" + hs_name + "].enabled, true);");
            }
        }
        i++;
    }
}

function toggle_hiding(){
   if (hotspot_hidden){
        showHotSpots();
        hotspot_hidden = false;
   }
   else{
        hideHotSpots();
        hotspot_hidden = true;
   }
}

function toggle_scene_combobox(){
	var krpano = document.getElementById("krpanoViewer");
	if (combobox_open){
		krpano.call("set(layer[cbscenes].alpha, 0)");
		combobox_open = false;
	}else{
		krpano.call("set(layer[cbscenes].alpha, 1)");
		combobox_open = true;
	}
}

function open_scene_combobox(){
	var krpano = document.getElementById("krpanoViewer");
	krpano.call("set(layer[cbscenes].alpha, '1')");
	//krpano.call("layer[cbscenes].openList()");
}

function hide_scene_combobox(){
	var krpano = document.getElementById("krpanoViewer");
	krpano.call("set(layer[cbscenes].alpha, '0')");
}

function set_webvr_fmap_visiblity(){
	 var krpano = document.getElementById("krpanoViewer");
    if (!(floormap_present)){
        krpano.call("set(hotspot[webvr_floormap].visible, false)");
        krpano.call("set(hotspot[webvr_prev_scene].ox, -30)");
        krpano.call("set(hotspot[webvr_next_scene].ox, 30)");
    }else
        krpano.call("set(hotspot[webvr_floormap].visible, true)");

}

function set_menu_icons_availability(){
	 var krpano = document.getElementById("krpanoViewer");
     floormap_present = krpano.get("layer[floormap1].enabled");
     sc_x = krpano.get("layer[btn_sc].x");
     fp_x = krpano.get("layer[btn_fp].x");
     hi_x = krpano.get("layer[btn_hi].x");
     ss_x = krpano.get("layer[btn_ss].x");
     gy_x = krpano.get("layer[btn_gy].x");
     combobox_x = krpano.get("layer[cbscenes].x");
     share_x = krpano.get("layer[share_bg].x");

    //Check floorplan availability
    if (!(floormap_present)){
        krpano.call("set(layer[btn_fp].visible, false)");
        krpano.call("set(layer[btn_fp].enabled, false)");

        sc_x -= 50; combobox_x -= 50;
    }

    is_mobile = krpano.get("device.mobile");
    if(!(is_mobile)){
        //If it mobile, then only Cardboard option and gyro option shoud be available
        krpano.call("set(layer[btn_vr].visible, false)");
        krpano.call("set(layer[btn_vr].enabled, false)");

        krpano.call("set(layer[btn_gy].visible, false)");
        krpano.call("set(layer[btn_gy].enabled, false)");

        sc_x -= 50; fp_x -= 50; hi_x -= 50; ss_x -= 50; combobox_x -= 50; share_x -= 50;
    }
    is_ios = krpano.get("device.ios");
    if(is_ios){
        //In ios, full screen option is not available
        krpano.call("set(layer[btn_fs].visible, false)");
        krpano.call("set(layer[btn_fs].enabled, false)");

        sc_x -= 50; fp_x -= 50; hi_x -= 50; ss_x -= 50; gy_x -= 50; combobox_x -= 50; share_x -= 50;
    }

    krpano.call("set(layer[btn_sc].x, "+sc_x+")");
    krpano.call("set(layer[btn_fp].x, "+fp_x+")");
    krpano.call("set(layer[btn_hi].x, "+hi_x+")");
    krpano.call("set(layer[btn_ss].x, "+ss_x+")");
    krpano.call("set(layer[btn_gy].x, "+gy_x+")");
    krpano.call("set(layer[cbscenes].x, "+combobox_x+")");
    krpano.call("set(layer[share_bg].x, "+share_x+")");
}

function setMenuIconPosition(){
	var krpano = document.getElementById("krpanoViewer");
    is_portrait = krpano.get("plugin[orientation].portrait");
    ort = "landscape";
    if (is_portrait)
        ort = "portrait";
    last_x = parseInt(krpano.get("layer[btn_sc].x"));
    last_y = parseInt(krpano.get("layer[btn_sc].y"));
    last_mode = "portrait";
    if(last_x > last_y){
        last_mode = "landscape";
    }

    btn_arr = ['btn_sc', 'btn_fp', 'btn_hi', 'btn_ss', 'btn_gy', 'btn_fs']; //It should be in exact order
    arr_length = 6;
    if(ort == "portrait"){
        if (last_mode == "portrait")
            return;
        last_x = [];
        for(var i=0; i < arr_length; i++){
            btn_x = parseInt(krpano.get("layer["+btn_arr[i]+"].x"));
            last_x.push(btn_x + (10*(arr_length-i)));  //Giving marging of extra 10 pixels in case of vertial display
            krpano.call("set(layer["+btn_arr[i]+"].x, 40);");
        }
        for(var j=0; j < arr_length; j++){
            krpano.call("set(layer["+btn_arr[j]+"].y, "+last_x[j]+");");
        }
        krpano.call("set(layer['cbscenes'].x, 75)");
        krpano.call("set(layer['cbscenes'].y, "+last_x[0]+")");

        krpano.call("set(layer['share_bg'].x, 75)");
        krpano.call("set(layer['share_bg'].y, "+last_x[3]+")");
    }else{
        if (last_mode == "landscape")
            return;
        last_y = [];
        for(var i=0; i < arr_length; i++){
            btn_y = parseInt(krpano.get("layer["+btn_arr[i]+"].y"));
            last_y.push(btn_y - (10*(arr_length-i)));  //Removing marging of extra 10 pixels in case of vertial display
            krpano.call("set(layer["+btn_arr[i]+"].y, 40);");
        }
        for(var j=0; j < arr_length; j++){
            krpano.call("set(layer["+btn_arr[j]+"].x, "+last_y[j]+");");
        }
        krpano.call("set(layer['cbscenes'].x, "+last_y[0]+")");
        krpano.call("set(layer['cbscenes'].y, 75)");

        krpano.call("set(layer['share_bg'].x, "+last_y[3]+")");
        krpano.call("set(layer['share_bg'].y, 110)");
    }
}

function set_gyro_icon(show_msg = "true"){
    //Getting called each time onloaded is called. onloaded gets called even in image change
	var krpano = document.getElementById("krpanoViewer");
    gy_enabled = krpano.get("plugin[skin_gyro].enabled");
    msg = "";
    if(gy_enabled){
        krpano.call("set(layer['btn_gy'].url, '%CURRENTXML%/skin/sentio/gyro_on.png')");
        msg = "Autorotation has been turned on"
    }else{
        krpano.call("set(layer['btn_gy'].url, '%CURRENTXML%/skin/sentio/gyro_off.png')");
        msg = "Autorotation has been turned off"
    }
    if(show_msg == "true"){
        krpano.call("set(layer['gy_msg'].html, "+msg+")");
        krpano.call("set(layer['gy_msg'].visible, true)");
        krpano.call("delayedcall(3.0, set(layer['gy_msg'].visible, false););");
    }
}

//DUMMY METHOD to match editor
function change_floormap_for_point(){
}
