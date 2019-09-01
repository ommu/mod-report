<?php
/**
 * ReportCategory
 * 
 * @author Putra Sudaryanto <putra@ommu.co>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2018 OMMU (www.ommu.co)
 * @created date 26 April 2018, 08:55 WIB
 * @link https://github.com/ommu/mod-report
 *
 * This is the model class for table "_report_category".
 *
 * The followings are the available columns in table "_report_category":
 * @property integer $cat_id
 * @property string $reports
 * @property string $report_resolved
 * @property integer $report_all
 *
 */

namespace ommu\report\models\view;

use Yii;
use yii\helpers\Html;
use yii\helpers\Url;

class ReportCategory extends \app\components\ActiveRecord
{
	public $gridForbiddenColumn = [];

	/**
	 * @return string the associated database table name
	 */
	public static function tableName()
	{
		return '_report_category';
	}

	/**
	 * @return string the primarykey column
	 */
	public static function primaryKey()
	{
		return ['cat_id'];
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		return [
			[['cat_id', 'report_all'], 'integer'],
			[['reports', 'report_resolved'], 'number'],
		];
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return [
			'cat_id' => Yii::t('app', 'Category'),
			'reports' => Yii::t('app', 'Reports'),
			'report_resolved' => Yii::t('app', 'Report Resolved'),
			'report_all' => Yii::t('app', 'Report All'),
		];
	}

	/**
	 * Set default columns to display
	 */
	public function init()
	{
		parent::init();

		if(!(Yii::$app instanceof \app\components\Application))
			return;

		$this->templateColumns['_no'] = [
			'header' => '#',
			'class' => 'yii\grid\SerialColumn',
			'contentOptions' => ['class'=>'center'],
		];
		$this->templateColumns['cat_id'] = [
			'attribute' => 'cat_id',
			'value' => function($model, $key, $index, $column) {
				return $model->cat_id;
			},
		];
		$this->templateColumns['reports'] = [
			'attribute' => 'reports',
			'value' => function($model, $key, $index, $column) {
				return $model->reports;
			},
		];
		$this->templateColumns['report_resolved'] = [
			'attribute' => 'report_resolved',
			'value' => function($model, $key, $index, $column) {
				return $model->report_resolved;
			},
		];
		$this->templateColumns['report_all'] = [
			'attribute' => 'report_all',
			'value' => function($model, $key, $index, $column) {
				return $model->report_all;
			},
		];
	}

	/**
	 * User get information
	 */
	public static function getInfo($id, $column=null)
	{
		if($column != null) {
			$model = self::find();
			if(is_array($column))
				$model->select($column);
			else
				$model->select([$column]);
			$model = $model->where(['cat_id' => $id])->one();
			return is_array($column) ? $model : $model->$column;
			
		} else {
			$model = self::findOne($id);
			return $model;
		}
	}
}
