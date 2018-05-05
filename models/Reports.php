<?php
/**
 * Reports
 * 
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2017 ECC UGM (ecc.ft.ugm.ac.id)
 * @created date 19 September 2017, 22:55 WIB
 * @modified date 18 April 2018, 22:17 WIB
 * @link https://ecc.ft.ugm.ac.id
 *
 * This is the model class for table "ommu_reports".
 *
 * The followings are the available columns in table "ommu_reports":
 * @property integer $report_id
 * @property integer $status
 * @property integer $cat_id
 * @property integer $user_id
 * @property string $report_url
 * @property string $report_body
 * @property string $report_message
 * @property integer $reports
 * @property string $report_date
 * @property string $report_ip
 * @property string $modified_date
 * @property integer $modified_id
 * @property string $updated_date
 *
 * The followings are the available model relations:
 * @property ReportComment[] $comments
 * @property ReportHistory[] $histories
 * @property ReportStatus[] $statuses
 * @property ReportUser[] $users
 * @property ReportCategory $category
 * @property Users $user
 * @property Users $modified
 *
 */

namespace app\modules\report\models;

use Yii;
use yii\helpers\Url;
use yii\helpers\Html;
use app\modules\user\models\Users;
use app\modules\report\models\view\Reports as ReportsView;

class Reports extends \app\components\ActiveRecord
{
	use \ommu\traits\GridViewTrait;

	public $gridForbiddenColumn = ['report_url','report_message','report_ip','modified_date','modified_search','updated_date'];

	// Variable Search
	public $category_search;
	public $reporter_search;
	public $modified_search;
	public $user_search;

	const SCENARIOREPORT = 'reportForm';
	const SCENARIORESOLVED = 'resolveForm';

