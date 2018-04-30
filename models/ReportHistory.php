<?php
/**
 * ReportHistory
 * 
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2017 ECC UGM (ecc.ft.ugm.ac.id)
 * @created date 19 September 2017, 23:26 WIB
 * @modified date 18 April 2018, 22:15 WIB
 * @link https://ecc.ft.ugm.ac.id
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

namespace app\modules\report\models;

use Yii;
use yii\helpers\Url;
use yii\helpers\Html;
use app\coremodules\user\models\Users;

class ReportHistory extends \app\components\ActiveRecord
{
	public $gridForbiddenColumn = [];

	// Variable Search
	public $category_search;
	public $report_search;
	public $reporter_search;

	/**
	 * @return string the associated database table name
	 */
	public static function tableName()
	{
		return 'ommu_report_history';
	}

	/**
	 * @return \yii\db\Connection the database connection used by this AR class.
	 */
	public static function getDb()
	{
		return Yii::$app->get('ecc4');
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		return [
			[['report_id', 'report_ip'], 'required'],
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
			'category_search' => Yii::t('app', 'Category'),
			'report_search' => Yii::t('app', 'Report'),
			'reporter_search' => Yii::t('app', 'Reporter'),
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
				$this->templateColumns['category_search'] = [
					'attribute' => 'category_search',
					'filter' => ReportCategory::getCategory(),
					'value' => function($model, $key, $index, $column) {
						return isset($model->report) ? $model->report->category->title->message : '-';
					},
				];
			}
			$this->templateColumns['report_search'] = [
				'attribute' => 'report_search',
				'value' => function($model, $key, $index, $column) {
					return isset($model->report) ? $model->report->report_body : '-';
				},
			];
		}
		if(!Yii::$app->request->get('user')) {
			$this->templateColumns['reporter_search'] = [
				'attribute' => 'reporter_search',
				'value' => function($model, $key, $index, $column) {
					return isset($model->user) ? $model->user->displayname : '-';
				},
			];
		}
		$this->templateColumns['report_date'] = [
			'attribute' => 'report_date',
			'filter' => Html::input('date', 'report_date', Yii::$app->request->get('report_date'), ['class'=>'form-control']),
			'value' => function($model, $key, $index, $column) {
				return !in_array($model->report_date, ['0000-00-00 00:00:00','1970-01-01 00:00:00','-0001-11-30 00:00:00']) ? Yii::$app->formatter->format($model->report_date, 'datetime') : '-';
			},
			'format' => 'html',
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

	/**
	 * before validate attributes
	 */
	public function beforeValidate() 
	{
		if(parent::beforeValidate()) {
			if($this->isNewRecord)
				$this->user_id = !Yii::$app->user->isGuest ? Yii::$app->user->id : null;
			
			$this->report_ip = $_SERVER['REMOTE_ADDR'];
		}
		return true;
	}
}
