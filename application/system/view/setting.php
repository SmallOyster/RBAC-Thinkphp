<?php
/**
 * @name 生蚝科技RBAC框架(TP)-V-系统配置
 * @package System/Setting
 * @author Jerry Cheung <master@xshgzs.com>
 * @since 2019-10-25
 * @version 2019-10-28
 */
?>

<!-- Vue main -->
<div id="tabVue-{$tabId}">

	<!-- Page main -->
	<div id="panel{$tabId}" class="panel panel-default">
		<div class="panel-body">
			<table id="table{$tabId}" class="table table-striped table-bordered table-hover" style="border-radius: 5px; border-collapse: separate;">
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

	<div class="modal fade" id="editModal" data-backdrop="static" data-keyboard="false">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span><span class="sr-only">Close</span></button>
					<h3 class="modal-title">{{chineseName}}-系统参数修改</h3>
				</div>
				<div class="modal-body">
					<div class="form-group">
						<label for="name">键名</label>
						<input class="form-control" v-model="name" disabled>
					</div>
					<div class="form-group">
						<label for="name">中文描述</label>
						<input class="form-control" v-model="chineseName">
					</div>
					<div class="form-group">
						<label for="name">值</label>
						<input class="form-control" v-model="value">
						<p class="help-block">Tips</p>
					</div>
				</div>
				<div class="modal-footer">
					<button class="btn btn-primary" onclick="$('#editModal').modal('hide');">&lt; 返回</button> <button class="btn btn-warning" @click='edit_sure'>确认编辑 &gt;</button>
				</div>
			</div><!-- /.modal-content -->
		</div><!-- /.modal-dialog -->
	</div><!-- /.modal -->

</div><!-- /.Vue main -->

<script>
var table{$tabId};

var vm{$tabId} = new Vue({
	el:'#tabVue-{$tabId}',
	data:{
		name:"",
		oldChineseName:"",
		chineseName:"",
		oldValue:"",
		value:""
	},
	methods:{
		getList:()=>{
			lockScreen();

			$.ajax({
				url:"{:URL('system/setting/getList')}",
				dataType:'json',
				error:function(e){
					unlockScreen();
					showModalTips("服务器错误！"+e.status);
					console.log(JSON.stringify(e));
					return false;
				},
				success:ret=>{
					if(ret.code==200){
						let list=ret.data['list'];

						// 先清空表格
						table{$tabId}.clear().draw();

						for(i in list){							
							let operateHtml=''
							               +"<a onclick='vm{$tabId}.edit_ready("+'"'+list[i]['name']+'","'+list[i]['chinese_name']+'","'+list[i]['value']+'"'+")' class='btn btn-warning'>编辑</a>";

							table{$tabId}.row.add({
								0: list[i]['chinese_name'],
								1: list[i]['value'],
								2: operateHtml
							}).draw();
						}

						unlockScreen();
						$("#panel{$tabId}").width($("#table{$tabId}").width()+30);
					}
				}
			})
		},
		edit_ready:(name,chineseName,value)=>{
			vm{$tabId}.oldChineseName=chineseName;
			vm{$tabId}.oldValue=value;
			vm{$tabId}.name=name;
			vm{$tabId}.chineseName=chineseName;
			vm{$tabId}.value=value;
			$("#editModal").modal('show');
		},
		edit_sure:()=>{			
			if(vm{$tabId}.chineseName==vm{$tabId}.oldChineseName && vm{$tabId}.value==vm{$tabId}.oldValue){
				showModalTips("请编辑您所要修改的数据！");
				return false;
			}
			
			lockScreen();
			$.ajax({
				url:"{:URL('system/setting/save')}",
				type:"post",
				data:{"name":vm{$tabId}.name,"chineseName":vm{$tabId}.chineseName,"value":vm{$tabId}.value},
				dataType:'json',
				error:function(e){
					unlockScreen();
					showModalTips("服务器错误！"+e.status);
					console.log(JSON.stringify(e));
					return false;
				},
				success:ret=>{
					unlockScreen();
					$("#editModal").modal("hide");
					
					if(ret.code==200){
						alert("修改成功！");
						vm{$tabId}.getList();
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
		table{$tabId}=$('#table{$tabId}').DataTable({
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