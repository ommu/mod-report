<?php
/**
 * ReportSetting
 * version: 0.0.1
 *
 * ReportSetting represents the model behind the search form about `app\modules\report\models\ReportSetting`.
 *
 * @copyright Copyright (c) 2017 ECC UGM (ecc.ft.ugm.ac.id)
 * @link http://ecc.ft.ugm.ac.id
 * @author Aziz Masruhan <aziz.masruhan@gmail.com>
 * @created date 22 September 2017, 13:49 WIB
 * @contact (+62)857-4115-5177
 *
 */

namespace app\modules\report\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\report\models\ReportSetting as ReportSettingModel;
//use app\coremodules\user\models\Users;

class ReportSetting extends ReportSettingModel
{
	// Variable Search	
	public $modified_search;

	/**
	 * @inheritdoc
	 */
	public function rules()
	{
		return [
			[['id', 'permission', 'auto_report_cat_id', 'modified_id'], 'integer'],
            [['license', 'meta_keyword', 'meta_description', 'modified_date',
				'modified_search'], 'safe'],
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
		$query = ReportSettingModel::find()->alias('t');
		$query->joinWith(['modified modified']);

		// add conditions that should always apply here
		$dataProvider = new ActiveDataProvider([
			'query' => $query,
		]);

		$attributes = array_keys($this->getTableSchema()->columns);
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
            't.permission' => $this->permission,
            't.auto_report_cat_id' => $this->auto_report_cat_id,
            'cast(t.modified_date as date)' => $this->modified_date,
            't.modified_id' => isset($params['modified']) ? $params['modified'] : $this->modified_id,
		]);
		
        $query->andFilterWhere(['like', 't.license', $this->license])
            ->andFilterWhere(['like', 't.meta_keyword', $this->meta_keyword])
            ->andFilterWhere(['like', 't.meta_description', $this->meta_description])
            ->andFilterWhere(['like', 'modified.displayname', $this->modified_search]);

		return $dataProvider;
	}
}
