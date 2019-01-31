<?php
/**
 * ReportCategory
 * 
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2017 OMMU (www.ommu.co)
 * @created date 19 September 2017, 22:56 WIB
 * @modified date 16 January 2019, 16:26 WIB
 * @link https://github.com/ommu/mod-report
 *
 * This is the model class for table "ommu_report_category".
 *
 * The followings are the available columns in table "ommu_report_category":
 * @property integer $cat_id
 * @property integer $publish
 * @property integer $name
 * @property integer $desc
 * @property string $creation_date
 * @property integer $creation_id
 * @property string $modified_date
 * @property integer $modified_id
 * @property string $updated_date
 * @property string $slug
 *
 * The followings are the available model relations:
 * @property Reports[] $reports
 * @property SourceMessage $title
 * @property SourceMessage $description
 * @property Users $creation
 * @property Users $modified
 *
 */

namespace ommu\report\models;

use Yii;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\behaviors\SluggableBehavior;
use app\models\SourceMessage;
use ommu\users\models\Users;
use ommu\report\models\view\ReportCategory as ReportCategoryView;

class ReportCategory extends \app\components\ActiveRecord
{
	use \ommu\traits\UtilityTrait;

	public $gridForbiddenColumn = ['desc_i','modified_date','modifiedDisplayname','updated_date','slug'];
	public $name_i;
	public $desc_i;

	// Search Variable
	public $creationDisplayname;
	public $modifiedDisplayname;

	/**
	 * @return string the associated database table name
	 */
	public static function tableName()
	{
		return 'ommu_report_category';
	}

