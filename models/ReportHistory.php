<?php
/**
 * ReportHistory
 * 
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2017 OMMU (www.ommu.co)
 * @created date 19 September 2017, 23:26 WIB
 * @modified date 18 January 2019, 14:56 WIB
 * @link https://github.com/ommu/mod-report
 *
 * This is the model class for table "ommu_report_history".
 *
 * The followings are the available columns in table "ommu_report_history":
 * @property integer $id
 * @property integer $report_id
 * @property integer $user_id
 * @property string $report_date
 * @property string $report_ip
 *
 * The followings are the available model relations:
 * @property Reports $report
 * @property Users $user
 *
 */

namespace ommu\report\models;

use Yii;
use yii\helpers\Html;
use ommu\users\models\Users;

class ReportHistory extends \app\components\ActiveRecord
{
	public $gridForbiddenColumn = [];

	// Search Variable
	public $categoryId;
	public $reportBody;
	public $reporterDisplayname;

	/**
	 * @return string the associated database table name
	 */
	public static function tableName()
	{
		return 'ommu_report_history';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		return [
			[['report_id', 'user_id', 'report_ip'], 'required'],
			[['report_id', 'user_id'], 'integer'],
			[['report_date'], 'safe'],
			[['report_ip'], 'string', 'max' => 20],
			[['report_id'], 'exist', 'skipOnError' => true, 'targetClass' => Reports::className(), 'targetAttribute' => ['report_id' => 'report_id']],
			[['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => Users::className(), 'targetAttribute' => ['user_id' => 'user_id']],
		];
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return [
			'id' => Yii::t('app', 'ID'),
			'report_id' => Yii::t('app', 'Report'),
			'user_id' => Yii::t('app', 'User'),
			'report_date' => Yii::t('app', 'Report Date'),
			'report_ip' => Yii::t('app', 'Report Ip'),
			'categoryId' => Yii::t('app', 'Category'),
			'reportBody' => Yii::t('app', 'Report'),
			'reporterDisplayname' => Yii::t('app', 'Reporter'),
		];
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getReport()
	{
		return $this->hasOne(Reports::className(), ['report_id' => 'report_id']);
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getUser()
	{
		return $this->hasOne(Users::className(), ['user_id' => 'user_id']);
	}

	/**
	 * {@inheritdoc}
	 * @return \ommu\report\models\query\ReportHistory the active query used by this AR class.
	 */
	public static function find()
	{
		return new \ommu\report\models\query\ReportHistory(get_called_class());
	}

	/**
	 * Set default columns to display
	 */
	public function init()
	{
		parent::init();

		$this->templateColumns['_no'] = [
			'header' => Yii::t('app', 'No'),
			'class'  => 'yii\grid\SerialColumn',
			'contentOptions' => ['class'=>'center'],
		];
		if(!Yii::$app->request->get('report')) {
			if(!Yii::$app->request->get('category')) {
				$this->templateColumns['categoryId'] = [
					'attribute' => 'categoryId',
					'filter' => ReportCategory::getCategory(),
					'value' => function($model, $key, $index, $column) {
						return isset($model->report) ? $model->report->category->title->message : '-';
					},
				];
			}
			$this->templateColumns['reportBody'] = [
				'attribute' => 'reportBody',
				'value' => function($model, $key, $index, $column) {
					return isset($model->report) ? $model->report->report_body : '-';
				},
			];
		}
		if(!Yii::$app->request->get('user')) {
			$this->templateColumns['reporterDisplayname'] = [
				'attribute' => 'reporterDisplayname',
				'value' => function($model, $key, $index, $column) {
					return isset($model->user) ? $model->user->displayname : '-';
				},
			];
		}
		$this->templateColumns['report_date'] = [
			'attribute' => 'report_date',
			'value' => function($model, $key, $index, $column) {
				return Yii::$app->formatter->asDatetime($model->report_date, 'medium');
			},
			'filter' => $this->filterDatepicker($this, 'report_date'),
		];
		$this->templateColumns['report_ip'] = [
			'attribute' => 'report_ip',
			'value' => function($model, $key, $index, $column) {
				return $model->report_ip;
			},
		];
	}

	/**
	 * User get information
	 */
	public static function getInfo($id, $column=null)
	{
		if($column != null) {
			$model = self::find()
				->select([$column])
				->where(['id' => $id])
				->one();
			return $model->$column;
			
		} else {
			$model = self::findOne($id);
			return $model;
		}
	}
}
