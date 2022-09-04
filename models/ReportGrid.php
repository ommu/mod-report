<?php
/**
 * ReportGrid
 * 
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2022 OMMU (www.ommu.id)
 * @created date 4 September 2022, 22:52 WIB
 * @link https://github.com/ommu/mod-report
 *
 * This is the model class for table "ommu_report_grid".
 *
 * The followings are the available columns in table "ommu_report_grid":
 * @property integer $id
 * @property integer $comment
 * @property integer $history
 * @property integer $read
 * @property integer $status
 * @property integer $user
 * @property string $modified_date
 *
 * The followings are the available model relations:
 * @property Reports $report
 *
 */

namespace ommu\report\models;

use Yii;

class ReportGrid extends \app\components\ActiveRecord
{
    public $gridForbiddenColumn = [];

	/**
	 * @return string the associated database table name
	 */
	public static function tableName()
	{
		return 'ommu_report_grid';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		return [
			[['id', 'comment', 'history', 'read', 'status', 'user'], 'required'],
			[['id', 'comment', 'history', 'read', 'status', 'user'], 'integer'],
			[['id'], 'unique'],
			[['id'], 'exist', 'skipOnError' => true, 'targetClass' => Reports::className(), 'targetAttribute' => ['id' => 'report_id']],
		];
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return [
			'id' => Yii::t('app', 'ID'),
			'comment' => Yii::t('app', 'Comment'),
			'history' => Yii::t('app', 'History'),
			'read' => Yii::t('app', 'Read'),
			'status' => Yii::t('app', 'Status'),
			'user' => Yii::t('app', 'User'),
			'modified_date' => Yii::t('app', 'Modified Date'),
		];
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function get0()
	{
		return $this->hasOne(Reports::className(), ['report_id' => 'id']);
	}

	/**
	 * Set default columns to display
	 */
	public function init()
	{
        parent::init();

        if (!(Yii::$app instanceof \app\components\Application)) {
            return;
        }

        if (!$this->hasMethod('search')) {
            return;
        }

		$this->templateColumns['_no'] = [
			'header' => '#',
			'class' => 'app\components\grid\SerialColumn',
			'contentOptions' => ['class' => 'text-center'],
		];
		$this->templateColumns['id'] = [
			'attribute' => 'id',
			'value' => function($model, $key, $index, $column) {
				return isset($model->report) ? $model->report->report_body : '-';
				// return $model->id;
			},
			'visible' => !Yii::$app->request->get('id') ? true : false,
		];
		$this->templateColumns['comment'] = [
			'attribute' => 'comment',
			'value' => function($model, $key, $index, $column) {
				return $model->comment;
			},
		];
		$this->templateColumns['history'] = [
			'attribute' => 'history',
			'value' => function($model, $key, $index, $column) {
				return $model->history;
			},
		];
		$this->templateColumns['read'] = [
			'attribute' => 'read',
			'value' => function($model, $key, $index, $column) {
				return $model->read;
			},
		];
		$this->templateColumns['status'] = [
			'attribute' => 'status',
			'value' => function($model, $key, $index, $column) {
				return $model->status;
			},
		];
		$this->templateColumns['user'] = [
			'attribute' => 'user',
			'value' => function($model, $key, $index, $column) {
				return $model->user;
			},
		];
		$this->templateColumns['modified_date'] = [
			'attribute' => 'modified_date',
			'value' => function($model, $key, $index, $column) {
				return Yii::$app->formatter->asDatetime($model->modified_date, 'medium');
			},
			'filter' => $this->filterDatepicker($this, 'modified_date'),
		];
	}

	/**
	 * User get information
	 */
	public static function getInfo($id, $column=null)
	{
        if ($column != null) {
            $model = self::find();
            if (is_array($column)) {
                $model->select($column);
            } else {
                $model->select([$column]);
            }
            $model = $model->where(['id' => $id])->one();
            return is_array($column) ? $model : $model->$column;

        } else {
            $model = self::findOne($id);
            return $model;
        }
	}
}
