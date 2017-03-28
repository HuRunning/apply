$('.tb_body').click(function(e) {
	if (e.target !== this) {
		return false;
	};
	if (!login) {
		alert('请先登入');
		document.location = "//"+window.location.host+"/Index/login";
		return false;
	}
	$('#date').text($(this).parent().find('.tb_date').text());
	$('#room').children().eq($('#room_select').get(0).selectedIndex).attr('selected','selected');
	$('#apply').modal();
});
$('#user_info').click(function(e) {
	if (!login) {
		alert('请先登入');
		document.location = "//"+window.location.host+"/Index/login";
		return false;
	}
	$('#user_apply').modal();
});
$('.occupied').click(function(e) {
	$('#data_id').text($(this).attr('data-id'));
	$('#room_info').text($('#room_select').children().eq($('#room_select').get(0).selectedIndex).text());
	$('#date_info').text($(this).parent().parent().find('.tb_date').text());
	$('#org_info').text($(this).attr('data-org'));
	$('#name_info').text($(this).attr('data-name'));
	$('#phone_info').text($(this).attr('data-phone'));
	$('#time_info').text($(this).find('.occupied_time').text());
	$('#activity_info').text($(this).find('.occupied_text').text());
	// Modified by oziroe on Nov 13, 2016.
	// Display apply time for admin.
	if ($('#apply-time').length) {
		$('#apply-time').text($(this).attr('data-apply-time'));
	}
	// Display cancel button if ok.
	if ($(this).attr('data-cancelable') == 1) {
		$('#cancel').removeClass('hidden');
	} else {
		$('#cancel').addClass('hidden');
	}
	// Modify ends here.
	if ($(this).hasClass('verifying')) {
		$('#status_info').toggleClass('alert-success', false);
		$('#status_info').toggleClass('alert-warning', true);
		$('#status_info').text('审核中');
	} else {
		$('#status_info').toggleClass('alert-success', true);
		$('#status_info').toggleClass('alert-warning', false);
		$('#status_info').text('已通过');
	}
	$('#info').modal();
});
$('#submit').click(function() {
	if ($("#activity").val()=="") {
		alert("活动不能为空!");
		$("#activity").focus();
		return false;
	};
	if ($("#org").val()=="") {
		alert("申请组织不能为空!");
		$("#org").focus();
		return false;
	};
	if (!(/^1\d{10}$/.test($("#phone").val()))) {
		alert("手机号格式错误!");
		$("#phone").focus();
		return false;
	};
	var year = date.getFullYear();
	var month = date.getMonth() + 1;
	var day = date.getDate();
	var formDate = $("#date").text();
	formDate = formDate.replace(/月/,'-');
	formDate = formDate.replace(/日/,'');

	var time = new Date(year+'-'+formDate).getTime()/1000;
	if (new Date(year+'-'+month+'-'+day).getTime()/1000 > time) {
		time = new Date(year+1+'-'+formDate).getTime()/1000;
	}
	$.ajax({
		type: "GET",
		url:  "//"+window.location.host+"/Index/handle",
		data: {
			'_token': csrf_token,
			'type': $("#room_select").val(),
			'people': $("#people").val(),
			'other': $("#other").val(),
			'theme': $("#theme").val(),
			'classify': $("#classify").val(),
			'activity': $("#activity").val(),
			'org': $("#org").val(),
			'phone': $("#phone").val(),
			'email': $("#email").val(),
			'timeA': ($("#timeA").get(0).selectedIndex)*30*60+time + (startTime - 8) * 60 * 60,
			'timeB': ($("#timeB").get(0).selectedIndex)*30*60+time + (startTime - 8) * 60 * 60
		},
		success: function(data){
			alert(data);
			document.location.reload();
		},
		error: function(){
			alert("提交失败!");
		}
	});
	return false;
});
function operation_submit(id,operation,reason) {
	$.ajax({
		type: "GET",
		url:  "//"+window.location.host+"/Index/auditHandle",
		data: {
			'_token': csrf_token,
			'id': id,
			'status': operation,
			'decreason': reason
		},
		success: function(data){
			alert(data);
			document.location.reload();
		},
		error: function(){
			alert("操作失败!");
		}
	});
}
$('#pass').click(function() {
	operation_submit($("#data_id").text(),1);
	return false;
});
$('#decline').click(function() {
	var reason=prompt("请输入拒绝原因","");
	if (reason!=null && reason!="") {
	  operation_submit($("#data_id").text(),2,reason);
	}
	return false;
});
$('#room_select').change(function() {
	var url = '/?org='+org+'&type='+$('#room_select').val();
	if (getQueryString("day")) {
		url += '&day=' + getQueryString("day");
	};
	document.location = url;
});
function getQueryString(name) {
    var reg = new RegExp("(^|&)" + name + "=([^&]*)(&|$)", "i");
    var r = window.location.search.substr(1).match(reg);
    if (r != null) return unescape(r[2]); return null;
    }
var org = 1;
if (getQueryString("org")) {
	var org = ~~getQueryString("org");
};
var date = new Date();
var dateText = $($('.tb_date').get(0)).text();
dateText = dateText.replace(/月/,'-');
dateText = dateText.replace(/日/,'');
date = new Date(date.getFullYear()+'-'+dateText);
if (getQueryString("day")) {
	date.setTime(~~getQueryString("day")*1000);
};
var date2 = new Date(date.getFullYear()+'-'+dateText);
date2.setDate(date.getDate()+6);
$('.week_pre a').attr('href','/?org='+org+'&type='+$('#room_select').val()+'&day='+(Date.parse(date)/1000 - 86400*7));
$('.week_nex a').attr('href','/?org='+org+'&type='+$('#room_select').val()+'&day='+(Date.parse(date)/1000 + 86400*7));
$('.week_cur').text((date.getMonth()+1)+'月'+date.getDate()+'日 - '+(date2.getMonth()+1)+'月'+date2.getDate()+'日');
