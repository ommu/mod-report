<?php
/**
 * Report Users (report-user)
 * @var $this app\components\View
 * @var $this ommu\report\controllers\UserController
 * @var $model ommu\report\models\search\ReportUser
 * @var $form app\components\ActiveForm
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2017 OMMU (www.ommu.co)
 * @created date 22 September 2017, 13:56 WIB
 * @modified date 26 April 2018, 11:12 WIB
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
		<?php echo $form->field($model, 'publish')->checkbox();?>

		<?php echo $form->field($model, 'report_search');?>

		<?php echo $form->field($model, 'user_search');?>

		<?php echo $form->field($model, 'creation_date')
			->input('date');?>

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
