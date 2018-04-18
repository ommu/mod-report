<?php
/**
 * ReportsController
 * @var $this yii\web\View
 * @var $model app\modules\report\models\Reports
 * version: 0.0.1
 *
 * ReportsController implements the CRUD actions for Reports model.
 * Reference start
 * TOC :
 *	Index
 *	Create
 *	Update
 *	View
 *	Delete
 *
 *	findModel
 *
 * @copyright Copyright (c) 2017 ECC UGM (ecc.ft.ugm.ac.id)
 * @link http://ecc.ft.ugm.ac.id
 * @author Aziz Masruhan <aziz.masruhan@gmail.com>
 * @created date 22 September 2017, 15:57 WIB
 * @contact (+62)857-4115-5177
 *
 */
 
namespace app\modules\report\controllers;

use Yii;
use app\modules\report\models\Reports;
use app\modules\report\models\search\Reports as ReportsSearch;
use app\components\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\modules\report\models\ReportHistory;
use mdm\admin\components\AccessControl;

class ReportsController extends Controller
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
	 * Lists all Reports models.
	 * @return mixed
	 */
	public function actionIndex()
	{
		$searchModel = new ReportsSearch();
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

		$this->view->title = Yii::t('app', 'Reports');
		$this->view->description = '';
		$this->view->keywords = '';
		return $this->render('admin_index', [
			'searchModel' => $searchModel,
			'dataProvider' => $dataProvider,
			'columns'	  => $columns,
		]);
	}

	/**
	 * Creates a new Reports model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 * @return mixed
	 */
	public function actionCreate()
	{
		$model = new Reports();

		if ($model->load(Yii::$app->request->post()) ) {
			$model->user_id = Yii::$app->user->id;
			$model->report_ip = Yii::$app->request->userIP;
			$model->save();
			return $this->redirect(['index']);
			//coment

		} else {
			$this->view->title = Yii::t('app', 'Create Reports');
			$this->view->description = '';
			$this->view->keywords = '';
			return $this->render('admin_create', [
				'model' => $model,
			]);
		}
	}

	/**
	 * Updates an existing Reports model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param string $id
	 * @return mixed
	 */
	public function actionUpdate($id)
	{
		$model = $this->findModel($id);

		// if ($model->load(Yii::$app->request->post()) && $model->save()) {
		// 	//return $this->redirect(['view', 'id' => $model->report_id]);
		// 	return $this->redirect(['index']);

		if ($model->load(Yii::$app->request->post()) ) {
			$model->user_id = Yii::$app->user->id;
			$model->report_ip = Yii::$app->request->userIP;
			$model->save();
			return $this->redirect(['index']);

		} else {
			$this->view->title = Yii::t('app', 'Update {modelClass}: {report_id}', ['modelClass' => 'Reports', 'report_id' => $model->report_id]);
			$this->view->description = '';
			$this->view->keywords = '';
			return $this->render('admin_update', [
				'model' => $model,
			]);
		}
	}

	/**
	 * Displays a single Reports model.
	 * @param string $id
	 * @return mixed
	 */
	public function actionView($id)
	{
		$model = $this->findModel($id);

		$this->view->title = Yii::t('app', 'View {modelClass}: {report_id}', ['modelClass' => 'Reports', 'report_id' => $model->report_id]);
		$this->view->description = '';
		$this->view->keywords = '';
		return $this->render('admin_view', [
			'model' => $model,
		]);
	}

	/**
	 * Deletes an existing Reports model.
	 * If deletion is successful, the browser will be redirected to the 'index' page.
	 * @param string $id
	 * @return mixed
	 */
	public function actionDelete($id)
	{
		$this->findModel($id)->delete();
		
		return $this->redirect(['index']);
	}

	/**
	 * Finds the Reports model based on its primary key value.
	 * If the model is not found, a 404 HTTP exception will be thrown.
	 * @param string $id
	 * @return Reports the loaded model
	 * @throws NotFoundHttpException if the model cannot be found
	 */
	protected function findModel($id)
	{
		if (($model = Reports::findOne($id)) !== null) 
			return $model;
		else
			throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
	}
}
