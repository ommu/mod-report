<?php
/**
 * m220828_093901_report_module_insertRole_historyView
 * 
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)811-2540-432
 * @copyright Copyright (c) 2022 OMMU (www.ommu.id)
 * @created date 28 August 2022, 09:40 WIB
 * @link https://github.com/ommu/mod-report
 *
 */

use yii\base\InvalidConfigException;
use yii\rbac\DbManager;

class m220828_093901_report_module_insertRole_historyView extends \yii\db\Migration
{
	/**
	 * @throws yii\base\InvalidConfigException
	 * @return DbManager
	 */
	protected function getAuthManager()
	{
		$authManager = Yii::$app->getAuthManager();
		if (!$authManager instanceof DbManager) {
			throw new InvalidConfigException('You should configure "authManager" component to use database before executing this migration.');
		}

		return $authManager;
	}

	public function up()
	{
		$authManager = $this->getAuthManager();
		$this->db = $authManager->db;
		$schema = $this->db->getSchema()->defaultSchema;

		$tableName = Yii::$app->db->tablePrefix . $authManager->itemTable;
		if (Yii::$app->db->getTableSchema($tableName, true)) {
			$this->batchInsert($tableName, ['name', 'type', 'data', 'created_at'], [
				['/report/history/view/*', '2', '', time()],
			]);
		}

		$tableName = Yii::$app->db->tablePrefix . $authManager->itemChildTable;
		if (Yii::$app->db->getTableSchema($tableName, true)) {
			$this->batchInsert($tableName, ['parent', 'child'], [
				['reportModLevelModerator', '/report/history/view/*'],
			]);
		}
	}
}
