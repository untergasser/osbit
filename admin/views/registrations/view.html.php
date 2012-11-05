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
 * Registrations View
 */
class OSBITViewRegistrations extends JView
{
	/**
	 * OSBIT view display method
	 * @return void
	 */
	function display($tpl = null) 
	{
		// Get data from the model
		$items 		= $this->get('Items');
		$pagination = $this->get('Pagination');
		$state		= $this->get('State');
		 
		// Check for errors.
		if (count($errors = $this->get('Errors'))) 
		{
			JError::raiseError(500, implode('<br />', $errors));
			return false;
		}
		// Assign data to the view
		$this->items		= $items;
		$this->pagination	= $pagination;
		$this->state		= $state;
		$this->listOrder	= $this->state->get('list.ordering');
		$this->listDirn		= $this->state->get('list.direction');
		
		// Set the toolbar
        $this->addToolBar();
		
        require_once JPATH_COMPONENT .'/models/fields/schoolclient.php';
        require_once JPATH_COMPONENT .'/models/fields/classclient.php';
        require_once JPATH_COMPONENT .'/models/fields/courseclient.php';
        require_once JPATH_COMPONENT .'/models/fields/sectionclient.php';
        
		// Display the template
		parent::display($tpl);
	}
	
	/**
	* Setting the toolbar
	*/
	protected function addToolBar() 
	{
		JToolBarHelper::title(JText::_('COM_OSBIT_REGISTRATIONS'), 'registrations');
//		JToolBarHelper::addNewX('registration.add');
//		JToolBarHelper::editListX('registration.edit');
		JToolBarHelper::deleteListX('', 'registrations.delete');
		
		JToolBarHelper::divider();
		//$bar->appendButton('Popup', 'export', 'JTOOLBAR_EXPORT', 'index.php?option=com_osbit&task=registrations.display&format=csv', 600, 300);
		//JToolBarHelper::custom('registrations.display&format=csv','export','','JTOOLBAR_EXPORT',false);
		JToolBarHelper::custom('registrations.export','export','','JTOOLBAR_EXPORT',false);
	}
}