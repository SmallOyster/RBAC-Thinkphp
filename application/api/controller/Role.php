<?php
/**
 * @name 生蚝科技RBAC框架(TP)-C-角色接口
 * @package Api
 * @author Jerry Cheung <master@xshgzs.com>
 * @since 2019-11-02
 * @version 2019-11-03
 */

namespace app\api\controller;

use app\common\controller\Safe;
//use app\common\model\Menu;
//use app\common\model\RolePermission;
use think\Db;

class Role
{	
	public function getList()
	{
		$list=model('Role')->select();
		
		returnAjaxData(200,'success',['list'=>$list]);
	}
	
	
	public function setPermission()
	{
		dump(model("User")->resetPassword("1"));
	}
}
