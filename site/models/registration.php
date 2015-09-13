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
jimport('joomla.application.component.modelitem');
jimport('joomla.mail.helper');
jimport('joomla.mail.mail');

/**
 * OSBIT Model
 *
 * @package    Joomla
 * @subpackage OSBIT
 */
class OSBITModelRegistration extends JModelItem
{

	protected $courseInfos;

	/**
	 * Gets all course informations from the database
	 */
	public function getCourseInfos()
	{
		if (!isset($this->courseInfos))
		{
			/*
			 * SQL query:
			 *
			 * SELECT DISTINCT(name), description
			 * FORM #__lectureManagerCourses
			 * ORDER BY 1
			 */
				
			$db = JFactory::getDBO();
			$query = $db->getQuery(true);
			$query->select('distinct(name), description, lector1, lector1firm, lector2, lector2firm');
			$query->from('#__osbitcourses');
			$query->order('1');
			$db->setQuery((string)$query);
			$request = $db->loadObjectList();
				
			if($request)
			{
				foreach ($request as $course)
				{
					$this->courseInfos[] = array(
						'courseName'	=> $course->name,
						'courseInfo'	=> $course->description,
						'lector1'		=> $course->lector1,
						'lector1firm'	=> $course->lector1firm,
						'lector2'		=> $course->lector2,
						'lector2firm'	=> $course->lector2firm,
					);
				}
			}
		}
		return $this->courseInfos;
	}

	protected $columns;
	
	public function getColumns()
	{
		if(!isset($this->columns))
			$this->columns = JRequest::getInt('columns');
		return $this->columns;
	}
	
	protected $courses;

	/**
	 * Gets all courses from the database
	 */
	
	public function getCourses()
	{
		
		$this->getColumns();

		$userID = JRequest::getInt('uID');
		$password = JRequest::getString('code');
		
		// Check the user
		$person = $this->getPerson($userID);
		if(!$this->checkUser($userID, $password))
			die('com_osbit - getCourses - Hacking');
		
		if(!isset($this->courses))
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
			$request = $db->loadObjectList();
			if($request)
				foreach ($request as $section)
					if(!isset($this->courses[$section->sectionID]))
						$this->courses[$section->sectionID] = array(
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

			$oldRegistration = $this->getOldRegistration($userID);
			
//			foreach ($oldRegistration as $value)
//			{
//				echo $value . '<br />';
//			}
			
			if($request)
				foreach ($request as $course)
				{
					$row = count($this->courses[$course->sectionID]['rows']) - 1;
					if($row < 0)
						$row = 0;
					elseif(count($this->courses[$course->sectionID]['rows'][$row]) >= $this->columns)
						$row++;
					$this->courses[$course->sectionID]['rows'][$row][$course->ID] = array(
						'ID'				=> $course->ID,
						'courseID'			=> $course->courseID,
						'courseName'		=> $course->name,
						'lector1'			=> $course->lector1,
						'lector1Firm'		=> $course->lector1Firm,
						'lector2'			=> $course->lector2,
						'lector2Firm'		=> $course->lector2Firm,
						'maxRegistrations'	=> $course->maxRegistrations,
						'registrations'		=> $this->getCountRegistrations($course->ID),
						'room'				=> $course->room,
						'selected'			=> (($oldRegistration ? in_array($course->ID, $oldRegistration) : false) || (JRequest::getString('sec' . $course->sectionID) == $course->ID)),
					);
				}
		}
		return $this->courses;
	}

	protected $counts;
	
	/**
	 * Gets the count of registartion of the given course.
	 * @param int $couseID The ID of the course.
	 */
	
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
	
