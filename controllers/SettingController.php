<?php
/**
 * SettingController
 * @var $this yii\web\View
 * @var $model app\modules\report\models\ReportSetting
 *
 * SettingController implements the CRUD actions for ReportSetting model.
 * Reference start
 * TOC :
 *	Index
 *	Update
 *	Delete
 *
 *	findModel
 *
 * @author Aziz Masruhan <aziz.masruhan@gmail.com>
 * @contact (+62)857-4115-5177
 * @copyright Copyright (c) 2018 ECC UGM (ecc.ft.ugm.ac.id)
 * @created date 22 September 2017, 13:49 WIB
 * @modified date 25 April 2018, 15:36 WIB
 * @modified by Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @link http://ecc.ft.ugm.ac.id
 *
 */
 
namespace app\modules\report\controllers;

use Yii;
use yii\filters\VerbFilter;
use yii\web\NotFoundHttpException;
use app\components\Controller;
use mdm\admin\components\AccessControl;
use app\modules\report\models\ReportSetting;
use app\modules\report\models\search\ReportSetting as ReportSettingSearch;
use app\modules\report\models\search\ReportCategory as ReportCategorySearch;

class SettingController extends Controller
{
	/**
	 * @inheritdoc
	 */
	public function behaviors()
	{
		return [
			'access' => [
				'class' => AccessControl::className(),
			],
			'verbs' => [
				'class' => VerbFilter::className(),
				'actions' => [
					'delete' => ['POST'],
				],
			],
		];
	}

	/**
	 * Lists all ReportSetting models.
	 * @return mixed
	 */
	public function actionIndex()
	{
		return $this->redirect(['update']);
	}

	/**
	 * Updates an existing ReportSetting model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id
	 * @return mixed
	 */
	public function actionUpdate()
	{
		$this->layout = 'admin_default';
		
		$searchModel = new ReportCategorySearch();
		$dataProvider = $searchModel->search(Yii::$app->request->queryParams);

		$gridColumn = Yii::$app->request->get('GridColumn', null);
		$cols = [];
		if($gridColumn != null && count($gridColumn) > 0) {
			foreach($gridColumn as $key => $val) {
				if($gridColumn[$key] == 1)
					$cols[] = $key;
			}
		}
		$columns = $searchModel->getGridColumn($cols);
		
		$model = ReportSetting::findOne(1);
		if($model === null) 
			$model = new ReportSetting();

		if(Yii::$app->request->isPost) {
			$model->load(Yii::$app->request->post());

			if($model->save()) {
				Yii::$app->session->setFlash('success', Yii::t('app', 'Setting success updated.'));
				return $this->redirect(['update']);
				//return $this->redirect(['view', 'id' => $model->id]);
			}
		}

		$this->view->title = Yii::t('app', 'Settings');
		$this->view->description = '';
		$this->view->keywords = '';
		return $this->render('admin_update', [
			'searchModel' => $searchModel,
			'dataProvider' => $dataProvider,
			'columns' => $columns,
			'model' => $model,
		]);
	}

	/**
	 * Deletes an existing ReportSetting model.
	 * If deletion is successful, the browser will be redirected to the 'index' page.
	 * @param integer $id
	 * @return mixed
	 */
	public function actionDelete($id)
	{
		$this->findModel($id)->delete();
		
		Yii::$app->session->setFlash('success', Yii::t('app', 'Report setting success deleted.'));
		return $this->redirect(['update']);
	}

	/**
	 * Finds the ReportSetting model based on its primary key value.
	 * If the model is not found, a 404 HTTP exception will be thrown.
	 * @param integer $id
	 * @return ReportSetting the loaded model
	 * @throws NotFoundHttpException if the model cannot be found
	 */
	protected function findModel($id)
	{
		if(($model = ReportSetting::findOne($id)) !== null) 
			return $model;
		else
			throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
	}
}
