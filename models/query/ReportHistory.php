<?php
/**
 * ReportHistory
 *
 * This is the ActiveQuery class for [[\ommu\report\models\ReportHistory]].
 * @see \ommu\report\models\ReportHistory
 * 
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2019 OMMU (www.ommu.co)
 * @created date 18 January 2019, 14:56 WIB
 * @link https://github.com/ommu/mod-report
 *
 */

namespace ommu\report\models\query;

class ReportHistory extends \yii\db\ActiveQuery
{
	/*
	public function active()
	{
		return $this->andWhere('[[status]]=1');
	}
	*/

	/**
	 * {@inheritdoc}
	 * @return \ommu\report\models\ReportHistory[]|array
	 */
	public function all($db = null)
	{
		return parent::all($db);
	}

	/**
	 * {@inheritdoc}
	 * @return \ommu\report\models\ReportHistory|array|null
	 */
	public function one($db = null)
	{
		return parent::one($db);
	}
}
