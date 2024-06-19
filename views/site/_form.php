<?php
/**
 * Reports (reports)
 * @var $this app\components\View
 * @var $this ommu\report\controllers\SiteController
 * @var $model ommu\report\models\Reports
 * @var $form app\components\widgets\ActiveForm
 *
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)811-2540-432
 * @copyright Copyright (c) 2019 OMMU (www.ommu.id)
 * @created date 7 June 2019, 07:11 WIB
 * @link https://github.com/ommu/mod-report
 *
 */

use yii\helpers\Html;
use app\components\widgets\ActiveForm;
use yii\redactor\widgets\Redactor;
use ommu\report\models\ReportCategory;

$redactorOptions = [
	'buttons' => ['html', 'format', 'bold', 'italic', 'deleted'],
	'plugins' => ['fontcolor', 'imagemanager']
];
?>

<div class="reports-form">

<?php $form = ActiveForm::begin([
	'options' => ['class' => 'form-horizontal form-label-left'],
	'enableClientValidation' => false,
	'enableAjaxValidation' => false,
	//'enableClientScript' => true,
	'fieldConfig' => [
		'errorOptions' => [
			'encode' => false,
		],
	],
]); ?>

<?php echo $form->errorSummary($model);?>

<?php $model->report_url = Yii::$app->request->get('url') ? Yii::$app->request->get('url') : '';
echo $form->field($model, 'report_url')
	->textInput()
	->label($model->getAttributeLabel('report_url')); ?>

<?php $category = ReportCategory::getCategory(1);
echo $form->field($model, 'cat_id')
	->dropDownList($category, ['prompt' => ''])
	->label($model->getAttributeLabel('cat_id')); ?>

<?php $model->report_body = Yii::$app->request->get('message') ? Yii::$app->request->get('message') : '';
echo $form->field($model, 'report_body')
	->textarea(['rows' => 6, 'cols' => 50])
	->widget(Redactor::className(), ['clientOptions' => $redactorOptions])
	->label($model->getAttributeLabel('report_body')); ?>

<hr/>

<?php echo $form->field($model, 'submitButton')
	->submitButton(); ?>

<?php ActiveForm::end(); ?>

</div>