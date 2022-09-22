<?php
/**
 * ReportRead
 * 
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2022 OMMU (www.ommu.id)
 * @created date 28 August 2022, 07:24 WIB
 * @link https://github.com/ommu/mod-report
 *
 * This is the model class for table "ommu_report_read".
 *
 * The followings are the available columns in table "ommu_report_read":
 * @property string $id
 * @property integer $report_id
 * @property integer $user_id
 * @property string $creation_date
 *
 * The followings are the available model relations:
 * @property Reports $report
 * @property Users $user
 *
 */

namespace ommu\report\models;

use Yii;
use thamtech\uuid\helpers\UuidHelper;
use app\models\Users;

class ReportRead extends \app\components\ActiveRecord
{
	public $gridForbiddenColumn = [];

	public $categoryId;
	public $reportBody;
	public $userDisplayname;

	/**
	 * @return string the associated database table name
	 */
	public static function tableName()
	{
		return 'ommu_report_read';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		return [
			[['id', 'report_id'], 'required'],
			[['report_id', 'user_id'], 'integer'],
			[['id'], 'string', 'max' => 36],
			[['id'], 'unique'],
			[['user_id'], 'safe'],
			[['report_id'], 'exist', 'skipOnError' => true, 'targetClass' => Reports::className(), 'targetAttribute' => ['report_id' => 'report_id']],
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
			'creation_date' => Yii::t('app', 'Creation Date'),
			'categoryId' => Yii::t('app', 'Category'),
			'reportBody' => Yii::t('app', 'Report'),
			'userDisplayname' => Yii::t('app', 'User'),
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
	 * @return \ommu\report\models\query\ReportRead the active query used by this AR class.
	 */
	public static function find()
	{
		return new \ommu\report\models\query\ReportRead(get_called_class());
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
				return isset($model->report) ? $model->report->category->title->message : '-';
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
			'visible' => !Yii::$app->request->get('report') ? true : false,
		];
		$this->templateColumns['userDisplayname'] = [
			'attribute' => 'userDisplayname',
			'value' => function($model, $key, $index, $column) {
				return isset($model->user) ? $model->user->displayname : '-';
				// return $model->userDisplayname;
			},
			'visible' => !Yii::$app->request->get('user') ? true : false,
		];
		$this->templateColumns['creation_date'] = [
			'attribute' => 'creation_date',
			'value' => function($model, $key, $index, $column) {
				return Yii::$app->formatter->asDatetime($model->creation_date, 'medium');
			},
			'filter' => $this->filterDatepicker($this, 'creation_date'),
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
	 * {@inheritdoc}
	 */
	public function insertView($report_id, $user_id=null)
	{
        if ($user_id == null) {
            $user_id = !Yii::$app->user->isGuest ? Yii::$app->user->id : null;
        }

        $model = new self();
        $model->report_id = $report_id;
        if ($user_id != null) {
            $model->user_id = $user_id;
        }
        $model->save();
	}

	/**
	 * after find attributes
	 */
	public function afterFind()
	{
		parent::afterFind();

		// $this->categoryId = iisset($model->report) ? $model->report->category->title->message : '-';
		// $this->reportBody = isset($this->report) ? $this->report->report_body : '-';
		// $this->userDisplayname = isset($this->user) ? $this->user->displayname : '-';
	}

	/**
	 * before validate attributes
	 */
	public function beforeValidate()
	{
        if (parent::beforeValidate()) {
            if ($this->isNewRecord) {
                $this->id = UuidHelper::uuid();

                if ($this->user_id == null) {
                    $this->user_id = !Yii::$app->user->isGuest ? Yii::$app->user->id : null;
                }
            }
        }
        return true;
	}
}
