<?php
/**
 * @name 生蚝科技RBAC框架(TP)-C-角色管理
 * @package System
 * @author Jerry Cheung <master@xshgzs.com>
 * @since 2019-11-02
 * @version 2020-01-11
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
		$roleData=inputPost('roleData',0,1);
		
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
	}
}
