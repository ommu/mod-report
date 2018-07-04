<?php
/**
 * Reports (reports)
 * @var $this SitesController
 * @var $model Reports
 * @var $form CActiveForm
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2014 Ommu Platform (www.ommu.co)
 * @link https://github.com/ommu/mod-report
 *
 */

	$this->breadcrumbs=array(
		'Reports'=>array('manage'),
		'Create',
	);
?>

<?php if(Yii::app()->getRequest()->getParam('type') == 'success') {?>
	<div class="dialog-content">
		Report send success
	</div>
	<div class="dialog-submit">
		<?php echo CHtml::button('Closed', array('id'=>'closed')); ?>
	</div>
	
<?php } else {
	echo $this->renderPartial('_form', array('model'=>$model));
}?>