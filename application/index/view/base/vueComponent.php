<?php
/**
 * @name Vue-Component-多选模态框
 * @author Jerry Cheung <master@xshgzs.com>
 * @since 2020-01-03
 * @version 2020-01-05
 */
?>
<style>
	.border{
		border:1px solid #e8e8e8;
		font-size: 14px;
	}
	.r{
		margin:0 16px;
	}
	.r a{
		float: left;
		margin: 9px 20px 9px 0;
		text-decoration: none;
	}

	.checked{
		margin-bottom: 10px;
	}

	.checked div.pull-left{
		margin-right: 6px;
		padding: 4px 6px;
		margin-bottom: 4px;
		border: 1px solid #dedede;
	}

	.bdd{
		border-bottom:1px dashed #dedede;
	}

	.checked div.pull-left a{
		text-decoration: none;
	}
	
	.checkNode{
		color:#6aab20;
		font-weight: bold;
	}

	.clearfix a:hover,a:visited,a:active{
		color:#e68a02;
	}	
</style>

<template id="choose-item-template">
	<div class="modal fade" id="chooseItemModalMain">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span><span class="sr-only">Close</span></button>
					<h3 class="modal-title">{{title}}</h3>
				</div>
				<div class="modal-body">
					<div class="checked clearfix">
						<div v-for="checkedId in checkedList" class="pull-left">
							<span>{{itemList[checkedId]}}</span>
							<a @click="checked(checkedId)"><span>×</span></a>
						</div>
					</div>

					<div class="border">
						<div class="r clearfix">
							<a v-for="(name,id) in itemList" :class="[(checkedList.indexOf(id)>=0)?'checkNode':'']" style="hover{color:red;}" @click="checked(id)">{{name}}</a>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<button class="btn btn-default" @click="clearAll">关闭</button>
					<button class="btn btn-primary" @click="returnCheckedId">保存</button>
				</div>
			</div>
		</div>
	</div>
</template>

<script>
var chooseItemModalVue = {
	template: '#choose-item-template',
	props:['title','itemList','propCheckedList'],
	data: function () {
		return {
			checkedList:this.propCheckedList
		}
	},
	methods:{
		returnCheckedId:function(){
			this.$emit('update:checked-id',this.checkedList);
			$("#chooseItemModalMain").modal('hide');
		},
		show:function(){
			// 第二次调用时更新data
			this.checkedList=this.propCheckedList;
			$("#chooseItemModalMain").modal('show');
			return true;
		},
		checked:function(id){
			let loc = this.checkedList.indexOf(id);
			
			if(loc>=0){
				this.checkedList.splice(loc,1);
			}else{
				this.checkedList.push(id);
			}
		},
		clearAll:function(){
			$("#chooseItemModalMain").modal('hide');
		}
	}
};
</script>
<!-- /.多选模态框 -->
