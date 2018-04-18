<?php
/**
 * Report Statuses (report-status)
 * @var $this yii\web\View
 * @var $this app\modules\report\controllers\StatusController
 * @var $model app\modules\report\models\search\ReportStatus
 * @var $form yii\widgets\ActiveForm
 * version: 0.0.1
 *
 * @copyright Copyright (c) 2017 ECC UGM (ecc.ft.ugm.ac.id)
 * @link http://ecc.ft.ugm.ac.id
 * @author Aziz Masruhan <aziz.masruhan@gmail.com>
 * @created date 22 September 2017, 16:03 WIB
 * @contact (+62)857-4115-5177
 *
 */

use yii\helpers\Html;
use yii\widgets\ActiveForm;
?>

<div class="search-form">
	<?php $form = ActiveForm::begin([
		'action' => ['index'],
		'method' => 'get',
	]); ?>
		<?= $form->field($model, 'history_id') ?>

		<?= $form->field($model, 'status') ?>

		<?= $form->field($model, 'report_id') ?>

		<?= $form->field($model, 'user_id') ?>

		<?= $form->field($model, 'report_message') ?>

		<?= $form->field($model, 'updated_date') ?>

		<?= $form->field($model, 'updated_ip') ?>

		<?= $form->field($model, 'modified_date') ?>

		<?= $form->field($model, 'modified_id') ?>

		<div class="form-group">
			<?php echo Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
			<?php echo Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-default']) ?>
		</div>
	<?php ActiveForm::end(); ?>
</div>
