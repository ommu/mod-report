<?php
/**
 * Reports (reports)
 * @var $this app\components\View
 * @var $this ommu\report\controllers\AdminController
 * @var $model ommu\report\models\Reports
 * @var $form app\components\ActiveForm
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2017 OMMU (www.ommu.co)
 * @created date 19 September 2017, 22:58 WIB
 * @modified date 17 January 2019, 11:38 WIB
 * @link https://github.com/ommu/mod-report
 *
 */

use yii\helpers\Html;
use app\components\ActiveForm;
use yii\redactor\widgets\Redactor;
use ommu\report\models\ReportCategory;

$redactorOptions = [
	'buttons' => ['html', 'format', 'bold', 'italic', 'deleted'],
	'plugins' => ['fontcolor','imagemanager']
];
?>

<div class="reports-form">

<?php $form = ActiveForm::begin([
	'options' => ['class'=>'form-horizontal form-label-left'],
	'enableClientValidation' => true,
	'enableAjaxValidation' => false,
	//'enableClientScript' => true,
]); ?>

<?php //echo $form->errorSummary($model);?>

<?php $category = ReportCategory::getCategory(1);
echo $form->field($model, 'cat_id')
	->dropDownList($category, ['prompt'=>''])
	->label($model->getAttributeLabel('cat_id')); ?>

<?php echo $form->field($model, 'report_url')
	->textInput()
	->label($model->getAttributeLabel('report_url')); ?>

<?php echo $form->field($model, 'report_body')
	->textarea(['rows'=>6, 'cols'=>50])
	->widget(Redactor::className(), ['clientOptions' => $redactorOptions])
	->label($model->getAttributeLabel('report_body')); ?>

<?php if(!$model->isNewRecord) {
	$status = [
		1 => Yii::t('app', 'Resolved'),
		0 => Yii::t('app', 'Unresolved'),
	];
	echo $form->field($model, 'status')
		->radioList($status)
		->label($model->getAttributeLabel('status'));
} ?>

<div class="ln_solid"></div>
<div class="form-group row">
	<div class="col-md-6 col-sm-9 col-xs-12 col-12 col-sm-offset-3">
		<?php echo Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']); ?>
	</div>
</div>

<?php ActiveForm::end(); ?>

</div>