	/**
	 * behaviors model class.
	 */
	public function behaviors() {
		return [
			[
				'class' => SluggableBehavior::className(),
				'attribute' => 'title.message',
				'immutable' => true,
				'ensureUnique' => true,
			],
		];
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		return [
			[['name_i', 'desc_i'], 'required'],
			[['publish', 'name', 'desc', 'creation_id', 'modified_id'], 'integer'],
			[['name_i', 'desc_i', 'slug'], 'string'],
			[['name_i'], 'string', 'max' => 64],
			[['desc_i'], 'string', 'max' => 128],
		];
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return [
			'cat_id' => Yii::t('app', 'Category'),
			'publish' => Yii::t('app', 'Publish'),
			'name' => Yii::t('app', 'Category'),
			'desc' => Yii::t('app', 'Description'),
			'creation_date' => Yii::t('app', 'Creation Date'),
			'creation_id' => Yii::t('app', 'Creation'),
			'modified_date' => Yii::t('app', 'Modified Date'),
			'modified_id' => Yii::t('app', 'Modified'),
			'updated_date' => Yii::t('app', 'Updated Date'),
			'slug' => Yii::t('app', 'Slug'),
			'name_i' => Yii::t('app', 'Category'),
			'desc_i' => Yii::t('app', 'Description'),
			'reports' => Yii::t('app', 'Reports'),
			'resolved' => Yii::t('app', 'Resolved'),
			'unresolved' => Yii::t('app', 'Unresolved'),
			'creationDisplayname' => Yii::t('app', 'Creation'),
			'modifiedDisplayname' => Yii::t('app', 'Modified'),
		];
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getReports($count=true, $status=null)
	{
		if($count == true) {
			$model = Reports::find()
				->where(['cat_id' => $this->cat_id]);
			if($status == 0)
				$model->unresolved();
			elseif($status == 1)
				$model->resolved();
			return $model->count();
		}

		return $this->hasMany(Reports::className(), ['cat_id' => 'cat_id'])
			->andOnCondition([sprintf('%s.status', Reports::tableName()) => $status]);
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getResolved($count=true)
	{
		return $this->getReports($count, 1);
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getUnresolved($count=true)
	{
		return $this->getReports($count, 0);
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getTitle()
	{
		return $this->hasOne(SourceMessage::className(), ['id' => 'name']);
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getDescription()
	{
		return $this->hasOne(SourceMessage::className(), ['id' => 'desc']);
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getCreation()
	{
		return $this->hasOne(Users::className(), ['user_id' => 'creation_id']);
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
		return $this->hasOne(ReportCategoryView::className(), ['cat_id' => 'cat_id']);
	}

	/**
	 * {@inheritdoc}
	 * @return \ommu\report\models\query\ReportCategory the active query used by this AR class.
	 */
	public static function find()
	{
		return new \ommu\report\models\query\ReportCategory(get_called_class());
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
		$this->templateColumns['name_i'] = [
			'attribute' => 'name_i',
			'value' => function($model, $key, $index, $column) {
				return $model->name_i;
			},
		];
		$this->templateColumns['desc_i'] = [
			'attribute' => 'desc_i',
			'value' => function($model, $key, $index, $column) {
				return $model->desc_i;
			},
		];
		$this->templateColumns['creation_date'] = [
			'attribute' => 'creation_date',
			'value' => function($model, $key, $index, $column) {
				return Yii::$app->formatter->asDatetime($model->creation_date, 'medium');
			},
			'filter' => $this->filterDatepicker($this, 'creation_date'),
		];
		if(!Yii::$app->request->get('creation')) {
			$this->templateColumns['creationDisplayname'] = [
				'attribute' => 'creationDisplayname',
				'value' => function($model, $key, $index, $column) {
					return isset($model->creation) ? $model->creation->displayname : '-';
				},
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
		$this->templateColumns['slug'] = [
			'attribute' => 'slug',
			'value' => function($model, $key, $index, $column) {
				return $model->slug;
			},
		];
		$this->templateColumns['unresolved'] = [
			'attribute' => 'unresolved',
			'filter' => false,
			'value' => function($model, $key, $index, $column) {
				return Html::a($model->unresolved, ['admin/manage', 'category'=>$model->primaryKey, 'status' => 0], ['title'=>Yii::t('app', '{count} unresolved', ['count'=>$model->unresolved])]);
			},
			'contentOptions' => ['class'=>'center'],
			'format' => 'html',
		];
		$this->templateColumns['resolved'] = [
			'attribute' => 'resolved',
			'filter' => false,
			'value' => function($model, $key, $index, $column) {
				return Html::a($model->resolved, ['admin/manage', 'category'=>$model->primaryKey, 'status' => 1], ['title'=>Yii::t('app', '{count} resolved', ['count'=>$model->resolved])]);
			},
			'contentOptions' => ['class'=>'center'],
			'format' => 'html',
		];
		$this->templateColumns['reports'] = [
			'attribute' => 'reports',
			'filter' => false,
			'value' => function($model, $key, $index, $column) {
				return Html::a($model->reports, ['admin/manage', 'category'=>$model->primaryKey], ['title'=>Yii::t('app', '{count} reports', ['count'=>$model->reports])]);
			},
			'contentOptions' => ['class'=>'center'],
			'format' => 'html',
		];
		if(!Yii::$app->request->get('trash')) {
			$this->templateColumns['publish'] = [
				'attribute' => 'publish',
				'filter' => $this->filterYesNo(),
				'value' => function($model, $key, $index, $column) {
					$url = Url::to(['category/publish', 'id'=>$model->primaryKey]);
					return $this->quickAction($url, $model->publish, 'Enable,Disable');
				},
				'contentOptions' => ['class'=>'center'],
				'format' => 'raw',
			];
		}
	}

	/**
	 * User get information
	 */
	public static function getInfo($id, $column=null)
	{
		if($column != null) {
			$model = self::find()
				->select([$column])
				->where(['cat_id' => $id])
				->one();
			return $model->$column;
			
		} else {
			$model = self::findOne($id);
			return $model;
		}
	}

	/**
	 * function getCategory
	 */
	public static function getCategory($publish=null, $array=true) 
	{
		$model = self::find()->alias('t');
		$model->leftJoin(sprintf('%s title', SourceMessage::tableName()), 't.name=title.id');
		if($publish != null)
			$model->andWhere(['t.publish' => $publish]);

		$model = $model->orderBy('title.message ASC')->all();

		if($array == true)
			return \yii\helpers\ArrayHelper::map($model, 'cat_id', 'name_i');

		return $model;
	}

	/**
	 * after find attributes
	 */
	public function afterFind()
	{
		parent::afterFind();

		$this->name_i = isset($this->title) ? $this->title->message : '';
		$this->desc_i = isset($this->description) ? $this->description->message : '';
	}

	/**
	 * before validate attributes
	 */
	public function beforeValidate()
	{
		if(parent::beforeValidate()) {
			if($this->isNewRecord) {
				if($this->creation_id == null)
					$this->creation_id = !Yii::$app->user->isGuest ? Yii::$app->user->id : null;
			} else {
				if($this->modified_id == null)
					$this->modified_id = !Yii::$app->user->isGuest ? Yii::$app->user->id : null;
			}
		}
		return true;
	}

	/**
	 * before save attributes
	 */
	public function beforeSave($insert)
	{
		$module = strtolower(Yii::$app->controller->module->id);
		$controller = strtolower(Yii::$app->controller->id);
		$action = strtolower(Yii::$app->controller->action->id);

		$location = $this->urlTitle($module.' '.$controller);

		if(parent::beforeSave($insert)) {
			if($insert || (!$insert && !$this->name)) {
				$name = new SourceMessage();
				$name->location = $location.'_title';
				$name->message = $this->name_i;
				if($name->save())
					$this->name = $name->id;

				$this->slug = $this->urlTitle($this->name_i);

			} else {
				$name = SourceMessage::findOne($this->name);
				$name->message = $this->name_i;
				$name->save();
			}

			if($insert || (!$insert && !$this->desc)) {
				$desc = new SourceMessage();
				$desc->location = $location.'_description';
				$desc->message = $this->desc_i;
				if($desc->save())
					$this->desc = $desc->id;

			} else {
				$desc = SourceMessage::findOne($this->desc);
				$desc->message = $this->desc_i;
				$desc->save();
			}

		}
		return true;
	}
}
