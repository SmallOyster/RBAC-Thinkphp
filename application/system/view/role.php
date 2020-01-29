<?php 
/**
 * @name 生蚝科技RBAC框架(TP)-V-角色列表
 * @author Jerry Cheung <master@xshgzs.com>
 * @since 2019-10-22
 * @version 2020-01-29
 */
?>
<style>
	.ztree li span.button.add {margin-left:2px; margin-right: -1px; background-position:-144px 0; vertical-align:top; *vertical-align:middle}
	ul.ztree {margin-top: 10px;border: 1px solid #617775;height:360px;overflow-y:scroll;overflow-x:auto;}
</style>

<!-- Vue main -->
<div id="tabVue_SystemRoleManage">
		<a @click='operateReady(1)' class="btn btn-primary btn-block">新 增 角 色</a>

		<hr>

		<div class="panel panel-default">
			<div class="panel-body">
				<table id="table_SystemRoleManage" class="table table-striped table-bordered table-hover" style="border-radius: 5px; border-collapse: separate;">
					<thead>
						<tr>
							<th>角色名称</th>
							<th>操作</th>
						</tr>
					</thead>
					<tbody></tbody>
				</table>
			</div>
		</div>

	<div class="modal fade" id="operateModal_SystemRoleManage" data-backdrop="static" data-keyboard="false">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span><span class="sr-only">Close</span></button>
					<h3 class="modal-title">{{operateModalTitle}}</h3>
				</div>
				<div class="modal-body">
					<div class="form-group">
						<label for="name">角色名称</label>
						<input class="form-control" id="name" onkeyup='if(event.keyCode==13)$("#remark").focus();' v-model="name">
						<p class="help-block">请输入<font color="green">1</font>-<font color="green">20</font>字的角色名称</p>
					</div>
					<br>
					<div class="form-group">
						<label for="remark">角色简述</label>
						<textarea class="form-control" id="remark" v-model="remark"></textarea>
					</div>
				</div>
				<div class="modal-footer">
					<button class="btn btn-warning" onclick="vm_SystemRoleManage.name='';vm_SystemRoleManage.remark='';vm_SystemRoleManage.operateType=-1;vm_SystemRoleManage.operateRoleId=0;$('#operateModal_SystemRoleManage').modal('hide');">&lt; 返回</button> <button class="btn btn-success" @click='operateSure'>{{operateModalBtn}}</button>
				</div>
			</div><!-- /.modal-content -->
		</div><!-- /.modal-dialog -->
	</div><!-- /.modal -->
	
	<div class="modal fade" id="treeModal_SystemRoleManage">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span><span class="sr-only">Close</span></button>
					<h3 class="modal-title">分配 [{{setPermissionRoleName}}] 的权限</h3>
				</div>
				<div class="modal-body">
					<ul id="tree_SystemRoleManage" class="ztree"></ul>
				</div>
				<div class="modal-footer">
					<button class="btn btn-warning" onclick="vm_SystemRoleManage.setPermissionRoleId=0;vm_SystemRoleManage.setPermissionRoleName='';$('#treeModal').modal('hide');">&lt; 返回</button> <button class="btn btn-success" @click='setPermission_sure'>确认分配权限 &gt;</button>
				</div>
			</div><!-- /.modal-content -->
		</div><!-- /.modal-dialog -->
	</div><!-- /.modal -->
</div>
<!-- ./页面内容 -->

<script>
var table_SystemRoleManage;

var vm_SystemRoleManage = new Vue({
	el:'#tabVue_SystemRoleManage',
	data:{
		deleteId:0,
		name:"",
		remark:"",
		operateType:-1,
		operateRoleId:0,
		operateModalTitle:'',
		operateModalBtn:'',
		operateOriginData:[],
		setPermissionRoleId:0,
		setPermissionRoleName:"",
		treeNode:{},
		treeCheckNodeId:"",
		treeSetting:{
			view: {
				selectedMulti: false
			},
			check: {
				enable: true
			},
			data: {
				simpleData: {
					enable: true
				}
			}
		}
	},
	methods:{
		getList:()=>{
			$.ajax({
				url:"{:url('getList')}",
				dataType:'json',
				success:ret=>{
					if(ret.code==200){
						let list=ret.data['list'];

						// 先清空表格
						table_SystemRoleManage.clear().draw();

						for(i in list){
							let operateHtml=''
							               +"<a onclick='vm_SystemRoleManage.operateReady(2,"+'"'+list[i]['id']+'","'+list[i]['name']+'","'+list[i]['remark']+'"'+")' class='btn btn-info'>编辑</a> "
							               +"<a onclick='vm_SystemRoleManage.del_ready("+'"'+list[i]['id']+'","'+list[i]['name']+'"'+")' class='btn btn-danger'>删除</a> "
							               +"<a onclick='vm_SystemRoleManage.setPermission_ready("+'"'+list[i]['id']+'","'+list[i]['name']+'"'+")' class='btn btn-success'>分配权限</a> "
							               +'<a href="'+headerVm.rootUrl+'admin/role/setPermission?id='+list[i]['id']+'&name='+list[i]['name']+'" class="btn btn-success">分配权限(旧)</a> ';

							operateHtml=list[i]['is_default']!=0?operateHtml:operateHtml+"<a onclick='vm_SystemRoleManage.setDefaultRole("+'"'+list[i]['id']+'"'+")' class='btn btn-primary'>设为默认角色</a> ";

							table_SystemRoleManage.row.add({
								0: list[i]['name'],
								1: operateHtml
							}).draw();
						}
					}
				}
			})
		},
		operateReady:function(type=1,roleId=0,name='',remark=''){
			this.operateType=type;
			this.operateRoleId=roleId;
			this.name=name;
			this.remark=remark;
			this.operateOriginData=[name,remark];

			if(type==1){
				this.operateModalTitle="新 增 角 色";
				this.operateModalBtn="确 认 新 增 角 色 >";
			}else if(type==2){
				this.operateModalTitle="编 辑 角 色";
				this.operateModalBtn="确 认 编 辑 角 色 >";
			}

			$("#operateModal_SystemRoleManage").modal("show");
		},
		operateSure:function(){
			let data={};

			// 检查是否有修改数据
			if(this.name!==this.operateOriginData[0]) data.name=this.name;
			if(this.remark!==this.operateOriginData[1]) data.remark=this.remark;
			
			if($.isEmptyObject(data)==true){
				showModalTips('请填写需要操作的数据！');
				return;
			}

			lockTabScreen('SystemRoleManage');
			
			$.ajax({
				url:"{:url('toOperate')}",
				type:'post',
				data:{'type':this.operateType,'roleId':this.operateRoleId,data},
				dataType:"json",
				error:function(e){
					console.log(e);
					unlockTabScreen('SystemRoleManage');
					showModalTips("服务器错误！<hr>请联系技术支持并提交以下错误码：<br><font color='blue'>"+e.status+"</font>");
					return false;				
				},
				success:ret=>{
					$("#operateModal").modal('hide');
					unlockTabScreen('SystemRoleManage');

					if(ret.code==200){
						alert("操作成功！");
						vm_SystemRoleManage.getList();
						return;
					}else if(ret.code==4001){
						showModalTips("数据包含非法字段！<hr>请联系技术支持<br>并提交以下错误码：AU4001-"+ret.data);
						return;
					}else if(ret.code==4002){
						showModalTips("数据包含空值！<hr>请联系技术支持<br>并提交以下错误码：AU4002-"+ret.data);
						return;
					}else if(ret.code==500){
						showModalTips("数据库错误！<br>请联系技术支持！");
						return;
					}else{
						showModalTips("系统错误！<br>请联系技术支持！");
						return;
					}
				}
			})
		},
		del_ready:function(id,name){
			this.deleteId=id;
			$("#delName_show").html(name);
			$("#delModal").modal('show');
		},
		del_sure:()=>{
			lockTabScreen('SystemRoleManage');

			$.ajax({
				url:"{:url('toDelete')}",
				type:"post",
				dataType:"json",
				data:{"id":vm_SystemRoleManage.deleteId},
				error:function(e){
					console.log(e);
					unlockTabScreen('SystemRoleManage');
					$("#delModal").modal('hide');
					showModalTips("服务器错误！<hr>请联系技术支持并提交以下错误码：<br><font color='blue'>"+e.status+"</font>");
					return false;
				},
				success:function(ret){
					unlockTabScreen('SystemRoleManage');
					$("#delModal").modal('hide');

					if(ret.code==200){
						alert("删除成功！");
						vm_SystemRoleManage.getList();
						return true;
					}else if(ret.code==1){
						showModalTips("删除失败！！！");
						return false;
					}else{
						showModalTips("系统错误！<hr>请联系技术支持并提交以下错误码：<br><font color='blue'>"+ret.code+"</font>");
						return false;
					}
				}
			});
		},
		setDefaultRole:function(id){
			lockTabScreen('SystemRoleManage');

			$.ajax({
				url:"./toSetDefaultRole",
				type:"post",
				dataType:"json",
				data:{"id":id},
				error:function(e){
					console.log(e);
					unlockTabScreen('SystemRoleManage');
					$("#delModal").modal('hide');
					showModalTips("服务器错误！<hr>请联系技术支持并提交以下错误码：<br><font color='blue'>"+e.status+"</font>");
					return false;
				},
				success:function(ret){
					unlockTabScreen('SystemRoleManage');

					if(ret.code==200){
						alert("设置成功！");
						vm_SystemRoleManage.getList();
						return true;
					}else if(ret.code==1){
						showModalTips("设置失败！！！");
						return false;
					}else if(ret.code==0){
						showModalTips("参数缺失！请联系技术支持！");
						return false;
					}else{
						showModalTips("系统错误！<hr>请联系技术支持并提交以下错误码：<br><font color='blue'>"+ret.code+"</font>");
						return false;
					}
				}
			});
		},
		getAllMenu:function(){
			lockTabScreen('SystemRoleManage');
			
			try{
				$.fn.zTree.getZTreeObj("tree_SystemRoleManage").destroy();
			}catch{

			}

			$.ajax({
				url:headerVm.apiPath+"menu/getRoleMenuForZtree",
				data:{"roleId":this.setPermissionRoleId},
				dataType:"json",
				async:false,
				error:function(e){
					console.log(e);
					unlockTabScreen('SystemRoleManage');
					showModalTips("服务器错误！<hr>请联系技术支持并提交以下错误码：<br><font color='blue'>"+e.status+"</font>");
					return false;
				},
				success:function(ret){
					vm_SystemRoleManage.treeNode=ret;
					$.fn.zTree.init($("#tree_SystemRoleManage"),vm_SystemRoleManage.treeSetting,vm_SystemRoleManage.treeNode);
					unlockTabScreen('SystemRoleManage');
				}
			});
		},
		setPermission_ready:function(id,name){
			this.setPermissionRoleId=id;
			this.setPermissionRoleName=name;
			this.getAllMenu();
			$("#treeModal_SystemRoleManage").modal("show");
		},
		getCheckedNodes:function(){
			let ids=[];
			let nodes = $.fn.zTree.getZTreeObj("tree_SystemRoleManage").getCheckedNodes();
			
			for (i=0,l=nodes.length;i<l;i++){
				ids[i]=nodes[i].id;
			}

			this.treeCheckNodeId=ids;
		},
		setPermission_sure:function(){
			this.getCheckedNodes();
			roleId=this.setPermissionRoleId;
			menuIds=this.treeCheckNodeId;

			lockTabScreen('SystemRoleManage');
			$.ajax({
				url:"{:url('toSetPermission')}",
				type:"post",
				dataType:"json",
				data:{'sensOprToken':headerVm.sensOprToken,'roleId':roleId,'menuIds':menuIds},
				error:function(e){
					console.log(e);
					unlockTabScreen('SystemRoleManage');
					showModalTips("服务器错误！<hr>请联系技术支持并提交以下错误码：<br><font color='blue'>"+e.status+"</font>");
					return false;
				},
				success:function(ret){
					unlockTabScreen('SystemRoleManage');
					$("#treeModal").modal("hide");

					if(ret.code==200){
						alert("权限分配成功！");
						return true;
					}else if(ret.tips!=""){
						showModalTips(ret.tips);
						return false;
					}else if(ret.code==500){
						showModalTips("权限分配数量不匹配！！<br>请联系管理员！");
						return false;
					}else if(ret.code==1){
						showModalTips("权限清空失败！！<br>请联系管理员！");
						return false;
					}else if(ret.code==0){
						showModalTips("参数缺失！请联系技术支持！");
						return false;
					}else{
						showModalTips("系统错误！<hr>请联系技术支持并提交以下错误码：<br><font color='blue'>"+ret.code+"</font>");
						return false;
					}
				}
			});
		}
	},
	mounted:function(){
		table_SystemRoleManage=$('#table_SystemRoleManage').DataTable({});
		this.getList();
	}
});
</script>

<div class="modal fade" id="delModal">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span><span class="sr-only">Close</span></button>
				<h3 class="modal-title" id="ModalTitle">温馨提示</h3>
			</div>
			<div class="modal-body">
				<center>
				<font color="red" style="font-weight:bolder;font-size:23px;">确定要删除下列角色吗？</font>
				<br><br>
				<font color="blue" style="font-weight:bolder;font-size:23px;"><p id="delName_show"></p></font>
				</center>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-success" data-dismiss="modal">&lt; 返回</button> <button type="button" class="btn btn-danger" onclick="vm_SystemRoleManage.del_sure();">确定 &gt;</button>
			</div>
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div><!-- /.modal -->

</body>
</html>
