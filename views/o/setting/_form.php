<?php
/**
 * Report Settings (report-setting)
 * @var $this SettingController
 * @var $model ReportSetting
 * @var $form CActiveForm
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2017 Ommu Platform (www.ommu.co)
 * @created date 24 August 2017, 14:41 WIB
 * @modified date 23 July 2018, 14:08 WIB
 * @link https://github.com/ommu/mod-report
 *
 */

	$cs = Yii::app()->getClientScript();
$js=<<<EOP
	$('#ReportSetting_auto_report_i').on('change', function() {
		var id = $(this).prop('checked');
		if(id == true) {
			$('div#auto-report-category').slideDown();
		} else {
			$('div#auto-report-category').slideUp();
		}
	});
EOP;
	$cs->registerScript('auto-report', $js, CClientScript::POS_END);
?>

<?php $form=$this->beginWidget('application.libraries.yii-traits.system.OActiveForm', array(
	'id'=>'report-setting-form',
	'enableAjaxValidation'=>true,
	/*
	'htmlOptions' => array(
		'enctype' => 'multipart/form-data',
		'on_post' => '',
	),
	'enableClientValidation'=>true,
	'clientOptions'=>array(
		'validateOnSubmit'=>true,
	),
	*/
)); ?>

	<?php //begin.Messages ?>
	<div id="ajax-message">
		<?php echo $form->errorSummary($model); ?>
	</div>
	<?php //begin.Messages ?>

	<fieldset>

		<div class="form-group row">
			<label class="col-form-label col-lg-4 col-md-3 col-sm-12">
				<?php echo $model->getAttributeLabel('license');?> <span class="required">*</span><br/>
				<span><?php echo Yii::t('phrase', 'Format: XXXX-XXXX-XXXX-XXXX');?></span>
			</label>
			<div class="col-lg-8 col-md-9 col-sm-12">
				<?php
				if($model->isNewRecord || (!$model->isNewRecord && $model->license == '')) {
					$model->license = $this->licenseCode();
					echo $form->textField($model, 'license', array('maxlength'=>32, 'class'=>'form-control'));
				} else
					echo $form->textField($model, 'license', array('maxlength'=>32, 'class'=>'form-control', 'disabled'=>'disabled'));?>
				<?php echo $form->error($model, 'license'); ?>
				<span class="small-px"><?php echo Yii::t('phrase', 'Enter the your license key that is provided to you when you purchased this plugin. If you do not know your license key, please contact support team.');?></span>
			</div>
		</div>

		<div class="form-group row">
			<?php echo $form->labelEx($model, 'permission', array('class'=>'col-form-label col-lg-4 col-md-3 col-sm-12')); ?>
			<div class="col-lg-8 col-md-9 col-sm-12">
				<span class="small-px"><?php echo Yii::t('phrase', 'Select whether or not you want to let the public (visitors that are not logged-in) to view the following sections of your social network. In some cases (such as Profiles, Blogs, and Albums), if you have given them the option, your users will be able to make their pages private even though you have made them publically viewable here. For more permissions settings, please visit the General Settings page.');?></span>
				<?php 
				if($model->isNewRecord && !$model->getErrors())
					$model->permission = 1;
				echo $form->radioButtonList($model, 'permission', array(
					1 => Yii::t('phrase', 'Yes, the public can view report unless they are made private.'),
					0 => Yii::t('phrase', 'No, the public cannot view report.'),
				), array('class'=>'form-control')); ?>
				<?php echo $form->error($model, 'permission'); ?>
			</div>
		</div>

		<div class="form-group row">
			<?php echo $form->labelEx($model, 'meta_description', array('class'=>'col-form-label col-lg-4 col-md-3 col-sm-12')); ?>
			<div class="col-lg-8 col-md-9 col-sm-12">
				<?php echo $form->textArea($model, 'meta_description', array('rows'=>6, 'cols'=>50, 'class'=>'form-control smaller')); ?>
				<?php echo $form->error($model, 'meta_description'); ?>
			</div>
		</div>

		<div class="form-group row">
			<?php echo $form->labelEx($model, 'meta_keyword', array('class'=>'col-form-label col-lg-4 col-md-3 col-sm-12')); ?>
			<div class="col-lg-8 col-md-9 col-sm-12">
				<?php echo $form->textArea($model, 'meta_keyword', array('rows'=>6, 'cols'=>50, 'class'=>'form-control smaller')); ?>
				<?php echo $form->error($model, 'meta_keyword'); ?>
			</div>
		</div>

		<div class="form-group row publish">
			<?php echo $form->labelEx($model, 'auto_report_i', array('class'=>'col-form-label col-lg-4 col-md-3 col-sm-12')); ?>
			<div class="col-lg-8 col-md-9 col-sm-12">
				<?php echo $form->checkBox($model, 'auto_report_i', array('class'=>'form-control')); ?>
				<?php echo $form->labelEx($model, 'auto_report_i'); ?>
				<?php echo $form->error($model, 'auto_report_i'); ?>
			</div>
		</div>

		<div class="form-group row <?php echo $model->auto_report_cat_id ? '' : 'hide'?>" id="auto-report-category">
			<?php echo $form->labelEx($model, 'auto_report_cat_id', array('class'=>'col-form-label col-lg-4 col-md-3 col-sm-12')); ?>
			<div class="col-lg-8 col-md-9 col-sm-12">
				<?php 
				$category = ReportCategory::getCategory(1);
				if($category != null)
					echo $form->dropDownList($model, 'auto_report_cat_id', $category, array('prompt'=>'', 'class'=>'form-control'));
				else
					echo $form->dropDownList($model, 'auto_report_cat_id', array('prompt'=>''), array('class'=>'form-control'));?>
				<?php echo $form->error($model, 'auto_report_cat_id'); ?>
			</div>
		</div>

		<div class="form-group row submit">
			<label class="col-form-label col-lg-4 col-md-3 col-sm-12">&nbsp;</label>
			<div class="col-lg-8 col-md-9 col-sm-12">
				<?php echo CHtml::submitButton($model->isNewRecord ? Yii::t('phrase', 'Create') : Yii::t('phrase', 'Save'), array('onclick' => 'setEnableSave()')); ?>
			</div>
		</div>

	</fieldset>
<?php $this->endWidget(); ?>