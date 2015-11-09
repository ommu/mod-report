<?php

/**
 * @author Putra Sudaryanto <putra.sudaryanto@gmail.com>
 * @copyright Copyright (c) 2014 Ommu Platform (ommu.co)
 * @link http://company.ommu.co
 * @contact (+62)856-299-4114
 *
 * This is the template for generating the model class of a specified table.
 * - $this: the ModelCode object
 * - $tableName: the table name for this class (prefix is already removed if necessary)
 * - $modelClass: the model class name
 * - $columns: list of table columns (name=>CDbColumnSchema)
 * - $labels: list of attribute labels (name=>label)
 * - $rules: list of validation rules
 * - $relations: list of relations (name=>relation declaration)
 *
 * --------------------------------------------------------------------------------------
 *
 * This is the model class for table "ommu_report_category".
 *
 * The followings are the available columns in table 'ommu_report_category':
 * @property integer $cat_id
 * @property integer $publish
 * @property integer $dependency
 * @property integer $orders
 * @property string $name
 * @property string $desc
 *
 * The followings are the available model relations:
 * @property OmmuReports[] $ommuReports
 */
class ReportCategory extends CActiveRecord
{
	public $defaultColumns = array();
	public $title;
	public $description;

	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return ReportCategory the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'ommu_report_category';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('
				title, description', 'required'),
			array('publish, dependency, orders', 'numerical', 'integerOnly'=>true),
			array('name, desc', 'length', 'max'=>11),
			array('
				title', 'length', 'max'=>32),
			array('
				description', 'length', 'max'=>128),
			array('dependency', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('cat_id, publish, dependency, orders, name, desc', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			//'ommuReports' => array(self::HAS_MANY, 'OmmuReports', 'cat_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'cat_id' => Phrase::trans(12018,1),
			'publish' => Phrase::trans(12021,1),
			'dependency' => Phrase::trans(12019,1),
			'orders' => 'Order',
			'name' => Phrase::trans(12016,1),
			'desc' => Phrase::trans(12017,1),
			'title' => Phrase::trans(12016,1),
			'description' => Phrase::trans(12017,1),
		);
	}
	
	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	public function search()
	{
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('cat_id',$this->cat_id);
		if(isset($_GET['type']) && $_GET['type'] == 'publish') {
			$criteria->compare('t.publish',1);
		} elseif(isset($_GET['type']) && $_GET['type'] == 'unpublish') {
			$criteria->compare('t.publish',0);
		} elseif(isset($_GET['type']) && $_GET['type'] == 'trash') {
			$criteria->compare('t.publish',2);
		} else {
			$criteria->addInCondition('t.publish',array(0,1));
			$criteria->compare('t.publish',$this->publish);
		}
		$criteria->compare('dependency',$this->dependency);
		$criteria->compare('orders',$this->orders);
		$criteria->compare('name',$this->name);
		$criteria->compare('desc',$this->desc);
		if(!isset($_GET['ReportCategory_sort']))
			$criteria->order = 'cat_id DESC';

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}


	/**
	 * Get column for CGrid View
	 */
	public function getGridColumn($columns=null) {
		if($columns !== null) {
			foreach($columns as $val) {
				/*
				if(trim($val) == 'enabled') {
					$this->defaultColumns[] = array(
						'name'  => 'enabled',
						'value' => '$data->enabled == 1? "Ya": "Tidak"',
					);
				}
				*/
				$this->defaultColumns[] = $val;
			}
		}else {
			//$this->defaultColumns[] = 'cat_id';
			$this->defaultColumns[] = 'publish';
			$this->defaultColumns[] = 'dependency';
			$this->defaultColumns[] = 'orders';
			$this->defaultColumns[] = 'name';
			$this->defaultColumns[] = 'desc';
		}

		return $this->defaultColumns;
	}

	/**
	 * Set default columns to display
	 */
	protected function afterConstruct() {
		if(count($this->defaultColumns) == 0) {
			/*
			$this->defaultColumns[] = array(
				'class' => 'CCheckBoxColumn',
				'name' => 'id',
				'selectableRows' => 2,
				'checkBoxHtmlOptions' => array('name' => 'trash_id[]')
			);
			*/
			$this->defaultColumns[] = array(
				'header' => 'No',
				'value' => '$this->grid->dataProvider->pagination->currentPage*$this->grid->dataProvider->pagination->pageSize + $row+1'
			);
			$this->defaultColumns[] = array(
				'name' => 'name',
				'value' => 'Phrase::trans($data->name, 2)',
			);
			$this->defaultColumns[] = array(
				'name' => 'desc',
				'value' => 'Phrase::trans($data->desc, 2)',
			);
			$this->defaultColumns[] = array(
				'name' => 'dependency',
				'value' => 'Phrase::trans(ReportCategory::model()->findByPk($data->dependency)->name, 2)',
			);
			if(!isset($_GET['type'])) {
				$this->defaultColumns[] = array(
					'name' => 'publish',
					'value' => 'Utility::getPublish(Yii::app()->controller->createUrl("publish",array("id"=>$data->cat_id)), $data->publish, 1)',
					'htmlOptions' => array(
						'class' => 'center',
					),
					'type' => 'raw',
				);
			}
		}
		parent::afterConstruct();
	}

	/**
	 * Get category
	 * 0 = unpublish
	 * 1 = publish
	 */
	public static function getCategory($publish=null) {
		if($publish == null) {
			$model = self::model()->findAll();
		} else {
			$model = self::model()->findAll(array(
				//'select' => 'publish, name',
				'condition' => 'publish = :publish',
				'params' => array(
					':publish' => $publish,
				),
				//'order' => 'cat_id ASC'
			));
		}

		$items = array();
		if($model != null) {
			foreach($model as $key => $val) {
				$items[$val->cat_id] = Phrase::trans($val->name, 2);
			}
			return $items;
		} else {
			return false;
		}
	}

	/**
	 * before validate attributes
	 */
	protected function beforeValidate() {
		if(parent::beforeValidate()) {		
			if($this->isNewRecord) {
				$model = self::model()->findAll(array(
					//'select' => 'dependency',
					'condition' => 'dependency = :id',
					'params' => array(
						':id' => $this->dependency,
					)
				));
				$this->orders = count($model) + 1;
			}
		}
		return true;
	}
	
	/**
	 * before save attributes
	 */
	protected function beforeSave() {
		if(parent::beforeSave()) {
			$currentAction = strtolower(Yii::app()->controller->module->id.'/'.Yii::app()->controller->id);
			if($this->isNewRecord) {
				$title=new OmmuSystemPhrase;
				$title->location = $currentAction;
				$title->en = $this->title;
				if($title->save()) {
					$this->name = $title->phrase_id;
				}

				$desc=new OmmuSystemPhrase;
				$desc->location = $currentAction;
				$desc->en = $this->description;
				if($desc->save()) {
					$this->desc = $desc->phrase_id;
				}
			} else {
				$title = OmmuSystemPhrase::model()->findByPk($this->name);
				$title->en = $this->title;
				$title->save();

				$desc = OmmuSystemPhrase::model()->findByPk($this->desc);
				$desc->en = $this->description;
				$desc->save();
			}
		}
		return true;
	}
}