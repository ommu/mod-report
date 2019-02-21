<?php
/**
 * Report Settings (report-setting)
 * @var $this app\components\View
 * @var $this ommu\report\controllers\setting\AdminController
 * @var $model ommu\report\models\ReportSetting
 * @var $form app\components\ActiveForm
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2017 OMMU (www.ommu.co)
 * @created date 22 September 2017, 13:49 WIB
 * @modified date 25 April 2018, 15:36 WIB
 * @link https://github.com/ommu/mod-report
 *
 */

use yii\helpers\Html;
use yii\helpers\Url;
use app\components\widgets\GridView;
use yii\widgets\Pjax;
use yii\helpers\ArrayHelper;
use app\components\widgets\MenuContent;
use app\components\widgets\MenuOption;
use yii\widgets\DetailView;
use ommu\report\models\ReportSetting;

$this->params['breadcrumbs'][] = Yii::t('app', 'Report Settings');

$this->params['menu']['content'] = [
	['label' => Yii::t('app', 'Add Category'), 'url' => Url::to(['setting/category/create']), 'htmlOptions' => ['class'=>'modal-btn'], 'icon' => 'plus-square'],
];
$this->params['menu']['option'] = [
	//['label' => Yii::t('app', 'Search'), 'url' => 'javascript:void(0);'],
	['label' => Yii::t('app', 'Grid Option'), 'url' => 'javascript:void(0);'],
];
?>

<div class="row">
	<div class="col-md-12 col-sm-12 col-xs-12">
		<div class="x_panel">
			<div class="x_title">
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
<?php Pjax::begin(); ?>

<?php //echo $this->render('/setting/category/_search', ['model'=>$searchModel]); ?>

<?php echo $this->render('/setting/category/_option_form', ['model'=>$searchModel, 'gridColumns'=>$this->activeDefaultColumns($columns), 'route'=>$this->context->route]); ?>

<?php 
$columnData = $columns;
array_push($columnData, [
	'class' => 'yii\grid\ActionColumn',
	'header' => Yii::t('app', 'Option'),
	'contentOptions' => [
		'class'=>'action-column',
	],
	'buttons' => [
		'view' => function ($url, $model, $key) {
			$url = Url::to(ArrayHelper::merge(['setting/category/view', 'id'=>$model->primaryKey], Yii::$app->request->get()));
			return Html::a('<span class="glyphicon glyphicon-eye-open"></span>', $url, ['title' => Yii::t('app', 'Detail Category')]);
		},
		'update' => function ($url, $model, $key) {
			$url = Url::to(ArrayHelper::merge(['setting/category/update', 'id'=>$model->primaryKey], Yii::$app->request->get()));
			return Html::a('<span class="glyphicon glyphicon-pencil"></span>', $url, ['title' => Yii::t('app', 'Update Category'), 'class'=>'modal-btn']);
		},
		'delete' => function ($url, $model, $key) {
			$url = Url::to(['setting/category/delete', 'id'=>$model->primaryKey]);
			return Html::a('<span class="glyphicon glyphicon-trash"></span>', $url, [
				'title' => Yii::t('app', 'Delete Category'),
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

<?php Pjax::end(); ?>
			</div>
		</div>
	</div>
</div>

<div class="row">
	<div class="col-md-12 col-sm-12 col-xs-12">
		<?php if(Yii::$app->session->hasFlash('success'))
			echo $this->flashMessage(Yii::$app->session->getFlash('success'));
		else if(Yii::$app->session->hasFlash('error'))
			echo $this->flashMessage(Yii::$app->session->getFlash('error'), 'danger');?>

		<div class="x_panel">
			<div class="x_title">
				<ul class="nav navbar-right panel_toolbox">
					<li><a href="#" title="<?php echo Yii::t('app', 'Toggle');?>" class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>
					<li><a href="#" title="<?php echo Yii::t('app', 'Close');?>" class="close-link"><i class="fa fa-close"></i></a></li>
				</ul>
				<div class="clearfix"></div>
			</div>
			<div class="x_content">
<div class="report-setting-index">
<?php if(!$model->isNewRecord) {
	echo DetailView::widget([
		'model' => $model,
		'options' => [
			'class'=>'table table-striped detail-view',
		],
		'attributes' => [
			'id',
			'license',
			[
				'attribute' => 'permission',
				'value' => ReportSetting::getPermission($model->permission),
			],
			[
				'attribute' => 'meta_description',
				'value' => $model->meta_description ? $model->meta_description : '-',
			],
			[
				'attribute' => 'meta_keyword',
				'value' => $model->meta_keyword ? $model->meta_keyword : '-',
			],
			[
				'attribute' => 'auto_report_cat_id',
				'value' => isset($model->category) ? $model->category->title->message : '-',
			],
			[
				'attribute' => 'modified_date',
				'value' => Yii::$app->formatter->asDatetime($model->modified_date, 'medium'),
			],
			[
				'attribute' => 'modifiedDisplayname',
				'value' => isset($model->modified) ? $model->modified->displayname : '-',
			],
			[
				'attribute' => '',
				'value' => Html::a(Yii::t('app', 'Update'), Url::to(['update']), [
					'class' => 'btn btn-success',
				]),
				'format' => 'raw',
			],
		],
	]);
} else {
	echo $this->render('_form', [
		'model' => $model,
	]);
}?>
</div>
			</div>
		</div>
	</div>
</div>