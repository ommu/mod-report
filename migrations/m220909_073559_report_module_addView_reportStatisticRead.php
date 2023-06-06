<?php
/**
 * m220909_073559_report_module_addView_reportStatisticRead
 * 
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2022 OMMU (www.ommu.id)
 * @created date 9 September 2022, 07:38 WIB
 * @link https://github.com/ommu/mod-report
 *
 */

use yii\db\Schema;

class m220909_073559_report_module_addView_reportStatisticRead extends \yii\db\Migration
{
	public function up()
	{
		$this->execute('DROP VIEW IF EXISTS `_report_statistic_read`');

		// alter view _report_statistic_read
		$alterViewReportStatisticRead = <<< SQL
CREATE VIEW `_report_statistic_read` AS 
SELECT
`a`.`report_id` AS `report_id`,
COUNT(`a`.`id`) AS `reads`
FROM `ommu_report_read` `a`
GROUP BY `a`.`report_id`;
SQL;
		$this->execute($alterViewReportStatisticRead);
	}

	public function down()
	{
		$this->execute('DROP VIEW IF EXISTS `_report_statistic_read`');
	}
}
