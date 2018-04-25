<?php
/**
 * Report Categories (report-category)
 * @var $this yii\web\View
 * @var $this app\modules\report\controllers\CategoryController
 * @var $model app\modules\report\models\ReportCategory
 * @var $form yii\widgets\ActiveForm
 *
 * @author Aziz Masruhan <aziz.masruhan@gmail.com>
 * @contact (+62)857-4115-5177
 * @copyright Copyright (c) 2017 ECC UGM (ecc.ft.ugm.ac.id)
 * @created date 22 September 2017, 16:13 WIB
 * @modified date 25 April 2018, 16:36 WIB
 * @modified by Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @link http://ecc.ft.ugm.ac.id
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
	['label' => Yii::t('app', 'Delete'), 'url' => Url::to(['delete', 'id' => $model->cat_id]), 'confirm' => Yii::t('app', 'Are you sure you want to delete this item?'), 'method' => 'post', 'icon' => 'trash'],
];
?>

<?php echo $this->render('_form', [
	'model' => $model,
]); ?>