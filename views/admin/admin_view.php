<?php
/**
 * Reports (reports)
 * @var $this yii\web\View
 * @var $this app\modules\report\controllers\AdminController
 * @var $model app\modules\report\models\Reports
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2017 ECC UGM (ecc.ft.ugm.ac.id)
 * @created date 19 September 2017, 22:58 WIB
 * @modified date 25 April 2018, 17:15 WIB
 * @link http://ecc.ft.ugm.ac.id
 *
 */

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;

$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Reports'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

$this->params['menu']['content'] = [
	['label' => Yii::t('app', 'Back To Manage'), 'url' => Url::to(['index']), 'icon' => 'table'],
	['label' => Yii::t('app', 'Update'), 'url' => Url::to(['update', 'id' => $model->report_id]), 'icon' => 'pencil'],
	['label' => Yii::t('app', 'Delete'), 'url' => Url::to(['delete', 'id' => $model->report_id]), 'confirm' => Yii::t('app', 'Are you sure you want to delete this item?'), 'method' => 'post', 'icon' => 'trash'],
];
?>

<?php echo DetailView::widget([
	'model' => $model,
	'options' => [
		'class'=>'table table-striped detail-view',
	],
	'attributes' => [
		[
			'attribute' => 'status',
			'value' => $model->status == 1 ? Yii::t('app', 'Resolved') : Yii::t('app', 'Unresolved'),
		],
		[
			'attribute' => 'category_search',
			'value' => isset($model->category) ? $model->category->title->message : '-',
		],
		[
			'attribute' => 'user_search',
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
		'reports',
		[
			'attribute' => 'report_date',
			'value' => !in_array($model->report_date, ['0000-00-00 00:00:00','1970-01-01 00:00:00','0002-12-02 00:00:00','-0001-11-30 00:00:00']) ? Yii::$app->formatter->format($model->report_date, 'datetime') : '-',
		],
		'report_ip',
		[
			'attribute' => 'modified_date',
			'value' => !in_array($model->modified_date, ['0000-00-00 00:00:00','1970-01-01 00:00:00','0002-12-02 00:00:00','-0001-11-30 00:00:00']) ? Yii::$app->formatter->format($model->modified_date, 'datetime') : '-',
		],
		[
			'attribute' => 'modified_search',
			'value' => isset($model->modified) ? $model->modified->displayname : '-',
		],
		[
			'attribute' => 'updated_date',
			'value' => !in_array($model->updated_date, ['0000-00-00 00:00:00','1970-01-01 00:00:00','0002-12-02 00:00:00','-0001-11-30 00:00:00']) ? Yii::$app->formatter->format($model->updated_date, 'datetime') : '-',
		],
	],
]) ?>