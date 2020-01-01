<?php
/**
 * @name 生蚝科技RBAC框架(TP)-V-测试登录
 * @author Jerry Cheung <master@xshgzs.com>
 * @since 2019-12-24
 * @version 2019-12-29
 */
?>

<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<link rel="stylesheet" href="https://cdn.bootcss.com/bootstrap/3.3.7/css/bootstrap.min.css">
	<link rel="stylesheet" href="https://cdn.bootcss.com/font-awesome/4.7.0/css/font-awesome.min.css">

	<script src="https://cdn.bootcss.com/jquery/3.1.0/jquery.min.js"></script>
	<script src="https://cdn.bootcss.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

	<title>测试登录 / 生蚝科技RBAC框架(TP版)</title>
	<style>
		.main-body {
			margin: 10rem auto;
		}
		.main-body .list {
			display: flex;
		}
		@media (max-width: 600px) {
			.main-body {
				margin: 20px auto;
			}
			.main-body .list {
				display: contents;
				
			}
		}

		.main-body .list .item {
			margin: 10px 20px;
			flex: 1;
			box-shadow: 0 2px 20px 0 rgba(0, 0, 0, 0.05);
			border-radius: 8px;
			text-align: center;
		}

		.main-body .list .item a {
			display: block;
			height: 140px;line-height: 140px;
			background: url(data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAcwAAADACAYAAACJblNJAAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAAyhpVFh0WE1MOmNvbS5hZG9iZS54bXAAAAAAADw/eHBhY2tldCBiZWdpbj0i77u/IiBpZD0iVzVNME1wQ2VoaUh6cmVTek5UY3prYzlkIj8+IDx4OnhtcG1ldGEgeG1sbnM6eD0iYWRvYmU6bnM6bWV0YS8iIHg6eG1wdGs9IkFkb2JlIFhNUCBDb3JlIDUuNi1jMDY3IDc5LjE1Nzc0NywgMjAxNS8wMy8zMC0yMzo0MDo0MiAgICAgICAgIj4gPHJkZjpSREYgeG1sbnM6cmRmPSJodHRwOi8vd3d3LnczLm9yZy8xOTk5LzAyLzIyLXJkZi1zeW50YXgtbnMjIj4gPHJkZjpEZXNjcmlwdGlvbiByZGY6YWJvdXQ9IiIgeG1sbnM6eG1wTU09Imh0dHA6Ly9ucy5hZG9iZS5jb20veGFwLzEuMC9tbS8iIHhtbG5zOnN0UmVmPSJodHRwOi8vbnMuYWRvYmUuY29tL3hhcC8xLjAvc1R5cGUvUmVzb3VyY2VSZWYjIiB4bWxuczp4bXA9Imh0dHA6Ly9ucy5hZG9iZS5jb20veGFwLzEuMC8iIHhtcE1NOkRvY3VtZW50SUQ9InhtcC5kaWQ6NkE1MzVBRUE1NDJCMTFFOTkyRTZFNkFCQzQ3NDMxNzciIHhtcE1NOkluc3RhbmNlSUQ9InhtcC5paWQ6NkE1MzVBRTk1NDJCMTFFOTkyRTZFNkFCQzQ3NDMxNzciIHhtcDpDcmVhdG9yVG9vbD0iQWRvYmUgUGhvdG9zaG9wIENDIDIwMTcgKE1hY2ludG9zaCkiPiA8eG1wTU06RGVyaXZlZEZyb20gc3RSZWY6aW5zdGFuY2VJRD0ieG1wLmlpZDoxOUMyMEFGOTZDNTQxMUU4QTI1MUQ1RTc5MkI4RjFFNCIgc3RSZWY6ZG9jdW1lbnRJRD0ieG1wLmRpZDoxOUMyMEFGQTZDNTQxMUU4QTI1MUQ1RTc5MkI4RjFFNCIvPiA8L3JkZjpEZXNjcmlwdGlvbj4gPC9yZGY6UkRGPiA8L3g6eG1wbWV0YT4gPD94cGFja2V0IGVuZD0iciI/PsaOenUAAAnUSURBVHja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) no-repeat right 0;
			background-size: auto 100%;
			text-decoration: none;
			font-size: 36px;
			color: #fff;
		}

		.main-body .list .item:nth-child(1) {
			background: linear-gradient(220deg, #72A3FF 0, #ACABFF 100%);
		}


		.main-body .list .item:nth-child(2) {
			background: linear-gradient(220deg, #FFCEAB 0, #FC7F78 100%);
		}


		.main-body .list .item:nth-child(3) {
			background: linear-gradient(220deg, #4FC8FF 0, #6DAFFD 100%);
		}
	</style>
</head>

<body style="background-image: url(https://static.xshgzs.com/image/backstage_bg.png); background-position: center center; background-repeat: repeat;">
	<div class="main-body">
		<center>
			<div style="margin: 1rem 1rem 1rem 2rem;color: rgb(51, 122, 183); font-size: 21px;">
				<img src="https://www.itrclub.com/resource/index/img/logo.png" style="width: 100px; height: 80px;">&nbsp;&nbsp;&nbsp;RBAC框架测试登录
			</div>
		</center>
		<div class="list">
			<div class="item"><a href="{:URL('testlogin?u=1546eb2e-ae17-ac9b-fb65-487e1ce728af')}">SUPER</a></div>
			<div class="item"><a href="{:URL('testlogin?u=e36cd728-2816-3bab-a9de-90450afc727a')}">USER</a></div>
			<!--div class="item"><a href="">OA首页</a></div-->
		</div>
	</div>
	
<script>
var platform;
var tester;

function chooseTester(platform2){
	platform=platform2;
	$("#chooseTesterModal").modal('show');
}

function login(tester){
	$.ajax({
		url:"./hdc",
		dataType:"json",
		data:{platform:platform,tester:tester},
		error:function(e){
			alert("服务器错误！"+e.status);
			return false;
		},
		success:function(ret){
			if(ret.code==200){
				window.location.href='./slg';
			}else if(ret.code==403){
				alert("无此测试者资料 或 此测试者权限已过期！");
				return false;
			}else{
				alert("系统错误！"+ret.code);
				console.log(ret);
			}
		}
	});
}
</script>

<div class="modal fade" id="chooseTesterModal" z-index="99999">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true"></span><span class="sr-only">Close</span></button>
				<h3 class="modal-title">选择测试者</h3>
			</div>
			<div class="modal-body">
				<table class="table table-hover table-striped" style="text-align: left;">
					<tr>
						<th>姓名</th>
						<th>权限组</th>
						<th>登录</th>
					</tr>
					<tr>
						<td>JerryCheung</td>
						<td>isDEVer,isWXDer</td>
						<td><button class="btn btn-primary" onclick="login('2f4f5b831fb79afa2a236bb817927312')">登录</button></td>
					</tr>
					<tr>
						<td>SamuelZey</td>
						<td>isABGer</td>
						<td><button class="btn btn-primary" onclick="login('70b2a3e57deaad0d8e496d1640e79266')">登录</button></td>
					</tr>
				</table>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-primary" data-dismiss="modal">关闭 &gt;</button>
			</div>
		</div>
	</div>
</div>

</body>
</html>