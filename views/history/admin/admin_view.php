<?php
/**
 * Report Histories (report-history)
 * @var $this AdminController
 * @var $model ReportHistory
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2018 Ommu Platform (www.ommu.co)
 * @created date 23 July 2018, 12:46 WIB
 * @modified date 21 September 2018, 21:33 WIB
 * @link https://github.com/ommu/mod-report
 *
 */

	$this->breadcrumbs=array(
		Yii::t('phrase', 'Report')=>array('o/admin/manage'),
		Yii::t('phrase', 'History')=>array('manage'),
		$model->report->report_body,
	);
?>

<?php //begin.Messages ?>
<div id="ajax-message">
<?php if(Yii::app()->user->hasFlash('success'))
	echo $this->flashMessage(Yii::app()->user->getFlash('success'), 'success');?>
</div>
<?php //end.Messages ?>

<div class="dialog-content">
	<?php echo $this->renderPartial('_detail', array('model'=>$model)); ?>
</div>
<div class="dialog-submit">
	<?php echo CHtml::button(Yii::t('phrase', 'Close'), array('id'=>'closed')); ?>
</div>
