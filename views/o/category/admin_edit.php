<?php
/**
 * Report Category (report-category)
 * @var $this CategoryController
 * @var $model ReportCategory
 * @var $form CActiveForm
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2014 Ommu Platform (opensource.ommu.co)
 * @link https://github.com/ommu/ommu-report
 *
 */

	$this->breadcrumbs=array(
		'Report Categories'=>array('manage'),
		$model->title->message=>array('view','id'=>$model->cat_id),
		'Update',
	);
?>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>