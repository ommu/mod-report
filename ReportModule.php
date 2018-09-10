<?php
/**
 * ReportModule
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2014 Ommu Platform (www.ommu.co)
 * @link https://github.com/ommu/mod-report
 *
 *----------------------------------------------------------------------------------------------------------
 */

class ReportModule extends CWebModule
{
	use ThemeTrait;

	public $publicControllers = array();
	private $_module = 'report';

	public $defaultController = 'site'; 

	// getAssetsUrl()
	//	return the URL for this module's assets, performing the publish operation
	//	the first time, and caching the result for subsequent use.
	private $_assetsUrl;

	public function init()
	{
		// this method is called when the module is being created
		// you may place code here to customize the module or the application
		
		// import the module-level models and components
		$this->setImport(array(
			'report.models.*',
			'report.components.*',
		));

		// this method is called before any module controller action is performed
		// you may place customized code here
		// list public controller in this module
		$controllerMap = array();

		$controllerPath = 'application.modules.'.$this->_module.'.controllers';
		if(!empty($controllerMap))
			$controllerMap = array_merge($controllerMap, $this->getController($controllerPath));
		else
			$controllerMap = $this->getController($controllerPath);
			
		$this->controllerMap = $controllerMap;
		$this->publicControllers = array_keys($this->controllerMap);
	}

	public function beforeControllerAction($controller, $action) 
	{
		if(parent::beforeControllerAction($controller, $action)) 
		{
			// pake ini untuk set theme per action di controller..
			// $currentAction = Yii::app()->controller->id.'/'.$action->id;
			if(!in_array(Yii::app()->controller->id, $this->publicControllers) && !Yii::app()->user->isGuest) {
				$arrThemes = $this->currentTemplate('admin');
				Yii::app()->theme = $arrThemes['folder'];
				$this->layout = $arrThemes['layout'];
			}
			$this->applyCurrentTheme($this);
			
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

	public function getController($path, $sub=null)
	{
		$controllerMap = array();
		$controllerPath = Yii::getPathOfAlias($path);
		$pathArray = explode('.', $path);
		$lastPath = end($pathArray);

		foreach (new DirectoryIterator($controllerPath) as $fileInfo) {
			if($fileInfo->isDot() && $fileInfo->isDir())
				continue;
			
			if($fileInfo->isFile() && !in_array($fileInfo->getFilename(), array('index.php','.DS_Store'))) {
				$getFilename = $fileInfo->getFilename();
				$controller = strtolower(preg_replace('(Controller.php)', '', $getFilename));
				if($lastPath != 'controllers')
					$controller = join('', array($lastPath, preg_replace('(Controller.php)', '', $getFilename)));
				$controllerClass = preg_replace('(.php)', '', $getFilename);

				$controllerMap[$controller] = array(
					'class'=>join('.', array($path, $controllerClass)),
				);

			} else if($fileInfo->isDir()) {
				$sub = $fileInfo->getFilename();
				$subPath = join('.', array($path, $sub));
				$controllerMap = array_merge($controllerMap, $this->getController($subPath, $sub));
			}
		}
		
		return $controllerMap;
	}
}
