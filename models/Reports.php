<?php
/**
 * Reports
 * version: 0.0.1
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

 * @copyright Copyright (c) 2017 ECC UGM (ecc.ft.ugm.ac.id)
 * @link http://ecc.ft.ugm.ac.id
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @created date 19 September 2017, 22:55 WIB
 * @contact (+62)856-299-4114
 *
 */

namespace app\modules\report\models;

use Yii;
use yii\helpers\Url;
use app\coremodules\user\models\Users;

class Reports extends \app\components\ActiveRecord
{
	public $gridForbiddenColumn = ['modified_date','updated_date','modified_id'];

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
			[['status', 'cat_id', 'user_id', 'reports'], 'integer'],
			[['cat_id', 'report_url', 'report_body', 'report_message', 'report_ip'], 'required'],
			[['report_url', 'report_body', 'report_message'], 'string'],
			[['report_date', 'modified_date', 'updated_date'], 'safe'],
			[['report_ip'], 'string', 'max' => 20],
			[['cat_id'], 'exist', 'skipOnError' => true, 'targetClass' => ReportCategory::className(), 'targetAttribute' => ['cat_id' => 'cat_id']],
		];
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getComments()
	{
		return $this->hasMany(ReportComment::className(), ['comment_id' => 'comment_id']);
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
			'user_search' => Yii::t('app', 'User'),
			'modified_search' => Yii::t('app', 'Modified'),
		];
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
		];
		$this->templateColumns['category_search'] = [
			'attribute' => 'category_search',
			'value' => function($model, $key, $index, $column) {
				return $model->category->name;
			},
		];
		$this->templateColumns['user_search'] = [
			'attribute' => 'user_search',
			'value' => function($model, $key, $index, $column) {
				return $model->user->displayname ? $model->user->displayname : '-';
			},
		];
		$this->templateColumns['report_url'] = 'report_url';
		$this->templateColumns['report_body'] = 'report_body';
		$this->templateColumns['report_message'] = 'report_message';
		$this->templateColumns['reports'] = 'reports';
		$this->templateColumns['report_date'] = [
			'attribute' => 'report_date',
			'filter'	=> \yii\jui\DatePicker::widget([
				'dateFormat' => 'yyyy-MM-dd',
				'attribute' => 'report_date',
				'model'	 => $this,
			]),
			'value' => function($model, $key, $index, $column) {
				return !in_array($model->report_date, ['0000-00-00 00:00:00','1970-01-01 00:00:00']) ? Yii::$app->formatter->format($model->report_date, 'date'/*datetime*/) : '-';
			},
			'format'	=> 'html',
		];
		$this->templateColumns['report_ip'] = 'report_ip';
		$this->templateColumns['modified_date'] = [
			'attribute' => 'modified_date',
			'filter'	=> \yii\jui\DatePicker::widget([
				'dateFormat' => 'yyyy-MM-dd',
				'attribute' => 'modified_date',
				'model'	 => $this,
			]),
			'value' => function($model, $key, $index, $column) {
				return !in_array($model->modified_date, ['0000-00-00 00:00:00','1970-01-01 00:00:00']) ? Yii::$app->formatter->format($model->modified_date, 'date'/*datetime*/) : '-';
			},
			'format'	=> 'html',
		];
		$this->templateColumns['modified_search'] = [
			'attribute' => 'modified_search',
			'value' => function($model, $key, $index, $column) {
				return $model->modified->displayname ? $model->modified->displayname : '-';
			},
		];
		$this->templateColumns['updated_date'] = [
			'attribute' => 'updated_date',
			'filter'	=> \yii\jui\DatePicker::widget([
				'dateFormat' => 'yyyy-MM-dd',
				'attribute' => 'updated_date',
				'model'	 => $this,
			]),
			'value' => function($model, $key, $index, $column) {
				return !in_array($model->updated_date, ['0000-00-00 00:00:00','1970-01-01 00:00:00']) ? Yii::$app->formatter->format($model->updated_date, 'date'/*datetime*/) : '-';
			},
			'format'	=> 'html',
		];
		$this->templateColumns['status'] = [
			'attribute' => 'status',
			'value' => function($model, $key, $index, $column) {
				return $model->status;
			},
			'contentOptions' => ['class'=>'center'],
		];
	}

	// public function insertReports()
	// {
	// 	$user_id = Yii::$app->user->id;
	// 	$reports = Reports::find()->where(['reports' => $report_id, 'user_id' => $user_id])->one();
	// 	$reports = new Reports;
	// 	$reports->report_id = $report_id;
	//  	$reports->user_id = $user_id;
	//  	$reports->report_ip = Yii::$app->request->userIP;
	//  	$reports->save();

	// }
	/**
	 * before validate attributes
	 */

	public function beforeValidate() 
	{
		if(parent::beforeValidate()) {
			if(!$this->isNewRecord)
				$this->modified_id = !Yii::$app->user->isGuest ? Yii::$app->user->id : '0';
			// Create action
		}
		return true;
	}

}
