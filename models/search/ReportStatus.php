<?php
/**
 * ReportStatus
 * version: 0.0.1
 *
 * ReportStatus represents the model behind the search form about `app\modules\report\models\ReportStatus`.
 *
 * @copyright Copyright (c) 2017 ECC UGM (ecc.ft.ugm.ac.id)
 * @link http://ecc.ft.ugm.ac.id
 * @author Aziz Masruhan <aziz.masruhan@gmail.com>
 * @created date 22 September 2017, 16:03 WIB
 * @contact (+62)857-4115-5177
 *
 */

namespace app\modules\report\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\report\models\ReportStatus as ReportStatusModel;
//use app\modules\report\models\Reports;
//use app\coremodules\user\models\Users;

class ReportStatus extends ReportStatusModel
{
	// Variable Search	
	public $reports_search;
	public $user_search;
	public $modified_search;

	/**
	 * @inheritdoc
	 */
	public function rules()
	{
		return [
			[['history_id', 'status', 'report_id', 'user_id', 'modified_id'], 'integer'],
            [['report_message', 'updated_date', 'updated_ip', 'modified_date',
				'reports_search', 'user_search', 'modified_search'], 'safe'],
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
		$query = ReportStatusModel::find()->alias('t');
		$query->joinWith(['reports reports', 'user user', 'modified modified']);

		// add conditions that should always apply here
		$dataProvider = new ActiveDataProvider([
			'query' => $query,
		]);

		$attributes = array_keys($this->getTableSchema()->columns);
		$attributes['reports_search'] = [
			'asc' => ['reports.history_id' => SORT_ASC],
			'desc' => ['reports.history_id' => SORT_DESC],
		];
		$attributes['user_search'] = [
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

		if (!$this->validate()) {
			// uncomment the following line if you do not want to return any records when validation fails
			// $query->where('0=1');
			return $dataProvider;
		}

		// grid filtering conditions
		$query->andFilterWhere([
			't.history_id' => $this->history_id,
            't.status' => $this->status,
			't.report_id' => isset($params['reports']) ? $params['reports'] : $this->report_id,
            't.user_id' => isset($params['user']) ? $params['user'] : $this->user_id,
            'cast(t.updated_date as date)' => $this->updated_date,
            'cast(t.modified_date as date)' => $this->modified_date,
            't.modified_id' => isset($params['modified']) ? $params['modified'] : $this->modified_id,
        ]);

        $query->andFilterWhere(['like', 't.report_message', $this->report_message])
            ->andFilterWhere(['like', 't.updated_ip', $this->updated_ip])
            ->andFilterWhere(['like', 'reports.history_id', $this->reports_search])
            ->andFilterWhere(['like', 'user.displayname', $this->user_search])
            ->andFilterWhere(['like', 'modified.displayname', $this->modified_search]);

		return $dataProvider;
	}
}
