<?php/** * @version		1.0.0 * @package		Joomla * @subpackage	OSBIT * @copyright	(C) 2010 - 2012 Mathias Gebhardt * @license		GNU General Public License version 2 or later; see LICENSE.txt */// No direct accessdefined( '_JEXEC' ) or die( 'Restricted access' );jimport('joomla.application.component.controller');/** * LectureManage Component Controller * * @package    Joomla * @subpackage OSBIT */class OSBITController extends JControllerLegacy{	/**	 * Method to display the view	 *	 * @access    public	 */	function display($cachable = false, $urlparams = false)	{		$this->getVars();		// interceptors to support legacy urls		switch ($this->getTask())		{			//index.php?option=com_lecuremanager&task=registration			case 'next':				if($this->vars['pos'] != 0 && $this->vars['pos'] != 1)					break;				$this->vars['pos']++;				break;			case 'prev':				if($this->vars['pos'] != 2)					break;				$this->vars['pos']--;				break;			case 'submit':				if($this->vars['pos'] == 2)					$this->vars['pos'] = 3;				break;			case 'reset':				$this->vars['pos'] = 0;				break;		}		if ($this->vars['pos'] < 0 || $this->vars['pos'] > 3)			$this->vars['pos'] = 0;		if($this->vars['pos'] != 0)		JRequest::checkToken() or die( 'Invalid Token' );		// set default view if not set		JRequest::setVar('view', JRequest::getCmd('view', 'Registration'));		JRequest::setVar('pos', $this->vars['pos']);		// call parent behavior		parent::display($cachable, $urlparams);				}	protected $vars;		protected function getVars()	{		if(!isset($this->vars))		{			$this->vars['pos'] = JRequest::getInt('pos');			if($this->vars['pos'] < 0 || $this->vars['pos'] > 3)				$this->vars['pos'] = 0;		}		return $this->vars;	}}