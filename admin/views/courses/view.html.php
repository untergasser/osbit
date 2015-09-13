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
 * Courses View
 */
class OSBITViewCourses extends JViewLegacy
{
	/**
	 * Courses view display method
	 * @return void
	 */
	function display($tpl = null) 
	{
		// Get data from the model
		$items = $this->get('Items');
		$pagination = $this->get('Pagination');
//		$sections = $this->get('CourseSections');
		 
		// Check for errors.
		if (count($errors = $this->get('Errors'))) 
		{
			JError::raiseError(500, implode('<br />', $errors));
			return false;
		}
		// Assign data to the view
		$this->items = $items;
		$this->pagination = $pagination;
//		$this->sections = $sections;
		
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
		JToolBarHelper::title(JText::_('COM_OSBIT_COURSES'), 'courses');
		JToolBarHelper::addNew('course.add');
		JToolBarHelper::editList('course.edit');
		JToolBarHelper::deleteList('', 'courses.delete');
	}
}