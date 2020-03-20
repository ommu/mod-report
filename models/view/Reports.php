<?php
/**
 * Reports
 * 
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2018 OMMU (www.ommu.id)
 * @created date 26 April 2018, 08:56 WIB
 * @link https://github.com/ommu/mod-report
 *
 * This is the model class for table "_reports".
 *
 * The followings are the available columns in table "_reports":
 * @property integer $report_id
 * @property string $history_resolved
 * @property string $history_unresolved
 * @property integer $history_all
 * @property string $comments
 * @property integer $comment_all
 * @property string $users
 * @property integer $user_all
 *
 */

namespace ommu\report\models\view;

use Yii;
use yii\helpers\Html;
use yii\helpers\Url;

class Reports extends \app\components\ActiveRecord
{
	public $gridForbiddenColumn = [];

	/**
	 * @return string the associated database table name
	 */
	public static function tableName()
	{
		return '_reports';
	}

	/**
	 * @return string the primarykey column
	 */
	public static function primaryKey()
	{
		return ['report_id'];
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		return [
			[['report_id', 'history_all', 'comment_all', 'user_all'], 'integer'],
			[['history_resolved', 'history_unresolved', 'comments', 'users'], 'number'],
		];
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return [
			'report_id' => Yii::t('app', 'Report'),
			'history_resolved' => Yii::t('app', 'History Resolved'),
			'history_unresolved' => Yii::t('app', 'History Unresolved'),
			'history_all' => Yii::t('app', 'History All'),
			'comments' => Yii::t('app', 'Comments'),
			'comment_all' => Yii::t('app', 'Comment All'),
			'users' => Yii::t('app', 'Users'),
			'user_all' => Yii::t('app', 'User All'),
		];
	}

	/**
	 * Set default columns to display
	 */
	public function init()
	{
		parent::init();

		if(!(Yii::$app instanceof \app\components\Application))
			return;

		if(!$this->hasMethod('search'))
			return;

		$this->templateColumns['_no'] = [
			'header' => '#',
			'class' => 'app\components\grid\SerialColumn',
			'contentOptions' => ['class'=>'text-center'],
		];
		$this->templateColumns['report_id'] = [
			'attribute' => 'report_id',
			'value' => function($model, $key, $index, $column) {
				return $model->report_id;
			},
		];
		$this->templateColumns['history_resolved'] = [
			'attribute' => 'history_resolved',
			'value' => function($model, $key, $index, $column) {
				return $model->history_resolved;
			},
		];
		$this->templateColumns['history_unresolved'] = [
			'attribute' => 'history_unresolved',
			'value' => function($model, $key, $index, $column) {
				return $model->history_unresolved;
			},
		];
		$this->templateColumns['history_all'] = [
			'attribute' => 'history_all',
			'value' => function($model, $key, $index, $column) {
				return $model->history_all;
			},
		];
		$this->templateColumns['comments'] = [
			'attribute' => 'comments',
			'value' => function($model, $key, $index, $column) {
				return $model->comments;
			},
		];
		$this->templateColumns['comment_all'] = [
			'attribute' => 'comment_all',
			'value' => function($model, $key, $index, $column) {
				return $model->comment_all;
			},
		];
		$this->templateColumns['users'] = [
			'attribute' => 'users',
			'value' => function($model, $key, $index, $column) {
				return $model->users;
			},
		];
		$this->templateColumns['user_all'] = [
			'attribute' => 'user_all',
			'value' => function($model, $key, $index, $column) {
				return $model->user_all;
			},
		];
	}

	/**
	 * User get information
	 */
	public static function getInfo($id, $column=null)
	{
		if($column != null) {
			$model = self::find();
			if(is_array($column))
				$model->select($column);
			else
				$model->select([$column]);
			$model = $model->where(['report_id' => $id])->one();
			return is_array($column) ? $model : $model->$column;
			
		} else {
			$model = self::findOne($id);
			return $model;
		}
	}
}
