<?php
/**
 * Reports
 *
 * This is the ActiveQuery class for [[\ommu\report\models\Reports]].
 * @see \ommu\report\models\Reports
 * 
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2019 OMMU (www.ommu.co)
 * @created date 17 January 2019, 11:37 WIB
 * @link https://github.com/ommu/mod-report
 *
 */

namespace ommu\report\models\query;

class Reports extends \yii\db\ActiveQuery
{
	/*
	public function active()
	{
		return $this->andWhere('[[status]]=1');
	}
	*/

	/**
	 * {@inheritdoc}
	 */
	public function resolved() 
	{
		return $this->andWhere(['status' => 1]);
	}

	/**
	 * {@inheritdoc}
	 */
	public function unresolved() 
	{
		return $this->andWhere(['status' => 0]);
	}

	/**
	 * {@inheritdoc}
	 * @return \ommu\report\models\Reports[]|array
	 */
	public function all($db = null)
	{
		return parent::all($db);
	}

	/**
	 * {@inheritdoc}
	 * @return \ommu\report\models\Reports|array|null
	 */
	public function one($db = null)
	{
		return parent::one($db);
	}
}
