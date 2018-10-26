<?php
/**
 * ReportStatus
 * 
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2017 Ommu Platform (www.ommu.co)
 * @created date 19 September 2017, 23:27 WIB
 * @modified date 18 April 2018, 22:16 WIB
 * @link https://ecc.ft.ugm.ac.id
 *
 * This is the model class for table "ommu_report_status".
 *
 * The followings are the available columns in table "ommu_report_status":
 * @property integer $history_id
 * @property integer $status
 * @property integer $report_id
 * @property integer $user_id
 * @property string $report_message
 * @property string $updated_date
 * @property string $updated_ip
 * @property string $modified_date
 * @property integer $modified_id
 *
 * The followings are the available model relations:
 * @property Reports $report
 * @property Users $user
 * @property Users $modified
 *
 */

namespace ommu\report\models;

use Yii;
use yii\helpers\Url;
use yii\helpers\Html;
use ommu\users\models\Users;

class ReportStatus extends \app\components\ActiveRecord
{
	use \ommu\traits\UtilityTrait;

	public $gridForbiddenColumn = ['modified_date','modified_search','updated_date'];

	// Variable Search
	public $category_search;
	public $report_search;
	public $reporter_search;
	public $modified_search;

	/**
	 * @return string the associated database table name
	 */
	public static function tableName()
	{
		return 'ommu_report_status';
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
			[['status', 'report_id', 'report_message', 'updated_ip'], 'required'],
			[['status', 'report_id', 'user_id', 'modified_id'], 'integer'],
			[['report_message'], 'string'],
			[['updated_date', 'modified_date'], 'safe'],
			[['updated_ip'], 'string', 'max' => 20],
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
			'history_id' => Yii::t('app', 'History'),
			'status' => Yii::t('app', 'Status'),
			'report_id' => Yii::t('app', 'Report'),
			'user_id' => Yii::t('app', 'User'),
			'report_message' => Yii::t('app', 'Report Message'),
			'updated_date' => Yii::t('app', 'Updated Date'),
			'updated_ip' => Yii::t('app', 'Updated Ip'),
			'modified_date' => Yii::t('app', 'Modified Date'),
			'modified_id' => Yii::t('app', 'Modified'),
			'category_search' => Yii::t('app', 'Category'),
			'report_search' => Yii::t('app', 'Report'),
			'reporter_search' => Yii::t('app', 'Reporter'),
			'modified_search' => Yii::t('app', 'Modified'),
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
	 * @return \yii\db\ActiveQuery
	 */
	public function getModified()
	{
		return $this->hasOne(Users::className(), ['user_id' => 'modified_id']);
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
		if(!Yii::$app->request->get('category') && !Yii::$app->request->get('report')) {
			$this->templateColumns['category_search'] = [
				'attribute' => 'category_search',
				'filter' => ReportCategory::getCategory(),
				'value' => function($model, $key, $index, $column) {
					return isset($model->report) ? $model->report->category->title->message : '-';
				},
			];
		}
		if(!Yii::$app->request->get('report')) {
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
		$this->templateColumns['report_message'] = [
			'attribute' => 'report_message',
			'value' => function($model, $key, $index, $column) {
				return $model->report_message;
			},
			'format' => 'html',
		];
		$this->templateColumns['updated_date'] = [
			'attribute' => 'updated_date',
			'filter' => Html::input('date', 'updated_date', Yii::$app->request->get('updated_date'), ['class'=>'form-control']),
			'value' => function($model, $key, $index, $column) {
				return !in_array($model->updated_date, ['0000-00-00 00:00:00','1970-01-01 00:00:00','0002-12-02 07:07:12','-0001-11-30 00:00:00']) ? Yii::$app->formatter->format($model->updated_date, 'datetime') : '-';
			},
			'format' => 'html',
		];
		$this->templateColumns['updated_ip'] = [
			'attribute' => 'updated_ip',
			'value' => function($model, $key, $index, $column) {
				return $model->updated_ip;
			},
		];
		$this->templateColumns['modified_date'] = [
			'attribute' => 'modified_date',
			'filter' => Html::input('date', 'modified_date', Yii::$app->request->get('modified_date'), ['class'=>'form-control']),
			'value' => function($model, $key, $index, $column) {
				return !in_array($model->modified_date, ['0000-00-00 00:00:00','1970-01-01 00:00:00','0002-12-02 07:07:12','-0001-11-30 00:00:00']) ? Yii::$app->formatter->format($model->modified_date, 'datetime') : '-';
			},
			'format' => 'html',
		];
		if(!Yii::$app->request->get('modified')) {
			$this->templateColumns['modified_search'] = [
				'attribute' => 'modified_search',
				'value' => function($model, $key, $index, $column) {
					return isset($model->modified) ? $model->modified->displayname : '-';
				},
			];
		}
		$this->templateColumns['status'] = [
			'attribute' => 'status',
			'filter' => $this->filterYesNo(),
			'value' => function($model, $key, $index, $column) {
				return $model->status == 1 ? Yii::t('app', 'Resolved') : Yii::t('app', 'Unresolved');
			},
			'contentOptions' => ['class'=>'center'],
			'format' => 'raw',
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
				->where(['history_id' => $id])
				->one();
			return $model->$column;
			
		} else {
			$model = self::findOne($id);
			return $model;
		}
	}

	/**
	 * function getStatus
	 */
	public static function getStatus($array=true) 
	{
		$model = self::find()->alias('t');
		$model = $model->orderBy('t.history_id ASC')->all();

		if($array == true) {
			$items = [];
			if($model !== null) {
				foreach($model as $val) {
					$items[$val->history_id] = $val->history_id;
				}
				return $items;
			} else
				return false;
		} else 
			return $model;
	}

	/**
	 * before validate attributes
	 */
	public function beforeValidate() 
	{
		if(parent::beforeValidate()) {
			if($this->isNewRecord)
				$this->user_id = !Yii::$app->user->isGuest ? Yii::$app->user->id : null;
			else
				$this->modified_id = !Yii::$app->user->isGuest ? Yii::$app->user->id : null;
			
			$this->updated_ip = $_SERVER['REMOTE_ADDR'];
		}
		return true;
	}
}
