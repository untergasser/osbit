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

// import Joomla modelform library
jimport('joomla.application.component.modeladmin');

/**
 * Section Model
 */
class OSBITModelSection extends JModelAdmin
{
	/**
	 * Method override to check if you can edit an existing record.
	 *
	 * @param	array	$data	An array of input data.
	 * @param	string	$key	The name of the key for the primary key.
	 *
	 * @return	boolean
	 * @since	1.6
	 */
	protected function allowEdit($data = array(), $key = 'ID')
	{
		// Check specific edit permission then general edit permission.
		return JFactory::getUser()->authorise('core.manage', 'com_osbit');
	}
	/**
	 * Returns a reference to the a Table object, always creating it.
	 *
	 * @param	type	The table type to instantiate
	 * @param	string	A prefix for the table class name. Optional.
	 * @param	array	Configuration array for model. Optional.
	 * @return	JTable	A database object
	 * @since	1.6
	 */
	public function getTable($type = 'Section', $prefix = 'OSBITTable', $config = array()) 
	{
		return JTable::getInstance($type, $prefix, $config);
	}
	/**
	 * Method to get the record form.
	 *
	 * @param	array	$data		Data for the form.
	 * @param	boolean	$loadData	True if the form is to load its own data (default case), false if not.
	 * @return	mixed	A JForm object on success, false on failure
	 * @since	1.6
	 */
	public function getForm($data = array(), $loadData = true) 
	{
		// Get the form.
		$form = $this->loadForm('com_osbit.section', 'section', array('control' => 'jform', 'load_data' => $loadData));
		if (empty($form)) 
		{
			return false;
		}
		return $form;
	}
	/**
	 * Method to get the script that have to be included on the form
	 *
	 * @return string	Script files
	 */
	public function getScript() 
	{
		return 'administrator/components/com_osbit/models/forms/section.js';
	}
	/**
	 * Method to get the data that should be injected in the form.
	 *
	 * @return	mixed	The data for the form.
	 * @since	1.6
	 */
	protected function loadFormData() 
	{
		// Check the session for previously entered form data.
		$data = JFactory::getApplication()->getUserState('com_osbit.edit.section.data', array());
		if (empty($data)) 
		{
			$data = $this->getItem();
		}
		return $data;
	}
	
	public function delete(&$pks)
	{
		if(!parent::delete($pks))
			return false;
		// Delete the course section and the registrations
		foreach ($pks as $i => $pk)
		{
			/*
			 * SQL query:
			 *
			 * SELECT ID
			 * FROM #__osbitcoursesection
			 * WHERE sectionID = $pk
			 */
				
			$db = JFactory::getDBO();
			$query = $db->getQuery(true);
			$query->select('ID');
			$query->from('#__osbitcoursesection');
			$query->where("sectionID = '$pk'");
			$db->setQuery((string)$query);
			$request = $db->loadObjectList();;
			if(!$request)
				return true;
			
			/*
			 * SQL query:
			 *
			 * DELETE FROM #__osbitcoursesection
			 * WHERE courseID = $pk
			 */
				
			$db = JFactory::getDBO();
			$query = $db->getQuery(true);
			$query->delete('#__osbitcoursesection');
			$query->where("sectionID = '$pk'");
			$db->setQuery((string)$query);
			$db->query();
			
			foreach ($request as $key => $section)
			{
				/*
				 * SQL query:
				 *
				 * DELETE FROM #__osbitregistrations
				 * WHERE courseID = $section->ID
				 */
					
				$db = JFactory::getDBO();
				$query = $db->getQuery(true);
				$query->delete('#__osbitregistrations');
				$query->where("courseID = '" . $section->ID . "'");
				$db->setQuery((string)$query);
				$db->query();
			}
		}
	}
}