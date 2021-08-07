<?php
/**
 * m210807_121431_report_module_dropColumn_publish_modifiedDate_modifiedId_updatedDate_user
 * 
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2021 OMMU (www.ommu.id)
 * @created date 7 August 2021, 12:14 WIB
 * @link https://www.ommu.id
 *
 */

use Yii;
use yii\db\Schema;

class m210807_121431_report_module_dropColumn_publish_modifiedDate_modifiedId_updatedDate_user extends \yii\db\Migration
{
	public function up()
	{
		$tableName = Yii::$app->db->tablePrefix . 'ommu_report_user';
		if (Yii::$app->db->getTableSchema($tableName, true)) {
			$this->dropColumn(
				$tableName,
				'publish',
			);

			$this->dropColumn(
				$tableName,
				'modified_date',
			);

			$this->dropColumn(
				$tableName,
				'modified_id',
			);

			$this->dropColumn(
				$tableName,
				'updated_date',
			);
		}
	}
}
