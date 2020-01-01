<?php
/**
 * @name 生蚝科技RBAC框架(TP)-A-用户
 * @package Api
 * @author Jerry Cheung <master@xshgzs.com>
 * @since 2019-12-28
 * @version 2020-01-01
 */

namespace app\api\controller;

use think\Session;
use app\common\controller\Safe;

class User
{	
	public function getCurrentUserInfo()
	{
		$userInfo=Session::get('userInfo');

		if(count($userInfo)<1) returnAjaxData(40301,'User not login');
		else $userInfo['currentRoleId']=Session::get('currentRoleId');
		
		returnAjaxData(200,'success',$userInfo);
	}
	
	
	static public function generateToken($userId='',$roleId='')
	{
		$userId=$userId!=''?$userId:inputGet('userId',0,1);
		$roleId=$roleId!=''?$roleId:inputGet('roleId',0,1);
		$userInfo=model('User')->getInfo($userId);

		if($userInfo==[]) return ['token'=>''];
		
		$tokenData=[];
		$tokenData['userId']=$userId;
		$tokenData['roleList']=$userInfo['role'];
		$tokenData['currentRoleId']=$roleId;
		$token=generateToken($tokenData);
		
		return ['token'=>$token,'expireTime'=>7200];
	}
}
