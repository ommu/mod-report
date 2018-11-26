<?php
/**
 * Report Categories (report-category)
 * @var $this yii\web\View
 * @var $this ommu\report\controllers\CategoryController
 * @var $model ommu\report\models\ReportCategory
 * @var $form yii\widgets\ActiveForm
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2017 Ommu Platform (www.ommu.co)
 * @created date 22 September 2017, 16:13 WIB
 * @modified date 25 April 2018, 16:36 WIB
 * @link https://github.com/ommu/mod-report
 *
 */

use yii\helpers\Html;
use yii\helpers\Url;

$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Report Categories'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->title->message, 'url' => ['view', 'id' => $model->cat_id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');

$this->params['menu']['content'] = [
	['label' => Yii::t('app', 'Back To Manage'), 'url' => Url::to(['setting/index']), 'icon' => 'table'],
	['label' => Yii::t('app', 'Detail'), 'url' => Url::to(['view', 'id' => $model->cat_id]), 'icon' => 'eye'],
	['label' => Yii::t('app', 'Delete'), 'url' => Url::to(['delete', 'id' => $model->cat_id]), 'htmlOptions' => ['data-confirm'=>Yii::t('app', 'Are you sure you want to delete this item?'), 'data-method'=>'post'], 'icon' => 'trash'],
];
?>

<?php echo $this->render('_form', [
	'model' => $model,
]); ?>