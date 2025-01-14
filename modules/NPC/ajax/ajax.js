function ShowZone() {
    $('#top_right_pane').html('');
    $("#shownpczone").html("<b style='color:red;font-size:30px'>LOADING, PLEASE WAIT...</b><hr><i class='fa fa-spinner fa-spin' style='font-size:80px'>");
	var zone_sn = $("#zoneselect").val();
	var inst_version = $("#zinstid").val();
	var NPC = $("#npcname").val();
	$.ajax({
		url: "ajax.php?M=NPC&show_npc_table&Zone=" + zone_sn + "&inst_version=" + inst_version + "&npc_filter=" + encodeURIComponent(NPC),
		context: document.body
	}).done(function(e) {
		$("#shownpczone").html(e);
	});

	history.pushState(null, "Zone " + zone_sn, "index.php?M=NPC2&zone=" + zone_sn + "&version=" + inst_version + "&npc_filter=" + encodeURIComponent(NPC));
}

function ShowZoneFromURL(zone_sn, inst_version, name_filter, field_filter){
    $("#shownpczone").html("<b style='color:red;font-size:30px'>LOADING, PLEASE WAIT...</b><hr><i class='fa fa-spinner fa-spin' style='font-size:80px'>");
	$.ajax({
		url: "ajax.php?M=NPC&show_npc_table&Zone=" + zone_sn + "&inst_version=" + inst_version + "&npc_filter=" + encodeURIComponent(name_filter),
		context: document.body
	}).done(function(e) {
		$("#shownpczone").html(e);
	});
}

function update_npc_field(npc_id, db_field, db_val, notify){
    notify = typeof notify !== 'undefined' ? notify : 1;
	if(db_field == "armortint_id"){
		$(".armortint_red").val(0);
		$(".armortint_green").val(0);
		$(".armortint_blue").val(0);
		update_npc_field($("#npc_id").val(), "armortint_red", $(".armortint_red").val());
		update_npc_field($("#npc_id").val(), "armortint_green", $(".armortint_green").val());
		update_npc_field($("#npc_id").val(), "armortint_blue", $(".armortint_blue").val());
	}

	if(db_field == "d_melee_texture1"){
		document.getElementById("d_melee_texture1").src = "includes/img.php?type=weaponimage&id=" + db_val;
	}
	if(db_field == "d_melee_texture2"){
		document.getElementById("d_melee_texture2").src = "includes/img.php?type=weaponimage&id=" + db_val;
	}

	$( "td[" + npc_id + "-" + db_field + "]").css("border", "1px solid rgba(64,153,255, 1)");
	$( "td[" + npc_id + "-" + db_field + "]").html(db_val);

	$.ajax({
		url: "ajax.php?M=NPC&DoFieldUpdateSingleNPC=1&NPC=" + npc_id + "&Field=" + db_field + "&Value=" + db_val,
		context: document.body
	}).done(function(e) {
        if(notify == 1) {
            Notific8("NPC Editor", "Field '" + db_field + "' updated to value '" + db_val + "'", 3000);
        }
	});
}

function working_notify(){
    Notific8("NPC Editor", "Working....", 3000);
}

function do_set_field_mass_value(){
    var db_field = $("#npctypesfield").val();
    var value = $("#massfieldvalue").val();
    $("td[npc_db_field=" + db_field + "]").each(function( index ) {
        // console.log($(this).html());
        npc_id = $(this).attr("npc_id");
        update_npc_field(npc_id, db_field, value, 0);
    });
    Notific8("NPC Editor", "Updated all '" + db_field + "' to value '" + value + "' in NPC list", 3000);
}

function SetFieldMassValue(){
    working_notify();
    var timer = setInterval(function () {
        do_set_field_mass_value();
        window.clearInterval(timer);
    }, 200);
}

