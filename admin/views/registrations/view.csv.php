<?php
/**
 * @version		1.0.0
 * @package		Joomla
 * @subpackage	OSBIT
 * @copyright	(C) 2010 - 2012 Mathias Gebhardt
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

// import Joomla view library
jimport('joomla.application.component.view');

/**
 * View class for a csv export of registrations.
 */
class OSBITViewRegistrations extends JViewLegacy
{
	/**
	 * Display the view
	 */
	public function display($tpl = null)
	{
		$basename		= $this->get('BaseName');
		$filetype		= $this->get('FileType');
		$mimetype		= $this->get('MimeType');
		$content		= $this->get('Content');

		// Check for errors.
		if (count($errors = $this->get('Errors'))) {
			JError::raiseError(500, implode("\n", $errors));
			return false;
		}

		$document = JFactory::getDocument();
		$document->setMimeEncoding($mimetype);
		JResponse::setHeader('Content-disposition', 'attachment; filename="'.$basename.'.'.$filetype.'"; creation-date="'.JFactory::getDate()->toRFC822().'"', true);
		echo $content;
	}
}