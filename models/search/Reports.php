<?php
/**
 * Reports
 *
 * Reports represents the model behind the search form about `ommu\report\models\Reports`.
 *
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2017 OMMU (www.ommu.id)
 * @created date 22 September 2017, 15:57 WIB
 * @modified date 17 January 2019, 11:38 WIB
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
			[['report_id', 'status', 'read', 'cat_id', 'user_id', 'reports', 'modified_id', 'oComment', 'oRead', 'oStatus', 'oUser'], 'integer'],
			[['app', 'report_url', 'report_body', 'report_message', 'report_date', 'report_ip', 'modified_date', 'updated_date', 'categoryName', 'reporterDisplayname', 'modifiedDisplayname'], 'safe'],
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
	 *
	 * @return ActiveDataProvider
	 */
	public function search($params, $column=null)
	{
        if (!($column && is_array($column))) {
            $query = ReportsModel::find()->alias('t');
        } else {
            $query = ReportsModel::find()->alias('t')->select($column);
        }
		$query->joinWith([
            'grid grid',
			// 'category.title category', 
			// 'user user', 
			// 'modified modified'
		]);
        if ((isset($params['sort']) && in_array($params['sort'], ['cat_id', '-cat_id'])) || (isset($params['categoryName']) && $params['categoryName'] != '')) {
            $query->joinWith(['category.title category']);
        }
        if ((isset($params['sort']) && in_array($params['sort'], ['userDisplayname', '-userDisplayname'])) || (isset($params['userDisplayname']) && $params['userDisplayname'] != '')) {
            $query->joinWith(['user user']);
        }
        if ((isset($params['sort']) && in_array($params['sort'], ['modifiedDisplayname', '-modifiedDisplayname'])) || (isset($params['modifiedDisplayname']) && $params['modifiedDisplayname'] != '')) {
            $query->joinWith(['modified modified']);
        }
        // if ((isset($params['sort']) && in_array($params['sort'], ['oComment', '-oComment'])) || (isset($params['oComment']) && $params['oComment'] != '')) {
        //     $query->joinWith(['comments comments']);
        //     if (isset($params['sort']) && in_array($params['sort'], ['oComment', '-oComment'])) {
        //         $query->select(['t.*', 'count(comments.id) as oComment']);
        //     }
        // }
        // if ((isset($params['sort']) && in_array($params['sort'], ['oRead', '-oRead'])) || (isset($params['oRead']) && $params['oRead'] != '')) {
        //     $query->joinWith(['reads reads']);
        //     if (isset($params['sort']) && in_array($params['sort'], ['oRead', '-oRead'])) {
        //         $query->select(['t.*', 'count(reads.id) as oRead']);
        //     }
        // }
        // if ((isset($params['sort']) && in_array($params['sort'], ['oStatus', '-oStatus'])) || (isset($params['oStatus']) && $params['oStatus'] != '')) {
        //     $query->joinWith(['statuses statuses']);
        //     if (isset($params['sort']) && in_array($params['sort'], ['oStatus', '-oStatus'])) {
        //         $query->select(['t.*', 'count(statuses.id) as oStatus']);
        //     }
        // }
        // if ((isset($params['sort']) && in_array($params['sort'], ['oUser', '-oUser'])) || (isset($params['oUser']) && $params['oUser'] != '')) {
        //     $query->joinWith(['users users']);
        //     if (isset($params['sort']) && in_array($params['sort'], ['oUser', '-oUser'])) {
        //         $query->select(['t.*', 'count(users.id) as oUser']);
        //     }
        // }

		$query->groupBy(['report_id']);

        // add conditions that should always apply here
		$dataParams = [
			'query' => $query,
		];
        // disable pagination agar data pada api tampil semua
        if (isset($params['pagination']) && $params['pagination'] == 0) {
            $dataParams['pagination'] = false;
        }
		$dataProvider = new ActiveDataProvider($dataParams);

		$attributes = array_keys($this->getTableSchema()->columns);
		$attributes['cat_id'] = [
			'asc' => ['category.message' => SORT_ASC],
			'desc' => ['category.message' => SORT_DESC],
		];
		$attributes['categoryName'] = [
			'asc' => ['category.message' => SORT_ASC],
			'desc' => ['category.message' => SORT_DESC],
		];
		$attributes['reporterDisplayname'] = [
			'asc' => ['user.displayname' => SORT_ASC],
			'desc' => ['user.displayname' => SORT_DESC],
		];
		$attributes['modifiedDisplayname'] = [
			'asc' => ['modified.displayname' => SORT_ASC],
			'desc' => ['modified.displayname' => SORT_DESC],
		];
		$attributes['oComment'] = [
			'asc' => ['grid.comment' => SORT_ASC],
			'desc' => ['grid.comment' => SORT_DESC],
		];
		$attributes['oRead'] = [
			'asc' => ['grid.read' => SORT_ASC],
			'desc' => ['grid.read' => SORT_DESC],
		];
		$attributes['oStatus'] = [
			'asc' => ['grid.status' => SORT_ASC],
			'desc' => ['grid.status' => SORT_DESC],
		];
		$attributes['oUser'] = [
			'asc' => ['grid.user' => SORT_ASC],
			'desc' => ['grid.user' => SORT_DESC],
		];
		$dataProvider->setSort([
			'attributes' => $attributes,
			'defaultOrder' => ['report_id' => SORT_DESC],
		]);

		$this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

		// grid filtering conditions
		$query->andFilterWhere([
			't.report_id' => $this->report_id,
			't.app' => $this->app,
			't.status' => isset($params['status']) ? $params['status'] : $this->status,
			't.read' => isset($params['read']) ? $params['read'] : $this->read,
			't.cat_id' => isset($params['category']) ? $params['category'] : $this->cat_id,
			't.user_id' => isset($params['user']) ? $params['user'] : $this->user_id,
			't.reports' => $this->reports,
			'cast(t.report_date as date)' => $this->report_date,
			'cast(t.modified_date as date)' => $this->modified_date,
			't.modified_id' => isset($params['modified']) ? $params['modified'] : $this->modified_id,
			'cast(t.updated_date as date)' => $this->updated_date,
		]);

        if (isset($params['oComment']) && $params['oComment'] != '') {
            if ($this->oComment == 1) {
                $query->andWhere(['<>', 'grid.comment', 0]);
            } else if ($this->oComment == 0) {
                $query->andWhere(['=', 'grid.comment', 0]);
            }
        }
        if (isset($params['oRead']) && $params['oRead'] != '') {
            if ($this->oRead == 1) {
                $query->andWhere(['<>', 'grid.read', 0]);
            } else if ($this->oRead == 0) {
                $query->andWhere(['=', 'grid.read', 0]);
            }
        }
        if (isset($params['oStatus']) && $params['oStatus'] != '') {
            if ($this->oStatus == 1) {
                $query->andWhere(['<>', 'grid.status', 0]);
            } else if ($this->oStatus == 0) {
                $query->andWhere(['=', 'grid.status', 0]);
            }
        }
        if (isset($params['oUser']) && $params['oUser'] != '') {
            if ($this->oUser == 1) {
                $query->andWhere(['<>', 'grid.user', 0]);
            } else if ($this->oUser == 0) {
                $query->andWhere(['=', 'grid.user', 0]);
            }
        }

		$query->andFilterWhere(['or',
                ['like', 't.report_body', $this->report_body],
                ['like', 't.report_url', $this->report_body]
            ])
			->andFilterWhere(['like', 't.report_message', $this->report_message])
			->andFilterWhere(['like', 't.report_ip', $this->report_ip])
			->andFilterWhere(['like', 'category.message', $this->categoryName])
			->andFilterWhere(['like', 'user.displayname', $this->reporterDisplayname])
			->andFilterWhere(['like', 'modified.displayname', $this->modifiedDisplayname]);

		return $dataProvider;
	}
}
