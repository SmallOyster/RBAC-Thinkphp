<?php 
/**
 * @name 生蚝科技RBAC框架(TP)-V-登录
 * @author Jerry Cheung <master@xshgzs.com>
 * @since 2019-12-29
 * @version 2020-01-06
 */
?>

<!DOCTYPE html>
<html>

<head>
	{include file="base/loadCssJs" /}

	<title>登录 / {:config('custom.app_name')}</title>
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	
	<style>
	body{
		padding-top: 20px;
	}
	</style>
</head>

<body style="background-color: #66CCFF;">
<div class="container">
	<div class="col-md-6 col-md-offset-3">
		<!--center><img src="" style="display: inline-block;height: auto;max-width: 100%;"></center><br-->
		<div class="panel panel-default">
			<div class="panel-heading">
				<h3 class="panel-title" style="text-align:center;line-height:23px;">欢迎登录<br>{:config('custom.app_name')}</h3>
			</div>
			<div class="panel-body">
				<div class="form-group">
					<label for="userName">用户名</label>
					<input class="form-control" placeholder="用户名 / UserName" id="userName" onkeyup='if(event.keyCode==13)$("#password").focus();'>
				</div>
				<br>
				<div class="form-group">
					<label for="password">密码</label> <!--a href="" target="_blank">（忘记密码 Forget Password）</a-->
					<input class="form-control" placeholder="密码 / Password" id="password" type="password" onkeyup='if(event.keyCode==13)toLogin();'>
				</div>
				<div class="checkbox">
					<label for="Remember">
						<input type="checkbox" id="Remember">记住用户名
					</label>
				</div>

				<center>
					<a class="btn btn-primary" style="width:49%" href='register'>注册 / Register</a>
					<button class="btn btn-success" style="width:49%" onclick='toLogin();'>登录 / Login &gt;</button>
				</center>
			</div>
		</div>
	</div>
</div>

<center>
	<!-- 页脚版权 -->
	<p style="font-weight:bold;font-size:20px;line-height:26px;">
		&copy; <a href="https://www.xshgzs.com?from=tp_rbac" target="_blank" style="font-size:21px;">生蚝科技</a> 2014-{:date('Y')}
		<a style="color:#07C160" onclick='showWXCode()'><i class="fa fa-weixin fa-lg" aria-hidden="true"></i></a>
		<a style="color:#FF7043" onclick='launchQQ()'><i class="fa fa-qq fa-lg" aria-hidden="true"></i></a>
		<a style="color:#29B6F6" href="mailto:master@xshgzs.com"><i class="fa fa-envelope fa-lg" aria-hidden="true"></i></a>
		<a style="color:#AB47BC" href="https://github.com/OysterTech" target="_blank"><i class="fa fa-github fa-lg" aria-hidden="true"></i></a>
		
		<br>
		
		All Rights Reserved.<br>
		<a href="http://miit.beian.gov.cn/" target="_blank" style="color:black;">粤ICP备19018320号-1</a><br><br>
	</p>
	<!-- ./页脚版权 -->
</center>

<script>
function launchQQ(){
	if(/Android|webOS|iPhone|iPod|BlackBerry/i.test(navigator.userAgent)){
		window.location.href="mqqwpa://im/chat?chat_type=wpa&uin=571339406";
	}else{
		window.open("http://wpa.qq.com/msgrd?v=3&uin=571339406");
	}
}

function showWXCode(){
	$("#wxModal").modal('show');
}

var isAjaxing=0;

// 监听模态框关闭事件
$(function (){
	$('#tipsModal').on('hidden.bs.modal',function (){
		isAjaxing=0;
	});
});

window.onload=function(){

	/********** ▼ 记住密码 ▼ **********/
	Remember=getCookie("{:config('session.prefix')}_RmUN");
	if(Remember!=null){
		$("#userName").val(Remember);
		$("#password").focus();
		$("#Remember").attr("checked",true);
	}else{
		$("#userName").focus();
	}
	/********** ▲ 记住密码 ▲ **********/

	sessionStorage.removeItem("allRoleInfo");
}

