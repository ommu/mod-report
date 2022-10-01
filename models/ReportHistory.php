<?php
/**
 * ReportHistory
 * 
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2017 OMMU (www.ommu.id)
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
use app\models\Users;
use app\models\SourceMessage;

class ReportHistory extends \app\components\ActiveRecord
{
	public $gridForbiddenColumn = [];

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
			'report_id' => Yii::t('app', 'Error'),
			'user_id' => Yii::t('app', 'User'),
			'report_date' => Yii::t('app', 'Report Date'),
			'report_ip' => Yii::t('app', 'Report IP'),
			'categoryId' => Yii::t('app', 'Category'),
			'reportBody' => Yii::t('app', 'Error'),
			'reporterDisplayname' => Yii::t('app', 'Reporter'),
		];
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getReport()
	{
		return $this->hasOne(Reports::className(), ['report_id' => 'report_id'])
            ->select(['cat_id', 'report_body']);
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getCategory()
	{
		return $this->hasOne(ReportCategory::className(), ['cat_id' => 'cat_id'])
            ->select(['name'])
            ->via('report');
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getCategoryTitle()
	{
		return $this->hasOne(SourceMessage::className(), ['id' => 'name'])
            ->via('category');
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getUser()
	{
		return $this->hasOne(Users::className(), ['user_id' => 'user_id'])
            ->select(['displayname']);
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
	}
}
