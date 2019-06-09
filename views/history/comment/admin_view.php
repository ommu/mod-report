<?php
/**
 * Report Comments (report-comment)
 * @var $this app\components\View
 * @var $this ommu\report\controllers\history\CommentController
 * @var $model ommu\report\models\ReportComment
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2017 OMMU (www.ommu.co)
 * @created date 22 September 2017, 13:54 WIB
 * @modified date 18 January 2019, 15:37 WIB
 * @link https://github.com/ommu/mod-report
 *
 */

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;

$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Comments'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $model->report->report_body;

$this->params['menu']['content'] = [
	['label' => Yii::t('app', 'Delete'), 'url' => Url::to(['delete', 'id'=>$model->comment_id]), 'htmlOptions' => ['data-confirm'=>Yii::t('app', 'Are you sure you want to delete this item?'), 'data-method'=>'post', 'class'=>'btn btn-danger'], 'icon' => 'trash'],
];
?>

<div class="report-comment-view">

<?php
$attributes = [
	'comment_id',
	[
		'attribute' => 'publish',
		'value' => $model->quickAction(Url::to(['publish', 'id'=>$model->primaryKey]), $model->publish),
		'format' => 'raw',
	],
	[
		'attribute' => 'categoryId',
		'value' => isset($model->report->category) ? $model->report->category->title->message : '-',
	],
	[
		'attribute' => 'reportBody',
		'value' => function ($model) {
			$reportBody = isset($model->report) ? $model->report->report_body : '-';
			if($reportBody != '-')
				return Html::a($reportBody, ['admin/view', 'id'=>$model->report_id], ['title'=>$reportBody, 'class'=>'modal-btn']);
			return $reportBody;
		},
		'format' => 'html',
	],
	[
		'attribute' => 'comment_text',
		'value' => $model->comment_text ? $model->comment_text : '-',
	],
	[
		'attribute' => 'userDisplayname',
		'value' => isset($model->user) ? $model->user->displayname : '-',
	],
	[
		'attribute' => 'creation_date',
		'value' => Yii::$app->formatter->asDatetime($model->creation_date, 'medium'),
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
		'attribute' => '',
		'value' => Html::a(Yii::t('app', 'Update'), ['update', 'id'=>$model->id], ['title'=>Yii::t('app', 'Update'), 'class'=>'btn btn-primary']),
		'format' => 'html',
		'visible' => Yii::$app->request->isAjax ? true : false,
	],
];

echo DetailView::widget([
	'model' => $model,
	'options' => [
		'class'=>'table table-striped detail-view',
	],
	'attributes' => $attributes,
]); ?>

</div>