<?php
/**
 * ReportStatus
 * 
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)811-2540-432
 * @copyright Copyright (c) 2017 OMMU (www.ommu.id)
 * @created date 19 September 2017, 23:27 WIB
 * @modified date 18 January 2019, 14:57 WIB
 * @link https://github.com/ommu/mod-report
 *
 * This is the model class for table "ommu_report_status".
 *
 * The followings are the available columns in table "ommu_report_status":
 * @property integer $id
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
use yii\helpers\Html;
use yii\helpers\Url;
use app\models\Users;
use app\models\SourceMessage;

class ReportStatus extends \app\components\ActiveRecord
{
	use \ommu\traits\UtilityTrait;

	public $gridForbiddenColumn = ['modified_date', 'modifiedDisplayname', 'updated_date'];

	public $categoryId;
	public $reportBody;
	public $reporterDisplayname;
	public $modifiedDisplayname;

	/**
	 * @return string the associated database table name
	 */
	public static function tableName()
	{
		return 'ommu_report_status';
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
			'id' => Yii::t('app', 'ID'),
			'status' => Yii::t('app', 'Status'),
			'report_id' => Yii::t('app', 'Error'),
			'user_id' => Yii::t('app', 'User'),
			'report_message' => Yii::t('app', 'Noted'),
			'updated_date' => Yii::t('app', 'Updated Date'),
			'updated_ip' => Yii::t('app', 'Updated IP'),
			'modified_date' => Yii::t('app', 'Modified Date'),
			'modified_id' => Yii::t('app', 'Modified'),
			'categoryId' => Yii::t('app', 'Category'),
			'reportBody' => Yii::t('app', 'Error'),
			'reporterDisplayname' => Yii::t('app', 'Reporter'),
			'modifiedDisplayname' => Yii::t('app', 'Modified'),
		];
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getReport()
	{
		return $this->hasOne(Reports::className(), ['report_id' => 'report_id'])
            ->select(['report_id', 'cat_id', 'report_body']);
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getCategory()
	{
		return $this->hasOne(ReportCategory::className(), ['cat_id' => 'cat_id'])
            ->select(['cat_id', 'name'])
            ->via('report');
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getCategoryTitle()
	{
		return $this->hasOne(SourceMessage::className(), ['id' => 'name'])
            ->select(['id', 'message'])
            ->via('category');
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getUser()
	{
		return $this->hasOne(Users::className(), ['user_id' => 'user_id'])
            ->select(['user_id', 'displayname']);
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getModified()
	{
		return $this->hasOne(Users::className(), ['user_id' => 'modified_id'])
            ->select(['user_id', 'displayname']);
	}

	/**
	 * {@inheritdoc}
	 * @return \ommu\report\models\query\ReportStatus the active query used by this AR class.
	 */
	public static function find()
	{
		return new \ommu\report\models\query\ReportStatus(get_called_class());
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
		$this->templateColumns['categoryId'] = [
			'attribute' => 'categoryId',
			'value' => function($model, $key, $index, $column) {
				return isset($model->categoryTitle) ? $model->categoryTitle->message : '-';
				// return $model->categoryId;
			},
			'filter' => ReportCategory::getCategory(),
			'visible' => !Yii::$app->request->get('report') && !Yii::$app->request->get('id') && !Yii::$app->request->get('category') ? true : false,
		];
		$this->templateColumns['reportBody'] = [
			'attribute' => 'reportBody',
			'value' => function($model, $key, $index, $column) {
				return isset($model->report) ? $model->report->report_body : '-';
				// return $model->reportBody;
			},
			'format' => 'html',
			'visible' => !Yii::$app->request->get('report') && !Yii::$app->request->get('id') ? true : false,
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
		$this->templateColumns['updated_date'] = [
			'attribute' => 'updated_date',
			'value' => function($model, $key, $index, $column) {
				return Yii::$app->formatter->asDatetime($model->updated_date, 'medium');
			},
			'filter' => $this->filterDatepicker($this, 'updated_date'),
		];
		$this->templateColumns['updated_ip'] = [
			'attribute' => 'updated_ip',
			'value' => function($model, $key, $index, $column) {
				return $model->updated_ip;
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
		$this->templateColumns['status'] = [
			'attribute' => 'status',
			'label' => Yii::t('app', 'Resolved'),
			'value' => function($model, $key, $index, $column) {
				return $this->filterYesNo($model->status);
			},
			'filter' => $this->filterYesNo(),
			'contentOptions' => ['class' => 'text-center'],
			'format' => 'html',
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
	 * after find attributes
	 */
	public function afterFind()
	{
		parent::afterFind();

		// $this->categoryId = iisset($model->report) ? $model->report->category->title->message : '-';
		// $this->reportBody = isset($model->report) ? $model->report->report_body : '-';
		// $this->reporterDisplayname = isset($model->user) ? $model->user->displayname : '-';
		// $this->modifiedDisplayname = isset($this->modified) ? $this->modified->displayname : '-';
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
            $this->updated_ip = $_SERVER['REMOTE_ADDR'];
        }
        return true;
	}
}