	protected function getOldRegistration($userID)
	{
		if(!is_numeric($userID) || $userID <= 0)
			die('com_osbit - getOldRegistration - $userID may an integer!');
		
		/*
		 * SQL query:
		 *
		 * SELECT courseID
		 * FROM #__osbitregistrations
		 * WHERE userID = '$useridID'
		 */
			
		$db = JFactory::getDBO();
		$query = $db->getQuery(true);
		$query->select('courseID');
		$query->from('#__osbitregistrations');
		$query->where("userID = '$userID'");
		$db->setQuery((string)$query);
		$request = $db->loadObjectList();
		
		if($request)
		{
			$IDs = array();
			foreach ($request as $value)
			{
				$IDs[] = $value->courseID;
			}
			return $IDs;
		}
		else
			return false;
	}
	
	protected $pos;

	/**
	 * Gets the current position.
	 */
	
	public function getPosition()
	{
		if(!isset($this->pos))
		$this->pos = JRequest::getInt('pos');
		return $this->pos;
	}

	protected $params;

	/**
	 * Gets an array of all parameters.
	 */
	
	public function getParams()
	{
		if (!isset($this->params))
		{
			$this->params = array();
			$this->params['header']					= JRequest::getString('header');
			$this->params['personalDataInfo']		= str_replace('\n', '<br>', JRequest::getString('personalDataInfo'));
			$this->params['allowNewRegistration']	= true;
			$this->params['showSchoolAsList']		= (bool) JRequest::getInt('showSchoolAsList');
			$this->params['showClassAsList']		= (bool) JRequest::getInt('showClassAsList');
			$this->params['dateBegin']				= JRequest::getCmd('dateBegin', '00.00.0000');
			$this->params['timeBegin']				= JRequest::getVar('timeBegin', '00:00');
			$this->params['dateEnd']				= JRequest::getCmd('dateEnd', '00.00.0000');
			$this->params['timeEnd']				= JRequest::getVar('timeEnd', '00:00');
			
			if(!preg_match('/^[0-3 ]\d.[01 ]\d.\d{4}$/', $this->params['dateBegin']))
				$this->params['dateBegin'] = '00.00.0000';
			if(!preg_match('/^[0-2 ]\d:[0-6 ]\d$/', $this->params['timeBegin']))
				$this->params['timeBegin'] = '00:00';
			if(!preg_match('/^[0-3 ]\d.[01 ]\d.\d{4}$/', $this->params['dateEnd']))
				$this->params['dateEnd'] = '00.00.0000';
			if(!preg_match('/^[0-2 ]\d:[0-6 ]\d$/', $this->params['timeEnd']))
				$this->params['timeEnd'] = '00:00';
			
			// Current date and time is before registration begin.
			$this->params['registrationState'] = -1;
			
			// Current date is in registration time.
			if(($this->params['dateBegin'] == '00.00.0000' && $this->params['dateEnd'] == '00.00.0000') ||
				($this->params['dateEnd'] == '00.00.0000' && date('d.m.Y H:i') >= $this->params['dateBegin'] . ' ' . $this->params['timeBegin']) ||
				($this->params['dateBegin'] == '00.00.0000' && date('d.m.Y H:i') <= $this->params['dateEnd'] . ' ' . $this->params['timeEnd']) ||
				(date('d.m.Y H:i') >= $this->params['dateBegin'] . ' ' . $this->params['timeBegin'] && date('d.m.Y H:i') <= $this->params['dateEnd'] . ' ' . $this->params['timeEnd']))
				$this->params['registrationState'] = 1;
			
			// Registration is closed
			if($this->params['dateBegin'] != '00.00.0000' && date('d.m.Y H:i') >= $this->params['dateEnd'] . ' ' . $this->params['timeEnd'])
			{
				$this->params['registrationState'] = 0;
				$this->params['allowNewRegistration'] = false;
			}
			
// TODO - add params for mailer here
		}
		return $this->params;
	}

	protected $schoolList;

	/**
	 * Returns a string that contains all posible schools as options 
	 * for a select input.
	 */
	
