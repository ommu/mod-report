<?php
/**
 * @var $this SitesController
 * @var $model Reports
 *
 * @author Putra Sudaryanto <putra.sudaryanto@gmail.com>
 * @copyright Copyright (c) 2014 Ommu Platform (ommu.co)
 * @link http://company.ommu.co
 * @contact (+62)856-299-4114
 *
 */

	$this->breadcrumbs=array(
		'Reports'=>array('manage'),
		'Create',
	);
?>

<?php if(isset($_GET['type']) && $_GET['type'] == 'success') {?>	
	<div class="dialog-content">
		Report send success
	</div>
	<div class="dialog-submit">
		<?php echo CHtml::button('Closed', array('id'=>'closed')); ?>
	</div>
	
<?php } else {
	echo $this->renderPartial('_form', array('model'=>$model));
}?>