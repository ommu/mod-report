<?php
/**
 * Report Reads (report-read)
 * @var $this app\components\View
 * @var $this ommu\report\controllers\history\ReadController
 * @var $model ommu\report\models\search\ReportRead
 * @var $form yii\widgets\ActiveForm
 *
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)811-2540-432
 * @copyright Copyright (c) 2022 OMMU (www.ommu.id)
 * @created date 28 August 2022, 09:25 WIB
 * @link https://github.com/ommu/mod-report
 *
 */

use yii\helpers\Html;
use yii\widgets\ActiveForm;
?>

<div class="report-read-search search-form">

	<?php $form = ActiveForm::begin([
		'action' => ['index'],
		'method' => 'get',
		'options' => [
			'data-pjax' => 1
		],
	]); ?>

		<?php echo $form->field($model, 'reportBody');?>

		<?php echo $form->field($model, 'userDisplayname');?>

		<?php echo $form->field($model, 'creation_date')
			->input('date');?>

		<div class="form-group">
			<?php echo Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']); ?>
			<?php echo Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-default']); ?>
		</div>

	<?php ActiveForm::end(); ?>

</div>