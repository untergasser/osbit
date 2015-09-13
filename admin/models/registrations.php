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
 * Registration list Model
 */
class OSBITModelRegistrations extends JModelList
{
	/**
	 * Constructor.
	 *
	 * @param	array	An optional associative array of configuration settings.
	 * @see		JController
	 * @since	1.6
	 */
	public function __construct($config = array())
	{
		if (empty($config['filter_fields'])) {
			$config['filter_fields'] = array(
				'ID', 'r.ID',
				'u.ID', 'userID',
				'c.ID', 'courseID',
				's.ID',	'sectionID',
				'firstName',
				'lastName',
				'school',
				'`class`',
				'name',
				'begin',
				'end',
			);
		}

		parent::__construct($config);
	}
	
	/**
	 * Method to auto-populate the model state.
	 *
	 * Note. Calling getState in this method will result in recursion.
	 *
	 * @return	void
	 * @since	1.6
	 */
	protected function populateState($ordering = null, $direction = null)
	{
		// Initialise variables.
		$app = JFactory::getApplication();

		$search = $this->getUserStateFromRequest($this->context.'.filter.search', 'filter_search');
		$this->setState('filter.search', $search);

		$school = $this->getUserStateFromRequest($this->context.'.filter.school', 'filter_school', '');
		$this->setState('filter.school', $school);

		$class = $this->getUserStateFromRequest($this->context.'.filter.class', 'filter_class', '');
		$this->setState('filter.class', $class);

		$courseID = $this->getUserStateFromRequest($this->context.'.filter.courseID', 'filter_courseID', 0, 'int');
		$this->setState('filter.courseID', $courseID);

		$sectionID = $this->getUserStateFromRequest($this->context.'.filter.sectionID', 'filter_sectionID', 0, 'int');
		$this->setState('filter.sectionID', $sectionID);

		// List state information.
		parent::populateState('lastName', 'asc');
	}


	/**
	 * Method to get a store id based on model configuration state.
	 *
	 * This is necessary because the model is used by the component and
	 * different modules that might need different sets of data or different
	 * ordering requirements.
	 *
	 * @param	string		$id	A prefix for the store id.
	 *
	 * @return	string		A store id.
	 * @since	1.6
	 */
	protected function getStoreId($id = '')
	{
		// Compile the store id.
		$id	.= ':'.$this->getState('filter.search');
		$id	.= ':'.$this->getState('filter.school');
		$id	.= ':'.$this->getState('filter.class');
		$id	.= ':'.$this->getState('filter.courseID');
		$id	.= ':'.$this->getState('filter.sectionID');

		return parent::getStoreId($id);
	}
	
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
		 * SELECT r.ID AS ID , u.ID as userID , c.ID AS courseID , s.ID AS sectionID , `firstName` , `lastName` , `school` , `class` , `name`, `begin` , `end`
		 * FROM #__osbitusers AS u
		 * INNER JOIN #__osbitregistrations AS r ON u.ID = r.userID
		 * INNER JOIN #__osbitcoursesection AS cs ON r.courseID = cs.ID
		 * INNER JOIN #__osbitcoursesections AS s ON cs.sectionID = s.ID
		 * INNER JOIN #__osbitcourses AS c ON cs.courseID = c.ID
		 */
		$db = JFactory::getDBO();
		$query = $db->getQuery(true);
		$query->select(
			$this->getState(
				'list.select',
				'r.ID AS ID , u.ID as userID , c.ID AS courseID , ' .
				's.ID AS sectionID , `firstName` , `lastName` , ' .
				'`school` , `class` , `name`, `begin` , `end`'));
		$query->from('#__osbitusers AS u');
		$query->innerJoin('#__osbitregistrations AS r ON u.ID = r.userID');
		$query->innerJoin('#__osbitcoursesection AS cs ON r.courseID = cs.ID');
		$query->innerJoin('#__osbitcoursesections AS s ON cs.sectionID = s.ID');
		$query->innerJoin('#__osbitcourses AS c ON cs.courseID = c.ID');
		
	
		// Filter by search in title
		$search = $this->getState('filter.search');
		if (!empty($search)) {
			if (stripos($search, 'id:') === 0) {
				$query->where('r.ID = '.(int) substr($search, 3));
			} else {
				$search = $db->Quote('%'.$db->escape($search, true).'%');
				$query->where('(firstName LIKE '.$search.' OR lastName LIKE '.$search.')');
			}
		}
		
		// Filter by school
		if($school = $this->getState('filter.school'))
			$query->where('school = ' . $db->quote($school));

		// Filter by class
		if($class = $this->getState('filter.class'))
			$query->where('`class` = ' . $db->quote($class));

