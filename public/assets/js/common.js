function resizing_screen(){
	var krpano = document.getElementById("krpanoViewer");
    current_width = krpano.get('stagewidth');
    ratio = current_width/1480; //Number of Amit's desktop, based on which size was set
    is_mobile = krpano.get("device.mobile");
    if(is_mobile){
        krpano.call("set(stagescale, 0.5)");
    }else{
        krpano.call("set(stagescale, "+ratio+")");
    }
}
function toggle_preview_note(note_name){
    var krpano = document.getElementById("krpanoViewer");
    note_title_hs = note_name+"_title";
    cur_visible = krpano.get("hotspot["+note_title_hs+"].visible");
    if(cur_visible){
        hide_preview_note(note_name);
    }else{
        show_preview_note(note_name);
    }
}

function hide_preview_note(note_name){
   var krpano = document.getElementById("krpanoViewer");
   note_container = note_name+"_container";
   note_title = note_name+"_title";
   note_desc = note_name+"_desc";
   note_image = note_name+"_image";
   krpano.call("set(hotspot["+note_container+"].visible, false)");
   krpano.call("set(hotspot["+note_title+"].visible, false)");
   krpano.call("set(hotspot["+note_desc+"].visible, false)");
   krpano.call("set(hotspot["+note_image+"].visible, false)");
    //FOR MEETING
	if (!(typeof user_type === "undefined") && (user_type == 'host'))
       firebase_send_action(['hide_preview_note', note_name, Date.now()]);
}

function show_preview_note(note_name){
   var krpano = document.getElementById("krpanoViewer");
   ath = krpano.get("hotspot["+note_name+"].ath");
   atv = krpano.get("hotspot["+note_name+"].atv");
   krpano.call("moveto("+ath+", "+atv+")");
   note_container = note_name+"_container";
   note_title = note_name+"_title";
   note_desc = note_name+"_desc";
   note_image = note_name+"_image";
   krpano.call("set(hotspot["+note_container+"].visible, true)");
   krpano.call("set(hotspot["+note_title+"].visible, true)");
   krpano.call("set(hotspot["+note_desc+"].visible, true)");
   krpano.call("set(hotspot["+note_image+"].visible, true)");
    //FOR MEETING
	if (!(typeof user_type === "undefined") && (user_type == 'host'))
       firebase_send_action(['show_preview_note', note_name, Date.now()]);
}

function open_note_full_view(note_container){
	var krpano = document.getElementById("krpanoViewer");

    webvr_enabled = krpano.get("webvr.isenabled");
    if(webvr_enabled)
        return;

    note_name = note_container.split('_')[0];
    note_title = note_name+'_title';
    note_desc = note_name+"_desc";
    note_image = note_name+"_image";

    image_url = krpano.get("hotspot["+note_image+"].url");
    desc_text = krpano.get("hotspot["+note_desc+"].html");
    title_text = krpano.get("hotspot["+note_title+"].html");

    krpano.call("set(layer[note_full_view_title].html, '"+title_text+"')");
    krpano.call("set(layer[note_full_view_desc].html, '"+desc_text+"')");
    //SOMEHOW JUST SETTING URL TO EMPTY STRING NOT WORKING SO PUTTING ALPHA CHECK
    if(image_url){
        krpano.call("set(layer[note_full_view_image].url, '"+image_url+"')");
        krpano.call("set(layer[note_full_view_image].alpha, 1.0)");
    }else
        krpano.call("set(layer[note_full_view_image].alpha, 0)");
    if((image_url) || (desc_text))
        krpano.call("set(layer[note_full_view].visible, true)");

    //Based on device change the y position to set the height
    is_mobile = krpano.get("device.mobile");
    if(is_mobile){
        is_portrait = krpano.get("plugin[orientation].portrait");
        if(is_portrait){
            krpano.call("set(layer[note_full_view_title].y, 10%)");
            krpano.call("set(layer[note_full_view_desc].y, 15%)");
            krpano.call("set(layer[note_full_view_image].y, 50%)");
        }else{
            krpano.call("set(layer[note_full_view_title].y, 10%)");
            krpano.call("set(layer[note_full_view_desc].y, 20%)");
            krpano.call("set(layer[note_full_view_image].y, 60%)");
        }
    }else{
        krpano.call("set(layer[note_full_view_title].y, 10%)");
        krpano.call("set(layer[note_full_view_desc].y, 15%)");
        krpano.call("set(layer[note_full_view_image].y, 50%)");
    }
    //FOR MEETING
	if (!(typeof user_type === "undefined") && (user_type == 'host'))
       firebase_send_action(['open_note_full_view', note_container, Date.now()]);
}

