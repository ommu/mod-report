<?php
/**
 * Report Settings (report-setting)
 * @var $this SettingController
 * @var $model ReportSetting
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2017 Ommu Platform (www.ommu.co)
 * @modified date 18 January 2018, 13:38 WIB
 * @link https://github.com/ommu/mod-report
 *
 */

	$this->breadcrumbs=array(
		'Report Settings'=>array('manage'),
		'Manual Book',
	);
?>

<div class="dialog-content">
	<ul>
	<?php
		foreach (new DirectoryIterator($manual_path) as $fileInfo) {
			$filePath = '';
			if($fileInfo->isDot())
				continue;
			
			if($fileInfo->isFile()) {
				$extension = pathinfo($fileInfo->getFilename(), PATHINFO_EXTENSION);
				if(!in_array(strtolower($extension), array('php')))
					$filePath = $this->module->assetsUrl.'/manual/'.$fileInfo->getFilename();
			}
			if($filePath)
				echo '<li>'.CHtml::link($fileInfo->getFilename(), $filePath).'</li>';
		}
	?>
	</ul>
</div>
<div class="dialog-submit">
	<?php echo CHtml::button(Yii::t('phrase', 'Close'), array('id'=>'closed')); ?>
</div>