		// Filter by course.
		$courseID = $this->getState('filter.courseID');
		if (is_numeric($courseID) && $courseID > 0) {
			$query->where('c.ID = '.(int) $courseID);
		}
		
		// Filter by section.
		$sectionID = $this->getState('filter.sectionID');
		if (is_numeric($sectionID) && $sectionID > 0) {
			$query->where('s.ID = '.(int) $sectionID);
		}
		
		// Add the list ordering clause.
		$orderCol	= $this->state->get('list.ordering');
		$orderDirn	= $this->state->get('list.direction');
		if($orderCol == 'lastName' || $orderCol == 'firstName')
			$orderCol = 'lastName ' . $orderDirn . ', firstName';
		$query->order($db->escape($orderCol.' '.$orderDirn));
		
		return $query;
	}
	
	
/*
	public function export()
	{
		$app = JFactory::getApplication();
		
		$db = JFactory::getDBO();
		$query = $db->getQuery(true);
		$query->select('cs.`ID` AS ID, cs.`room` , s.`begin` , s.`end` , c.`name` , c.`maxRegistrations`');
		$query->from('#__osbitcoursesection AS cs');
		$query->innerJoin('#__osbitcourses AS c ON cs.courseID = c.ID');
		$query->innerJoin('#__osbitcoursesections AS s ON cs.sectionID = s.ID');
		$query->order('3, 4');
		$db->setQuery((string)$query);
		$courses = $db->loadObjectList();
		
		$dump;
		
		foreach ($courses as $course)
		{
			$query = $db->getQuery(true);
			$query->select('u.`lastName`, u.`firstName`, u.`school`, u.`class`');
			$query->from('#__osbitregistrations AS r');
			$query->innerJoin('#__osbitusers AS u on r.userID = u.ID');
			$query->where('r.courseID = ' . $course->ID);
			$query->order('1, 2');
			$db->setQuery($query);
			$course->users = $db->loadObjectList();
			
			$dump .= implode(';', array($course->name, $course->begin, $course->end, $course->room, count($course->users), $course->maxRegistrations)) . '\r\n';
			$dump .= 'Nachname; Vorname; Schule; Klasse\r\n';
			foreach ($course->users as $user)
			{
				$dump .= implode(';', array($user->lastName, $user->firstName, $user->school, $user->class)) . '\r\n';
			}
			$dump .= '\r\n';
			
		}
		
		echo $dump;
		die();
		return false;
	}
*/	
	/**
	 * Get file name
	 */
	public function getBaseName()
	{
		/*
		if (!isset($this->basename))
		{
	
			$app		= JFactory::getApplication();
			$basename	= $this->getState('basename');
			$basename	= str_replace('__SITE__', $app->getCfg('sitename'), $basename);
			$categoryId	= $this->getState('filter.category_id');
	
			if (is_numeric($categoryId)) {
				if ($categoryId > 0) {
					$basename = str_replace('__CATID__', $categoryId, $basename);
				} else {
					$basename = str_replace('__CATID__', '', $basename);
				}
				$categoryName = $this->getCategoryName();
				$basename = str_replace('__CATNAME__', $categoryName, $basename);
			} else {
				$basename = str_replace('__CATID__', '', $basename);
				$basename = str_replace('__CATNAME__', '', $basename);
			}
	
			$clientId = $this->getState('filter.client_id');
			if (is_numeric($clientId)) {
	
				if ($clientId > 0) {
					$basename = str_replace('__CLIENTID__', $clientId, $basename);
				} else {
					$basename = str_replace('__CLIENTID__', '', $basename);
				}
				$clientName = $this->getClientName();
				$basename = str_replace('__CLIENTNAME__', $clientName, $basename);
	
			} else {
	
				$basename = str_replace('__CLIENTID__', '', $basename);
				$basename = str_replace('__CLIENTNAME__', '', $basename);
			}
	
			$type = $this->getState('filter.type');
			if ($type > 0) {
	
				$basename = str_replace('__TYPE__', $type, $basename);
				$typeName = JText::_('COM_BANNERS_TYPE'.$type);
				$basename = str_replace('__TYPENAME__', $typeName, $basename);
			} else {
				$basename = str_replace('__TYPE__', '', $basename);
				$basename = str_replace('__TYPENAME__', '', $basename);
			}
	
			$begin = $this->getState('filter.begin');
			if (!empty($begin)) {
				$basename = str_replace('__BEGIN__', $begin, $basename);
			} else {
				$basename = str_replace('__BEGIN__', '', $basename);
			}
	
			$end = $this->getState('filter.end');
			if (!empty($end)) {
				$basename = str_replace('__END__', $end, $basename);
			} else {
				$basename = str_replace('__END__', '', $basename);
			}
	
			$this->basename = $basename;
		}
		*/
		//return $this->basename;
		return 'OSBIT_' . JFactory::getDate();
	}
	
	/**
	 * Get the file type.
	 */
	public function getFileType()
	{
		//return $this->getState('compressed') ? 'zip' : 'csv';
		return 'csv';
	}
	
	/**
	 * Get the mime type.
	 */
	public function getMimeType()
	{
		//return $this->getState('compressed') ? 'application/zip' : 'text/csv';
		return 'text/csv';
	}
	
	/**
	 * Get the content
	 */
	public function getContent()
	{
		/*
		if (false)
		{
	
			$this->content = '';
			$this->content.=
			'"'.str_replace('"', '""', JText::_('COM_BANNERS_HEADING_NAME')).'","'.
			str_replace('"', '""', JText::_('COM_BANNERS_HEADING_CLIENT')).'","'.
			str_replace('"', '""', JText::_('JCATEGORY')).'","'.
			str_replace('"', '""', JText::_('COM_BANNERS_HEADING_TYPE')).'","'.
			str_replace('"', '""', JText::_('COM_BANNERS_HEADING_COUNT')).'","'.
			str_replace('"', '""', JText::_('JDATE')).'"'."\n";
	
			foreach($this->getItems() as $item) {
	
				$this->content.=
				'"'.str_replace('"', '""', $item->name).'","'.
				str_replace('"', '""', $item->client_name).'","'.
				str_replace('"', '""', $item->category_title).'","'.
				str_replace('"', '""', ($item->track_type==1 ? JText::_('COM_BANNERS_IMPRESSION'): JText::_('COM_BANNERS_CLICK'))).'","'.
				str_replace('"', '""', $item->count).'","'.
				str_replace('"', '""', $item->track_date).'"'."\n";
			}
	
			if ($this->getState('compressed')) {
				$app = JFactory::getApplication('administrator');
	
				$files = array();
				$files['track']=array();
				$files['track']['name'] = $this->getBasename() . '.csv';
				$files['track']['data'] = $this->content;
				$files['track']['time'] = time();
				$ziproot = $app->getCfg('tmp_path').'/' . uniqid('banners_tracks_') . '.zip';
	
				// run the packager
				jimport('joomla.filesystem.folder');
				jimport('joomla.filesystem.file');
				jimport('joomla.filesystem.archive');
				$delete = JFolder::files($app->getCfg('tmp_path').'/', uniqid('banners_tracks_'), false, true);
	
				if (!empty($delete)) {
					if (!JFile::delete($delete)) {
						// JFile::delete throws an error
						$this->setError(JText::_('COM_BANNERS_ERR_ZIP_DELETE_FAILURE'));
						return false;
					}
				}
	
				if (!$packager = JArchive::getAdapter('zip')) {
					$this->setError(JText::_('COM_BANNERS_ERR_ZIP_ADAPTER_FAILURE'));
					return false;
				} elseif (!$packager->create($ziproot, $files)) {
					$this->setError(JText::_('COM_BANNERS_ERR_ZIP_CREATE_FAILURE'));
					return false;
				}
	
				$this->content = file_get_contents($ziproot);
			}
		}
		*/
		//return $this->content;
		
		$app = JFactory::getApplication();
		
		$db = JFactory::getDBO();
		$query = $db->getQuery(true);
		$query->select('cs.`ID` AS ID, cs.`room` , s.`begin` , s.`end` , c.`name` , c.`maxRegistrations`');
		$query->from('#__osbitcoursesection AS cs');
		$query->innerJoin('#__osbitcourses AS c ON cs.courseID = c.ID');
		$query->innerJoin('#__osbitcoursesections AS s ON cs.sectionID = s.ID');
		$query->order('3, 4');
		$db->setQuery((string)$query);
		$courses = $db->loadObjectList();
		
		$dump;
		
		foreach ($courses as $course)
		{
			$query = $db->getQuery(true);
			$query->select('u.`lastName`, u.`firstName`, u.`school`, u.`class`');
			$query->from('#__osbitregistrations AS r');
			$query->innerJoin('#__osbitusers AS u on r.userID = u.ID');
			$query->where('r.courseID = ' . $course->ID);
			$query->order('1, 2');
			$db->setQuery($query);
			$course->users = $db->loadObjectList();
				
			$dump .= implode(';', array($course->name, $course->begin, $course->end, $course->room, count($course->users), $course->maxRegistrations)) . "\r\n";
			$dump .= "Nachname; Vorname; Schule; Klasse\r\n";
			foreach ($course->users as $user)
			{
				$dump .= implode(';', array($user->lastName, $user->firstName, $user->school, $user->class)) . "\r\n";
			}
			$dump .= "\r\n";
				
		}
		
		return $dump;
	}
}