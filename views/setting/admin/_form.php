<?php
/**
 * Report Settings (report-setting)
 * @var $this app\components\View
 * @var $this ommu\report\controllers\setting\AdminController
 * @var $model ommu\report\models\ReportSetting
 * @var $form app\components\ActiveForm
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)857-4115-5177
 * @copyright Copyright (c) 2017 OMMU (www.ommu.co)
 * @created date 22 September 2017, 13:49 WIB
 * @modified date 16 January 2019, 11:10 WIB
 * @link https://github.com/ommu/mod-report
 *
 */

use yii\helpers\Html;
use app\components\ActiveForm;
use ommu\report\models\ReportSetting;
use ommu\report\models\ReportCategory;
?>

<div class="report-setting-form">

<?php $form = ActiveForm::begin([
	'options' => ['class'=>'form-horizontal form-label-left'],
	'enableClientValidation' => false,
	'enableAjaxValidation' => false,
	//'enableClientScript' => true,
]); ?>

<?php //echo $form->errorSummary($model);?>

<?php if($model->isNewRecord && !$model->getErrors())
	$model->license = $model->licenseCode();
echo $form->field($model, 'license', ['horizontalCssClasses' => ['wrapper'=>'col-sm-9 col-xs-12 col-12']])
	->textInput(['maxlength'=>true])
	->label($model->getAttributeLabel('license'))
	->hint(Yii::t('app', 'Enter the your license key that is provided to you when you purchased this plugin. If you do not know your license key, please contact support team.').'<br/>'.Yii::t('app', 'Format: XXXX-XXXX-XXXX-XXXX')); ?>

<?php $permission = ReportSetting::getPermission();
echo $form->field($model, 'permission', ['template' => '{label}{beginWrapper}{hint}{input}{error}{endWrapper}', 'horizontalCssClasses' => ['wrapper'=>'col-sm-9 col-xs-12 col-12']])
	->radioList($permission)
	->label($model->getAttributeLabel('permission'))
	->hint(Yii::t('app', 'Select whether or not you want to let the public (visitors that are not logged-in) to view the following sections of your social network. In some cases (such as Profiles, Blogs, and Albums), if you have given them the option, your users will be able to make their pages private even though you have made them publically viewable here. For more permissions settings, please visit the General Settings page.')); ?>

<?php echo $form->field($model, 'meta_description', ['horizontalCssClasses' => ['wrapper'=>'col-sm-9 col-xs-12 col-12']])
	->textarea(['rows'=>6, 'cols'=>50])
	->label($model->getAttributeLabel('meta_description')); ?>

<?php echo $form->field($model, 'meta_keyword', ['horizontalCssClasses' => ['wrapper'=>'col-sm-9 col-xs-12 col-12']])
	->textarea(['rows'=>6, 'cols'=>50])
	->label($model->getAttributeLabel('meta_keyword')); ?>

<?php echo $form->field($model, 'auto_report_i', ['horizontalCssClasses' => ['wrapper'=>'col-sm-9 col-xs-12 col-12']])
	->checkbox()
	->label($model->getAttributeLabel('auto_report_i')); ?>

<?php $category = ReportCategory::getCategory(1);
echo $form->field($model, 'auto_report_cat_id', ['horizontalCssClasses' => ['wrapper'=>'col-sm-9 col-xs-12 col-12']])
	->dropDownList($category, ['prompt'=>''])
	->label($model->getAttributeLabel('auto_report_cat_id')); ?>

<div class="ln_solid"></div>
<div class="form-group row">
	<div class="col-sm-9 col-xs-12 col-12 col-sm-offset-3">
		<?php echo Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']); ?>
	</div>
</div>

<?php ActiveForm::end(); ?>

</div>