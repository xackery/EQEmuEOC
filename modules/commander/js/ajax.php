<?php
	require_once('modules/commander/functions.php');
	if($_GET['GetZoneMap']){
		echo Draw2DMap($_GET['GetZoneMap']);
	}

    if($_GET['sidebar_menu']){
        if($_GET['sidebar_menu'] == "entity_search"){
            echo '
                <ul class="list-items borderless">
                    <h3 style="padding-left:10px; color:white">
                        <i class="fa fa-angle-double-right" style="font-size:20px"></i> Entity List

                    </h3><hr>
                    <li>
                        <input type="text" class="form-control" placeholder="Search entity list..." onkeypress="SearchEntity(this.value)"><hr>

                    </li>
                    <div id="entity_list"></div>
                </ul>
            ';

            echo '<script type="text/javascript">
                SearchEntity("");
            </script>';
        }
        if($_GET['sidebar_menu'] == "entity"){
            require_once('includes/constants.php');

            $race_sel = '<select onchange="SetEntAttribute(' . $_GET['ent_id'] . ', \'race\', this.value)" class="form-control input-inline input-sm input-small">';
            foreach ($races as $key => $value){
                if($_POST['race'] == $key){
                    $race_sel .= '<option selected value="'. $key . '">'. $key . ': '. $value . ' </option>';
                }
                else{
                    $race_sel .= '<option value="'. $key . '">'. $key . ': '. $value . ' </option>';
                }
            }
            $race_sel .= "</select>";

            $send_app_effect_sel = '<select onchange="SendAppearanceEffect(' . $_GET['ent_id'] . ', this.value)" class="form-control input-inline input-sm input-small">';
            for($i = 0; $i < 500; $i++){
                if($i == $send_appearance_effect_list[$i][1]){
                    $description = ': ' . $send_appearance_effect_list[$i][0];
                }
                else{
                    $description = "";
                }

                $send_app_effect_sel .= '<option value="' . $i . '">' . $i  . '' . $description;
            }
            $send_app_effect_sel .= '</select>';

            echo '
                <ul class="media-list list-items">
                    <h3 style="padding-left:10px; color:white">
                        <i class="fa fa-angle-double-right" style="font-size:20px"></i> Entity Selected
                    </h3><hr>
                    <li>
                        <table class="table">
                            <tr><td style="width:75px">Name:</td><td>"' . $_POST['clean_name'] . '"</td></tr>
                            <tr><td style="width:75px">Race:</td><td>' . $race_sel . '</td></tr>
                            <tr><td style="width:75px">Size:</td><td><div id="size_val" style="display:inline">' . $_POST["size"] . '</div> <div id="slider_size"></div></td></tr>
                            <tr><td style="width:75px">Texture:</td><td><div id="texture_val" style="display:inline">' . $_POST["texture"] . '</div> <div id="slider_texture"></div></td></tr>
                            <tr><td style="width:75px">Weapon1:</td><td><div id="wep1_val" style="display:inline">' . $_POST["weapon_1"] . '</div> <div id="slider_wep1"></div></td></tr>
                            <tr><td style="width:75px">Weapon2:</td><td><div id="wep2_val" style="display:inline">' . $_POST["weapon_2"] . '</div> <div id="slider_wep2"></div></td></tr>
                            <tr><td style="width:75px">Send Appearance Effect:</td><td>' . $send_app_effect_sel . '</td></tr>
                            <tr><td style="width:75px">Heading:</td><td><div id="heading_dial"></div></td></tr>
                        </table>
                    </li>
                </ul>
            ';

            echo '<script type="text/javascript">
                if(highlight_timer){ clearInterval(highlight_timer); }
                HighlightEntity(' . $_GET["ent_id"] . ');
                var highlight_timer = setInterval(function(){ HighlightEntity(' . $_GET["ent_id"] . '); }, 1000);
                jQuery(document).ready(function() {
                    ComponentsjQueryUISliders.init();
                    $("#heading_dial").knob({
                        "dynamicDraw": true,
                        "thickness": 0.3,
                        "cursor": true,
                        "tickColorizeValues": true,
                        "skin": "tron",
                        "lineCap": "round",
                        "min": 0,
                        "fgColor": "#000000",
                        "max": 256,
                        "width": 50,
                        "value": ' . ($_GET["heading"] ?: 0) . ',
                        "change" : function (v){
                            socket.send(JSON.stringify({id: "set_entity_attribute",
                            method: "Zone.SetEntityAttribute",
                            params: [g_zone_id, g_instance_id, "" + ' . $_GET["ent_id"] . ' + "", "heading", "" + v + ""]}));
                        },
                    });
                    $( "#slider_size" ).slider({
                          range: "max",  min: 1,  max: 255, value: ' . $_POST["size"] . ',
                          slide: function( event, ui ) {
                            socket.send(JSON.stringify({id: "set_entity_attribute",
                            method: "Zone.SetEntityAttribute",
                            params: [g_zone_id, g_instance_id, "" + ' . $_GET["ent_id"] . ' + "", "size", "" + ui.value + ""]}));
                            $("#size_val").html(ui.value);
                          }

                    });
                    $( "#slider_texture" ).slider({
                          range: "max",  min: 0,  max: 30, value: ' . $_POST["texture"] . ',
                          slide: function( event, ui ) {
                            socket.send(JSON.stringify({id: "set_entity_attribute",
                            method: "Zone.SetEntityAttribute",
                            params: [g_zone_id, g_instance_id, "" + ' . $_GET["ent_id"] . ' + "", "texture", "" + ui.value + ""]}));
                            $("#texture_val").html(ui.value);
                          }
                    });
                    $( "#slider_wep1" ).slider({
                          range: "max",  min: 1,  max: 20000, value: ' . $_POST["weapon_1"] . ',
                          slide: function( event, ui ) {
                            socket.send(JSON.stringify({id: "set_entity_attribute",
                            method: "Zone.SetEntityAttribute",
                            params: [g_zone_id, g_instance_id, "" + ' . $_GET["ent_id"] . ' + "", "weapon_1", "" + ui.value + ""]}));
                            $("#wep1_val").html(ui.value);
                          }
                    });
                    $( "#slider_wep2" ).slider({
                          range: "max",  min: 1,  max: 20000, value: ' . $_POST["weapon_2"] . ',
                          slide: function( event, ui ) {
                            socket.send(JSON.stringify({id: "set_entity_attribute",
                            method: "Zone.SetEntityAttribute",
                            params: [g_zone_id, g_instance_id, "" + ' . $_GET["ent_id"] . ' + "", "weapon_2", "" + ui.value + ""]}));
                            $("#wep2_val").html(ui.value);
                          }
                    });
                });

            </script>';
        }
        if($_GET['sidebar_menu'] == "zone_action") {
            echo '
            <ul class="media-list list-items">

                <h3 style="padding-left:10px;color:white"><i class="fa fa-angle-double-right" style="font-size:20px"></i> Zone Actions</h3>
                <li>
                    <a href="javascript:;" onclick="ZoneAction(\'Repop\')" class="btn btn-default blue btn-xs"> Repop Zone </a>
                    <a href="javascript:;" onclick="ZoneAction(\'ReloadQuests\')" class="btn btn-default purple btn-xs"> Reload Quests </a>
                </li>

                <h3 style="padding-left:10px;color:white"><i class="fa fa-angle-double-right" style="font-size:20px"></i> Sky & Fog</h3>
                <li>
                    <h4>Fog Clip Max</h4>
                        <div id="slider_fog_clip_max"></div>
                </li>
                <li>
                    <h4>Zone Clip Max (Clip Plane)</h4>
                        <div id="slider_clip_max"></div>
                </li>
                <li>
                    <h4>Fog Density</h4>
                        <div id="slider_zone_fog_density"></div>
                </li>
                <li style="text-align:center">
                    <button id="color" class="btn btn-default blue btn-xs">Select Zone Sky Color</button>
                </li>
                <li style="text-align:center">
                    <button id="color" class="btn btn-default green  btn-xs" onclick="SaveZoneHeaders()">Save Zone Headers</button>
                </li>
                <li>
                    <h4>Entity Size (Map)</h4>
                    <div id="slider_text_size"></div>
                </li>
            </ul>
            ';

            echo '<script type="text/javascript">
                jQuery(document).ready(function() {
                    ComponentsjQueryUISliders.init();

                    $( "#slider_fog_clip_max" ).slider({
                          range: true,  min: 1,  max: 15000, values: [ 1, 7550 ],
                          slide: function( event, ui ) {
                            socket.send(JSON.stringify({id: "zone_action", method: "Zone.Action", params: [g_zone_id, g_instance_id, "ZoneFogClip", "" + ui.values[0] + "", "" + ui.values[1] + ""]}));
                          }
                    });
                    $( "#slider_clip_max" ).slider({
                          range: true,  min: 1,  max: 15000, values: [ 1, 7550 ],
                          slide: function( event, ui ) {
                            socket.send(JSON.stringify({id: "zone_action", method: "Zone.Action", params: [g_zone_id, g_instance_id, "ZoneClip", "" + ui.values[0] + "", "" + ui.values[1] + ""]}));
                          }
                    });
                    $( "#slider_zone_fog_density" ).slider({
                          range: "max",  min: 1,  max: 100, value: 33,
                          slide: function( event, ui ) {
                            socket.send(JSON.stringify({id: "zone_action", method: "Zone.Action", params: [g_zone_id, g_instance_id, "ZoneFogDensity", "" + (ui.value / 100) + ""]}));
                          }
                    });
                    $( "#slider_text_size" ).slider({
                          range: "max",  min: 1,  max: 20, value: 12,
                          slide: function( event, ui ) {
                            $(".ent_container").children().css( "font-size", (ui.value));
                            $(".ent_container a").css( "line-height", (ui.value / 10));
                            $(".ent_container img").css( "width", Math.round(ui.value * 1.56));
                          }
                    });

                    $("#color").colpick({
                        layout:"rgb",
                        colorScheme: "dark",
                        onChange:function(hsb,hex,rgb,el,bySetColor) {
                            socket.send(JSON.stringify({id: "zone_action", method: "Zone.Action", params: [g_zone_id, g_instance_id, "ZoneSky", "" + rgb.r + "", "" + rgb.g + "", "" + rgb.b + ""]}));
                        }
                    }).keyup(function(){
                        $(this).colpickSetColor(this.value);
                    });

                });

            </script>';
        }
    }
    if(isset($_GET['quick_sidebar_menu_options'])){
        echo '
            <li>  <a href="#" data-toggle="tab" onclick="SideBarMenu(\'zone_action\')"> <i class="fa fa-location-arrow"></i> Zone Actions </a>  </li>
            <li>  <a href="#" data-toggle="tab" onclick="SideBarMenu(\'entity_search\')"> <i class="fa fa-location-arrow"></i> Entity List Search </a>  </li>
            <li class="divider"> </li>
        ';
    }
?>