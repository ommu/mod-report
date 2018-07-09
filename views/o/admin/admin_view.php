<?php
/**
 * Reports (reports)
 * @var $this AdminController
 * @var $model Reports
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2017 Ommu Platform (www.ommu.co)
 * @created date 23 August 2017, 23:39 WIB
 * @modified date 18 January 2018, 13:38 WIB
 * @link https://github.com/ommu/mod-report
 *
 */

	$this->breadcrumbs=array(
		'Reports'=>array('manage'),
		$model->report_id,
	);
?>

<div class="dialog-content">
<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		array(
			'name'=>'report_id',
			'value'=>$model->report_id,
		),
		array(
			'name'=>'status',
			'value'=>$model->status == '1' ? CHtml::image(Yii::app()->theme->baseUrl.'/images/icons/publish.png') : CHtml::image(Yii::app()->theme->baseUrl.'/images/icons/unpublish.png'),
			'type'=>'raw',
		),
		array(
			'name'=>'cat_id',
			'value'=>$model->cat_id ? $model->category->title->message : '-',
		),
		array(
			'name'=>'user_id',
			'value'=>$model->user_id ? $model->user->displayname : '-',
		),
		array(
			'name'=>'report_url',
			'value'=>$model->report_url ? $model->report_url : '-',
			'type'=>'raw',
		),
		array(
			'name'=>'report_body',
			'value'=>$model->report_body ? $model->report_body : '-',
			'type'=>'raw',
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
			'value'=>!in_array($model->report_date, array('0000-00-00 00:00:00','1970-01-01 00:00:00','0002-12-02 07:07:12','-0001-11-30 00:00:00')) ? $this->dateFormat($model->report_date, true) : '-',
		),
		array(
			'name'=>'report_ip',
			'value'=>$model->report_ip ? $model->report_ip : '-',
		),
		array(
			'name'=>'modified_date',
			'value'=>!in_array($model->modified_date, array('0000-00-00 00:00:00','1970-01-01 00:00:00','0002-12-02 07:07:12','-0001-11-30 00:00:00')) ? $this->dateFormat($model->modified_date, true) : '-',
		),
		array(
			'name'=>'modified_id',
			'value'=>$model->modified_id ? $model->modified->displayname : '-',
		),
		array(
			'name'=>'updated_date',
			'value'=>!in_array($model->updated_date, array('0000-00-00 00:00:00','1970-01-01 00:00:00','0002-12-02 07:07:12','-0001-11-30 00:00:00')) ? $this->dateFormat($model->updated_date, true) : '-',
		),
	),
)); ?>
</div>
<div class="dialog-submit">
	<?php echo CHtml::button(Yii::t('phrase', 'Close'), array('id'=>'closed')); ?>
</div>