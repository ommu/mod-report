<?php
/**
 * UserController
 * @var $this ommu\report\controllers\history\UserController
 * @var $model ommu\report\models\ReportUser
 *
 * UserController implements the CRUD actions for ReportUser model.
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
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2017 OMMU (www.ommu.id)
 * @created date 22 September 2017, 13:56 WIB
 * @modified date 18 January 2019, 15:38 WIB
 * @link https://github.com/ommu/mod-report
 *
 */

namespace ommu\report\controllers\history;

use Yii;
use app\components\Controller;
use mdm\admin\components\AccessControl;
use yii\filters\VerbFilter;
use ommu\report\models\ReportUser;
use ommu\report\models\search\ReportUser as ReportUserSearch;
use ommu\report\models\Reports;

class UserController extends Controller
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
	 * Lists all ReportUser models.
	 * @return mixed
	 */
	public function actionManage()
	{
        $searchModel = new ReportUserSearch();
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

		$this->view->title = Yii::t('app', 'Users');
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
	 * Displays a single ReportUser model.
	 * @param integer $id
	 * @return mixed
	 */
	public function actionView($id)
	{
        $model = $this->findModel($id);
		$this->subMenuParam = $model->report_id;

		$this->view->title = Yii::t('app', 'Detail User: {report-id}', ['report-id' => Reports::htmlHardDecode($model->report->report_body)]);
		$this->view->description = '';
		$this->view->keywords = '';
		return $this->oRender('admin_view', [
			'model' => $model,
		]);
	}

	/**
	 * Deletes an existing ReportUser model.
	 * If deletion is successful, the browser will be redirected to the 'index' page.
	 * @param integer $id
	 * @return mixed
	 */
	public function actionDelete($id)
	{
		$model = $this->findModel($id);
		$model->publish = 2;

        if ($model->save(false, ['publish', 'modified_id'])) {
			Yii::$app->session->setFlash('success', Yii::t('app', 'Report user success deleted.'));
			return $this->redirect(Yii::$app->request->referrer ?: ['manage', 'report' => $model->report_id]);
		}
	}

	/**
	 * actionPublish an existing ReportUser model.
	 * If publish is successful, the browser will be redirected to the 'index' page.
	 * @param integer $id
	 * @return mixed
	 */
	public function actionPublish($id)
	{
		$model = $this->findModel($id);
		$replace = $model->publish == 1 ? 0 : 1;
		$model->publish = $replace;

        if ($model->save(false, ['publish', 'modified_id'])) {
			Yii::$app->session->setFlash('success', Yii::t('app', 'Report user success updated.'));
			return $this->redirect(Yii::$app->request->referrer ?: ['manage', 'report' => $model->report_id]);
		}
	}

	/**
	 * Finds the ReportUser model based on its primary key value.
	 * If the model is not found, a 404 HTTP exception will be thrown.
	 * @param integer $id
	 * @return ReportUser the loaded model
	 * @throws NotFoundHttpException if the model cannot be found
	 */
	protected function findModel($id)
	{
        if (($model = ReportUser::findOne($id)) !== null) {
            return $model;
        }

		throw new \yii\web\NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
	}
}
