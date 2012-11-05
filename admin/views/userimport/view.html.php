<?php
/**
 * @version 1.0 $Id: view.html.php 2 2012-10-05 16:49:19Z mathias_lt $
 * @package Joomla
 * @subpackage OSBIT
 * @copyright (C) 2010 - 2011 Mathias Gebhardt
 * @license GNU/GPL, see LICENSE.php
 * OSBIT is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License 2
 * as published by the Free Software Foundation.

 * OSBIT is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.

 * You should have received a copy of the GNU General Public License
 * along with OSBIT; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 */

// No direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

// import Joomla view library
jimport('joomla.application.component.view');
 
/**
 * Users View
 */
class OSBITViewUserimport extends JView
{
	/**
	 * OSBIT view display method
	 * @return void
	 */
	function display($tpl = null) 
	{
		// Get data from the model
		
		
		// Check for errors.
		if (count($errors = $this->get('Errors'))) 
		{
			JError::raiseError(500, implode('<br />', $errors));
			return false;
		}
		// Assign data to the view
		
		// Set the toolbar
        $this->addToolBar();
		
        
		// Display the template
		parent::display($tpl);
	}
	
	/**
	* Setting the toolbar
	*/
	protected function addToolBar() 
	{
		JRequest::setVar('hidemainmenu', true);
		JToolBarHelper::title(JText::_('COM_OSBIT_USERIMPORT'), 'user');

		JToolBarHelper::custom('userimport.import','import','','Import',false);
		JToolBarHelper::cancel('userimport.cancel');
	}
}