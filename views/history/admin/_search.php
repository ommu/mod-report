<?php
/**
 * Report Histories (report-history)
 * @var $this AdminController
 * @var $model ReportHistory
 * @var $form CActiveForm
 *
 * @author Putra Sudaryanto <putra@ommu.co>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2017 Ommu Platform (www.ommu.co)
 * @created date 24 August 2017, 14:01 WIB
 * @modified date 23 July 2018, 12:46 WIB
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
			<?php echo $model->getAttributeLabel('report_date'); ?>
			<?php echo $this->filterDatepicker($model, 'report_date', false); ?>
		</li>

		<li>
			<?php echo $model->getAttributeLabel('report_ip'); ?>
			<?php echo $form->textField($model, 'report_ip', array('maxlength'=>20, 'class'=>'form-control')); ?>
		</li>

		<li class="submit">
			<?php echo CHtml::submitButton(Yii::t('phrase', 'Search')); ?>
		</li>
	</ul>
<?php $this->endWidget(); ?>