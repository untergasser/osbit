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
 * User list Model
 */
class OSBITModelUsers extends JModelList
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
					'ID',
					'firstName',
					'lastName',
					'school',
					'`class`',
					'hasRegistered',
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
	
		$hasRegistered = $this->getUserStateFromRequest($this->context.'.filter.hasRegistered', 'filter_hasRegistered', '');
		$this->setState('filter.hasRegistered', $hasRegistered);
	
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
		$id	.= ':'.$this->getState('filter.hasRegistered');
	
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
		* SELECT `ID`, `firstName`, `lastName`, `email`, `birthdate`, `school`, `class`, `userName`, `hasRegistered`
		* FORM #__osbitusers
		*/
		$db = JFactory::getDBO();
		$query = $db->getQuery(true);
		$query->select('`ID`, `firstName`, `lastName`, `email`, `school`, `class`, `userName`, `hasRegistered`');
		$query->from('#__osbitusers');
	
		// Filter by search in title
		$search = $this->getState('filter.search');
		if (!empty($search)) {
			if (stripos($search, 'id:') === 0) {
				$query->where('`ID` = '.(int) substr($search, 3));
			} else {
				$search = $db->Quote('%'.$db->escape($search, true).'%');
				$query->where('(`firstName` LIKE '.$search.' OR `lastName` LIKE '.$search.')');
			}
		}
	
		// Filter by school
		if($school = $this->getState('filter.school'))
		$query->where('`school` = ' . $db->quote($school));
	
		// Filter by class
		if($class = $this->getState('filter.class'))
		$query->where('`class` = ' . $db->quote($class));
	
		// Filter by registration.
		$hasRegistered = $this->getState('filter.hasRegistered');
		if (is_numeric($hasRegistered) && ($hasRegistered === 0 || $hasRegistered == 1)) {
			$query->where('`hasRegistered` = '.(int) $hasRegistered);
		}
	
		// Add the list ordering clause.
		$orderCol	= $this->state->get('list.ordering');
		$orderDirn	= $this->state->get('list.direction');
		$query->order($db->escape($orderCol.' '.$orderDirn));
	
		return $query;
	}
}