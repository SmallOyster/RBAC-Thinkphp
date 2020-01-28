<?php
/**
 * @name 生蚝科技RBAC框架(TP)-V-个人资料
 * @package Index/User
 * @author Jerry Cheung <master@xshgzs.com>
 * @since 2019-10-27
 * @version 2020-01-21
 */
?>

<!-- Vue main -->
<div id="tabVue_{$tabId}">
	<!-- Page main -->
	<div class="box">
		<div class="box-body">

			<div class="row">
				<div class="form-group">
					<label class="col-sm-2 col-lg-2 control-label">通行证登录用户名 / UserName</label>
					<div class="col-sm-10 col-lg-10">
						<input class="form-control" :value="userInfo['userName']" disabled>
					</div>
				</div>
			</div><br>

			<div class="row">
				<div class="form-group">
					<label class="col-sm-2 col-lg-2 control-label">通行证昵称 / NickName</label>
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

			<button class="btn btn-primary" style="width:48%" onclick="headerVm.removeTab('{$tabId}');headerVm.changeTab(0);">&lt; 取 消 操 作</button> <button class="btn btn-success" style="width:48%" @click="updateUserInfo">确 认 修 改 &gt;</button>

		</div>
	</div>
</div>

<script>
var vm_{$tabId} = new Vue({
	el:'#tabVue_{$tabId}',
	data:{
		userInfo:headerVm.userInfo,
		inputData:{
			nickName:headerVm.userInfo.nickName,
			phone:headerVm.userInfo.phone,
			email:headerVm.userInfo.email
		}
	},
	methods:{
		updateUserInfo:function(){
			let updateData={};
			
			for(i in this.inputData){
				if(this.inputData[i]!=this.userInfo[i]){
					updateData[changeCamelToUnderline(i)]=this.inputData[i];
				}
			}
			
			if(updateData=={}){
				showModalTips('请修改你想要修改的数据');
				return false;			
			}
			
			lockTabScreen('{$tabId}');
			$.ajax({
				url:headerVm.rootUrl+"index/user/toUpdateProfile",
				type:"post",
				data:{'data':updateData},
				dataType:"json",
				error:function(e){
					unlockTabScreen('{$tabId}');
					showModalTips("服务器错误！"+e.status);
					console.log(JSON.stringify(e));
					return false;
				},
				success:function(ret){
					unlockTabScreen('{$tabId}');
					console.log("ajax-suc",JSON.stringify(ret.data));

					if(ret.code==200){
						showModalTips("修改成功<br>资料变动将在您下次登录时呈现");
						return true;
					}else if(ret.tips!=""){
						showModalTips(ret.tips);
						return false;					
					}
				}
			})
		}
	}
});
</script>
