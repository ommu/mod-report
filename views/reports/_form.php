<?php
/**
 * Reports (reports)
 * @var $this yii\web\View
 * @var $this app\modules\report\controllers\ReportsController
 * @var $model app\modules\report\models\Reports
 * @var $form yii\widgets\ActiveForm
 * version: 0.0.1
 *
 * @copyright Copyright (c) 2017 ECC UGM (ecc.ft.ugm.ac.id)
 * @link http://ecc.ft.ugm.ac.id
 * @author Aziz Masruhan <aziz.masruhan@gmail.com>
 * @created date 22 September 2017, 15:57 WIB
 * @contact (+62)857-4115-5177
 *
 */

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\redactor\widgets\Redactor;
use yii\helpers\ArrayHelper;
use app\modules\report\models\ReportCategory;


$redactorOptions = [
	'imageManagerJson' => ['/redactor/upload/image-json'],
	'imageUpload'	  => ['/redactor/upload/image'],
	'fileUpload'	   => ['/redactor/upload/file'],
	'plugins'		  => ['clips', 'fontcolor','imagemanager']
];
?>

<?php $form = ActiveForm::begin([
	'options' => [
		'class' => 'form-horizontal form-label-left',
		//'enctype' => 'multipart/form-data',
	],
]); ?>

<?php echo $form->field($model, 'status', ['template' => '{label}<div class="col-md-6 col-sm-6 col-xs-12 checkbox">{input}{error}</div>'])
	->checkbox(['label'=>''])
	->label($model->getAttributeLabel('status'), ['class'=>'control-label col-md-3 col-sm-3 col-xs-12']); ?>

<!-- <?php echo $form->field($model, 'cat_id', ['template' => '{label}<div class="col-md-6 col-sm-6 col-xs-12">{input}{error}</div>'])
	->textInput()
	->label($model->getAttributeLabel('cat_id'), ['class'=>'control-label col-md-3 col-sm-3 col-xs-12']); ?> -->

<?php echo $form->field($model,'cat_id', ['template' => '{label}<div class="col-md-6 col-sm-6 col-xs-12">{input}{error}</div>'])
	->textInput()
	->label($model->getAttributeLabel('cat_id'), ['class'=>'control-label col-md-3 col-sm-3 col-xs-12'])->dropDownList( ArrayHelper::map (ReportCategory::find()->where(['publish'=>1])->all(),'cat_id','name'),
    ['prompt'=>'-- Choose a Category --']	) ?>


<!-- <?php echo $form->field($model, 'user_id', ['template' => '{label}<div class="col-md-6 col-sm-6 col-xs-12">{input}{error}</div>'])
	->textInput(['maxlength' => true])
	->label($model->getAttributeLabel('user_id'), ['class'=>'control-label col-md-3 col-sm-3 col-xs-12']); ?> -->

<?php echo $form->field($model, 'report_url', ['template' => '{label}<div class="col-md-6 col-sm-6 col-xs-12">{input}{error}</div>'])
	->textarea(['rows'=>2,'rows'=>6])
	->widget(Redactor::className(), ['clientOptions' => $redactorOptions])
	->label($model->getAttributeLabel('report_url'), ['class'=>'control-label col-md-3 col-sm-3 col-xs-12']); ?>

<?php echo $form->field($model, 'report_body', ['template' => '{label}<div class="col-md-6 col-sm-6 col-xs-12">{input}{error}</div>'])
	->textarea(['rows'=>2,'rows'=>6])
	->widget(Redactor::className(), ['clientOptions' => $redactorOptions])
	->label($model->getAttributeLabel('report_body'), ['class'=>'control-label col-md-3 col-sm-3 col-xs-12']); ?>

<?php echo $form->field($model, 'report_message', ['template' => '{label}<div class="col-md-6 col-sm-6 col-xs-12">{input}{error}</div>'])
	->textarea(['rows'=>2,'rows'=>6])
	->widget(Redactor::className(), ['clientOptions' => $redactorOptions])
	->label($model->getAttributeLabel('report_message'), ['class'=>'control-label col-md-3 col-sm-3 col-xs-12']); ?>

<?php echo $form->field($model, 'reports', ['template' => '{label}<div class="col-md-6 col-sm-6 col-xs-12">{input}{error}</div>'])
	->textInput()
	->label($model->getAttributeLabel('reports'), ['class'=>'control-label col-md-3 col-sm-3 col-xs-12']); ?>

<!-- <?php echo $form->field($model, 'report_ip', ['template' => '{label}<div class="col-md-6 col-sm-6 col-xs-12">{input}{error}</div>'])
	->textInput(['maxlength' => true])
	->label($model->getAttributeLabel('report_ip'), ['class'=>'control-label col-md-3 col-sm-3 col-xs-12']); ?> -->

<div class="ln_solid"></div>
<div class="form-group">
	<div class="col-md-9 col-sm-9 col-xs-12 col-md-offset-3">
		<?php echo Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']); ?>
	</div>
</div>

<?php ActiveForm::end(); ?>