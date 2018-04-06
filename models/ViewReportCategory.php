<?php
/**
 * ViewReportCategory
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2015 Ommu Platform (opensource.ommu.co)
 * @modified date 18 January 2018, 13:01 WIB
 * @link https://github.com/ommu/mod-report
 *
 * This is the model class for table "_report_category".
 *
 * The followings are the available columns in table '_report_category':
 * @property integer $cat_id
 * @property string $reports
 * @property string $report_resolved
 * @property string $report_all
 */
class ViewReportCategory extends OActiveRecord
{
	public $gridForbiddenColumn = array();

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return ViewReportCategory the static model class
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
		return $matches[1].'._report_category';
	}

	/**
	 * @return string the primarykey column
	 */
	public function primaryKey()
	{
		return 'cat_id';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('cat_id', 'numerical', 'integerOnly'=>true),
			array('reports, report_resolved', 'length', 'max'=>23),
			array('report_all', 'length', 'max'=>21),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('cat_id, reports, report_resolved, report_all', 'safe', 'on'=>'search'),
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
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'cat_id' => Yii::t('attribute', 'Category'),
			'reports' => Yii::t('attribute', 'Report'),
			'report_resolved' => Yii::t('attribute', 'Resolved'),
			'report_all' => Yii::t('attribute', 'All'),
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

		$criteria->compare('t.cat_id', $this->cat_id);
		$criteria->compare('t.reports', $this->reports);
		$criteria->compare('t.report_resolved', $this->report_resolved);
		$criteria->compare('t.report_all', $this->report_all);

		if(!Yii::app()->getRequest()->getParam('ViewReportCategory_sort'))
			$criteria->order = 't.cat_id DESC';

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
			$this->templateColumns['cat_id'] = array(
				'name' => 'cat_id',
				'value' => '$data->cat_id',
			);
			$this->templateColumns['reports'] = array(
				'name' => 'reports',
				'value' => '$data->reports',
			);
			$this->templateColumns['report_resolved'] = array(
				'name' => 'report_resolved',
				'value' => '$data->report_resolved',
			);
			$this->templateColumns['report_all'] = array(
				'name' => 'report_all',
				'value' => '$data->report_all',
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
				'select' => $column
			));
			return $model->$column;
			
		} else {
			$model = self::model()->findByPk($id);
			return $model;
		}
	}

}