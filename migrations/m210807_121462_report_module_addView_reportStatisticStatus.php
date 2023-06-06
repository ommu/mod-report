<?php
/**
 * m210807_121462_report_module_addView_reportStatisticStatus
 * 
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2021 OMMU (www.ommu.id)
 * @created date 7 August 2021, 12:14 WIB
 * @link https://github.com/ommu/mod-report
 *
 */

use yii\db\Schema;

class m210807_121462_report_module_addView_reportStatisticStatus extends \yii\db\Migration
{
	public function up()
	{
		$this->execute('DROP VIEW IF EXISTS `_report_statistic_status`');

		// alter view _report_statistic_status
		$alterViewReportStatisticStatus = <<< SQL
CREATE VIEW `_report_statistic_status` AS 
select
  `a`.`report_id` AS `report_id`,
  sum(case when `a`.`status` = '1' then 1 else 0 end) AS `resolved`,
  sum(case when `a`.`status` = '0' then 1 else 0 end) AS `unresolved`,
  count(`a`.`id`) AS `statuses`
from `ommu_report_status` `a`
group by `a`.`report_id`;
SQL;
		$this->execute($alterViewReportStatisticStatus);
	}

	public function down()
	{
		$this->execute('DROP VIEW IF EXISTS `_report_statistic_status`');
	}
}
