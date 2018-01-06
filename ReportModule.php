<?php
/**
 * ReportModule
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2014 Ommu Platform (opensource.ommu.co)
 * @link https://github.com/ommu/ommu-report
 *
 *----------------------------------------------------------------------------------------------------------
 */

class ReportModule extends CWebModule
{
	public $publicControllers = array();
	private $_module = 'report';
	
	public $defaultController = 'site'; 

	// getAssetsUrl()
	//	return the URL for this module's assets, performing the publish operation
	//	the first time, and caching the result for subsequent use.
	private $_assetsUrl;

	public function init() {
		// this method is called when the module is being created
		// you may place code here to customize the module or the application
		
		// import the module-level models and components
		$this->setImport(array(
			'report.models.*',
			'report.models.view.*',
			'report.components.*',
		));

		// this method is called before any module controller action is performed
		// you may place customized code here
		// list public controller in this module
		$publicControllers = array();
		$controllerMap = array();

		$controllerPath = Yii::getPathOfAlias('application.modules.'.$this->_module.'.controllers');
		foreach (new DirectoryIterator($controllerPath) as $fileInfo) {
			if($fileInfo->isDot())
				continue;
			
			if($fileInfo->isFile() && !in_array($fileInfo->getFilename(), array('index.php'))) {
				$getFilename = $fileInfo->getFilename();
				$publicControllers[] = $controller = strtolower(preg_replace('(Controller.php)', '', $getFilename));
				$controllerMap[$controller] = array(
					'class'=>'application.modules.'.$this->_module.'.controllers.'.preg_replace('(.php)', '', $getFilename),
				);
			}
		}
		$this->controllerMap = $controllerMap;
		$this->publicControllers = $publicControllers;
	}

	public function beforeControllerAction($controller, $action) 
	{
		if(parent::beforeControllerAction($controller, $action)) 
		{
			// pake ini untuk set theme per action di controller..
			// $currentAction = Yii::app()->controller->id.'/'.$action->id;
			if(!in_array(strtolower(Yii::app()->controller->id), $this->publicControllers) && !Yii::app()->user->isGuest) {
				$arrThemes = Utility::getCurrentTemplate('admin');
				Yii::app()->theme = $arrThemes['folder'];
				$this->layout = $arrThemes['layout'];
			}
			Utility::applyCurrentTheme($this);
			
			return true;
		}
		else
			return false;
	}
 
	public function getAssetsUrl()
	{
		if ($this->_assetsUrl === null)
			$this->_assetsUrl = Yii::app()->getAssetManager()->publish(Yii::getPathOfAlias('report.assets'));
		
		return $this->_assetsUrl;
	}
}
