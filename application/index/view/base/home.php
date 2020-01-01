<?php
/**
 * @name 生蚝科技RBAC框架(TP)-V-主框架
 * @author Jerry Cheung <master@xshgzs.com>
 * @since 2019-10-20
 * @version 2020-01-01
 */
?>

<!DOCTYPE html>
<html>

<head>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>{:config('app_name')}</title>

	{include file="base/loadCssJs" /}
</head>

<body class="hold-transition skin-cyan sidebar-mini">
<div class="wrapper">

<!-- 头部 -->
<div id="headerVm">
<header class="main-header">
	<a v-bind:href="[rootUrl]" class="logo">
		<span class="logo-mini"><img src="https://www.xshgzs.com/resource/index/images/logo2.png" style="width:85%"></span>
		<span class="logo-lg"><img src="https://www.xshgzs.com/resource/index/images/logo2.png" style="width:20%"> <b>生蚝科技</b></span>
	</a>
	<nav class="navbar navbar-static-top">
		<a class="sidebar-toggle" data-toggle="push-menu" role="button"><span class="sr-only">Toggle navigation</span></a>

		<div class="navbar-custom-menu">
			<ul class="nav navbar-nav">
				<li class="dropdown user user-menu">
					<a href="#" class="dropdown-toggle" data-toggle="dropdown">
						<img src="https://static.xshgzs.com/image/user.png" class="user-image">
						<span class="hidden-xs">{{userInfo['nickName']}}</span>
					</a>
					<ul class="dropdown-menu">
						<li class="user-header">
							<img src="https://static.xshgzs.com/image/user.png" class="img-circle">
							<p>{{userInfo['userName']}} - {{userInfo['nickName']}}<!--small>Member since ?</small--></p>
						</li>
						<!-- Menu Footer-->
						<li class="user-footer">
							<div class="pull-left">
								<a v-bind:href="[rootUrl+'user/updateProfile']" class="btn btn-default btn-flat">用户中心</a>
								<a data-toggle="modal" data-target="#changePasswordModal" class="btn btn-default btn-flat">修改密码</a>
							</div>
							<div class="pull-right">
								<button data-toggle="modal" data-target="#logoutModal" class="btn btn-default btn-flat">登出</button>
							</div>
						</li>
					</ul>
				</li>
			</ul>
		</div>
	</nav>
</header>

<!-- 侧边导航栏 -->
<aside class="main-sidebar">
	<section class="sidebar">
		<div class="user-panel">
			<div class="pull-left image">
				<img src="https://evip.zy.com/dist/img/avatar_img.png" class="img-circle" alt="User Image">
			</div>
			<div class="pull-left info">
				<p>小生蚝</p>
				<select id="roleList" style="background-color:rgb(59, 73, 102);border:0;color:#fff;margin-left:-4px;" v-on:change="changeRole" v-model="roleIdSelected">
					<option v-for="(roleName,roleId) in allRoleInfo" v-bind:value="roleId">{{roleName}}</option>
				</select>
			</div>
		</div>
		<!-- 菜单树 -->
		<!-- 父菜单 -->
		<ul class="sidebar-menu" data-widget="tree">
			<li>
				<a v-bind:href="[rootUrl]">
					<i class="fa fa-home"></i> 系统主页面
				</a>
			</li>
			<li v-for="fatherInfo in treeData" v-if="fatherInfo['hasChild']!=1 && fatherInfo['type']==1"><a @click="addTab(fatherInfo['id'],[rootUrl+fatherInfo['uri']],fatherInfo['name'])"><i v-bind:class="['fa fa-'+fatherInfo['icon']]"></i> {{fatherInfo['name']}}</a></li>
			<!-- 二级菜单 -->
			<li v-else-if="fatherInfo['type']==1" class="treeview">
				<a href="#">
					<i v-bind:class="['fa fa-'+fatherInfo['icon']]"></i> <span>{{fatherInfo['name']}}</span>
					<span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span>
				</a>
				<ul class="treeview-menu">
					<li v-for="childInfo in fatherInfo['child']" v-if="childInfo['hasChild']!=1"><a @click="addTab(childInfo['id'],[rootUrl+childInfo['uri']],childInfo['name'])"><i v-bind:class="['fa fa-'+childInfo['icon']]"></i> {{childInfo['name']}}</a></li>
					<!-- 三级菜单 -->
					<li v-else class="treeview">
						<a href="#">
							<i v-bind:class="['fa fa-'+childInfo['icon']]"></i> <span>{{childInfo['name']}}</span>
							<span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span>
						</a>
						<ul class="treeview-menu">
							<li v-for="grandsonInfo in childInfo['child']"><a @click="addTab(grandsonInfo['id'],[rootUrl+grandsonInfo['uri']],grandsonInfo['name'])"><i v-bind:class="['fa fa-'+grandsonInfo['icon']]"></i> {{grandsonInfo['name']}}</a></li>
						</ul>
					</li>
					<!-- ./三级菜单 -->
				</ul>
			</li>
			<!-- ./二级菜单 -->
		</ul>
		<!-- /.父菜单 -->
		<!-- /.菜单树 -->
	</section>
