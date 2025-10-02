<?php

/*
 * This file is part of Contao Marquee Bundle.
 *
 * (c) Hamid Peywasti 2015-2024 <hamid@respinar.com>
 *
 * @license LGPL-3.0-or-later
 */

use Contao\Backend;
use Contao\CoreBundle\Security\ContaoCorePermissions;
use Contao\DC_Table;
use Contao\Image;
use Contao\StringUtil;
use Contao\System;

/**
 * Table tl_marquee_text
 */
$GLOBALS['TL_DCA']['tl_marquee_text'] = array
(

	// Config
	'config' => array
	(
		'dataContainer'               => DC_Table::class,
        'ptable'                      => 'tl_marquee',
		'enableVersioning'            => true,
		'sql' => array
		(
			'keys' => array
			(
				'id'  => 'primary',
                'pid' => 'index',
			)
		)
	),

	// List
	'list' => array
	(
		'sorting' => array
		(
			'mode'                    => 4,
            'filter'                  => true,
            'fields'                  => array('sorting'),
            'panelLayout'             => 'filter;search,limit',
            'headerFields'            => array('title', 'tstamp'),
		),
		'label' => array
		(
			'fields'                  => array('text'),
			'format'                  => '%s'
		),
		'global_operations' => array
		(
			'all' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['MSC']['all'],
				'href'                => 'act=select',
				'class'               => 'header_edit_all',
				'attributes'          => 'onclick="Backend.getScrollOffset();" accesskey="e"'
			)
		),
		'operations' => array
		(
			'edit' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_marquee_text']['edit'],
				'href'                => 'act=edit',
				'icon'                => 'edit.svg'
			),
			'copy' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_marquee_text']['copy'],
				'href'                => 'act=copy',
				'icon'                => 'copy.svg'
			),
			'delete' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_marquee_text']['delete'],
				'href'                => 'act=delete',
				'icon'                => 'delete.svg',
				'attributes'          => 'onclick="if(!confirm(\'' . $GLOBALS['TL_LANG']['MSC']['deleteConfirm'] . '\'))return false;Backend.getScrollOffset()"'
			),
            'toggle' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_marquee_text']['toggle'],
				'href'                => 'act=toggle&amp;field=published',
				'icon'                => 'visible.svg',
				'button_callback'     => array('tl_marquee_text', 'toggleIcon')
			),
			'show' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_marquee_text']['show'],
				'href'                => 'act=show',
				'icon'                => 'show.gif'
			)
		)
	),

	// Select
	'select' => array
	(
		'buttons_callback' => array()
	),

	// Edit
	'edit' => array
	(
		'buttons_callback' => array()
	),

	// Palettes
	'palettes' => array
	(
		'__selector__'                => array('published'),
		'default'                     => '{title_legend},text;{link_legend:hide},url,target;{publish_legend},published'
	),

	// Subpalettes
	'subpalettes' => array
	(
		'published'                   => 'start,stop'
	),

	// Fields
	'fields' => array
	(
		'id' => array
		(
			'sql'                     => "int(10) unsigned NOT NULL auto_increment"
		),
        'pid' => array
		(
			'sql'                     => "int(10) unsigned NOT NULL default '0'"
		),
		'sorting' => array
		(
			'sql'                     => "int(10) unsigned NOT NULL default '0'"
		),
		'tstamp' => array
		(
			'sql'                     => "int(10) unsigned NOT NULL default '0'"
		),
		'text' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_marquee_text']['text'],
			'exclude'                 => true,
			'inputType'               => 'textarea',
			'eval'                    => array('mandatory'=>true, 'tl_class'=>'long'),
			'sql'                     => "varchar(255) NOT NULL default ''"
		),
        'url' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['MSC']['url'],
            'exclude'                 => true,
            'search'                  => true,
            'inputType'               => 'text',
            'eval'                    => array('rgxp'=>'url', 'decodeEntities'=>true, 'tl_class'=>'w50', 'dcaPicker' => true),
            'sql'                     => "varchar(255) NOT NULL default ''"
        ),
        'target' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['MSC']['target'],
            'exclude'                 => true,
            'inputType'               => 'checkbox',
            'eval'                    => array('tl_class'=>'w50 m12'),
            'sql'                     => "char(1) NOT NULL default ''"
        ),
        'published' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_marquee_text']['published'],
            'exclude'                 => true,
            'filter'                  => true,
			'toggle'				  => true,
            'inputType'               => 'checkbox',
            'eval'                    => array('doNotCopy'=>true, 'submitOnChange'=>true),
            'sql'                     => "char(1) NOT NULL default ''"
        ),
        'start' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_marquee_text']['start'],
            'exclude'                 => true,
            'inputType'               => 'text',
            'eval'                    => array('rgxp'=>'datim', 'datepicker'=>true, 'tl_class'=>'w50 wizard'),
            'sql'                     => "varchar(10) NOT NULL default ''"
        ),
        'stop' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_marquee_text']['stop'],
            'exclude'                 => true,
            'inputType'               => 'text',
            'eval'                    => array('rgxp'=>'datim', 'datepicker'=>true, 'tl_class'=>'w50 wizard'),
            'sql'                     => "varchar(10) NOT NULL default ''"
        )
	)
);


/**
 * Provide miscellaneous methods that are used by the data configuration array.
 */
class tl_marquee_text extends Backend
{
    /**
	 * Return the "toggle visibility" button
	 *
	 * @param array  $row
	 * @param string $href
	 * @param string $label
	 * @param string $title
	 * @param string $icon
	 * @param string $attributes
	 *
	 * @return string
	 */
	public function toggleIcon($row, $href, $label, $title, $icon, $attributes)
	{
		$security = System::getContainer()->get('security.helper');

		if (!$security->isGranted(ContaoCorePermissions::USER_CAN_EDIT_FIELD_OF_TABLE, 'tl_marquee_text::published'))
		{
			return '';
		}

		$href .= '&amp;id=' . $row['id'];

		if (!$row['published'])
		{
			$icon = 'invisible.svg';
		}

		return '<a href="' . $this->addToUrl($href) . '" title="' . StringUtil::specialchars($title) . '" onclick="Backend.getScrollOffset();return AjaxRequest.toggleField(this,true)">' . Image::getHtml($icon, $label, 'data-icon="' . Image::getPath('visible.svg') . '" data-icon-disabled="' . Image::getPath('invisible.svg') . '" data-state="' . ($row['published'] ? 1 : 0) . '"') . '</a> ';
	}
}
