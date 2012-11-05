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

// import Joomla view library
jimport('joomla.application.component.view');
 
/**
 * Main View
 */
class OSBITViewMain extends JView
{
	/**
	* Main view display method
	* @return void
	*/
	function display($tpl = null) 
	{
		// Get data from the model
		$courses = $this->get('CourseOverview');
		 
		// Check for errors.
		if (count($errors = $this->get('Errors'))) 
		{
			JError::raiseError(500, implode('<br />', $errors));
			return false;
		}
		// Assign data to the view
		$this->courses = $courses;
		
		$document = JFactory::getDocument();
		$document->addStyleSheet(JURI::root() . 'media/com_osbit/css/com_osbit.css');
		
		// Set the toolbar
        $this->addToolBar();
		
		// Display the template
		parent::display($tpl);
	}
	
	
	/**
	* Setting the toolbar
	*/
	protected function addToolBar() 
	{
// 		$canDo = OSBITHelper::getActions();
		JToolBarHelper::title(JText::_('COM_OSBIT_MAIN'), 'osbit');
// 		if ($canDo->get('core.admin'))
// 		{
			JToolBarHelper::divider();
			JToolBarHelper::preferences('com_osbit');
// 		}
	}
}