<?php
/**
 * m220909_081025_report_module_alterTrigger_all_insertReportGrid
 * 
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2022 OMMU (www.ommu.id)
 * @created date 9 September 2022, 08:21 WIB
 * @link https://github.com/ommu/mod-report
 *
 */

use yii\db\Schema;

class m220909_081025_report_module_alterTrigger_all_insertReportGrid extends \yii\db\Migration
{
	public function up()
	{
		$this->execute('DROP TRIGGER IF EXISTS `reportAfterInsert`');

		// alter sp reportAfterInsert
		$reportAfterInsert = <<< SQL
CREATE
    TRIGGER `reportAfterInsert` AFTER INSERT ON `ommu_reports` 
    FOR EACH ROW BEGIN
	/* Report History */
	INSERT `ommu_report_history` (`report_id`, `user_id`, `report_date`, `report_ip`)
	VALUE (NEW.report_id, NEW.user_id, NEW.report_date, NEW.report_ip);
	
	/* Report Status */
	INSERT `ommu_report_status` (`status`, `report_id`, `user_id`, `report_message`, `updated_date`, `updated_ip`)
	VALUE (NEW.status, NEW.report_id, NEW.user_id, NEW.report_body, NEW.report_date, NEW.report_ip);

	INSERT `ommu_report_grid` (`id`, `comment`, `read`, `status`, `user`) 
	VALUE (NEW.report_id, 0, 0, 0, 0);
    END;
SQL;
		$this->execute($reportAfterInsert);
	}

	public function down()
	{
		$this->execute('DROP TRIGGER IF EXISTS `reportAfterInsert`');

		// alter sp reportAfterInsert
		$reportAfterInsert = <<< SQL
CREATE
    TRIGGER `reportAfterInsert` AFTER INSERT ON `ommu_reports` 
    FOR EACH ROW BEGIN
	/* Report History */
	INSERT `ommu_report_history` (`report_id`, `user_id`, `report_date`, `report_ip`)
	VALUE (NEW.report_id, NEW.user_id, NEW.report_date, NEW.report_ip);
	
	/* Report Status */
	INSERT `ommu_report_status` (`status`, `report_id`, `user_id`, `report_message`, `updated_date`, `updated_ip`)
	VALUE (NEW.status, NEW.report_id, NEW.user_id, NEW.report_body, NEW.report_date, NEW.report_ip);
    END;
SQL;
		$this->execute($reportAfterInsert);
	}
}
