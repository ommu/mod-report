<?php
/**
 * m210807_121431_report_module_dropColumn_publish_modifiedDate_modifiedId_updatedDate_user
 * 
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2021 OMMU (www.ommu.id)
 * @created date 7 August 2021, 12:14 WIB
 * @link https://github.com/ommu/mod-report
 *
 */

use Yii;
use yii\db\Schema;

class m210807_121431_report_module_dropColumn_publish_modifiedDate_modifiedId_updatedDate_user extends \yii\db\Migration
{
	public function up()
	{
        $this->execute('DROP TRIGGER IF EXISTS `reportBeforeUpdateUser`');

        // alter table ommu_report_user
		$tableName = Yii::$app->db->tablePrefix . 'ommu_report_user';
		if (Yii::$app->db->getTableSchema($tableName, true)) {
            // alter view _report_statistic_user
            $alterViewReportStatisticUser = <<< SQL
ALTER ALGORITHM=UNDEFINED SQL SECURITY DEFINER VIEW `_report_statistic_user` AS 
SELECT
`a`.`report_id` AS `report_id`,
COUNT(`a`.`user_id`) AS `users`
FROM `ommu_report_user` `a`
GROUP BY `a`.`report_id`;
SQL;
            $this->execute($alterViewReportStatisticUser);

            // alter view _reports
            $alterViewReports = <<< SQL
ALTER ALGORITHM=UNDEFINED SQL SECURITY DEFINER VIEW `_reports` AS 
SELECT
  `a`.`report_id`   AS `report_id`,
  COUNT(`b`.`id`)   AS `histories`,
  `c`.`resolved`    AS `resolved`,
  `c`.`unresolved`  AS `unresolved`,
  `c`.`statuses`    AS `statuses`,
  `d`.`comments`    AS `comments`,
  `d`.`comment_all` AS `comment_all`,
  `e`.`users`       AS `users`
FROM ((((`ommu_reports` `a`
      LEFT JOIN `ommu_report_history` `b`
        ON (`a`.`report_id` = `b`.`report_id`))
     LEFT JOIN `_report_statistic_status` `c`
       ON (`a`.`report_id` = `c`.`report_id`))
    LEFT JOIN `_report_statistic_comment` `d`
      ON (`a`.`report_id` = `d`.`report_id`))
   LEFT JOIN `_report_statistic_user` `e`
     ON (`a`.`report_id` = `e`.`report_id`))
GROUP BY `a`.`report_id`;
SQL;
            $this->execute($alterViewReports);

            // drop column publish, modified_date, modified_id, updated_date
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

            // alter sp reportSetUser
            $alterProsedureReportSetUser = <<< SQL
CREATE PROCEDURE `reportSetUser`(IN `report_id_sp` INT, IN `user_id_sp` INT, IN `creation_date_sp` DATETIME)
BEGIN
    DECLARE id_sp INT;
    
    IF (user_id_sp IS NOT NULL) THEN
        SELECT `id` INTO id_sp FROM `ommu_report_user` WHERE `report_id`=report_id_sp AND `user_id`=user_id_sp;
        IF (id_sp IS NULL) THEN
            INSERT `ommu_report_user` (`report_id`, `user_id`, `creation_date`)
            VALUE (report_id_sp, user_id_sp, creation_date_sp);
        END IF;
    END IF;
END;
SQL;
            $this->execute('DROP PROCEDURE IF EXISTS `reportSetUser`');
            $this->execute($alterProsedureReportSetUser);
		}
	}
}
