<?php
/**
 * ReportCategoryGrid
 * 
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2022 OMMU (www.ommu.id)
 * @created date 9 September 2022, 15:46 WIB
 * @link https://github.com/ommu/mod-report
 *
 * This is the model class for table "ommu_report_category_grid".
 *
 * The followings are the available columns in table "ommu_report_category_grid":
 * @property integer $id
 * @property integer $unresolved
 * @property integer $resolved
 * @property integer $report
 * @property string $modified_date
 *
 * The followings are the available model relations:
 *
 */

namespace ommu\report\models;

use Yii;

class ReportCategoryGrid extends \app\components\ActiveRecord
{
    public $gridForbiddenColumn = [];

	/**
	 * @return string the associated database table name
	 */
	public static function tableName()
	{
		return 'ommu_report_category_grid';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		return [
			[['id', 'report'], 'required'],
			[['id', 'unresolved', 'resolved', 'report'], 'integer'],
			[['id'], 'unique'],
			[['id'], 'exist', 'skipOnError' => true, 'targetClass' => ReportCategory::className(), 'targetAttribute' => ['id' => 'cat_id']],
		];
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return [
			'id' => Yii::t('app', 'ID'),
			'unresolved' => Yii::t('app', 'Unresolved'),
			'resolved' => Yii::t('app', 'Resolved'),
			'report' => Yii::t('app', 'Report'),
			'modified_date' => Yii::t('app', 'Modified Date'),
		];
	}

	/**
	 * Set default columns to display
	 */
	public function init()
	{
        parent::init();

        if (!(Yii::$app instanceof \app\components\Application)) {
            return;
        }

        if (!$this->hasMethod('search')) {
            return;
        }
	}

	/**
	 * User get information
	 */
	public static function getInfo($id, $column=null)
	{
        if ($column != null) {
            $model = self::find();
            if (is_array($column)) {
                $model->select($column);
            } else {
                $model->select([$column]);
            }
            $model = $model->where(['id' => $id])->one();
            return is_array($column) ? $model : $model->$column;

        } else {
            $model = self::findOne($id);
            return $model;
        }
	}
}
