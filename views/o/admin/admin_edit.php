<?php
/**
 * Reports (reports)
 * @var $this AdminController
 * @var $model Reports
 * @var $form CActiveForm
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2014 Ommu Platform (www.ommu.co)
 * @modified date 18 January 2018, 13:38 WIB
 * @link https://github.com/ommu/mod-report
 *
 */

	$this->breadcrumbs=array(
		'Reports'=>array('manage'),
		$model->report_id=>array('view','id'=>$model->report_id),
		'Update',
	);
?>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>
