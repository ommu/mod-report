<?php
/**
 * Report Comments (report-comment)
 * @var $this CommentController
 * @var $model ReportComment
 * @var $form CActiveForm
 *
 * @author Putra Sudaryanto <putra@ommu.co>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2017 Ommu Platform (www.ommu.co)
 * @created date 22 February 2017, 12:25 WIB
 * @modified date 18 January 2018, 13:37 WIB
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
			<?php echo $model->getAttributeLabel('category_search'); ?>
			<?php echo $form->dropDownList($model, 'category_search', ReportCategory::getCategory(), array('prompt'=>'', 'class'=>'form-control')); ?>
		</li>

		<li>
			<?php echo $model->getAttributeLabel('report_search'); ?>
			<?php echo $form->textField($model, 'report_search', array('class'=>'form-control')); ?>
		</li>

		<li>
			<?php echo $model->getAttributeLabel('user_search'); ?>
			<?php echo $form->textField($model, 'user_search', array('class'=>'form-control')); ?>
		</li>

		<li>
			<?php echo $model->getAttributeLabel('comment_text'); ?>
			<?php echo $form->textField($model, 'comment_text', array('class'=>'form-control')); ?>
		</li>

		<li>
			<?php echo $model->getAttributeLabel('creation_date'); ?>
			<?php echo $this->filterDatepicker($model, 'creation_date', false); ?>
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
			<?php echo $model->getAttributeLabel('updated_date'); ?>
			<?php echo $this->filterDatepicker($model, 'updated_date', false); ?>
		</li>

		<li>
			<?php echo $model->getAttributeLabel('publish'); ?>
			<?php echo $form->dropDownList($model, 'publish', $this->filterYesNo(), array('prompt'=>'', 'class'=>'form-control')); ?>
		</li>

		<li class="submit">
			<?php echo CHtml::submitButton(Yii::t('phrase', 'Search')); ?>
		</li>
	</ul>
<?php $this->endWidget(); ?>