<?php
/**
 * m210807_102335_report_module_create_table_status
 * 
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2021 OMMU (www.ommu.id)
 * @created date 7 August 2021, 10:23 WIB
 * @link https://github.com/ommu/mod-report
 *
 */

use Yii;
use yii\db\Schema;

class m210807_102335_report_module_create_table_status extends \yii\db\Migration
{
	public function up()
	{
		$tableOptions = null;
		if ($this->db->driverName === 'mysql') {
			$tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
		}
		$tableName = Yii::$app->db->tablePrefix . 'ommu_report_status';
		if (!Yii::$app->db->getTableSchema($tableName, true)) {
			$this->createTable($tableName, [
				'id' => Schema::TYPE_INTEGER . '(11) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT \'trigger[insert]\'',
				'status' => Schema::TYPE_TINYINT . '(1) NOT NULL COMMENT \'Resolved,Unresolved\'',
				'report_id' => Schema::TYPE_INTEGER . '(11) UNSIGNED NOT NULL',
				'user_id' => Schema::TYPE_INTEGER . '(11) UNSIGNED',
				'report_message' => Schema::TYPE_TEXT . ' NOT NULL COMMENT \'redactor\'',
				'updated_date' => Schema::TYPE_DATETIME . ' NOT NULL DEFAULT \'0000-00-00 00:00:00\' COMMENT \'trigger\'',
				'updated_ip' => Schema::TYPE_STRING . '(20) NOT NULL',
				'modified_date' => Schema::TYPE_TIMESTAMP . ' NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT \'trigger,on_update\'',
				'modified_id' => Schema::TYPE_INTEGER . '(11) UNSIGNED',
				'PRIMARY KEY ([[id]])',
				'CONSTRAINT ommu_report_status_ibfk_1 FFOREIGN KEY ([[report_id]]) REFERENCES ommu_reports ([[report_id]]) ON DELETE CASCADE ON UPDATE CASCADE',
				'CONSTRAINT ommu_report_status_ibfk_2 FFOREIGN KEY ([[user_id]]) REFERENCES ommu_users ([[user_id]]) ON DELETE CASCADE ON UPDATE CASCADE',
			], $tableOptions);
		}

		if (Yii::$app->db->getTableSchema($tableName, true)) {
            // create view _report_statistic_status
            $createViewStatisticStatus = <<< SQL
CREATE VIEW `_report_statistic_status` AS 
SELECT
  `a`.`report_id` AS `report_id`,
  SUM(CASE WHEN `a`.`status` = '1' THEN 1 ELSE 0 END) AS `resolved`,
  SUM(CASE WHEN `a`.`status` = '0' THEN 1 ELSE 0 END) AS `unresolved`,
  COUNT(`a`.`id`) AS `statuses`
FROM `ommu_report_status` `a`
GROUP BY `a`.`report_id`;
SQL;
            $this->execute($createViewStatisticStatus);
        }
	}

	public function down()
	{
		$tableName = Yii::$app->db->tablePrefix . 'ommu_report_status';
		$this->dropTable($tableName);
	}
}
