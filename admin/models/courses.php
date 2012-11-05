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
// import the Joomla modellist library
jimport('joomla.application.component.modellist');
/**
 * Course list Model
 */
class OSBITModelCourses extends JModelList
{
	/**
	 * Method to build an SQL query to load the list data.
	 *
	 * @return      string  An SQL query
	 */
	protected function getListQuery()
	{
       	/*
		 * SQL query:
		 *
		 * SELECT `ID`, `name`, `description`, `lector1`, `lector1Firm`, `lector2`, `lector2Firm`, `maxRegistrations`
		 * FORM #__osbitcourses
		 */
		$db = JFactory::getDBO();
		$query = $db->getQuery(true);
		$query->select('`ID`, `name`, `description`, `lector1`, `lector1Firm`, `lector2`, `lector2Firm`, `maxRegistrations`');
		$query->from('#__osbitcourses');
		return $query;

		/*
		 * SQL query:
		 *
		 * SELECT c.ID, s.sectionID, name , description , lector1 , lector1Firm , lector2 , lector2Firm , count( * ) AS registrations, maxRegistrations
		 * FROM #__osbitcourses AS c
		 * LEFT JOIN #__osbitcoursesection AS s ON c.ID = s.courseID
		 * INNER JOIN #__osbitregistrations AS r ON s.ID = r.courseID
		 * CLASS BY c.ID, s.sectionID
		 */
// Not used jet. Maybe later.		
//		$db = JFactory::getDBO();
//		$query = $db->getQuery(true);
//		$query->select('c.ID, s.sectionID, name , description , lector1 , lector1Firm , lector2 , lector2Firm , count( * ) AS registrations, maxRegistrations');
//		$query->from('#__osbitcourses AS c');
//		$query->leftJoin('#__osbitcoursesection AS s ON c.ID = s.courseID');
//		$query->innerJoin('#__osbitregistrations AS r ON s.ID = r.courseID');
//		$query->group('c.ID, s.sectionID');
//		return $query;
	}
	
//	protected $sections;
	
	/**
	 * Gets a list of all available course sections
	 * 
	 * @return	mixed	The course sections
	 */
// Not used jet. Maybe later.	
//	public function getCourseSections()
//	{
//		if(!isset($this->sections))
//		{
//			/*
//			 * SQL query:
//			 *
//			 * SELECT ID, begin, end
//			 * FROM #__osbitcoursesections
//			 * ORDER BY begin
//			 */
//				
//			$db = JFactory::getDBO();
//			$query = $db->getQuery(true);
//			$query->select('ID, begin, end');
//			$query->from('#__osbitcoursesections');
//			$query->order('begin');
//			$db->setQuery((string)$query);
//			$this->sections = $db->loadObjectList();
//		}
//		return $this->sections;
//	}
	
	/**
	 * Method to get an array of data items.
	 *
	 * @return	mixed	An array of data items on success, false on failure.
	 * @since	1.6
	 */
// Not used jet. Maybe later.
//	public function getItems()
//	{
//		if(!($items = parent::getItems()))
//			return false;
//			
//		$res = array();
//		foreach ($items as $item)
//		{
//			if(!isset($res[$item->ID]))
//			{
//				$res[$item->ID] = $item;
//				unset($res[$item->ID]->sectionID);
//				$res[$item->ID]->registrations = array();
//			}
//			$res[$item->ID]->registrations[$item->sectionID] = $item->registrations;
//		}
//		return $res;
//	}
}