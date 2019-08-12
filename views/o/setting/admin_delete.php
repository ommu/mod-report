<?php
/**
 * Report Settings (report-setting)
 * @var $this SettingController
 * @var $model ReportSetting
 * @var $form CActiveForm
 *
 * @author Putra Sudaryanto <putra@ommu.co>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2018 Ommu Platform (www.ommu.co)
 * @created date 23 July 2018, 14:13 WIB
 * @modified date 23 July 2018, 14:13 WIB
 * @link https://github.com/ommu/mod-report
 *
 */

	$this->breadcrumbs=array(
		Yii::t('phrase', 'Report')=>array('o/admin/manage'),
		Yii::t('phrase', 'Setting')=>array('edit'),
		Yii::t('phrase', 'Delete'),
	);
?>

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'report-setting-form',
	'enableAjaxValidation'=>true,
)); ?>

	<div class="dialog-content">
		<?php echo Yii::t('phrase', 'Are you sure you want to delete this item?');?>
	</div>
	<div class="dialog-submit">
		<?php echo CHtml::submitButton(Yii::t('phrase', 'Delete'), array('onclick' => 'setEnableSave()')); ?>
		<?php echo CHtml::button(Yii::t('phrase', 'Cancel'), array('id'=>'closed')); ?>
	</div>
	
<?php $this->endWidget(); ?>
