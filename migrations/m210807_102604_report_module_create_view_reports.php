<?php
/**
 * m210807_102604_report_module_create_view_reports
 * 
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2021 OMMU (www.ommu.id)
 * @created date 7 August 2021, 10:26 WIB
 * @link https://github.com/ommu/mod-report
 *
 */

use Yii;
use yii\db\Schema;

class m210807_102604_report_module_create_view_reports extends \yii\db\Migration
{
	public function up()
	{
		$tableName = Yii::$app->db->tablePrefix . 'ommu_reports';
		if (Yii::$app->db->getTableSchema($tableName, true)) {
            // create view _reports
            $createViewReports = <<< SQL
CREATE VIEW `_reports` AS 
SELECT
  `a`.`report_id`   AS `report_id`,
  COUNT(`b`.`id`)   AS `histories`,
  `c`.`resolved`    AS `resolved`,
  `c`.`unresolved`  AS `unresolved`,
  `c`.`statuses`    AS `statuses`,
  `d`.`comments`    AS `comments`,
  `d`.`comment_all` AS `comment_all`,
  `e`.`users`       AS `users`,
  `e`.`user_all`    AS `user_all`
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
            $this->execute($createViewReports);
		}
	}
}
