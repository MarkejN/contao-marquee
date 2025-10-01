<?php

/**
 * Contao Open Source CMS
 *
 * Copyright (c) 2005-2017 Leo Feyer
 *
 * @package   Marquee
 * @author    Hamid Abbaszadeh info@respinar.com
 * @license   LGPL-3.0+
 * @copyright respinar 2015-2017
 */


/**
 * Run in a custom namespace, so the class can be replaced
 */
namespace Respinar\Marquee\Model;

use Contao\Model;
use Contao\Model\Collection;

/**
 * Reads and writes Members Act
 *
 * @package   Models
 * @author    Hamid Abbaszadeh <https://respinar.com>
 * @copyright Hamid Abbaszadeh 2013-2014
 */
class MarqueeTextModel extends Model
{

	/**
	 * Table name
	 * @var string
	 */
	protected static $strTable = 'tl_marquee_text';


	/**
	 * Count published news items by their parent ID
	 *
	 * @param array   $arrPids     An array of news archive IDs
	 * @param boolean $blnFeatured If true, return only featured news, if false, return only unfeatured news
	 * @param array   $arrOptions  An optional options array
	 *
	 * @return integer The number of news items
	 */
	public static function countPublishedByPid($intPid, array $arrOptions=array())
	{
		if (empty($intPid))
		{
			return 0;
		}

		$t = static::$strTable;
		$arrColumns = array("$t.pid=?");


		if (!BE_USER_LOGGED_IN)
		{
			$time = time();
			$arrColumns[] = "($t.start='' OR $t.start<$time) AND ($t.stop='' OR $t.stop>$time) AND $t.published=1";
		}

		return static::countBy($arrColumns, $intPid, $arrOptions);
	}	


	/**
     * Find published news items by their parent ID
     *
     * @param integer $intId      The news archive ID
     * @param integer $intLimit   An optional limit
     * @param array   $arrOptions An optional options array
     *
     * @return Collection|null A collection of models or null if there are no news
     */
    public static function findPublishedByPid($intId,array $arrOptions=array())
	{
		$time = time();
		$t = static::$strTable;

		$arrColumns = array("$t.pid=? AND ($t.start='' OR $t.start<$time) AND ($t.stop='' OR $t.stop>$time) AND $t.published=1");

		if (!isset($arrOptions['order']))
		{
			$arrOptions['order'] = "$t.sorting";
		}

		return static::findBy($arrColumns, $intId, $arrOptions);
	}
	
}
