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
 * Sections View
 */
class OSBITViewSections extends JViewLegacy
{
	/**
	 * Sections view display method
	 * @return void
	 */
	function display($tpl = null) 
	{
		// Get data from the model
		$items = $this->get('Items');
		$pagination = $this->get('Pagination');
		 
		// Check for errors.
		if (count($errors = $this->get('Errors'))) 
		{
			JError::raiseError(500, implode('<br />', $errors));
			return false;
		}
		// Assign data to the view
		$this->items = $items;
		$this->pagination = $pagination;
		
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
		JToolBarHelper::title(JText::_('COM_OSBIT_SECTIONS'), 'sections');
		JToolBarHelper::addNewX('section.add');
		JToolBarHelper::editListX('section.edit');
		JToolBarHelper::deleteListX('', 'sections.delete');
	}
}