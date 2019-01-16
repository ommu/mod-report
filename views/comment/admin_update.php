<?php
/**
 * Report Comments (report-comment)
 * @var $this app\components\View
 * @var $this ommu\report\controllers\CommentController
 * @var $model ommu\report\models\ReportComment
 * @var $form app\components\ActiveForm
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2017 OMMU (www.ommu.co)
 * @created date 22 September 2017, 13:54 WIB
 * @link https://github.com/ommu/mod-report
 *
 */

use yii\helpers\Html;
use yii\helpers\Url;
use app\components\menu\MenuContent;

$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Report Comments'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->comment_id, 'url' => ['view', 'id'=>$model->comment_id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');

$this->params['menu']['content'] = [
	['label' => Yii::t('app', 'Back To Manage'), 'url' => Url::to(['index']), 'icon' => 'table'],
	['label' => Yii::t('app', 'View'), 'url' => Url::to(['view', 'id'=>$model->comment_id]), 'icon' => 'eye'],
	['label' => Yii::t('app', 'Delete'), 'url' => Url::to(['delete', 'id'=>$model->comment_id]), 'htmlOptions' => ['data-confirm'=>Yii::t('app', 'Are you sure you want to delete this item?'), 'data-method'=>'post'], 'icon' => 'trash'],
];
?>

<div class="col-md-12 col-sm-12 col-xs-12">
	<div class="x_panel">
		<div class="x_title">
			<h2><?php echo Html::encode($this->title); ?></h2>
			<?php if($this->params['menu']['content']):
			echo MenuContent::widget(['items' => $this->params['menu']['content']]);
			endif;?>
			<ul class="nav navbar-right panel_toolbox">
				<li><a href="#" title="<?php echo Yii::t('app', 'Toggle');?>" class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>
				<li><a href="#" title="<?php echo Yii::t('app', 'Close');?>" class="close-link"><i class="fa fa-close"></i></a></li>
			</ul>
			<div class="clearfix"></div>
		</div>
		<div class="x_content">
			<?php echo $this->render('_form', [
				'model' => $model,
			]); ?>
		</div>
	</div>
</div>