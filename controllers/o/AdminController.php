<?php
/**
 * AdminController
 * @var $this AdminController
 * @var $model Reports
 * @var $form CActiveForm
 *
 * Reference start
 * TOC :
 *	Index
 *	Manage
 *	Add
 *	Edit
 *	View
 *	Delete
 *	Status
 *
 *	LoadModel
 *	performAjaxValidation
 *
 * @author Putra Sudaryanto <putra@ommu.co>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2014 Ommu Platform (www.ommu.co)
 * @modified date 26 September 2018, 23:34 WIB
 * @link https://github.com/ommu/mod-report
 *
 *----------------------------------------------------------------------------------------------------------
 */

class AdminController extends Controller
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
				'actions'=>array('index','manage','add','edit','view','delete','status'),
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
		$this->redirect(array('manage'));
	}

	/**
	 * Manages all models.
	 */
	public function actionManage($category=null, $user=null) 
	{
		$model=new Reports('search');
		$model->unsetAttributes();	// clear any default values
		$Reports = Yii::app()->getRequest()->getParam('Reports');
		if($Reports)
			$model->attributes=$Reports;

		$columns = $model->getGridColumn($this->gridColumnTemp());

		$pageTitle = Yii::t('phrase', 'Abuse Reports');
		if($category != null) {
			$data = ReportCategory::model()->findByPk($category);
			$pageTitle = Yii::t('phrase', 'Reports: Category {name}', array ('{name}'=>$data->title->message));
		}
		if($user != null) {
			$data = Users::model()->findByPk($user);
			$pageTitle = Yii::t('phrase', 'Reports: User {displayname}', array ('{displayname}'=>$data->displayname));
		}

		$this->pageTitle = $pageTitle;
		$this->pageDescription = Yii::t('phrase', 'This page lists all of the reports your users have sent in regarding inappropriate content, system abuse, spam, and so forth. You can use the search field to look for reports that contain a particular word or phrase. Very old reports are periodically deleted by the system.');
		$this->pageMeta = '';
		$this->render('admin_manage', array(
			'model'=>$model,
			'columns' => $columns,
		));
	}

	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionAdd() 
	{
		$model=new Reports;

		// Uncomment the following line if AJAX validation is needed
		$this->performAjaxValidation($model);

		if(isset($_POST['Reports'])) {
			$model->attributes=$_POST['Reports'];

			$jsonError = CActiveForm::validate($model);
			if(strlen($jsonError) > 2) {
				echo $jsonError;

			} else {
				if(Yii::app()->getRequest()->getParam('enablesave') == 1) {
					if($model->save()) {
						echo CJSON::encode(array(
							'type' => 5,
							'get' => Yii::app()->controller->createUrl('manage'),
							'id' => 'partial-reports',
							'msg' => '<div class="errorSummary success"><strong>'.Yii::t('phrase', 'Report success created.').'</strong></div>',
						));
					} else
						print_r($model->getErrors());
				}
			}
			Yii::app()->end();
		}

		$this->dialogDetail = true;
		$this->dialogGroundUrl = Yii::app()->controller->createUrl('manage');
		$this->dialogWidth = 600;

		$this->pageTitle = Yii::t('phrase', 'Create Report');
		$this->pageDescription = '';
		$this->pageMeta = '';
		$this->render('admin_add', array(
			'model'=>$model,
		));
	}

	/**
	 * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id the ID of the model to be updated
	 */
	public function actionEdit($id) 
	{
		$model=$this->loadModel($id);

		// Uncomment the following line if AJAX validation is needed
		$this->performAjaxValidation($model);

		if(isset($_POST['Reports'])) {
			$model->attributes=$_POST['Reports'];
			if($model->status != $model->old_status_i)
				$model->scenario = 'resolveForm';

			$jsonError = CActiveForm::validate($model);
			if(strlen($jsonError) > 2) {
				echo $jsonError;

			} else {
				if(Yii::app()->getRequest()->getParam('enablesave') == 1) {
					if($model->save()) {
						echo CJSON::encode(array(
							'type' => 5,
							'get' => Yii::app()->controller->createUrl('manage'),
							'id' => 'partial-reports',
							'msg' => '<div class="errorSummary success"><strong>'.Yii::t('phrase', 'Report success updated.').'</strong></div>',
						));
					} else
						print_r($model->getErrors());
				}
			}
			Yii::app()->end();
		}

		$this->dialogDetail = true;
		$this->dialogGroundUrl = Yii::app()->controller->createUrl('manage');
		$this->dialogWidth = 600;

		$this->pageTitle = Yii::t('phrase', 'Update Report: {report_body}', array('{report_body}'=>$model->report_body));
		$this->pageDescription = '';
		$this->pageMeta = '';
		$this->render('admin_edit', array(
			'model'=>$model,
		));
	}

	/**
	 * Displays a particular model.
	 * @param integer $id the ID of the model to be displayed
	 */
	public function actionView($id) 
	{
		$model=$this->loadModel($id);

		$this->dialogDetail = true;
		$this->dialogGroundUrl = Yii::app()->controller->createUrl('manage');
		$this->dialogWidth = 600;

		$this->pageTitle = Yii::t('phrase', 'Detail Report: {report_body}', array('{report_body}'=>$model->report_body));
		$this->pageDescription = '';
		$this->pageMeta = '';
		$this->render('admin_view', array(
			'model'=>$model,
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
					'get' => Yii::app()->controller->createUrl('manage'),
					'id' => 'partial-reports',
					'msg' => '<div class="errorSummary success"><strong>'.Yii::t('phrase', 'Report success deleted.').'</strong></div>',
				));
			}
			Yii::app()->end();
		}

		$this->dialogDetail = true;
		$this->dialogGroundUrl = Yii::app()->controller->createUrl('manage');
		$this->dialogWidth = 350;

		$this->pageTitle = Yii::t('phrase', 'Delete Report: {report_body}', array('{report_body}'=>$model->report_body));
		$this->pageDescription = '';
		$this->pageMeta = '';
		$this->render('admin_delete');
	}

	/**
	 * Status a particular model.
	 * If status is successful, the browser will be redirected to the 'manage' page.
	 * @param integer $id the ID of the model to be status
	 */
	public function actionStatus($id) 
	{
		$model=$this->loadModel($id);
		$title = $model->status == 1 ? Yii::t('phrase', 'Unresolved') : Yii::t('phrase', 'Resolved');
		$replace = $model->status == 1 ? 0 : 1;

		// Uncomment the following line if AJAX validation is needed
		$this->performAjaxValidation($model);

		if(isset($_POST['Reports'])) {
			$model->attributes=$_POST['Reports'];
			$model->scenario = 'resolveForm';

			$model->status = $replace;
			$model->modified_id = !Yii::app()->user->isGuest ? Yii::app()->user->id : null;

			$jsonError = CActiveForm::validate($model);
			if(strlen($jsonError) > 2) {
				echo $jsonError;
				
			} else {
				if(Yii::app()->getRequest()->getParam('enablesave') == 1) {
					if($model->update()) {
						echo CJSON::encode(array(
							'type' => 5,
							'get' => Yii::app()->controller->createUrl('manage'),
							'id' => 'partial-reports',
							'msg' => '<div class="errorSummary success"><strong>'.Yii::t('phrase', 'Report success updated.').'</strong></div>',
						));
					} else
						print_r($model->getErrors());
				}
			}
			Yii::app()->end();
		}

		$this->dialogDetail = true;
		$this->dialogGroundUrl = Yii::app()->controller->createUrl('manage');
		$this->dialogWidth = 600;

		$this->pageTitle = Yii::t('phrase', '{title} Report: {report_body}', array('{title}'=>$title, '{report_body}'=>$model->report_body));
		$this->pageDescription = '';
		$this->pageMeta = '';
		$this->render('admin_status', array(
			'title'=>$title,
			'model'=>$model,
		));
	}
	
	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer the ID of the model to be loaded
	 */
	public function loadModel($id) 
	{
		$model = Reports::model()->findByPk($id);
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
		if(isset($_POST['ajax']) && $_POST['ajax']==='reports-form') {
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}

}
