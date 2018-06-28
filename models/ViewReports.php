<?php
/**
 * ViewReports
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2017 Ommu Platform (opensource.ommu.co)
 * @created date 22 February 2017, 13:14 WIB
 * @modified date 18 January 2018, 13:01 WIB
 * @link https://github.com/ommu/mod-report
 *
 * This is the model class for table "_reports".
 *
 * The followings are the available columns in table '_reports':
 * @property string $report_id
 * @property string $history_resolved
 * @property string $history_unresolved
 * @property string $history_all
 * @property string $comments
 * @property string $comment_all
 * @property string $users
 * @property string $user_all
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
			array('report_id', 'length', 'max'=>11),
			array('history_resolved, history_unresolved, comments, users', 'length', 'max'=>23),
			array('history_all, comment_all, user_all', 'length', 'max'=>21),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('report_id, history_resolved, history_unresolved, history_all, comments, comment_all, users, user_all', 'safe', 'on'=>'search'),
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
			'history_resolved' => Yii::t('attribute', 'History Resolved'),
			'history_unresolved' => Yii::t('attribute', 'History Unresolved'),
			'history_all' => Yii::t('attribute', 'History All'),
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
		$criteria->compare('t.history_resolved', $this->history_resolved);
		$criteria->compare('t.history_unresolved', $this->history_unresolved);
		$criteria->compare('t.history_all', $this->history_all);
		$criteria->compare('t.comments', $this->comments);
		$criteria->compare('t.comment_all', $this->comment_all);
		$criteria->compare('t.users', $this->users);
		$criteria->compare('t.user_all', $this->user_all);

		if(!Yii::app()->getRequest()->getParam('ViewReports_sort'))
			$criteria->order = 't.report_id DESC';

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
			$this->templateColumns['report_id'] = array(
				'name' => 'report_id',
				'value' => '$data->report_id',
			);
			$this->templateColumns['history_resolved'] = array(
				'name' => 'history_resolved',
				'value' => '$data->history_resolved',
			);
			$this->templateColumns['history_unresolved'] = array(
				'name' => 'history_unresolved',
				'value' => '$data->history_unresolved',
			);
			$this->templateColumns['history_all'] = array(
				'name' => 'history_all',
				'value' => '$data->history_all',
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