<?php
/**
 * m220909_080739_report_module_insertRow_reportGrid
 * 
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2022 OMMU (www.ommu.id)
 * @created date 9 September 2022, 08:08 WIB
 * @link https://github.com/ommu/mod-report
 *
 */

use Yii;
use yii\db\Schema;

class m220909_080739_report_module_insertRow_reportGrid extends \yii\db\Migration
{
	public function up()
	{
        $inserRowReportGrid = <<< SQL
INSERT INTO `ommu_report_grid` (`id`, `comment`, `read`, `status`, `user`) 

SELECT 
    a.report_id AS id,
    case when a.comments is null then 0 else a.comments end AS `comments`,
    case when a.reads is null then 0 else a.reads end AS `reads`,
    case when a.statuses is null then 0 else a.statuses end AS `statuses`,
    case when a.users is null then 0 else a.users end AS `users`
FROM _reports AS a
LEFT JOIN ommu_report_grid AS b
    ON b.id = a.report_id
WHERE
	b.id IS NULL;
SQL;
        $this->execute($inserRowReportGrid);
	}
}
