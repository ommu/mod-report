<?php
/**
 * ReportSetting
 *
 * @author Putra Sudaryanto <putra@ommu.co>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2017 Ommu Platform (www.ommu.co)
 * @created date 24 August 2017, 14:15 WIB
 * @modified date 22 July 2018, 19:47 WIB
 * @link https://github.com/ommu/mod-report
 *
 * This is the model class for table "ommu_report_setting".
 *
 * The followings are the available columns in table 'ommu_report_setting':
 * @property integer $id
 * @property string $license
 * @property integer $permission
 * @property string $meta_keyword
 * @property string $meta_description
 * @property integer $auto_report_cat_id
 * @property string $modified_date
 * @property integer $modified_id
 *
 * The followings are the available model relations:
 * @property ReportCategory $category
 * @property Users $modified
 */

class ReportSetting extends OActiveRecord
{
	use GridViewTrait;

	public $gridForbiddenColumn = array();
	public $auto_report_i;

	// Variable Search
	public $modified_search;

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return ReportSetting the static model class
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
		return $matches[1].'.ommu_report_setting';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('license, meta_keyword, meta_description', 'required'),
			array('permission, auto_report_cat_id, modified_id, auto_report_i', 'numerical', 'integerOnly'=>true),
			array('auto_report_cat_id, auto_report_i', 'safe'),
			array('auto_report_cat_id', 'length', 'max'=>5),
			array('modified_id', 'length', 'max'=>11),
			array('license', 'length', 'max'=>32),
			// array('modified_date', 'trigger'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, license, permission, meta_keyword, meta_description, auto_report_cat_id, modified_date, modified_id,
				modified_search', 'safe', 'on'=>'search'),
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
			'category' => array(self::BELONGS_TO, 'ReportCategory', 'auto_report_cat_id'),
			'modified' => array(self::BELONGS_TO, 'Users', 'modified_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => Yii::t('attribute', 'ID'),
			'license' => Yii::t('attribute', 'License'),
			'permission' => Yii::t('attribute', 'Permission'),
			'meta_keyword' => Yii::t('attribute', 'Meta Keyword'),
			'meta_description' => Yii::t('attribute', 'Meta Description'),
			'auto_report_cat_id' => Yii::t('attribute', 'Auto Report Cat'),
			'modified_date' => Yii::t('attribute', 'Modified Date'),
			'modified_id' => Yii::t('attribute', 'Modified'),
			'auto_report_i' => Yii::t('attribute', 'Enable Auto Report'),
			'modified_search' => Yii::t('attribute', 'Modified'),
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
		$criteria->with = array(
			'modified' => array(
				'alias' => 'modified',
				'select' => 'displayname',
			),
		);

		$criteria->compare('t.id', $this->id);
		$criteria->compare('t.license', strtolower($this->license), true);
		$criteria->compare('t.permission', $this->permission);
		$criteria->compare('t.meta_keyword', strtolower($this->meta_keyword), true);
		$criteria->compare('t.meta_description', strtolower($this->meta_description), true);
		$criteria->compare('t.auto_report_cat_id', Yii::app()->getRequest()->getParam('category') ? Yii::app()->getRequest()->getParam('category') : $this->auto_report_cat_id);
		if($this->modified_date != null && !in_array($this->modified_date, array('0000-00-00 00:00:00','1970-01-01 00:00:00','0002-12-02 07:07:12','-0001-11-30 00:00:00')))
			$criteria->compare('date(t.modified_date)', date('Y-m-d', strtotime($this->modified_date)));
		$criteria->compare('t.modified_id', Yii::app()->getRequest()->getParam('modified') ? Yii::app()->getRequest()->getParam('modified') : $this->modified_id);

		$criteria->compare('modified.displayname', strtolower($this->modified_search), true);

		if(!Yii::app()->getRequest()->getParam('ReportSetting_sort'))
			$criteria->order = 't.id DESC';

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
			'pagination'=>array(
				'pageSize'=>Yii::app()->params['grid-view'] ? Yii::app()->params['grid-view']['pageSize'] : 50,
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
			$this->templateColumns['license'] = array(
				'name' => 'license',
				'value' => '$data->license',
			);
			$this->templateColumns['permission'] = array(
				'name' => 'permission',
				'value' => '$data->permission ? Yii::t(\'phrase\', \'Yes\') : Yii::t(\'phrase\, \'No\')',
			);
			$this->templateColumns['meta_keyword'] = array(
				'name' => 'meta_keyword',
				'value' => '$data->meta_keyword',
			);
			$this->templateColumns['meta_description'] = array(
				'name' => 'meta_description',
				'value' => '$data->meta_description',
			);
			if(!Yii::app()->getRequest()->getParam('category')) {
				$this->templateColumns['auto_report_cat_id'] = array(
					'name' => 'auto_report_cat_id',
					'value' => '$data->category->title->message ? $data->category->title->message : \'-\'',
					'filter' => ReportCategory::getCategory(),
				);
			}
			$this->templateColumns['modified_date'] = array(
				'name' => 'modified_date',
				'value' => '!in_array($data->modified_date, array(\'0000-00-00 00:00:00\', \'1970-01-01 00:00:00\', \'0002-12-02 07:07:12\', \'-0001-11-30 00:00:00\')) ? Yii::app()->dateFormatter->formatDateTime($data->modified_date, \'medium\', false) : \'-\'',
				'htmlOptions' => array(
					'class' => 'center',
				),
				'filter' => $this->filterDatepicker($this, 'modified_date'),
			);
			if(!Yii::app()->getRequest()->getParam('modified')) {
				$this->templateColumns['modified_search'] = array(
					'name' => 'modified_search',
					'value' => '$data->modified->displayname ? $data->modified->displayname : \'-\'',
				);
			}
		}
		parent::afterConstruct();
	}

	/**
	 * Model get information
	 */
	public static function getInfo($column=null)
	{
		if($column != null) {
			$model = self::model()->findByPk(1, array(
				'select' => $column,
			));
			if(count(explode(',', $column)) == 1)
				return $model->$column;
			else
				return $model;
			
		} else {
			$model = self::model()->findByPk(1);
			return $model;
		}
	}

	/**
	 * This is invoked when a record is populated with data from a find() call.
	 */
	protected function afterFind()
	{
		parent::afterFind();
		if($this->auto_report_cat_id)
			$this->auto_report_i = 1;

		return true;
	}

	/**
	 * before validate attributes
	 */
	protected function beforeValidate() 
	{
		if(parent::beforeValidate()) {
			if(!$this->isNewRecord)
				$this->modified_id = !Yii::app()->user->isGuest ? Yii::app()->user->id : null;

			if($this->auto_report_i && !$this->auto_report_cat_id)
				$this->addError('auto_report_cat_id', Yii::t('attribute', '{attribute} cannot be blank.', ['{attribute}'=>$this->getAttributeLabel('auto_report_cat_id')]));
		}
		return true;
	}

	/**
	 * before save attributes
	 */
	protected function beforeSave() 
	{
		if(parent::beforeSave()) {
			if(!$this->auto_report_i)
				$this->auto_report_cat_id = null;
		}
		return true;
	}
}