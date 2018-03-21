<?php
/**
 * Report Settings (report-setting)
 * @var $this SettingController
 * @var $model ReportSetting
 * @var $form CActiveForm
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2017 Ommu Platform (opensource.ommu.co)
 * @created date 24 August 2017, 14:41 WIB
 * @modified date 18 January 2018, 13:38 WIB
 * @link https://github.com/ommu/ommu-report
 *
 */

	$this->breadcrumbs=array(
		'Report Settings'=>array('manage'),
		$model->id=>array('view','id'=>$model->id),
		'Update',
	);
?>

<div id="partial-report-category">
	<?php //begin.Messages ?>
	<div id="ajax-message">
	<?php
	if(Yii::app()->user->hasFlash('error'))
		echo Utility::flashError(Yii::app()->user->getFlash('error'));
	if(Yii::app()->user->hasFlash('success'))
		echo Utility::flashSuccess(Yii::app()->user->getFlash('success'));
	?>
	</div>
	<?php //begin.Messages ?>

	<div class="boxed">
		<?php //begin.Grid Item ?>
		<?php 
			$columnData   = $columns;
			array_push($columnData, array(
				'header' => Yii::t('phrase', 'Options'),
				'class'=>'CButtonColumn',
				'buttons' => array(
					'view' => array(
						'label' => Yii::t('phrase', 'Detail Report Category'),
						'imageUrl' => false,
						'options' => array(
							'class' => 'view',
						),
						'url' => 'Yii::app()->controller->createUrl("o/category/view",array("id"=>$data->primaryKey))'),
					'update' => array(
						'label' => Yii::t('phrase', 'Update Report Category'),
						'imageUrl' => false,
						'options' => array(
							'class' => 'update'
						),
						'url' => 'Yii::app()->controller->createUrl("o/category/edit",array("id"=>$data->primaryKey))'),
					'delete' => array(
						'label' => Yii::t('phrase', 'Delete Report Category'),
						'imageUrl' => false,
						'options' => array(
							'class' => 'delete'
						),
						'url' => 'Yii::app()->controller->createUrl("o/category/delete",array("id"=>$data->primaryKey))')
				),
				'template' => '{view}|{update}|{delete}',
			));

			$this->widget('application.libraries.core.components.system.OGridView', array(
				'id'=>'report-category-grid',
				'dataProvider'=>$category->search(),
				'filter'=>$category,
				'afterAjaxUpdate' => 'reinstallDatePicker',
				'columns' => $columnData,
				'pager' => array('header' => ''),
			));
		?>
		<?php //end.Grid Item ?>
	</div>
</div>

<div class="form" name="post-on">
	<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>
</div>
