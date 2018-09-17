<?php
/**
 * Reports
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2014 Ommu Platform (www.ommu.co)
 * @modified date 23 July 2018, 09:50 WIB
 * @link https://github.com/ommu/mod-report
 *
 * This is the model class for table "ommu_reports".
 *
 * The followings are the available columns in table 'ommu_reports':
 * @property integer $report_id
 * @property integer $status
 * @property integer $cat_id
 * @property integer $user_id
 * @property string $report_url
 * @property string $report_body
 * @property string $report_message
 * @property integer $reports
 * @property string $report_date
 * @property string $report_ip
 * @property string $modified_date
 * @property integer $modified_id
 * @property string $updated_date
 *
 * The followings are the available model relations:
 * @property ReportComment[] $comments
 * @property ReportComment[] $commentAll
 * @property ReportHistory[] $histories
 * @property ReportStatus[] $statuses
 * @property ReportUser[] $users
 * @property ReportUser[] $userAll
 * @property ViewReports $view
 * @property ReportCategory $category
 * @property Users $user
 * @property Users $modified
 */

class Reports extends OActiveRecord
{
	use GridViewTrait;

	public $gridForbiddenColumn = array('report_url','report_message','report_ip','modified_date','modified_search','updated_date','comment_i','status_i','user_i');
	public $old_status_i;
	public $comment_i;
	public $history_i;
	public $status_i;
	public $user_i;

