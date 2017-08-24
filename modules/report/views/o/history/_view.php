<?php
/**
 * Report Histories (report-history)
 * @var $this HistoryController
 * @var $data ReportHistory
 * version: 0.0.1
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @copyright Copyright (c) 2017 Ommu Platform (opensource.ommu.co)
 * @created date 24 August 2017, 14:01 WIB
 * @link https://github.com/ommu/mod-report
 * @contact (+62)856-299-4114
 *
 */
?>

<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->id), array('view', 'id'=>$data->id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('report_id')); ?>:</b>
	<?php echo CHtml::encode($data->report->column_name_relation); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('user_id')); ?>:</b>
	<?php echo CHtml::encode($data->user->displayname); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('report_date')); ?>:</b>
	<?php echo CHtml::encode(Utility::dateFormat($data->report_date, true)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('report_ip')); ?>:</b>
	<?php echo CHtml::encode($data->report_ip); ?>
	<br />


</div>