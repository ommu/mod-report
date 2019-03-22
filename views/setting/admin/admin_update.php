<?php
/**
 * Report Settings (report-setting)
 * @var $this app\components\View
 * @var $this ommu\report\controllers\setting\AdminController
 * @var $model ommu\report\models\ReportSetting
 * @var $form app\components\ActiveForm
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2017 OMMU (www.ommu.co)
 * @created date 22 September 2017, 13:49 WIB
 * @modified date 16 January 2019, 11:10 WIB
 * @link https://github.com/ommu/mod-report
 *
 */

use yii\helpers\Url;

$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Report Settings'), 'url' => ['index']];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>

<div class="report-setting-update">

<?php echo $this->render('_form', [
	'model' => $model,
]); ?>

</div>