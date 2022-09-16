<?php
/**
 * m220909_180719_report_module_alterTrigger_all_updateReportCategoryGrid
 * 
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2022 OMMU (www.ommu.id)
 * @created date 9 September 2022, 18:10 WIB
 * @link https://github.com/ommu/mod-report
 *
 */

use Yii;
use yii\db\Schema;

class m220909_180719_report_module_alterTrigger_all_updateReportCategoryGrid extends \yii\db\Migration
{
	public function up()
	{
		$this->execute('DROP TRIGGER IF EXISTS `reportAfterInsert`');
		$this->execute('DROP TRIGGER IF EXISTS `reportAfterUpdate`');

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

	IF (NEW.cat_id IS NOT NULL) THEN
		UPDATE `ommu_report_category_grid` SET `report` = `report` + 1 WHERE `id` = NEW.cat_id;
	END IF;
    END;
SQL;
		$this->execute($reportAfterInsert);

		// alter sp reportAfterUpdate
		$reportAfterUpdate = <<< SQL
CREATE
    TRIGGER `reportAfterUpdate` AFTER UPDATE ON `ommu_reports` 
    FOR EACH ROW BEGIN
	DECLARE user_id_tr INT;
	
	/* Report History */
	IF (NEW.report_date <> OLD.report_date) THEN
		INSERT `ommu_report_history` (`report_id`, `user_id`, `report_date`, `report_ip`)
		VALUE (NEW.report_id, NEW.user_id, NEW.report_date, NEW.report_ip);
	END IF;
	
	/* Report Status */
	IF (NEW.updated_date <> OLD.updated_date) THEN
		SET user_id_tr = NEW.modified_id;
		IF (NEW.modified_id = 0) THEN
			SET user_id_tr = NULL;
		END IF;
		
		INSERT `ommu_report_status` (`status`, `report_id`, `user_id`, `report_message`, `updated_date`, `updated_ip`)
		VALUE (NEW.status, NEW.report_id, user_id_tr, NEW.report_message, NEW.updated_date, NEW.report_ip);
	END IF;

	IF ((NEW.cat_id IS NOT NULL AND NEW.cat_id = OLD.cat_id) AND NEW.status <> OLD.status) THEN
		UPDATE `ommu_report_category_grid` AS a
		LEFT JOIN _report_category AS b
		ON a.`id` = b.`cat_id`
		SET a.`unresolved` = b.`reports`, a.`resolved` = b.`report_resolved`
		WHERE a.`id` = NEW.`cat_id`;
	END IF;
    END;
SQL;
		$this->execute($reportAfterUpdate);
	}

	public function down()
	{
		$this->execute('DROP TRIGGER IF EXISTS `reportAfterInsert`');
		$this->execute('DROP TRIGGER IF EXISTS `reportAfterUpdate`');

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

		// alter sp reportAfterUpdate
		$reportAfterUpdate = <<< SQL
CREATE
    TRIGGER `reportAfterUpdate` AFTER UPDATE ON `ommu_reports` 
    FOR EACH ROW BEGIN
	DECLARE user_id_tr INT;
	
	/* Report History */
	IF (NEW.report_date <> OLD.report_date) THEN
		INSERT `ommu_report_history` (`report_id`, `user_id`, `report_date`, `report_ip`)
		VALUE (NEW.report_id, NEW.user_id, NEW.report_date, NEW.report_ip);
	END IF;	
	
	/* Report Status */
	IF (NEW.updated_date <> OLD.updated_date) THEN
		SET user_id_tr = NEW.modified_id;
		IF (NEW.modified_id = 0) THEN
			SET user_id_tr = NULL;
		END IF;
		
		INSERT `ommu_report_status` (`status`, `report_id`, `user_id`, `report_message`, `updated_date`, `updated_ip`)
		VALUE (NEW.status, NEW.report_id, user_id_tr, NEW.report_message, NEW.updated_date, NEW.report_ip);
	END IF;	
    END;
SQL;
		$this->execute($reportAfterUpdate);
	}
}
