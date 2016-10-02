<?php
/**
 * Reports (reports)
 * @var $this AdminController
 * @var $model Reports
 * @var $form CActiveForm
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @copyright Copyright (c) 2014 Ommu Platform (ommu.co)
 * @link https://github.com/oMMu/Ommu-Report
 * @contact (+62)856-299-4114
 *
 */
?>

<?php $form=$this->beginWidget('application.components.system.OActiveForm', array(
	'id'=>'reports-form',
	'enableAjaxValidation'=>true,
	//'htmlOptions' => array('enctype' => 'multipart/form-data')
)); ?>
<div class="dialog-content">

	<fieldset>

		<div id="ajax-message">
			<?php echo $form->errorSummary($model); ?>
		</div>

		<div class="clearfix">
			<?php echo $form->labelEx($model,'cat_id'); ?>
			<div class="desc">
				<?php if(ReportCategory::getCategory(1) != null) {
					echo $form->dropDownList($model,'cat_id', ReportCategory::getCategory(1));
				} else {
					echo $form->dropDownList($model,'cat_id', array('prompt'=>Yii::t('phrase', 'No Parent')));
				}?>
				<?php echo $form->error($model,'cat_id'); ?>
			</div>
		</div>

		<div class="clearfix">
			<?php echo $form->labelEx($model,'url'); ?>
			<div class="desc">
				<?php 
				$model->url = "http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
				echo $form->textField($model,'url',array('maxlength'=>255, 'class'=>'span-11')); ?>
				<?php echo $form->error($model,'url'); ?>
			</div>
		</div>

		<div class="clearfix">
			<?php echo $form->labelEx($model,'body'); ?>
			<div class="desc">
				<?php echo $form->textArea($model,'body',array('rows'=>6, 'cols'=>50, 'class'=>'span-11 smaller')); ?>
				<?php echo $form->error($model,'body'); ?>
			</div>
		</div>

		<div class="clearfix publish">
			<?php echo $form->labelEx($model,'status'); ?>
			<div class="desc">
				<?php echo $form->checkBox($model,'status'); ?>
				<?php echo $form->labelEx($model,'status'); ?>
				<?php echo $form->error($model,'status'); ?>
			</div>
		</div>

	</fieldset>
</div>
<div class="dialog-submit">
	<?php echo CHtml::submitButton($model->isNewRecord ? Yii::t('phrase', 'Create') : Yii::t('phrase', 'Save'), array('onclick' => 'setEnableSave()')); ?>
	<?php echo CHtml::button('Closed', array('id'=>'closed')); ?>
</div>

<?php $this->endWidget(); ?>

