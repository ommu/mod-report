<?php
/**
 * Report Histories (report-history)
 * @var $this HistoryController
 * @var $model ReportHistory
 * @var $dataProvider CActiveDataProvider
 * version: 0.0.1
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @copyright Copyright (c) 2017 Ommu Platform (opensource.ommu.co)
 * @created date 24 August 2017, 14:01 WIB
 * @link https://github.com/ommu/mod-report
 * @contact (+62)856-299-4114
 *
 */

	$this->breadcrumbs=array(
		'Report Histories',
	);
?>

<?php $this->widget('application.components.system.FListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
	'pager' => array(
		'header' => '',
	), 
	'summaryText' => '',
	'itemsCssClass' => 'items clearfix',
	'pagerCssClass'=>'pager clearfix',
)); ?>
