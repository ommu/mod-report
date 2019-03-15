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
$this->params['breadcrumbs'][] = Yii::t('app', 'Create');
?>

<div class="report-category-create">

<?php echo $this->render('_form', [
	'model' => $model,
]); ?>

</div>
