<?php
/**
 * @version		1.0.0
 * @package		Joomla
 * @subpackage	OSBIT
 * @copyright	(C) 2010 - 2012 Mathias Gebhardt
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

// import Joomla view library
jimport('joomla.application.component.view');

/**
 * Registration View
 */
class OSBITViewRegistration extends JViewLegacy
{
	/**
	 * display method of user view
	 * @return void
	 */
	public function display($tpl = null) 
	{
		// get the Data
		$form = $this->get('Form');
		$item = $this->get('Item');
		$script = $this->get('Script');

		// Check for errors.
		if (count($errors = $this->get('Errors'))) 
		{
			JError::raiseError(500, implode('<br />', $errors));
			return false;
		}
		// Assign the Data
		$this->form = $form;
		$this->item = $item;
		$this->script = $script;

		// Set the toolbar
		$this->addToolBar();

		// Display the template
		parent::display($tpl);

		// Set the document
		$this->setDocument();
	}

	/**
	 * Setting the toolbar
	 */
	protected function addToolBar() 
	{
		JRequest::setVar('hidemainmenu', true);
		$user = JFactory::getUser();
		$userId = $user->id;
		$isNew = $this->item->ID == 0;
		JToolBarHelper::title($isNew ? JText::_('COM_OSBIT_REGISTRATION_NEW') : JText::_('COM_OSBIT_REGISTRATION_EDIT'), 'registrations');

		if (JFactory::getUser()->authorise('core.manage', 'com_osbit')) {
			JToolBarHelper::apply('registration.apply', 'JTOOLBAR_APPLY');
			JToolBarHelper::save('registration.save', 'JTOOLBAR_SAVE');
			JToolBarHelper::custom('registration.save2new', 'save-new.png', 'save-new_f2.png', 'JTOOLBAR_SAVE_AND_NEW', false);

			// Built the actions for new and existing records.
			if ($isNew) 
			{
				JToolBarHelper::cancel('registration.cancel', 'JTOOLBAR_CANCEL');
			}
			else
			{
				JToolBarHelper::custom('registration.save2copy', 'save-copy.png', 'save-copy_f2.png', 'JTOOLBAR_SAVE_AS_COPY', false);
				JToolBarHelper::cancel('registration.cancel', 'JTOOLBAR_CLOSE');
			}
		}
	}
	/**
	 * Method to set up the document properties
	 *
	 * @return void
	 */
	protected function setDocument() 
	{
		$isNew = $this->item->ID == 0;
		$document = JFactory::getDocument();
		$document->setTitle($isNew ? JText::_('COM_OSBIT_REGISTRATION_CREATING') : JText::_('COM_OSBIT_REGISTRATION_EDITING'));
		$document->addScript(JURI::root() . $this->script);
		$document->addScript(JURI::root() . "/administrator/components/com_osbit/views/registration/submitbutton.js");
		JText::script('COM_OSBIT_REGISTRATION_ERROR_UNACCEPTABLE');
	}
}
