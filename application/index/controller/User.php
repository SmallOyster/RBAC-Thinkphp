<?php
/**
 * @name 生蚝科技RBAC框架(TP)-C-用户
 * @package Index
 * @author Jerry Cheung <master@xshgzs.com>
 * @since 2019-10-27
 * @version 2020-01-01
 */

namespace app\index\controller;

use think\Session;
use app\api\controller\User as ApiUser;

class User
{
	public function editProfile()
	{
		checkTabloadToken(inputGet('tabloadToken'),inputGet('tabId',1));
		return view('editProfile');
	}


	public function login()
	{
		return view('login');
	}


	public function tl()
	{
		return view('testLogin');
	}

	
	public function testlogin()
	{
		$u=inputGet('u',0,1);
		
		Session::set('isLogin',true);
		$token=ApiUser::generateToken($u);

		if($token['token']=='') returnAjaxData(403,'No user');
		else returnAjaxData(200,'success',$token);
	}


	public function toLogin()
	{
		$userName=inputPost('userName',0,1);
		$password=inputPost('password',0,1);
		$hashPassword=sha1(md5($password).$userName);
		
		$userInfo=model('User')
			->get([
				'user_name'=>$userName,
				'password'=>$hashPassword
			]);
			
		if($userInfo==null){
			returnAjaxData(4031,'Invaild username or password',[],'用户名或密码错误<br>请重新输入');
		}else{
			$userId=$userInfo->id;
			$userInfo=model('User')->getInfo($userId);
			
			if($userInfo['role']==[]){
				returnAjaxData(4001,'Failed to get user role info');
			}else{
				$currentRoleId=$userInfo['role'][0]['id'];

				foreach($userInfo['role'] as $key=>$info){
					$userInfo['role'][$info['id']]=$info['name'];
					unset($userInfo['role'][$key]);
				}
			}
			
			Session::set('isLogin',true);
			Session::set('userInfo',$userInfo);
			Session::set('currentRoleId',$currentRoleId);

			model('User')
				->where('id',$userId)
				->update(['last_login'=>date('Y-m-d H:i:s')]);
			
			$data=[
				'url'=>url('/','','',true),
				'roleInfo'=>json_encode($userInfo['role']),
				'currentRoleId'=>$currentRoleId
			];
				
			returnAjaxData(200,'success',$data);
		}
	}
}
