<?php
/**
 * m220909_100909_report_module_createTable_ReportCategoryGrid
 * 
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)811-2540-432
 * @copyright Copyright (c) 2022 OMMU (www.ommu.id)
 * @created date 9 September 2022, 10:10 WIB
 * @link https://github.com/ommu/mod-report
 *
 */

use yii\db\Schema;

class m220909_100909_report_module_createTable_ReportCategoryGrid extends \yii\db\Migration
{
	public function up()
	{
		$tableOptions = null;
		if ($this->db->driverName === 'mysql') {
			$tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
		}
		$tableName = Yii::$app->db->tablePrefix . 'ommu_report_category_grid';
		if (!Yii::$app->db->getTableSchema($tableName, true)) {
			$this->createTable($tableName, [
				'id' => Schema::TYPE_SMALLINT . '(5) UNSIGNED NOT NULL',
				'report' => Schema::TYPE_INTEGER . '(11) NOT NULL',
				'modified_date' => Schema::TYPE_TIMESTAMP . ' NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT \'trigger,on_update\'',
				'PRIMARY KEY ([[id]])',
				'CONSTRAINT ommu_report_category_grid_ibfk_1 FOREIGN KEY ([[id]]) REFERENCES ommu_report_category ([[cat_id]]) ON DELETE CASCADE ON UPDATE CASCADE',
			], $tableOptions);
		}
	}

	public function down()
	{
		$tableName = Yii::$app->db->tablePrefix . 'ommu_report_category_grid';
		$this->dropTable($tableName);
	}
}


