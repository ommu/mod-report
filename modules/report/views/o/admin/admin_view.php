<?php
/**
 * Reports (reports)
 * @var $this AdminController
 * @var $model Reports
 * version: 0.0.1
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @copyright Copyright (c) 2017 Ommu Platform (opensource.ommu.co)
 * @created date 23 August 2017, 23:39 WIB
 * @link https://github.com/ommu/mod-report
 * @contact (+62)856-299-4114
 *
 */

	$this->breadcrumbs=array(
		'Reports'=>array('manage'),
		$model->cat_id,
	);
?>

<div class="dialog-content">
	<?php $this->widget('application.components.system.FDetailView', array(
		'data'=>$model,
		'attributes'=>array(
			array(
				'name'=>'report_id',
				'value'=>$model->report_id,
			),
			array(
				'name'=>'status',
				'value'=>$model->status == '1' ? Chtml::image(Yii::app()->theme->baseUrl.'/images/icons/publish.png') : Chtml::image(Yii::app()->theme->baseUrl.'/images/icons/unpublish.png'),
				'type'=>'raw',
			),
			array(
				'name'=>'cat_id',
				'value'=>$model->cat_id ? Phrase::trans($model->cat->name) : '-',
			),
			array(
				'name'=>'user_id',
				'value'=>$model->user->displayname ? $model->user->displayname : '-',
			),
			array(
				'name'=>'report_url',
				'value'=>$model->report_url ? $model->report_url : '-',
			),
			array(
				'name'=>'report_body',
				'value'=>$model->report_body ? $model->report_body : '-',
				'type'=>'raw',
			),
			array(
				'name'=>'report_message 	',
				'value'=>$model->report_message ? $model->report_message : '-',
				'type'=>'raw',
			),
			array(
				'name'=>'report_date',
				'value'=>!in_array($model->report_date, array('0000-00-00 00:00:00','1970-01-01 00:00:00')) ? Utility::dateFormat($model->report_date, true) : '-',
			),
			array(
				'name'=>'report_ip',
				'value'=>$model->report_ip ? $model->report_ip : '-',
			),
			array(
				'name'=>'modified_date',
				'value'=>!in_array($model->modified_date, array('0000-00-00 00:00:00','1970-01-01 00:00:00')) ? Utility::dateFormat($model->modified_date, true) : '-',
			),
			array(
				'name'=>'modified_id',
				'value'=>$model->modified->displayname ? $model->modified->displayname : '-',
			),
			array(
				'name'=>'updated_date',
				'value'=>!in_array($model->updated_date, array('0000-00-00 00:00:00','1970-01-01 00:00:00')) ? Utility::dateFormat($model->updated_date, true) : '-',
			),
		),
	)); ?>
</div>
<div class="dialog-submit">
	<?php echo CHtml::button(Yii::t('phrase', 'Close'), array('id'=>'closed')); ?>
</div>