	public function getSchoolList()
	{
		if(!isset($this->schoolList))
		{
			/*
			 * SQL query:
			 *
			 * SELECT DISTINCT(school)
			 * FROM #__osbitusers
			 * ORDER BY 1
			 */
				
			$db = JFactory::getDBO();
			$query = $db->getQuery(true);
			$query->select('DISTINCT(school)');
			$query->from('#__osbitusers');
			$query->order('1');
			$db->setQuery((string)$query);
			$request = $db->loadObjectList();

			$seletedSchool = JRequest::getString('school');
			
			if($request)
			{
				$this->schoolList = '<option selected="selected" disabled="disabled" value="-1">'. JText::_( 'COM_OSBIT_PLEASE_SELECT') . '</option>';
				foreach ($request as $school)
				{
					$this->schoolList .= '<option value="' . $school->school . '" ' . ($seletedSchool == $school->school ? 'selected="selected" ' : '') . '>' . $school->school . '</option>';
				}
			}
		}
		return $this->schoolList;
	}

	protected $classList;

	/**
	 * Returns a string that contains all posible classs as options 
	 * for a select input.
	 */
	
	public function getClassList()
	{
		if(!isset($this->classList))
		{
			/*
			 * SQL query:
			 *
			 * SELECT DISTINCT(`class`)
			 * FROM #__osbitusers
			 * ORDER BY 1
			 */
				
			$db = JFactory::getDBO();
			$query = $db->getQuery(true);
			$query->select('DISTINCT(`class`)');
			$query->from('#__osbitusers');
			$query->order('1');
			$db->setQuery((string)$query);
			$request = $db->loadObjectList();

			$seletedClass = JRequest::getString('class');
			
			if($request)
			{
				$this->classList = '<option selected="selected" disabled="disabled" value="-1">' . JText::_( 'COM_OSBIT_PLEASE_SELECT') . '</option>';
				foreach ($request as $class)
				{
					$this->classList .= '<option value="' . $class->class . '" ' . ($seletedClass == $class->class ? 'selected="selected" ' : '') . '>' . $class->class . '</option>';
				}
			}
		}
		return $this->classList;
	}
	
	protected $person;
	
	public function getPerson($userID)
	{
		if(!is_numeric($userID) || $userID <= 0)
			die('com_osbit - getPerson - $userId may an integer!');
		if(!isset($this->person))
		{
			/*
			 * SQL query:
			 *
			 * SELECT *
			 * FROM #__osbitusers
			 * WHERE ID = '$userID'
			 */
			
			$db = JFactory::getDBO();
			$query = $db->getQuery(true);
			$query->select('*');
			$query->from('#__osbitusers');
			$query->where("`ID` = '$userID'");
			$db->setQuery((string)$query);
			$this->person = $db->loadObject();
		}
		return $this->person;
	}
	
	/**
	 * Checks whether the password belongs tor the given user or not.
	 * 
	 * @param int $userID The ID of the user.
	 * @param string $password The password to check.
	 */
	
	protected function checkUser($userID, $password)
	{
		if(!is_numeric($userID) || $userID <= 0)
			die('com_osbit - checkUser - $userId may an integer!');
		if(empty($password))
			die('com_osbit - checkUser - $password may not be empty!');

		$person = $this->getPerson($userID);
		
		if($person->password == $password)
			return true;
		
		return false;
	}
	
