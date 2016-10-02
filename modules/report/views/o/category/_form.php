<?php
/**
 * Report Category (report-category)
 * @var $this CategoryController
 * @var $model ReportCategory
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
	'id'=>'report-category-form',
	'enableAjaxValidation'=>true,
	//'htmlOptions' => array('enctype' => 'multipart/form-data')
)); ?>
<div class="dialog-content">
	<fieldset>

		<div id="ajax-message">
			<?php echo $form->errorSummary($model); ?>
		</div>

		<div class="clearfix">
			<?php echo $form->labelEx($model,'dependency'); ?>
			<div class="desc">
				<?php if(ReportCategory::getCategory() != null) {
					echo $form->dropDownList($model,'dependency', ReportCategory::getCategory(), array('prompt'=>Yii::t('phrase', 'No Parent')));
				} else {
					echo $form->dropDownList($model,'dependency', array('prompt'=>Yii::t('phrase', 'No Parent')));
				}?>
				<?php echo $form->error($model,'dependency'); ?>
			</div>
		</div>

		<div class="clearfix">
			<?php echo $form->labelEx($model,'title'); ?>
			<div class="desc">
				<?php 
				$model->title = Phrase::trans($model->name, 2);
				echo $form->textField($model,'title',array('maxlength'=>64,'class'=>'span-8')); ?>
				<?php echo $form->error($model,'title'); ?>
			</div>
		</div>

		<div class="clearfix">
			<?php echo $form->labelEx($model,'description'); ?>
			<div class="desc">
				<?php 
				$model->description = Phrase::trans($model->desc, 2);
				echo $form->textArea($model,'description',array('rows'=>6, 'cols'=>50, 'class'=>'span-11 smaller')); ?>
				<?php echo $form->error($model,'description'); ?>
			</div>
		</div>

		<div class="clearfix publish">
			<?php echo $form->labelEx($model,'publish'); ?>
			<div class="desc">
				<?php echo $form->checkBox($model,'publish'); ?>
				<?php echo $form->labelEx($model,'publish'); ?>
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

