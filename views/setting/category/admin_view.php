<?php
/**
 * Report Categories (report-category)
 * @var $this app\components\View
 * @var $this ommu\report\controllers\setting\CategoryController
 * @var $model ommu\report\models\ReportCategory
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2017 OMMU (www.ommu.co)
 * @created date 22 September 2017, 16:13 WIB
 * @modified date 16 January 2019, 16:25 WIB
 * @link https://github.com/ommu/mod-report
 *
 */

use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\DetailView;

$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Categories'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $model->title->message;

$this->params['menu']['content'] = [
	['label' => Yii::t('app', 'Back To Manage'), 'url' => Url::to(['setting/admin/index']), 'icon' => 'table'],
	['label' => Yii::t('app', 'Detail'), 'url' => Url::to(['view', 'id'=>$model->cat_id]), 'icon' => 'eye'],
	['label' => Yii::t('app', 'Update'), 'url' => Url::to(['update', 'id'=>$model->cat_id]), 'icon' => 'pencil'],
	['label' => Yii::t('app', 'Delete'), 'url' => Url::to(['delete', 'id'=>$model->cat_id]), 'htmlOptions' => ['data-confirm'=>Yii::t('app', 'Are you sure you want to delete this item?'), 'data-method'=>'post'], 'icon' => 'trash'],
];
?>

<div class="report-category-view">

<?php echo DetailView::widget([
	'model' => $model,
	'options' => [
		'class'=>'table table-striped detail-view',
	],
	'attributes' => [
		'cat_id',
		[
			'attribute' => 'publish',
			'value' => $this->quickAction(Url::to(['publish', 'id'=>$model->primaryKey]), $model->publish, 'Enable,Disable'),
			'format' => 'raw',
		],
		[
			'attribute' => 'name_i',
			'value' => $model->name_i,
		],
		[
			'attribute' => 'desc_i',
			'value' => $model->desc_i,
		],
		[
			'attribute' => 'creation_date',
			'value' => Yii::$app->formatter->asDatetime($model->creation_date, 'medium'),
		],
		[
			'attribute' => 'creationDisplayname',
			'value' => isset($model->creation) ? $model->creation->displayname : '-',
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
			'attribute' => 'updated_date',
			'value' => Yii::$app->formatter->asDatetime($model->updated_date, 'medium'),
		],
		[
			'attribute' => 'slug',
			'value' => $model->slug ? $model->slug : '-',
		],
		[
			'attribute' => 'unresolved',
			'value' => Html::a($model->unresolved, ['admin/manage', 'category'=>$model->primaryKey, 'status' => 0], ['title'=>Yii::t('app', '{count} unresolved', ['count'=>$model->unresolved])]),
			'format' => 'html',
		],
		[
			'attribute' => 'resolved',
			'value' => Html::a($model->resolved, ['admin/manage', 'category'=>$model->primaryKey, 'status' => 1], ['title'=>Yii::t('app', '{count} resolved', ['count'=>$model->resolved])]),
			'format' => 'html',
		],
		[
			'attribute' => 'reports',
			'value' => Html::a($model->reports, ['admin/manage', 'category'=>$model->primaryKey], ['title'=>Yii::t('app', '{count} reports', ['count'=>$model->reports])]),
			'format' => 'html',
		],
	],
]) ?>

</div>