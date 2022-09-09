<?php
/**
 * m220904_103049_report_module_createTable_ReportGrid
 * 
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2022 OMMU (www.ommu.id)
 * @created date 4 September 2022, 18:46 WIB
 * @link https://github.com/ommu/mod-report
 *
 */

use Yii;
use yii\db\Schema;

class m220904_103049_report_module_createTable_ReportGrid extends \yii\db\Migration
{
	public function up()
	{
		$tableOptions = null;
		if ($this->db->driverName === 'mysql') {
			$tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
		}
		$tableName = Yii::$app->db->tablePrefix . 'ommu_report_grid';
		if (!Yii::$app->db->getTableSchema($tableName, true)) {
			$this->createTable($tableName, [
				'id' => Schema::TYPE_INTEGER . '(11) UNSIGNED NOT NULL',
				'comment' => Schema::TYPE_INTEGER . '(11) NOT NULL',
				'history' => Schema::TYPE_INTEGER . '(11) NOT NULL',
				'read' => Schema::TYPE_INTEGER . '(11) NOT NULL',
				'status' => Schema::TYPE_INTEGER . '(11) NOT NULL',
				'user' => Schema::TYPE_INTEGER . '(11) NOT NULL',
				'modified_date' => Schema::TYPE_TIMESTAMP . ' NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT \'trigger,on_update\'',
				'PRIMARY KEY ([[id]])',
				'CONSTRAINT ommu_report_grid_ibfk_1 FOREIGN KEY ([[id]]) REFERENCES ommu_reports ([[report_id]]) ON DELETE CASCADE ON UPDATE CASCADE',
			], $tableOptions);
		}
	}

	public function down()
	{
		$tableName = Yii::$app->db->tablePrefix . 'ommu_report_grid';
		$this->dropTable($tableName);
	}
}


