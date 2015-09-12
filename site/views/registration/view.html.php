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
 
jimport( 'joomla.application.component.view' );
jimport( 'joomla.html.html' );

/**
 * HTML View class for the OSBIT Component
 *
 * @package    OSBIT
 */
class OSBITViewRegistration extends JViewLegacy
{	
    function display($tpl = null)
    {	
    	$this->params	= $this->get('Params');
		
    	$this->pos		= $this->get('Position');
    	
    	$this->columns	= $this->get('Columns'); 
    	
    	if($this->coursePreview = ($this->params['registrationState'] == -1))
    		$this->pos = 1;
    	
    	$this->data();
 
    	// Load the css
    	$document = JFactory::getDocument();
    	$document->addStyleSheet(JURI::root() . 'media/com_osbit/css/com_osbit.css');
//     	JHTML::stylesheet(JURI::root() , 'media/com_osbit/css/com_osbit.css');
    	
    	// Check for notices.
        if (count($notices = $this->get('Notices'))) 
        {
	        JError::raiseNotice(500, implode('<br />', $notices));
	        $this->pos--;
	        $this->data();
        }
    	
        // Check for errors.
        if (count($errors = $this->get('Errors'))) 
        {
	        JError::raiseError(500, implode('<br />', $errors));
	        return false;
        }
        
        // Display the template
        parent::display($tpl);
    	
    }
    
    protected function data()
    {
    	switch ($this->pos)
    	{
    		case 0:
	    		//Dosen't need special informations form the model
	    		break;
	    	case 1:
	    		if($this->get('Task') == 'next')
    				$this->get('PersonalData');
	    		//Needs the course informations
        		$this->courseInfos = $this->get('CourseInfos');
	    		break;
	    	case 2:
	    		//Needs the courses
	    		$this->courses = $this->get('Courses');
	    		break;
	    	case 3:
	    		//
	    		$this->summary = $this->get('Registration');
	    		break;
    	}
    }
}
