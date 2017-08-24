<?php
/**
 * Reports
 * version: 0.0.1
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @copyright Copyright (c) 2014 Ommu Platform (opensource.ommu.co)
 * @link https://github.com/ommu/mod-report
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
 * @property string $report_url
 * @property string $report_body
 * @property string $report_message
 * @property string $reports
 * @property string $report_date
 * @property string $report_ip
 * @property string $modified_date
 * @property string $modified_id
 * @property string $updated_date
 *
 * The followings are the available model relations:
 * @property ReportCategory $cat
 */
class Reports extends CActiveRecord
{
	public $defaultColumns = array();
	public $old_status;
	
	// Variable Search
	public $reporter_search;
	public $modified_search;
	public $status_search;
	public $comment_search;
	public $user_search;

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
			array('cat_id, report_url, report_body', 'required'),
			array('report_message', 'required', 'on'=>'resolveForm'),
			array('status, cat_id, user_id, modified_id', 'numerical', 'integerOnly'=>true),
			array('cat_id', 'length', 'max'=>5),
			array('user_id, modified_id', 'length', 'max'=>11),
			array('report_ip', 'length', 'max'=>20),
			array('report_ip, report_message, reports', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('report_id, status, cat_id, user_id, report_url, report_body, report_message, reports, report_date, report_ip, modified_date, modified_id, updated_date,
				reporter_search, modified_search, status_search, comment_search, user_search', 'safe', 'on'=>'search'),
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
			'status' => Yii::t('attribute', 'Status'),
			'cat_id' => Yii::t('attribute', 'Category'),
			'user_id' => Yii::t('attribute', 'User'),
			'report_url' => Yii::t('attribute', 'Report URL'),
			'report_body' => Yii::t('attribute', 'Report Trouble'),
			'report_message' => Yii::t('attribute', 'Report Message'),
			'reports' => Yii::t('attribute', 'Reports'),
			'report_date' => Yii::t('attribute', 'Report Date'),
			'report_ip' => Yii::t('attribute', 'Report IP'),
			'modified_date' => Yii::t('attribute', 'Modified Date'),
			'modified_id' => Yii::t('attribute', 'Modified'),
			'updated_date' => Yii::t('attribute', 'Updated Date'),
			'reporter_search' => Yii::t('attribute', 'Reporter'),
			'modified_search' => Yii::t('attribute', 'Modified'),
			'status_search' => Yii::t('attribute', 'Status'),
			'comment_search' => Yii::t('attribute', 'Comments'),
			'user_search' => Yii::t('attribute', 'Users'),
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
			'view' => array(
				'alias'=>'view',
			),
			'user' => array(
				'alias'=>'user',
				'select'=>'displayname'
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
		$criteria->compare('t.report_url',$this->report_url,true);
		$criteria->compare('t.report_body',$this->report_body,true);
		$criteria->compare('t.report_message',$this->report_message,true);
		$criteria->compare('t.reports',$this->reports);
		if($this->report_date != null && !in_array($this->report_date, array('0000-00-00 00:00:00', '0000-00-00')))
			$criteria->compare('date(t.report_date)',date('Y-m-d', strtotime($this->report_date)));
		$criteria->compare('t.report_ip',$this->report_ip,true);
		if($this->modified_date != null && !in_array($this->modified_date, array('0000-00-00 00:00:00', '0000-00-00')))
			$criteria->compare('date(t.modified_date)',date('Y-m-d', strtotime($this->modified_date)));
		if(isset($_GET['modified']))
			$criteria->compare('t.modified_id',$_GET['modified']);
		else
			$criteria->compare('t.modified_id',$this->modified_id);
		if($this->updated_date != null && !in_array($this->updated_date, array('0000-00-00 00:00:00', '0000-00-00')))
			$criteria->compare('date(t.updated_date)',date('Y-m-d', strtotime($this->updated_date)));
		
		$criteria->compare('user.displayname',strtolower($this->reporter_search), true);
		$criteria->compare('modified.displayname',strtolower($this->modified_search), true);
		$criteria->compare('view.history_all',$this->status_search);
		$criteria->compare('view.comments',$this->comment_search);
		$criteria->compare('view.users',$this->user_search);
		
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
			$this->defaultColumns[] = 'report_url';
			$this->defaultColumns[] = 'report_body';
			$this->defaultColumns[] = 'report_message';
			$this->defaultColumns[] = 'reports';
			$this->defaultColumns[] = 'report_date';
			$this->defaultColumns[] = 'report_ip';
			$this->defaultColumns[] = 'modified_date';
			$this->defaultColumns[] = 'modified_id';
			$this->defaultColumns[] = 'updated_date';
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
			//$this->defaultColumns[] = 'report_url';
			$this->defaultColumns[] = 'report_body';
			/*
			$this->defaultColumns[] = array(
				'name' => 'reporter_search',
				'value' => '$data->user->displayname',
			);
			*/
			$this->defaultColumns[] = array(
				'name' => 'report_date',
				'value' => 'Utility::dateFormat($data->report_date, true)',
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
				'name' => 'reports',
				'value' => 'CHtml::link($data->reports ? $data->reports : 0, Yii::app()->controller->createurl("o/history/manage",array(\'report\'=>$data->report_id,\'status\'=>0)))',
				'htmlOptions' => array(
					'class' => 'center',
				),
				'type' => 'raw',
			);
			$this->defaultColumns[] = array(
				'name' => 'status_search',
				'value' => 'CHtml::link($data->view->history_all ? $data->view->history_all : 0, Yii::app()->controller->createurl("o/status/manage",array(\'report\'=>$data->report_id)))',
				'htmlOptions' => array(
					'class' => 'center',
				),
				'type' => 'raw',
			);
			$this->defaultColumns[] = array(
				'name' => 'comment_search',
				'value' => 'CHtml::link($data->view->comments ? $data->view->comments : 0, Yii::app()->controller->createurl("o/comment/manage",array(\'report\'=>$data->report_id,\'type\'=>\'publish\')))',		
				'htmlOptions' => array(
					'class' => 'center',
				),
				'type' => 'raw',
			);
			$this->defaultColumns[] = array(
				'name' => 'user_search',
				'value' => 'CHtml::link($data->view->users ? $data->view->users : 0, Yii::app()->controller->createurl("o/user/manage",array(\'report\'=>$data->report_id,\'type\'=>\'publish\')))',		
				'htmlOptions' => array(
					'class' => 'center',
				),
				'type' => 'raw',
			);
			$this->defaultColumns[] = array(
				'name' => 'status',
				'value' => 'Utility::getPublish(Yii::app()->controller->createurl("resolve",array("id"=>$data->report_id)), $data->status, 5)',
				'htmlOptions' => array(
					'class' => 'center',
				),
				'type' => 'raw',
			);
		}
		parent::afterConstruct();
	}

	/**
	 * User get information
	 */
	public static function insertReport($report_url, $report_body)
	{
		$user_id = !Yii::app()->user->isGuest ? Yii::app()->user->id : 0;

		$setting = ReportSetting::model()->findByPk(1, array(
			'select' => 'auto_report_cat_id',
		));
		$criteria=new CDbCriteria;
		$criteria->select = 'report_id, cat_id, report_url, reports';
		if($setting != null)
			$criteria->compare('cat_id', $setting->auto_report_cat_id);
		$criteria->compare('report_url', $report_url);
		$findReport = self::model()->find($criteria);
		
		if($findReport != null)
			self::model()->updateByPk($findReport->report_id, array('user_id'=>$user_id, 'reports'=>$findReport->reports + 1, 'report_ip'=>$_SERVER['REMOTE_ADDR']));
		
		else {
			$report=new Reports;
			$report->cat_id = $setting != null ? $setting->auto_report_cat_id : '1';
			$report->report_url = $report_url;
			$report->report_body = $report_body;
			$report->save();
		}
	}

	/**
	 * before validate attributes
	 */
	protected function beforeValidate() {
		if(parent::beforeValidate()) {
			if($this->isNewRecord)
				$this->user_id = !Yii::app()->user->isGuest ? Yii::app()->user->id : 0;
			else
				$this->modified_id = Yii::app()->user->id;
				
			$this->report_ip = $_SERVER['REMOTE_ADDR'];
		}
		return true;
	}

}