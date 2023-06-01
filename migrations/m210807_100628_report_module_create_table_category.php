<?php
/**
 * m210807_100628_report_module_create_table_category
 * 
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2021 OMMU (www.ommu.id)
 * @created date 7 August 2021, 10:06 WIB
 * @link https://github.com/ommu/mod-report
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

			$this->batchInsert($tableName, ['publish', 'name', 'desc'], [
				['1', SourceMessage::setMessage('Spam', 'report category title'), SourceMessage::setMessage('Spam', 'report category description')],
				['1', SourceMessage::setMessage('Inappropriate Content', 'report category title'), SourceMessage::setMessage('Inappropriate Content', 'report category description')],
				['1', SourceMessage::setMessage('Abuse', 'report category title'), SourceMessage::setMessage('Abuse', 'report category description')],
			]);
		}
	}

	public function down()
	{
		$tableName = Yii::$app->db->tablePrefix . 'ommu_report_category';
		$this->dropTable($tableName);
	}
}
