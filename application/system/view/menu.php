<?php 
/**
 * @name 生蚝科技RBAC框架(TP)-V-菜单管理
 * @package System/Menu
 * @author Jerry Cheung <master@xshgzs.com>
 * @since 2019-11-01
 * @version 2020-02-08
 */
?>
<style>
	.ztree li span.button.add {margin-left:2px; margin-right: -1px; background-position:-144px 0; vertical-align:top; *vertical-align:middle}
	ul.ztree {margin-top: 10px;border: 1px solid #617775;background: #f0f6e4;height:360px;overflow-y:scroll;overflow-x:auto;}
</style>

<!-- Vue main -->
<div id="tabVue_SystemMenuManage">
	<a @click="operateReady(0,0,'home','系统主菜单')" class="btn btn-primary btn-block">新 增 主 菜 单</a>

	<ul id="tree_SystemMenuManage" class="ztree"></ul>

	<div class="modal fade" id="operateModal_SystemMenuManage" data-backdrop="static" data-keyboard="false">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span><span class="sr-only">Close</span></button>
					<h3 class="modal-title" id="operateTitle_SystemMenuManage"></h3>
				</div>
				<div class="modal-body">
					<div id="fatherMenuDiv" class="alert" style="font-size:16px;background-color:#caffdf;color:#e029ff;border-color:white;">父菜单：<i id='menuIcon' aria-hidden="true" style="font-weight:bold;"></i></div>
					<div class="panel-body">
						<div class="form-group">
							<label for="name">菜单类型</label>
							<select class="form-control" v-model="menuType" onkeyup='if(event.keyCode==13)$("#name").focus()'>
								<option value="1">菜单</option>
								<option value="2" disabled>按钮（未开发）</option>
								<option value="3" disabled>接口（未开发）</option>
							</select>
						</div>
						<br>
						<div class="form-group">
							<label for="name">菜单名称</label>
							<input class="form-control" v-model="name" onkeyup='if(event.keyCode==13)$("#icon").focus()'>
							<p class="help-block">请输入<font color="green">1</font>-<font color="green">20</font>字的菜单名称</p>
						</div>
						<br>
						<div class="form-group">
							<label for="icon">菜单图标 ( 预览: <i id="iconPreviewEle" aria-hidden="true"></i> ) &nbsp;&nbsp;&nbsp;&nbsp;<button class="btn btn-primary" onclick="$('#icon').val('circle-o');vm_SystemMenuManage.icon='circle-o';vm_SystemMenuManage.iconPreview();">使用默认图标</button></label>
							<input class="form-control" id="icon" v-model="icon" onkeyup='if(event.keyCode==13)$("#uri").focus()' @input='iconPreview'>
							<p class="help-block">请输入Font-Awesome图标名称，无需输入前缀“fa-”，输入后可实时在上方预览</p>
						</div>
						<br>
						<div class="form-group">
							<label for="uri">链接URL</label>
							<input class="form-control" v-model="uri" @keyup.enter='add'>
							<p class="help-block">
								若此菜单为父菜单，请留空此项<br>
								如此菜单需跳出站外，请<a @click="inputJumpOutURI">点此输入</a>
							</p>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<button class="btn btn-warning" onclick="vm_SystemMenuManage.icon='';vm_SystemMenuManage.uri='';vm_SystemMenuManage.name='';vm_SystemMenuManage.operateType=-1;vm_SystemMenuManage.operateMenuId=0;$('#operateModal_SystemMenuManage').modal('hide');">&lt; 返回</button> <button class="btn btn-success" @click='operateSure' id="operateBtn_SystemMenuManage"></button>
				</div>
			</div><!-- /.modal-content -->
		</div><!-- /.modal-dialog -->
	</div><!-- /.modal -->
</div>

<script>
var vm_SystemMenuManage = new Vue({
	el:'#tabVue_SystemMenuManage',
	data:{
		deleteId:0,
		menuType:1,
		operateType:0,
		operateMenuId:'',
		name:'',
		icon:'',
		uri:'',
		zNodes:{},
		setting:{
			view: {
				selectedMulti: false
			},
			data: {
				simpleData: {
					enable: true
				}
			},
			view: {
				addHoverDom: addHoverDom,
				removeHoverDom: removeHoverDom,
			}
		}
	},
	methods:{
		getAllMenu:()=>{
			lockTabScreen('SystemMenuManage');
			
			try{
				$.fn.zTree.destroy();
			}catch{

			}

			$.ajax({
				url:headerVm.apiPath+"menu/getList",
				data:{'isZtree':1},
				dataType:"json",
				async:false,
				error:function(e){
					console.log(e);
					unlockTabScreen('SystemMenuManage');
					showModalTips("服务器错误！<hr>请联系技术支持并提交以下错误码：<br><font color='blue'>"+e.status+"</font>");
					return false;
				},
				success:function(ret){
					vm_SystemMenuManage.zNodes=ret;
					$.fn.zTree.init($("#tree_SystemMenuManage"),vm_SystemMenuManage.setting,vm_SystemMenuManage.zNodes);
					unlockTabScreen('SystemMenuManage');
				}
			});
		},
		iconPreview:()=>{
			$("#iconPreviewEle").attr("class","fa fa-"+vm_SystemMenuManage.icon);
		},
		inputJumpOutURI:()=>{
			uri=prompt("请输入需要跳转到的网站的完整URL（包括HTTP/HTTPS头）","http://");

			if(uri=="http://" || uri=="https://" || uri==""){
				alert("请输入需要跳转到的网站的完整URL（包括HTTP/HTTPS头）！");
				return false;
			}else if(uri==null){
				return;
			}else{
				vm_SystemMenuManage.uri="show/jumpout/"+uri;
				$("#uri").val("show/jumpout/"+uri);
			}
		},
		operateReady:function(type=0,menuId='',icon='',name='',uri=''){
			if(type==0){
				$("#fatherMenuDiv").show();
				$('#operateTitle_SystemMenuManage').html('新 增 主 菜 单');
				$('#operateBtn_SystemMenuManage').html('确 认 新 增 菜 单 &gt;');
			}else if(type==1){
				$("#fatherMenuDiv").show();
				$('#operateTitle_SystemMenuManage').html('新 增 菜 单');
				$('#operateBtn_SystemMenuManage').html('确 认 新 增 菜 单 &gt;');
			}else if(type==2){
				$("#fatherMenuDiv").hide();
				$('#operateTitle_SystemMenuManage').html('编 辑 菜 单');
				$('#operateBtn_SystemMenuManage').html('确 认 编 辑 菜 单 &gt;');
				this.name=name;
				this.uri=uri;
				this.icon=icon;
				this.iconPreview();
			}else{
				return false;
			}

			this.operateMenuId=menuId;
			this.operateType=type;
			$('#menuIcon').attr('class','fa fa-'+icon);
			$('#menuIcon').html(' '+name);
			$('#operateModal_SystemMenuManage').modal('show');
		},
		operateSure:function(){
			unlockTabScreen('SystemMenuManage');

			if(this.name==""){
				unlockTabScreen('SystemMenuManage');
				showModalTips("请输入菜单名称！");
				return false;
			}
			if(this.name.length<1 || this.name.length>20){
				unlockTabScreen('SystemMenuManage');
				showModalTips("请输入 1-20字 的菜单名称！");
				return false;
			}
			if(this.icon==""){
				unlockTabScreen('SystemMenuManage');
				showModalTips("请输入菜单图标名称！");
				return false;
			}

			$.ajax({
				url:"{:url('toOperate')}",
				type:"post",
				data:{"operateType":this.operateType,"menuId":this.operateMenuId,"menuType":this.menuType,"name":this.name,"icon":this.icon,"uri":this.uri},
				dataType:"json",
				error:function(e){
					console.log(e.responseText);
					unlockTabScreen('SystemMenuManage');
					showModalTips("服务器错误！<hr>请联系技术支持并提交以下错误码：<br><font color='blue'>"+e.status+"</font>");
					return false;
				},
				success:function(ret){
					unlockTabScreen('SystemMenuManage');

					if(ret.code==200){
						$("#operateModal_SystemMenuManage").modal('hide');
						alert("操作成功！");
						vm_SystemMenuManage.getAllMenu();
						return true;
					}else if(ret.code==500){
						showModalTips("操作失败！！！");
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
		deleteReady:function(id,name){
			this.deleteId=id;
			$("#delName_show").html(name);
			$("#delModal").modal('show');
		},
		deleteSure:()=>{
			unlockTabScreen('SystemMenuManage');

			$.ajax({
				url:"{:url('delete')}",
				type:"post",
				dataType:"json",
				data:{"menuId":vm_SystemMenuManage.deleteId},
				error:function(e){
					console.log(JSON.stringify(e));
					unlockTabScreen('SystemMenuManage');
					$("#delModal").modal('hide');
					showModalTips("服务器错误！<hr>请联系技术支持并提交以下错误码：<br><font color='blue'>"+e.status+"</font>");
					return false;
				},
				success:function(ret){
					unlockTabScreen('SystemMenuManage');

					if(ret.code==200){
						$("#delModal").modal('hide');
						alert("删除成功！");
						vm_SystemMenuManage.getAllMenu();
						return true;
					}else if(ret.code==1){
						$("#delModal").modal('hide');
						showModalTips("删除失败！！！");
						return false;
					}else if(ret.code==403001){
						$("#delModal").modal('hide');
						showModalTips("Token无效！<hr>Tips:请勿在提交前打开另一页面哦~");
						return false;
					}else if(ret.code==0){
						showModalTips("参数缺失！请联系技术支持！");
						return false;
					}else{
						$("#delModal").modal('hide');
						showModalTips("系统错误！<hr>请联系技术支持并提交以下错误码：<br><font color='blue'>"+ret.code+"</font>");
						return false;
					}
				}
			});
		}
	}
});

vm_SystemMenuManage.getAllMenu();

function addHoverDom(treeId, treeNode) {
	var aObj=$("#"+treeNode.tId+"_a");
	var editStr=""
		+"<button class='btn btn-info' id='treeBtn_edit_"+treeNode.id+"' onclick="+'"'+"vm_SystemMenuManage.operateReady(2,'"+treeNode.id+"','"+treeNode.menuIcon+"','"+treeNode.menuName+"','"+treeNode.uri+"')"+'"'+"'>编辑</button> "
		+"<button class='btn btn-danger' id='treeBtn_delete_"+treeNode.id+"' onclick="+'"'+"vm_SystemMenuManage.deleteReady('"+treeNode.id+"','"+treeNode.name+"')"+'"'+"'>删除</button> "
		+"<button class='btn btn-success' id='treeBtn_add_"+treeNode.id+"' onclick="+'"'+"vm_SystemMenuManage.operateReady(1,'"+treeNode.id+"','"+treeNode.menuIcon+"','"+treeNode.menuName+"')"+'"'+"'>新增子菜单</button>";
	
	// 如果已存在button就返回
	if($("#treeBtn_edit_"+treeNode.id).length>0) return;
	if($("#treeBtn_delete_"+treeNode.id).length>0) return;
	if($("#treeBtn_add_"+treeNode.id).length>0) return;
	
	aObj.append(editStr);
	
	// 三级菜单不允许新增子菜单
	if(treeNode.level>=2) $("#treeBtn_add_"+treeNode.id).remove();
}

function removeHoverDom(treeId, treeNode) {
	$("#treeBtn_edit_"+treeNode.id).unbind().remove();
	$("#treeBtn_delete_"+treeNode.id).unbind().remove();
	$("#treeBtn_add_"+treeNode.id).unbind().remove();
}
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
				<font color="red" style="font-weight:bolder;font-size:23px;">确定要删除下列菜单吗？</font>
				<br><br>
				<font color="blue" style="font-weight:bolder;font-size:23px;"><p id="delName_show"></p></font>
				</center>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-success" data-dismiss="modal">&lt; 返回</button> <button type="button" class="btn btn-danger" onclick="vm_SystemMenuManage.deleteSure()">确定 &gt;</button>
			</div>
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div><!-- /.modal -->

</body>
</html>
