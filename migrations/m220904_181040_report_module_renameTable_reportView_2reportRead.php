<?php
/**
 * m220904_181040_report_module_renameTable_reportView_2reportRead
 * 
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2022 OMMU (www.ommu.id)
 * @created date 4 September 2022, 18:18 WIB
 * @link https://github.com/ommu/mod-report
 *
 */

use yii\db\Schema;

class m220904_181040_report_module_renameTable_reportView_2reportRead extends \yii\db\Migration
{
	public function up()
	{
		$tableName = Yii::$app->db->tablePrefix . '{{%ommu_report_view}}';
		if (Yii::$app->db->getTableSchema($tableName, true)) {
			$this->renameTable($tableName, 'ommu_report_read');
		}
	}

	public function down()
	{
		$tableName = Yii::$app->db->tablePrefix . '{{%ommu_report_read}}';
		if (Yii::$app->db->getTableSchema($tableName, true)) {
			$this->renameTable($tableName, 'ommu_report_view');
		}
	}
}
