<?php

/*
 * This file is part of Contao Marquee Bundle.
 *
 * (c) Hamid Peywasti 2015-2024 <hamid@respinar.com>
 *
 * @license LGPL-3.0-or-later
 */


/**
 * Namespace
 */
namespace Respinar\Marquee\Frontend\Element;

use Contao\BackendTemplate;
use Contao\ContentElement;
use Respinar\Marquee\Model\MarqueeModel;
use Respinar\Marquee\Model\MarqueeTextModel;


/**
 * Class ModuleMarquee
 *
 * @copyright  respinar 2015-2017
 * @author     Hamid Abbaszadeh info@respinar.com
 * @package    Devtools
 */
class ContentMarquee extends ContentElement
{

	/**
	 * Template
	 * @var string
	 */
	protected $strTemplate = 'ce_marquee';
    
    
    /**
	 * Display a wildcard in the back end
	 * @return string
	 */
	public function generate()
	{
		if (TL_MODE == 'BE')
		{
			$objTemplate = new BackendTemplate('be_wildcard');

			$objTemplate->wildcard = '### ' . utf8_strtoupper($GLOBALS['TL_LANG']['FMD']['marquee'][0]) . ' ###';

			$objMarquee = MarqueeModel::findBy('id',$this->marquee);

			$objTemplate->title = $this->headline;
			$objTemplate->id = $this->marquee;
			$objTemplate->link = $objMarquee->title;
			$objTemplate->href = 'contao/main.php?do=marquee&amp;table=tl_marquee_text&amp;id=' . $this->marquee;

			return $objTemplate->parse();
		}

		// No marquee available
		if (empty($this->marquee))
		{
			return '';
		}
        
       $GLOBALS['TL_JAVASCRIPT'][] = 'system/modules/marquee/assets/jquery.marquee.js|static';
       $GLOBALS['TL_CSS'][]        = 'system/modules/marquee/assets/marquee.min.css|static';

		return parent::generate();
	}


	/**
	 * Generate the module
	 */
	protected function compile()
	{
        
        $this->Template->duration         = $this->marquee_duration;
//        $this->Template->durationIsSpeed  = $this->marquee_duration_is_speed ? "true" : "false";
        $this->Template->durationIsSpeed  = true;

        $this->Template->gap              = $this->marquee_gap;
        $this->Template->delayBeforeStart = $this->marquee_delayBeforeStart;        
        $this->Template->direction        = $this->marquee_direction;
        $this->Template->duplicate        = $this->marquee_duplicate;        
        $this->Template->pauseOnHover     = $this->marquee_pauseOnHover ? "true" : "false";
        $this->Template->duplicated       = $this->marquee_duplicated ? "true" : "false";
        
        $this->Template->empty = $GLOBALS['TL_LANG']['MSC']['emptyMarquee'];
        
       
        $optns=array();
        if(isset($this->numberOfItem) && $this->numberOfItem>0)
            $optns["limit"]=$this->numberOfItem;
        
        $objMarqueeTexts = MarqueeTextModel::findPublishedByPid($this->marquee,$optns);
        
        $arrMarqueeTexts = array();

        // No items found
		if ($objMarqueeTexts !== null && !empty($objMarqueeTexts))
		{
			while ($objMarqueeTexts->next())
            {
                $tmpArr= array();
                if(isset($objMarqueeTexts->text) && !empty($objMarqueeTexts->text)) {
                    $tmpArr ['text']   = $objMarqueeTexts->text;
                    $tmpArr ['url']    = $objMarqueeTexts->url;
                    $tmpArr ['target'] = $objMarqueeTexts->target;
                    $arrMarqueeTexts[] = $tmpArr;
                }
            }
            $this->Template->texts = $arrMarqueeTexts;
        }
	}
}
