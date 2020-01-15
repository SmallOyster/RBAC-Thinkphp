<?php
/**
 * @name 生蚝科技RBAC框架(TP)-C-用户管理
 * @package System
 * @author Jerry Cheung <master@xshgzs.com>
 * @since 2019-10-27
 * @version 2020-01-11
 */

namespace app\system\controller;

use app\common\controller\Safe;
use think\Session;

class User
{
	public function __construct()
	{
		$obj_Safe=new Safe();
		$obj_Safe->checkLogin();
	}
	
	
	public function index()
	{
		checkTabloadToken(inputGet('tabloadToken'),inputGet('tabId',1));
		
		$sensOprToken=sha1(time().makeUUID());
		return view('/user',['tabId'=>inputGet('tabId'),'sensOprToken'=>$sensOprToken]);
	}
	
	
	public function getList()
	{
		$list=model('User')
			->alias('u')
			->field('u.*')
			->field('GROUP_CONCAT(ur.role_id) AS role_id')
			->join('user_role ur','ur.user_id = u.id','LEFT')
			->group('u.id')
			->select();
		
		returnAjaxData(200,'success',['list'=>$list]);
	}
	
	
	public function toOperate()
	{
		$type=inputPost('type',0,1);
		$userId=inputPost('userId',0,1);
		$userData=inputPost('userData',0,1);

		// 操作用户基本信息
		if($type==2){
			$query=model('User')
				->allowField(true)
				->save($userData,['id'=>$userId]);
		}elseif($type==1){
			$userData['id']=makeUUID();
			$query=model('User')
				->allowField(true)
				->save($userData);
		}

		$operateSuccess=$query;

		// 操作用户角色
		if(isset($userData['role_id'])){
			$deleteUserRoleQuery=model('UserRole')
				->where('user_id',$userId)
				->delete();

			// 是否成功删除旧的用户角色
			if($deleteUserRoleQuery>=1){
				$userRoleIds=[];
				foreach($userData['role_id'] as $roleId){
					$data=[
						'user_id'=>$userId,
						'role_id'=>$roleId,
						'create_user_id'=>Session::get('userInfo.id'),
						'create_ip'=>getIP(),
						'update_user_id'=>Session::get('userInfo.id'),
						'update_ip'=>getIP()
					];
					array_push($userRoleIds,$data);
				}

				$insertUserRoleQuery=model('UserRole')->insertAll($userRoleIds);
				$operateSuccess+=$insertUserRoleQuery;
			}else{
				returnAjaxData(40001,'Failed to delete old user role',[$deleteUserRoleQuery],'删除用户角色失败');
			}
		}

		if($operateSuccess>=1){
			returnAjaxData(200,'success');
		}else{
			returnAjaxData(500,'Database error',[],'操作用户失败');
		}
	}
	
	
	public function delete()
	{
		checkSensOprToken(inputPost('sensOprToken',0,1));
		
		$userId=inputPost('userId',0,1);
		$query=model('User')->delete([$userId]);
		
		if($query==1) returnAjaxData(200,'success');
		else returnAjaxData(500,'Failed to delete user: Database error',[],'删除用户失败');
	}
}
