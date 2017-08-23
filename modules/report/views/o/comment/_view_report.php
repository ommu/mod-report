<?php
/**
 * Report Comments (report-comment)
 * @var $this CommentController
 * @var $model ReportComment
 * version: 0.0.1
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @copyright Copyright (c) 2012 Ommu Platform (opensource.ommu.co)
 * @created date 22 February 2017, 21:37 WIB
 * @link https://github.com/ommu/mod-report
 * @contact (+62)856-299-4114
 *
 */

	$report_url = $model->report->report_url ? $model->report->report_url : '-';
	$report_body = $model->report->report_body ? $model->report->report_body : '-';
	$report_date = $model->report->report_date ? Utility::dateFormat($model->report->report_date, true) : '-';
?>

<ul>
	<li><?php echo Yii::t('phrase', 'Name: $report_url', array('$report_url'=>$report_url));?></li>
	<li><?php echo Yii::t('phrase', 'Email: $report_body', array('$report_body'=>$report_body));?></li>
	<li><?php echo Yii::t('phrase', 'Phone: $report_date', array('$report_date'=>$report_date));?></li>
<ul>