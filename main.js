warning: LF will be replaced by CRLF in public/public/js/main.js.
The file will have its original line endings in your working directory.
warning: LF will be replaced by CRLF in app/Http/Controllers/ApplyController.php.
The file will have its original line endings in your working directory.
[1mdiff --git a/app/Http/Controllers/ApplyController.php b/app/Http/Controllers/ApplyController.php[m
[1mindex f9decef..7deb33c 100644[m
[1m--- a/app/Http/Controllers/ApplyController.php[m
[1m+++ b/app/Http/Controllers/ApplyController.php[m
[36m@@ -177,6 +177,13 @@[m [mclass ApplyController extends Controller[m
         echo $newInfo;[m
         $newInfo['name']    = session('username');[m
         $newInfo['netid']   = session('netid');[m
[32m+[m[32m        //[m
[32m+[m[32m        $newInfo['theme']   = request()->input('theme','未知主题');[m
[32m+[m[32m        $newInfo['other']   = request()->input('other','无');[m
[32m+[m[32m        $newInfo['email']   = request()->input('email');[m
[32m+[m[32m        $newInfo['set_reset']=request()->input('set_reset');[m
[32m+[m[32m        $newInfo['classify'] =request()->input('classify');[m
[32m+[m[32m        //[m
         $newInfo['reason']  = request()->input('activity', '未知活动');[m
         $newInfo['org']     = request()->input('org', '未知活动');[m
         $newInfo['phone']   = request()->input('phone', '11111111111');[m
[1mdiff --git a/public/public/js/main.js b/public/public/js/main.js[m
[1mindex 0f58ed1..f6c96f0 100644[m
[1m--- a/public/public/js/main.js[m
[1m+++ b/public/public/js/main.js[m
[36m@@ -4,7 +4,7 @@[m [m$('.tb_body').click(function(e) {[m
 	};[m
 	if (!login) {[m
 		alert('请先登入');[m
[31m-		document.location = 'https://apply.tiaozhan.com/Index/login';[m
[32m+[m		[32mdocument.location = "//"+window.location.host+"/Index/login";[m
 		return false;[m
 	}[m
 	$('#date').text($(this).parent().find('.tb_date').text());[m
[36m@@ -14,7 +14,7 @@[m [m$('.tb_body').click(function(e) {[m
 $('#user_info').click(function(e) {[m
 	if (!login) {[m
 		alert('请先登入');[m
[31m-		document.location = 'https://apply.tiaozhan.com/Index/login';[m
[32m+[m		[32mdocument.location = "//"+window.location.host+"/Index/login";[m
 		return false;[m
 	}[m
 	$('#user_apply').modal();[m
