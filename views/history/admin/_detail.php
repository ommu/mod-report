<?php
/**
 * Report Histories (report-history)
 * @var $this AdminController
 * @var $model ReportHistory
 *
 * @author Putra Sudaryanto <putra@ommu.co>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2018 Ommu Platform (www.ommu.co)
 * @created date 23 July 2018, 12:46 WIB
 * @modified date 21 September 2018, 21:33 WIB
 * @link https://github.com/ommu/mod-report
 *
 */
?>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		array(
			'name'=>'id',
			'value'=>$model->id,
		),
		array(
			'name'=>'report_search',
			'value'=>$model->report->report_url || $model->report->report_date ? $this->renderPartial('_view_report', array('model'=>$model), true, false) : '-',
			'type'=>'raw',
		),
		array(
			'name'=>'user_search',
			'value'=>$model->user->displayname ? $model->user->displayname : '-',
		),
		array(
			'name'=>'report_date',
			'value'=>!in_array($model->report_date, array('0000-00-00 00:00:00','1970-01-01 00:00:00','0002-12-02 07:07:12','-0001-11-30 00:00:00')) ? $this->dateFormat($model->report_date) : '-',
		),
		array(
			'name'=>'report_ip',
			'value'=>$model->report_ip ? $model->report_ip : '-',
		),
	),
)); ?>