<?php
/**
 * SiteController
 * @var $this ommu\report\controllers\SiteController
 * @var $model ommu\report\models\Reports
 *
 * SiteController implements the CRUD actions for Reports model.
 * Reference start
 * TOC :
 *	Index
 *	Add
 *
 *	findModel
 *
 * @author Putra Sudaryanto <putra@ommu.co>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2019 OMMU (www.ommu.co)
 * @created date 7 June 2019, 07:11 WIB
 * @link https://github.com/ommu/mod-report
 *
 */

namespace ommu\report\controllers;

use Yii;
use app\components\Controller;
use mdm\admin\components\AccessControl;
use yii\filters\VerbFilter;
use ommu\report\models\Reports;
use yii\data\ActiveDataProvider;

class SiteController extends Controller
{
	public static $backoffice = false;

	/**
	 * {@inheritdoc}
	 */
	public function actionIndex()
	{
		return $this->redirect(['add']);
	}

	/**
	 * Creates a new Reports model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 * @return mixed
	 */
	public function actionAdd()
	{
		$model = new Reports();
		$model->scenario = Reports::SCENARIO_REPORT;

		if(Yii::$app->request->isPost) {
			$model->load(Yii::$app->request->post());
			// $postData = Yii::$app->request->post();
			// $model->load($postData);
			// $model->order = $postData['order'] ? $postData['order'] : 0;

			if($model->save()) {
				return \yii\helpers\Json::encode([
					'error' => 0,
					'message' => Yii::t('app', 'Report success added.'),
				]);

			} else {
				if(Yii::$app->request->isAjax)
					return \yii\helpers\Json::encode(\app\components\widgets\ActiveForm::validate($model));
			}
		}

		$this->view->title = Yii::t('app', 'Report This');
		$this->view->description = '';
		$this->view->keywords = '';
		return $this->oRender('admin_add', [
			'model' => $model,
		]);
	}

	/**
	 * Finds the Reports model based on its primary key value.
	 * If the model is not found, a 404 HTTP exception will be thrown.
	 * @param integer $id
	 * @return Reports the loaded model
	 * @throws NotFoundHttpException if the model cannot be found
	 */
	protected function findModel($id)
	{
		if(($model = Reports::findOne($id)) !== null)
			return $model;

		throw new \yii\web\NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
	}
}
