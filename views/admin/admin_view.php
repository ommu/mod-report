<?php
/**
 * Reports (reports)
 * @var $this app\components\View
 * @var $this ommu\report\controllers\AdminController
 * @var $model ommu\report\models\Reports
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2017 OMMU (www.ommu.co)
 * @created date 19 September 2017, 22:58 WIB
 * @modified date 17 January 2019, 11:38 WIB
 * @link https://github.com/ommu/mod-report
 *
 */

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;

$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Reports'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $model->report_body;

$this->params['menu']['content'] = [
	['label' => Yii::t('app', 'Detail'), 'url' => Url::to(['view', 'id'=>$model->report_id]), 'icon' => 'eye', 'htmlOptions' => ['class'=>'btn btn-success']],
	['label' => Yii::t('app', 'Update'), 'url' => Url::to(['update', 'id'=>$model->report_id]), 'icon' => 'pencil', 'htmlOptions' => ['class'=>'btn btn-primary']],
	['label' => Yii::t('app', 'Delete'), 'url' => Url::to(['delete', 'id'=>$model->report_id]), 'htmlOptions' => ['data-confirm'=>Yii::t('app', 'Are you sure you want to delete this item?'), 'data-method'=>'post', 'class'=>'btn btn-danger'], 'icon' => 'trash'],
];
?>

<div class="reports-view">

<?php echo DetailView::widget([
	'model' => $model,
	'options' => [
		'class'=>'table table-striped detail-view',
	],
	'attributes' => [
		'report_id',
		[
			'attribute' => 'status',
			'value' => $model->quickAction(Url::to(['status', 'id'=>$model->primaryKey]), $model->status, 'Resolved,Unresolved'),
			'format' => 'raw',
		],
		[
			'attribute' => 'cat_id',
			'value' => isset($model->category) ? $model->category->title->message : '-',
		],
		[
			'attribute' => 'userDisplayname',
			'value' => isset($model->user) ? $model->user->displayname : '-',
		],
		[
			'attribute' => 'report_url',
			'value' => $model->report_url ? $model->report_url : '-',
		],
		[
			'attribute' => 'report_body',
			'value' => $model->report_body ? $model->report_body : '-',
		],
		[
			'attribute' => 'report_message',
			'value' => $model->report_message ? $model->report_message : '-',
			'format' => 'html',
		],
		[
			'attribute' => 'report_date',
			'value' => Yii::$app->formatter->asDatetime($model->report_date, 'medium'),
		],
		'report_ip',
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
			'attribute' => 'reports',
			'value' => function ($model) {
				$reports = $model->reports;
				return Html::a($reports, ['history/admin/manage', 'report'=>$model->primaryKey], ['title'=>Yii::t('app', '{count} reports', ['count'=>$reports])]);
			},
			'format' => 'html',
		],
		[
			'attribute' => 'comments',
			'value' => function ($model) {
				$comments = $model->getComments(true);
				return Html::a($comments, ['history/comment/manage', 'report'=>$model->primaryKey, 'publish'=>1], ['title'=>Yii::t('app', '{count} comments', ['count'=>$comments])]);
			},
			'format' => 'html',
		],
		[
			'attribute' => 'statuses',
			'value' => function ($model) {
				$statuses = $model->getStatuses(true);
				return Html::a($statuses, ['history/status/manage', 'report'=>$model->primaryKey], ['title'=>Yii::t('app', '{count} statuses', ['count'=>$statuses])]);
			},
			'format' => 'html',
		],
		[
			'attribute' => 'users',
			'value' => function ($model) {
				$users = $model->getUsers(true);
				return Html::a($users, ['history/user/manage', 'report'=>$model->primaryKey, 'publish'=>1], ['title'=>Yii::t('app', '{count} users', ['count'=>$users])]);
			},
			'format' => 'html',
		],
	],
]) ?>

</div>