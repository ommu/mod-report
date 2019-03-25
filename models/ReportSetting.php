<?php
/**
 * ReportSetting
 * 
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2017 OMMU (www.ommu.co)
 * @created date 19 September 2017, 23:28 WIB
 * @modified date 16 January 2019, 15:37 WIB
 * @link https://github.com/ommu/mod-report
 *
 * This is the model class for table "ommu_report_setting".
 *
 * The followings are the available columns in table "ommu_report_setting":
 * @property integer $id
 * @property string $license
 * @property integer $permission
 * @property string $meta_description
 * @property string $meta_keyword
 * @property integer $auto_report_cat_id
 * @property string $modified_date
 * @property integer $modified_id
 *
 * The followings are the available model relations:
 * @property ReportCategory $category
 * @property Users $modified
 *
 */

namespace ommu\report\models;

use Yii;
use ommu\users\models\Users;

class ReportSetting extends \app\components\ActiveRecord
{
	use \ommu\traits\UtilityTrait;

	public $gridForbiddenColumn = [];

	public $auto_report_i;
	public $modifiedDisplayname;

	/**
	 * @return string the associated database table name
	 */
	public static function tableName()
	{
		return 'ommu_report_setting';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		return [
			[['license', 'meta_description', 'meta_keyword'], 'required'],
			[['permission', 'auto_report_cat_id', 'modified_id', 'auto_report_i'], 'integer'],
			[['meta_description', 'meta_keyword'], 'string'],
			[['auto_report_cat_id', 'auto_report_i'], 'safe'],
			[['license'], 'string', 'max' => 32],
			[['auto_report_cat_id'], 'exist', 'skipOnError' => true, 'targetClass' => ReportCategory::className(), 'targetAttribute' => ['auto_report_cat_id' => 'cat_id']],
		];
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return [
			'id' => Yii::t('app', 'ID'),
			'license' => Yii::t('app', 'License'),
			'permission' => Yii::t('app', 'Permission'),
			'meta_description' => Yii::t('app', 'Meta Description'),
			'meta_keyword' => Yii::t('app', 'Meta Keyword'),
			'auto_report_cat_id' => Yii::t('app', 'Auto Report Category'),
			'modified_date' => Yii::t('app', 'Modified Date'),
			'modified_id' => Yii::t('app', 'Modified'),
			'auto_report_i' => Yii::t('app', 'Enable Auto Report'),
			'modifiedDisplayname' => Yii::t('app', 'Modified'),
		];
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getCategory()
	{
		return $this->hasOne(ReportCategory::className(), ['cat_id' => 'auto_report_cat_id']);
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
		$this->templateColumns['license'] = [
			'attribute' => 'license',
			'value' => function($model, $key, $index, $column) {
				return $model->license;
			},
		];
		$this->templateColumns['permission'] = [
			'attribute' => 'permission',
			'value' => function($model, $key, $index, $column) {
				return self::getPermission($model->permission);
			},
		];
		$this->templateColumns['meta_description'] = [
			'attribute' => 'meta_description',
			'value' => function($model, $key, $index, $column) {
				return $model->meta_description;
			},
		];
		$this->templateColumns['meta_keyword'] = [
			'attribute' => 'meta_keyword',
			'value' => function($model, $key, $index, $column) {
				return $model->meta_keyword;
			},
		];
		if(!Yii::$app->request->get('category')) {
			$this->templateColumns['auto_report_cat_id'] = [
				'attribute' => 'auto_report_cat_id',
				'value' => function($model, $key, $index, $column) {
					return isset($model->category) ? $model->category->title->message : '-';
				},
				'filter' => ReportCategory::getCategory(),
			];
		}
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
	}

	/**
	 * User get information
	 */
	public static function getInfo($column=null)
	{
		if($column != null) {
			$model = self::find()
				->select([$column])
				->where(['id' => 1])
				->one();
			return $model->$column;
			
		} else {
			$model = self::findOne(1);
			return $model;
		}
	}

	/**
	 * function getPermission
	 */
	public static function getPermission($value=null)
	{
		$items = array(
			1 => Yii::t('app', 'Yes, the public can view "module name" unless they are made private.'),
			0 => Yii::t('app', 'No, the public cannot view "module name".'),
		);

		if($value !== null)
			return $items[$value];
		else
			return $items;
	}

	/**
	 * after find attributes
	 */
	public function afterFind() 
	{
		if($this->auto_report_cat_id)
			$this->auto_report_i = 1;
		// $this->modifiedDisplayname = isset($this->modified) ? $this->modified->displayname : '-';
	}

	/**
	 * before validate attributes
	 */
	public function beforeValidate()
	{
		if(parent::beforeValidate()) {
			if(!$this->isNewRecord) {
				if($this->modified_id == null)
					$this->modified_id = !Yii::$app->user->isGuest ? Yii::$app->user->id : null;
			}

			if($this->auto_report_i && !$this->auto_report_cat_id)
				$this->addError('auto_report_cat_id', Yii::t('app', '{attribute} cannot be blank.', ['attribute'=>$this->getAttributeLabel('auto_report_cat_id')]));
		}
		return true;
	}

	/**
	 * before save attributes
	 */
	public function beforeSave($insert)
	{
		if(parent::beforeSave($insert)) {
			if(!$this->auto_report_i)
				$this->auto_report_cat_id = null;

		}
		return true;
	}
}
