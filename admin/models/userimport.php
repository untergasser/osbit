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

// import the Joomla modellist library
jimport('joomla.application.component.model');
/**
 * User import Model
 */
class OSBITModelUserimport extends JModelLegacy  
{
	
	protected $ignoreFirstLine;
	
	protected $excelStyle;
	
	public function import()
	{
		$app = JFactory::getApplication();
		
		$this->getSettings();
		
		if(!($file = $this->getCSVFromUpload()))
			return false;
		
		return $this->readCSVFile($file);
		
	}

	protected function readCSVFile($file)
	{
		$app			= JFactory::getApplication();
		$rows			= 0;
		$ignoredRows	= 0;
		$db				= JFactory::getDBO();
		
		if(($fp = fopen($file, 'r')) === false)
		{
			$app->enqueueMessage(JText::_('COM_OSBIT_USERIMPORT_COULD_NOT_OPEN_FILE'), 'error');
			return false;
		}
		
		if($this->ignoreFirstLine)
			fgetcsv($fp, 500, ';');
		
		while (($data = fgetcsv($fp, 500, ';')) !== FALSE)
		{
			// There must be 4 columns
			if(count($data) != 4)
			{
				$ignoredRows++;
				continue;
			}
			
			// Convert to UTF8
			for ($i = 0; $i < 4; $i++)
			{
				$data[$i] = utf8_encode($data[$i]);
			}
			
			// Check whether user already exists
			$query = $db->getQuery(true);
			$query->select('*');
			$query->from('#__osbitusers');
			$query->where("`lastName` LIKE '$data[0]'");
			$query->where("`firstName` LIKE '$data[1]'");
			$query->where("`class` LIKE '$data[2]'");
			$query->where("`school` LIKE '$data[3]'");
			$db->setQuery($query);
			$db->query();
			if($db->getNumRows() != 0)
			{
				// if user exits: continue with next user
				$ignoredRows++;
				continue;
			}
			
			// insert user into DB
			$query = $db->getQuery(true);
			$query->insert('#__osbitusers');
			$query->set("`lastName` = '$data[0]'");
			$query->set("`firstName` = '$data[1]'");
			$query->set("`class` = '$data[2]'");
			$query->set("`school` = '$data[3]'");
			$db->setQuery((string)$query);
			$db->query();
			
			$rows++;
			
		}

		fclose($handle);
		
		$app->enqueueMessage(sprintf(JText::_('COM_OSBIT_USERIMPORT_SUCCESS'), $rows, $ignoredRows));
		return true;
				
	}
	
	protected function getSettings()
	{
		$app = JFactory::getApplication();
		$this->ignoreFirstLine	= JRequest::getBool('ignore_first_line', false);
		$this->excelStyle		= JRequest::getBool('excel_style', true);
	}
	
	/**
	* Works out an scv file from a HTTP upload
	*
	* @return the path to scv file or false on failure
	*/
	protected function getCSVFromUpload()
	{
		$app = JFactory::getApplication();
		
		// Get the uploaded file information
		$userfile = JRequest::getVar('import_file', null, 'files', 'array');
	
		// Make sure that file uploads are enabled in php
		if (!(bool) ini_get('file_uploads')) {
			$app->enqueueMessage(JText::_('COM_OSBIT_USERIMPORT_NO_UPLOADS_ENABLED'), 'error');
			return false;
		}
	
		// If there is no uploaded file, we have a problem...
		if (!is_array($userfile)) {
			$app->enqueueMessage(JText::_('COM_OSBIT_USERIMPORT_NO_FILE_SELECTED'), 'error');
			return false;
		}
	
		// Check if there was a problem uploading the file.
		if ($userfile['error'] || $userfile['size'] < 1) {
			$app->enqueueMessage(JText::_('COM_OSBIT_USERIMPORT_COULD_NOT_UPLOAD'), 'error');
			return false;
		}
	
		// Build the appropriate paths
		$config		= JFactory::getConfig();
		$tmp_dest	= $config->get('tmp_path') . '/' . $userfile['name'];
		$tmp_src	= $userfile['tmp_name'];
	
		// Move uploaded file
		jimport('joomla.filesystem.file');
		if(!JFile::upload($tmp_src, $tmp_dest))
		{
			$app->enqueueMessage(JText::_('COM_OSBIT_USERIMPORT_COULD_NOT_UPLOAD'), 'error');
			return false;
		}
		
		return $tmp_dest;
	}
}