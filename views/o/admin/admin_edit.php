<?php
/**
 * Reports (reports)
 * @var $this AdminController
 * @var $model Reports
 * @var $form CActiveForm
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2014 Ommu Platform (opensource.ommu.co)
 * @link https://github.com/ommu/ommu-report
 *
 */

	$this->breadcrumbs=array(
		'Reports'=>array('manage'),
		$model->report_id=>array('view','id'=>$model->report_id),
		'Update',
	);
?>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>