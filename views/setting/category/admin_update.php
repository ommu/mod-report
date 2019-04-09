<?php
/**
 * Report Categories (report-category)
 * @var $this app\components\View
 * @var $this ommu\report\controllers\setting\CategoryController
 * @var $model ommu\report\models\ReportCategory
 * @var $form app\components\ActiveForm
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

$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Settings'), 'url' => Url::to(['setting/admin/index'])];
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Category'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->title->message, 'url' => ['view', 'id'=>$model->cat_id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');

$this->params['menu']['content'] = [
	['label' => Yii::t('app', 'Detail'), 'url' => Url::to(['view', 'id'=>$model->cat_id]), 'icon' => 'eye', 'htmlOptions' => ['class'=>'btn btn-info btn-sm']],
	['label' => Yii::t('app', 'Update'), 'url' => Url::to(['update', 'id'=>$model->cat_id]), 'icon' => 'pencil', 'htmlOptions' => ['class'=>'btn btn-primary btn-sm']],
	['label' => Yii::t('app', 'Delete'), 'url' => Url::to(['delete', 'id'=>$model->cat_id]), 'htmlOptions' => ['data-confirm'=>Yii::t('app', 'Are you sure you want to delete this item?'), 'data-method'=>'post', 'class'=>'btn btn-danger btn-sm'], 'icon' => 'trash'],
];
?>

<div class="report-category-update">

<?php echo $this->render('_form', [
	'model' => $model,
]); ?>

</div>