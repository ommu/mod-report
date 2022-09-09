<?php
/**
 * m220909_082027_report_module_alterTrigger_all_updateReportGrid
 * 
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2022 OMMU (www.ommu.id)
 * @created date 9 September 2022, 08:21 WIB
 * @link https://github.com/ommu/mod-report
 *
 */

use Yii;
use yii\db\Schema;

class m220909_082027_report_module_alterTrigger_all_updateReportGrid extends \yii\db\Migration
{
	public function up()
	{
        $this->execute('DROP TRIGGER `reportAfterInsertComment`');
        $this->execute('DROP TRIGGER `reportAfterInsertStatus`');
        $this->execute('DROP TRIGGER `reportAfterInsertRead`');
        $this->execute('DROP TRIGGER `reportAfterInsertUser`');

        // alter sp reportAfterInsertComment
        $reportAfterInsertComment = <<< SQL
CREATE
    TRIGGER `reportAfterInsertComment` AFTER INSERT ON `ommu_report_comment` 
    FOR EACH ROW BEGIN
	CALL reportSetUser(NEW.report_id, NEW.user_id, NEW.creation_date);

	UPDATE `ommu_report_grid` SET `comment` = `comment` + 1 WHERE `id` = NEW.report_id;
    END;
SQL;
        $this->execute($reportAfterInsertComment);

        // alter sp reportAfterInsertStatus
        $reportAfterInsertStatus = <<< SQL
CREATE
    TRIGGER `reportAfterInsertStatus` AFTER INSERT ON `ommu_report_status` 
    FOR EACH ROW BEGIN
	CALL reportSetUser(NEW.report_id, NEW.user_id, NEW.updated_date);

	UPDATE `ommu_report_grid` SET `status` = `status` + 1 WHERE `id` = NEW.report_id;
    END;
SQL;
        $this->execute($reportAfterInsertStatus);

        // alter sp reportAfterInsertRead
        $reportAfterInsertRead = <<< SQL
CREATE
    TRIGGER `reportAfterInsertRead` AFTER INSERT ON `ommu_report_read` 
    FOR EACH ROW BEGIN
	CALL reportSetUser(NEW.report_id, NEW.user_id, NEW.creation_date);

	UPDATE `ommu_report_grid` SET `read` = `read` + 1 WHERE `id` = NEW.report_id;
    END;
SQL;
        $this->execute($reportAfterInsertRead);

        // alter sp reportAfterInsertUser
        $reportAfterInsertUser = <<< SQL
CREATE
    TRIGGER `reportAfterInsertUser` AFTER INSERT ON `ommu_report_user` 
    FOR EACH ROW BEGIN
	UPDATE `ommu_report_grid` SET `user` = `user` + 1 WHERE `id` = NEW.report_id;
    END;
SQL;
        $this->execute($reportAfterInsertUser);
    }

	public function down()
	{
        $this->execute('DROP TRIGGER `reportAfterInsertComment`');
        $this->execute('DROP TRIGGER `reportAfterInsertStatus`');
        $this->execute('DROP TRIGGER `reportAfterInsertRead`');
        $this->execute('DROP TRIGGER `reportAfterInsertUser`');

        // alter sp reportAfterInsertComment
        $reportAfterInsertComment = <<< SQL
CREATE
    TRIGGER `reportAfterInsertComment` AFTER INSERT ON `ommu_report_comment` 
    FOR EACH ROW BEGIN
	CALL reportSetUser(NEW.report_id, NEW.user_id, NEW.creation_date);
    END;
SQL;
        $this->execute($reportAfterInsertComment);

        // alter sp reportAfterInsertStatus
        $reportAfterInsertStatus = <<< SQL
CREATE
    TRIGGER `reportAfterInsertStatus` AFTER INSERT ON `ommu_report_status` 
    FOR EACH ROW BEGIN
	CALL reportSetUser(NEW.report_id, NEW.user_id, NEW.updated_date);
    END;
SQL;
        $this->execute($reportAfterInsertStatus);
	}
}
