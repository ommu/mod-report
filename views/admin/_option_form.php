<?php
/**
 * Reports (reports)
 * @var $this yii\web\View
 * @var $this app\modules\report\controllers\AdminController
 * @var $model app\modules\report\models\search\Reports
 * @var $form yii\widgets\ActiveForm
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2017 ECC UGM (ecc.ft.ugm.ac.id)
 * @created date 19 September 2017, 22:58 WIB
 * @modified date 25 April 2018, 17:15 WIB
 * @link http://ecc.ft.ugm.ac.id
 *
 */

use app\helpers\Html;
use yii\helpers\Url;

$js = <<<JS
	$('form[name="gridoption"] :checkbox').click(function() {
		var url = $('form[name="gridoption"]').attr('action');
		var data = $('form[name="gridoption"] :checked').serialize();
		$.ajax({
			url: url,
			data: data,
			success: function(response) {
				//$("#w0").yiiGridView("applyFilter");
				//$.pjax({container: '#w0'});
				return false;
			}
		});
	});
JS;
	$this->registerJs($js, \yii\web\View::POS_READY);
?>

<div class="grid-form">
	<?php echo Html::beginForm(Url::to(['/'.$route]), 'get', ['name' => 'gridoption']);
		$columns = [];
		foreach($model->templateColumns as $key => $column) {
			if($key == '_no')
				continue;
			$columns[$key] = $key;
		}
	?>
		<ul>
			<?php foreach($columns as $key => $val): ?> 
			<li>
				<?php echo Html::checkBox(sprintf("GridColumn[%s]", $key), in_array($key, $gridColumns) ? true : false, ['id'=>'GridColumn_'.$key]); ?>
				<?php echo Html::label($model->getAttributeLabel($val), 'GridColumn_'.$val); ?>
			</li>
			<?php endforeach; ?>
		</ul>
	<?php echo Html::endForm(); ?>
</div>