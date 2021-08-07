<?php
/**
 * m210807_114830_report_module_dropForeignKey_userId_status
 * 
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2021 OMMU (www.ommu.id)
 * @created date 7 August 2021, 11:48 WIB
 * @link https://www.ommu.id
 *
 */

use Yii;
use yii\db\Schema;

class m210807_114830_report_module_dropForeignKey_userId_status extends \yii\db\Migration
{
	public function up()
	{
		$tableName = Yii::$app->db->tablePrefix . 'ommu_report_status';
		if (Yii::$app->db->getTableSchema($tableName, true)) {
			$this->dropForeignKey(
				'ommu_report_status_ibfk_2',
				$tableName,
			);
		}
	}
}
