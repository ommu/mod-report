<?php
/**
 * StatusController
 * @var $this ommu\report\controllers\history\StatusController
 * @var $model ommu\report\models\ReportStatus
 *
 * StatusController implements the CRUD actions for ReportStatus model.
 * Reference start
 * TOC :
 *	Index
 *	Manage
 *	View
 *	Delete
 *
 *	findModel
 *
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2017 OMMU (www.ommu.id)
 * @created date 22 September 2017, 16:03 WIB
 * @modified date 18 January 2019, 15:38 WIB
 * @link https://github.com/ommu/mod-report
 *
 */

namespace ommu\report\controllers\history;

use Yii;
use app\components\Controller;
use mdm\admin\components\AccessControl;
use yii\filters\VerbFilter;
use ommu\report\models\ReportStatus;
use ommu\report\models\search\ReportStatus as ReportStatusSearch;
use ommu\report\models\Reports;

class StatusController extends Controller
{
	/**
	 * {@inheritdoc}
	 */
	public function init()
	{
        parent::init();

        if (Yii::$app->request->get('id') || Yii::$app->request->get('report')) {
			$this->subMenu = $this->module->params['report_submenu'];
        }
	}

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
	 * {@inheritdoc}
	 */
	public function actionIndex()
	{
        return $this->redirect(['manage']);
	}

	/**
	 * Lists all ReportStatus models.
	 * @return mixed
	 */
	public function actionManage()
	{
        $searchModel = new ReportStatusSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        $gridColumn = Yii::$app->request->get('GridColumn', null);
        $cols = [];
        if ($gridColumn != null && count($gridColumn) > 0) {
            foreach ($gridColumn as $key => $val) {
                if ($gridColumn[$key] == 1) {
                    $cols[] = $key;
                }
            }
        }
        $columns = $searchModel->getGridColumn($cols);

        if (($report = Yii::$app->request->get('report')) != null) {
            $this->subMenuParam = $report;
			$report = \ommu\report\models\Reports::findOne($report);
		}
        if (($user = Yii::$app->request->get('user')) != null) {
            $user = \app\models\Users::findOne($user);
        }

		$this->view->title = Yii::t('app', 'Statuses');
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
	 * Displays a single ReportStatus model.
	 * @param integer $id
	 * @return mixed
	 */
	public function actionView($id)
	{
        $model = $this->findModel($id);
		$this->subMenuParam = $model->report_id;

		$this->view->title = Yii::t('app', 'Detail Status: {report-id}', ['report-id' => Reports::htmlHardDecode($model->report->report_body)]);
		$this->view->description = '';
		$this->view->keywords = '';
		return $this->oRender('admin_view', [
			'model' => $model,
			'small' => false,
		]);
	}

	/**
	 * Deletes an existing ReportStatus model.
	 * If deletion is successful, the browser will be redirected to the 'index' page.
	 * @param integer $id
	 * @return mixed
	 */
	public function actionDelete($id)
	{
		$model = $this->findModel($id);
		$model->delete();

		Yii::$app->session->setFlash('success', Yii::t('app', 'Report status success deleted.'));
		return $this->redirect(Yii::$app->request->referrer ?: ['manage', 'report' => $model->report_id]);
	}

	/**
	 * Finds the ReportStatus model based on its primary key value.
	 * If the model is not found, a 404 HTTP exception will be thrown.
	 * @param integer $id
	 * @return ReportStatus the loaded model
	 * @throws NotFoundHttpException if the model cannot be found
	 */
	protected function findModel($id)
	{
        if (($model = ReportStatus::findOne($id)) !== null) {
            return $model;
        }

		throw new \yii\web\NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
	}
}
