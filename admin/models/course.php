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
 * Course Model
 */
class OSBITModelCourse extends JModelAdmin
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
	public function getTable($type = 'Course', $prefix = 'OSBITTable', $config = array()) 
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
		$form = $this->loadForm('com_osbit.course', 'course', array('control' => 'jform', 'load_data' => $loadData));
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
		return 'administrator/components/com_osbit/models/forms/course.js';
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
		$data = JFactory::getApplication()->getUserState('com_osbit.edit.course.data', array());
		if (empty($data)) 
		{
			$data = $this->getItem();
		}
		return $data;
	}
	
	protected $sections;
	
	/**
	 * Gets a list of all available course sections
	 * 
	 * @return	mixed	The course sections
	 */
	public function getCourseSections()
	{
		if(!isset($this->sections))
		{
			/*
			 * SQL query:
			 *
			 * SELECT ID, begin, end
			 * FROM #__osbitcoursesections
			 * ORDER BY begin
			 */
				
			$db = JFactory::getDBO();
			$query = $db->getQuery(true);
			$query->select('ID, begin, end');
			$query->from('#__osbitcoursesections');
			$query->order('begin');
			$db->setQuery((string)$query);
			$this->sections = $db->loadObjectList();
		}
		return $this->sections;
	}
	/**
	 * Method to get a single record.
	 *
	 * @param	integer	$pk	The id of the primary key.
	 *
	 * @return	mixed	Object on success, false on failure.
	 * @since	1.6
	 */
	public function getItem($pk = null)
	{
		$item = parent::getItem($pk);
		/*
		 * SQL query:
		 *
		 * SELECT sectionID
		 * FROM #__osbitcoursesection
		 * WHERE courseID = $item->ID
		 * ORDER BY begin
		 */
			
		$db = JFactory::getDBO();
		$query = $db->getQuery(true);
		$query->select('sectionID, room');
		$query->from('#__osbitcoursesection');
		$query->where("courseID = '" . $item->ID . "'");
		$db->setQuery((string)$query);
		$request = $db->loadObjectList();
		
		$item->sections = array();
		
		if($request)
		{
			foreach ($request as $section)
			{
				$item->sections[] = $section->sectionID;
				$item->rooms[$section->sectionID] = $section->room;
			}	
		}
		return $item;
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
			 * WHERE courseID = $pk
			 */
				
			$db = JFactory::getDBO();
			$query = $db->getQuery(true);
			$query->select('ID');
			$query->from('#__osbitcoursesection');
			$query->where("courseID = '$pk'");
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
			$query->where("courseID = '$pk'");
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
	
	public function save($data)
	{
		if(!parent::save($data))
			return false;

		$table		= $this->getTable();
		$key		= $table->getKeyName();
		$courseID	= (!empty($data[$key])) ? $data[$key] : (int)$this->getState($this->getName().'.id');
		$db			= JFactory::getDBO();
// TODO - Check array here		
		$data		= JRequest::getVar('jform', array(), 'post', 'array');
		
		// Get the old sections
		/*
		 * SQL query:
		 *
		 * SELECT sectionID, room
		 * FROM #__osbitcoursesection
		 * WHERE courseID = $courseID
		 * ORDER BY begin
		 */
			
		$query = $db->getQuery(true);
		$query->select('sectionID');
		$query->from('#__osbitcoursesection');
		$query->where("courseID = '$courseID'");
		$db->setQuery((string)$query);
		$request = $db->loadObjectList();
		$oldSections = array();
		if($request)
			foreach ($request as $section)
			{
				$oldSections[] = $section->sectionID;
				$rooms[$section->sectionID] = $section->room;
			}
		
		// Add new sections
		foreach ($data['sections'] as $section)
		{
			if(in_array($section, $oldSections))
			{
				// Changed the room?
				if($data['rooms'][$section] == $rooms[$section])
					continue;
				
				// Change room:
				/*
				 * SQL query:
				 *
				 * UPDATE #__osbitcoursesection
				 * SET room = '$data['rooms'][$section]'
				 */
					
				$query = $db->getQuery(true);
				$query->update('#__osbitcoursesection');
				$query->set("room = '" . $data['rooms'][$section] . "'");
				$query->where("courseID = '$courseID'");
				$query->where("sectionID = '$section'",'AND');
				$db->setQuery((string)$query);
				$db->query();	
				continue;
			}
			
			/*
			 * SQL query:
			 *
			 * INSERT INTO #__osbitcoursesection
			 * SET courseID = $courseID, sectionID = $section
			 * WHERE courseID = $courseID AND sectionID = $section
			 */
				
			$query = $db->getQuery(true);
			$query->insert('#__osbitcoursesection');
			$query->set("courseID = '$courseID'");
			$query->set("sectionID = '$section'");
			$query->set("room = '" . $data['rooms'][$section] . "'");
			$db->setQuery((string)$query);
			$db->query();
		}
		// Delete old sections
		foreach ($oldSections as $section)
		{
			if(in_array($section, $data['sections']))
				continue;
			
			/*
			 * SQL query:
			 *
			 * SELECT ID
			 * FROM #__osbitcoursesection
			 * WHERE courseID = $courseID AND sectionID = $section
			 */
				
			$query = $db->getQuery(true);
			$query->select('ID');
			$query->from('#__osbitcoursesection');
			$query->where("courseID = '$courseID'");
			$query->where("sectionID = '$section'",'AND');
			$db->setQuery((string)$query);
			$ID = $db->loadObject()->ID;
				
			/*
			 * SQL query:
			 *
			 * DELETE FORM #__osbitcoursesection
			 * WHERE ID = '$ID'
			 */
				
			$query = $db->getQuery(true);
			$query->delete('#__osbitcoursesection');
			$query->where("ID = '$ID'");
			$db->setQuery((string)$query);
			$db->query();
			
			/*
			 * SQL query:
			 *
			 * DELETE FORM #__osbitregistrations
			 * WHERE ID = '$ID'
			 */
				
			$query = $db->getQuery(true);
			$query->delete('#__osbitregistrations');
			$query->where("CourseID = '$ID'");
			$db->setQuery((string)$query);
			$db->query();
		}
		return true;
	}
}