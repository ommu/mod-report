<?php
/**
 * CommentController
 * @var $this ommu\report\controllers\history\CommentController
 * @var $model ommu\report\models\ReportComment
 *
 * CommentController implements the CRUD actions for ReportComment model.
 * Reference start
 * TOC :
 *	Index
 *	Manage
 *	View
 *	Delete
 *	RunAction
 *	Publish
 *
 *	findModel
 *
 * @author Putra Sudaryanto <putra@ommu.co>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2017 OMMU (www.ommu.co)
 * @created date 22 September 2017, 13:54 WIB
 * @modified date 18 January 2019, 15:37 WIB
 * @link https://github.com/ommu/mod-report
 *
 */

namespace ommu\report\controllers\history;

use Yii;
use yii\filters\VerbFilter;
use app\components\Controller;
use mdm\admin\components\AccessControl;
use ommu\report\models\ReportComment;
use ommu\report\models\search\ReportComment as ReportCommentSearch;
use ommu\report\models\Reports;

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
	 * {@inheritdoc}
	 */
	public function actionIndex()
	{
		return $this->redirect(['manage']);
	}

	/**
	 * Lists all ReportComment models.
	 * @return mixed
	 */
	public function actionManage()
	{
		$searchModel = new ReportCommentSearch();
		if(($id = Yii::$app->request->get('id')) != null)
			$searchModel = new ReportCommentSearch(['report_id'=>$id]);
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

		if(($report = Yii::$app->request->get('report')) != null || ($report = $id) != null)
			$report = \ommu\report\models\Reports::findOne($report);
		if(($user = Yii::$app->request->get('user')) != null)
			$user = \ommu\users\models\Users::findOne($user);

		$this->view->title = Yii::t('app', 'Comments');
		$this->view->description = '';
		$this->view->keywords = '';
		return $this->render('admin_manage', [
			'searchModel' => $searchModel,
			'dataProvider' => $dataProvider,
			'columns' => $columns,
			'report' => $report,
			'user' => $user,
		]);
	}

	/**
	 * Displays a single ReportComment model.
	 * @param integer $id
	 * @return mixed
	 */
	public function actionView($id)
	{
		$model = $this->findModel($id);
		$this->subMenuParam = $model->report_id;

		$this->view->title = Yii::t('app', 'Detail Comment: {report-id}', ['report-id' => Reports::htmlHardDecode($model->report->report_body)]);
		$this->view->description = '';
		$this->view->keywords = '';
		return $this->oRender('admin_view', [
			'model' => $model,
		]);
	}

	/**
	 * Deletes an existing ReportComment model.
	 * If deletion is successful, the browser will be redirected to the 'index' page.
	 * @param integer $id
	 * @return mixed
	 */
	public function actionDelete($id)
	{
		$model = $this->findModel($id);
		$model->publish = 2;

		if($model->save(false, ['publish','modified_id'])) {
			Yii::$app->session->setFlash('success', Yii::t('app', 'Report comment success deleted.'));
			return $this->redirect(['manage', 'id'=>$model->report_id]);
		}
	}

	/**
	 * actionPublish an existing ReportComment model.
	 * If publish is successful, the browser will be redirected to the 'index' page.
	 * @param integer $id
	 * @return mixed
	 */
	public function actionPublish($id)
	{
		$model = $this->findModel($id);
		$replace = $model->publish == 1 ? 0 : 1;
		$model->publish = $replace;

		if($model->save(false, ['publish','modified_id'])) {
			Yii::$app->session->setFlash('success', Yii::t('app', 'Report comment success updated.'));
			return $this->redirect(['manage', 'id'=>$model->report_id]);
		}
	}

	/**
	 * Finds the ReportComment model based on its primary key value.
	 * If the model is not found, a 404 HTTP exception will be thrown.
	 * @param integer $id
	 * @return ReportComment the loaded model
	 * @throws NotFoundHttpException if the model cannot be found
	 */
	protected function findModel($id)
	{
		if(($model = ReportComment::findOne($id)) !== null)
			return $model;

		throw new \yii\web\NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
	}
}
