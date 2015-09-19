<?php
/**
 * @version		1.0.0
 * @package		Joomla
 * @subpackage	OSBIT
 * @copyright	(C) 2010 - 2012 Mathias Gebhardt
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

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
	 *
	 * @param	string	$name	The name of the model.
	 * @param	string	$prefix	The prefix for the model class name.
	 *
	 * @return	JModel
	 * @since	1.6
	 */
	public function getModel($name = 'Registrations', $prefix = 'OSBITModel', $config = array())
	{
		$model = parent::getModel($name, $prefix, array('ignore_request' => true));
		return $model;
	}
	
	/**
	 * Display method for the csv registration data.
	 *
	 * @param	boolean			If true, the view output will be cached
	 * @param	array			An array of safe url parameters and their variable types, for valid values see {@link JFilterInput::clean()}.
	 *
	 * @return	JController		This object to support chaining.
	 * @since	1.5
	 * @todo	This should be done as a view, not here!
	 */
	public function display($cachable = false, $urlparams = false)
	{
	// Get the document object.
		$document	= JFactory::getDocument();
		$vName		= 'registrations';
		$vFormat	= 'csv';

		// Get and render the view.
		if ($view = $this->getView($vName, $vFormat))
		{
			// Get the model for the view.
			$model = $this->getModel($vName);

			// Push the model into the view (as default).
			$view->setModel($model, true);

			// Push document object into the view.
			$view->assignRef('document', $document);

			$view->display();
		}
	}
}