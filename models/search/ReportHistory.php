<?php
/**
 * ReportHistory
 *
 * ReportHistory represents the model behind the search form about `ommu\report\models\ReportHistory`.
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2018 Ommu Platform (www.ommu.co)
 * @created date 22 September 2017, 13:57 WIB
 * @modified date 26 April 2018, 06:34 WIB
 * @link https://github.com/ommu/mod-report
 *
 */

namespace ommu\report\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use ommu\report\models\ReportHistory as ReportHistoryModel;

class ReportHistory extends ReportHistoryModel
{
	/**
	 * @inheritdoc
	 */
	public function rules()
	{
		return [
			[['id', 'report_id', 'user_id'], 'integer'],
			[['report_date', 'report_ip',
				'category_search', 'report_search', 'reporter_search'], 'safe'],
		];
	}

	/**
	 * @inheritdoc
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
		$query = ReportHistoryModel::find()->alias('t');
		$query->joinWith([
			'report report', 
			'report.category.title category', 
			'user user'
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
		$dataProvider->setSort([
			'attributes' => $attributes,
			'defaultOrder' => ['id' => SORT_DESC],
		]);

		$this->load($params);

		if(!$this->validate()) {
			// uncomment the following line if you do not want to return any records when validation fails
			// $query->where('0=1');
			return $dataProvider;
		}

		// grid filtering conditions
		$query->andFilterWhere([
			't.id' => $this->id,
			't.report_id' => isset($params['report']) ? $params['report'] : $this->report_id,
			't.user_id' => isset($params['user']) ? $params['user'] : $this->user_id,
			'cast(t.report_date as date)' => $this->report_date,
			'report.cat_id' => isset($params['category']) ? $params['category'] : $this->category_search,
		]);

		$query->andFilterWhere(['like', 't.report_ip', $this->report_ip])
			->andFilterWhere(['like', 'report.report_body', $this->report_search])
			->andFilterWhere(['like', 'user.displayname', $this->reporter_search]);

		return $dataProvider;
	}
}