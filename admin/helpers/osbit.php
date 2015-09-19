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
	}
}