</aside><!-- /.侧边导航栏 -->

<div class="modal modal-warning fade" id="logoutModal">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title">登出提示</h4>
			</div>
			<div class="modal-body">
				<h3 style="line-height:38px;">确认要退出吗？</h3>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-outline pull-left" data-dismiss="modal">&lt; 取消</button>
				<a v-bind:href="[rootUrl+'logout']" class="btn btn-outline">确认登出 &gt;</a>
			</div>
		</div>
		<!-- /.modal-content -->
	</div>
	<!-- /.modal-dialog -->
</div>
<!-- /.modal -->

</div><!-- /.头部 -->

<script>
var headerVm = new Vue({
	el:'#headerVm',
	data:{
		rootUrl:"{:config('url_domain_root')}/",
		apiPath:"{:URL('/api/','','',true)}",
		//token:JWT.get(),
		userId:"",
		userInfo:{},
		treeData:{},
		allRoleInfo:{},
		roleIdSelected:'',
		contentMaxHeight:0,
		tabloadToken:'{$tabloadToken}'
	},
	methods:{
		getUserInfo:function(){
			lockScreen();

			$.ajax({
				url:headerVm.apiPath+"user/getCurrentUserInfo",
				dataType:"json",
				error:function(e){
					unlockScreen();
					showModalTips("服务器错误！"+e.status);
					console.log(e);
					return false;
				},
				success:function(ret){
					if(ret.code==200){
						unlockScreen();
						headerVm.userInfo=ret.data;
						headerVm.roleIdSelected=headerVm.userInfo['currentRoleId'];
						return true;
					}else{
						unlockScreen();
						showModalTips("系统错误！<br>请联系技术支持并提供错误码【USIF"+ret.code+"】");
						return false;
					}
				}
			});
		},
		getMenuTree:function(){
			lockScreen();

			$.ajax({
				url:headerVm.apiPath+"menu/getCurrentUserMenu",
				dataType:"json",
				error:function(e){
					unlockScreen();
					showModalTips("服务器错误！"+e.status);
					console.log(e);
					return false;
				},
				success:function(ret){
					if(ret.code==200){
						treeData=ret.data['treeData'];
						headerVm.treeData=treeData;
						if(treeData==""){
							showModalTips("用户菜单获取失败 或 用户暂无权限！");
						}
						unlockScreen();
						return true;
					}else if(ret.code==403){
						unlockScreen();
						showModalTips("用户菜单获取失败！");
						return false;
					}else{
						unlockScreen();
						showModalTips("系统错误！<br>请联系技术支持并提供错误码【MN"+ret.code+"】");
						return false;
					}
				}
			});
		},
		getAllRole:function(){
			allRoleInfo=sessionStorage.getItem('allRoleInfo');
			headerVm.allRoleInfo=JSON.parse(allRoleInfo);
		},
		changeRole:function(){
			roleId=$("#roleList").val();

			if(roleId=='' || roleId==headerVm.userInfo['roleId']) return;

			$.ajax({
				url:headerVm.rootUrl+"user/toChangeRole",
				type:"post",
				data:{"roleId":roleId},
				dataType:"json",
				error:function(e){
					console.log(JSON.stringify(e));
				},
				success:function(ret){
					window.location.href=headerVm.rootUrl;
				}
			});
		},
		addTab:(menuId,url,name)=>{
			menuId=menuId.replace(/-/g,"");

			// 判断是否已经打开
			if($("#tab-"+menuId).length>0){
				headerVm.changeTab(menuId);
				return true;
			}

			// 先取消所有tab的active
			$(".nav-tabs li").attr('class','');
			// 再新增tab
			var tabHtml=''
			+'<li class="active">'
			+'<a onclick="headerVm.changeTab('+"'"+menuId+"'"+')" id="tab-'+menuId+'">'+name+'</a>'
			+'<span class="tabClose" onclick="headerVm.removeTab('+"'"+menuId+"'"+')">&times;</span>'
			+'</li>';
			$(".nav-tabs").append(tabHtml);

			// 先把所有的tabPanel隐藏
			$(".tab-pane").attr('class','tab-pane');

			// 再新增tabPanel
			//lockScreen();
			$.ajax({
				url:url,
				data:{tabId:menuId,tabloadToken:headerVm.tabloadToken},
				error:function(e){
					unlockScreen();
					alert("加载页面失败！");
					console.log(JSON.stringify(e));
					//removeTab(menuId);
					$(".tab-content").append('<div class="tab-pane active" id="tabPanel-'+menuId+'" style="overflow-x:scroll;">'+e.responseText+'</div>');
					return false;
				},
				success:function(ret){
					unlockScreen();
					$(".tab-content").append('<div class="tab-pane active" id="tabPanel-'+menuId+'" style="overflow-x:scroll;">'+ret+'</div>');

					if(/Android|webOS|iPhone|iPod|BlackBerry/i.test(navigator.userAgent)){
						height=document.body.clientHeight;
						height=window.screen.availWidth;
						$("#tabPanel-"+menuId).height(height);
					}
				}
			})
		},
		changeTab:(id)=>{
			$(".nav-tabs li").attr('class','');
			$("#tab-"+id).parent().attr('class','active');

			$(".tab-pane").attr('class','tab-pane');
			$("#tabPanel-"+id).attr('class','tab-pane active');
		},
		removeTab:(id)=>{
			$("#tab-"+id).parent().remove();
			$("#tabPanel-"+id).remove();
		}
	}
});

