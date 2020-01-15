<?php
/**
 * @name 生蚝科技RBAC框架(TP)-C-菜单管理
 * @package System
 * @author Jerry Cheung <master@xshgzs.com>
 * @since 2019-11-01
 * @version 2020-01-11
 */

namespace app\system\controller;

use app\common\controller\Safe;
use think\Session;

class Menu
{	
	public function __construct()
	{
		$obj_Safe=new Safe();
		$obj_Safe->checkLogin();
	}
	
	
	public function index()
	{
		checkTabloadToken(inputGet('tabloadToken'),inputGet('tabId',1));
		return view('/menu',['tabId'=>inputGet('tabId')]);
	}
	
	
	public function getList()
	{
		$list=model('Menu')->select();
		
		returnAjaxData(200,'success',['list'=>$list]);
	}
	
	
	public function toOperate()
	{
		$operateType=inputPost('operateType',0,1);
		$menuId=inputPost('menuId',0,1);
		$menuType=inputPost('menuType',0,1);
		$name=inputPost('name',0,1);
		$icon=inputPost('icon',0,1);
		$uri=inputPost('uri',1,1);
		
		if(substr($uri,0,13)=='show/jumpout/'){
			$jumpToURL=urlencode(substr($uri,13));
			$uri='show/jumpout/'.$jumpToURL;
		}
		
		if($operateType==2){
			$query=model('Menu')->save([
				'type'=>$menuType,
				'name'=>$name,
				'icon'=>$icon,
				'uri'=>$uri
			],['id'=>$menuId]);
		}else{
			$query=model('Menu')->data([
				'id'=>makeUUID(),
				'father_id'=>$menuId,
				'type'=>$menuType,
				'name'=>$name,
				'icon'=>$icon,
				'uri'=>$uri
			])->save();
		}

		if($query==1){
			returnAjaxData(200,'success');
		}else{
			returnAjaxData(500,'Failed to operate',[],'操作系统菜单失败');
		}
	}
}
