<?php
/**
 * ViewReports
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2017 Ommu Platform (www.ommu.co)
 * @created date 22 February 2017, 13:14 WIB
 * @modified date 23 July 2018, 09:00 WIB
 * @link https://github.com/ommu/mod-report
 *
 * This is the model class for table "_reports".
 *
 * The followings are the available columns in table '_reports':
 * @property integer $report_id
 * @property integer $histories
 * @property string $resolved
 * @property string $unresolved
 * @property integer $statuses
 * @property string $comments
 * @property integer $comment_all
 * @property string $users
 * @property integer $user_all
 */

class ViewReports extends OActiveRecord
{
	public $gridForbiddenColumn = array();

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
		preg_match("/dbname=([^;]+)/i", $this->dbConnection->connectionString, $matches);
		return $matches[1].'._reports';
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
			array('report_id, histories, statuses, comment_all, user_all', 'numerical', 'integerOnly'=>true),
			array('report_id', 'length', 'max'=>11),
			array('histories, statuses, comment_all, user_all', 'length', 'max'=>21),
			array('resolved, unresolved, comments, users', 'length', 'max'=>23),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('report_id, histories, resolved, unresolved, statuses, comments, comment_all, users, user_all', 'safe', 'on'=>'search'),
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
			'histories' => Yii::t('attribute', 'Histories'),
			'resolved' => Yii::t('attribute', 'Resolved'),
			'unresolved' => Yii::t('attribute', 'Unresolved'),
			'statuses' => Yii::t('attribute', 'Statuses'),
			'comments' => Yii::t('attribute', 'Comments'),
			'comment_all' => Yii::t('attribute', 'Comment All'),
			'users' => Yii::t('attribute', 'Users'),
			'user_all' => Yii::t('attribute', 'User All'),
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
		$criteria->compare('t.report_id', $this->report_id);
		$criteria->compare('t.histories', $this->histories);
		$criteria->compare('t.resolved', $this->resolved);
		$criteria->compare('t.unresolved', $this->unresolved);
		$criteria->compare('t.statuses', $this->statuses);
		$criteria->compare('t.comments', $this->comments);
		$criteria->compare('t.comment_all', $this->comment_all);
		$criteria->compare('t.users', $this->users);
		$criteria->compare('t.user_all', $this->user_all);

		if(!Yii::app()->getRequest()->getParam('ViewReports_sort'))
			$criteria->order = 't.report_id DESC';

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
			$this->templateColumns['report_id'] = array(
				'name' => 'report_id',
				'value' => '$data->report_id',
			);
			$this->templateColumns['histories'] = array(
				'name' => 'histories',
				'value' => '$data->histories',
			);
			$this->templateColumns['resolved'] = array(
				'name' => 'resolved',
				'value' => '$data->resolved',
			);
			$this->templateColumns['unresolved'] = array(
				'name' => 'unresolved',
				'value' => '$data->unresolved',
			);
			$this->templateColumns['statuses'] = array(
				'name' => 'statuses',
				'value' => '$data->statuses',
			);
			$this->templateColumns['comments'] = array(
				'name' => 'comments',
				'value' => '$data->comments',
			);
			$this->templateColumns['comment_all'] = array(
				'name' => 'comment_all',
				'value' => '$data->comment_all',
			);
			$this->templateColumns['users'] = array(
				'name' => 'users',
				'value' => '$data->users',
			);
			$this->templateColumns['user_all'] = array(
				'name' => 'user_all',
				'value' => '$data->user_all',
			);
		}
		parent::afterConstruct();
	}

	/**
	 * Model get information
	 */
	public static function getInfo($id, $column=null)
	{
		if($column != null) {
			$model = self::model()->findByPk($id, array(
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