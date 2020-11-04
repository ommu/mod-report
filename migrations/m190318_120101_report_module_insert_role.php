<?php
/**
 * m190318_120101_report_module_insert_role
 * 
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2019 OMMU (www.ommu.id)
 * @created date 18 March 2019, 19:04 WIB
 * @link https://github.com/ommu/mod-report
 *
 */

use Yii;

class m190318_120101_report_module_insert_role extends \yii\db\Migration
{
	public function up()
	{
		$tableName = Yii::$app->db->tablePrefix . 'ommu_core_auth_item';
        if (Yii::$app->db->getTableSchema($tableName, true)) {
			$this->batchInsert('ommu_core_auth_item', ['name', 'type', 'data', 'created_at'], [
				['reportModLevelAdmin', '2', '', time()],
				['reportModLevelModerator', '2', '', time()],
				['/report/admin/*', '2', '', time()],
				['/report/admin/index', '2', '', time()],
				['/report/history/admin/*', '2', '', time()],
				['/report/history/comment/*', '2', '', time()],
				['/report/history/status/*', '2', '', time()],
				['/report/history/user/*', '2', '', time()],
				['/report/setting/admin/index', '2', '', time()],
				['/report/setting/admin/update', '2', '', time()],
				['/report/setting/admin/delete', '2', '', time()],
				['/report/setting/category/*', '2', '', time()],
			]);
		}

		$tableName = Yii::$app->db->tablePrefix . 'ommu_core_auth_item_child';
        if (Yii::$app->db->getTableSchema($tableName, true)) {
			$this->batchInsert('ommu_core_auth_item_child', ['parent', 'child'], [
				['userAdmin', 'reportModLevelAdmin'],
				['userModerator', 'reportModLevelModerator'],
				['reportModLevelAdmin', 'reportModLevelModerator'],
				['reportModLevelAdmin', '/report/setting/admin/update'],
				['reportModLevelAdmin', '/report/setting/admin/delete'],
				['reportModLevelAdmin', '/report/setting/category/*'],
				['reportModLevelModerator', '/report/setting/admin/index'],
				['reportModLevelModerator', '/report/admin/*'],
				['reportModLevelModerator', '/report/history/admin/*'],
				['reportModLevelModerator', '/report/history/comment/*'],
				['reportModLevelModerator', '/report/history/status/*'],
				['reportModLevelModerator', '/report/history/user/*'],
			]);
		}
	}
}
