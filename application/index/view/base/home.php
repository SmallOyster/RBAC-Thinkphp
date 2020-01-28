<?php
/**
 * @name 生蚝科技RBAC框架(TP)-V-主框架
 * @author Jerry Cheung <master@xshgzs.com>
 * @since 2019-10-20
 * @version 2020-01-28
 */
?>

<!DOCTYPE html>
<html>

<head>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>{:config('custom.app_name')}</title>

	{include file="base/loadCssJs" /}
</head>

<body class="hold-transition skin-cyan sidebar-mini">
<!-- Page -->
<div class="wrapper">

<!-- Vue组件 -->
{include file="base/vueComponent" /}
<!-- /.Vue组件 -->

<!-- 头部 -->
<div id="headerVm">
	{include file="base/navbar" /}
</div>
<!-- /.头部 -->

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
				url:headerVm.rootUrl+"toChangeRole",
				type:"post",
				data:{"roleId":roleId},
				dataType:"json",
				error:function(e){
					console.log(JSON.stringify(e));
				},
				success:function(ret){
					if(ret.code==200){
						window.location.href=headerVm.rootUrl;
					}else{
						showModalTips(ret.tips);
						return false;
					}
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
					
					if(e.status==404){
						alert("页面不存在！\n请联系管理员！");
						headerVm.removeTab(menuId);
						return false;
					}else{
						alert("加载页面失败！");
						$(".tab-content").append('<div class="tab-pane active" id="tabPanel-'+menuId+'" style="overflow-x:scroll;">'+e.responseText+'</div>');
						return false;
					}
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
		},
		navbarLogout:()=>{
			sessionStorage.removeItem('allRoleInfo');
			showModalTips('您已安全登出系统！');
			window.location.href=headerVm.rootUrl+"logout";
			return true;
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

<div class="modal fade" id="tipsModal" style="z-index:99999">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true"></span><span class="sr-only">Close</span></button>
				<h3 class="modal-title" id="tipsTitle">温馨提示</h3>
			</div>
			<div class="modal-body">
				<font color="#fb7312" style="font-weight:bold;font-size:24px;text-align:center;">
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
