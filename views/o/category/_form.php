<?php
/**
 * Report Category (report-category)
 * @var $this CategoryController
 * @var $model ReportCategory
 * @var $form CActiveForm
 * version: 0.0.1
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @copyright Copyright (c) 2014 Ommu Platform (opensource.ommu.co)
 * @link https://github.com/ommu/ommu-report
 * @contact (+62)856-299-4114
 *
 */
?>

<?php $form=$this->beginWidget('application.libraries.core.components.system.OActiveForm', array(
	'id'=>'report-category-form',
	'enableAjaxValidation'=>true,
	//'htmlOptions' => array('enctype' => 'multipart/form-data')
)); ?>
<div class="dialog-content">
	<fieldset>

		<div id="ajax-message">
			<?php echo $form->errorSummary($model); ?>
		</div>

		<div class="form-group row">
			<?php echo $form->labelEx($model,'name_i', array('class'=>'col-form-label col-lg-4 col-md-3 col-sm-12')); ?>
			<div class="col-lg-8 col-md-9 col-sm-12">
				<?php echo $form->textField($model,'name_i',array('maxlength'=>64,'class'=>'form-control')); ?>
				<?php echo $form->error($model,'name_i'); ?>
			</div>
		</div>

		<div class="form-group row">
			<?php echo $form->labelEx($model,'desc_i', array('class'=>'col-form-label col-lg-4 col-md-3 col-sm-12')); ?>
			<div class="col-lg-8 col-md-9 col-sm-12">
				<?php echo $form->textArea($model,'desc_i',array('rows'=>6, 'cols'=>50, 'class'=>'form-control smaller')); ?>
				<?php echo $form->error($model,'desc_i'); ?>
			</div>
		</div>

		<div class="form-group row">
			<?php echo $form->labelEx($model,'publish', array('class'=>'col-form-label col-lg-4 col-md-3 col-sm-12')); ?>
			<div class="col-lg-8 col-md-9 col-sm-12">
				<?php echo $form->checkBox($model,'publish', array('class'=>'form-control')); ?>
				<?php echo $form->error($model,'publish'); ?>
			</div>
		</div>
 	</fieldset>
</div>
<div class="dialog-submit">
	<?php echo CHtml::submitButton($model->isNewRecord ? Yii::t('phrase', 'Create') : Yii::t('phrase', 'Save'), array('onclick' => 'setEnableSave()')); ?>
	<?php echo CHtml::button('Closed', array('id'=>'closed')); ?>
</div>
<?php $this->endWidget(); ?>

