<?php
/**
 * UserController
 * @var $this UserController
 * @var $model ReportUser
 * @var $form CActiveForm
 *
 * Reference start
 * TOC :
 *	Index
 *	Manage
 *	View
 *	Delete
 *	Runaction
 *	Publish
 *
 *	LoadModel
 *	performAjaxValidation
 *
 * @author Putra Sudaryanto <putra@ommu.co>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2017 Ommu Platform (www.ommu.co)
 * @created date 22 February 2017, 12:26 WIB
 * @modified date 27 September 2018, 09:01 WIB
 * @link https://github.com/ommu/mod-report
 *
 *----------------------------------------------------------------------------------------------------------
 */

class UserController extends Controller
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
				'actions'=>array('index','manage','view','delete','runaction','publish'),
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
	public function actionManage($report=null, $user=null) 
	{
		$model=new ReportUser('search');
		$model->unsetAttributes();	// clear any default values
		$ReportUser = Yii::app()->getRequest()->getParam('ReportUser');
		if($ReportUser)
			$model->attributes=$ReportUser;

		$columns = $model->getGridColumn($this->gridColumnTemp());

		$pageTitle = Yii::t('phrase', 'Report Users');
		if($report != null) {
			$data = Reports::model()->findByPk($report);
			$pageTitle = Yii::t('phrase', 'Users: Report {report_body}', array ('{report_body}'=>$data->report_body));
		}
		if($user != null) {
			$data = Users::model()->findByPk($user);
			$pageTitle = Yii::t('phrase', 'Users: {displayname}', array ('{displayname}'=>$data->displayname));
		}

		$this->pageTitle = $pageTitle;
		$this->pageDescription = '';
		$this->pageMeta = '';
		$this->render('admin_manage', array(
			'model'=>$model,
			'columns' => $columns,
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

		$this->pageTitle = Yii::t('phrase', 'Detail User: {report_id}', array('{report_id}'=>$model->report->report_body));
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
			$model->publish = 2;
			$model->modified_id = !Yii::app()->user->isGuest ? Yii::app()->user->id : null;
			
			if($model->update()) {
				echo CJSON::encode(array(
					'type' => 5,
					'get' => Yii::app()->controller->createUrl('manage'),
					'id' => 'partial-report-user',
					'msg' => '<div class="errorSummary success"><strong>'.Yii::t('phrase', 'Report user success deleted.').'</strong></div>',
				));
			}
			Yii::app()->end();
		}

		$this->dialogDetail = true;
		$this->dialogGroundUrl = Yii::app()->controller->createUrl('manage');
		$this->dialogWidth = 350;

		$this->pageTitle = Yii::t('phrase', 'Delete User: {report_id}', array('{report_id}'=>$model->report->report_body));
		$this->pageDescription = '';
		$this->pageMeta = '';
		$this->render('admin_delete');
	}

	/**
	 * Displays a particular model.
	 * @param integer $id the ID of the model to be displayed
	 */
	public function actionRunaction() 
	{
		$id       = $_POST['trash_id'];
		$criteria = null;
		$actions  = Yii::app()->getRequest()->getParam('action');

		if(count($id) > 0) {
			$criteria = new CDbCriteria;
			$criteria->addInCondition('id', $id);

			if($actions == 'publish') {
				ReportUser::model()->updateAll(array(
					'publish' => 1,
				), $criteria);
			} elseif($actions == 'unpublish') {
				ReportUser::model()->updateAll(array(
					'publish' => 0,
				), $criteria);
			} elseif($actions == 'trash') {
				ReportUser::model()->updateAll(array(
					'publish' => 2,
				), $criteria);
			} elseif($actions == 'delete') {
				ReportUser::model()->deleteAll($criteria);
			}
		}

		// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
		if(!Yii::app()->getRequest()->getParam('ajax'))
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('manage'));
	}

	/**
	 * Publish a particular model.
	 * If publish is successful, the browser will be redirected to the 'manage' page.
	 * @param integer $id the ID of the model to be publish
	 */
	public function actionPublish($id) 
	{
		$model=$this->loadModel($id);
		$title = $model->publish == 1 ? Yii::t('phrase', 'Unpublish') : Yii::t('phrase', 'Publish');
		$replace = $model->publish == 1 ? 0 : 1;

		if(Yii::app()->request->isPostRequest) {
			// we only allow publish via POST request
			$model->publish = $replace;
			$model->modified_id = !Yii::app()->user->isGuest ? Yii::app()->user->id : null;

			if($model->update()) {
				echo CJSON::encode(array(
					'type' => 5,
					'get' => Yii::app()->controller->createUrl('manage'),
					'id' => 'partial-report-user',
					'msg' => '<div class="errorSummary success"><strong>'.Yii::t('phrase', 'Report user success updated.').'</strong></div>',
				));
			}
			Yii::app()->end();
		}

		$this->dialogDetail = true;
		$this->dialogGroundUrl = Yii::app()->controller->createUrl('manage');
		$this->dialogWidth = 350;

		$this->pageTitle = Yii::t('phrase', '{title} User: {report_id}', array('{title}'=>$title, '{report_id}'=>$model->report->report_body));
		$this->pageDescription = '';
		$this->pageMeta = '';
		$this->render('admin_publish', array(
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
		$model = ReportUser::model()->findByPk($id);
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
		if(isset($_POST['ajax']) && $_POST['ajax']==='report-user-form') {
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}

}
