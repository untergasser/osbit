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
 * User import Controller
 */
class OSBITControllerUserimport extends JControllerAdmin
{
	/**
	 * Constructor.
	 *
	 * @param	array	An optional associative array of configuration settings.
	 */
	public function __construct($config = array())
	{
		parent::__construct($config);
		$this->registerTask('cancel', 'goBack');
		$this->registerTask('import', 'import');
	}
	
	public function goBack()
	{
		$this->redirect = JRoute::_('index.php?option=com_osbit') . '&view=users';
		$this->redirect();
	}
	
	public function import()
	{
		// Check for request forgeries
		JRequest::checkToken() or jexit(JText::_('JINVALID_TOKEN'));
		
		$model = $this->getModel('userimport');
		if($model->import())
			$this->goBack();
		$this->redirect = JRoute::_('index.php?option=com_osbit') . '&view=userimport';
		$this->redirect();
	}
}