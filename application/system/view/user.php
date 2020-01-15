<?php
/**
 * @name 生蚝科技RBAC框架(TP)-V-用户管理
 * @package System/User
 * @author Jerry Cheung <master@xshgzs.com>
 * @since 2019-10-27
 * @version 2020-01-15
 */
?>

<!-- Vue main -->
<div id="tabVue_{$tabId}">
	<!-- Page main -->
	<div id="panel_{$tabId}" class="panel panel-default">
		<div class="panel-body">
			<table id="table_{$tabId}" class="table table-striped table-bordered table-hover" style="border-radius: 5px; border-collapse: separate;">
				<thead>
					<tr>
						<th>用户名</th>
						<th>昵称</th>
						<th>手机号</th>
						<th>邮箱</th>
						<th>操作</th>
					</tr>
				</thead>
				<tbody></tbody>
			</table>
		</div>
	</div><!-- /.Page main -->

	<div class="modal fade" id="operateModal" data-backdrop="static" data-keyboard="false">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span><span class="sr-only">Close</span></button>
					<h3 class="modal-title">{{operateModalTitle}}</h3>
				</div>
				<div class="modal-body">
					<div class="form-group">
						<label for="userName">用户名</label>
						<input class="form-control" id="userName" v-model="operateInputData.userName" onkeyup='vm.checkDuplicate("user_name",this.value);if(event.keyCode==13){$("#nickName").focus();}'>
						<p class="help-block">请输入<font color="green">4</font>-<font color="green">20</font>字的用户名</p>
						<p class="help-block" id="user_name_duplicateTips" style="display:none;color:red;font-weight: bold;font-size:16px">当前已存在此用户名，请修改！</p>
					</div>
					<br>
					<div class="form-group">
						<label for="nickName">昵称</label>
						<input class="form-control" id="nickName" v-model="operateInputData.nickName" onkeyup='if(event.keyCode==13){$("#phone").focus();}'>
					</div>
					<br>
					<div class="form-group">
						<label>角色</label>
						<button @click="chooseRole" class="btn btn-primary">选择角色</button>
					</div>
					<br>
					<div class="form-group">
						<label for="phone">手机号</label>
						<input type="number" class="form-control" id="phone" v-model="operateInputData.phone" onkeyup='if(this.value.length==11){vm.checkDuplicate("phone",this.value);}if(event.keyCode==13){$("#email").focus();}'>
						<p class="help-block">目前仅支持中国大陆的手机号码</p>
						<p class="help-block" id="phone_duplicateTips" style="display:none;color:red;font-weight: bold;font-size:16px">当前已存在此手机号，请修改！</p>
					</div>
					<br>
					<div class="form-group">
						<label for="email">邮箱</label>
						<input type="email" class="form-control" id="email" v-model="operateInputData.email" onkeyup='if(this.value.indexOf("@")!=-1){vm.checkDuplicate("email",this.value);}if(event.keyCode==13){$("#ssoUnionId").focus();}'>
						<p class="help-block" id="email_duplicateTips" style="display:none;color:red;font-weight: bold;font-size:16px">当前已存在此邮箱，请修改！</p>
					</div>
				</div>
				<div class="modal-footer">
					<button class="btn btn-warning" onclick="vm_{$tabId}.operateInputData={};vm_{$tabId}.operateType=-1;vm_{$tabId}.operateUserId=0;$('#operateModal').modal('hide');">&lt; 返回</button> <button id="submitBtn" class="btn btn-success" @click='operateSure'>{{operateModalBtn}}</button>
				</div>
			</div><!-- /.modal-content -->
		</div><!-- /.modal-dialog -->
	</div><!-- /.modal -->

	<choose-item-modal ref='chooseItemModal'
		:title="chooseItemModalName"
		:item-list='roleList'
		:prop-checked-list="operateUserRoleIds"
		:checked-id.sync="checkedRoleList">
	</choose-item-modal>

</div><!-- /.Vue main -->

<script>
var table_{$tabId};

