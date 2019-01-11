<?php
/**
 * Reports (reports)
 * @var $this yii\web\View
 * @var $this ommu\report\controllers\AdminController
 * @var $model ommu\report\models\search\Reports
 * @var $form yii\widgets\ActiveForm
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2017 OMMU (www.ommu.co)
 * @created date 19 September 2017, 22:58 WIB
 * @modified date 25 April 2018, 17:15 WIB
 * @link https://github.com/ommu/mod-report
 *
 */

use yii\helpers\Html;
use app\components\ActiveForm;
?>

<div class="search-form">
	<?php $form = ActiveForm::begin([
		'action' => ['index'],
		'method' => 'get',
	]); ?>
		<?php echo $form->field($model, 'status')->checkbox();?>

		<?php echo $form->field($model, 'category_search');?>

		<?php echo $form->field($model, 'reporter_search');?>

		<?php echo $form->field($model, 'report_url');?>

		<?php echo $form->field($model, 'report_body');?>

		<?php echo $form->field($model, 'report_message');?>

		<?php echo $form->field($model, 'reports');?>

		<?php echo $form->field($model, 'report_date')
			->input('date');?>

		<?php echo $form->field($model, 'report_ip');?>

		<?php echo $form->field($model, 'modified_date')
			->input('date');?>

		<?php echo $form->field($model, 'modified_search');?>

		<?php echo $form->field($model, 'updated_date')
			->input('date');?>

		<div class="form-group">
			<?php echo Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
			<?php echo Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-default']) ?>
		</div>
	<?php ActiveForm::end(); ?>
</div>
