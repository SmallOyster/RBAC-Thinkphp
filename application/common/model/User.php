<?php
/**
 * @name 生蚝科技RBAC框架(TP)-M-用户
 * @package Common
 * @author Jerry Cheung <master@xshgzs.com>
 * @since 2019-11-04
 * @version 2020-01-01
 */

namespace app\common\model;

use think\Model;

class User extends Model
{
	protected $pk = 'id';
	
	public function resetPassword($userId='')
	{
		$userInfo=$this->get(['id'=>$userId]);
		return $userInfo;
	}
	
	
	public function getInfo($userId='')
	{
		$userInfo=$this
			->field('id,status,phone,email')
			->field('sso_union_id AS ssoUnionId')
			->field('user_name AS userName')
			->field('nick_name AS nickName')
			->field('create_time AS createTime')
			->find($userId);

		if($userInfo==null) return [];
		
		$roleInfo=model('UserRole')
			->alias('ur')
			->field('r.id,r.name')
			->join('role r','ur.role_id=r.id')
			->where('ur.user_id',$userId)
			->select();

		$userInfo['role']=$roleInfo->toArray();
		
		return $userInfo->hidden(['password','key_id'])->toArray();
	}
}
