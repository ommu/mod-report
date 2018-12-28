<?php
/**
 * Reports
 *
 * Reports represents the model behind the search form about `ommu\report\models\Reports`.
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2017 OMMU (www.ommu.co)
 * @created date 22 September 2017, 15:57 WIB
 * @modified date 25 April 2018, 17:15 WIB
 * @link https://github.com/ommu/mod-report
 *
 */

namespace ommu\report\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use ommu\report\models\Reports as ReportsModel;

class Reports extends ReportsModel
{
	/**
	 * {@inheritdoc}
	 */
	public function rules()
	{
		return [
			[['report_id', 'status', 'cat_id', 'user_id', 'reports', 'modified_id'], 'integer'],
			[['report_url', 'report_body', 'report_message', 'report_date', 'report_ip', 'modified_date', 'updated_date',
				'category_search', 'reporter_search', 'modified_search', 'user_search'], 'safe'],
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
		$query = ReportsModel::find()->alias('t');
		$query->joinWith([
			'view view', 
			'category.title category',
			'user user', 
			'modified modified'
		]);

		// add conditions that should always apply here
		$dataProvider = new ActiveDataProvider([
			'query' => $query,
		]);

		$attributes = array_keys($this->getTableSchema()->columns);
		$attributes['cat_id'] = [
			'asc' => ['category.message' => SORT_ASC],
			'desc' => ['category.message' => SORT_DESC],
		];
		$attributes['category_search'] = [
			'asc' => ['category.message' => SORT_ASC],
			'desc' => ['category.message' => SORT_DESC],
		];
		$attributes['reporter_search'] = [
			'asc' => ['user.displayname' => SORT_ASC],
			'desc' => ['user.displayname' => SORT_DESC],
		];
		$attributes['modified_search'] = [
			'asc' => ['modified.displayname' => SORT_ASC],
			'desc' => ['modified.displayname' => SORT_DESC],
		];
		$attributes['user_search'] = [
			'asc' => ['view.users' => SORT_ASC],
			'desc' => ['view.users' => SORT_DESC],
		];
		$dataProvider->setSort([
			'attributes' => $attributes,
			'defaultOrder' => ['report_id' => SORT_DESC],
		]);

		$this->load($params);

		if(!$this->validate()) {
			// uncomment the following line if you do not want to return any records when validation fails
			// $query->where('0=1');
			return $dataProvider;
		}

		// grid filtering conditions
		$query->andFilterWhere([
			't.report_id' => $this->report_id,
			't.status' => isset($params['status']) ? $params['status'] : $this->status,
			't.cat_id' => isset($params['category']) ? $params['category'] : $this->cat_id,
			't.user_id' => isset($params['user']) ? $params['user'] : $this->user_id,
			't.reports' => $this->reports,
			'cast(t.report_date as date)' => $this->report_date,
			'cast(t.modified_date as date)' => $this->modified_date,
			't.modified_id' => isset($params['modified']) ? $params['modified'] : $this->modified_id,
			'cast(t.updated_date as date)' => $this->updated_date,
		]);

		$query->andFilterWhere(['like', 't.report_url', $this->report_url])
			->andFilterWhere(['like', 't.report_body', $this->report_body])
			->andFilterWhere(['like', 't.report_message', $this->report_message])
			->andFilterWhere(['like', 't.report_ip', $this->report_ip])
			->andFilterWhere(['like', 'category.message', $this->category_search])
			->andFilterWhere(['like', 'user.displayname', $this->reporter_search])
			->andFilterWhere(['like', 'modified.displayname', $this->modified_search]);

		return $dataProvider;
	}
}
