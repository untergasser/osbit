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

// import Joomla controlleradmin library
jimport('joomla.application.component.controlleradmin');

/**
 * Registrations Controller
 */
class OSBITControllerRegistrations extends JControllerAdmin
{
	/**
	 * Constructor.
	 *
	 * @param	array	An optional associative array of configuration settings.
	 */
	public function __construct($config = array())
	{
		parent::__construct($config);
	}
	
	/**
	 * Proxy for getModel.
	 * @since	1.6
	 */
	public function getModel($name = 'Registration', $prefix = 'OSBITModel', $config = array()) 
	{
		if(!isset($config))
			$config = array();
		$config['ignore_request'] = false;
		$model = parent::getModel($name, $prefix, $config);
		return $model;
	}
	
	public function export()
	{
		$this->setRedirect(JRoute::_('index.php?option=com_osbit&task=registrations.display&format=csv', false));
		/*
		$app = JFactory::getApplication();
		$app->enqueueMessage('Export');
		
		// Check for request forgeries.
		JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));
		
		$model = $this->getModel('Registrations', 'OSBITModel');
		$return = $model->export();
		if ($return === false)
		{
			// Reorder failed.
			//COM_OSBIT_ERROR_REGISTRATION_EXPORT
			$message = JText::sprintf('Kurse: %s', $model->getError());
			$this->setRedirect(JRoute::_('index.php?option=com_osbit&view=registrations', false), $message, 'error');
			return false;
		}
		else
		{
			// Reorder succeeded.
			$message = JText::_('COM_OSBIT_REGISTRATION_EXPORT');
			$this->setRedirect(JRoute::_('index.php?option=com_osbit&view=registrations', false), $message);
			return true;
		}
		*/
	}
}