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

// load tooltip behavior
JHtml::_('behavior.tooltip');
?>
<form enctype="multipart/form-data" action="<?php echo JRoute::_('index.php?option=com_osbit&view=userimport');?>" method="post" name="adminForm" id="adminForm">
	<div class="fltlft">
		<fieldset>
			<span><?php echo JText::_('COM_OSBIT_USERIMPORT_MESSAGE'); ?></span>
		</fieldset>
		<fieldset class="uploadform">
			<legend><?php echo JText::_('COM_OSBIT_USERIMPORT_LABEL_UPLOAD'); ?></legend>
			<label for="import_file"><?php echo JText::_('COM_OSBIT_USERIMPORT_CSV_FILE'); ?></label>
			<input class="input_box" id="import_file" name="import_file" type="file" size="57" />
		</fieldset>
		<fieldset>
			<legend><?php echo JText::_('COM_OSBIT_USERIMPORT_LABEL_SETTINGS'); ?></legend>
			<label for="ignore_first_line"><?php echo JText::_('COM_OSBIT_USERIMPORT_IGNORE_FIRST_LINE'); ?></label>
			<input type="checkbox" name="ignore_first_line" checked="checked" value="1" /><br />
<!-- 			<label for="excel_style"><?php echo JText::_('COM_OSBIT_USERIMPORT_EXCEL_STYLE'); ?></label> -->
<!-- 			<input type="checkbox" name="excel_style" checked="checked" value="1" /> -->
		</fieldset>
		<input type="hidden" name="task" value="userimport.import" />
		<?php echo JHtml::_('form.token'); ?>
	</div>
</form>