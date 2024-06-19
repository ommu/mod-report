<?php
/**
 * m210807_121463_report_module_addView_reportStatisticUser
 * 
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)811-2540-432
 * @copyright Copyright (c) 2021 OMMU (www.ommu.id)
 * @created date 7 August 2021, 12:14 WIB
 * @link https://github.com/ommu/mod-report
 *
 */

use yii\db\Schema;

class m210807_121463_report_module_addView_reportStatisticUser extends \yii\db\Migration
{
	public function up()
	{
		$this->execute('DROP VIEW IF EXISTS `_report_statistic_user`');

		// alter view _report_statistic_user
		$alterViewReportStatisticUser = <<< SQL
CREATE VIEW `_report_statistic_user` AS 
SELECT
  `a`.`report_id` AS `report_id`,
  COUNT(`a`.`user_id`) AS `users`
FROM `ommu_report_user` `a`
GROUP BY `a`.`report_id`;
SQL;
		$this->execute($alterViewReportStatisticUser);
	}

	public function down()
	{
		$this->execute('DROP VIEW IF EXISTS `_report_statistic_user`');

        // create view _report_statistic_user
        $alterViewReportStatisticUser = <<< SQL
CREATE VIEW `_report_statistic_user` AS 
SELECT
  `a`.`report_id` AS `report_id`,
  SUM(CASE WHEN `a`.`publish` = '1' THEN 1 ELSE 0 END) AS `users`,
  COUNT(`a`.`user_id`) AS `user_all`
FROM `ommu_report_user` `a`
GROUP BY `a`.`report_id`;
SQL;
		$this->execute($alterViewReportStatisticUser);
	}
}
