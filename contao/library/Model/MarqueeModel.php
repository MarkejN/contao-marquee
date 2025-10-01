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

/**
 * Reads and writes Marquee
 *
 * @package   Models
 * @author    Hamid Abbaszadeh <http://respinar.com>
 * @copyright Hamid Abbaszadeh 2013-2014
 */
class MarqueeModel extends Model
{

	/**
	 * Table name
	 * @var string
	 */
	protected static $strTable = 'tl_marquee';

}