function toLogin(){
	// 防止多次提交
	if(isAjaxing==1){
		return false;
	}

	isAjaxing=1;
	lockScreen();
	$("#userName").attr("disabled",true);
	$("#password").attr("disabled",true);
	userName=$("#userName").val();
	password=$("#password").val();

	/********** ▼ 记住密码 ▼ **********/
	Remember=$("input[type='checkbox']").is(':checked');
	if(Remember==true){
		setCookie("{:config('session.prefix')}RmUN",userName);
	}else{
		delCookie("{:config('session.prefix')}RmUN");
	}
	/********** ▲ 记住密码 ▲ **********/

	if(userName==""){
		$("#tips").html("请输入用户名！");
		unlockScreen();
		$("#userName").removeAttr("disabled");
		$("#password").removeAttr("disabled");
		return false;
	}
	if(password==""){
		unlockScreen();
		showModalTips("请输入密码！");
		$("#password").removeAttr("disabled");
		return false;
	}
	
	$.ajax({
		url:"{:url('index/login/toLogin')}",
		type:"post",
		data:{"userName":userName,"password":password},
		dataType:"json",
		error:function(e){
			console.log(JSON.stringify(e));
			unlockScreen();
			$("#userName").removeAttr("disabled");
			$("#password").removeAttr("disabled");
			
			showModalTips("服务器错误！<hr>请联系技术支持并提交以下错误码：<br><font color='blue'>"+e.status+"</font>");
			return false;
		},
		success:function(ret){
			unlockScreen();
			$("#userName").removeAttr("disabled");
			$("#password").removeAttr("disabled");

			if(ret.code==200){
				sessionStorage.setItem('allRoleInfo',ret.data['roleInfo']);

				// 如果有重定向地址且base64有效
				if(getURLParam("redirect")!=null){
					try {
						window.location.href=window.atob(getURLParam("redirect"));
					} catch (err) {
						window.location.href=ret.data['url'];
					}
				}else{
					window.location.href=ret.data['url'];
				}
			}else if(ret.tips!=null){
				showModalTips(ret.tips);
				return false;
			}else if(ret.code==1){
				showModalTips("当前用户被禁用！<br>请联系管理员！");
				return false;
			}else if(ret.code==403){
				showModalTips("用户名或密码错误！");
				return false;
			}else if(ret.code==3){
				showModalTips("用户暂未激活！<br>请尽快进行激活！");
				return false;
			}else if(ret.code==2){
				showModalTips("获取角色信息失败！请联系管理员！");
				return false;
			}else{
				console.log(ret);
				showModalTips("系统错误！<hr>请联系技术支持并提交以下错误码：<br><font color='blue'>"+ret.code+"</font>");
				return false;
			}
		}  
	});
}
</script>

<div class="modal fade" id="tipsModal">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span><span class="sr-only">Close</span></button>
				<h3 class="modal-title" id="ModalTitle">温馨提示</h3>
			</div>
			<div class="modal-body">
				<font color="red" style="font-weight:bolder;font-size:24px;text-align:center;">
					<p id="tips"></p>
				</font>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-warning" onclick='isAjaxing=0;$("#tipsModal").modal("hide");'>返回 &gt;</button>
			</div>
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<div class="modal fade" id="wxModal">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span><span class="sr-only">Close</span></button>
				<h3 class="modal-title">微信公众号二维码</h3>
			</div>
			<div class="modal-body">
				<center><img src="https://www.xshgzs.com/resource/index/images/wxOfficialAccountQRCode.jpg" style="width:85%"></center>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-primary" onclick='$("#wxModal").modal("hide");'>关闭 &gt;</button>
			</div>
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div><!-- /.modal -->

</body>
</html>
