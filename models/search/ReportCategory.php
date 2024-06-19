<?php
/**
 * ReportCategory
 *
 * ReportCategory represents the model behind the search form about `ommu\report\models\ReportCategory`.
 *
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)811-2540-432
 * @copyright Copyright (c) 2017 OMMU (www.ommu.id)
 * @created date 22 September 2017, 16:13 WIB
 * @modified date 16 January 2019, 16:25 WIB
 * @link https://github.com/ommu/mod-report
 *
 */

namespace ommu\report\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use ommu\report\models\ReportCategory as ReportCategoryModel;

class ReportCategory extends ReportCategoryModel
{
	/**
	 * {@inheritdoc}
	 */
	public function rules()
	{
		return [
			[['cat_id', 'publish', 'name', 'desc', 'creation_id', 'modified_id', 'oReport', 'oUnresolved', 'oResolved'], 'integer'],
			[['creation_date', 'modified_date', 'updated_date', 'slug', 'name_i', 'desc_i', 'creationDisplayname', 'modifiedDisplayname'], 'safe'],
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
            $query = ReportCategoryModel::find()->alias('t');
        } else {
            $query = ReportCategoryModel::find()->alias('t')
                ->select($column);
        }
		$query->joinWith([
			// 'grid grid', 
			// 'title title', 
			// 'description description', 
			// 'creation creation', 
			// 'modified modified'
		]);
        if ((isset($params['sort']) && in_array($params['sort'], ['oReport', '-oReport', 'oUnresolved', '-oUnresolved', 'oResolved', '-oResolved'])) || (
            (isset($params['oReport']) && $params['oReport'] != '') ||
            (isset($params['oUnresolved']) && $params['oUnresolved'] != '') ||
            (isset($params['oResolved']) && $params['oResolved'] != '')
        )) {
            $query->joinWith(['grid grid']);
        }
        if ((isset($params['sort']) && in_array($params['sort'], ['name_i', '-name_i'])) || 
            (isset($params['name_i']) && $params['name_i'] != '')
        ) {
            $query->joinWith(['title title']);
        }
        if ((isset($params['sort']) && in_array($params['sort'], ['desc_i', '-desc_i'])) || 
            (isset($params['desc_i']) && $params['desc_i'] != '')
        ) {
            $query->joinWith(['description description']);
        }
        if ((isset($params['sort']) && in_array($params['sort'], ['creationDisplayname', '-creationDisplayname'])) || 
            (isset($params['creationDisplayname']) && $params['creationDisplayname'] != '')
        ) {
            $query->joinWith(['creation creation']);
        }
        if ((isset($params['sort']) && in_array($params['sort'], ['modifiedDisplayname', '-modifiedDisplayname'])) || 
            (isset($params['modifiedDisplayname']) && $params['modifiedDisplayname'] != '')
        ) {
            $query->joinWith(['modified modified']);
        }

		$query->groupBy(['cat_id']);

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
		$attributes['name_i'] = [
			'asc' => ['title.message' => SORT_ASC],
			'desc' => ['title.message' => SORT_DESC],
		];
		$attributes['desc_i'] = [
			'asc' => ['description.message' => SORT_ASC],
			'desc' => ['description.message' => SORT_DESC],
		];
		$attributes['creationDisplayname'] = [
			'asc' => ['creation.displayname' => SORT_ASC],
			'desc' => ['creation.displayname' => SORT_DESC],
		];
		$attributes['modifiedDisplayname'] = [
			'asc' => ['modified.displayname' => SORT_ASC],
			'desc' => ['modified.displayname' => SORT_DESC],
		];
		$attributes['oReport'] = [
			'asc' => ['grid.report' => SORT_ASC],
			'desc' => ['grid.report' => SORT_DESC],
		];
		$attributes['oUnresolved'] = [
			'asc' => ['grid.unresolved' => SORT_ASC],
			'desc' => ['grid.unresolved' => SORT_DESC],
		];
		$attributes['oResolved'] = [
			'asc' => ['grid.resolved' => SORT_ASC],
			'desc' => ['grid.resolved' => SORT_DESC],
		];
		$dataProvider->setSort([
			'attributes' => $attributes,
			'defaultOrder' => ['cat_id' => SORT_DESC],
		]);

        if (Yii::$app->request->get('cat_id')) {
            unset($params['cat_id']);
        }

		$this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

		// grid filtering conditions
		$query->andFilterWhere([
			't.cat_id' => $this->cat_id,
			't.name' => $this->name,
			't.desc' => $this->desc,
			'cast(t.creation_date as date)' => $this->creation_date,
			't.creation_id' => isset($params['creation']) ? $params['creation'] : $this->creation_id,
			'cast(t.modified_date as date)' => $this->modified_date,
			't.modified_id' => isset($params['modified']) ? $params['modified'] : $this->modified_id,
			'cast(t.updated_date as date)' => $this->updated_date,
		]);

        if (isset($params['oReport']) && $params['oReport'] != '') {
            if ($this->oReport == 1) {
                $query->andWhere(['<>', 'grid.report', 0]);
            } else if ($this->oReport == 0) {
                $query->andWhere(['=', 'grid.report', 0]);
            }
        }
        if (isset($params['oUnresolved']) && $params['oUnresolved'] != '') {
            if ($this->oUnresolved == 1) {
                $query->andWhere(['<>', 'grid.unresolved', 0]);
            } else if ($this->oUnresolved == 0) {
                $query->andWhere(['=', 'grid.unresolved', 0]);
            }
        }
        if (isset($params['oResolved']) && $params['oResolved'] != '') {
            if ($this->oResolved == 1) {
                $query->andWhere(['<>', 'grid.resolved', 0]);
            } else if ($this->oResolved == 0) {
                $query->andWhere(['=', 'grid.resolved', 0]);
            }
        }

        if ((!isset($params['publish']) || (isset($params['publish']) && $params['publish'] == '')) && !$this->publish) {
            $query->andFilterWhere(['IN', 't.publish', [0,1]]);
        } else {
            $query->andFilterWhere(['t.publish' => $this->publish]);
        }

        if (isset($params['trash']) && $params['trash'] == 1) {
            $query->andFilterWhere(['NOT IN', 't.publish', [0,1]]);
        }

		$query->andFilterWhere(['like', 't.slug', $this->slug])
			->andFilterWhere(['like', 'title.message', $this->name_i])
			->andFilterWhere(['like', 'description.message', $this->desc_i])
			->andFilterWhere(['like', 'creation.displayname', $this->creationDisplayname])
			->andFilterWhere(['like', 'modified.displayname', $this->modifiedDisplayname]);

		return $dataProvider;
	}
}