function close_note_full_view(){
	var krpano = document.getElementById("krpanoViewer");
    krpano.call("set(layer[note_full_view].visible, false)");
    krpano.call("set(layer[note_full_view_image].url, '')");
    krpano.call("set(layer[note_full_view_title].html, '')");
    krpano.call("set(layer[note_full_view_desc].html, '')");
    //FOR MEETING
	if (!(typeof user_type === "undefined") && (user_type == 'host'))
       firebase_send_action(['close_note_full_view', Date.now()]);
}

function show_layer_text(layer_name){
    var krpano = document.getElementById("krpanoViewer");
    var layer_target_text_name = layer_name + "_text";
    krpano.call("set(layer["+layer_target_text_name+"].visible, true)");
}

function hide_layer_text(layer_name){
    var krpano = document.getElementById("krpanoViewer");
    var layer_target_text_name = layer_name + "_text";
    krpano.call("set(layer["+layer_target_text_name+"].visible, false)");
}

function show_hs_text(hs_name){
    var krpano = document.getElementById("krpanoViewer");
    var hs_target_text_name = hs_name + "_text";
    krpano.call("set(hotspot["+hs_target_text_name+"].visible, true)");
}

function hide_hs_text(hs_name){
    var krpano = document.getElementById("krpanoViewer");
    var hs_target_text_name = hs_name + "_text";
    krpano.call("set(hotspot["+hs_target_text_name+"].visible, false)");
}

//VIDEO segment
function show_video(video_name){
    var krpano = document.getElementById("krpanoViewer");
    //Disabling download option from chrome
    var elements = document.getElementsByTagName("Video");
    for (var i=0; i<elements.length; i++) {
        elements[i].setAttribute("controlsList", "nodownload");
        elements[i].setAttribute("disablepictureinpicture", "");
    }

    video_url = "%CURRENTXML%/videos/"+video_name;

    webvr_enabled = krpano.get("webvr.isenabled");
    if(webvr_enabled){
        var cur_ath = krpano.get("view.hlookat");
        var cur_atv = krpano.get("view.vlookat");

        krpano.call("set(hotspot[video_hs].ath, "+cur_ath+")");
        krpano.call("set(hotspot[video_hs].atv, "+cur_atv+")");
        krpano.call("set(hotspot[btn_video_close_hs].ath, "+cur_ath+")");

        krpano.call("hotspot[video_hs].playvideo("+video_url+");");
        krpano.call("set(hotspot[video_hs].visible, true)");
        krpano.call("set(hotspot[btn_video_close_hs].visible, true)");
    }else{
        krpano.call("layer[video].playvideo("+video_url+");");
        //krpano.call("set(layer[video].visible, true)");
    }
    //FOR MEETING
	if (!(typeof user_type === "undefined") && (user_type == 'host'))
       firebase_send_action(['show_video', video_name, Date.now()]);
}

function resize_video(){
    var krpano = document.getElementById("krpanoViewer");
    stage_width = krpano.get('area.pixelwidth');
    stage_height = krpano.get('area.pixelheight');
    if(stage_height > stage_width){
        krpano.call("set(layer[video].height, prop);");
        krpano.call("set(layer[video].width, 80%);");
    }else{
        krpano.call("set(layer[video].height, 80%);");
        krpano.call("set(layer[video].width, prop);");
    }
    krpano.call("set(layer[video].visible, true)");
}

function hide_video(){
    var krpano = document.getElementById("krpanoViewer");
    webvr_enabled = krpano.get("webvr.isenabled");
    if(webvr_enabled){
        krpano.call("hotspot[video_hs].stop()");
        krpano.call("set(hotspot[video_hs].visible, false)");
        krpano.call("set(hotspot[btn_video_close_hs].visible, false)");
    }else{
        krpano.call("layer[video].stop()");
        krpano.call("set(layer[video].visible, false)");
    }
    //FOR MEETING
	if (!(typeof user_type === "undefined") && (user_type == 'host'))
       firebase_send_action(['hide_video', Date.now()]);
}
