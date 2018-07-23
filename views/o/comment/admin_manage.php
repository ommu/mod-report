<?php
/**
 * Report Comments (report-comment)
 * @var $this CommentController
 * @var $model ReportComment
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
<?php $this->renderPartial('_search', array(
	'model'=>$model,
)); ?>
</div>
<?php //end.Search ?>

<?php //begin.Grid Option ?>
<div class="grid-form">
<?php $this->renderPartial('_option_form', array(
	'model'=>$model,
	'gridColumns'=>$this->activeDefaultColumns($columns),
)); ?>
</div>
<?php //end.Grid Option ?>

<div id="partial-report-comment">
	<?php //begin.Messages ?>
	<div id="ajax-message">
	<?php
	if(Yii::app()->user->hasFlash('error'))
		echo $this->flashMessage(Yii::app()->user->getFlash('error'), 'error');
	if(Yii::app()->user->hasFlash('success'))
		echo $this->flashMessage(Yii::app()->user->getFlash('success'), 'success');
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
						'label' => Yii::t('phrase', 'Detail Report Comment'),
						'imageUrl' => Yii::app()->params['grid-view']['buttonImageUrl'],
						'options' => array(
							'class' => 'view',
						),
						'url' => 'Yii::app()->controller->createUrl(\'view\', array(\'id\'=>$data->primaryKey))'),
					'update' => array(
						'label' => Yii::t('phrase', 'Update Report Comment'),
						'imageUrl' => Yii::app()->params['grid-view']['buttonImageUrl'],
						'options' => array(
							'class' => 'update',
						),
						'url' => 'Yii::app()->controller->createUrl(\'edit\', array(\'id\'=>$data->primaryKey))'),
					'delete' => array(
						'label' => Yii::t('phrase', 'Delete Report Comment'),
						'imageUrl' => Yii::app()->params['grid-view']['buttonImageUrl'],
						'options' => array(
							'class' => 'delete',
						),
						'url' => 'Yii::app()->controller->createUrl(\'delete\', array(\'id\'=>$data->primaryKey))'),
				),
				'template' => '{view}|{update}|{delete}',
			));

			$this->widget('application.libraries.yii-traits.system.OGridView', array(
				'id'=>'report-comment-grid',
				'dataProvider'=>$model->search(),
				'filter'=>$model,
				'columns'=>$columnData,
				'template'=>Yii::app()->params['grid-view']['gridTemplate'],
				'pager'=>array('header'=>''),
				'afterAjaxUpdate'=>'reinstallDatePicker',
			));
		?>
		<?php //end.Grid Item ?>
	</div>
</div>