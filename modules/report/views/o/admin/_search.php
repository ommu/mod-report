<?php
/**
 * Reports (reports)
 * @var $this AdminController
 * @var $model Reports
 * @var $form CActiveForm
 * version: 0.0.1
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @copyright Copyright (c) 2014 Ommu Platform (opensource.ommu.co)
 * @link https://github.com/ommu/Report
 * @contact (+62)856-299-4114
 *
 */
?>

<?php $form=$this->beginWidget('application.components.system.OActiveForm', array(
	'action'=>Yii::app()->createUrl($this->route),
	'method'=>'get',
)); ?>
	<ul>
		<li>
			<?php echo $model->getAttributeLabel('report_id'); ?><br/>
			<?php echo $form->textField($model,'report_id',array('size'=>11,'maxlength'=>11)); ?>
		</li>

		<li>
			<?php echo $model->getAttributeLabel('status'); ?><br/>
			<?php echo $form->textField($model,'status'); ?>
		</li>

		<li>
			<?php echo $model->getAttributeLabel('cat_id'); ?><br/>
			<?php echo $form->textField($model,'cat_id'); ?>
		</li>

		<li>
			<?php echo $model->getAttributeLabel('user_id'); ?><br/>
			<?php echo $form->textField($model,'user_id',array('size'=>11,'maxlength'=>11)); ?>
		</li>

		<li>
			<?php echo $model->getAttributeLabel('url'); ?><br/>
			<?php echo $form->textField($model,'url',array('size'=>60,'maxlength'=>255)); ?>
		</li>

		<li>
			<?php echo $model->getAttributeLabel('report_body'); ?><br/>
			<?php echo $form->textArea($model,'report_body',array('rows'=>6, 'cols'=>50)); ?>
		</li>

		<li>
			<?php echo $model->getAttributeLabel('report_date'); ?><br/>
			<?php echo $form->textField($model,'report_date'); ?>
		</li>

		<li>
			<?php echo $model->getAttributeLabel('report_ip'); ?><br/>
			<?php echo $form->textField($model,'report_ip',array('size'=>20,'maxlength'=>20)); ?>
		</li>

		<li>
			<?php echo $model->getAttributeLabel('report_message'); ?><br/>
			<?php echo $form->textField($model,'report_message',array('size'=>20,'maxlength'=>20)); ?>
		</li>

		<li>
			<?php echo $model->getAttributeLabel('resolved_date'); ?><br/>
			<?php echo $form->textField($model,'resolved_date'); ?>
		</li>

		<li>
			<?php echo $model->getAttributeLabel('resolved_id'); ?><br/>
			<?php echo $form->textField($model,'resolved_id',array('size'=>20,'maxlength'=>20)); ?>
		</li>

		<li>
			<?php echo $model->getAttributeLabel('unresolved_date'); ?><br/>
			<?php echo $form->textField($model,'unresolved_date'); ?>
		</li>

		<li>
			<?php echo $model->getAttributeLabel('unresolved_id'); ?><br/>
			<?php echo $form->textField($model,'unresolved_id',array('size'=>20,'maxlength'=>20)); ?>
		</li>

		<li class="submit">
			<?php echo CHtml::submitButton(Yii::t('phrase', 'Search')); ?>
		</li>
	</ul>
	<div class="clear"></div>
<?php $this->endWidget(); ?>
