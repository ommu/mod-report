<?php
/**
 * SettingController
 * @var $this SettingController
 * @var $model ReportSetting
 * @var $form CActiveForm
 *
 * Reference start
 * TOC :
 *	Index
 *	Edit
 *	Delete
 *	Manual
 *
 *	LoadModel
 *	performAjaxValidation
 *
 * @author Putra Sudaryanto <putra@ommu.co>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2017 Ommu Platform (www.ommu.co)
 * @modified date 23 July 2018, 13:20 WIB
 * @link https://github.com/ommu/mod-report
 *
 *----------------------------------------------------------------------------------------------------------
 */

class SettingController extends Controller
{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	//public $layout='//layouts/column2';
	public $defaultAction = 'index';

	/**
	 * Initialize admin page theme
	 */
	public function init() 
	{
		if(!Yii::app()->user->isGuest) {
			if(in_array(Yii::app()->user->level, array(1,2))) {
				$arrThemes = $this->currentTemplate('admin');
				Yii::app()->theme = $arrThemes['folder'];
				$this->layout = $arrThemes['layout'];
			}
		} else
			$this->redirect(Yii::app()->createUrl('site/login'));
	}

	/**
	 * @return array action filters
	 */
	public function filters() 
	{
		return array(
			'accessControl', // perform access control for CRUD operations
			//'postOnly + delete', // we only allow deletion via POST request
		);
	}

	/**
	 * Specifies the access control rules.
	 * This method is used by the 'accessControl' filter.
	 * @return array access control rules
	 */
	public function accessRules() 
	{
		return array(
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'=>array('edit','delete'),
				'users'=>array('@'),
				'expression'=>'Yii::app()->user->level == 1',
			),
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'=>array('index','manual'),
				'users'=>array('@'),
				'expression'=>'in_array(Yii::app()->user->level, array(1,2))',
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}

	/**
	 * Lists all models.
	 */
	public function actionIndex() 
	{
		$this->redirect(array('edit'));
	}

	/**
	 * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id the ID of the model to be updated
	 */
	public function actionEdit() 
	{
		if(Yii::app()->user->level != 1)
			throw new CHttpException(404, Yii::t('phrase', 'The requested page does not exist.'));

		$category=new ReportCategory('search');
		$category->unsetAttributes();	// clear any default values
		$ReportCategory = Yii::app()->getRequest()->getParam('ReportCategory');
		if($ReportCategory)
			$category->attributes=$ReportCategory;

		$columns = $category->getGridColumn($this->gridColumnTemp());

		$model = ReportSetting::model()->findByPk(1);
		if($model == null)
			$model=new ReportSetting;

		// Uncomment the following line if AJAX validation is needed
		$this->performAjaxValidation($model);

		if(isset($_POST['ReportSetting'])) {
			$model->attributes=$_POST['ReportSetting'];

			$jsonError = CActiveForm::validate($model);
			if(strlen($jsonError) > 2) {
				$errors = $model->getErrors();
				$summary['msg'] = "<div class='errorSummary'><strong>".Yii::t('phrase', 'Please fix the following input errors:')."</strong>";
				$summary['msg'] .= "<ul>";
				foreach($errors as $key => $value) {
					$summary['msg'] .= "<li>{$value[0]}</li>";
				}
				$summary['msg'] .= "</ul></div>";

				$message = json_decode($jsonError, true);
				$merge = array_merge_recursive($summary, $message);
				$encode = json_encode($merge);
				echo $encode;

			} else {
				if(Yii::app()->getRequest()->getParam('enablesave') == 1) {
					if($model->save()) {
						echo CJSON::encode(array(
							'type' => 0,
							'msg' => '<div class="errorSummary success"><strong>'.Yii::t('phrase', 'Report setting success updated.').'</strong></div>',
						));
					} else
						print_r($model->getErrors());
				}
			}
			Yii::app()->end();
		}

		$this->pageTitle = Yii::t('phrase', 'Report Settings');
		$this->pageDescription = '';
		$this->pageMeta = '';
		$this->render('admin_edit', array(
			'model'=>$model,
			'category'=>$category,
			'columns' => $columns,
		));
	}

	/**
	 * Deletes a particular model.
	 * If deletion is successful, the browser will be redirected to the 'admin' page.
	 * @param integer $id the ID of the model to be deleted
	 */
	public function actionDelete($id) 
	{
		$model=$this->loadModel($id);
		
		if(Yii::app()->request->isPostRequest) {
			// we only allow deletion via POST request
			if($model->delete()) {
				echo CJSON::encode(array(
					'type' => 5,
					'get' => Yii::app()->controller->createUrl('edit'),
					'id' => 'partial-report-setting',
					'msg' => '<div class="errorSummary success"><strong>'.Yii::t('phrase', 'Report setting success deleted.').'</strong></div>',
				));
			}
			Yii::app()->end();
		}

		$this->dialogDetail = true;
		$this->dialogGroundUrl = Yii::app()->controller->createUrl('edit');
		$this->dialogWidth = 350;

		$this->pageTitle = Yii::t('phrase', 'Delete Setting');
		$this->pageDescription = '';
		$this->pageMeta = '';
		$this->render('admin_delete');
	}

	/**
	 * Lists all models.
	 */
	public function actionManual() 
	{
		$manual_path = $this->module->basePath.'/assets/manual';
		
		$this->dialogDetail = true;
		$this->dialogGroundUrl = Yii::app()->controller->createUrl('o/admin/manage');
		$this->dialogWidth = 400;
		
		$this->pageTitle = Yii::t('phrase', 'Report Manual');
		$this->pageDescription = '';
		$this->pageMeta = '';
		$this->render('admin_manual', array(
			'manual_path'=>$manual_path,
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer the ID of the model to be loaded
	 */
	public function loadModel($id) 
	{
		$model = ReportSetting::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404, Yii::t('phrase', 'The requested page does not exist.'));
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param CModel the model to be validated
	 */
	protected function performAjaxValidation($model) 
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='report-setting-form') {
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}

}
