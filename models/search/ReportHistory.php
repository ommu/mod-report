<?php
/**
 * ReportHistory
 * version: 0.0.1
 *
 * ReportHistory represents the model behind the search form about `app\modules\report\models\ReportHistory`.
 *
 * @copyright Copyright (c) 2017 ECC UGM (ecc.ft.ugm.ac.id)
 * @link http://ecc.ft.ugm.ac.id
 * @author Aziz Masruhan <aziz.masruhan@gmail.com>
 * @created date 22 September 2017, 13:57 WIB
 * @contact (+62)857-4115-5177
 *
 */

namespace app\modules\report\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\report\models\ReportHistory as ReportHistoryModel;
//use app\modules\report\models\Reports;
//use app\coremodules\user\models\Users;

class ReportHistory extends ReportHistoryModel
{
	// Variable Search	
	public $reports_search;
	public $user_search;

	/**
	 * @inheritdoc
	 */
	public function rules()
	{
		return [
			[['id', 'report_id', 'user_id'], 'integer'],
            [['report_date', 'report_ip',
				'reports_search', 'user_search'], 'safe'],
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
		$query->joinWith(['reports reports', 'user user']);

		// add conditions that should always apply here
		$dataProvider = new ActiveDataProvider([
			'query' => $query,
		]);

		$attributes = array_keys($this->getTableSchema()->columns);
		$attributes['reports_search'] = [
			'asc' => ['reports.id' => SORT_ASC],
			'desc' => ['reports.id' => SORT_DESC],
		];
		$attributes['user_search'] = [
			'asc' => ['user.displayname' => SORT_ASC],
			'desc' => ['user.displayname' => SORT_DESC],
		];
		$dataProvider->setSort([
			'attributes' => $attributes,
			'defaultOrder' => ['id' => SORT_DESC],
		]);

		$this->load($params);

		if (!$this->validate()) {
			// uncomment the following line if you do not want to return any records when validation fails
			// $query->where('0=1');
			return $dataProvider;
		}

		// grid filtering conditions
		$query->andFilterWhere([
			't.id' => $this->id,
			't.report_id' => isset($params['reports']) ? $params['reports'] : $this->report_id,
            't.user_id' => isset($params['user']) ? $params['user'] : $this->user_id,
            'cast(t.report_date as date)' => $this->report_date,
        ]);

        $query->andFilterWhere(['like', 't.report_ip', $this->report_ip])
            ->andFilterWhere(['like', 'reports.id', $this->reports_search])
            ->andFilterWhere(['like', 'user.displayname', $this->user_search]);

		return $dataProvider;
	}
}
