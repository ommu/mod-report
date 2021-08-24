<?php
/**
 * m210807_114224_report_module_dropForeignKey_userId_reports
 * 
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2021 OMMU (www.ommu.id)
 * @created date 7 August 2021, 11:42 WIB
 * @link https://github.com/ommu/mod-report
 *
 */

use Yii;
use yii\db\Schema;

class m210807_114224_report_module_dropForeignKey_userId_reports extends \yii\db\Migration
{
	public function up()
	{
		$tableName = Yii::$app->db->tablePrefix . 'ommu_reports';
		if (Yii::$app->db->getTableSchema($tableName, true)) {
			$this->dropForeignKey(
				'ommu_reports_ibfk_2',
				$tableName,
			);
		}
	}
}
