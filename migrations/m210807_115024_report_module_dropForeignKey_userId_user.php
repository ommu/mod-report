<?php
/**
 * m210807_115024_report_module_dropForeignKey_userId_user
 * 
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2021 OMMU (www.ommu.id)
 * @created date 7 August 2021, 11:50 WIB
 * @link https://github.com/ommu/mod-report
 *
 */

use yii\db\Schema;

class m210807_115024_report_module_dropForeignKey_userId_user extends \yii\db\Migration
{
	public function up()
	{
		$tableName = Yii::$app->db->tablePrefix . 'ommu_report_user';
		if (Yii::$app->db->getTableSchema($tableName, true)) {
			$this->dropForeignKey(
				'ommu_report_user_ibfk_2',
				$tableName,
			);
		}
	}
}
