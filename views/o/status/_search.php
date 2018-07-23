<?php
/**
 * Report Statuses (report-status)
 * @var $this StatusController
 * @var $model ReportStatus
 * @var $form CActiveForm
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2017 Ommu Platform (www.ommu.co)
 * @created date 22 February 2017, 12:25 WIB
 * @modified date 23 July 2018, 11:58 WIB
 * @link https://github.com/ommu/mod-report
 *
 */
?>

<?php $form=$this->beginWidget('CActiveForm', array(
	'action'=>Yii::app()->createUrl($this->route),
	'method'=>'get',
)); ?>
	<ul>
		<li>
			<?php echo $model->getAttributeLabel('report_search'); ?>
			<?php echo $form->textField($model, 'report_search', array('class'=>'form-control')); ?>
		</li>

		<li>
			<?php echo $model->getAttributeLabel('user_search'); ?>
			<?php echo $form->textField($model, 'user_search', array('class'=>'form-control')); ?>
		</li>

		<li>
			<?php echo $model->getAttributeLabel('report_message'); ?>
			<?php echo $form->textField($model, 'report_message', array('class'=>'form-control')); ?>
		</li>

		<li>
			<?php echo $model->getAttributeLabel('updated_date'); ?>
			<?php echo $this->filterDatepicker($model, 'updated_date', false); ?>
		</li>

		<li>
			<?php echo $model->getAttributeLabel('updated_ip'); ?>
			<?php echo $form->textField($model, 'updated_ip', array('maxlength'=>20, 'class'=>'form-control')); ?>
		</li>

		<li>
			<?php echo $model->getAttributeLabel('modified_date'); ?>
			<?php echo $this->filterDatepicker($model, 'modified_date', false); ?>
		</li>

		<li>
			<?php echo $model->getAttributeLabel('modified_search'); ?>
			<?php echo $form->textField($model, 'modified_search', array('class'=>'form-control')); ?>
		</li>

		<li>
			<?php echo $model->getAttributeLabel('status'); ?>
			<?php echo $form->dropDownList($model, 'status', array('1'=>Yii::t('phrase', 'Resolved'), '0'=>Yii::t('phrase', 'Unresolved')), array('prompt'=>'', 'class'=>'form-control')); ?>
		</li>

		<li class="submit">
			<?php echo CHtml::submitButton(Yii::t('phrase', 'Search')); ?>
		</li>
	</ul>
<?php $this->endWidget(); ?>