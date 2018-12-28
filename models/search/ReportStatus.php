<?php
/**
 * ReportStatus
 *
 * ReportStatus represents the model behind the search form about `ommu\report\models\ReportStatus`.
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2018 OMMU (www.ommu.co)
 * @created date 22 September 2017, 16:03 WIB
 * @modified date 26 April 2018, 09:31 WIB
 * @link https://github.com/ommu/mod-report
 *
 */

namespace ommu\report\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use ommu\report\models\ReportStatus as ReportStatusModel;

class ReportStatus extends ReportStatusModel
{
	/**
	 * {@inheritdoc}
	 */
	public function rules()
	{
		return [
			[['history_id', 'status', 'report_id', 'user_id', 'modified_id'], 'integer'],
			[['report_message', 'updated_date', 'updated_ip', 'modified_date',
				'category_search', 'report_search', 'reporter_search', 'modified_search'], 'safe'],
		];
	}

	/**
	 * {@inheritdoc}
	 */
	public function scenarios()
	{
		// bypass scenarios() implementation in the parent class
		return Model::scenarios();
	}

	/**
	 * Tambahkan fungsi beforeValidate ini pada model search untuk menumpuk validasi pd model induk. 
	 * dan "jangan" tambahkan parent::beforeValidate, cukup "return true" saja.
	 * maka validasi yg akan dipakai hanya pd model ini, semua script yg ditaruh di beforeValidate pada model induk
	 * tidak akan dijalankan.
	 */
	public function beforeValidate() {
		return true;
	}

	/**
	 * Creates data provider instance with search query applied
	 *
	 * @param array $params
	 * @return ActiveDataProvider
	 */
	public function search($params)
	{
		$query = ReportStatusModel::find()->alias('t');
		$query->joinWith([
			'report report', 
			'report.category.title category', 
			'user user', 
			'modified modified'
		]);

		// add conditions that should always apply here
		$dataProvider = new ActiveDataProvider([
			'query' => $query,
		]);

		$attributes = array_keys($this->getTableSchema()->columns);
		$attributes['category_search'] = [
			'asc' => ['category.message' => SORT_ASC],
			'desc' => ['category.message' => SORT_DESC],
		];
		$attributes['report_search'] = [
			'asc' => ['report.report_body' => SORT_ASC],
			'desc' => ['report.report_body' => SORT_DESC],
		];
		$attributes['reporter_search'] = [
			'asc' => ['user.displayname' => SORT_ASC],
			'desc' => ['user.displayname' => SORT_DESC],
		];
		$attributes['modified_search'] = [
			'asc' => ['modified.displayname' => SORT_ASC],
			'desc' => ['modified.displayname' => SORT_DESC],
		];
		$dataProvider->setSort([
			'attributes' => $attributes,
			'defaultOrder' => ['history_id' => SORT_DESC],
		]);

		$this->load($params);

		if(!$this->validate()) {
			// uncomment the following line if you do not want to return any records when validation fails
			// $query->where('0=1');
			return $dataProvider;
		}

		// grid filtering conditions
		$query->andFilterWhere([
			't.history_id' => $this->history_id,
			't.status' => $this->status,
			't.report_id' => isset($params['report']) ? $params['report'] : $this->report_id,
			't.user_id' => isset($params['user']) ? $params['user'] : $this->user_id,
			'cast(t.updated_date as date)' => $this->updated_date,
			'cast(t.modified_date as date)' => $this->modified_date,
			't.modified_id' => isset($params['modified']) ? $params['modified'] : $this->modified_id,
			'report.cat_id' => isset($params['category']) ? $params['category'] : $this->category_search,
		]);

		$query->andFilterWhere(['like', 't.report_message', $this->report_message])
			->andFilterWhere(['like', 't.updated_ip', $this->updated_ip])
			->andFilterWhere(['like', 'report.report_body', $this->report_search])
			->andFilterWhere(['like', 'user.displayname', $this->reporter_search])
			->andFilterWhere(['like', 'modified.displayname', $this->modified_search]);

		return $dataProvider;
	}
}
