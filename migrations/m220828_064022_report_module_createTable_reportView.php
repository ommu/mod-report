<?php
/**
 * m220828_064022_report_module_createTable_reportView
 * 
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)811-2540-432
 * @copyright Copyright (c) 2022 OMMU (www.ommu.id)
 * @created date 28 August 2022, 06:44 WIB
 * @link https://github.com/ommu/mod-report
 *
 */

use yii\db\Schema;

class m220828_064022_report_module_createTable_reportView extends \yii\db\Migration
{
	public function up()
	{
		$tableOptions = null;
		if ($this->db->driverName === 'mysql') {
			$tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
		}
		$tableName = Yii::$app->db->tablePrefix . '{{%ommu_report_view}}';
		if (!Yii::$app->db->getTableSchema($tableName, true)) {
			$this->createTable($tableName, [
				'id' => Schema::TYPE_CHAR . '(36) NOT NULL COMMENT \'uuid,trigger[insert]\'',
				'report_id' => Schema::TYPE_INTEGER . '(11) UNSIGNED NOT NULL',
				'user_id' => Schema::TYPE_INTEGER . '(11) UNSIGNED',
				'creation_date' => Schema::TYPE_TIMESTAMP . ' NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT \'trigger\'',
				'PRIMARY KEY ([[id]])',
				'CONSTRAINT ommu_report_view_ibfk_1 FOREIGN KEY ([[report_id]]) REFERENCES {{%ommu_reports}} ([[report_id]]) ON DELETE CASCADE ON UPDATE CASCADE',
			], $tableOptions);

			$this->createIndex(
				'user_id',
				$tableName,
				['user_id']
			);
		}
	}

	public function down()
	{
		$tableName = Yii::$app->db->tablePrefix . '{{%ommu_report_view}}';
		$this->dropTable($tableName);
	}
}
