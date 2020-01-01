<?php
/**
 * @name 生蚝科技RBAC框架(TP)-A-菜单
 * @package Api
 * @author Jerry Cheung <master@xshgzs.com>
 * @since 2019-11-03
 * @version 2020-01-01
 */

namespace app\api\controller;

use think\Session;
use app\common\controller\Safe;
use app\common\controller\Rbac;

class Menu
{
	public function getCurrentUserMenu()
	{
		$roleId=Session::get('currentRoleId');

		if($roleId==''){
			returnAjaxData(40301,'User not login');
		}

		$obj_Rbac=new Rbac();
		returnAjaxData(200,'success',['treeData'=>$obj_Rbac->getAllMenuByRole($roleId)]);
	}


	public function getList()
	{
		$isZtree=inputGet('isZtree',1,1);
		$list=model('Menu')->select();
		
		if($isZtree==1){
			$key=[];
		
			foreach($list as $key=>$info){
				$rtn[$key]['id']=$info['id'];
				$rtn[$key]['pId']=$info['father_id'];
				$rtn[$key]['menuIcon']=$info['icon'];
				$rtn[$key]['menuName']=$info['name'];
				$rtn[$key]['uri']=$info['uri'];
				$rtn[$key]['type']=$info['type'];
				$rtn[$key]['name']=$info['type']==1?urlencode($info['name']):($info['type']==2?'(按钮)'.urlencode($info['name']):'(接口)'.urlencode($info['name']));
			}
			
			die(urldecode(json_encode($rtn)));
		}else{
			returnAjaxData(200,'success',['list'=>$list]);
		}
	}


	/**
	 * 获取适配zTree的角色菜单
	 * @return string zTree格式的菜单JSON字符串
	 */
	public function getRoleMenuForZtree()
	{
		$roleId=inputGet('roleId',1,1);
		$rtn=[];
		$allPermission=[];

		$menuList=model('Menu')->select();
		$permissionList=model('RolePermission')
			->where('role_id',$roleId)
			->select();

		foreach($permissionList as $permissionInfo){
			array_push($allPermission,$permissionInfo['menu_id']);
		}

		foreach($menuList as $key=>$info){
			$rtn[$key]['id']=(int)$info['id'];
			$rtn[$key]['pId']=(int)$info['father_id'];
			$rtn[$key]['menuIcon']=$info['icon'];
			$rtn[$key]['menuName']=$info['name'];
			$rtn[$key]['uri']=$info['uri'];
			$rtn[$key]['type']=$info['type'];
			$rtn[$key]['name']=$info['type']==1?urlencode($info['name']):($info['type']==2?'(按钮)'.urlencode($info['name']):'(接口)'.urlencode($info['name']));
			if(in_array($info['id'],$allPermission)) $rtn[$key]['checked']=true;
		}

		die(urldecode(json_encode($rtn)));
	}
}
