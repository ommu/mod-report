<?php
/**
 * Reports (reports)
 * @var $this AdminController
 * @var $model Reports
 * @var $form CActiveForm
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2014 Ommu Platform (opensource.ommu.co)
 * @modified date 18 January 2018, 13:38 WIB
 * @link https://github.com/ommu/mod-report
 *
 */
?>

<div class="dialog-content">
<?php $form=$this->beginWidget('application.libraries.core.components.system.OActiveForm', array(
	'id'=>'reports-form',
	'enableAjaxValidation'=>true,
	/*
	'enableClientValidation'=>true,
	'clientOptions'=>array(
		'validateOnSubmit'=>true,
	),
	'htmlOptions' => array(
		'enctype' => 'multipart/form-data',
	),
	*/
)); ?>
	<fieldset>

		<?php //begin.Messages ?>
		<div id="ajax-message">
			<?php echo $form->errorSummary($model); ?>
		</div>
		<?php //begin.Messages ?>

		<div class="form-group row">
			<?php echo $form->labelEx($model, 'cat_id', array('class'=>'col-form-label col-lg-4 col-md-3 col-sm-12')); ?>
			<div class="col-lg-8 col-md-9 col-sm-12">
				<?php 
				$category = ReportCategory::getCategory(1);
				if($category != null) {
					echo $form->dropDownList($model, 'cat_id', $category, array('class'=>'form-control'));
				} else {
					echo $form->dropDownList($model, 'cat_id', array('prompt'=>Yii::t('phrase', 'No Parent')), array('class'=>'form-control'));
				}?>
				<?php echo $form->error($model, 'cat_id'); ?>
			</div>
		</div>

		<div class="form-group row">
			<?php echo $form->labelEx($model, 'report_url', array('class'=>'col-form-label col-lg-4 col-md-3 col-sm-12')); ?>
			<div class="col-lg-8 col-md-9 col-sm-12">
				<?php //$model->report_url = "http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
				echo $form->textField($model, 'report_url', array('class'=>'form-control')); ?>
				<?php echo $form->error($model, 'report_url'); ?>
			</div>
		</div>

		<div class="form-group row">
			<?php echo $form->labelEx($model, 'report_body', array('class'=>'col-form-label col-lg-4 col-md-3 col-sm-12')); ?>
			<div class="col-lg-8 col-md-9 col-sm-12">
				<?php //echo $form->textArea($model, 'report_body', array('rows'=>6, 'cols'=>50, 'class'=>'form-control'));
				$this->widget('yiiext.imperavi-redactor-widget.ImperaviRedactorWidget', array(
					'model'=>$model,
					'attribute'=>'report_body',
					'options'=>array(
						'buttons'=>array(
							'html', 'formatting', '|', 
							'bold', 'italic', 'deleted', '|',
						),
					),
					'plugins' => array(
						'fontcolor' => array('js' => array('fontcolor.js')),
						'fullscreen' => array('js' => array('fullscreen.js')),
					),
					'htmlOptions'=>array(
						'class' => 'form-control',
					),
				));?>
				<?php echo $form->error($model, 'report_body'); ?>
			</div>
		</div>

		<?php if(!$model->isNewRecord) {?>
		<div class="form-group row publish">
			<?php echo $form->labelEx($model, 'status', array('class'=>'col-form-label col-lg-4 col-md-3 col-sm-12')); ?>
			<div class="col-lg-8 col-md-9 col-sm-12">
				<?php echo $form->checkBox($model, 'status', array('class'=>'form-control')); ?>
				<?php echo $form->labelEx($model, 'status'); ?>
				<?php echo $form->error($model, 'status'); ?>
			</div>
		</div>
		<?php }?>

	</fieldset>

</div>
<div class="dialog-submit">
	<?php echo CHtml::submitButton($model->isNewRecord ? Yii::t('phrase', 'Create') : Yii::t('phrase', 'Save'), array('onclick' => 'setEnableSave()')); ?>
	<?php echo CHtml::button(Yii::t('phrase', 'Cancel'), array('id'=>'closed')); ?>
</div>
<?php $this->endWidget(); ?>