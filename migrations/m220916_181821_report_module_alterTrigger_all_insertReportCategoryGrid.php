<?php
/**
 * m220916_181821_report_module_alterTrigger_all_insertReportCategoryGrid
 * 
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)811-2540-432
 * @copyright Copyright (c) 2022 OMMU (www.ommu.id)
 * @created date 16 September 2022, 18:23 WIB
 * @link https://github.com/ommu/mod-report
 *
 */

use yii\db\Schema;

class m220916_181821_report_module_alterTrigger_all_insertReportCategoryGrid extends \yii\db\Migration
{
	public function up()
	{
		$this->execute('DROP TRIGGER IF EXISTS `reportAfterInsertCategory`');

		// alter sp reportAfterInsertCategory
		$reportAfterInsertCategory = <<< SQL
CREATE
    TRIGGER `reportAfterInsertCategory` AFTER INSERT ON `ommu_report_category` 
    FOR EACH ROW BEGIN
	INSERT `ommu_report_category_grid` (`id`, `unresolved`, `resolved`, `report`) 
	VALUE (NEW.cat_id, 0, 0, 0);
    END;
SQL;
		$this->execute($reportAfterInsertCategory);
	}

	public function down()
	{
		$this->execute('DROP TRIGGER IF EXISTS `reportAfterInsertCategory`');
	}
}