	// Variable Search
	public $user_search;
	public $modified_search;

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
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
		preg_match("/dbname=([^;]+)/i", $this->dbConnection->connectionString, $matches);
		return $matches[1].'.ommu_reports';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('report_url, report_body', 'required'),
			array('report_message', 'required', 'on'=>'resolveForm'),
			array('status, cat_id, user_id, reports, modified_id, old_status_i', 'numerical', 'integerOnly'=>true),
			array('status, cat_id, user_id, reports, report_message, report_ip, old_status_i', 'safe'),
			array('cat_id', 'length', 'max'=>5),
			array('user_id, modified_id', 'length', 'max'=>11),
			array('report_ip', 'length', 'max'=>20),
			// array('report_date, modified_date, updated_date', 'trigger'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('report_id, status, cat_id, user_id, report_url, report_body, report_message, reports, report_date, report_ip, modified_date, modified_id, updated_date,
				comment_i, history_i, status_i, user_i, user_search, modified_search', 'safe', 'on'=>'search'),
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
			'comments' => array(self::HAS_MANY, 'ReportComment', 'report_id', 'on'=>'comments.publish=1'),
			'commentAll' => array(self::HAS_MANY, 'ReportComment', 'report_id'),
			'histories' => array(self::HAS_MANY, 'ReportHistory', 'report_id'),
			'statuses' => array(self::HAS_MANY, 'ReportStatus', 'report_id'),
			'users' => array(self::HAS_MANY, 'ReportUser', 'report_id', 'on'=>'users.publish=1'),
			'userAll' => array(self::HAS_MANY, 'ReportUser', 'report_id'),
			'view' => array(self::BELONGS_TO, 'ViewReports', 'report_id'),
			'category' => array(self::BELONGS_TO, 'ReportCategory', 'cat_id'),
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
			'status' => Yii::t('attribute', 'Resolved'),
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
			'old_status_i' => Yii::t('attribute', 'Old Status'),
			'comment_i' => Yii::t('attribute', 'Comments'),
			'history_i' => Yii::t('attribute', 'Histories'),
			'status_i' => Yii::t('attribute', 'Statuses'),
			'user_i' => Yii::t('attribute', 'Users'),
			'user_search' => Yii::t('attribute', 'User'),
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
			'view' => array(
				'alias' => 'view',
			),
			'user' => array(
				'alias' => 'user',
				'select' => 'displayname',
			),
			'modified' => array(
				'alias' => 'modified',
				'select' => 'displayname',
			),
		);

		$criteria->compare('t.report_id', $this->report_id);
		$criteria->compare('t.status', Yii::app()->getRequest()->getParam('status') ? Yii::app()->getRequest()->getParam('status') : $this->status);
		$criteria->compare('t.cat_id', Yii::app()->getRequest()->getParam('category') ? Yii::app()->getRequest()->getParam('category') : $this->cat_id);
		$criteria->compare('t.user_id', Yii::app()->getRequest()->getParam('user') ? Yii::app()->getRequest()->getParam('user') : $this->user_id);
		$criteria->compare('t.report_url', strtolower($this->report_url), true);
		$criteria->compare('t.report_body', strtolower($this->report_body), true);
		$criteria->compare('t.report_message', strtolower($this->report_message), true);
		$criteria->compare('t.reports', $this->reports);
		if($this->report_date != null && !in_array($this->report_date, array('0000-00-00 00:00:00','1970-01-01 00:00:00','0002-12-02 07:07:12','-0001-11-30 00:00:00')))
			$criteria->compare('date(t.report_date)', date('Y-m-d', strtotime($this->report_date)));
		$criteria->compare('t.report_ip', strtolower($this->report_ip), true);
		if($this->modified_date != null && !in_array($this->modified_date, array('0000-00-00 00:00:00','1970-01-01 00:00:00','0002-12-02 07:07:12','-0001-11-30 00:00:00')))
			$criteria->compare('date(t.modified_date)', date('Y-m-d', strtotime($this->modified_date)));
		$criteria->compare('t.modified_id', Yii::app()->getRequest()->getParam('modified') ? Yii::app()->getRequest()->getParam('modified') : $this->modified_id);
		if($this->updated_date != null && !in_array($this->updated_date, array('0000-00-00 00:00:00','1970-01-01 00:00:00','0002-12-02 07:07:12','-0001-11-30 00:00:00')))
			$criteria->compare('date(t.updated_date)', date('Y-m-d', strtotime($this->updated_date)));

		$criteria->compare('user.displayname', strtolower($this->user_search), true);			//user.displayname
		$criteria->compare('modified.displayname', strtolower($this->modified_search), true);
		$criteria->compare('view.comments', $this->comment_i);
		$criteria->compare('view.histories', $this->history_i);
		$criteria->compare('view.statuses', $this->status_i);
		$criteria->compare('view.users', $this->user_i);

		if(!Yii::app()->getRequest()->getParam('Reports_sort'))
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
			if(!Yii::app()->getRequest()->getParam('category')) {
				$this->templateColumns['cat_id'] = array(
					'name' => 'cat_id',
					'value' => '$data->category->title->message ? $data->category->title->message : \'-\'',
					'filter' => ReportCategory::getCategory(),
				);
			}
			if(!Yii::app()->getRequest()->getParam('user')) {
				$this->templateColumns['user_search'] = array(
					'name' => 'user_search',
					'value' => '$data->user->displayname ? $data->user->displayname : \'-\'',
				);
			}
			$this->templateColumns['report_url'] = array(
				'name' => 'report_url',
				'value' => '$data->report_url',
			);
			$this->templateColumns['report_body'] = array(
				'name' => 'report_body',
				'value' => '$data->report_body',
			);
			$this->templateColumns['report_message'] = array(
				'name' => 'report_message',
				'value' => '$data->report_message',
				'type' => 'raw',
			);
			$this->templateColumns['report_date'] = array(
				'name' => 'report_date',
				'value' => '!in_array($data->report_date, array(\'0000-00-00 00:00:00\', \'1970-01-01 00:00:00\', \'0002-12-02 07:07:12\', \'-0001-11-30 00:00:00\')) ? Yii::app()->dateFormatter->formatDateTime($data->report_date, \'medium\', false) : \'-\'',
				'htmlOptions' => array(
					'class' => 'center',
				),
				'filter' => $this->filterDatepicker($this, 'report_date'),
			);
			$this->templateColumns['report_ip'] = array(
				'name' => 'report_ip',
				'value' => '$data->report_ip',
			);
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
			$this->templateColumns['updated_date'] = array(
				'name' => 'updated_date',
				'value' => '!in_array($data->updated_date, array(\'0000-00-00 00:00:00\', \'1970-01-01 00:00:00\', \'0002-12-02 07:07:12\', \'-0001-11-30 00:00:00\')) ? Yii::app()->dateFormatter->formatDateTime($data->updated_date, \'medium\', false) : \'-\'',
				'htmlOptions' => array(
					'class' => 'center',
				),
				'filter' => $this->filterDatepicker($this, 'updated_date'),
			);
			$this->templateColumns['reports'] = array(
				'name' => 'reports',
				'value' => 'CHtml::link($data->reports, Yii::app()->controller->createurl(\'history/admin/manage\', array(\'report\'=>$data->report_id)))',
				'htmlOptions' => array(
					'class' => 'center',
				),
				'type' => 'raw',
			);
			$this->templateColumns['comment_i'] = array(
				'name' => 'comment_i',
				'value' => 'CHtml::link($data->view->comments ? $data->view->comments : 0, Yii::app()->controller->createUrl(\'o/comment/manage\', array(\'report\'=>$data->report_id)))',
				'htmlOptions' => array(
					'class' => 'center',
				),
				'type' => 'raw',
			);
			$this->templateColumns['status_i'] = array(
				'name' => 'status_i',
				'value' => 'CHtml::link($data->view->statuses ? $data->view->statuses : 0, Yii::app()->controller->createUrl(\'o/status/manage\', array(\'report\'=>$data->report_id)))',
				'htmlOptions' => array(
					'class' => 'center',
				),
				'type' => 'raw',
			);
			$this->templateColumns['user_i'] = array(
				'name' => 'user_i',
				'value' => 'CHtml::link($data->view->users ? $data->view->users : 0, Yii::app()->controller->createUrl(\'o/user/manage\', array(\'report\'=>$data->report_id)))',
				'htmlOptions' => array(
					'class' => 'center',
				),
				'type' => 'raw',
			);
			$this->templateColumns['status'] = array(
				'name' => 'status',
				'value' => 'Utility::getPublish(Yii::app()->controller->createUrl(\'status\', array(\'id\'=>$data->report_id)), $data->status, \'Resolved,Unresolved\')',
				'htmlOptions' => array(
					'class' => 'center',
				),
				'filter' => $this->filterYesNo(),
				'type' => 'raw',
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

	/**
	 * insertReport
	 */
	public static function insertReport($report_url, $report_body)
	{
		$setting = ReportSetting::model()->findByPk(1, array(
			'select' => 'auto_report_cat_id',
		));

		if(!$setting->auto_report_cat_id)
			return false;

		$user_id = !Yii::app()->user->isGuest ? Yii::app()->user->id : null;

		$criteria=new CDbCriteria;
		$criteria->select = 'report_id, reports';
		$criteria->compare('cat_id', $setting->auto_report_cat_id);
		$criteria->compare('report_url', $report_url);
		$findReport = self::model()->find($criteria);
		
		if($findReport != null)
			self::model()->updateByPk($findReport->report_id, array('user_id'=>$user_id, 'reports'=>$findReport->reports + 1, 'report_ip'=>$_SERVER['REMOTE_ADDR']));
		
		else {
			$report=new Reports;
			$report->cat_id = $setting->auto_report_cat_id;
			$report->report_url = $report_url;
			$report->report_body = $report_body;
			$report->save();
		}
	}

	/**
	 * This is invoked when a record is populated with data from a find() call.
	 */
	protected function afterFind()
	{
		parent::afterFind();
		$this->old_status_i = $this->status;

		return true;
	}

	/**
	 * before validate attributes
	 */
	protected function beforeValidate() 
	{
		if(parent::beforeValidate()) {
			if($this->isNewRecord)
				$this->user_id = !Yii::app()->user->isGuest ? Yii::app()->user->id : null;
			else
				$this->modified_id = !Yii::app()->user->isGuest ? Yii::app()->user->id : null;
			$this->report_ip = $_SERVER['REMOTE_ADDR'];
		}
		return true;
	}
}