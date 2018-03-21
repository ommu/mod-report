<?php
/**
 * Report Categories (report-category)
 * @var $this CategoryController
 * @var $model ReportCategory
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2014 Ommu Platform (opensource.ommu.co)
 * @modified date 18 January 2018, 13:37 WIB
 * @link https://github.com/ommu/ommu-report
 *
 */

	$this->breadcrumbs=array(
		'Report Categories'=>array('manage'),
		'Manage',
	);
	$this->menu=array(
		array(
			'label' => Yii::t('phrase', 'Filter'),
			'url' => array('javascript:void(0);'),
			'itemOptions' => array('class' => 'search-button'),
			'linkOptions' => array('title' => Yii::t('phrase', 'Filter')),
		),
		array(
			'label' => Yii::t('phrase', 'Grid Options'),
			'url' => array('javascript:void(0);'),
			'itemOptions' => array('class' => 'grid-button'),
			'linkOptions' => array('title' => Yii::t('phrase', 'Grid Options')),
		),
	);

?>

<?php //begin.Search ?>
<div class="search-form">
<?php $this->renderPartial('_search',array(
	'model'=>$model,
)); ?>
</div>
<?php //end.Search ?>

<?php //begin.Grid Option ?>
<div class="grid-form">
<?php $this->renderPartial('_option_form',array(
	'model'=>$model,
	'gridColumns'=>Utility::getActiveDefaultColumns($columns),
)); ?>
</div>
<?php //end.Grid Option ?>

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
						'url' => 'Yii::app()->controller->createUrl(\'view\',array(\'id\'=>$data->primaryKey))'),
					'update' => array(
						'label' => Yii::t('phrase', 'Update Report Category'),
						'imageUrl' => false,
						'options' => array(
							'class' => 'update'
						),
						'url' => 'Yii::app()->controller->createUrl(\'edit\',array(\'id\'=>$data->primaryKey))'),
					'delete' => array(
						'label' => Yii::t('phrase', 'Delete Report Category'),
						'imageUrl' => false,
						'options' => array(
							'class' => 'delete'
						),
						'url' => 'Yii::app()->controller->createUrl(\'delete\',array(\'id\'=>$data->primaryKey))')
				),
				'template' => '{view}|{update}|{delete}',
			));

			$this->widget('application.libraries.core.components.system.OGridView', array(
				'id'=>'report-category-grid',
				'dataProvider'=>$model->search(),
				'filter'=>$model,
				'afterAjaxUpdate' => 'reinstallDatePicker',
				'columns' => $columnData,
				'pager' => array('header' => ''),
			));
		?>
		<?php //end.Grid Item ?>
	</div>
</div>