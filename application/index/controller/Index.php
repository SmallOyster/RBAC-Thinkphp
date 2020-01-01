<?php
/**
 * @name 生蚝科技RBAC框架(TP)-C-主
 * @author Jerry Cheung <master@xshgzs.com>
 * @since 2019-10-20
 * @version 2019-12-28
 */

namespace app\index\controller;

use think\Session;
use app\common\controller\Safe;

class Index
{
	public function __construct()
	{
		$obj_Safe=new Safe();
		$obj_Safe->checkLogin(0);
	}


	public function index()
	{
		$tabloadToken=sha1(makeUUID());

		$tokenSess=['value'=>$tabloadToken,'expireTime'=>time()+3600];
		Session::set('tabloadToken',$tokenSess);

		return view('base/home',['tabloadToken'=>$tabloadToken]);
	}
}