var vm_{$tabId} = new Vue({
	el:'#tabVue_{$tabId}',
	data:{
		sensOprToken:"{$sensOprToken}",
		updateId:0,
		statusNum:1,
		resetId:0,
		deleteId:0,
		operateType:0,
		operateUserId:0,
		operateUserRoleIds:[],
		operateInputData:{},
		operateOriginData:[],
		operateModalTitle:'',
		operateModalBtn:'',
		roleList:{},
		checkedRoleList:[],
		chooseItemModalName:""
	},
	components: {
		'choose-item-modal' : chooseItemModalVue
	},
	methods:{
		getList:()=>{
			lockTabScreen('{$tabId}');

			$.ajax({
				url:"{:url('system/user/getList')}",
				dataType:'json',
				error:function(e){
					unlockTabScreen();
					showModalTips("服务器错误！"+e.status);
					console.log(JSON.stringify(e));
					return false;
				},
				success:ret=>{
					if(ret.code==200){
						let list=ret.data['list'];

						// 先清空表格
						table_{$tabId}.clear().draw();

						for(i in list){							
							let operateHtml=''
							               +'<a onclick="vm_{$tabId}.operateReady(2,'+"'"+list[i]['id']+"','"+list[i]['user_name']+"','"+list[i]['nick_name']+"','"+list[i]['phone']+"','"+list[i]['email']+"','"+list[i]['role_id']+"'"+');" class="btn btn-primary">编辑</a> '
							               +"<a onclick='vm_{$tabId}.resetPwd_ready("+'"'+list[i]['id']+'","'+list[i]['nick_name']+'"'+")' class='btn btn-warning'>重置密码</a> "
							               +"<a onclick='vm_{$tabId}.del_ready("+'"'+list[i]['id']+'","'+list[i]['nick_name']+'"'+")' class='btn btn-danger'>删除</a>";

							table_{$tabId}.row.add({
								0: list[i]['user_name'],
								1: list[i]['nick_name'],
								2: list[i]['phone'],
								3: list[i]['email'],
								4: operateHtml
							}).draw();
						}

						unlockTabScreen('{$tabId}');
						$("#panel_{$tabId}").width($("#table_{$tabId}").width()+30);
					}
				}
			})
		},
		operateReady:(type=1,userId=0,userName='',nickName='',phone='',email='',roleIds='')=>{
			vm_{$tabId}.operateType=type;
			vm_{$tabId}.operateUserId=userId;
			vm_{$tabId}.operateInputData={
				userName:userName,
				nickName:nickName,
				phone:phone,
				email:email
			};
			vm_{$tabId}.operateOriginData={
				userName:userName,
				nickName:nickName,
				phone:phone,
				email:email,
				roleIds:roleIds
			};
			
			if(type==1){
				vm_{$tabId}.operateModalTitle="新 增 用 户";
				vm_{$tabId}.operateModalBtn="确 认 新 增 用 户 >";
			}else if(type==2){
				vm_{$tabId}.operateModalTitle="编 辑 用 户";
				vm_{$tabId}.operateModalBtn="确 认 编 辑 用 户 >";
			}

			vm_{$tabId}.getAllRole(roleIds.split(","));
			$("#operateModal").modal("show");
		},
		operateSure:function(){
			let userData={};
			let roleIds=this.checkedRoleList;
			let type=this.operateType;

			// 检查是否有修改数据
			if(vm_{$tabId}.operateInputData.userName!==vm_{$tabId}.operateOriginData.userName) userData.user_name=vm_{$tabId}.operateInputData.userName;
			if(vm_{$tabId}.operateInputData.nickName!==vm_{$tabId}.operateOriginData.nickName) userData.nick_name=vm_{$tabId}.operateInputData.nickName;
			if(vm_{$tabId}.operateInputData.phone!==vm_{$tabId}.operateOriginData.phone) userData.phone=vm_{$tabId}.operateInputData.phone;
			if(vm_{$tabId}.operateInputData.email!==vm_{$tabId}.operateOriginData.email) userData.email=vm_{$tabId}.operateInputData.email;
			if(roleIds.join(',')!==vm_{$tabId}.operateOriginData.roleIds) userData.role_id=roleIds;
			
			if($.isEmptyObject(userData)==true){
				showModalTips('请填写需要操作的数据！');
				vm_{$tabId}.operateInputData={};
				vm_{$tabId}.operateType=-1;
				vm_{$tabId}.operateUserId=0;
				$('#operateModal').modal('hide');
				return false;
			}
			
			lockTabScreen('{$tabId}');

			$.ajax({
				url:"{:url('system/user/toOperate')}",
				type:'post',
				data:{'type':type,'userId':this.operateUserId,userData},
				dataType:"json",
				error:function(e){
					console.log(e);
					unlockTabScreen('{$tabId}');
					showModalTips("服务器错误！<hr>请联系技术支持并提交以下错误码：<br><font color='blue'>"+e.status+"</font>");
					return false;
				},
				success:ret=>{
					$("#operateModal").modal('hide');
					unlockTabScreen('{$tabId}');

					if(ret.code==200){
						showModalTips("操作成功！");
						vm_{$tabId}.getList();

						// 操作类型为新增，显示用户基本资料及初始密码
						if(type==1){
							$("#info_userName_show").html(vm_{$tabId}.operateInputData.userName);
							$("#info_nickName_show").html(vm_{$tabId}.operateInputData.nickName);
							$("#info_originPwd_show").html(ret.data['originPassword']);
							$("#infoModal").modal('show');
						}
						
						return true;
					}else if(ret.tips!=""){
						showModalTips(ret.tips);
						return false;
					}else{
						showModalTips("系统错误！<br>请联系技术支持！<hr>错误码："+ret.code);
						return false;
					}
				}
			})
		},
		del_ready:(id,name)=>{
			vm_{$tabId}.deleteId=id;
			$("#delName_show").html(name);
			$("#delModal").modal('show');
		},
		del_sure:function(){
			lockTabScreen('{$tabId}');
			
			let that=this;
			$.ajax({
				url:"{:url('delete')}",
				type:"post",
				dataType:"json",
				data:{"sensOprToken":this.sensOprToken,"userId":this.deleteId},
				error:function(e){
					console.log(e);
					unlockTabScreen('{$tabId}');
					$("#delModal").modal('hide');
					showModalTips("服务器错误！<hr>请联系技术支持并提交以下错误码：<br><font color='blue'>"+e.status+"</font>");
					return false;
				},
				success:function(ret){
					unlockTabScreen('{$tabId}');
					$("#delModal").modal('hide');

					if(ret.code==200){
						showModalTips("操作成功！");
						vm_{$tabId}.getList();
						return true;
					}else if(ret.tips!=""){
						showModalTips(ret.tips);
						return false;
					}else{
						showModalTips("系统错误！<br>请联系技术支持！<hr>错误码："+ret.code);
						return false;
					}
				}
			});
		},
		getAllRole:function(roleIds=[]){
			lockTabScreen('{$tabId}');

			// 是否已经获取过所有角色
			if(JSON.stringify(vm_{$tabId}.roleList)=="{}"){
				$.ajax({
					url:headerVm.apiPath+"role/getList",
					dataType:'json',
					error:function(e){
						console.log(JSON.stringify(e));
						unlockTabScreen('{$tabId}');
						showModalTips("服务器错误！<hr>请联系技术支持并提交以下错误码：<br><font color='blue'>"+e.status+"</font>");
						return false;
					},
					success:function(ret){
						unlockTabScreen('{$tabId}');

						if(ret.code==200){
							let list=[];

							for(i in ret.data['list']){
								let info=ret.data['list'][i];
								vm_{$tabId}.roleList[info['id']]=info['name'];

								// 检查当前用户是否已拥有此角色
								if(roleIds.indexOf(info['id'])>=0){
									vm_{$tabId}.operateUserRoleIds.push(info['id']);
								}
							}

							// 去重
							vm_{$tabId}.operateUserRoleIds=vm_{$tabId}.operateUserRoleIds.filter((item, index, self) => self.indexOf(item) === index)
							return true;
						}else{
							showModalTips("系统错误！<hr>请联系技术支持并提交以下错误码：<br><font color='blue'>"+ret.code+"</font>");
							return false;
						}
					}
				});
			}else{
				let roleList=vm_{$tabId}.roleList;
				let list=[];

				for(i in roleList){
					// 检查当前用户是否已拥有此角色
					if(roleIds.indexOf(i)>=0){
						list.push(i);
					}
				}

				// 去重
				vm_{$tabId}.operateUserRoleIds=list.filter((item, index, self) => self.indexOf(item) === index)
				unlockTabScreen('{$tabId}');
			}
		},
		checkDuplicate:function(field='',value=''){
			$.ajax({
				url:headerVm.apiPath+'user/checkDuplicate',
				data:{'field':field,'value':value},
				dataType:'json',
				error:function(e){
					console.log(e);
					unlockTabScreen('{$tabId}');
					showModalTips("服务器错误！<hr>请联系技术支持并提交以下错误码：<br><font color='blue'>"+e.status+"</font>");
					return false;
				},
				success:ret=>{
					if(ret.code==200){
						$("#"+field+"_duplicateTips").hide(500);
						$("#submitBtn").removeAttr("disabled");
						return true;
					}else{
						$("#"+field+"_duplicateTips").show(500);
						$("#submitBtn").attr("disabled",true);
					}
				}
			})
		},
		chooseRole:function(){
			vm_{$tabId}.chooseItemModalName="请选择["+vm_{$tabId}.nickName+"]的角色";
			vm_{$tabId}.$refs.chooseItemModal.show();
		}
	},
	mounted:function(){
		table_{$tabId}=$('#table_{$tabId}').DataTable({
			"pageLength": 25,
			"order":[[0,'asc']],
			"columnDefs":[{
				"targets":[2],
				"orderable": false
			}]
		});
		this.getList();
	}
});
</script>

<div class="modal fade" id="delModal">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span><span class="sr-only">Close</span></button>
				<h3 class="modal-title">温馨提示</h3>
			</div>
			<div class="modal-body">
				<center>
				<font color="red" style="font-weight:bolder;font-size:23px;">确定要删除下列用户吗？</font>
				<br><br>
				<font color="blue" style="font-weight:bolder;font-size:23px;"><p id="delName_show"></p></font>
				</center>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-success" data-dismiss="modal">&lt; 返回</button> <button type="button" class="btn btn-danger" onclick="vm_{$tabId}.del_sure();">确定 &gt;</button>
			</div>
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div><!-- /.modal -->
