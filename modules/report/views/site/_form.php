<?php
/**
 * @var $this SitesController
 * @var $model Reports
 * @var $form CActiveForm
 *
 * @author Putra Sudaryanto <putra.sudaryanto@gmail.com>
 * @copyright Copyright (c) 2014 Ommu Platform (ommu.co)
 * @link http://company.ommu.co
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
					echo $form->dropDownList($model,'cat_id', array('prompt'=>Phrase::trans(12020,1)));
				}?>
				<?php echo $form->error($model,'cat_id'); ?>
			</div>
		</div>

		<div class="clearfix">
			<?php echo $form->labelEx($model,'url'); ?>
			<div class="desc">
				<?php 
				$model->url = $_GET['url'];
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

	</fieldset>
</div>
<div class="dialog-submit">
	<?php echo CHtml::submitButton($model->isNewRecord ? Phrase::trans(12015,0) : Phrase::trans(2,0), array('onclick' => 'setEnableSave()')); ?>
	<?php echo CHtml::button('Closed', array('id'=>'closed')); ?>
</div>

<?php $this->endWidget(); ?>

