<?php
/**
 * Reports
 * version: 0.0.1
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @copyright Copyright (c) 2014 Ommu Platform (opensource.ommu.co)
 * @link https://github.com/ommu/Report
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
 * @property integer $status
 * @property integer $cat_id
 * @property string $user_id
 * @property string $url
 * @property string $report_body
 * @property string $report_date
 * @property string $report_ip
 * @property string $report_message
 * @property string $resolved_date
 * @property integer $resolved_id
 * @property string $unresolved_date
 * @property integer $unresolved_id
 * @property string $modified_date
 * @property string $modified_id
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
	public $modified_search;	

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
			array('cat_id, url, report_body', 'required'),
			array('status, cat_id, user_id, resolved_id, unresolved_id, modified_id', 'numerical', 'integerOnly'=>true),
			array('cat_id', 'length', 'max'=>5),
			array('user_id, resolved_id, unresolved_id, modified_id', 'length', 'max'=>11),
			array('report_ip', 'length', 'max'=>20),
			array('report_ip, report_message', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('report_id, status, cat_id, user_id, url, report_body, report_date, report_ip, report_message, resolved_date, resolved_id, unresolved_date, unresolved_id, modified_date, modified_id,
				user_search, resolved_search, unresolved_search, modified_search', 'safe', 'on'=>'search'),
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
			'view' => array(self::BELONGS_TO, 'ViewReports', 'report_id'),
			'cat' => array(self::BELONGS_TO, 'ReportCategory', 'cat_id'),
			'user' => array(self::BELONGS_TO, 'Users', 'user_id'),
			'resolved' => array(self::BELONGS_TO, 'Users', 'resolved_id'),
			'unresolved' => array(self::BELONGS_TO, 'Users', 'unresolved_id'),
			'modified' => array(self::BELONGS_TO, 'Users', 'modified_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'report_id' => Yii::t('attribute', 'Report'),
			'cat_id' => Yii::t('attribute', 'Category'),
			'user_id' => Yii::t('attribute', 'User'),
			'status' => Yii::t('attribute', 'Status'),
			'url' => Yii::t('attribute', 'URL'),
			'report_body' => Yii::t('attribute', 'Report Trouble'),
			'report_date' => Yii::t('attribute', 'Report Date'),
			'report_ip' => Yii::t('attribute', 'Report IP'),
			'report_message' => Yii::t('attribute', 'Report Message'),
			'resolved_date' => Yii::t('attribute', 'Resolved Date'),
			'resolved_id' => Yii::t('attribute', 'Resolved'),
			'modified_date' => Yii::t('attribute', 'Modified'),
			'modified_id' => Yii::t('attribute', 'Modified'),
			'user_search' => Yii::t('attribute', 'User'),
			'unresolved_date' => Yii::t('attribute', 'Unresolved Date'),
			'unresolved_id' => Yii::t('attribute', 'Unresolved'),
			'modified_search' => Yii::t('attribute', 'Modified'),
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
		
		// Custom Search
		$criteria->with = array(
			'user' => array(
				'alias'=>'user',
				'select'=>'displayname'
			),
			'resolved' => array(
				'alias'=>'resolved',
				'select'=>'displayname',
			),
			'unresolved' => array(
				'alias'=>'unresolved',
				'select'=>'displayname',
			),
			'modified' => array(
				'alias'=>'modified',
				'select'=>'displayname',
			),
		);

		$criteria->compare('t.report_id',$this->report_id);
		if(isset($_GET['category']))
			$criteria->compare('t.cat_id',$_GET['category']);
		else
			$criteria->compare('t.cat_id',$this->cat_id);
		$criteria->compare('t.user_id',$this->user_id);
		if(isset($_GET['status']))
			$criteria->compare('t.status',$_GET['status']);
		else
			$criteria->compare('t.status',$this->status);
		$criteria->compare('t.url',$this->url,true);
		$criteria->compare('t.report_body',$this->report_body,true);
		if($this->report_date != null && !in_array($this->report_date, array('0000-00-00 00:00:00', '0000-00-00')))
			$criteria->compare('date(t.report_date)',date('Y-m-d', strtotime($this->report_date)));
		$criteria->compare('t.report_ip',$this->report_ip,true);
		$criteria->compare('t.report_message',$this->report_message,true);
		if($this->resolved_date != null && !in_array($this->resolved_date, array('0000-00-00 00:00:00', '0000-00-00')))
			$criteria->compare('date(t.resolved_date)',date('Y-m-d', strtotime($this->resolved_date)));
		$criteria->compare('t.resolved_id',$this->resolved_id);
		if($this->unresolved_date != null && !in_array($this->unresolved_date, array('0000-00-00 00:00:00', '0000-00-00')))
			$criteria->compare('date(t.unresolved_date)',date('Y-m-d', strtotime($this->unresolved_date)));
		$criteria->compare('t.unresolved_id',$this->unresolved_id);
		if($this->modified_date != null && !in_array($this->modified_date, array('0000-00-00 00:00:00', '0000-00-00')))
			$criteria->compare('date(t.modified_date)',date('Y-m-d', strtotime($this->modified_date)));
		$criteria->compare('t.modified_id',$this->modified_id);
		
		$criteria->compare('user.displayname',strtolower($this->user_search), true);
		$criteria->compare('resolved.displayname',strtolower($this->resolved_search), true);
		$criteria->compare('unresolved.displayname',strtolower($this->unresolved_search), true);
		$criteria->compare('modified.displayname',strtolower($this->modified_search), true);
		
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
			$this->defaultColumns[] = 'report_body';
			$this->defaultColumns[] = 'report_date';
			$this->defaultColumns[] = 'report_ip';
			$this->defaultColumns[] = 'report_message';
			$this->defaultColumns[] = 'resolved_date';
			$this->defaultColumns[] = 'resolved_id';
			$this->defaultColumns[] = 'unresolved_date';
			$this->defaultColumns[] = 'unresolved_id';
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
					'value' => 'Phrase::trans($data->cat->name)',
					'filter'=> ReportCategory::getCategory(),
					'type' => 'raw',
				);
			}
			//$this->defaultColumns[] = 'url';
			$this->defaultColumns[] = 'report_body';
			$this->defaultColumns[] = array(
				'header' => Yii::t('attribute', 'Reports'),
				'value' => 'CHtml::link($data->view->reports ? $data->view->reports : 0, Yii::app()->controller->createUrl("o/history/manage",array(\'report\'=>$data->report_id,\'status\'=>0)))',				
				'htmlOptions' => array(
					'class' => 'center',
				),
				'type' => 'raw',
			);
			$this->defaultColumns[] = array(
				'header' => Yii::t('attribute', 'Comments'),
				'value' => 'CHtml::link($data->view->comments ? $data->view->comments : 0, Yii::app()->controller->createUrl("o/comment/manage",array(\'report\'=>$data->report_id,\'publish\'=>1)))',		
				'htmlOptions' => array(
					'class' => 'center',
				),
				'type' => 'raw',
			);
			$this->defaultColumns[] = array(
				'header' => Yii::t('attribute', 'Users'),
				'value' => 'CHtml::link($data->view->users ? $data->view->users : 0, Yii::app()->controller->createUrl("o/user/manage",array(\'report\'=>$data->report_id,\'publish\'=>1)))',		
				'htmlOptions' => array(
					'class' => 'center',
				),
				'type' => 'raw',
			);
			$this->defaultColumns[] = array(
				'name' => 'user_search',
				'value' => '$data->user->displayname',
			);
			$this->defaultColumns[] = array(
				'name' => 'report_date',
				'value' => 'Utility::dateFormat($data->report_date)',
				'htmlOptions' => array(
					'class' => 'center',
				),
				'filter' => Yii::app()->controller->widget('application.components.system.CJuiDatePicker', array(
					'model'=>$this, 
					'attribute'=>'report_date', 
					'language' => 'en',
					'i18nScriptFile' => 'jquery-ui-i18n.min.js',
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
			if($this->isNewRecord)
				$this->user_id = !Yii::app()->user->isGuest ? Yii::app()->user->id : 0;
			
			else {
				$this->resolved_id = Yii::app()->user->id;
				$this->unresolved_id = Yii::app()->user->id;
				$this->modified_id = Yii::app()->user->id;
			}
			$this->report_ip = $_SERVER['REMOTE_ADDR'];
		}
		return true;
	}

}