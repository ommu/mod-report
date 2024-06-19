<?php
/**
 * ReportComment
 *
 * This is the ActiveQuery class for [[\ommu\report\models\ReportComment]].
 * @see \ommu\report\models\ReportComment
 * 
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)811-2540-432
 * @copyright Copyright (c) 2019 OMMU (www.ommu.id)
 * @created date 18 January 2019, 14:56 WIB
 * @link https://github.com/ommu/mod-report
 *
 */

namespace ommu\report\models\query;

class ReportComment extends \yii\db\ActiveQuery
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
	 * @return \ommu\report\models\ReportComment[]|array
	 */
	public function all($db = null)
	{
		return parent::all($db);
	}

	/**
	 * {@inheritdoc}
	 * @return \ommu\report\models\ReportComment|array|null
	 */
	public function one($db = null)
	{
		return parent::one($db);
	}
}
