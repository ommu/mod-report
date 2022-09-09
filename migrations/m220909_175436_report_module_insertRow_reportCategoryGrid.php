<?php
/**
 * m220909_175436_report_module_insertRow_reportCategoryGrid
 * 
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2022 OMMU (www.ommu.id)
 * @created date 9 September 2022, 17:59 WIB
 * @link https://github.com/ommu/mod-report
 *
 */

use Yii;
use yii\db\Schema;

class m220909_175436_report_module_insertRow_reportCategoryGrid extends \yii\db\Migration
{
	public function up()
	{
        $inserRowReportCategoryGrid = <<< SQL
INSERT INTO `ommu_report_category_grid` (`id`, `unresolved`, `resolved`, `report`) 

SELECT 
    a.cat_id AS id,
    case when a.reports is null then 0 else a.reports end AS `unresolved`,
    case when a.report_resolved is null then 0 else a.report_resolved end AS `resolved`,
    case when a.report_all is null then 0 else a.report_all end AS `report`
FROM _report_category AS a
LEFT JOIN ommu_report_category_grid AS b
    ON b.id = a.cat_id
WHERE
	b.id IS NULL;
SQL;
        $this->execute($inserRowReportCategoryGrid);
	}
}
