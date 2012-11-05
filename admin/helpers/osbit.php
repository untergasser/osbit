<?php
/**
 * @version		1.0.0
 * @package		Joomla
 * @subpackage	OSBIT
 * @copyright	(C) 2010 - 2012 Mathias Gebhardt
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access to this file
defined('_JEXEC') or die;

/**
 * OSBIT component helper.
 */
abstract class OSBITHelper
{
	/**
	 * Configure the Linkbar.
	 */
	public static function addSubmenu($submenu) 
	{
		JSubMenuHelper::addEntry(JText::_('COM_OSBIT_SUBMENU_OVERVIEW'), 'index.php?option=com_osbit', $submenu == 'main');
		JSubMenuHelper::addEntry(JText::_('COM_OSBIT_SUBMENU_USERS'), 'index.php?option=com_osbit&view=users', $submenu == 'users');
		JSubMenuHelper::addEntry(JText::_('COM_OSBIT_SUBMENU_COURSES'), 'index.php?option=com_osbit&view=courses', $submenu == 'courses');
		JSubMenuHelper::addEntry(JText::_('COM_OSBIT_SUBMENU_SECTIONS'), 'index.php?option=com_osbit&view=sections', $submenu == 'sections');
		JSubMenuHelper::addEntry(JText::_('COM_OSBIT_SUBMENU_REGISTRATIONS'), 'index.php?option=com_osbit&view=registrations', $submenu == 'registrations');
		// set some global property
//		$document = JFactory::getDocument();
//		$document->addStyleDeclaration('.icon-48-helloworld {background-image: url(../media/com_helloworld/images/tux-48x48.png);}');
	}
	/**
	 * Get the user actions
	 */
	public static function getUserActions($userId = 0)
	{
		$user	= JFactory::getUser();
		$result	= new JObject;

		if (empty($userId)) {
			$assetName = 'com_osbit';
		}
		else {
			$assetName = 'com_osbit.user.'.(int) $userId;
		}

		$actions = array(
			'core.admin', 'core.manage', 'core.create', 'core.edit', 'core.delete'
		);

		foreach ($actions as $action) {
			$result->set($action,	$user->authorise($action, $assetName));
		}

		return $result;
	}
	/**
	 * Get the section actions
	 */
	public static function getSectionActions($sectionId = 0)
	{
		$user	= JFactory::getUser();
		$result	= new JObject;

		if (empty($sectionId)) {
			$assetName = 'com_osbit';
		}
		else {
			$assetName = 'com_osbit.section.'.(int) $sectionId;
		}

		$actions = array(
			'core.admin', 'core.manage', 'core.create', 'core.edit', 'core.delete'
		);

		foreach ($actions as $action) {
			$result->set($action,	$user->authorise($action, $assetName));
		}

		return $result;
	}
	/**
	 * Get the course actions
	 */
	public static function getCourseActions($courseId = 0)
	{
		$user	= JFactory::getUser();
		$result	= new JObject;

		if (empty($courseId)) {
			$assetName = 'com_osbit';
		}
		else {
			$assetName = 'com_osbit.course.'.(int) $courseId;
		}

		$actions = array(
			'core.admin', 'core.manage', 'core.create', 'core.edit', 'core.delete'
		);

		foreach ($actions as $action) {
			$result->set($action,	$user->authorise($action, $assetName));
		}

		return $result;
	}
}
