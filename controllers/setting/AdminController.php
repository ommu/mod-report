<?php
/**
 * AdminController
 * @var $this ommu\report\controllers\setting\AdminController
 * @var $model ommu\report\models\ReportSetting
 *
 * AdminController implements the CRUD actions for ReportSetting model.
 * Reference start
 * TOC :
 *	Index
 *	Update
 *	Delete
 *
 *	findModel
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2017 OMMU (www.ommu.co)
 * @created date 22 September 2017, 13:49 WIB
 * @modified date 16 January 2019, 11:10 WIB
 * @link https://github.com/ommu/mod-report
 *
 */
 
namespace ommu\report\controllers\setting;

use Yii;
use app\components\Controller;
use yii\filters\VerbFilter;
use mdm\admin\components\AccessControl;
use ommu\report\models\ReportSetting;
use ommu\report\models\search\ReportCategory as ReportCategorySearch;

class AdminController extends Controller
{
	/**
	 * {@inheritdoc}
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
		$this->layout = 'admin_default';

		$model = ReportSetting::findOne(1);
		if($model === null) 
			$model = new ReportSetting();

		if(Yii::$app->request->isPost) {
			$model->load(Yii::$app->request->post());

			if($model->save()) {
				Yii::$app->session->setFlash('success', Yii::t('app', 'Report setting success updated.'));
				return $this->redirect(['index']);
			}
		}

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

		$this->view->title = Yii::t('app', 'Report Settings');
		$this->view->description = '';
		$this->view->keywords = '';
		return $this->render('admin_index', [
			'model' => $model,
			'searchModel' => $searchModel,
			'dataProvider' => $dataProvider,
			'columns' => $columns,
		]);
	}

	/**
	 * Updates an existing ReportSetting model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id
	 * @return mixed
	 */
	public function actionUpdate()
	{
		$model = ReportSetting::findOne(1);
		if($model === null) 
			$model = new ReportSetting();

		if(Yii::$app->request->isPost) {
			$model->load(Yii::$app->request->post());

			if($model->save()) {
				Yii::$app->session->setFlash('success', Yii::t('app', 'Report setting success updated.'));
				return $this->redirect(['update']);

			} else {
				if(Yii::$app->request->isAjax)
					return \yii\helpers\Json::encode(\app\components\widgets\ActiveForm::validate($model));
			}
		}

		$this->subMenu = $this->module->params['setting_submenu'];
		$this->view->title = Yii::t('app', 'Report Settings');
		$this->view->description = '';
		$this->view->keywords = '';
		return $this->render('admin_update', [
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
		$model = $this->findModel($id);
		$model->delete();
		
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

		throw new \yii\web\NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
	}
}
