<?php
/**
 * Reports (reports)
 * @var $this AdminController
 * @var $model Reports
 *
 * @author Putra Sudaryanto <putra@ommu.co>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2017 Ommu Platform (www.ommu.co)
 * @created date 23 August 2017, 23:39 WIB
 * @modified date 23 July 2018, 14:39 WIB
 * @link https://github.com/ommu/mod-report
 *
 */
?>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		array(
			'name'=>'report_id',
			'value'=>$model->report_id,
		),
		array(
			'name'=>'status',
			'value'=>$this->quickAction(Yii::app()->controller->createUrl('status', array('id'=>$model->report_id)), $model->status, 'Resolved,Unresolved'),
			'type'=>'raw',
		),
		array(
			'name'=>'cat_id',
			'value'=>$model->category->title->message ? $model->category->title->message : '-',
		),
		array(
			'name'=>'user_search',
			'value'=>$model->user->displayname ? $model->user->displayname : '-',
		),
		array(
			'name'=>'report_url',
			'value'=>$model->report_url ? $model->report_url : '-',
		),
		array(
			'name'=>'report_body',
			'value'=>$model->report_body ? $model->report_body : '-',
		),
		array(
			'name'=>'report_message',
			'value'=>$model->report_message ? $model->report_message : '-',
			'type'=>'raw',
		),
		array(
			'name'=>'reports',
			'value'=>$model->reports ? $model->reports : '-',
		),
		array(
			'name'=>'report_date',
			'value'=>!in_array($model->report_date, array('0000-00-00 00:00:00','1970-01-01 00:00:00','0002-12-02 07:07:12','-0001-11-30 00:00:00')) ? $this->dateFormat($model->report_date) : '-',
		),
		array(
			'name'=>'report_ip',
			'value'=>$model->report_ip ? $model->report_ip : '-',
		),
		array(
			'name'=>'modified_date',
			'value'=>!in_array($model->modified_date, array('0000-00-00 00:00:00','1970-01-01 00:00:00','0002-12-02 07:07:12','-0001-11-30 00:00:00')) ? $this->dateFormat($model->modified_date) : '-',
		),
		array(
			'name'=>'modified_search',
			'value'=>$model->modified->displayname ? $model->modified->displayname : '-',
		),
		array(
			'name'=>'updated_date',
			'value'=>!in_array($model->updated_date, array('0000-00-00 00:00:00','1970-01-01 00:00:00','0002-12-02 07:07:12','-0001-11-30 00:00:00')) ? $this->dateFormat($model->updated_date) : '-',
		),
	),
)); ?>