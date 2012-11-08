<?php
/**
 * @version		1.0.0
 * @package		Joomla
 * @subpackage	OSBIT
 * @copyright	(C) 2010 - 2012 Mathias Gebhardt
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */


// No direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

jimport('joomla.application.component.controller');
 
/**
 * OSBIT Component Controller
 *
 * @package    Joomla
 * @subpackage OSBIT
 */
class OSBITController extends JController
{
    /**
     * Method to display the view
     *
     * @access    public
     */
    public function display($cachable = false, $urlparams = false)
	{
		// set default view if not set
		JRequest::setVar('view', ($view = JRequest::getCmd('view', 'Main')));
		 
		// call parent behavior
		parent::display($cachable, $urlparams);
		
		// Set the submenu
		if ($view != 'Main')
			OSBITHelper::addSubmenu($view);
	}
}