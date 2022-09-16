<?php
/**
 * m220909_101128_report_module_addColumn_unresolved_resolved_reportCategoryGrid
 * 
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2022 OMMU (www.ommu.id)
 * @created date 9 September 2022, 10:13 WIB
 * @link https://github.com/ommu/mod-report
 *
 */

use Yii;
use yii\db\Schema;

class m220909_101128_report_module_addColumn_unresolved_resolved_reportCategoryGrid extends \yii\db\Migration
{
	public $tableName = 'ommu_report_category_grid';

	public function up()
	{
		$tableName = Yii::$app->db->tablePrefix . $this->tableName;
		if (Yii::$app->db->getTableSchema($tableName, true)) {
			$this->addColumn(
				$tableName,
				'resolved',
				$this->integer()->notNull()->defaultValue(0)->after('id'),
			);
			$this->addColumn(
				$tableName,
				'unresolved',
				$this->integer()->notNull()->defaultValue(0)->after('id'),
			);
		}
	}

	public function down()
	{
		$tableName = Yii::$app->db->tablePrefix . $this->tableName;
		if (Yii::$app->db->getTableSchema($tableName, true)) {
			$this->dropColumn($tableName, 'unresolved');
			$this->dropColumn($tableName, 'resolved');
		}
	}
}