	public function getRegistration()
	{
		$app = JFactory::getApplication();
		$userID = JRequest::getInt('uID');
		$password = JRequest::getString('code');
		
		// Check the user
		//@TODO: Check warning "Creating default object from empty value."
		$res->person = $this->getPerson($userID);
		if(!$this->checkUser($userID, $password))
			die('com_osbit - getRegistration - Hacking');
		
		// Get all course sections
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
		$request = $db->loadObjectList();
			
		if($request)
		{
			foreach ($request as $section)
			{
				if(!isset($res->courses[$section->sectionID]))
				$res->courses[$section->sectionID] = array(
						'begin' => $section->begin,
						'end'	=> $section->end,
				);
			}
		}
		
		// Get the selected courses
		$IDs = array();
		$error = false;
		foreach ($res->courses as $sectionID => $values)
		{
			if(($res->courses[$sectionID]['ID'] = JRequest::getInt(('sec' . $sectionID), -1)) == -1)
			{
				//@TODO: Use Joomla! application here
				$this->setNotice(sprintf(JText::_('COM_OSBIT_ERROR_MISSING_COURSE'), $res->courses[$sectionID]['begin'], $res->courses[$sectionID]['end']));
			}
			else 
				$IDs[]= $res->courses[$sectionID]['ID'];
		}
		if(count($this->getNotices()))
			return false;
		
		// Get the course informations for the last page
		/*
		 * SQL query:
		 *
		 * SELECT #__osbitcoursesection.ID as ID, courseID, sectionID, name, maxRegistrations, room
		 * FROM #__osbitcourses
		 * INNER JOIN #__osbitcoursesection ON #__osbitcourses.id = #__osbitcoursesection.courseID
		 * INNER JOIN #__osbitcoursesections ON #__osbitcoursesections.id = #__osbitcoursesection.sectionID
		 * WHERE #__osbitcoursesection.ID in (implode(',', $IDs))
		 */
			
		$query = $db->getQuery(true);
		$query->select('#__osbitcoursesection.ID as ID, courseID, sectionID, name, maxRegistrations, room');
		$query->from('#__osbitcourses');
		$query->innerJoin('#__osbitcoursesection ON #__osbitcourses.id = #__osbitcoursesection.courseID');
		$query->innerJoin('#__osbitcoursesections ON #__osbitcoursesections.id = #__osbitcoursesection.sectionID');
		$query->where("#__osbitcoursesection.ID in (" . implode(', ', $IDs) . ")");
		$db->setQuery((string)$query);
		$request = $db->loadObjectList();
			
		if($request)
		{
			foreach ($request as $course)
			{
				$res->courses[$course->sectionID]['courseID'] = $course->courseID;
				$res->courses[$course->sectionID]['name'] = $course->name;
				$res->courses[$course->sectionID]['maxRegistrations'] = $course->maxRegistrations;
				$res->courses[$course->sectionID]['room'] = $course->room;
			}
		}	
		
		$error = false;
		
		// You musn't do a course two or more times
		foreach ($res->courses as $course1)
		{
			foreach ($res->courses as $course2)
			{
				if(($course1['courseID'] == $course2['courseID']) && ($course1['ID'] != $course2['ID']))
				{
					$error = true;
					JError::raiseNotice('400', $course1[name] . ' und ' . $course2['name']);
				}
			}
		}
		if($error)
		{
			$this->setNotice(JText::_('COM_OSBIT_ERROR_TWO_IDENTICAL_COURSES'), 'warning');
			return false;
		}
		
		$oldIDs = $this->getOldRegistration($userID);
		
		// Test current registration count of courses
		$fail = false;
		foreach ($res->courses as $course)
		{
			if($oldIDs !== false)
				foreach ($oldIDs as $oldID)
					if($oldID == $course['ID'])
						continue 2;
			
			if($this->getCountRegistrations($course['ID']) + 1 > $course['maxRegistrations'])
			{
				$fail = true;
				$this->setNotice(sprintf(JText::_('COM_OSBIT_ERROR_COURSE_FULL'), $course['name']), 'warning');
			}
		}
		if($fail)
			return false;
		
		
		// Delet old all entries
		/*
		 * SQL query:
		 *
		 * DELETE
		 * FROM #__osbitregistrations
		 * WHERE `userID` = '$userID'
		 */
		
		$query = $db->getQuery(true);
		$query->delete('#__osbitregistrations');
		$query->where("`userID` = '$userID'");
		$db->setQuery((string)$query);
		$db->query();
		
		// Insert the new entries
		foreach ($res->courses as $value)
		{
			/*
			 * SQL query:
			 *
			 * INSERT
			 * INTO #__osbitregistrations
			 * SET `userID` = '$userID', `courseID` = '$courseID'
			 */
			
			$query = $db->getQuery(true);
			$query->insert('#__osbitregistrations');
			$query->set("`userID` = '$userID'");
			$query->set("`courseID` = '" . $value['ID'] . "'");
			$db->setQuery((string)$query);
			$db->query();
		}
		
		if(JRequest::getBool('send_email', false))
		{
			$mailer = JFactory::getMailer();
			$config = JFactory::getConfig();
			$sender = array(
				$config->get( 'config.mailfrom' ),
				$config->get( 'config.fromname' ) );
			$mailer->setSender($sender);
			$mailer->addRecipient($res->person->email);
			
			$mailer->setSubject($this->params['header']);
			$body = sprintf(JText::_('COM_OSBIT_REGISTRATION_EMAIL_INTRODUCTION'), $res->person->firstName, $res->person->lastName);
			$body .= sprintf(JText::_('COM_OSBIT_REGISTRATION_EMAIL_COURSE_REGITRATION'));
			foreach ($res->courses as $course) {
				$body .= sprintf(JText::_('COM_OSBIT_REGISTRATION_EMAIL_COURSE'), substr($course['begin'], 0, 5), substr($course['end'], 0, 5), $course['name'], $course['room']);
			}
			
			$body .= sprintf(JText::_('COM_OSBIT_REGISTRATION_EMAIL_ENDING'));
			$mailer->setBody($body);
			$mailer->Send();
		}
		
		return $res;
	}
	
