<?php
/**
 * Reports
 * 
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2017 OMMU (www.ommu.id)
 * @created date 19 September 2017, 22:55 WIB
 * @modified date 17 January 2019, 11:37 WIB
 * @link https://github.com/ommu/mod-report
 *
 * This is the model class for table "ommu_reports".
 *
 * The followings are the available columns in table "ommu_reports":
 * @property integer $report_id
 * @property string $app
 * @property integer $status
 * @property integer $read
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
 * @property ReportGrid $grid
 * @property ReportHistory[] $histories
 * @property ReportRead[] $reads
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
use app\models\Users;
use app\models\SourceMessage;

class Reports extends \app\components\ActiveRecord
{
	use \ommu\traits\UtilityTrait;

	public $gridForbiddenColumn = ['report_url', 'report_message', 'reports', 'report_ip', 'modified_date', 'modifiedDisplayname', 'updated_date', 'oComment', 'oRead', 'oStatus', 'oUser'];

	public $categoryName;
	public $reporterDisplayname;
	public $modifiedDisplayname;
    public $oComment;
    public $oRead;
    public $oStatus;
    public $oUser;

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
			[['report_url', 'report_body'], 'required'],
			[['app'], 'required', 'on' => self::SCENARIO_REPORT],
			[['report_message'], 'required', 'on' => self::SCENARIO_RESOLVED],
			[['status', 'read', 'cat_id', 'user_id', 'reports', 'modified_id'], 'integer'],
			[['app', 'report_url', 'report_body', 'report_message'], 'string'],
			[['app', 'cat_id'], 'safe'],
			[['app'], 'string', 'max' => 32],
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
		$scenarios = parent::scenarios();
		$scenarios[self::SCENARIO_REPORT] = ['cat_id', 'app', 'report_url', 'report_body'];
		$scenarios[self::SCENARIO_RESOLVED] = ['report_message'];
		return $scenarios;
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return [
			'report_id' => Yii::t('app', 'Report'),
			'app' => Yii::t('app', 'Application'),
			'status' => Yii::t('app', 'Status'),
			'read' => Yii::t('app', 'Read'),
			'cat_id' => Yii::t('app', 'Category'),
			'user_id' => Yii::t('app', 'User'),
			'report_url' => Yii::t('app', 'URL'),
			'report_body' => Yii::t('app', 'Error'),
			'report_message' => Yii::t('app', 'Noted'),
			'reports' => Yii::t('app', 'Reports'),
			'report_date' => Yii::t('app', 'Report Date'),
			'report_ip' => Yii::t('app', 'Report IP'),
			'modified_date' => Yii::t('app', 'Modified Date'),
			'modified_id' => Yii::t('app', 'Modified'),
			'updated_date' => Yii::t('app', 'Updated Date'),
			'comments' => Yii::t('app', 'Comments'),
			'histories' => Yii::t('app', 'Histories'),
			'statuses' => Yii::t('app', 'Statuses'),
			'users' => Yii::t('app', 'Users'),
			'reads' => Yii::t('app', 'Reads'),
			'categoryName' => Yii::t('app', 'Category'),
			'reporterDisplayname' => Yii::t('app', 'Reporter'),
			'modifiedDisplayname' => Yii::t('app', 'Modified'),
			'oComment' => Yii::t('app', 'Comments'),
			'oRead' => Yii::t('app', 'Reads'),
			'oStatus' => Yii::t('app', 'Statuses'),
			'oUser' => Yii::t('app', 'Users'),
		];
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getComments($count=false, $publish=1)
	{
        if ($count == false) {
            return $this->hasMany(ReportComment::className(), ['report_id' => 'report_id'])
                ->alias('comments')
                ->andOnCondition([sprintf('%s.publish', 'comments') => $publish]);
        }

		$model = ReportComment::find()
            ->alias('t')
            ->where(['t.report_id' => $this->report_id]);
        if ($publish == 0) {
            $model->unpublish();
        } else if ($publish == 1) {
            $model->published();
        } else if ($publish == 2) {
            $model->deleted();
        }
		$comments = $model->count();

		return $comments ? $comments : 0;
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getGrid()
	{
		return $this->hasOne(ReportGrid::className(), ['id' => 'report_id']);
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getHistories($count=false)
	{
        if ($count == false) {
            return $this->hasMany(ReportHistory::className(), ['report_id' => 'report_id']);
        }

		$model = ReportHistory::find()
            ->alias('t')
            ->where(['t.report_id' => $this->report_id]);
		$histories = $model->count();

		return $histories ? $histories : 0;
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getReads($count=false)
	{
        if ($count == false) {
            return $this->hasMany(ReportRead::className(), ['report_id' => 'report_id']);
        }

		$model = ReportRead::find()
            ->alias('t')
            ->where(['t.report_id' => $this->report_id]);
		$reads = $model->count();

		return $reads ? $reads : 0;
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getStatuses($count=false)
	{
        if ($count == false) {
            return $this->hasMany(ReportStatus::className(), ['report_id' => 'report_id']);
        }

		$model = ReportStatus::find()
            ->alias('t')
            ->where(['t.report_id' => $this->report_id]);
		$statuses = $model->count();

		return $statuses ? $statuses : 0;
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getUsers($count=false)
	{
        if ($count == false) {
            return $this->hasMany(ReportUser::className(), ['report_id' => 'report_id']);
        }

		$model = ReportUser::find()
            ->alias('t')
            ->where(['t.report_id' => $this->report_id]);
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
	public function getCategoryTitle()
	{
		return $this->hasOne(SourceMessage::className(), ['id' => 'name'])->via('category');
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
		$this->templateColumns['app'] = [
			'attribute' => 'app',
			'value' => function($model, $key, $index, $column) {
				return $model->app;
			},
		];
		$this->templateColumns['cat_id'] = [
			'attribute' => 'cat_id',
			'value' => function($model, $key, $index, $column) {
				return isset($model->categoryTitle) ? $model->categoryTitle->message : '-';
				// return $model->categoryName;
			},
			'filter' => ReportCategory::getCategory(),
			'visible' => !Yii::$app->request->get('category') ? true : false,
		];
		$this->templateColumns['report_body'] = [
			'attribute' => 'report_body',
			'value' => function($model, $key, $index, $column) {
				return $model->parseReportBody();
			},
			'format' => 'raw',
		];
		$this->templateColumns['report_message'] = [
			'attribute' => 'report_message',
			'value' => function($model, $key, $index, $column) {
				return $model->report_message;
			},
			'format' => 'html',
		];
		$this->templateColumns['reporterDisplayname'] = [
			'attribute' => 'reporterDisplayname',
			'value' => function($model, $key, $index, $column) {
				return isset($model->user) ? $model->user->displayname : '-';
				// return $model->reporterDisplayname;
			},
			'visible' => !Yii::$app->request->get('user') ? true : false,
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
		$this->templateColumns['modifiedDisplayname'] = [
			'attribute' => 'modifiedDisplayname',
			'value' => function($model, $key, $index, $column) {
				return isset($model->modified) ? $model->modified->displayname : '-';
				// return $model->modifiedDisplayname;
			},
			'visible' => !Yii::$app->request->get('modified') ? true : false,
		];
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
				return Html::a($reports, ['history/admin/manage', 'report' => $model->primaryKey], ['title' => Yii::t('app', '{count} reports', ['count' => $reports]), 'data-pjax' => 0]);
			},
			'filter' => false,
			'contentOptions' => ['class' => 'text-center'],
			'format' => 'raw',
		];
		$this->templateColumns['oComment'] = [
			'attribute' => 'oComment',
			'value' => function($model, $key, $index, $column) {
				// $comments = $model->getComments(true);
                $comments = $model->oComment;
				return Html::a($comments, ['history/comment/manage', 'report' => $model->primaryKey, 'publish' => 1], ['title' => Yii::t('app', '{count} comments', ['count' => $comments]), 'data-pjax' => 0]);
			},
			'filter' => $this->filterYesNo(),
			'contentOptions' => ['class' => 'text-center'],
			'format' => 'raw',
		];
        $this->templateColumns['oRead'] = [
            'attribute' => 'oRead',
            'value' => function($model, $key, $index, $column) {
                // $reads = $model->getReads(true);
                $reads = $model->oRead;
                return Html::a($reads, ['history/read/manage', 'report' => $model->primaryKey], ['title' => Yii::t('app', '{count} reads', ['count' => $reads]), 'data-pjax' => 0]);
            },
            'filter' => $this->filterYesNo(),
            'contentOptions' => ['class' => 'text-center'],
            'format' => 'raw',
        ];
		$this->templateColumns['oStatus'] = [
			'attribute' => 'oStatus',
			'value' => function($model, $key, $index, $column) {
				// $statuses = $model->getStatuses(true);
                $statuses = $model->oStatus;
				return Html::a($statuses, ['history/status/manage', 'report' => $model->primaryKey], ['title' => Yii::t('app', '{count} statuses', ['count' => $statuses]), 'data-pjax' => 0]);
			},
			'filter' => $this->filterYesNo(),
			'contentOptions' => ['class' => 'text-center'],
			'format' => 'raw',
		];
		$this->templateColumns['oUser'] = [
			'attribute' => 'oUser',
			'value' => function($model, $key, $index, $column) {
				// $users = $model->getUsers(true);
                $users = $model->oUser;
				return Html::a($users, ['history/user/manage', 'report' => $model->primaryKey, 'publish' => 1], ['title' => Yii::t('app', '{count} users', ['count' => $users]), 'data-pjax' => 0]);
			},
			'filter' => $this->filterYesNo(),
			'contentOptions' => ['class' => 'text-center'],
			'format' => 'raw',
		];
		$this->templateColumns['read'] = [
			'attribute' => 'read',
			'value' => function($model, $key, $index, $column) {
				return $this->filterYesNo($model->read);
			},
			'filter' => $this->filterYesNo(),
			'contentOptions' => ['class' => 'text-center'],
			'format' => 'raw',
		];
		$this->templateColumns['status'] = [
			'attribute' => 'status',
			'value' => function($model, $key, $index, $column) {
				$status = $model->status == 1 ? Yii::t('app', 'Resolved') : Yii::t('app', 'Unresolved');
				$title = $model->status != 1 ? Yii::t('app', 'Resolved') : Yii::t('app', 'Unresolved');
				return Html::a($status, ['status', 'id' => $model->primaryKey], ['title' => Yii::t('app', 'Click to {title}', ['title' => $title]), 'class' => 'modal-btn']);
			},
			'filter' => $this->filterYesNo(),
			'contentOptions' => ['class' => 'text-center'],
			'format' => 'raw',
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
            $model = $model->where(['report_id' => $id])->one();
            return is_array($column) ? $model : $model->$column;

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
			->where(['id' => 1])
			->one();

		$autoReportCatId = $setting !== null && isset($setting->category) ? $setting->auto_report_cat_id : null;

        if ($autoReportCatId) {
			$report = self::find()
				->select(['report_id', 'status', 'report_url', 'report_body', 'reports'])
				->where(['cat_id' => $autoReportCatId])
				->andWhere(['app' => Yii::$app->id])
				->andWhere(['report_url' => $report_url])
				->one();
				
            if ($report !== null) {
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
	 * {@inheritdoc}
	 */
	public function parseReportBody()
	{
        $reports = $this->reports;
        $comments = $this->oComment;
        $reads = $this->oRead;
        $users = $this->oUser;
    
        $html = $this->report_body;
        $html .= '<hr class="mt-5 mb-5"/>';

        $html .= Html::button(Yii::t('app', 'url'), ['class' => 'btn btn-secondary btn-xs px-5']);
        $html .= Html::a($this->report_url, $this->report_url, ['title' => $this->report_url, 'target' => '_blank', 'data-pjax' => 0]);
        $html .= '<hr class="mt-5 mb-5"/>';
        
        $html .= Html::a(Yii::t('app', '{count} reports', ['count' => $reports]), ['history/admin/manage', 'report' => $this->primaryKey], ['title' => Yii::t('app', '{count} reports', ['count' => $reports]), 'class' => 'btn btn-primary btn-xs mr-5', 'data-pjax' => 0]);
        $html .= $users ? Html::a(Yii::t('app', '{count} users', ['count' => $users]), ['history/user/manage', 'report' => $this->primaryKey, 'publish' => 1], ['title' => Yii::t('app', '{count} users', ['count' => $users]), 'class' => 'btn btn-success btn-xs mr-5', 'data-pjax' => 0]) : '';
        $html .= $reads ? Html::a(Yii::t('app', '{count} reads', ['count' => $reads]), ['history/read/manage', 'report' => $this->primaryKey], ['title' => Yii::t('app', '{count} reads', ['count' => $reads]), 'class' => 'btn btn-info btn-xs mr-5', 'data-pjax' => 0]) : '';
        $html .= $comments ? Html::a(Yii::t('app', '{count} comments', ['count' => $comments]), ['history/comment/manage', 'report' => $this->primaryKey, 'publish' => 1], ['title' => Yii::t('app', '{count} comments', ['count' => $comments]), 'class' => 'btn btn-warning btn-xs mr-5', 'data-pjax' => 0]) : '';

        return $html;
    }

	/**
	 * after find attributes
	 */
	public function afterFind()
	{
		parent::afterFind();

		// $this->categoryName = isset($model->category) ? $model->category->title->message : '-';
		// $this->reporterDisplayname = isset($model->user) ? $model->user->displayname : '-';
		// $this->modifiedDisplayname = isset($this->modified) ? $this->modified->displayname : '-';
        // $this->comment = $this->getComments(true) ? 1 : 0;
        // $this->read = $this->getReads(true) ? 1 : 0;
        // $this->status = $this->getStatuses(true) ? 1 : 0;
        // $this->user = $this->getUsers(true) ? 1 : 0;
        $this->oComment = isset($this->grid) ? $this->grid->comment : 0;
        $this->oRead = isset($this->grid) ? $this->grid->read : 0;
        $this->oStatus = isset($this->grid) ? $this->grid->status : 0;
        $this->oUser = isset($this->grid) ? $this->grid->user : 0;
	}

	/**
	 * before validate attributes
	 */
	public function beforeValidate()
	{
        if (parent::beforeValidate()) {
            if ($this->isNewRecord) {
                if ($this->user_id == null) {
                    $this->user_id = !Yii::$app->user->isGuest ? Yii::$app->user->id : null;
                }
            } else {
                if ($this->modified_id == null) {
                    $this->modified_id = !Yii::$app->user->isGuest ? Yii::$app->user->id : null;
                }
            }
            $this->app = Yii::$app->id;
			$this->report_ip = $_SERVER['REMOTE_ADDR'];
		}

		return true;
	}
}
