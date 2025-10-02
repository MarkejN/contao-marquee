<?php

/*
 * This file is part of Contao Marquee Bundle.
 *
 * (c) Hamid Peywasti 2015-2024 <hamid@respinar.com>
 *
 * @license LGPL-3.0-or-later
 */

 /**
 * Back end modules
 */

use Contao\ArrayUtil;

ArrayUtil::arrayInsert($GLOBALS['BE_MOD']['content'], 1, array
(
	'marquee' => array
	(
		'tables' => array('tl_marquee', 'tl_marquee_text'),
	)
));

/**
 * Register models
 */
 $GLOBALS['TL_MODELS']['tl_marquee']      = 'Respinar\Marquee\Model\MarqueeModel';
 $GLOBALS['TL_MODELS']['tl_marquee_text'] = 'Respinar\Marquee\Model\MarqueeTextModel';

/**
 * Front end modules
 */
$GLOBALS['FE_MOD']['miscellaneous']['marquee']   = 'Respinar\Marquee\Frontend\Module\ModuleMarquee';

/**
 * Content elements
 */
$GLOBALS['TL_CTE']['miscellaneous']['marquee']   = 'Respinar\Marquee\Frontend\Element\ContentMarquee';