	/**
	 * Saves the data of the first step of registration
	 * and gets the data from the database.
	 */
	
	public function getPersonalData()
	{
		$this->getParams();
		if(($login = ($rG0 = JRequest::getString('RG0')) == 'login') && ($this->params['registrationState'] == 1))
		{
			$userName = JRequest::getString('userName');
			If(empty($userName))
			{
				$this->setNotice(JText::_( 'COM_OSBIT_ERROR_USERNAME_IS_EMPTY'));
				return;
			}
			$password = JRequest::getString('password');
			If(empty($password))
			{
				$this->setNotice(JText::_( 'COM_OSBIT_ERROR_PASSWORD_IS_EMPTY'));
				return;
			}
			$passwordHash = md5($password);
			
			/*
			 * SQL query:
			 *
			 * SELECT *
			 * FROM #__osbitusers
			 * WHERE userName like '$userName'
			 * AND password = '$passwordHash'
			 */
			
			$db = JFactory::getDBO();
			$query = $db->getQuery(true);
			$query->select('*');
			$query->from('#__osbitusers');
			$query->where("`userName` like '$userName'");
			$query->where("`password` = '$passwordHash'");
			$db->setQuery((string)$query);
			$db->query();
			if($db->getNumRows() != 1)
			{
				$this->setNotice(JText::_( 'COM_OSBIT_ERROR_INVALID_USERNAME_OR_PASSWORD'));
				return;
			}
			$this->person = $db->loadObject();
			
		}
		else 
		{
			if(empty($rG0))
			{
				$this->setNotice(JText::_( 'COM_OSBIT_ERROR_RG0_IS_EMPTY'));
				return;
			}
			
			$firstName		= JRequest::getString('firstName');
			$lastName		= JRequest::getString('lastName');
			$email			= JRequest::getString('email');
			$birthdate		= JRequest::getString('birthdate');
			$school	= JRequest::getString('school');
			$class			= JRequest::getString('class');
			
			$this->person = $this->registerPerson($firstName, $lastName, $email, $birthdate, $school, $class);
		}
		JRequest::setVar('uID', $this->person->ID);
		JRequest::setVar('code', $this->person->password);
		return $this->person;
	}
	
