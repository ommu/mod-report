<?php
/**
 * Report Settings (report-setting)
 * @var $this app\components\View
 * @var $this ommu\report\controllers\setting\AdminController
 * @var $model ommu\report\models\ReportSetting
 * @var $form yii\widgets\ActiveForm
 *
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)811-2540-432
 * @copyright Copyright (c) 2017 OMMU (www.ommu.id)
 * @created date 22 September 2017, 13:49 WIB
 * @modified date 25 April 2018, 15:36 WIB
 * @link https://github.com/ommu/mod-report
 *
 */

use yii\helpers\Html;
use yii\helpers\Url;

$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Settings'), 'url' => ['/setting/update']];
$this->params['breadcrumbs'][] = Yii::t('app', 'Report');

$this->params['menu']['option'] = [
	//['label' => Yii::t('app', 'Search'), 'url' => 'javascript:void(0);'],
	['label' => Yii::t('app', 'Grid Option'), 'url' => 'javascript:void(0);'],
];
?>

<?php echo $this->renderWidget('/setting/category/admin_manage', [
	'contentMenu' => true,
	'searchModel' => $searchModel,
	'dataProvider' => $dataProvider,
	'columns' => $columns,
	'breadcrumb' => false,
]); ?>

<?php echo $this->renderWidget(!$model->isNewRecord ? 'admin_view' : 'admin_update', [
	'contentMenu' => true,
	'model' => $model,
	'breadcrumb' => false,
]); ?>
