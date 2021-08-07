<?php
/**
 * Report Users (report-user)
 * @var $this app\components\View
 * @var $this ommu\report\controllers\history\UserController
 * @var $model ommu\report\models\ReportUser
 *
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2017 OMMU (www.ommu.id)
 * @created date 22 September 2017, 13:56 WIB
 * @modified date 18 January 2019, 15:38 WIB
 * @link https://github.com/ommu/mod-report
 *
 */

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;

if (!$small) {
    $this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Dashboard'), 'url' => ['/admin/dashboard/index']];
    $this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Reports'), 'url' => ['admin/index']];
    $this->params['breadcrumbs'][] = ['label' => $model->report->report_body, 'url' => ['admin/view', 'id' => $model->report_id]];
    $this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Users'), 'url' => ['history/user/manage', 'report' => $model->report_id]];
    $this->params['breadcrumbs'][] = Yii::t('app', 'Detail');

    $this->params['menu']['content'] = [
        ['label' => Yii::t('app', 'Detail'), 'url' => Url::to(['view', 'id' => $model->id]), 'icon' => 'eye', 'htmlOptions' => ['class' => 'btn btn-info']],
        ['label' => Yii::t('app', 'Delete'), 'url' => Url::to(['delete', 'id' => $model->id]), 'htmlOptions' => ['data-confirm' => Yii::t('app', 'Are you sure you want to delete this item?'), 'data-method' => 'post', 'class' => 'btn btn-danger'], 'icon' => 'trash'],
    ];
} ?>

<div class="report-user-view">

<?php
$attributes = [
	[
		'attribute' => 'id',
		'value' => $model->id ? $model->id : '-',
		'visible' => !$small,
	],
	[
		'attribute' => 'categoryId',
		'value' => isset($model->report->category) ? $model->report->category->title->message : '-',
	],
	[
		'attribute' => 'reportBody',
		'value' => function ($model) {
			$reportBody = isset($model->report) ? $model->report->report_body : '-';
            if ($reportBody != '-') {
                return Html::a($reportBody, ['admin/view', 'id' => $model->report_id], ['title' => $reportBody, 'class' => 'modal-btn']);
            }
			return $reportBody;
		},
		'format' => 'html',
	],
	[
		'attribute' => 'reporterDisplayname',
		'value' => isset($model->user) ? $model->user->displayname : '-',
	],
	[
		'attribute' => 'creation_date',
		'value' => Yii::$app->formatter->asDatetime($model->creation_date, 'medium'),
		'visible' => !$small,
	],
];

echo DetailView::widget([
	'model' => $model,
	'options' => [
		'class' => 'table table-striped detail-view',
	],
	'attributes' => $attributes,
]); ?>

</div>