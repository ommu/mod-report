<?php
/**
 * ReportStatus
 *
 * This is the ActiveQuery class for [[\ommu\report\models\ReportStatus]].
 * @see \ommu\report\models\ReportStatus
 * 
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)811-2540-432
 * @copyright Copyright (c) 2019 OMMU (www.ommu.id)
 * @created date 18 January 2019, 14:57 WIB
 * @link https://github.com/ommu/mod-report
 *
 */

namespace ommu\report\models\query;

class ReportStatus extends \yii\db\ActiveQuery
{
	/*
	public function active()
	{
		return $this->andWhere('[[status]]=1');
	}
	*/

	/**
	 * {@inheritdoc}
	 * @return \ommu\report\models\ReportStatus[]|array
	 */
	public function all($db = null)
	{
		return parent::all($db);
	}

	/**
	 * {@inheritdoc}
	 * @return \ommu\report\models\ReportStatus|array|null
	 */
	public function one($db = null)
	{
		return parent::one($db);
	}
}
