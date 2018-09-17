<?php
/**
 * Report Categories (report-category)
 * @var $this CategoryController
 * @var $model ReportCategory
 * @var $form CActiveForm
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2014 Ommu Platform (www.ommu.co)
 * @modified date 16 September 2018, 23:55 WIB
 * @link https://github.com/ommu/mod-report
 *
 */

	$this->breadcrumbs=array(
		'Report Categories'=>array('manage'),
		$model->title->message=>array('view','id'=>$model->cat_id),
		'Publish',
	);
?>

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'report-category-form',
	'enableAjaxValidation'=>true,
)); ?>

	<div class="dialog-content">
		<?php echo $model->publish == 1 ? Yii::t('phrase', 'Are you sure you want to disable this item?') : Yii::t('phrase', 'Are you sure you want to enable this item?')?>
	</div>
	<div class="dialog-submit">
		<?php echo CHtml::submitButton($title, array('onclick' => 'setEnableSave()')); ?>
		<?php echo CHtml::button(Yii::t('phrase', 'Cancel'), array('id'=>'closed')); ?>
	</div>
	
<?php $this->endWidget(); ?>