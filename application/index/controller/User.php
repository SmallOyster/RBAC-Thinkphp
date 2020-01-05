<?php
/**
 * @name 生蚝科技RBAC框架(TP)-C-用户
 * @package Index
 * @author Jerry Cheung <master@xshgzs.com>
 * @since 2019-10-27
 * @version 2020-01-02
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
}
