<?php
/**
 * ReportUser
 *
 * This is the ActiveQuery class for [[\ommu\report\models\ReportUser]].
 * @see \ommu\report\models\ReportUser
 * 
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2019 OMMU (www.ommu.id)
 * @created date 18 January 2019, 14:57 WIB
 * @link https://github.com/ommu/mod-report
 *
 */

namespace ommu\report\models\query;

class ReportUser extends \yii\db\ActiveQuery
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
	public function published()
	{
		return $this->andWhere(['t.publish' => 1]);
	}

	/**
	 * {@inheritdoc}
	 */
	public function unpublish()
	{
		return $this->andWhere(['t.publish' => 0]);
	}

	/**
	 * {@inheritdoc}
	 */
	public function deleted()
	{
		return $this->andWhere(['t.publish' => 2]);
	}

	/**
	 * {@inheritdoc}
	 * @return \ommu\report\models\ReportUser[]|array
	 */
	public function all($db = null)
	{
		return parent::all($db);
	}

	/**
	 * {@inheritdoc}
	 * @return \ommu\report\models\ReportUser|array|null
	 */
	public function one($db = null)
	{
		return parent::one($db);
	}
}
