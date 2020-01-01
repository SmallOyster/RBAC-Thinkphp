<?php
/**
 * @name 生蚝科技RBAC框架(TP)-C-用户管理
 * @package System
 * @author Jerry Cheung <master@xshgzs.com>
 * @since 2019-10-27
 * @version 2019-11-03
 */

namespace app\system\controller;

use app\common\controller\Safe;
use think\Db;

class User
{	
	public function index()
	{
		//checkTabloadToken(inputGet('tabloadToken'),inputGet('tabId',1));
		
		$sensOprToken=sha1(time().makeId());
		return view('/user',['tabId'=>inputGet('tabId'),'sensOprToken'=>$sensOprToken]);
	}
	
	
	public function getList()
	{
		Safe::checkLogin(1);
		
		$list=Model('User')->select();
		
		returnAjaxData(200,'success',['list'=>$list]);
	}
	
	
	public function operate()
	{
		$type=inputPost('type',0,1);
		$userId=inputPost('userId',0,1);
		$userData=inputPost('userData',0,1);
		
		if($type==2){
		$query=Modal('User')->save($userData,['id',$userId]);
		}elseif($type==1){
			$userData['id']=makeUUID();
			$query=Modal('User')->save($userData);
		}
			
			if($query==1) returnAjaxData(200,'success');
			else returnAjaxData(500,'Database error',[],'操作用户失败！');
	}
	
	
	public function delete()
	{
		$sensOprToken=inputPost('sensOprToken',0,1);
		$userId=inputPost('userId',0,1);
		
		$query=model('User')->destroy([$userId]);
		
		if($query==1) returnAjaxData(200,'success');
		else returnAjaxData(500,'Database error',[],'删除用户失败！');
	}
}
