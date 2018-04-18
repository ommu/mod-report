<?php
/**
 * ReportHistory
 * version: 0.0.1
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
 * @property Reports $reports

 * @copyright Copyright (c) 2017 ECC UGM (ecc.ft.ugm.ac.id)
 * @link http://ecc.ft.ugm.ac.id
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @created date 19 September 2017, 23:26 WIB
 * @contact (+62)856-299-4114
 *
 */

namespace app\modules\report\models;

use Yii;
use yii\helpers\Url;
use app\coremodules\user\models\Users;

class ReportHistory extends \app\components\ActiveRecord
{
	public $gridForbiddenColumn = [];

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
			[['report_id', 'user_id', 'report_ip'], 'required'],
			[['report_id', 'user_id'], 'integer'],
			[['report_date'], 'safe'],
			[['report_ip'], 'string', 'max' => 20],
			[['report_id'], 'exist', 'skipOnError' => true, 'targetClass' => Reports::className(), 'targetAttribute' => ['report_id' => 'report_id']],
		];
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getReports()
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
			'reports_search' => Yii::t('app', 'Reports'),
			'user_search' => Yii::t('app', 'User'),
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
		$this->templateColumns['reports_search'] = [
			'attribute' => 'reports_search',
			'value' => function($model, $key, $index, $column) {
				return $model->reports->reports;
			},
		];
		$this->templateColumns['user_search'] = [
			'attribute' => 'user_search',
			'value' => function($model, $key, $index, $column) {
				return $model->user->displayname ? $model->user->displayname : '-';
			},
		];
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
	}

	/**
	 * before validate attributes
	 */
	public function beforeValidate() 
	{
		if(parent::beforeValidate()) {
			// Create action
		}
		return true;
	}

}
