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
 * @link https://github.com/ommu/mod-report
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
			<?php echo $form->textField($model,'report_id'); ?>
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
			<?php echo $form->textField($model,'user_id'); ?>
		</li>

		<li>
			<?php echo $model->getAttributeLabel('report_url'); ?><br/>
			<?php echo $form->textField($model,'report_url'); ?>
		</li>

		<li>
			<?php echo $model->getAttributeLabel('report_body'); ?><br/>
			<?php echo $form->textArea($model,'report_body'); ?>
		</li>

		<li>
			<?php echo $model->getAttributeLabel('report_message'); ?><br/>
			<?php echo $form->textField($model,'report_message'); ?>
		</li>

		<li>
			<?php echo $model->getAttributeLabel('reports'); ?><br/>
			<?php echo $form->textField($model,'reports'); ?>
		</li>

		<li>
			<?php echo $model->getAttributeLabel('report_date'); ?><br/>
			<?php echo $form->textField($model,'report_date'); ?>
		</li>

		<li>
			<?php echo $model->getAttributeLabel('report_ip'); ?><br/>
			<?php echo $form->textField($model,'report_ip'); ?>
		</li>

		<li>
			<?php echo $model->getAttributeLabel('modified_date'); ?><br/>
			<?php echo $form->textField($model,'modified_date'); ?>
		</li>

		<li>
			<?php echo $model->getAttributeLabel('modified_id'); ?><br/>
			<?php echo $form->textField($model,'modified_id'); ?>
		</li>

		<li>
			<?php echo $model->getAttributeLabel('updated_date'); ?><br/>
			<?php echo $form->textField($model,'updated_date'); ?>
		</li>

		<li class="submit">
			<?php echo CHtml::submitButton(Yii::t('phrase', 'Search')); ?>
		</li>
	</ul>
	<div class="clear"></div>
<?php $this->endWidget(); ?>
