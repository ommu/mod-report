<?php
/**
 * Reports
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @copyright Copyright (c) 2014 Ommu Platform (ommu.co)
 * @link https://github.com/oMMu/Ommu-Report
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
 * This is the model class for table "ommu_reports".
 *
 * The followings are the available columns in table 'ommu_reports':
 * @property string $report_id
 * @property integer $cat_id
 * @property string $user_id
 * @property integer $status
 * @property string $url
 * @property string $body
 * @property string $report_date
 * @property string $report_ip
 *
 * The followings are the available model relations:
 * @property OmmuReportCategory $cat
 */
class Reports extends CActiveRecord
{
	public $defaultColumns = array();
	public $old_status;
	
	// Variable Search
	public $user_search;
	public $resolved_search;
	public $unresolved_search;

	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Reports the static model class
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
		return 'ommu_reports';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('cat_id, url, body', 'required'),
			array('cat_id, status', 'numerical', 'integerOnly'=>true),
			array('user_id', 'length', 'max'=>11),
			array('report_ip', 'length', 'max'=>20),
			array('url', 'length', 'max'=>255),
			array('report_date, report_ip', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('report_id, cat_id, user_id, status, url, body, report_date, report_ip, resolved_date, resolved_id, unresolved_date, unresolved_id,
				user_search, resolved_search, unresolved_search', 'safe', 'on'=>'search'),
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
			'cat' => array(self::BELONGS_TO, 'ReportCategory', 'cat_id'),
			'user' => array(self::BELONGS_TO, 'Users', 'user_id'),
			'resolved_relation' => array(self::BELONGS_TO, 'Users', 'resolved_id'),
			'unresolved_relation' => array(self::BELONGS_TO, 'Users', 'unresolved_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'report_id' => Yii::t('attribute', 'Report'),
			'cat_id' => Yii::t('attribute', 'Cat'),
			'user_id' => Yii::t('attribute', 'User'),
			'status' => Yii::t('attribute', 'Status'),
			'url' => Yii::t('attribute', 'Url'),
			'body' => Yii::t('attribute', 'Body'),
			'report_date' => Yii::t('attribute', 'Report Date'),
			'report_ip' => Yii::t('attribute', 'Report Ip'),
			'resolved_date' => Yii::t('attribute', 'Resolved Date'),
			'resolved_id' => Yii::t('attribute', 'Resolved'),
			'user_search' => Yii::t('attribute', 'User'),
			'unresolved_date' => Yii::t('attribute', 'Unresolved Date'),
			'unresolved_id' => Yii::t('attribute', 'Unresolved'),
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

		$criteria->compare('t.report_id',$this->report_id);
		if(isset($_GET['category']))
			$criteria->compare('t.cat_id',$_GET['category']);
		else
			$criteria->compare('t.cat_id',$this->cat_id);
		$criteria->compare('t.user_id',$this->user_id);
		$criteria->compare('t.status',$this->status);
		$criteria->compare('t.url',$this->url,true);
		$criteria->compare('t.body',$this->body,true);
		if($this->report_date != null && !in_array($this->report_date, array('0000-00-00 00:00:00', '0000-00-00')))
			$criteria->compare('date(t.report_date)',date('Y-m-d', strtotime($this->report_date)));
		$criteria->compare('t.report_ip',$this->report_ip,true);
		if($this->resolved_date != null && !in_array($this->resolved_date, array('0000-00-00 00:00:00', '0000-00-00')))
			$criteria->compare('date(t.resolved_date)',date('Y-m-d', strtotime($this->resolved_date)));
		$criteria->compare('t.resolved_id',$this->resolved_id);
		if($this->unresolved_date != null && !in_array($this->unresolved_date, array('0000-00-00 00:00:00', '0000-00-00')))
			$criteria->compare('date(t.unresolved_date)',date('Y-m-d', strtotime($this->unresolved_date)));
		$criteria->compare('t.unresolved_id',$this->unresolved_id);
		
		// Custom Search
		$criteria->with = array(
			'user' => array(
				'alias'=>'user',
				'select'=>'displayname'
			),
			'resolved_relation' => array(
				'alias'=>'resolved_relation',
				'select'=>'displayname',
			),
			'unresolved_relation' => array(
				'alias'=>'unresolved_relation',
				'select'=>'displayname',
			),
		);
		$criteria->compare('user.displayname',strtolower($this->user_search), true);
		$criteria->compare('resolved_relation.displayname',strtolower($this->resolved_search), true);
		$criteria->compare('unresolved_relation.displayname',strtolower($this->unresolved_search), true);
		
		if(!isset($_GET['Reports_sort']))
			$criteria->order = 't.report_id DESC';

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
			'pagination'=>array(
				'pageSize'=>30,
			),
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
			//$this->defaultColumns[] = 'report_id';
			$this->defaultColumns[] = 'cat_id';
			$this->defaultColumns[] = 'user_id';
			$this->defaultColumns[] = 'status';
			$this->defaultColumns[] = 'url';
			$this->defaultColumns[] = 'body';
			$this->defaultColumns[] = 'report_date';
			$this->defaultColumns[] = 'report_ip';
		}

		return $this->defaultColumns;
	}

	/**
	 * Set default columns to display
	 */
	protected function afterConstruct() {
		if(count($this->defaultColumns) == 0) {
			$this->defaultColumns[] = array(
				'header' => 'No',
				'value' => '$this->grid->dataProvider->pagination->currentPage*$this->grid->dataProvider->pagination->pageSize + $row+1'
			);
			if(!isset($_GET['category'])) {
				$this->defaultColumns[] = array(
					'name' => 'cat_id',
					'value' => 'Phrase::trans($data->cat->name, 2)',
					'filter'=> ReportCategory::getCategory(),
					'type' => 'raw',
				);
			}
			$this->defaultColumns[] = array(
				'name' => 'user_search',
				'value' => '$data->user->displayname',
			);
			$this->defaultColumns[] = 'url';
			$this->defaultColumns[] = 'body';
			$this->defaultColumns[] = array(
				'name' => 'report_date',
				'value' => 'Utility::dateFormat($data->report_date)',
				'htmlOptions' => array(
					'class' => 'center',
				),
				'filter' => Yii::app()->controller->widget('zii.widgets.jui.CJuiDatePicker', array(
					'model'=>$this, 
					'attribute'=>'report_date', 
					'language' => 'ja',
					'i18nScriptFile' => 'jquery.ui.datepicker-en.js',
					//'mode'=>'datetime',
					'htmlOptions' => array(
						'id' => 'report_date_filter',
					),
					'options'=>array(
						'showOn' => 'focus',
						'dateFormat' => 'dd-mm-yy',
						'showOtherMonths' => true,
						'selectOtherMonths' => true,
						'changeMonth' => true,
						'changeYear' => true,
						'showButtonPanel' => true,
					),
				), true),
			);
			$this->defaultColumns[] = array(
				'name' => 'status',
				'value' => 'Utility::getPublish(Yii::app()->controller->createUrl("resolve",array("id"=>$data->report_id)), $data->status, 5)',
				'htmlOptions' => array(
					'class' => 'center',
				),
				'type' => 'raw',
			);
		}
		parent::afterConstruct();
	}

	/**
	 * before validate attributes
	 */
	protected function beforeValidate() {
		if(parent::beforeValidate()) {
			if($this->isNewRecord) {
				$this->report_ip = $_SERVER['REMOTE_ADDR'];
				if(!Yii::app()->user->isGuest)
					$this->user_id = Yii::app()->user->id;
			} else {
				$this->resolved_id = Yii::app()->user->id;
				$this->unresolved_id = Yii::app()->user->id;
			}
		}
		return true;
	}

}