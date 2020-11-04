<?php
/**
 * Reports (reports)
 * @var $this app\components\View
 * @var $this ommu\report\controllers\AdminController
 * @var $model ommu\report\models\Reports
 * @var $form yii\widgets\ActiveForm
 *
 * @copyright Copyright (c) 2017 OMMU (www.ommu.id)
 * @link https://github.com/ommu/mod-report
 * @author Putra Sudaryanto <putra@ommu.id>
 * @created date 19 September 2017, 22:58 WIB
 * @contact (+62)856-299-4114
 *
 */

use yii\helpers\Html;
use yii\helpers\Url;
use app\components\widgets\ActiveForm;
use yii\redactor\widgets\Redactor;
use yii\widgets\DetailView;

$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Dashboard'), 'url' => ['/admin/dashboard/index']];
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Reports'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->report_body, 'url' => ['view', 'id'=>$model->report_id]];
$this->params['breadcrumbs'][] = $title;

$redactorOptions = [
	'imageManagerJson' => ['/redactor/upload/image-json'],
	'imageUpload' => ['/redactor/upload/image'],
	'fileUpload' => ['/redactor/upload/file'],
	'plugins' => ['fontcolor', 'imagemanager'],
	'buttons' => ['html', 'format', 'bold', 'italic', 'deleted'],
];
?>

<?php
$attributes = [
	[
		'attribute' => 'app',
		'value' => $model->app,
	],
	[
		'attribute' => 'cat_id',
		'value' => isset($model->category) ? $model->category->title->message : '-',
	],
	[
		'attribute' => 'report_url',
		'value' => function ($model) {
            if ($model->report_url && $model->report_url != '-') {
                return Html::a($model->report_url, $model->report_url, ['title'=>$model->report_url, 'target'=>'_blank']);
            }
			return '-';
		},
		'format' => 'raw',
	],
	[
		'attribute' => 'report_body',
		'value' => $model->report_body ? $model->report_body : '-',
		'format' => 'html',
	],
	[
		'attribute' => 'reporterDisplayname',
		'value' => isset($model->user) ? $model->user->displayname : '-',
		'visible' => !$small,
	],
	[
		'attribute' => 'report_date',
		'value' => Yii::$app->formatter->asDatetime($model->report_date, 'medium'),
		'visible' => !$small,
	],
];

echo DetailView::widget([
	'model' => $model,
	'options' => [
		'class'=>'table table-striped detail-view',
	],
	'template' => '<tr><th{captionOptions} class="active">{label}</th><td{contentOptions}>{value}</td></tr>',
	'attributes' => $attributes,
]); ?>

<?php $form = ActiveForm::begin([
	'options' => ['class'=>'form-horizontal form-label-left'],
	'enableClientValidation' => false,
	'enableAjaxValidation' => false,
	//'enableClientScript' => true,
	'fieldConfig' => [
		'errorOptions' => [
			'encode' => false,
		],
	],
]); ?>

<?php //echo $form->errorSummary($model);?>

<?php 
if (!$model->getErrors()) {
    $model->report_message = '';
}
echo $form->field($model, 'report_message')
	->textarea(['rows'=>6, 'cols'=>50])
	->widget(Redactor::className(), ['clientOptions' => $redactorOptions])
	->label($model->getAttributeLabel('report_message')); ?>

<hr/>

<?php echo $form->field($model, 'submitButton')
	->submitButton(); ?>

<?php ActiveForm::end(); ?>