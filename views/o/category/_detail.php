<?php
/**
 * Report Categories (report-category)
 * @var $this CategoryController
 * @var $model ReportCategory
 *
 * @author Putra Sudaryanto <putra@ommu.co>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2017 Ommu Platform (www.ommu.co)
 * @created date 23 August 2017, 20:42 WIB
 * @modified date 21 July 2018, 16:37 WIB
 * @link https://github.com/ommu/mod-report
 *
 */
?>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		array(
			'name'=>'cat_id',
			'value'=>$model->cat_id,
		),
		array(
			'name'=>'publish',
			'value'=>$this->quickAction(Yii::app()->controller->createUrl('publish', array('id'=>$model->cat_id)), $model->publish, 'Enable,Disable'),
			'type'=>'raw',
		),
		array(
			'name'=>'name_i',
			'value'=>$model->name ? $model->title->message : '-',
		),
		array(
			'name'=>'desc_i',
			'value'=>$model->desc ? $model->description->message : '-',
		),
		array(
			'name'=>'creation_date',
			'value'=>!in_array($model->creation_date, array('0000-00-00 00:00:00','1970-01-01 00:00:00','0002-12-02 07:07:12','-0001-11-30 00:00:00')) ? $this->dateFormat($model->creation_date) : '-',
		),
		array(
			'name'=>'creation_search',
			'value'=>$model->creation->displayname ? $model->creation->displayname : '-',
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
		array(
			'name'=>'slug',
			'value'=>$model->slug ? $model->slug : '-',
		),
	),
)); ?>