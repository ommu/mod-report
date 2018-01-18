<?php
/**
 * Report Statuses (report-status)
 * @var $this StatusController
 * @var $model ReportStatus
 * @var $form CActiveForm
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2017 Ommu Platform (opensource.ommu.co)
 * @created date 22 February 2017, 12:25 WIB
 * @modified date 18 January 2018, 13:38 WIB
 * @link https://github.com/ommu/ommu-report
 *
 */

	$cs = Yii::app()->getClientScript();
$js=<<<EOP
	$('form[name="gridoption"] :checkbox').click(function(){
		var url = $('form[name="gridoption"]').attr('action');
		$.ajax({
			url: url,
			data: $('form[name="gridoption"] :checked').serialize(),
			success: function(response) {
				$.fn.yiiGridView.update('report-status-grid', {
					data: $('form[name="gridoption"]').serialize()
				});
				return false;
			}
		});
	});
EOP;
	$cs->registerScript('grid-option', $js, CClientScript::POS_END);
?>

<?php echo CHtml::beginForm(Yii::app()->createUrl($this->route), 'get', array(
	'name' => 'gridoption',
));
$columns   = array();
$exception = array('_option','_no','id');
foreach($model->templateColumns as $key => $val) {
	if(!in_array($key, $exception))
		$columns[$key] = $key;
}
?>
<ul>
	<?php foreach($columns as $key => $val): ?>
	<li>
		<?php echo CHtml::checkBox('GridColumn['.$val.']', in_array($key, $gridColumns) ? true : false); ?>
		<?php echo CHtml::label($model->getAttributeLabel($val), 'GridColumn_'.$val); ?>
	</li>
	<?php endforeach; ?>
</ul>
<?php echo CHtml::endForm(); ?>
