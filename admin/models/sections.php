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
 * Section list Model
 */
class OSBITModelSections extends JModelList
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
		 * SELECT `ID`, `begin`, `end`
		 * FORM #__osbitcoursesections
		 */
		$db = JFactory::getDBO();
		$query = $db->getQuery(true);
		$query->select('`ID`, `begin`, `end`');
		$query->from('#__osbitcoursesections');
		return $query;
	}
}