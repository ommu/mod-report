<?php
/**
 * m210807_121481_report_module_addSP_reportSetUser
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

class m210807_121481_report_module_addSP_reportSetUser extends \yii\db\Migration
{
	public function up()
	{
		$this->execute('DROP PROCEDURE `reportSetUser`');

		// alter sp reportSetUser
		$alterProsedureReportSetUser = <<< SQL
CREATE PROCEDURE `reportSetUser`(IN `report_id_sp` INT, IN `user_id_sp` INT, IN `creation_date_sp` DATETIME)
BEGIN
	DECLARE id_sp INT;
	
	IF (user_id_sp IS NOT NULL) THEN
		SELECT `id` INTO id_sp FROM `ommu_report_user` WHERE `report_id`=report_id_sp AND `user_id`=user_id_sp;
		IF (id_sp IS NULL) THEN
			INSERT `ommu_report_user` (`report_id`, `user_id`, `creation_date`)
			VALUE (report_id_sp, user_id_sp, creation_date_sp);
		END IF;
	END IF;
END;
SQL;
		$this->execute($alterProsedureReportSetUser);
	}

	public function down()
	{
		$this->execute('DROP PROCEDURE `reportSetUser`');

        // create sp reportSetUser
        $alterProsedureReportSetUser = <<< SQL
CREATE PROCEDURE `reportSetUser`(IN `report_id_sp` INT, IN `user_id_sp` INT, IN `creation_date_sp` DATETIME)
BEGIN
	DECLARE id_sp INT;
	
	IF (user_id_sp IS NOT NULL) THEN
		SELECT `id` INTO id_sp FROM `ommu_report_user` WHERE `publish`='1' AND `report_id`=report_id_sp AND `user_id`=user_id_sp;
		IF (id_sp IS NULL) THEN
			INSERT `ommu_report_user` (`report_id`, `user_id`, `creation_date`)
			VALUE (report_id_sp, user_id_sp, creation_date_sp);
		END IF;
	END IF;
END;
SQL;
        $this->execute($alterProsedureReportSetUser);
	}
}