headerVm.getAllRole();
headerVm.getUserInfo();
headerVm.getMenuTree();
</script>

<!-- 页面内容 -->
<div class="content-wrapper">
	<!-- 页面主要内容 -->
	<section class="content">
		<div class="tabstyle">
			<ul class="nav nav-tabs">
				<li class="active">
					<a onclick="headerVm.changeTab(0)" id="tab-0">首页</a>
					<span class="tabClose" onclick="headerVm.removeTab(0)">&times;</span>
				</li>
			</ul>
			<div class="tab-content" style="height: 610px;overflow: scroll;">
				<div class="tab-pane active" id="tabPanel-0">
					首页示例内容
				</div>
			</div>
		</div>
	</section>
	<!-- ./页面主要内容 -->
</div>
<!-- ./页面内容 -->

<!-- ./Page -->
</div>

<div class="modal fade" id="tipsModal" z-index="99999">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true"></span><span class="sr-only">Close</span></button>
				<h3 class="modal-title" id="ModalTitle">温馨提示</h3>
			</div>
			<div class="modal-body">
				<font color="red" style="font-weight:bold;font-size:24px;text-align:center;">
					<p id="tips"></p>
				</font>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-primary" data-dismiss="modal">关闭 &gt;</button>
			</div>
		</div>
	</div>
</div>

</body>
</html>
