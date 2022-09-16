<?php
/**
 * m220828_030428_report_module_addTrigger_all
 * 
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2022 OMMU (www.ommu.id)
 * @created date 28 August 2022, 03:06 WIB
 * @link https://github.com/ommu/mod-report
 *
 */

use Yii;
use yii\db\Schema;

class m220828_030428_report_module_addTrigger_all extends \yii\db\Migration
{
	public function up()
	{
		$this->execute('DROP TRIGGER IF EXISTS `reportBeforeUpdateCategory`');
		$this->execute('DROP TRIGGER IF EXISTS `reportAfterDeleteCategory`');
		$this->execute('DROP TRIGGER IF EXISTS `reportAfterInsert`');
		$this->execute('DROP TRIGGER IF EXISTS `reportBeforeUpdate`');
		$this->execute('DROP TRIGGER IF EXISTS `reportAfterUpdate`');
		$this->execute('DROP TRIGGER IF EXISTS `reportAfterInsertComment`');
		$this->execute('DROP TRIGGER IF EXISTS `reportBeforeUpdateComment`');
		$this->execute('DROP TRIGGER IF EXISTS `reportAfterInsertStatus`');
		$this->execute('DROP TRIGGER IF EXISTS `reportAfterInsertHistory`');

		// alter sp reportBeforeUpdateCategory
		$reportBeforeUpdateCategory = <<< SQL
CREATE
    TRIGGER `reportBeforeUpdateCategory` BEFORE UPDATE ON `ommu_report_category` 
    FOR EACH ROW BEGIN
	IF (NEW.publish <> OLD.publish) THEN
		SET NEW.updated_date = NOW();
	END IF;	
    END;
SQL;
		$this->execute($reportBeforeUpdateCategory);

		// alter sp reportAfterDeleteCategory
		$reportAfterDeleteCategory = <<< SQL
CREATE
    TRIGGER `reportAfterDeleteCategory` AFTER DELETE ON `ommu_report_category` 
    FOR EACH ROW BEGIN
	/*
	DELETE FROM `source_message` WHERE `id`=OLD.name;
	DELETE FROM `source_message` WHERE `id`=OLD.desc;
	*/
	UPDATE `source_message` SET `message`=CONCAT(message,'_DELETED') WHERE `id`=OLD.name;
	UPDATE `source_message` SET `message`=CONCAT(message,'_DELETED') WHERE `id`=OLD.desc;
    END;
SQL;
		$this->execute($reportAfterDeleteCategory);

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

		// alter sp reportBeforeUpdate
		$reportBeforeUpdate = <<< SQL
CREATE
    TRIGGER `reportBeforeUpdate` BEFORE UPDATE ON `ommu_reports` 
    FOR EACH ROW BEGIN
	IF (NEW.reports <> OLD.reports AND NEW.reports > OLD.reports) THEN
		SET NEW.report_date = NOW();
	END IF;	
	
	IF (NEW.status <> OLD.status) THEN
		SET NEW.updated_date = NOW();
	END IF;	
    END;
SQL;
		$this->execute($reportBeforeUpdate);

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

		// alter sp reportAfterInsertComment
		$reportAfterInsertComment = <<< SQL
CREATE
    TRIGGER `reportAfterInsertComment` AFTER INSERT ON `ommu_report_comment` 
    FOR EACH ROW BEGIN
	CALL reportSetUser(NEW.report_id, NEW.user_id, NEW.creation_date);
    END;
SQL;
		$this->execute($reportAfterInsertComment);

		// alter sp reportBeforeUpdateComment
		$reportBeforeUpdateComment = <<< SQL
CREATE
    TRIGGER `reportBeforeUpdateComment` BEFORE UPDATE ON `ommu_report_comment` 
    FOR EACH ROW BEGIN
	IF (NEW.publish <> OLD.publish) THEN
		SET NEW.updated_date = NOW();
	END IF;	
    END;
SQL;
		$this->execute($reportBeforeUpdateComment);

		// alter sp reportAfterInsertStatus
		$reportAfterInsertStatus = <<< SQL
CREATE
    TRIGGER `reportAfterInsertStatus` AFTER INSERT ON `ommu_report_status` 
    FOR EACH ROW BEGIN
	CALL reportSetUser(NEW.report_id, NEW.user_id, NEW.updated_date);
    END;
SQL;
		$this->execute($reportAfterInsertStatus);

		// alter sp reportAfterInsertHistory
		$reportAfterInsertHistory = <<< SQL
CREATE
    TRIGGER `reportAfterInsertHistory` AFTER INSERT ON `ommu_report_history` 
    FOR EACH ROW BEGIN
	CALL reportSetUser(NEW.report_id, NEW.user_id, NEW.report_date);
    END;
SQL;
		$this->execute($reportAfterInsertHistory);
	}

	public function down()
	{
		$this->execute('DROP TRIGGER IF EXISTS `reportBeforeUpdateCategory`');
		$this->execute('DROP TRIGGER IF EXISTS `reportAfterDeleteCategory`');
		$this->execute('DROP TRIGGER IF EXISTS `reportAfterInsert`');
		$this->execute('DROP TRIGGER IF EXISTS `reportBeforeUpdate`');
		$this->execute('DROP TRIGGER IF EXISTS `reportAfterUpdate`');
		$this->execute('DROP TRIGGER IF EXISTS `reportAfterInsertComment`');
		$this->execute('DROP TRIGGER IF EXISTS `reportBeforeUpdateComment`');
		$this->execute('DROP TRIGGER IF EXISTS `reportAfterInsertStatus`');
		$this->execute('DROP TRIGGER IF EXISTS `reportAfterInsertHistory`');
	}
}
