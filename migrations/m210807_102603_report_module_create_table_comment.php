<?php
/**
 * m210807_102603_report_module_create_table_comment
 * 
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2021 OMMU (www.ommu.id)
 * @created date 7 August 2021, 10:26 WIB
 * @link https://github.com/ommu/mod-report
 *
 */

use yii\db\Schema;

class m210807_102603_report_module_create_table_comment extends \yii\db\Migration
{
	public function up()
	{
		$tableOptions = null;
		if ($this->db->driverName === 'mysql') {
			$tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
		}
		$tableName = Yii::$app->db->tablePrefix . 'ommu_report_comment';
		if (!Yii::$app->db->getTableSchema($tableName, true)) {
			$this->createTable($tableName, [
				'comment_id' => Schema::TYPE_INTEGER . '(11) UNSIGNED NOT NULL AUTO_INCREMENT',
				'publish' => Schema::TYPE_TINYINT . '(1) NOT NULL DEFAULT \'1\'',
				'report_id' => Schema::TYPE_INTEGER . '(11) UNSIGNED NOT NULL',
				'user_id' => Schema::TYPE_INTEGER . '(11) UNSIGNED',
				'comment_text' => Schema::TYPE_TEXT . ' NOT NULL',
				'creation_date' => Schema::TYPE_TIMESTAMP . ' NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT \'trigger\'',
				'modified_date' => Schema::TYPE_TIMESTAMP . ' NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT \'trigger,on_update\'',
				'modified_id' => Schema::TYPE_INTEGER . '(11) UNSIGNED',
				'updated_date' => Schema::TYPE_DATETIME . ' NOT NULL DEFAULT \'0000-00-00 00:00:00\' COMMENT \'trigger\'',
				'PRIMARY KEY ([[comment_id]])',
				'CONSTRAINT ommu_report_comment_ibfk_1 FOREIGN KEY ([[report_id]]) REFERENCES ommu_reports ([[report_id]]) ON DELETE CASCADE ON UPDATE CASCADE',
				'CONSTRAINT ommu_report_comment_ibfk_2 FOREIGN KEY ([[user_id]]) REFERENCES ommu_users ([[user_id]]) ON DELETE CASCADE ON UPDATE CASCADE',
			], $tableOptions);
		}
	}

	public function down()
	{
		$tableName = Yii::$app->db->tablePrefix . 'ommu_report_comment';
		$this->dropTable($tableName);
	}
}
