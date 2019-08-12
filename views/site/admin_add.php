<?php
/**
 * Reports (reports)
 * @var $this app\components\View
 * @var $this ommu\report\controllers\SiteController
 * @var $model ommu\report\models\Reports
 * @var $form app\components\widgets\ActiveForm
 *
 * @author Putra Sudaryanto <putra@ommu.co>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2019 OMMU (www.ommu.co)
 * @created date 7 June 2019, 07:11 WIB
 * @link https://github.com/ommu/mod-report
 *
 */

use yii\helpers\Url;
?>

<div class="reports-create">

<?php echo $this->render('_form', [
	'model' => $model,
]); ?>

</div>