	/**
	 * Check whether a person exits in the database or not, creats a user name
	 * and a password for this person and send an email withe the user name
	 * and password to the person.
	 * 
	 * @param string $firstName The first name of the person
	 * @param string $lastName The last name of the person
	 * @param string $email The email of the person
	 * @param srring $birthdate The birthdate of the pwerson
	 * @param string $school The organisatio of the person
	 * @param string $class the class of the person
	 */
	protected function registerPerson($firstName, $lastName, $email, $birthdate, $school, $class)
	{
		if(empty($email))
			$this->setNotice(JText::_( 'COM_OSBIT_ERROR_EMAIL_IS_EMPTY'));
		elseif(!JMailHelper::isEmailAddress($email))
			$this->setNotice(JText::_( 'COM_OSBIT_ERROR_EMAIL_IS_NOT_VALID'));
			
		/*
		 * SQL query:
		 *
		 * SELECT *
		 * FROM #__osbitusers
		 * WHERE `firstName` like '$firstName'
		 * AND `lastName` like '$lastName'
		 * AND `birthdate` like '$birthdate'
		 * AND `school` like '$school'
		 * AND `class` like '$class'
		 */
		
		$db = JFactory::getDBO();
		$query = $db->getQuery(true);
		$query->select('*');
		$query->from('#__osbitusers');
		
		if(!empty($firstName))
			$query->where("`firstName` like '$firstName'");
		else
			$this->setNotice(JText::_( 'COM_OSBIT_ERROR_FIRST_NAME_IS_EMPTY'));
		
		if(!empty($lastName))
			$query->where("`lastName` like '$lastName'", "and");
		else
			$this->setNotice(JText::_( 'COM_OSBIT_ERROR_LAST_NAME_IS_EMPTY'));
		
		if(!empty($school))
			$query->where("`school` like '$school'", "and");	
		else
			$this->setNotice(JText::_( 'COM_OSBIT_ERROR_SCHOOL_IS_EMPTY'));
	
		if(!empty($class))
			$query->where("`class` like '$class'", "and");	
		else
			$this->setNotice(JText::_( 'COM_OSBIT_ERROR_CLASS_IS_EMPTY'));
		
			
		if(count($this->notices))
			return;
			
		$db->setQuery((string)$query);
		$db->query();
		if($db->getNumRows() != 1)
		{
			// forgot middel name?
			/*
			 * SQL query:
			 *
			 * SELECT *
			 * FROM #__osbitusers
			 * WHERE `firstName` like '%$firstName%'
			 * AND `lastName` like '$lastName'
			 * AND `birthdate` like '$birthdate'
			 * AND `school` like '$school'
			 * AND `class` like '$class'
			 */
		
			$query = $db->getQuery(true);
			$query->select('*');
			$query->from('#__osbitusers');
			$query->where("`firstName` like '%$firstName%'");
			$query->where("`lastName` like '$lastName'", "and");
			$query->where("`school` like '$school'", "and");	
			$query->where("`class` like '$class'", "and");	
			$db->setQuery((string)$query);
			$db->query();
			if(($numRows = $db->getNumRows()) == 0)
			{
				$this->setNotice(JText::_( 'COM_OSBIT_ERROR_PERSON_NOT_FOUND'));
				return;
			}
			if($numRows > 1)
			{
				$this->setNotice(JText::_( 'COM_OSBIT_ERROR_PERSON_NOT_FOUND_TO_MANY'));	
				return;
			}
		}
		
		$request = $db->loadObject();
		
		$res = $this->creatUserName($userID = $request->ID);
		
		$userName = $res['userName'];
		$password = $res['password'];
		
		$passwordHash = md5($password);
		
		/*
		 * SQL query:
		 *
		 * UPDATE #__osbitusers
		 * SET userName = '$userName', password = '$password', email = '$email'
		 * WHERE ID = '$userID'
		 */
		
		$query = $db->getQuery(true);
		$query->update('#__osbitusers');
		$query->set("userName = '$userName'");
		$query->set("password = '$passwordHash'");
		$query->set("email = '$email'");
		$query->set("hasRegistered = '1'");
		$query->where("`ID` = '$userID'");
		$db->setQuery((string)$query);
		$db->query();

// TODO - impement mailer here.
		$mailer = JFactory::getMailer();
		$config = JFactory::getConfig();
		$sender = array( 
		    $config->get( 'config.mailfrom' ),
		    $config->get( 'config.fromname' ) );
		 
		$mailer->setSender($sender);
		$mailer->addRecipient($email);
		
		$mailer->setSubject($this->params['header']);
		
		$body = sprintf(JText::_('COM_OSBIT_REGISTRATION_EMAIL_INTRODUCTION'), $firstName, $lastName);
		$body .= sprintf(JText::_('COM_OSBIT_REGISTRATION_EMAIL_USER_REGISTRATION'), $userName, $password);
		$body .= sprintf(JText::_('COM_OSBIT_REGISTRATION_EMAIL_ENDING'));
		
		$mailer->setBody($body);
		$mailer->Send();
		
		/*
		 * SQL query:
		 *
		 * SELECT *
		 * FROM #__osbitusers
		 * WHERE ID = '$userID'
		 */
	
		$query = $db->getQuery(true);
		$query->select('*');
		$query->from('#__osbitusers');
		$query->where("`ID` = '$userID'");
		$db->setQuery((string)$query);
		
		return $db->loadObject();
	}
	
