<?php
/**
 * ViewController
 * @var $this ommu\report\controllers\history\ViewController
 * @var $model ommu\report\models\ReportView
 *
 * ViewController implements the CRUD actions for ReportView model.
 * Reference start
 * TOC :
 *  Index
 *  Manage
 *  Delete
 *
 *  findModel
 *
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2022 OMMU (www.ommu.id)
 * @created date 28 August 2022, 09:25 WIB
 * @link https://github.com/ommu/mod-report
 *
 */

namespace ommu\report\controllers\history;

use Yii;
use app\components\Controller;
use mdm\admin\components\AccessControl;
use yii\filters\VerbFilter;
use ommu\report\models\ReportView;
use ommu\report\models\search\ReportView as ReportViewSearch;

class ViewController extends Controller
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
	 * Lists all ReportView models.
	 * @return mixed
	 */
	public function actionManage()
	{
        $searchModel = new ReportViewSearch();
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

		$this->view->title = Yii::t('app', 'Views');
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
	 * Deletes an existing ReportView model.
	 * If deletion is successful, the browser will be redirected to the 'index' page.
	 * @param string $id
	 * @return mixed
	 */
	public function actionDelete($id)
	{
		$model = $this->findModel($id);
		$model->delete();

		Yii::$app->session->setFlash('success', Yii::t('app', 'Report view success deleted.'));
		return $this->redirect(Yii::$app->request->referrer ?: ['manage']);
	}

	/**
	 * Finds the ReportView model based on its primary key value.
	 * If the model is not found, a 404 HTTP exception will be thrown.
	 * @param string $id
	 * @return ReportView the loaded model
	 * @throws NotFoundHttpException if the model cannot be found
	 */
	protected function findModel($id)
	{
        if (($model = ReportView::findOne($id)) !== null) {
            return $model;
        }

		throw new \yii\web\NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
	}
}