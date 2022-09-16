<?php
/**
 * m210807_121461_report_module_addView_reportStatisticComment
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

class m210807_121461_report_module_addView_reportStatisticComment extends \yii\db\Migration
{
	public function up()
	{
		$this->execute('DROP VIEW `_report_statistic_comment`');

		// alter view _report_statistic_comment
		$alterViewReportStatisticComment = <<< SQL
CREATE VIEW `_report_statistic_comment` AS 
select
  `a`.`report_id` AS `report_id`,
  sum(case when `a`.`publish` = '1' then 1 else 0 end) AS `comments`,
  count(`a`.`comment_id`) AS `comment_all`
from `ommu_report_comment` `a`
group by `a`.`report_id`;
SQL;
		$this->execute($alterViewReportStatisticComment);
	}

	public function down()
	{
		$this->execute('DROP VIEW `_report_statistic_comment`');
	}
}
