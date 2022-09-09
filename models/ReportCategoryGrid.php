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
 * @property ReportCategory $0
 *
 */

namespace ommu\report\models;

use Yii;

class ReportCategoryGrid extends \app\components\ActiveRecord
{
    public $gridForbiddenColumn = ['idName'];

	public $idName;

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
			'idName' => Yii::t('app', 'Id'),
		];
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function get0()
	{
		return $this->hasOne(ReportCategory::className(), ['cat_id' => 'id']);
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

		$this->templateColumns['_no'] = [
			'header' => '#',
			'class' => 'app\components\grid\SerialColumn',
			'contentOptions' => ['class' => 'text-center'],
		];
		$this->templateColumns['id'] = [
			'attribute' => 'id',
			'value' => function($model, $key, $index, $column) {
				return isset($model->idRltn) ? $model->idRltn->title->message : '-';
				// return $model->idName;
			},
			'filter' => ReportCategory::getCategory(),
			'visible' => !Yii::$app->request->get('id') ? true : false,
		];
		$this->templateColumns['unresolved'] = [
			'attribute' => 'unresolved',
			'value' => function($model, $key, $index, $column) {
				return $model->unresolved;
			},
		];
		$this->templateColumns['resolved'] = [
			'attribute' => 'resolved',
			'value' => function($model, $key, $index, $column) {
				return $model->resolved;
			},
		];
		$this->templateColumns['report'] = [
			'attribute' => 'report',
			'value' => function($model, $key, $index, $column) {
				return $model->report;
			},
		];
		$this->templateColumns['modified_date'] = [
			'attribute' => 'modified_date',
			'value' => function($model, $key, $index, $column) {
				return Yii::$app->formatter->asDatetime($model->modified_date, 'medium');
			},
			'filter' => $this->filterDatepicker($this, 'modified_date'),
		];
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

	/**
	 * function getGrid
	 */
	public static function getGrid($array=true) 
	{
		$model = self::find()->alias('t')
			->select(['t.id', 't.id']);
		$model = $model->orderBy('t.id ASC')->all();

        if ($array == true) {
            return \yii\helpers\ArrayHelper::map($model, 'id', 'id');
        }

		return $model;
	}

	/**
	 * after find attributes
	 */
	public function afterFind()
	{
		parent::afterFind();

		// $this->idName = isset($this->idRltn) ? $this->idRltn->title->message : '-';
	}
}
