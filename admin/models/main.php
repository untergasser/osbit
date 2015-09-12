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

// import Joomla modelitem library
jimport('joomla.application.component.model');

/**
 * Main Model
 *
 * @package    Joomla
 * @subpackage OSBIT
 */
class OSBITModelMain extends JModelLegacy
{
	
	public function getCourseOverview()
	{
		/*
		* SQL query:
		*
		* SELECT DISTINCT(sectionID), begin, end
		* FROM #__osbitcoursesections
		* INNER JOIN #__osbitcoursesection ON #__osbitcoursesections.id = #__osbitcoursesection.sectionID
		* ORDER BY begin
		*/
		
		$db = JFactory::getDBO();
		$query = $db->getQuery(true);
		$query->select('DISTINCT(sectionID), begin, end');
		$query->from('#__osbitcoursesections');
		$query->innerJoin('#__osbitcoursesection ON #__osbitcoursesections.id = #__osbitcoursesection.sectionID');
		$query->order('begin');
		$db->setQuery((string)$query);
		$db->query();
		if($db->getNumRows() <= 0)
			return null;
		$request = $db->loadObjectList();
		if($request)
			foreach ($request as $section)
				if(!isset($this->courses[$section->sectionID]))
					$courses[$section->sectionID] = array(
						'begin' => $section->begin,
						'end'	=> $section->end,
						'rows'	=> array(),
		);
		
		/*
		 * SQL query:
		*
		* SELECT #__osbitcoursesection.ID as ID, courseID, sectionID, name, lector1, lector1firm, lector2, lector2firm, maxRegistrations, room
		* FROM #__osbitcourses
		* INNER JOIN #__osbitcoursesection ON #__osbitcourses.id = #__osbitcoursesection.courseID
		* INNER JOIN #__osbitcoursesections ON #__osbitcoursesections.id = #__osbitcoursesection.sectionID
		* ORDER BY room
		*/
		
		$query = $db->getQuery(true);
		$query->select('#__osbitcoursesection.ID as ID, courseID, sectionID, name, lector1, lector1Firm, lector2, lector2Firm, maxRegistrations, room');
		$query->from('#__osbitcourses');
		$query->innerJoin('#__osbitcoursesection ON #__osbitcourses.id = #__osbitcoursesection.courseID');
		$query->innerJoin('#__osbitcoursesections ON #__osbitcoursesections.id = #__osbitcoursesection.sectionID');
		$query->order('room');
		$db->setQuery((string)$query);
		$request = $db->loadObjectList();
		
		if($request)
		{
			foreach ($request as $course)
			{
				$row = count($courses[$course->sectionID]['rows']) - 1;
				if($row < 0)
					$row = 0;
				elseif(count($courses[$course->sectionID]['rows'][$row]) >= 3)
					$row++;
				
				$courses[$course->sectionID]['rows'][$row][$course->ID] = array(
									'ID'				=> $course->ID,
									'courseID'			=> $course->courseID,
									'courseName'		=> $course->name,
									'maxRegistrations'	=> $course->maxRegistrations,
									'registrations'		=> $this->getCountRegistrations($course->ID),
									'room'				=> $course->room,
				);
			}
		}
		return isset($courses) ? $courses : null;
	}
	
	protected $counts;
	
	protected function getCountRegistrations($courseID)
	{
		if(!is_numeric($courseID) || $courseID <= 0)
		die('com_osbit - getCountRegistrations - $courseId may an integer!');
	
		if(!isset($this->counts))
		{
			/*
			 * SQL query:
			*
			* SELECT courseID, count(*) as count
			* FROM #__osbitregistrations
			* GROUP BY courseID
			*/
	
			$db = JFactory::getDBO();
			$query = $db->getQuery(true);
			$query->select('courseID, count(*) as count');
			$query->from('#__osbitregistrations');
			$query->group('courseID');
			$db->setQuery((string)$query);
			$request = $db->loadObjectList();
				
			if($request)
				foreach ($request as $course)
					$this->counts[$course->courseID] = $course->count;
		}
		return isset($this->counts[$courseID]) ? $this->counts[$courseID] : 0;
	}
}