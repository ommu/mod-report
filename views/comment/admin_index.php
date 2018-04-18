<?php
/**
 * Report Comments (report-comment)
 * @var $this yii\web\View
 * @var $this app\modules\report\controllers\CommentController
 * @var $model app\modules\report\models\ReportComment
 * version: 0.0.1
 *
 * @copyright Copyright (c) 2017 ECC UGM (ecc.ft.ugm.ac.id)
 * @link http://ecc.ft.ugm.ac.id
 * @author Aziz Masruhan <aziz.masruhan@gmail.com>
 * @created date 22 September 2017, 13:54 WIB
 * @contact (+62)857-4115-5177
 *
 */

use yii\helpers\Html;
use yii\helpers\Url;
use app\libraries\MenuContent;
use app\libraries\MenuOption;
use app\libraries\grid\GridView;
use yii\widgets\Pjax;

$this->params['breadcrumbs'][] = $this->title;

$this->params['menu']['content'] = [
	['label' => Yii::t('app', 'Add Report Comment'), 'url' => Url::to(['create']), 'icon' => 'plus-square'],
];
$this->params['menu']['option'] = [
	// ['label' => Yii::t('app', 'Search'), 'url' => 'javascript:void(0);'],
	['label' => Yii::t('app', 'Grid Options'), 'url' => 'javascript:void(0);'],
];
?>

<?php Pjax::begin(); ?>
<div class="col-md-12 col-sm-12 col-xs-12">
	<div class="x_panel">
		<div class="x_title">
			<h2><?php echo Html::encode($this->title); ?></h2>
			<?php if($this->params['menu']['content']):
			echo MenuContent::widget(['items' => $this->params['menu']['content']]);
			endif;?>
			<ul class="nav navbar-right panel_toolbox">
				<li><a href="#" title="<?php echo Yii::t('app', 'Toggle');?>" class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>
				<?php if($this->params['menu']['option']):?>
				<li class="dropdown">
					<a href="#" title="<?php echo Yii::t('app', 'Options');?>" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"><i class="fa fa-wrench"></i></a>
					<?php echo MenuOption::widget(['items' => $this->params['menu']['option']]);?>
				</li>
				<?php endif;?>
				<li><a href="#" title="<?php echo Yii::t('app', 'Close');?>" class="close-link"><i class="fa fa-close"></i></a></li>
			</ul>
			<div class="clearfix"></div>
		</div>
		<div class="x_content">
			<?php echo $this->description != '' ? "<p class=\"text-muted font-13 m-b-30\">$this->description</p>" : '';?>

			<?php // echo $this->render('_search', ['model' => $searchModel]); ?>

			<?php echo $this->render('_option_form', ['model' => $searchModel, 'gridColumns' => GridView::getActiveDefaultColumns($columns), 'route' => $this->context->route]); ?>

			<?php 
			$columnData = $columns;
			array_push($columnData, [
				'class' => 'yii\grid\ActionColumn',
				'header' => Yii::t('app', 'Options'),
				'contentOptions' => [
					'class'=>'action-column',
				],
				'buttons' => [
					'view' => function ($url, $model, $key) {
						$url = Url::to(['view', 'id' => $model->primaryKey]);
						return Html::a('<span class="glyphicon glyphicon-eye-open"></span>', $url, ['title' => Yii::t('app', 'View Report Comment')]);
					},
					'update' => function ($url, $model, $key) {
						$url = Url::to(['update', 'id' => $model->primaryKey]);
						return Html::a('<span class="glyphicon glyphicon-pencil"></span>', $url, ['title' => Yii::t('app', 'Update Report Comment')]);
					},
					'delete' => function ($url, $model, $key) {
						$url = Url::to(['delete', 'id' => $model->primaryKey]);
						return Html::a('<span class="glyphicon glyphicon-trash"></span>', $url, [
							'title' => Yii::t('app', 'Delete Report Comment'),
							'data-confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
							'data-method'  => 'post',
						]);
					},
				],
				'template' => '{view}{update}{delete}',
			]);
			
			echo GridView::widget([
				'dataProvider' => $dataProvider,
				'filterModel' => $searchModel,
				'layout' => '<div class="row"><div class="col-sm-12">{items}</div></div><div class="row sum-page"><div class="col-sm-5">{summary}</div><div class="col-sm-7">{pager}</div></div>',
				'columns' => $columnData,
			]); ?>
		</div>
	</div>
</div>
<?php Pjax::end(); ?>