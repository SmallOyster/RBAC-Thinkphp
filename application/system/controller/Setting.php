<?php
/**
 * @name 生蚝科技RBAC框架(TP)-C-系统配置
 * @package System
 * @author Jerry Cheung <master@xshgzs.com>
 * @since 2019-10-20
 * @version 2019-10-27
 */

namespace app\system\controller;

use app\common\controller\Safe;
use think\Db;

class Setting
{	
	public function index()
	{
		checkTabloadToken(inputGet('tabloadToken'),inputGet('tabId'));
		return view('/setting',['tabId'=>inputGet('tabId')]);
	}
	
	
	public function getList()
	{
		$obj_Safe=new Safe();
		$obj_Safe->checkLogin(1);
		
		$list=Db::name('setting')->select();
		
		returnAjaxData(200,'success',['list'=>$list]);
	}
	
	
	public function save()
	{
		$name=inputPost('name',0,1);
		$chineseName=inputPost('chineseName',0,1);
		$value=inputPost('value',0,1);
		
		$query=Db::name('setting')->where('name',$name)
			->update(['chinese_name'=>$chineseName,'value'=>$value]);
			
			if($query==1) returnAjaxData(200,'success');
			else returnAjaxData(500,'Database error',[],'配置已被删除或为无效的值！');
	}
}
