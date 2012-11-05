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
?>

<tr>
	<th width="5">
		<?php echo JText::_('COM_OSBIT_HEADING_ID'); ?>
	</th>
	<th width="20">
		<input type="checkbox" name="toggle" value="" onclick="checkAll(<?php echo count($this->items); ?>);" />
	</th>
	<th>
		<?php echo JHtml::_('grid.sort',  'COM_OSBIT_USERS_HEADING_LAST_NAME', 'lastName', $this->listDirn, $this->listOrder); ?>
	</th>
	<th>
		<?php echo JHtml::_('grid.sort',  'COM_OSBIT_USERS_HEADING_FIRST_NAME', 'firstName', $this->listDirn, $this->listOrder); ?>
	</th>
	<th>
		<?php echo JHtml::_('grid.sort',  'COM_OSBIT_USERS_HEADING_EMAIL', 'email', $this->listDirn, $this->listOrder); ?>
	</th>
	<th>
		<?php echo JHtml::_('grid.sort',  'COM_OSBIT_USERS_HEADING_SCHOOL', 'school', $this->listDirn, $this->listOrder); ?>
	</th>
	<th>
		<?php echo JHtml::_('grid.sort',  'COM_OSBIT_USERS_HEADING_CLASS', 'class', $this->listDirn, $this->listOrder); ?>
	</th>
	<th>
		<?php echo JHtml::_('grid.sort',  'COM_OSBIT_USERS_HEADING_HAS_REGISTERED', 'hasRegistered', $this->listDirn, $this->listOrder); ?>
	</th>
</tr>