<?php
/**
 * Report Histories (report-history)
 * @var $this yii\web\View
 * @var $this app\modules\report\controllers\HistoryController
 * @var $model app\modules\report\models\search\ReportHistory
 * @var $form yii\widgets\ActiveForm
 *
 * @author Aziz Masruhan <aziz.masruhan@gmail.com>
 * @contact (+62)857-4115-5177
 * @copyright Copyright (c) 2017 ECC UGM (ecc.ft.ugm.ac.id)
 * @created date 22 September 2017, 13:57 WIB
 * @modified date 26 April 2018, 06:34 WIB
 * @modified by Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @link http://ecc.ft.ugm.ac.id
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
		<?php echo $form->field($model, 'report_search');?>

		<?php echo $form->field($model, 'user_search');?>

		<?php echo $form->field($model, 'report_date')
			->input('date');?>

		<?php echo $form->field($model, 'report_ip');?>

		<div class="form-group">
			<?php echo Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
			<?php echo Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-default']) ?>
		</div>
	<?php ActiveForm::end(); ?>
</div>
