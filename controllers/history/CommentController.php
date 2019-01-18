<?php
/**
 * CommentController
 * @var $this app\components\View
 * @var $model ommu\report\models\ReportComment
 *
 * CommentController implements the CRUD actions for ReportComment model.
 * Reference start
 * TOC :
 *	Index
 *	Create
 *	Update
 *	View
 *	Delete
 *	Runaction
 *	Publish
 *
 *	findModel
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2017 OMMU (www.ommu.co)
 * @created date 22 September 2017, 13:54 WIB
 * @link https://github.com/ommu/mod-report
 *
 */
 
namespace ommu\report\controllers\history;

use Yii;
use ommu\report\models\ReportComment;
use ommu\report\models\search\ReportComment as ReportCommentSearch;
use app\components\Controller;
use yii\filters\VerbFilter;
use mdm\admin\components\AccessControl;

class CommentController extends Controller
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
					'publish' => ['POST'],
				],
			],
		];
	}

	/**
	 * Lists all ReportComment models.
	 * @return mixed
	 */
	public function actionIndex()
	{
		$searchModel = new ReportCommentSearch();
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

		$this->view->title = Yii::t('app', 'Report Comments');
		$this->view->description = '';
		$this->view->keywords = '';
		return $this->render('admin_index', [
			'searchModel' => $searchModel,
			'dataProvider' => $dataProvider,
			'columns'	  => $columns,
		]);
	}

	/**
	 * Creates a new ReportComment model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 * @return mixed
	 */
	public function actionCreate()
	{
		$model = new ReportComment();

		if ($model->load(Yii::$app->request->post()) ) {
			//return $this->redirect(['view', 'id' => $model->comment_id]);
			$model->user_id = Yii::$app->user->id;
			$model->save();
			return $this->redirect(['index']);

		} else {
			$this->view->title = Yii::t('app', 'Create Report Comment');
			$this->view->description = '';
			$this->view->keywords = '';
			return $this->render('admin_create', [
				'model' => $model,
			]);
		}
	}

	/**
	 * Updates an existing ReportComment model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param string $id
	 * @return mixed
	 */
	public function actionUpdate($id)
	{
		$model = $this->findModel($id);

		if ($model->load(Yii::$app->request->post()) && $model->save()) {
			//return $this->redirect(['view', 'id' => $model->comment_id]);
			return $this->redirect(['index']);

		} else {
			$this->view->title = Yii::t('app', 'Update {modelClass}: {comment_id}', ['modelClass' => 'Report Comment', 'comment_id' => $model->comment_id]);
			$this->view->description = '';
			$this->view->keywords = '';
			return $this->render('admin_update', [
				'model' => $model,
			]);
		}
	}

	/**
	 * Displays a single ReportComment model.
	 * @param string $id
	 * @return mixed
	 */
	public function actionView($id)
	{
		$model = $this->findModel($id);

		$this->view->title = Yii::t('app', 'View {modelClass}: {comment_id}', ['modelClass' => 'Report Comment', 'comment_id' => $model->comment_id]);
		$this->view->description = '';
		$this->view->keywords = '';
		return $this->render('admin_view', [
			'model' => $model,
		]);
	}

	/**
	 * Deletes an existing ReportComment model.
	 * If deletion is successful, the browser will be redirected to the 'index' page.
	 * @param string $id
	 * @return mixed
	 */
	public function actionDelete($id)
	{
		$model = $this->findModel($id);
		$model->publish = 2;

		if ($model->save(false, ['publish'])) {
			//return $this->redirect(['view', 'id' => $model->comment_id]);
			return $this->redirect(['index']);
		}
	}

	/**
	 * Publish/Unpublish an existing ReportComment model.
	 * If publish/unpublish is successful, the browser will be redirected to the 'index' page.
	 * @param string $id
	 * @return mixed
	 */
	public function actionPublish($id)
	{
		$model = $this->findModel($id);
		$replace = $model->publish == 1 ? 0 : 1;
		$model->publish = $replace;

		if ($model->save(false, ['publish']))
			return $this->redirect(['index']);
	}

	/**
	 * Finds the ReportComment model based on its primary key value.
	 * If the model is not found, a 404 HTTP exception will be thrown.
	 * @param string $id
	 * @return ReportComment the loaded model
	 * @throws NotFoundHttpException if the model cannot be found
	 */
	protected function findModel($id)
	{
		if (($model = ReportComment::findOne($id)) !== null) 
			return $model;

		throw new \yii\web\NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
	}
}
