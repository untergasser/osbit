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
 * Users Controller
 */
class OSBITControllerUsers extends JControllerAdmin
{
	/**
	* Constructor.
	*
	* @param	array	An optional associative array of configuration settings.
	*/
	public function __construct($config = array())
	{
		parent::__construct($config);
		$this->registerTask('import', 'import');
	}
	
	/**
	 * Proxy for getModel.
	 * @since	1.6
	 */
	public function getModel($name = 'User', $prefix = 'OSBITModel') 
	{
		$model = parent::getModel($name, $prefix, array('ignore_request' => true));
		return $model;
	}
	
	public function import()
	{
		$this->redirect = JRoute::_('index.php?option=com_osbit') . '&view=userimport';
		$this->redirect();
	}
}