<?php
/**
 * @name 生蚝科技RBAC框架(TP)-C-角色管理
 * @package System
 * @author Jerry Cheung <master@xshgzs.com>
 * @since 2019-11-02
 * @version 2020-01-30
 */

namespace app\system\controller;

use app\common\controller\Safe;

class Role
{	
	public function __construct()
	{
		$obj_Safe=new Safe();
		$obj_Safe->checkLogin();
	}
	
	
	public function index()
	{
		checkTabloadToken(inputGet('tabloadToken'),inputGet('tabId',1));
		return view('/role',['tabId'=>inputGet('tabId')]);
	}
	
	
	public function getList()
	{
		$list=model('Role')->select();
		
		returnAjaxData(200,'success',['list'=>$list]);
	}
	
	
	public function toOperate()
	{
		$type=inputPost('type',0,1);
		$roleId=inputPost('roleId',0,1);
		$roleData=inputPost('data',0,1);
		
		if($type==2){
			$query=model('Role')
				->allowField(true)
				->save($roleData,['id'=>$roleId]);
		}elseif($type==1){
			$roleData['id']=makeUUID();
			$query=model('Role')
				->allowField(true)
				->save($roleData);
		}
			
			if($query==1) returnAjaxData(200,'success');
			else returnAjaxData(500,'Database error',[],'操作角色失败');
	}
	
	
	public function toDelete()
	{
		checkSensOprToken(inputPost('sensOprToken',0,1));
		
		$roleId=inputPost('roleId',0,1);
		$query=model('Role')->delete([$roleId]);
		
		if($query==1) returnAjaxData(200,'success');
		else returnAjaxData(500,'Failed to delete role: Database error',[],'删除角色失败');
	}


	public function toSetDefaultRole()
	{
		checkSensOprToken(inputPost('sensOprToken',0,1));

		$roleId=inputPost('roleId',0,1);
	}


	public function toSetPermission()
	{
		checkSensOprToken(inputPost('sensOprToken',0,1));

		$roleId=inputPost('roleId',0,1);
		$menuIds=inputPost('menuIds',0,1);

		$roleInfo=model('Role')->find($roleId);
		if(count($roleInfo)<1) returnAjaxData(4001,'Role not found',[],'角色信息不存在');

		// 先清空原有权限
		model('RolePermission')->destroy(['role_id'=>$roleId]);

		// 组合成一个二维数组
		$list=[];
		foreach($menuIds as $menuId){
			array_push($list,['role_id'=>$roleId,'menu_id'=>$menuId]);
		}

		// 插入
		$query=model('RolePermission')->saveAll($list);

		$totalPermission=count($query);
		$shouldPermission=count($menuIds);

		if($totalPermission==$shouldPermission) returnAjaxData(200,'success',['totalPermission'=>count($query)]);
		elseif($totalPermission>=1) returnAjaxData(4002,'Failed to match permission number when insert role permission',['totalPermission'=>$totalPermission,'shouldPermission'=>$shouldPermission],'权限分配数量不匹配<hr>已选权限数：'.$shouldPermission.'<br>成功分配权限数：'.$totalPermission);
		else returnAjaxData(500,'Failed to set role permission: database error',[],'数据库错误<br>分配权限失败');
	}
}
