<?php
/**
 * m190319_120101_report_module_insert_menu
 * 
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2019 OMMU (www.ommu.co)
 * @created date 18 March 2019, 19:04 WIB
 * @link https://github.com/ommu/ommu
 *
 */

use Yii;
use app\models\Menu;

class m190319_120101_report_module_insert_menu extends \yii\db\Migration
{
	public function up()
	{
		$tableName = Yii::$app->db->tablePrefix . 'ommu_core_menus';
		if(Yii::$app->db->getTableSchema($tableName, true)) {
			$this->batchInsert('ommu_core_menus', ['name', 'module', 'icon', 'parent', 'route', 'order', 'data'], [
				['Reports', 'report', null, Menu::getParentId('Dashboard#rbac'), '/report/admin/index', null, null],
				['Report Setting', 'report', null, Menu::getParentId('Settings#rbac'), '/report/setting/admin/index', null, null],
			]);
		}
	}
}
