<?php
/**
 * Report Histories (report-history)
 * @var $this yii\web\View
 * @var $this app\modules\report\controllers\HistoryController
 * @var $model app\modules\report\models\ReportHistory
 *
 * @author Aziz Masruhan <aziz.masruhan@gmail.com>
 * @contact (+62)857-4115-5177
 * @copyright Copyright (c) 2017 ECC UGM (ecc.ft.ugm.ac.id)
 * @created date 22 September 2017, 13:57 WIB
 * @modified date 26 April 2018, 06:34 WIB
 * @modified by Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @link http://ecc.ft.ugm.ac.id
 *
 */

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;

$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Report Histories'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

$this->params['menu']['content'] = [
	['label' => Yii::t('app', 'Back To Manage'), 'url' => Url::to(['index']), 'icon' => 'table'],
	['label' => Yii::t('app', 'Delete'), 'url' => Url::to(['delete', 'id' => $model->id]), 'confirm' => Yii::t('app', 'Are you sure you want to delete this item?'), 'method' => 'post', 'icon' => 'trash'],
];
?>

<?php echo DetailView::widget([
	'model' => $model,
	'options' => [
		'class'=>'table table-striped detail-view',
	],
	'attributes' => [
		[
			'attribute' => 'category_search',
			'value' => isset($model->report->category) ? $model->report->category->title->message : '-',
		],
		[
			'attribute' => 'report_search',
			'value' => isset($model->report) ? $model->report->report_body : '-',
		],
		[
			'attribute' => 'reporter_search',
			'value' => isset($model->user) ? $model->user->displayname : '-',
		],
		[
			'attribute' => 'report_date',
			'value' => !in_array($model->report_date, ['0000-00-00 00:00:00','1970-01-01 00:00:00','0002-12-02 00:00:00','-0001-11-30 00:00:00']) ? Yii::$app->formatter->format($model->report_date, 'datetime') : '-',
		],
		'report_ip',
	],
]) ?>