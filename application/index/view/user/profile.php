<?php
/**
 * @name 生蚝科技RBAC框架(TP)-V-个人资料
 * @package Index/User
 * @author Jerry Cheung <master@xshgzs.com>
 * @since 2019-10-27
 * @version 2020-02-10
 */
?>

<!-- Vue main -->
<div id="tabVue_UserProfile">
	<button data-toggle="modal" data-target="#updatePasswordModal" class="btn btn-block btn-warning"><i class="fa fa-key" aria-hidden="true"></i> 修 改 密 码 &gt;</button>

	<br>

	<!-- Page main -->
	<div class="box">
		<div class="box-body">
			<div class="row">
				<div class="form-group">
					<label class="col-sm-2 col-lg-2 control-label">登录用户名 / UserName</label>
					<div class="col-sm-10 col-lg-10">
						<input class="form-control" :value="userInfo['userName']" disabled>
					</div>
				</div>
			</div><br>

			<div class="row">
				<div class="form-group">
					<label class="col-sm-2 col-lg-2 control-label">用户昵称 / NickName</label>
					<div class="col-sm-10 col-lg-10">
						<input class="form-control" v-model="inputData.nickName">
					</div>
				</div>
			</div><br>

			<div class="row">
				<div class="form-group">
					<label class="col-sm-2 col-lg-2 control-label">手机号码 / Phone</label>
					<div class="col-sm-10 col-lg-10">
						<input type="number" class="form-control" v-model="inputData.phone">
					</div>
				</div>
			</div><br>

			<div class="row">
				<div class="form-group">
					<label class="col-sm-2 col-lg-2 control-label">邮箱地址 / Email</label>
					<div class="col-sm-10 col-lg-10">
						<input type="email" class="form-control" v-model="inputData.email">
					</div>
				</div>
			</div><br>

			<div class="row">
				<div class="form-group">
					<label class="col-sm-2 col-lg-2 control-label">注册日期 / Register Date</label>
					<div class="col-sm-10 col-lg-10">
						<input class="form-control" :value="userInfo['createTime']" disabled>
					</div>
				</div>
			</div>

			<hr>

			<button class="btn btn-primary" style="width:49%" onclick="headerVm.removeTab('UserProfile');headerVm.changeTab(0);">&lt; 取 消 操 作</button> <button class="btn btn-success" style="width:49%" @click="updateUserInfo">确 认 修 改 &gt;</button>
		</div>
	</div>

	<div class="modal fade" id="updatePasswordModal">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true"></span><span class="sr-only">Close</span></button>
					<h3 class="modal-title">修改密码</h3>
				</div>
				<div class="modal-body">
					<div class="row">
						<div class="form-group">
							<label class="col-sm-2 col-lg-2 control-label">旧密码</label>
							<div class="col-sm-10 col-lg-10">
								<input type="password" class="form-control" v-model="oldPassword">
							</div>
						</div>
					</div><br>

					<div class="row">
						<div class="form-group">
							<label class="col-sm-2 col-lg-2 control-label">新密码</label>
							<div class="col-sm-10 col-lg-10">
								<input type="password" class="form-control" v-model="newPassword">
							</div>
						</div>
					</div><br>

					<div class="row">
						<div class="form-group">
							<label class="col-sm-2 col-lg-2 control-label">请再次输入新密码</label>
							<div class="col-sm-10 col-lg-10">
								<input type="password" class="form-control" v-model="surePassword">
							</div>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-primary" data-dismiss="modal">&lt; 关闭</button>
					<button type="button" class="btn btn-warning" @click="updatePassword">确认修改 &gt;</button>
				</div>
			</div>
		</div>
	</div>
</div>

<script>
var vm_UserProfile = new Vue({
	el:'#tabVue_UserProfile',
	data:{
		oldPassword:"",
		newPassword:"",
		surePassword:"",
		userInfo:headerVm.userInfo,
		inputData:{
			nickName:headerVm.userInfo.nickName,
			phone:headerVm.userInfo.phone,
			email:headerVm.userInfo.email
		}
	},
	methods:{
		updatePassword:function(){
			if(this.oldPassword==""){
				showModalTips("请正确输入旧密码！");
				return false;
			}
			if(this.newPassword.length<6 || this.newPassword.length>20){
				showModalTips("请输入6~20位的新密码！");
				return false;
			}
			if(this.surePassword!=this.newPassword){
				showModalTips("两次输入的新密码不相符！");
				return false;
			}
			if(this.oldPassword==this.newPassword){
				showModalTips("新密码与旧密码相同！");
				return false;
			}

			lockTabScreen('UserProfile');

			$.ajax({
				url:headerVm.rootUrl+"index/user/toUpdatePassword",
				type:"post",
				data:{'data':updateData},
				dataType:"json",
				error:function(e){
					unlockTabScreen('UserProfile');
					showModalTips("服务器错误！"+e.status);
					console.log(JSON.stringify(e));
					return false;
				},
				success:function(ret){
					unlockTabScreen('UserProfile');

					if(ret.code==200){
						showModalTips("修改成功<br>请在下次登录时使用新密码");
						return true;
					}else if(ret.tips!=""){
						showModalTips(ret.tips);
						return false;
					}else{
						showModalTips("系统错误！<br>错误码："+ret.code);
						return false;
					}
				}
			})
		},
		updateUserInfo:function(){
			let updateData={};
			
			for(i in this.inputData){
				if(this.inputData[i]!=this.userInfo[i]){
					updateData[changeCamelToUnderline(i)]=this.inputData[i];
				}
			}
			
			if(checkObjectNull(updateData)){
				showModalTips('请修改你想要修改的数据');
				return false;
			}
			
			lockTabScreen('UserProfile');
			$.ajax({
				url:headerVm.rootUrl+"index/user/toUpdateProfile",
				type:"post",
				data:{'data':updateData},
				dataType:"json",
				error:function(e){
					unlockTabScreen('UserProfile');
					showModalTips("服务器错误！"+e.status);
					console.log(JSON.stringify(e));
					return false;
				},
				success:function(ret){
					unlockTabScreen('UserProfile');

					if(ret.code==200){
						showModalTips("修改成功<br>资料变动将在您下次登录时呈现");
						return true;
					}else if(ret.tips!=""){
						showModalTips(ret.tips);
						return false;
					}else{
						showModalTips("系统错误！<br>错误码："+ret.code);
						return false;
					}
				}
			})
		}
	}
});
</script>
