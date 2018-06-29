<?php
/**
 * ReportHistory
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2017 Ommu Platform (opensource.ommu.co)
 * @created date 24 August 2017, 13:47 WIB
 * @modified date 18 January 2018, 00:31 WIB
 * @link https://github.com/ommu/mod-report
 *
 * This is the model class for table "ommu_report_history".
 *
 * The followings are the available columns in table 'ommu_report_history':
 * @property string $id
 * @property string $report_id
 * @property string $user_id
 * @property string $report_date
 * @property string $report_ip
 *
 * The followings are the available model relations:
 * @property Reports $report
 * @property Users $user
 */
class ReportHistory extends OActiveRecord
{
	public $gridForbiddenColumn = array();

	// Variable Search
	public $category_search;
	public $report_search;
	public $user_search;

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return ReportHistory the static model class
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
		preg_match("/dbname=([^;]+)/i", $this->dbConnection->connectionString, $matches);
		return $matches[1].'.ommu_report_history';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('report_id, user_id, report_date, report_ip', 'required'),
			array('report_id, user_id', 'length', 'max'=>11),
			array('report_ip', 'length', 'max'=>20),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, report_id, user_id, report_date, report_ip, 
				category_search, report_search, user_search', 'safe', 'on'=>'search'),
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
			'report' => array(self::BELONGS_TO, 'Reports', 'report_id'),
			'user' => array(self::BELONGS_TO, 'Users', 'user_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => Yii::t('attribute', 'ID'),
			'report_id' => Yii::t('attribute', 'Report'),
			'user_id' => Yii::t('attribute', 'User'),
			'report_date' => Yii::t('attribute', 'Report Date'),
			'report_ip' => Yii::t('attribute', 'Report Ip'),
			'category_search' => Yii::t('attribute', 'Category'),
			'report_search' => Yii::t('attribute', 'Report'),
			'user_search' => Yii::t('attribute', 'User'),
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 *
	 * Typical usecase:
	 * - Initialize the model fields with values from filter form.
	 * - Execute this method to get CActiveDataProvider instance which will filter
	 * models according to data in model fields.
	 * - Pass data provider to CGridView, CListView or any similar widget.
	 *
	 * @return CActiveDataProvider the data provider that can return the models
	 * based on the search/filter conditions.
	 */
	public function search()
	{
		// @todo Please modify the following code to remove attributes that should not be searched.

		$criteria=new CDbCriteria;

		// Custom Search
		$criteria->with = array(
			'report' => array(
				'alias'=>'report',
				'select'=>'cat_id, report_url, report_body'
			),
			'user' => array(
				'alias'=>'user',
				'select'=>'displayname'
			),
		);
		
		$criteria->compare('t.id', $this->id);
		$criteria->compare('t.report_id', Yii::app()->getRequest()->getParam('report') ? Yii::app()->getRequest()->getParam('report') : $this->report_id);
		$criteria->compare('t.user_id', Yii::app()->getRequest()->getParam('user') ? Yii::app()->getRequest()->getParam('user') : $this->user_id);
		if($this->report_date != null && !in_array($this->report_date, array('0000-00-00 00:00:00','1970-01-01 00:00:00','0002-12-02 07:07:12','-0001-11-30 00:00:00')))
			$criteria->compare('date(t.report_date)', date('Y-m-d', strtotime($this->report_date)));
		$criteria->compare('t.report_ip', strtolower($this->report_ip), true);

		$criteria->compare('report.cat_id',$this->category_search);
		$criteria->compare('report.report_body', strtolower($this->report_search), true);
		$criteria->compare('user.displayname', strtolower($this->user_search), true);

		if(!Yii::app()->getRequest()->getParam('ReportHistory_sort'))
			$criteria->order = 't.id DESC';

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
			'pagination'=>array(
				'pageSize'=>Yii::app()->params['grid-view'] ? Yii::app()->params['grid-view']['pageSize'] : 20,
			),
		));
	}

	/**
	 * Set default columns to display
	 */
	protected function afterConstruct() {
		if(count($this->templateColumns) == 0) {
			$this->templateColumns['_option'] = array(
				'class' => 'CCheckBoxColumn',
				'name' => 'id',
				'selectableRows' => 2,
				'checkBoxHtmlOptions' => array('name' => 'trash_id[]')
			);
			$this->templateColumns['_no'] = array(
				'header' => Yii::t('app', 'No'),
				'value' => '$this->grid->dataProvider->pagination->currentPage*$this->grid->dataProvider->pagination->pageSize + $row+1',
				'htmlOptions' => array(
					'class' => 'center',
				),
			);
			if(!Yii::app()->getRequest()->getParam('report')) {
				$this->templateColumns['category_search'] = array(
					'name' => 'category_search',
					'value' => '$data->report->category->title->message',
					'filter'=> ReportCategory::getCategory(),
					'type' => 'raw',
				);
				$this->templateColumns['report_search'] = array(
					'name' => 'report_search',
					'value' => '$data->report->report_body ? $data->report->report_body : \'-\'',
				);
			}
			if(!Yii::app()->getRequest()->getParam('user')) {
				$this->templateColumns['user_search'] = array(
					'name' => 'user_search',
					'value' => '$data->user->displayname ? $data->user->displayname : \'-\'',
				);
			}
			$this->templateColumns['report_date'] = array(
				'name' => 'report_date',
				'value' => '!in_array($data->report_date, array(\'0000-00-00 00:00:00\', \'1970-01-01 00:00:00\', \'0002-12-02 07:07:12\', \'-0001-11-30 00:00:00\')) ? Utility::dateFormat($data->report_date, true) : \'-\'',
				'htmlOptions' => array(
					'class' => 'center',
				),
				'filter' => 'native-datepicker',
				/*
				'filter' => Yii::app()->controller->widget('application.libraries.core.components.system.CJuiDatePicker', array(
					'model'=>$this,
					'attribute'=>'report_date',
					'language' => 'en',
					'i18nScriptFile' => 'jquery-ui-i18n.min.js',
					//'mode'=>'datetime',
					'htmlOptions' => array(
						'id' => 'report_date_filter',
						'on_datepicker' => 'on',
						'placeholder' => Yii::t('phrase', 'filter'),
					),
					'options'=>array(
						'showOn' => 'focus',
						'dateFormat' => 'yy-mm-dd',
						'showOtherMonths' => true,
						'selectOtherMonths' => true,
						'changeMonth' => true,
						'changeYear' => true,
						'showButtonPanel' => true,
					),
				), true),
				*/
			);
			$this->templateColumns['report_ip'] = array(
				'name' => 'report_ip',
				'value' => '$data->report_ip',
			);
		}
		parent::afterConstruct();
	}

	/**
	 * User get information
	 */
	public static function getInfo($id, $column=null)
	{
		if($column != null) {
			$model = self::model()->findByPk($id,array(
				'select' => $column,
			));
 			if(count(explode(',', $column)) == 1)
 				return $model->$column;
 			else
 				return $model;
			
		} else {
			$model = self::model()->findByPk($id);
			return $model;
		}
	}

}