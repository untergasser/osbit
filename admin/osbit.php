<?php
/**
 * @version		1.0.0
 * @package		Joomla
 * @subpackage	OSBIT
 * @copyright	(C) 2010 - 2015 M. Gebhardt and A. Untergasser
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

// Access check.
if (!JFactory::getUser()->authorise('core.manage', 'com_osbit')) 
{
	return JError::raiseWarning(404, JText::_('JERROR_ALERTNOAUTHOR'));
}

// Set some global property
$document = JFactory::getDocument();
$document->addStyleDeclaration('.icon-48-osbit {background-image: url(../media/com_osbit/images/icon-48-osbit.png);}');
$document->addStyleDeclaration('.icon-48-users {background-image: url(../media/com_osbit/images/icon-48-users.png);}');
$document->addStyleDeclaration('.icon-48-courses {background-image: url(../media/com_osbit/images/icon-48-courses.png);}');
$document->addStyleDeclaration('.icon-48-sections {background-image: url(../media/com_osbit/images/icon-48-sections.png);}');
$document->addStyleDeclaration('.icon-48-registrations {background-image: url(../media/com_osbit/images/icon-48-registrations.png);}');
$document->addStyleDeclaration('.icon-32-import {background-image: url(../media/com_osbit/images/icon-32-import.png);}');

// require helper file
JLoader::register('OSBITHelper', dirname(__FILE__) . DS . 'helpers' . DS . 'osbit.php');

// import joomla controller library
jimport('joomla.application.component.controller');
 
// Get an instance of the controller prefixed by OSBIT
$controller = JController::getInstance('OSBIT');
 
// Perform the Request task
$controller->execute(JRequest::getCmd('task'));
 
// Redirect if set by the controller
$controller->redirect();