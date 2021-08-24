<?php
/**
 * m210807_100944_report_module_create_table_setting
 * 
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2021 OMMU (www.ommu.id)
 * @created date 7 August 2021, 10:11 WIB
 * @link https://github.com/ommu/mod-report
 *
 */

use Yii;
use yii\db\Schema;

class m210807_100944_report_module_create_table_setting extends \yii\db\Migration
{
	public function up()
	{
		$tableOptions = null;
		if ($this->db->driverName === 'mysql') {
			$tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
		}
		$tableName = Yii::$app->db->tablePrefix . 'ommu_report_setting';
		if (!Yii::$app->db->getTableSchema($tableName, true)) {
			$this->createTable($tableName, [
				'id' => Schema::TYPE_TINYINT . '(1) UNSIGNED NOT NULL AUTO_INCREMENT',
				'license' => Schema::TYPE_STRING . '(32) NOT NULL',
				'permission' => Schema::TYPE_TINYINT . '(1) NOT NULL DEFAULT \'1\'',
				'meta_description' => Schema::TYPE_TEXT . ' NOT NULL',
				'meta_keyword' => Schema::TYPE_TEXT . ' NOT NULL',
				'auto_report_cat_id' => Schema::TYPE_SMALLINT . '(5) UNSIGNED',
				'modified_date' => Schema::TYPE_TIMESTAMP . ' NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT \'trigger,on_update\'',
				'modified_id' => Schema::TYPE_INTEGER . '(11) UNSIGNED',
				'PRIMARY KEY ([[id]])',
				'CONSTRAINT ommu_report_setting_ibfk_1 FOREIGN KEY ([[auto_report_cat_id]]) REFERENCES ommu_report_category ([[cat_id]]) ON DELETE CASCADE ON UPDATE CASCADE',
			], $tableOptions);
		}
	}

	public function down()
	{
		$tableName = Yii::$app->db->tablePrefix . 'ommu_report_setting';
		$this->dropTable($tableName);
	}
}
