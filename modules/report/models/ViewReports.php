<?php
/**
 * ViewReports
 * version: 0.0.1
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @copyright Copyright (c) 2017 Ommu Platform (opensource.ommu.co)
 * @created date 22 February 2017, 13:14 WIB
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
 * This is the model class for table "_view_reports".
 *
 * The followings are the available columns in table '_view_reports':
 * @property string $report_id
 * @property string $reports
 * @property string $report_resolved
 * @property string $report_all
 * @property string $comments
 * @property string $comment_all
 * @property string $users
 * @property string $user_all
 */
class ViewReports extends CActiveRecord
{
	public $defaultColumns = array();

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return ViewReports the static model class
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
		return '_view_reports';
	}

	/**
	 * @return string the primarykey column
	 */
	public function primaryKey()
	{
		return 'report_id';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('report_id', 'length', 'max'=>11),
			array('reports, report_resolved, report_all, comments, comment_all, users, user_all', 'length', 'max'=>21),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('report_id, reports, report_resolved, report_all, comments, comment_all, users, user_all', 'safe', 'on'=>'search'),
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
			'report_id' => Yii::t('attribute', 'Report'),
			'reports' => Yii::t('attribute', 'Reports'),
			'report_resolved' => Yii::t('attribute', 'Report Resolved'),
			'report_all' => Yii::t('attribute', 'Report All'),
			'comments' => Yii::t('attribute', 'Comments'),
			'comment_all' => Yii::t('attribute', 'Comment All'),
			'users' => Yii::t('attribute', 'Users'),
			'user_all' => Yii::t('attribute', 'User All'),
		);
		/*
			'Report' => 'Report',
			'Reports' => 'Reports',
			'Report Resolved' => 'Report Resolved',
			'Report All' => 'Report All',
			'Comments' => 'Comments',
			'Comment All' => 'Comment All',
			'Users' => 'Users',
			'User All' => 'User All',
		
		*/
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

		$criteria->compare('t.report_id',strtolower($this->report_id),true);
		$criteria->compare('t.reports',strtolower($this->reports),true);
		$criteria->compare('t.report_resolved',strtolower($this->report_resolved),true);
		$criteria->compare('t.report_all',strtolower($this->report_all),true);
		$criteria->compare('t.comments',strtolower($this->comments),true);
		$criteria->compare('t.comment_all',strtolower($this->comment_all),true);
		$criteria->compare('t.users',strtolower($this->users),true);
		$criteria->compare('t.user_all',strtolower($this->user_all),true);

		if(!isset($_GET['ViewReports_sort']))
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
		} else {
			$this->defaultColumns[] = 'report_id';
			$this->defaultColumns[] = 'reports';
			$this->defaultColumns[] = 'report_resolved';
			$this->defaultColumns[] = 'report_all';
			$this->defaultColumns[] = 'comments';
			$this->defaultColumns[] = 'comment_all';
			$this->defaultColumns[] = 'users';
			$this->defaultColumns[] = 'user_all';
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
			$this->defaultColumns[] = 'report_id';
			$this->defaultColumns[] = 'reports';
			$this->defaultColumns[] = 'report_resolved';
			$this->defaultColumns[] = 'report_all';
			$this->defaultColumns[] = 'comments';
			$this->defaultColumns[] = 'comment_all';
			$this->defaultColumns[] = 'users';
			$this->defaultColumns[] = 'user_all';
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