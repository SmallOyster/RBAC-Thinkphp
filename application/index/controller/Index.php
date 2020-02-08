<?php
/**
 * @name 生蚝科技RBAC框架(TP)-C-主
 * @author Jerry Cheung <master@xshgzs.com>
 * @since 2019-10-20
 * @version 2020-01-30
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
		$tabloadToken=md5(time());
		$tokenSess=['value'=>$tabloadToken,'expireTime'=>time()+3600];
		$sensOprToken=sha1(time().makeUUID());
		
		Session::set('tabloadToken',$tokenSess);
		Session::set('sensOprToken',$sensOprToken);

		return view('base/home',['tabloadToken'=>$tabloadToken,'sensOprToken'=>$sensOprToken]);
	}
}
