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

// import Joomla controllerform library
jimport('joomla.application.component.controllerform');

/**
 * Course Controller
 */
class OSBITControllerCourse extends JControllerForm
{
	/**
	 * Overrides parent save method
	 *
	 * @param	string	$key	The name of the primary key of the URL variable.
	 * @param	string	$urlVar	The name of the URL variable if different from the primary key (sometimes required to avoid router collisions).
	 *
	 * @return	Boolean	True if successful, false otherwise.
	 * @since	1.6
	 */
//	public function save($key = null, $urlVar = null)
//	{
//		if(!parent::save($key, $urlVar))
//			return false;
//			
//		// Initialise variables.
//		$app		= JFactory::getApplication();
//		$lang		= JFactory::getLanguage();
//		$model		= $this->getModel();
//		$table		= $model->getTable();
//		$data		= JRequest::getVar('jform', array(), 'post', 'array');
//		$db			= JFactory::getDBO();
//		
//		// Determine the name of the primary key for the data.
//		if (empty($key)) {
//			$key = $table->getKeyName();
//		}
//
//		// The urlVar may be different from the primary key to avoid data collisions.
//		if (empty($urlVar)) {
//			$urlVar = $key;
//		}
//
//		$courseID	= JRequest::getInt($urlVar);
//		
//		// Get the old sections
//		/*
//		 * SQL query:
//		 *
//		 * SELECT sectionID, room
//		 * FROM #__osbitcoursesection
//		 * WHERE courseID = $recordId
//		 * ORDER BY begin
//		 */
//			
//		$query = $db->getQuery(true);
//		$query->select('sectionID');
//		$query->from('#__osbitcoursesection');
//		$query->where("courseID = '$courseID'");
//		$db->setQuery((string)$query);
//		$request = $db->loadObjectList();
//		$oldSections = array();
//		if($request)
//			foreach ($request as $section)
//			{
//				$oldSections[] = $section->sectionID;
//				$rooms[$section->sectionID] = $section->room;
//			}
//		
//		// Add new sections
//		foreach ($data['sections'] as $section)
//		{
//			if(in_array($section, $oldSections))
//			{
//				// Changed the room?
//				if($data['rooms'][$section] == $rooms[$section])
//					continue;
//				
//				// Change room:
//				/*
//				 * SQL query:
//				 *
//				 * UPDATE #__osbitcoursesection
//				 * SET room = '$data['rooms'][$section]'
//				 */
//					
//				$query = $db->getQuery(true);
//				$query->update('#__osbitcoursesection');
//				$query->set("room = '" . $data['rooms'][$section] . "'");
//				$query->where("courseID = '$courseID'");
//				$query->where("sectionID = '$section'",'AND');
//				$db->setQuery((string)$query);
//				$db->query();	
//				continue;
//			}
//			
//			/*
//			 * SQL query:
//			 *
//			 * INSERT INTO #__osbitcoursesection
//			 * SET courseID = $courseID, sectionID = $section
//			 * WHERE courseID = $courseID AND sectionID = $section
//			 */
//				
//			$query = $db->getQuery(true);
//			$query->insert('#__osbitcoursesection');
//			$query->set("courseID = '$courseID'");
//			$query->set("sectionID = '$section'");
//			$query->set("room = '" . $data['rooms'][$section] . "'");
//			$db->setQuery((string)$query);
//			$db->query();
//		}
//		// Delete old sections
//		foreach ($oldSections as $section)
//		{
//			if(in_array($section, $data['sections']))
//				continue;
//			
//			/*
//			 * SQL query:
//			 *
//			 * SELECT ID
//			 * FROM #__osbitcoursesection
//			 * WHERE courseID = $courseID AND sectionID = $section
//			 */
//				
//			$query = $db->getQuery(true);
//			$query->select('ID');
//			$query->from('#__osbitcoursesection');
//			$query->where("courseID = '$courseID'");
//			$query->where("sectionID = '$section'",'AND');
//			$db->setQuery((string)$query);
//			$ID = $db->loadObject()->ID;
//				
//			/*
//			 * SQL query:
//			 *
//			 * DELETE FORM #__osbitcoursesection
//			 * WHERE ID = '$ID'
//			 */
//				
//			$query = $db->getQuery(true);
//			$query->delete('#__osbitcoursesection');
//			$query->where("ID = '$ID'");
//			$db->setQuery((string)$query);
//			$db->query();
//			
//			/*
//			 * SQL query:
//			 *
//			 * DELETE FORM #__osbitregistrations
//			 * WHERE ID = '$ID'
//			 */
//				
//			$query = $db->getQuery(true);
//			$query->delete('#__osbitregistrations');
//			$query->where("CourseID = '$ID'");
//			$db->setQuery((string)$query);
//			$db->query();
//		}
//		return true;
//	}
}