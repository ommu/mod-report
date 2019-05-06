<?php
/**
 * Report Histories (report-history)
 * @var $this app\components\View
 * @var $this ommu\report\controllers\history\AdminController
 * @var $model ommu\report\models\search\ReportHistory
 * @var $form yii\widgets\ActiveForm
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2017 OMMU (www.ommu.co)
 * @created date 22 September 2017, 13:57 WIB
 * @modified date 18 January 2019, 15:37 WIB
 * @link https://github.com/ommu/mod-report
 *
 */

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use ommu\report\models\ReportCategory;
?>

<div class="report-history-search search-form">

	<?php $form = ActiveForm::begin([
		'action' => ['index'],
		'method' => 'get',
		'options' => [
			'data-pjax' => 1
		],
	]); ?>

		<?php $category = ReportCategory::getCategory();
		echo $form->field($model, 'categoryId')
			->dropDownList($category, ['prompt'=>'']);?>

		<?php echo $form->field($model, 'reportBody');?>

		<?php echo $form->field($model, 'reporterDisplayname');?>

		<?php echo $form->field($model, 'report_date')
			->input('date');?>

		<?php echo $form->field($model, 'report_ip');?>

		<div class="form-group">
			<?php echo Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
			<?php echo Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-default']) ?>
		</div>

	<?php ActiveForm::end(); ?>

</div>