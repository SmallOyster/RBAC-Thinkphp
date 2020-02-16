<?php
/**
 * @key 生蚝科技RBAC框架(TP)-V-系统配置
 * @package System/Setting
 * @author Jerry Cheung <master@xshgzs.com>
 * @since 2019-10-25
 * @version 2020-02-08
 */
?>

<!-- Vue main -->
<div id="tabVue_SystemSetting">

	<!-- Page main -->
	<div id="panel_SystemSetting" class="panel panel-default">
		<div class="panel-body">
			<table id="table_SystemSetting" class="table table-striped table-bordered table-hover" style="border-radius: 5px; border-collapse: separate;">
				<thead>
					<tr>
						<th>名称</th>
						<th>内容</th>
						<th>操作</th>
					</tr>
				</thead>
				<tbody></tbody>
			</table>
		</div>
	</div><!-- /.Page main -->

	<div class="modal fade" id="editModal_SystemSetting" data-backdrop="static" data-keyboard="false">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span><span class="sr-only">Close</span></button>
					<h3 class="modal-title">{{name}}-系统参数修改</h3>
				</div>
				<div class="modal-body">
					<div class="form-group">
						<label>键名</label>
						<input class="form-control" v-model="key" disabled>
					</div>
					<div class="form-group">
						<label for="name">中文描述</label>
						<input class="form-control" v-model="name">
					</div>
					<div class="form-group">
						<label for="value">值</label>
						<input class="form-control" v-model="value">
						<p class="help-block">Tips</p>
					</div>
				</div>
				<div class="modal-footer">
					<button class="btn btn-primary" data-dismiss="modal">&lt; 返回</button> <button class="btn btn-warning" @click='edit_sure'>确认编辑 &gt;</button>
				</div>
			</div><!-- /.modal-content -->
		</div><!-- /.modal-dialog -->
	</div><!-- /.modal -->

</div><!-- /.Vue main -->

<script>
var table_SystemSetting;

var vm_SystemSetting = new Vue({
	el:'#tabVue_SystemSetting',
	data:{
		key:"",
		oldChineseName:"",
		name:"",
		oldValue:"",
		value:""
	},
	methods:{
		getList:()=>{
			lockTabScreen('SystemSetting');

			$.ajax({
				url:"{:url('system/setting/getList')}",
				dataType:'json',
				error:function(e){
					unlockTabScreen('SystemSetting');
					showModalTips("服务器错误！"+e.status);
					console.log(JSON.stringify(e));
					return false;
				},
				success:ret=>{
					if(ret.code==200){
						let list=ret.data['list'];

						// 先清空表格
						table_SystemSetting.clear().draw();

						for(i in list){							
							let operateHtml=''
							               +"<a onclick='vm_SystemSetting.edit_ready("+'"'+list[i]['key']+'","'+list[i]['name']+'","'+list[i]['value']+'"'+")' class='btn btn-warning'>编辑</a>";

							table_SystemSetting.row.add({
								0: list[i]['name'],
								1: list[i]['value'],
								2: operateHtml
							}).draw();
						}

						unlockTabScreen('SystemSetting');
						$("#panel_SystemSetting").width($("#table_SystemSetting").width()+30);
					}
				}
			})
		},
		edit_ready:(key,name,value)=>{
			vm_SystemSetting.oldChineseName=name;
			vm_SystemSetting.oldValue=value;
			vm_SystemSetting.key=key;
			vm_SystemSetting.name=name;
			vm_SystemSetting.value=value;
			$("#editModal_SystemSetting").modal('show');
		},
		edit_sure:()=>{			
			if(vm_SystemSetting.name==vm_SystemSetting.oldChineseName && vm_SystemSetting.value==vm_SystemSetting.oldValue){
				showModalTips("请编辑您所要修改的数据！");
				return false;
			}
			
			lockTabScreen('SystemSetting');
			$.ajax({
				url:"{:URL('system/setting/save')}",
				type:"post",
				data:{"key":vm_SystemSetting.key,"name":vm_SystemSetting.name,"value":vm_SystemSetting.value},
				dataType:'json',
				error:function(e){
					unlockTabScreen('SystemSetting');
					showModalTips("服务器错误！"+e.status);
					console.log(JSON.stringify(e));
					return false;
				},
				success:ret=>{
					unlockTabScreen('SystemSetting');
					$("#editModal").modal("hide");
					
					if(ret.code==200){
						alert("修改成功！");
						vm_SystemSetting.getList();
						return true;
					}else{
						showModalTips("[修改失败]<br>"+ret.tips);
						console.log(ret);
						return false;
					}
				}
			});
		}
	},
	mounted:function(){
		table_SystemSetting=$('#table_SystemSetting').DataTable({
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
