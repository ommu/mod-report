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
use yii\base\InvalidConfigException;
use yii\rbac\DbManager;

class m190318_120101_report_module_insert_role extends \yii\db\Migration
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

		$tableName = Yii::$app->db->tablePrefix . $authManager->itemChildTable;
        if (Yii::$app->db->getTableSchema($tableName, true)) {
			$this->batchInsert($tableName, ['parent', 'child'], [
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
