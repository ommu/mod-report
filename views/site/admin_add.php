<?php
/**
 * Reports (reports)
 * @var $this app\components\View
 * @var $this ommu\report\controllers\SiteController
 * @var $model ommu\report\models\Reports
 * @var $form app\components\widgets\ActiveForm
 *
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)811-2540-432
 * @copyright Copyright (c) 2019 OMMU (www.ommu.id)
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
