<?php
/**
 * m210807_102429_report_module_create_table_user
 * 
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2021 OMMU (www.ommu.id)
 * @created date 7 August 2021, 10:25 WIB
 * @link https://github.com/ommu/mod-report
 *
 */

use Yii;
use yii\db\Schema;

class m210807_102429_report_module_create_table_user extends \yii\db\Migration
{
	public function up()
	{
		$tableOptions = null;
		if ($this->db->driverName === 'mysql') {
			$tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
		}
		$tableName = Yii::$app->db->tablePrefix . 'ommu_report_user';
		if (!Yii::$app->db->getTableSchema($tableName, true)) {
			$this->createTable($tableName, [
				'id' => Schema::TYPE_INTEGER . '(11) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT \'trigger[insert]\'',
				'publish' => Schema::TYPE_TINYINT . '(1) NOT NULL DEFAULT \'1\'',
				'report_id' => Schema::TYPE_INTEGER . '(11) UNSIGNED NOT NULL',
				'user_id' => Schema::TYPE_INTEGER . '(11) UNSIGNED',
				'creation_date' => Schema::TYPE_TIMESTAMP . ' NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT \'trigger\'',
				'modified_date' => Schema::TYPE_TIMESTAMP . ' NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT \'trigger,on_update\'',
				'modified_id' => Schema::TYPE_INTEGER . '(11) UNSIGNED',
				'updated_date' => Schema::TYPE_DATETIME . ' NOT NULL DEFAULT \'0000-00-00 00:00:00\' COMMENT \'trigger\'',
				'PRIMARY KEY ([[id]])',
				'CONSTRAINT ommu_report_user_ibfk_1 FOREIGN KEY ([[report_id]]) REFERENCES ommu_reports ([[report_id]]) ON DELETE CASCADE ON UPDATE CASCADE',
				'CONSTRAINT ommu_report_user_ibfk_2 FOREIGN KEY ([[user_id]]) REFERENCES ommu_users ([[user_id]]) ON DELETE CASCADE ON UPDATE CASCADE',
			], $tableOptions);
		}

		if (Yii::$app->db->getTableSchema($tableName, true)) {
            // create sp reportSetUser
            $createProsedureReportSetUser = <<< SQL
CREATE PROCEDURE `reportSetUser`(IN `report_id_sp` INT, IN `user_id_sp` INT, IN `creation_date_sp` DATETIME)
BEGIN
    DECLARE id_sp INT;
    
    IF (user_id_sp IS NOT NULL) THEN
        SELECT `id` INTO id_sp FROM `ommu_report_user` WHERE `publish`='1' AND `report_id`=report_id_sp AND `user_id`=user_id_sp;
        IF (id_sp IS NULL) THEN
            INSERT `ommu_report_user` (`report_id`, `user_id`, `creation_date`)
            VALUE (report_id_sp, user_id_sp, creation_date_sp);
        END IF;
    END IF;
END;
SQL;
            $this->execute('DROP PROCEDURE IF EXISTS `reportSetUser`');
            $this->execute($createProsedureReportSetUser);

            // create view _report_statistic_user
            $createViewStatisticUser = <<< SQL
CREATE VIEW `_report_statistic_user` AS 
SELECT
  `a`.`report_id` AS `report_id`,
  SUM(CASE WHEN `a`.`publish` = '1' THEN 1 ELSE 0 END) AS `users`,
  COUNT(`a`.`user_id`) AS `user_all`
FROM `ommu_report_user` `a`
GROUP BY `a`.`report_id`;
SQL;
            $this->execute($createViewStatisticUser);
        }
	}

	public function down()
	{
		$tableName = Yii::$app->db->tablePrefix . 'ommu_report_user';
		$this->dropTable($tableName);
	}
}