function do_set_field_mass_value_min_max(){
    var db_field = $("#npctypesfield").val();
    var min_value = parseInt($("#minfieldvalue").val());
    var max_value = parseInt($("#maxfieldvalue").val());
    $("td[npc_db_field=" + db_field + "]").each(function( index ) {
        // console.log($(this).html());
        npc_id = $(this).attr("npc_id");
        update_npc_field(npc_id, db_field, GetRandomInt(min_value, max_value), 0);
    });
    Notific8("NPC Editor", "Updated all fields '" + db_field + "' with random values between '" + min_value + "' and '" + max_value + "' in NPC list", 3000);
}

function SetFieldMassValueMinMax(){
    working_notify();
    var timer = setInterval(function () {
        do_set_field_mass_value_min_max();
        window.clearInterval(timer);
    }, 200);
}

function GetRandomInt (min, max) {
    return Math.floor(Math.random() * (max - min + 1)) + min;
}

function RaceChange(NPCID, val){
		var xmlhttp;
		if (window.XMLHttpRequest) { xmlhttp=new XMLHttpRequest(); }
		else {  xmlhttp=new ActiveXObject("Microsoft.XMLHTTP"); }
		xmlhttp.onreadystatechange=function() {
			if (xmlhttp.readyState==4 && xmlhttp.status==200) {
				
			}
		}
		document.getElementById("RaceIMG").src="includes/img.php?type=race&id=" + val;
		xmlhttp.open("GET","ajax.php?M=NPC&DoFieldUpdateSingleNPC=1&NPC=" + NPCID + "&Field=race&Value=" + val, true);
		xmlhttp.send();
}

function DoFieldUpdateParent(val){ 
	var n = val.split("-");
	console.log(val);
	var field_value = $( "td[" + val + "]", window.opener.document).html();
	if(n[0] && n[1]){
		Notific8("NPC Editor", "Field `" + n[1] + "` Updated to Value \'" + field_value + "\' on NPC ID: \'" + n[0] + "\'", 3000);
		var xmlhttp;
		if (window.XMLHttpRequest) { xmlhttp=new XMLHttpRequest(); }
		else {  xmlhttp=new ActiveXObject("Microsoft.XMLHTTP"); }
		xmlhttp.open("GET","ajax.php?M=NPC&DoFieldUpdate=1&NPC=" + n[0] + "&Field=" + encodeURIComponent(n[1]) + "&Value=" + encodeURIComponent(field_value), true);
		xmlhttp.send();
	}
} 

function do_npc_edit(val){
	DoModal("ajax.php?M=NPC&SingleNPCEdit=" + val);  
}

function do_npc_delete(val){
	DoModal("ajax.php?M=NPC&npc_delete_confirm=" + val);
}

function do_npc_copy(val){
	DoModal("ajax.php?M=NPC&npc_copy=" + val);
}

function do_npc_copy_confirm(val){
    $("#npc_copy_result").html("<i class='fa fa-spinner fa-spin' style='font-size:20px'>");
    $.ajax({
        url: "ajax.php?M=NPC&do_npc_copy_confirm=" + val + "&copies_to_make=" + $("#copies_to_make").val() + "&starting_insert="+ $("#starting_insert").val(),
        context: document.body
    }).done(function(e) {
        $('#npc_copy_result').html(e);
    });
}

function do_npc_confirm(val){
	$.ajax({
		url: "ajax.php?M=NPC&delete_npc=" + val,
		context: document.body
	}).done(function(e) {
		$('#ajax-modal').modal('hide');
		Notific8("NPC Editor", "NPC ID: " + val + " has been deleted", 2000);
		/* Update Data Table as well */
		$('#npc_head_table').DataTable().row($("tr[npc_row_id_" + val + "]")).remove().draw();
	});
}

function OpenWindow(Url, Title, w, h){
	var left = (screen.width/2)-(w/2);
	var top = (screen.height/2)-(h/2);
	window.open(Url, Title, "toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no, height="+h+",width="+w+", toolbar=no, top="+top+", left="+left+"");
}