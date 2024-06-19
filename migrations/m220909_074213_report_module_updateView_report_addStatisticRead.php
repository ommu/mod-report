<?php
/**
 * m220909_074213_report_module_updateView_report_addStatisticRead
 * 
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)811-2540-432
 * @copyright Copyright (c) 2022 OMMU (www.ommu.id)
 * @created date 9 September 2022, 07:43 WIB
 * @link https://github.com/ommu/mod-report
 *
 */

use yii\db\Schema;

class m220909_074213_report_module_updateView_report_addStatisticRead extends \yii\db\Migration
{
	public function up()
	{
		$this->execute('DROP VIEW IF EXISTS `_reports`');

		// alter view _reports
		$alterViewReports = <<< SQL
CREATE VIEW `_reports` AS 
SELECT
  `a`.`report_id`   AS `report_id`,
  COUNT(`b`.`id`)   AS `histories`,
  `c`.`resolved`	AS `resolved`,
  `c`.`unresolved`  AS `unresolved`,
  `c`.`statuses`	AS `statuses`,
  `d`.`comments`	AS `comments`,
  `d`.`comment_all` AS `comment_all`,
  `e`.`users`	   AS `users`,
  `f`.`reads`	   AS `reads`
FROM (((((`ommu_reports` `a`
	   LEFT JOIN `ommu_report_history` `b`
		 ON (`a`.`report_id` = `b`.`report_id`))
	  LEFT JOIN `_report_statistic_status` `c`
		ON (`a`.`report_id` = `c`.`report_id`))
	 LEFT JOIN `_report_statistic_comment` `d`
	   ON (`a`.`report_id` = `d`.`report_id`))
	LEFT JOIN `_report_statistic_user` `e`
	  ON (`a`.`report_id` = `e`.`report_id`))
   LEFT JOIN `_report_statistic_read` `f`
	 ON (`a`.`report_id` = `f`.`report_id`))
GROUP BY `a`.`report_id`;
SQL;
		$this->execute($alterViewReports);
	}

	public function down()
	{
		$this->execute('DROP VIEW IF EXISTS `_reports`');

		// alter view _reports
		$alterViewReports = <<< SQL
CREATE VIEW `_reports` AS 
select
  `a`.`report_id`   AS `report_id`,
  count(`b`.`id`)   AS `histories`,
  `c`.`resolved`	AS `resolved`,
  `c`.`unresolved`  AS `unresolved`,
  `c`.`statuses`	AS `statuses`,
  `d`.`comments`	AS `comments`,
  `d`.`comment_all` AS `comment_all`,
  `e`.`users`	   AS `users`
from ((((`ommu_reports` `a`
	  left join `ommu_report_history` `b`
		on (`a`.`report_id` = `b`.`report_id`))
	 left join `_report_statistic_status` `c`
	   on (`a`.`report_id` = `c`.`report_id`))
	left join `_report_statistic_comment` `d`
	  on (`a`.`report_id` = `d`.`report_id`))
   left join `_report_statistic_user` `e`
	 on (`a`.`report_id` = `e`.`report_id`))
group by `a`.`report_id`;
SQL;
		$this->execute($alterViewReports);
	}
}
