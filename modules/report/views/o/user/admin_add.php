<?php
/**
 * Report Users (report-user)
 * @var $this UserController
 * @var $model ReportUser
 * @var $form CActiveForm
 * version: 0.0.1
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @copyright Copyright (c) 2017 Ommu Platform (ommu.co)
 * @created date 22 February 2017, 12:26 WIB
 * @link https://github.com/ommu/Report
 * @contect (+62)856-299-4114
 *
 */

	$this->breadcrumbs=array(
		'Report Users'=>array('manage'),
		'Create',
	);
?>

<div class="form">
	<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>
</div>
