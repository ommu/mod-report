<?php
/**
 * Report Users (report-user)
 * @var $this UserController
 * @var $model ReportUser
 *
 * @author Putra Sudaryanto <putra@ommu.co>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2012 Ommu Platform (www.ommu.co)
 * @created date 23 August 2017, 11:02 WIB
 * @modified date 18 January 2018, 13:39 WIB
 * @link https://github.com/ommu/mod-report
 *
 */

	$category_name = $model->report->category->title->message ? $model->report->category->title->message : '-';
	$report_url = $model->report->report_url ? $model->report->report_url : '-';
	$report_body = $model->report->report_body ? $model->report->report_body : '-';
	$report_date = $model->report->report_date ? $this->dateFormat($model->report->report_date) : '-';
?>

<ul>
	<li><?php echo Yii::t('phrase', 'Category: {category_name}', array('{category_name}' => $category_name));?></li>
	<li><?php echo Yii::t('phrase', 'URL: {report_url}', array('{report_url}' => $report_url));?></li>
	<li><?php echo Yii::t('phrase', 'Error: {report_body}', array('{report_body}' => $report_body));?></li>
	<li><?php echo Yii::t('phrase', 'Date: {report_date}', array('{report_date}' => $report_date));?></li>
<ul>