<link rel="stylesheet" href="https://cdn.bootcss.com/bootstrap/3.3.7/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdn.bootcss.com/font-awesome/4.7.0/css/font-awesome.min.css">
<link rel="stylesheet" href="https://cdn.bootcss.com/admin-lte/2.4.8/css/AdminLTE.min.css">
<link rel="stylesheet" href="https://cdn.bootcss.com/datatables/1.10.19/css/dataTables.bootstrap.min.css">
<link rel="stylesheet" href="https://cdn.bootcss.com/zTree.v3/3.5.28/css/zTreeStyle/zTreeStyle.min.css">

<link rel="stylesheet" href="https://static.xshgzs.com/css/adminlte_cyan.css">
<link rel="stylesheet" href="{:url('/public/css/myStyle2.css')}">

<script src="https://cdn.bootcss.com/jquery/3.1.0/jquery.min.js"></script>
<!-- 开发环境版本，包含了有帮助的命令行警告 -->
<script src="https://cdn.jsdelivr.net/npm/vue/dist/vue.js"></script>
<script src="https://cdn.bootcss.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
<script src="https://cdn.bootcss.com/admin-lte/2.4.8/js/adminlte.min.js"></script>
<script src="https://cdn.bootcss.com/datatables/1.10.16/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.bootcss.com/datatables/1.10.16/js/dataTables.bootstrap.min.js"></script>
<script src="https://cdn.bootcss.com/zTree.v3/3.5.28/js/jquery.ztree.core.min.js"></script>
<script src="https://cdn.bootcss.com/zTree.v3/3.5.28/js/jquery.ztree.excheck.min.js"></script>
<script src="https://cdn.bootcss.com/zTree.v3/3.5.28/js/jquery.ztree.exedit.min.js"></script>
<script src="https://cdn.bootcss.com/zTree.v3/3.5.33/js/jquery.ztree.exhide.min.js"></script>

<script src="https://static.xshgzs.com/js/dataTables.responsive.js"></script>
<script src="https://static.xshgzs.com/js/utils.js"></script>

<style>
th{
	text-align:center;
	vertical-align:middle;
}
.modal {
	overflow-y: auto;
}
</style>

<script>
function lockTabScreen(tabId,content=''){
	$('#tabPanel-'+tabId).append(
		'<div class="loadingwrap" id="loadingwrap_'+tabId+'"><div class="spinner"><div class="rect1"></div><div class="rect2"></div><div class="rect3"></div><div class="rect4"></div><div class="rect5"></div><br><font style="color:yellow;font-size:24px;font-weight:bold;">' +
		content + '</font></div></div>'
	);

	// 防止挡住tab导航栏
	var topPx=15+$(".nav-tabs").height()+$(".main-header .navbar").height();
	if(!isPhone()){
		topPx=topPx;
	}else{
		topPx=topPx+$(".logo-lg").height();
	}

	$("#loadingwrap_"+tabId).attr('style','top:'+topPx+'px;');
}


function unlockTabScreen(tabId){
	// 0.3s后再删除，防止闪现
	setTimeout(function(){
		$("#loadingwrap_"+tabId).remove();
	},300);	
}
</script>