	/**
	 * Creats the user name out of the last name plus the first 
	 * letter of the first name. If this name allready exists, a
	 * number will be added.
	 * 
	 * @param int $userID The id of the user to update the database.
	 * 
	 * @return string The user name.
	 */
	
	protected function creatUserName ($userID)
	{
		if(!is_numeric($userID) || $userID <= 0)
			die('com_osbit - createUserName - $userId may an integer!');
		
		// Get the date form datebase
		/*
		 * SQL query:
		 *
		 * SELECT firstName, lastName, userName
		 * FROM #__osbitusers
		 * WHERE id = $userID
		 */
		
		$db = JFactory::getDBO();
		$query = $db->getQuery(true);
		$query->select('firstName, lastName, userName');
		$query->from('#__osbitusers');
		$query->where("`ID` = '$userID'");
		$db->setQuery((string)$query);
		$db->query();
		if($db->getNumRows() != 1)
			die('com_osbit - createUserName - User not found in data base!');
		
		$row = $db->loadRow();
			
		list($firstName, $lastName, $userName) = $row;

		if(empty($userName))
		{
			// Create user name
			$i = -1;
			do
			{
				$userName = $lastName . substr($firstName, 0, 1) . (++$i == 0 ? '' : $i);
				
				/*
				 * SQL query:
				 *
				 * SELECT count(*) as count
				 * FROM #__osbitusers
				 * WHERE userName like '$userName'
				 */
				
				$db = JFactory::getDBO();
				$query = $db->getQuery(true);
				$query->select('count(*) as count');
				$query->from('#__osbitusers');
				$query->where("`userName` like '$userName'");
				$db->setQuery((string)$query);
				$request = $db->loadObject();
			}while ($request->count != 0);
		}
		// Creat the password
		$password = '';
		$pool = "qwertzupasdfghkyxcvbnm";
		$pool .= "23456789";
		$pool .= "WERTZUPLKJHGFDSAYXCVBNM";
		srand ((double)microtime()*1000000);
		
		for ($i = 0; $i < 8; $i++)
		{
    		$password .= substr($pool,(rand()%(strlen ($pool))), 1);
		}
		
		return array('userName' => $userName, 'password' => $password);
	}
	
	protected $notices = array();
	
	/**
	 * Sets a notice.
	 * 
	 * @param string $notice The notice to set
	 */
	
	protected function setNotice($notice)
	{
		array_push($this->notices, $notice);
	}
	
	/**
	 * Return an array of all notices.
	 * 
	 */
	
	public function getNotices()
	{
		return $this->notices;
	}
	
	/**
	 * Gets the task
	 */
	
	public function getTask()
	{
		return JRequest::getString('task');
	}
}