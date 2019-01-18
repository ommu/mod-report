<?php
/**
 * Reports
 * 
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2017 OMMU (www.ommu.co)
 * @created date 19 September 2017, 22:55 WIB
 * @modified date 17 January 2019, 11:37 WIB
 * @link https://github.com/ommu/mod-report
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

namespace ommu\report\models;

use Yii;
use yii\helpers\Url;
use yii\helpers\Html;
use ommu\users\models\Users;
use ommu\report\models\view\Reports as ReportsView;

class Reports extends \app\components\ActiveRecord
{
	use \ommu\traits\UtilityTrait;

	public $gridForbiddenColumn = ['report_url','report_message','report_ip','modified_date','modified_search','updated_date'];

	// Search Variable
	public $reporter_search;
	public $modified_search;

	const SCENARIO_REPORT = 'reportForm';
	const SCENARIO_RESOLVED = 'resolveForm';

	/**
	 * @return string the associated database table name
	 */
	public static function tableName()
	{
		return 'ommu_reports';
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
			[['report_ip'], 'string', 'max' => 20],
			[['cat_id'], 'exist', 'skipOnError' => true, 'targetClass' => ReportCategory::className(), 'targetAttribute' => ['cat_id' => 'cat_id']],
			[['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => Users::className(), 'targetAttribute' => ['user_id' => 'user_id']],
		];
	}

	/**
	 * {@inheritdoc}
	 */
	public function scenarios()
	{
		return [
			self::SCENARIO_REPORT => ['cat_id', 'report_url', 'report_body'],
			self::SCENARIO_RESOLVED => ['report_message'],
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
			'comments' => Yii::t('app', 'Comments'),
			'histories' => Yii::t('app', 'Histories'),
			'statuses' => Yii::t('app', 'Statuses'),
			'users' => Yii::t('app', 'Users'),
			'reporter_search' => Yii::t('app', 'Reporter'),
			'modified_search' => Yii::t('app', 'Modified'),
		];
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getComments($count=true, $publish=1)
	{
		if($count == true) {
			$model = ReportComment::find();
			$model->where(['publish' => $publish]);
			return $model->count();
		}

		return $this->hasMany(ReportComment::className(), ['report_id' => 'report_id'])
			->andOnCondition([sprintf('%s.publish', ReportComment::tableName()) => $publish]);
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getHistories($count=true)
	{
		if($count == true) {
			$model = ReportHistory::find();
			return $model->count();
		}

		return $this->hasMany(ReportHistory::className(), ['report_id' => 'report_id']);
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getStatuses($count=true)
	{
		if($count == true) {
			$model = ReportStatus::find();
			return $model->count();
		}

		return $this->hasMany(ReportStatus::className(), ['report_id' => 'report_id']);
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getUsers($count=true, $publish=1)
	{
		if($count == true) {
			$model = ReportUser::find();
			$model->where(['publish' => $publish]);
			return $model->count();
		}

		return $this->hasMany(ReportUser::className(), ['report_id' => 'report_id'])
			->andOnCondition([sprintf('%s.publish', ReportUser::tableName()) => $publish]);
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
	 * {@inheritdoc}
	 * @return \ommu\report\models\query\Reports the active query used by this AR class.
	 */
	public static function find()
	{
		return new \ommu\report\models\query\Reports(get_called_class());
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
				'value' => function($model, $key, $index, $column) {
					return isset($model->category) ? $model->category->title->message : '-';
				},
				'filter' => ReportCategory::getCategory(),
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
			'format' => 'html',
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
		$this->templateColumns['modified_date'] = [
			'attribute' => 'modified_date',
			'value' => function($model, $key, $index, $column) {
				return Yii::$app->formatter->asDatetime($model->modified_date, 'medium');
			},
			'filter' => $this->filterDatepicker($this, 'modified_date'),
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
			'value' => function($model, $key, $index, $column) {
				return Yii::$app->formatter->asDatetime($model->updated_date, 'medium');
			},
			'filter' => $this->filterDatepicker($this, 'updated_date'),
		];
		$this->templateColumns['reports'] = [
			'attribute' => 'reports',
			'filter' => false,
			'value' => function($model, $key, $index, $column) {
				return Html::a($model->reports, ['history/admin/manage', 'report'=>$model->primaryKey], ['title'=>Yii::t('app', '{count} histories', ['count'=>$model->reports])]);
			},
			'contentOptions' => ['class'=>'center'],
			'format' => 'html',
		];
		$this->templateColumns['comments'] = [
			'attribute' => 'comments',
			'filter' => false,
			'value' => function($model, $key, $index, $column) {
				return Html::a($model->comments, ['history/comment/manage', 'report'=>$model->primaryKey, 'publish'=>1], ['title'=>Yii::t('app', '{count} comments', ['count'=>$model->comments])]);
			},
			'contentOptions' => ['class'=>'center'],
			'format' => 'html',
		];
		$this->templateColumns['statuses'] = [
			'attribute' => 'statuses',
			'filter' => false,
			'value' => function($model, $key, $index, $column) {
				return Html::a($model->statuses, ['history/status/manage', 'report'=>$model->primaryKey], ['title'=>Yii::t('app', '{count} statuses', ['count'=>$model->statuses])]);
			},
			'contentOptions' => ['class'=>'center'],
			'format' => 'html',
		];
		$this->templateColumns['users'] = [
			'attribute' => 'users',
			'filter' => false,
			'value' => function($model, $key, $index, $column) {
				return Html::a($model->users, ['history/user/manage', 'report'=>$model->primaryKey, 'publish'=>1], ['title'=>Yii::t('app', '{count} users', ['count'=>$model->users])]);
			},
			'contentOptions' => ['class'=>'center'],
			'format' => 'html',
		];
		$this->templateColumns['status'] = [
			'attribute' => 'status',
			'filter' => $this->filterYesNo(),
			'value' => function($model, $key, $index, $column) {
				$status = $model->status == 1 ? Yii::t('app', 'Resolved') : Yii::t('app', 'Unresolved');
				$title = $model->status != 1 ? Yii::t('app', 'Resolved') : Yii::t('app', 'Unresolved');
				return Html::a($status, ['status', 'id'=>$model->primaryKey], ['title'=>Yii::t('app', 'Click to {title}', ['title'=>$title])]);
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
				->select(['report_id','report_url','reports'])
				->where(['cat_id' => $auto_report_cat_id])
				->andWhere(['report_url' => $report_url])
				->one();
				
			if($findReport !== null)
				$findReport->updateAttributes(['user_id'=>$user_id, 'reports'=>$findReport->reports+1, 'report_ip'=>$_SERVER['REMOTE_ADDR']]);
	
			else {
				$report = new Reports();
				$report->scenario = Reports::SCENARIO_REPORT;
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
			if($this->isNewRecord) {
				if($this->user_id == null)
					$this->user_id = !Yii::$app->user->isGuest ? Yii::$app->user->id : null;
			} else {
				if($this->modified_id == null)
					$this->modified_id = !Yii::$app->user->isGuest ? Yii::$app->user->id : null;
			}
			$this->report_ip = $_SERVER['REMOTE_ADDR'];
		}
		return true;
	}
}
