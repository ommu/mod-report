<?php
/**
 * m210807_100628_report_module_create_table_category
 * 
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2021 OMMU (www.ommu.id)
 * @created date 7 August 2021, 10:06 WIB
 * @link https://www.ommu.id
 *
 */

use Yii;
use yii\db\Schema;

class m210807_100628_report_module_create_table_category extends \yii\db\Migration
{
	public function up()
	{
		$tableOptions = null;
		if ($this->db->driverName === 'mysql') {
			$tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
		}
		$tableName = Yii::$app->db->tablePrefix . 'ommu_report_category';
		if (!Yii::$app->db->getTableSchema($tableName, true)) {
			$this->createTable($tableName, [
				'cat_id' => Schema::TYPE_SMALLINT . '(5) UNSIGNED NOT NULL AUTO_INCREMENT',
				'publish' => Schema::TYPE_TINYINT . '(1) NOT NULL DEFAULT \'1\' COMMENT \'Enable,Disable\'',
				'name' => Schema::TYPE_INTEGER . '(11) UNSIGNED NOT NULL COMMENT \'trigger[delete]\'',
				'desc' => Schema::TYPE_INTEGER . '(11) UNSIGNED NOT NULL COMMENT \'trigger[delete],text\'',
				'creation_date' => Schema::TYPE_TIMESTAMP . ' NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT \'trigger\'',
				'creation_id' => Schema::TYPE_INTEGER . '(11) UNSIGNED',
				'modified_date' => Schema::TYPE_TIMESTAMP . ' NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT \'trigger,on_update\'',
				'modified_id' => Schema::TYPE_INTEGER . '(11) UNSIGNED',
				'updated_date' => Schema::TYPE_DATETIME . ' NOT NULL DEFAULT \'0000-00-00 00:00:00\' COMMENT \'trigger\'',
				'slug' => Schema::TYPE_TEXT . ' NOT NULL',
				'PRIMARY KEY ([[cat_id]])',
			], $tableOptions);

			$this->createIndex(
				'publishWithName',
				$tableName,
				['publish', 'name']
			);

			$this->createIndex(
				'name',
				$tableName,
				'name'
			);
		}

		if (Yii::$app->db->getTableSchema($tableName, true)) {
            // create view _report_category
            $createViewCategory = <<< SQL
CREATE VIEW `_report_category` AS 
SELECT
  `a`.`cat_id` AS `cat_id`,
  SUM(CASE WHEN `b`.`status` = '0' THEN 1 ELSE 0 END) AS `reports`,
  SUM(CASE WHEN `b`.`status` = '1' THEN 1 ELSE 0 END) AS `report_resolved`,
  COUNT(`b`.`cat_id`) AS `report_all`
FROM (`ommu_report_category` `a`
   LEFT JOIN `ommu_reports` `b`
     ON (`a`.`cat_id` = `b`.`cat_id`))
GROUP BY `a`.`cat_id`;
SQL;
            $this->execute($createViewCategory);
        }
	}

	public function down()
	{
		$tableName = Yii::$app->db->tablePrefix . 'ommu_report_category';
		$this->dropTable($tableName);
	}
}
