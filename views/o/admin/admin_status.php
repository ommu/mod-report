<?php
/**
 * Reports (reports)
 * @var $this AdminController
 * @var $model Reports
 * @var $form CActiveForm
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2014 Ommu Platform (www.ommu.co)
 * @modified date 23 July 2018, 14:39 WIB
 * @link https://github.com/ommu/mod-report
 *
 */

	$this->breadcrumbs=array(
		'Reports'=>array('manage'),
		$model->report_body=>array('view','id'=>$model->report_id),
		'Status',
	);
?>

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'reports-form',
	'enableAjaxValidation'=>true,
)); ?>
<div class="dialog-content">

	<fieldset>

		<?php //begin.Messages ?>
		<div id="ajax-message">
			<?php echo $form->errorSummary($model); ?>
		</div>
		<?php //begin.Messages ?>

		<div class="form-group row">
			<label class="col-form-label col-lg-4 col-md-3 col-sm-12"><?php echo $model->status == 1 ? Yii::t('phrase', 'Are you sure you want to unresolved this item?') : Yii::t('phrase', 'Are you sure you want to resolved this item?')?></label>
			<div class="col-lg-8 col-md-9 col-sm-12">
				<?php //echo $form->textArea($model,'report_message', array('rows'=>6, 'cols'=>50, 'class'=>'span-11 smaller'));
				if(!$model->getErrors())
					$model->report_message = '';
				$this->widget('yiiext.imperavi-redactor-widget.ImperaviRedactorWidget', array(
					'model'=>$model,
					'attribute'=>report_message,
					// Redactor options
					'options'=>array(
						//'lang'=>'fi',
						'buttons'=>array(
							'html', '|', 
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
				<?php echo $form->error($model,'report_message'); ?>
			</div>
		</div>

	</fieldset>
</div>
<div class="dialog-submit">
	<?php echo CHtml::submitButton($title, array('onclick' => 'setEnableSave()')); ?>
	<?php echo CHtml::button(Yii::t('phrase', 'Cancel'), array('id'=>'closed')); ?>
</div>
<?php $this->endWidget(); ?>