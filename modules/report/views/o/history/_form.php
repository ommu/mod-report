<?php
/**
 * Report Histories (report-history)
 * @var $this HistoryController
 * @var $model ReportHistory
 * @var $form CActiveForm
 * version: 0.0.1
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @copyright Copyright (c) 2017 Ommu Platform (ommu.co)
 * @created date 22 February 2017, 12:25 WIB
 * @link https://github.com/ommu/Report
 * @contect (+62)856-299-4114
 *
 */
?>

<?php $form=$this->beginWidget('application.components.system.OActiveForm', array(
	'id'=>'report-history-form',
	'enableAjaxValidation'=>true,
	//'htmlOptions' => array('enctype' => 'multipart/form-data')
)); ?>

<div class="dialog-content">
<fieldset>

	<?php //begin.Messages ?>
	<div id="ajax-message">
		<?php echo $form->errorSummary($model); ?>
	</div>

	<div class="clearfix">
		<?php echo $form->labelEx($model,'report_id'); ?>
		<div class="desc">
			<?php echo $form->textField($model,'report_id',array('maxlength'=>11)); ?>
			<?php echo $form->error($model,'report_id'); ?>
			<?php /*<div class="small-px silent"></div>*/?>
		</div>
	</div>

	<div class="clearfix">
		<?php echo $form->labelEx($model,'user_id'); ?>
		<div class="desc">
			<?php echo $form->textField($model,'user_id',array('maxlength'=>11)); ?>
			<?php echo $form->error($model,'user_id'); ?>
			<?php /*<div class="small-px silent"></div>*/?>
		</div>
	</div>

	<div class="clearfix">
		<?php echo $form->labelEx($model,'report_message'); ?>
		<div class="desc">
			<?php echo $form->textArea($model,'report_message',array('rows'=>6, 'cols'=>50,'class'=>'span-11')); ?>
			<?php echo $form->error($model,'report_message'); ?>
			<?php /*<div class="small-px silent"></div>*/?>
		</div>
	</div>

	<div class="clearfix">
		<?php echo $form->labelEx($model,'report_date'); ?>
		<div class="desc">
			<?php echo $form->textField($model,'report_date'); ?>
			<?php echo $form->error($model,'report_date'); ?>
			<?php /*<div class="small-px silent"></div>*/?>
		</div>
	</div>

	<div class="clearfix">
		<?php echo $form->labelEx($model,'report_ip'); ?>
		<div class="desc">
			<?php echo $form->textField($model,'report_ip',array('maxlength'=>20)); ?>
			<?php echo $form->error($model,'report_ip'); ?>
			<?php /*<div class="small-px silent"></div>*/?>
		</div>
	</div>
	<?php //begin.Messages ?>

	<div class="clearfix publish">
		<?php echo $form->labelEx($model,'status'); ?>
		<div class="desc">
			<?php echo $form->checkBox($model,'status'); ?>
			<?php echo $form->labelEx($model,'status'); ?>
			<?php echo $form->error($model,'status'); ?>
			<?php /*<div class="small-px silent"></div>*/?>
		</div>
	</div>

</fieldset>
</div>
<div class="dialog-submit">
	<?php echo CHtml::submitButton($model->isNewRecord ? Yii::t('phrase', 'Create') : Yii::t('phrase', 'Save') ,array('onclick' => 'setEnableSave()')); ?>
	<?php echo CHtml::button(Yii::t('phrase', 'Cancel'), array('id'=>'closed')); ?>
</div>
<?php $this->endWidget(); ?>


