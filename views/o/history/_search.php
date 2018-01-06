<?php
/**
 * Report Histories (report-history)
 * @var $this HistoryController
 * @var $model ReportHistory
 * @var $form CActiveForm
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2017 Ommu Platform (opensource.ommu.co)
 * @created date 24 August 2017, 14:01 WIB
 * @link https://github.com/ommu/ommu-report
 *
 */
?>

<?php $form=$this->beginWidget('CActiveForm', array(
	'action'=>Yii::app()->createUrl($this->route),
	'method'=>'get',
)); ?>
	<ul>
		<li>
			<?php echo $model->getAttributeLabel('id'); ?><br/>
			<?php echo $form->textField($model,'id'); ?><br/>
					</li>

		<li>
			<?php echo $model->getAttributeLabel('report_id'); ?><br/>
			<?php echo $form->textField($model,'report_id'); ?><br/>
					</li>

		<li>
			<?php echo $model->getAttributeLabel('user_id'); ?><br/>
			<?php echo $form->textField($model,'user_id'); ?><br/>
					</li>

		<li>
			<?php echo $model->getAttributeLabel('report_date'); ?><br/>
			<?php echo $form->textField($model,'report_date'); ?><br/>
					</li>

		<li>
			<?php echo $model->getAttributeLabel('report_ip'); ?><br/>
			<?php echo $form->textField($model,'report_ip'); ?><br/>
					</li>

		<li class="submit">
			<?php echo CHtml::submitButton(Yii::t('phrase', 'Search')); ?>
		</li>
	</ul>
<?php $this->endWidget(); ?>
