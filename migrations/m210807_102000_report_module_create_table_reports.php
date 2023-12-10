<?php
/**
 * m210807_102000_report_module_create_table_reports
 * 
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2021 OMMU (www.ommu.id)
 * @created date 7 August 2021, 10:20 WIB
 * @link https://github.com/ommu/mod-report
 *
 */

use yii\db\Schema;

class m210807_102000_report_module_create_table_reports extends \yii\db\Migration
{
	public function up()
	{
		$tableOptions = null;
		if ($this->db->driverName === 'mysql') {
			$tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
		}
		$tableName = Yii::$app->db->tablePrefix . 'ommu_reports';
		if (!Yii::$app->db->getTableSchema($tableName, true)) {
			$this->createTable($tableName, [
				'report_id' => Schema::TYPE_INTEGER . '(11) UNSIGNED NOT NULL AUTO_INCREMENT',
				'app' => Schema::TYPE_STRING . '(32) NOT NULL',
				'status' => Schema::TYPE_TINYINT . '(1) NOT NULL DEFAULT \'0\' COMMENT \'Resolved,Unresolved\'',
				'cat_id' => Schema::TYPE_SMALLINT . '(5) UNSIGNED',
				'user_id' => Schema::TYPE_INTEGER . '(11) UNSIGNED',
				'report_url' => Schema::TYPE_TEXT . ' NOT NULL',
				'report_body' => Schema::TYPE_TEXT . ' NOT NULL COMMENT \'redactor\'',
				'report_message' => Schema::TYPE_TEXT . ' NOT NULL COMMENT \'redactor\'',
				'reports' => Schema::TYPE_INTEGER . '(11) NOT NULL DEFAULT \'1\'',
				'report_date' => Schema::TYPE_TIMESTAMP . ' NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT \'trigger\'',
				'report_ip' => Schema::TYPE_STRING . '(20) NOT NULL',
				'modified_date' => Schema::TYPE_TIMESTAMP . ' NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT \'trigger,on_update\'',
				'modified_id' => Schema::TYPE_INTEGER . '(11) UNSIGNED',
				'updated_date' => Schema::TYPE_DATETIME . ' NOT NULL DEFAULT \'0000-00-00 00:00:00\' COMMENT \'trigger\'',
				'PRIMARY KEY ([[report_id]])',
				'CONSTRAINT ommu_reports_ibfk_1 FOREIGN KEY ([[cat_id]]) REFERENCES ommu_report_category ([[cat_id]]) ON DELETE CASCADE ON UPDATE CASCADE',
				'CONSTRAINT ommu_reports_ibfk_2 FOREIGN KEY ([[user_id]]) REFERENCES ommu_users ([[user_id]]) ON DELETE CASCADE ON UPDATE CASCADE',
			], $tableOptions);
		}
	}

	public function down()
	{
		$tableName = Yii::$app->db->tablePrefix . 'ommu_reports';
		$this->dropTable($tableName);
	}
}
