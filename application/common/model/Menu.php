<?php
/**
 * @name 生蚝科技RBAC框架(TP)-M-菜单
 * @package Common
 * @author Jerry Cheung <master@xshgzs.com>
 * @since 2019-12-28
 * @version 2020-01-01
 */

namespace app\common\model;
use think\Model;

class Menu extends Model
{
	protected $pk = 'id';
	
	public function getList($condition=[])
	{
		$list=$this
			->where($condition)
			->select();

		return $list->toArray();
	}


	public function getListByRole($roleId='',$fatherId='')
	{
		$list=$this
			->alias('m')
			->field('m.*')
			->where('m.father_id',$fatherId)
			->where('rp.role_id',$roleId)
			->join('role_permission rp','m.id=rp.menu_id')
			->select();
			
		return $list->toArray();
	}
}
