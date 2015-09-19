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
	}
}