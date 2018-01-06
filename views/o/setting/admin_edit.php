<?php
/**
 * User Settings (report-setting)
 * @var $this SettingController
 * @var $model ReportSetting
 * @var $form CActiveForm
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2017 Ommu Platform (opensource.ommu.co)
 * @created date 24 August 2017, 14:41 WIB
 * @link https://github.com/ommu/ommu-report
 *
 */

	$this->breadcrumbs=array(
		'Report Settings'=>array('manage'),
		$model->id=>array('view','id'=>$model->id),
		'Update',
	);
?>

<div class="form" name="post-on">
	<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>
</div>