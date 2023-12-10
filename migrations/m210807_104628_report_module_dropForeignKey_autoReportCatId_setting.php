<?php
/**
 * m210807_104628_report_module_dropForeignKey_autoReportCatId_setting
 * 
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2021 OMMU (www.ommu.id)
 * @created date 7 August 2021, 10:46 WIB
 * @link https://github.com/ommu/mod-report
 *
 */

use yii\db\Schema;

class m210807_104628_report_module_dropForeignKey_autoReportCatId_setting extends \yii\db\Migration
{
	public function up()
	{
		$tableName = Yii::$app->db->tablePrefix . 'ommu_report_setting';
		if (Yii::$app->db->getTableSchema($tableName, true)) {
			$this->dropForeignKey(
				'ommu_report_setting_ibfk_1',
				$tableName,
			);

			$this->dropIndex(
				'auto_report_cat_id',
				$tableName,
			);
		}
	}
}
