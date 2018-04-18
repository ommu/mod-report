<?php
/**
 * ReportUser
 * version: 0.0.1
 *
 * ReportUser represents the model behind the search form about `app\modules\report\models\ReportUser`.
 *
 * @copyright Copyright (c) 2017 ECC UGM (ecc.ft.ugm.ac.id)
 * @link http://ecc.ft.ugm.ac.id
 * @author Aziz Masruhan <aziz.masruhan@gmail.com>
 * @created date 22 September 2017, 13:56 WIB
 * @contact (+62)857-4115-5177
 *
 */

namespace app\modules\report\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\report\models\ReportUser as ReportUserModel;
//use app\modules\report\models\Reports;
//use app\coremodules\user\models\Users;

class ReportUser extends ReportUserModel
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
			[['id', 'publish', 'report_id', 'user_id', 'modified_id'], 'integer'],
            [['creation_date', 'modified_date', 'updated_date',
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
		$query = ReportUserModel::find()->alias('t');
		$query->joinWith(['reports reports', 'user user', 'modified modified']);

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
		$attributes['modified_search'] = [
			'asc' => ['modified.displayname' => SORT_ASC],
			'desc' => ['modified.displayname' => SORT_DESC],
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
			't.publish' => isset($params['publish']) ? 1 : $this->publish,
			't.report_id' => isset($params['reports']) ? $params['reports'] : $this->report_id,
            't.user_id' => isset($params['user']) ? $params['user'] : $this->user_id,
            'cast(t.creation_date as date)' => $this->creation_date,
            'cast(t.modified_date as date)' => $this->modified_date,
            't.modified_id' => isset($params['modified']) ? $params['modified'] : $this->modified_id,
            'cast(t.updated_date as date)' => $this->updated_date,
        ]);
		
		if(!isset($_GET['trash']))
			$query->andFilterWhere(['IN', 't.publish', [0,1]]);
		else
			$query->andFilterWhere(['NOT IN', 't.publish', [0,1]]);

        $query->andFilterWhere(['like', 'reports.id', $this->reports_search])
            ->andFilterWhere(['like', 'user.displayname', $this->user_search])
            ->andFilterWhere(['like', 'modified.displayname', $this->modified_search]);

		return $dataProvider;
	}
}
