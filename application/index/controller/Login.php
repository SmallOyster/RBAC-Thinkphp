<?php
/**
 * @name 生蚝科技RBAC框架(TP)-C-用户登录
 * @package Index
 * @author Jerry Cheung <master@xshgzs.com>
 * @since 2020-01-02
 * @version 2020-01-03
 */

namespace app\index\controller;

use think\Session;
use app\common\controller\Safe;
use app\api\controller\User as ApiUser;

class Login
{
	public function login()
	{
		return view('login');
	}
	
	
	public function logout()
	{
		Session::clear();
		gotourl(url('/login'));
	}
	
	
	public function toChangeRole()
	{
		$obj_Safe=new Safe();
		$obj_Safe->checkLogin(0);

		$roleInfo=Session::get('userInfo.role');
		$roleId=inputPost('roleId',0,1);

		if(!isset($roleInfo[$roleId])){
			returnAjaxData(40001,'Current user does not have permission for this role',['roleInfo'=>$roleInfo],'当前用户无此角色权限');
		}else{
			Session::set('currentRoleId',$roleId);
			returnAjaxData(200,'success');
		}
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
