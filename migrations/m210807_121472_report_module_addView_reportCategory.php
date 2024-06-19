<?php
/**
 * m210807_121472_report_module_addView_reportCategory
 * 
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)811-2540-432
 * @copyright Copyright (c) 2021 OMMU (www.ommu.id)
 * @created date 7 August 2021, 12:14 WIB
 * @link https://github.com/ommu/mod-report
 *
 */

use yii\db\Schema;

class m210807_121472_report_module_addView_reportCategory extends \yii\db\Migration
{
	public function up()
	{
		$this->execute('DROP VIEW IF EXISTS `_report_category`');

		// alter view _report_category
		$alterViewReportCategory = <<< SQL
CREATE VIEW `_report_category` AS 
select
  `a`.`cat_id` AS `cat_id`,
  sum(case when `b`.`status` = '0' then 1 else 0 end) AS `reports`,
  sum(case when `b`.`status` = '1' then 1 else 0 end) AS `report_resolved`,
  count(`b`.`cat_id`) AS `report_all`
from (`ommu_report_category` `a`
   left join `ommu_reports` `b`
	 on (`a`.`cat_id` = `b`.`cat_id`))
group by `a`.`cat_id`;
SQL;
		$this->execute($alterViewReportCategory);
	}

	public function down()
	{
		$this->execute('DROP VIEW IF EXISTS `_report_category`');
	}
}
