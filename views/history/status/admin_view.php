<?php
/**
 * Report Statuses (report-status)
 * @var $this app\components\View
 * @var $this ommu\report\controllers\history\StatusController
 * @var $model ommu\report\models\ReportStatus
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2017 OMMU (www.ommu.co)
 * @created date 22 September 2017, 16:03 WIB
 * @modified date 18 January 2019, 15:38 WIB
 * @link https://github.com/ommu/mod-report
 *
 */

use yii\helpers\Url;
use yii\widgets\DetailView;

$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Statuses'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $model->report->report_body;

$this->params['menu']['content'] = [
	['label' => Yii::t('app', 'Delete'), 'url' => Url::to(['delete', 'id'=>$model->history_id]), 'htmlOptions' => ['data-confirm'=>Yii::t('app', 'Are you sure you want to delete this item?'), 'data-method'=>'post'], 'icon' => 'trash'],
];
?>

<div class="report-status-view">

<?php echo DetailView::widget([
	'model' => $model,
	'options' => [
		'class'=>'table table-striped detail-view',
	],
	'attributes' => [
		'id',
		[
			'attribute' => 'status',
			'value' => $model->status == 1 ? Yii::t('app', 'Resolved') : Yii::t('app', 'Unresolved'),
		],
		[
			'attribute' => 'categoryId',
			'value' => isset($model->report->category) ? $model->report->category->title->message : '-',
		],
		[
			'attribute' => 'reportBody',
			'value' => isset($model->report) ? $model->report->report_body : '-',
		],
		[
			'attribute' => 'reporterDisplayname',
			'value' => isset($model->user) ? $model->user->displayname : '-',
		],
		[
			'attribute' => 'report_message',
			'value' => $model->report_message ? $model->report_message : '-',
			'format' => 'html',
		],
		[
			'attribute' => 'updated_date',
			'value' => Yii::$app->formatter->asDatetime($model->updated_date, 'medium'),
		],
		'updated_ip',
		[
			'attribute' => 'modified_date',
			'value' => Yii::$app->formatter->asDatetime($model->modified_date, 'medium'),
		],
		[
			'attribute' => 'modifiedDisplayname',
			'value' => isset($model->modified) ? $model->modified->displayname : '-',
		],
	],
]) ?>

</div>