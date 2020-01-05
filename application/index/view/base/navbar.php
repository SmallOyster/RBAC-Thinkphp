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