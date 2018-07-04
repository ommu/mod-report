<?php
/**
 * Report Comments (report-comment)
 * @var $this CommentController
 * @var $model ReportComment
 * @var $form CActiveForm
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2017 Ommu Platform (www.ommu.co)
 * @created date 22 February 2017, 12:25 WIB
 * @modified date 18 January 2018, 13:37 WIB
 * @link https://github.com/ommu/mod-report
 *
 */
?>

<?php $form=$this->beginWidget('application.libraries.core.components.system.OActiveForm', array(
	'id'=>'report-comment-form',
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

<div class="dialog-content">
	<fieldset>
		<?php //begin.Messages ?>
		<div id="ajax-message">
			<?php echo $form->errorSummary($model); ?>
		</div>
		<?php //begin.Messages ?>

		<div class="form-group row">
			<?php echo $form->labelEx($model, 'report_id', array('class'=>'col-form-label col-lg-4 col-md-3 col-sm-12')); ?>
			<div class="col-lg-8 col-md-9 col-sm-12">
				<?php echo $form->textField($model, 'report_id', array('maxlength'=>11, 'class'=>'form-control')); ?>
				<?php echo $form->error($model, 'report_id'); ?>
			</div>
		</div>

		<div class="form-group row">
			<?php echo $form->labelEx($model, 'comment_text', array('class'=>'col-form-label col-lg-4 col-md-3 col-sm-12')); ?>
			<div class="col-lg-8 col-md-9 col-sm-12">
				<?php //echo $form->textArea($model, 'comment_text', array('rows'=>6, 'cols'=>50, 'class'=>'form-control'));
				$this->widget('yiiext.imperavi-redactor-widget.ImperaviRedactorWidget', array(
					'model'=>$model,
					'attribute'=>'comment_text',
					'options'=>array(
						'buttons'=>array(
							'html', 'formatting', '|', 
							'bold', 'italic', 'deleted', '|',
							'unorderedlist', 'orderedlist', 'outdent', 'indent', '|',
							'link', '|',
						),
					),
					'plugins' => array(
						'fontcolor' => array('js' => array('fontcolor.js')),
						'table' => array('js' => array('table.js')),
						'fullscreen' => array('js' => array('fullscreen.js')),
					),
					'htmlOptions'=>array(
						'class' => 'form-control',
					),
				)); ?>
				<?php echo $form->error($model, 'comment_text'); ?>
			</div>
		</div>

		<div class="form-group row publish">
			<?php echo $form->labelEx($model, 'publish', array('class'=>'col-form-label col-lg-4 col-md-3 col-sm-12')); ?>
			<div class="col-lg-8 col-md-9 col-sm-12">
				<?php echo $form->checkBox($model, 'publish', array('class'=>'form-control')); ?>
				<?php echo $form->labelEx($model, 'publish'); ?>
				<?php echo $form->error($model, 'publish'); ?>
			</div>
		</div>

	</fieldset>
</div>
<div class="dialog-submit">
	<?php echo CHtml::submitButton($model->isNewRecord ? Yii::t('phrase', 'Create') : Yii::t('phrase', 'Save'), array('onclick' => 'setEnableSave()')); ?>
	<?php echo CHtml::button(Yii::t('phrase', 'Cancel'), array('id'=>'closed')); ?>
</div>
<?php $this->endWidget(); ?>