	/**
	 * @return string the associated database table name
	 */
	public static function tableName()
	{
		return 'ommu_reports';
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
			[['cat_id', 'report_url', 'report_body', 'report_message'], 'required'],
			[['status', 'cat_id', 'user_id', 'reports', 'modified_id'], 'integer'],
			[['report_url', 'report_body', 'report_message'], 'string'],
			[['report_date', 'report_ip', 'modified_date', 'updated_date'], 'safe'],
			[['report_ip'], 'string', 'max' => 20],
			[['cat_id'], 'exist', 'skipOnError' => true, 'targetClass' => ReportCategory::className(), 'targetAttribute' => ['cat_id' => 'cat_id']],
			[['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => Users::className(), 'targetAttribute' => ['user_id' => 'user_id']],
		];
	}

	// get scenarios
	public function scenarios()
	{
		return [
			self::SCENARIOREPORT => ['cat_id', 'report_url', 'report_body'],
			self::SCENARIORESOLVED => ['report_message'],
		];
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return [
			'report_id' => Yii::t('app', 'Report'),
			'status' => Yii::t('app', 'Status'),
			'cat_id' => Yii::t('app', 'Category'),
			'user_id' => Yii::t('app', 'User'),
			'report_url' => Yii::t('app', 'Report Url'),
			'report_body' => Yii::t('app', 'Report Body'),
			'report_message' => Yii::t('app', 'Report Message'),
			'reports' => Yii::t('app', 'Reports'),
			'report_date' => Yii::t('app', 'Report Date'),
			'report_ip' => Yii::t('app', 'Report Ip'),
			'modified_date' => Yii::t('app', 'Modified Date'),
			'modified_id' => Yii::t('app', 'Modified'),
			'updated_date' => Yii::t('app', 'Updated Date'),
			'category_search' => Yii::t('app', 'Category'),
			'reporter_search' => Yii::t('app', 'Reporter'),
			'modified_search' => Yii::t('app', 'Modified'),
			'user_search' => Yii::t('app', 'Users'),
		];
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getComments()
	{
		return $this->hasMany(ReportComment::className(), ['report_id' => 'report_id']);
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getHistories()
	{
		return $this->hasMany(ReportHistory::className(), ['report_id' => 'report_id']);
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getStatuses()
	{
		return $this->hasMany(ReportStatus::className(), ['report_id' => 'report_id']);
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getUsers()
	{
		return $this->hasMany(ReportUser::className(), ['report_id' => 'report_id']);
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getCategory()
	{
		return $this->hasOne(ReportCategory::className(), ['cat_id' => 'cat_id']);
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
	 * @return \yii\db\ActiveQuery
	 */
	public function getView()
	{
		return $this->hasOne(ReportsView::className(), ['report_id' => 'report_id']);
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
		if(!Yii::$app->request->get('category')) {
			$this->templateColumns['cat_id'] = [
				'attribute' => 'cat_id',
				'filter' => ReportCategory::getCategory(),
				'value' => function($model, $key, $index, $column) {
					return isset($model->category) ? $model->category->title->message : '-';
				},
			];
		}
		$this->templateColumns['report_url'] = [
			'attribute' => 'report_url',
			'value' => function($model, $key, $index, $column) {
				return $model->report_url;
			},
		];
		$this->templateColumns['report_body'] = [
			'attribute' => 'report_body',
			'value' => function($model, $key, $index, $column) {
				return $model->report_body;
			},
		];
		$this->templateColumns['report_message'] = [
			'attribute' => 'report_message',
			'value' => function($model, $key, $index, $column) {
				return $model->report_message;
			},
			'format' => 'html',
		];
		$this->templateColumns['report_date'] = [
			'attribute' => 'report_date',
			'filter' => Html::input('date', 'report_date', Yii::$app->request->get('report_date'), ['class'=>'form-control']),
			'value' => function($model, $key, $index, $column) {
				return !in_array($model->report_date, ['0000-00-00 00:00:00','1970-01-01 00:00:00','0002-12-02 07:07:12','-0001-11-30 00:00:00']) ? Yii::$app->formatter->format($model->report_date, 'datetime') : '-';
			},
			'format' => 'html',
		];
		if(!Yii::$app->request->get('user')) {
			$this->templateColumns['reporter_search'] = [
				'attribute' => 'reporter_search',
				'value' => function($model, $key, $index, $column) {
					return isset($model->user) ? $model->user->displayname : '-';
				},
			];
		}
		$this->templateColumns['report_ip'] = [
			'attribute' => 'report_ip',
			'value' => function($model, $key, $index, $column) {
				return $model->report_ip;
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
		$this->templateColumns['updated_date'] = [
			'attribute' => 'updated_date',
			'filter' => Html::input('date', 'updated_date', Yii::$app->request->get('updated_date'), ['class'=>'form-control']),
			'value' => function($model, $key, $index, $column) {
				return !in_array($model->updated_date, ['0000-00-00 00:00:00','1970-01-01 00:00:00','0002-12-02 07:07:12','-0001-11-30 00:00:00']) ? Yii::$app->formatter->format($model->updated_date, 'datetime') : '-';
			},
			'format' => 'html',
		];
		$this->templateColumns['reports'] = [
			'attribute' => 'reports',
			'filter' => false,
			'value' => function($model, $key, $index, $column) {
				$url = Url::to(['history/index', 'report'=>$model->primaryKey]);
				return Html::a($model->reports, $url);
			},
			'contentOptions' => ['class'=>'center'],
			'format' => 'raw',
		];
		$this->templateColumns['user_search'] = [
			'attribute' => 'user_search',
			'filter' => false,
			'value' => function($model, $key, $index, $column) {
				$url = Url::to(['user/index', 'report'=>$model->primaryKey, 'publish'=>1]);
				return Html::a($model->view->users, $url);
			},
			'contentOptions' => ['class'=>'center'],
			'format' => 'raw',
		];
		$this->templateColumns['status'] = [
			'attribute' => 'status',
			'filter' => $this->filterYesNo(),
			'value' => function($model, $key, $index, $column) {
				$url = Url::to(['status', 'id'=>$model->primaryKey]);
				return Html::a($model->status == 1 ? Yii::t('app', 'Resolved') : Yii::t('app', 'Unresolved'), $url);
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
				->where(['report_id' => $id])
				->one();
			return $model->$column;
			
		} else {
			$model = self::findOne($id);
			return $model;
		}
	}

	/**
	 * insertReport
	 */
	public static function insertReport($report_url, $report_body)
	{
		$user_id = !Yii::$app->user->isGuest ? Yii::$app->user->id : null;

		$setting = ReportSetting::find()
			->select(['auto_report_cat_id'])
			->where(['id' => 1])->one();

		$auto_report_cat_id = $setting !== null ? $setting->auto_report_cat_id : null;

		if($auto_report_cat_id) {
			$findReport = self::find()
				->select(['report_id','cat_id','report_url','reports'])
				->where(['cat_id' => $auto_report_cat_id])
				->andWhere(['report_url' => $report_url])
				->one();
				
			if($findReport !== null)
				$findReport->updateAttributes(['user_id'=>$user_id, 'reports'=>$findReport->reports+1, 'report_ip'=>$_SERVER['REMOTE_ADDR']]);
	
			else {
				$report = new Reports();
				$report->scenario = 'reportForm';
				$report->cat_id = $auto_report_cat_id;
				$report->report_url = $report_url;
				$report->report_body = $report_body;
				$report->save();
			}
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
			else
				$this->modified_id = !Yii::$app->user->isGuest ? Yii::$app->user->id : null;

			$this->report_ip = $_SERVER['REMOTE_ADDR'];
		}
		return true;
	}
}
