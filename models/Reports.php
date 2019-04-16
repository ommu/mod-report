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
 * @property string $app
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
use yii\helpers\Html;
use yii\helpers\Url;
use ommu\users\models\Users;
use ommu\report\models\view\Reports as ReportsView;

class Reports extends \app\components\ActiveRecord
{
	use \ommu\traits\UtilityTrait;

	public $gridForbiddenColumn = ['report_url','report_message','report_ip','modified_date','modifiedDisplayname','updated_date','comments','histories','statuses','users'];

	public $categoryId;
	public $reporterDisplayname;
	public $modifiedDisplayname;

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
			[['cat_id', 'app', 'report_url', 'report_body', 'report_message'], 'required'],
			[['status', 'cat_id', 'user_id', 'reports', 'modified_id'], 'integer'],
			[['app', 'report_url', 'report_body', 'report_message'], 'string'],
			[['report_ip'], 'string', 'max' => 20],
			[['app'], 'string', 'max' => 32],
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
			self::SCENARIO_REPORT => ['cat_id', 'app', 'report_url', 'report_body'],
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
			'app' => Yii::t('app', 'Application'),
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
			'reporterDisplayname' => Yii::t('app', 'Reporter'),
			'modifiedDisplayname' => Yii::t('app', 'Modified'),
		];
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getComments($count=false, $publish=1)
	{
		if($count == false) {
			return $this->hasMany(ReportComment::className(), ['report_id' => 'report_id'])
				->andOnCondition([sprintf('%s.publish', ReportComment::tableName()) => $publish]);
		}

		$model = ReportComment::find()
			->where(['report_id' => $this->report_id]);
		if($publish == 0)
			$model->unpublish();
		elseif($publish == 1)
			$model->published();
		elseif($publish == 2)
			$model->deleted();
		$comments = $model->count();

		return $comments ? $comments : 0;
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getHistories($count=false)
	{
		if($count == false)
			return $this->hasMany(ReportHistory::className(), ['report_id' => 'report_id']);

		$model = ReportHistory::find()
			->where(['report_id' => $this->report_id]);
		$histories = $model->count();

		return $histories ? $histories : 0;
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getStatuses($count=false)
	{
		if($count == false)
			return $this->hasMany(ReportStatus::className(), ['report_id' => 'report_id']);

		$model = ReportStatus::find()
			->where(['report_id' => $this->report_id]);
		$statuses = $model->count();

		return $statuses ? $statuses : 0;
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getUsers($count=false, $publish=1)
	{
		if($count == false) {
			return $this->hasMany(ReportUser::className(), ['report_id' => 'report_id'])
				->andOnCondition([sprintf('%s.publish', ReportUser::tableName()) => $publish]);
		}

		$model = ReportUser::find()
			->where(['report_id' => $this->report_id]);
		if($publish == 0)
			$model->unpublish();
		elseif($publish == 1)
			$model->published();
		elseif($publish == 2)
			$model->deleted();
		$users = $model->count();

		return $users ? $users : 0;
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
					// return $model->categoryId;
				},
				'filter' => ReportCategory::getCategory(),
			];
		}
		if(!Yii::$app->request->get('user')) {
			$this->templateColumns['reporterDisplayname'] = [
				'attribute' => 'reporterDisplayname',
				'value' => function($model, $key, $index, $column) {
					return isset($model->user) ? $model->user->displayname : '-';
					// return $model->reporterDisplayname;
				},
			];
		}
		$this->templateColumns['app'] = [
			'attribute' => 'app',
			'value' => function($model, $key, $index, $column) {
				return $model->app;
			},
		];
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
			$this->templateColumns['modifiedDisplayname'] = [
				'attribute' => 'modifiedDisplayname',
				'value' => function($model, $key, $index, $column) {
					return isset($model->modified) ? $model->modified->displayname : '-';
					// return $model->modifiedDisplayname;
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
			'value' => function($model, $key, $index, $column) {
				$reports = $model->reports;
				return Html::a($reports, ['history/admin/manage', 'report'=>$model->primaryKey], ['title'=>Yii::t('app', '{count} reports', ['count'=>$reports])]);
			},
			'filter' => false,
			'contentOptions' => ['class'=>'center'],
			'format' => 'html',
		];
		$this->templateColumns['comments'] = [
			'attribute' => 'comments',
			'value' => function($model, $key, $index, $column) {
				$comments = $model->getComments(true);
				return Html::a($comments, ['history/comment/manage', 'report'=>$model->primaryKey, 'publish'=>1], ['title'=>Yii::t('app', '{count} comments', ['count'=>$comments])]);
			},
			'filter' => false,
			'contentOptions' => ['class'=>'center'],
			'format' => 'html',
		];
		$this->templateColumns['statuses'] = [
			'attribute' => 'statuses',
			'value' => function($model, $key, $index, $column) {
				$statuses = $model->getStatuses(true);
				return Html::a($statuses, ['history/status/manage', 'report'=>$model->primaryKey], ['title'=>Yii::t('app', '{count} statuses', ['count'=>$statuses])]);
			},
			'filter' => false,
			'contentOptions' => ['class'=>'center'],
			'format' => 'html',
		];
		$this->templateColumns['users'] = [
			'attribute' => 'users',
			'value' => function($model, $key, $index, $column) {
				$users = $model->getUsers(true);
				return Html::a($users, ['history/user/manage', 'report'=>$model->primaryKey, 'publish'=>1], ['title'=>Yii::t('app', '{count} users', ['count'=>$users])]);
			},
			'filter' => false,
			'contentOptions' => ['class'=>'center'],
			'format' => 'html',
		];
		$this->templateColumns['status'] = [
			'attribute' => 'status',
			'value' => function($model, $key, $index, $column) {
				$status = $model->status == 1 ? Yii::t('app', 'Resolved') : Yii::t('app', 'Unresolved');
				$title = $model->status != 1 ? Yii::t('app', 'Resolved') : Yii::t('app', 'Unresolved');
				return Html::a($status, ['status', 'id'=>$model->primaryKey], ['title'=>Yii::t('app', 'Click to {title}', ['title'=>$title])]);
			},
			'filter' => $this->filterYesNo(),
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

		$autoReportCatId = $setting !== null ? $setting->auto_report_cat_id : null;

		if($autoReportCatId) {
			$report = self::find()
				->select(['report_id','reports'])
				->where(['cat_id' => $autoReportCatId])
				->andWhere(['app' => \app\components\Application::getAppId()])
				->andWhere(['report_url' => $report_url])
				->one();
				
			if($report !== null) {
				$report->user_id = $user_id;
				$report->reports = $report->reports+1;
				$report->report_ip = $_SERVER['REMOTE_ADDR'];
				$report->update();

			} else {
				$report = new Reports();
				$report->scenario = Reports::SCENARIO_REPORT;
				$report->cat_id = $autoReportCatId;
				$report->report_url = $report_url;
				$report->report_body = $report_body;
				$report->save();
			}
		}
	}

	/**
	 * after find attributes
	 */
	public function afterFind()
	{
		parent::afterFind();

		// $this->categoryId = isset($model->category) ? $model->category->title->message : '-';
		// $this->reporterDisplayname = isset($model->user) ? $model->user->displayname : '-';
		// $this->modifiedDisplayname = isset($this->modified) ? $this->modified->displayname : '-';
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
			$this->app = \app\components\Application::getAppId();
			$this->report_ip = $_SERVER['REMOTE_ADDR'];
		}
		return true;
	}
}
