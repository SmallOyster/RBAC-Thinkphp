<?php
/**
 * @name 生蚝科技RBAC框架(TP)-C-用户
 * @package Index
 * @author Jerry Cheung <master@xshgzs.com>
 * @since 2019-10-27
 * @version 2020-02-08
 */

namespace app\index\controller;

use think\Session;

class User
{
	public function profile()
	{
		checkTabloadToken(inputGet('tabloadToken'),inputGet('tabId'));
		return view('profile',['tabId'=>inputGet('tabId')]);
	}
	
	
	public function toUpdateProfile()
	{
		$data=inputPost('data',0,1);

		$data['update_time']=date('Y-m-d H:i:s');
		
		$query=model('User')
			->allowField(
				['phone','nick_name','email','update_time']
			)
			->save($data,['id'=>Session::get('userInfo.id')]);
			
		returnAjaxData(200,'success',[$query]);
	}
}
