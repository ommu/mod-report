<?php
/**
 * m220831_184611_report_module_addColumn_read_reports
 * 
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2022 OMMU (www.ommu.id)
 * @created date 31 August 2022, 18:48 WIB
 * @link https://github.com/ommu/mod-report
 *
 */

use yii\db\Schema;

class m220831_184611_report_module_addColumn_read_reports extends \yii\db\Migration
{
	public $tableName = 'ommu_reports';

	public function up()
	{
		$tableName = Yii::$app->db->tablePrefix . $this->tableName;
		if (Yii::$app->db->getTableSchema($tableName, true)) {
			$this->addColumn(
				$tableName,
				'read',
				$this->boolean()->notNull()->defaultValue(0)->after('status'),
			);
		}
	}

	public function down()
	{
		$tableName = Yii::$app->db->tablePrefix . $this->tableName;
		if (Yii::$app->db->getTableSchema($tableName, true)) {
			$this->dropColumn($tableName, 'read');
		}
	}
}
