<?php
/**
 * Report Status (report-status)
 * @var $this StatusController
 * @var $model ReportStatus
 * version: 0.0.1
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @copyright Copyright (c) 2017 Ommu Platform (opensource.ommu.co)
 * @created date 22 February 2017, 12:25 WIB
 * @link https://github.com/ommu/mod-report
 * @contact (+62)856-299-4114
 *
 */

	$this->breadcrumbs=array(
		'Report Status'=>array('manage'),
		$model->history_id,
	);
?>

<div class="dialog-content">
<?php $this->widget('application.components.system.FDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		array(
			'name'=>'history_id',
			'value'=>$model->history_id,
		),
		array(
			'name'=>'status',
			'value'=>$model->status == '1' ? Chtml::image(Yii::app()->theme->baseUrl.'/images/icons/publish.png') : Chtml::image(Yii::app()->theme->baseUrl.'/images/icons/unpublish.png'),
			'type'=>'raw',
		),
		array(
			'name'=>'report_id',
			'value'=>$model->report->report_url || $model->report->report_url || $model->report->report_date ? $this->renderPartial('_view_report', array('model'=>$model), true, false) : '-',
			'type'=>'raw',
		),
		array(
			'name'=>'report_message',
			'value'=>$model->report_message != '' ? $model->report_message : '-',
			'type'=>'raw',
		),
		array(
			'name'=>'user_id',
			'value'=>$model->user_id != 0 ? $model->user->displayname : '-',
		),
		array(
			'name'=>'updated_date',
			'value'=>!in_array($model->updated_date, array('0000-00-00 00:00:00','1970-01-01 00:00:00')) ? Utility::dateFormat($model->updated_date, true) : '-',
		),
		array(
			'name'=>'updated_ip',
			'value'=>$model->updated_ip != '' ? $model->updated_ip : '-',
		),
		array(
			'name'=>'modified_date',
			'value'=>!in_array($model->modified_date, array('0000-00-00 00:00:00','1970-01-01 00:00:00')) ? Utility::dateFormat($model->modified_date, true) : '-',
		),
		array(
			'name'=>'modified_id',
			'value'=>$model->modified_id != 0 ? $model->modified->displayname : '-',
		),
	),
)); ?>
</div>
<div class="dialog-submit">
	<?php echo CHtml::button(Yii::t('phrase', 'Close'), array('id'=>'closed')); ?>
</div>
