<?php
/**
 * Report Comments (report-comment)
 * @var $this CommentController
 * @var $model ReportComment
 * @var $form CActiveForm
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2017 Ommu Platform (www.ommu.co)
 * @created date 22 February 2017, 12:25 WIB
 * @modified date 23 July 2018, 12:51 WIB
 * @link https://github.com/ommu/mod-report
 *
 */

	$this->breadcrumbs=array(
		'Report Comments'=>array('manage'),
		$model->report->report_body=>array('view','id'=>$model->comment_id),
		'Update',
	);
?>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>
