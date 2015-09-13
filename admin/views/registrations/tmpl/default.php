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
JHtml::addIncludePath(JPATH_COMPONENT.'/helpers/html');
JHtml::_('behavior.tooltip');
JHTML::_('script','system/multiselect.js',false,true);
?>
<form action="<?php echo JRoute::_('index.php?option=com_osbit&view=registrations'); ?>" method="post" name="adminForm" id="adminForm">
	<fieldset id="filter-bar">
		<div class="filter-search fltlft">
			<label class="filter-search-lbl" for="filter_search"><?php echo JText::_('JSEARCH_FILTER_LABEL'); ?></label>
			<input type="text" name="filter_search" id="filter_search" value="<?php echo $this->escape($this->state->get('filter.search')); ?>" title="<?php echo JText::_('COM_OSBIT_REGISTRATIONS_SEARCH_IN_USER_NAME'); ?>" />
			<button type="submit"><?php echo JText::_('JSEARCH_FILTER_SUBMIT'); ?></button>
			<button type="button" onclick="document.id('filter_search').value='';this.form.submit();"><?php echo JText::_('JSEARCH_FILTER_CLEAR'); ?></button>
		</div>
		<div class="filter-select fltrt">

			<select name="filter_school" class="inputbox" onchange="this.form.submit()">
				<option value=""><?php echo JText::_('COM_OSBIT_REGISTRATIONS_SELECT_SCHOOL');?></option>
				<?php echo JHtml::_('select.options', JFormFieldSchoolClient::getOptions(), 'value', 'text', $this->state->get('filter.school'), true);?>
			</select>

			<select name="filter_class" class="inputbox" onchange="this.form.submit()">
				<option value=""><?php echo JText::_('COM_OSBIT_REGISTRATIONS_SELECT_CLASS');?></option>
				<?php echo JHtml::_('select.options', JFormFieldClassClient::getOptions(), 'value', 'text', $this->state->get('filter.class'));?>
			</select>
			<select name="filter_sectionID" class="inputbox" onchange="this.form.submit()">
				<option value="0"><?php echo JText::_('COM_OSBIT_REGISTRATIONS_SELECT_SECTION');?></option>
				<?php echo JHtml::_('select.options', JFormFieldSectionClient::getOptions(), 'value', 'text', $this->state->get('filter.sectionID'));?>
			</select>
			<select name="filter_courseID" class="inputbox" onchange="this.form.submit()">
				<option value="0"><?php echo JText::_('COM_OSBIT_REGISTRATIONS_SELECT_COURSE');?></option>
				<?php echo JHtml::_('select.options', JFormFieldCourseClient::getOptions(), 'value', 'text', $this->state->get('filter.courseID'));?>
			</select>
		</div>
	</fieldset>
	<div class="clr"> </div>
	<table class="table">
		<thead><?php echo $this->loadTemplate('head');?></thead>
		<tfoot><?php echo $this->loadTemplate('foot');?></tfoot>
		<tbody><?php echo $this->loadTemplate('body');?></tbody>
	</table>
	<div>
		<input type="hidden" name="task" value="" />
		<input type="hidden" name="boxchecked" value="0" />
		<input type="hidden" name="filter_order" value="<?php echo $this->listOrder; ?>" />
		<input type="hidden" name="filter_order_Dir" value="<?php echo $this->listDirn; ?>" />
		<input type="hidden" name="view" value="registrations">
		<?php echo JHtml::_('form.token'); ?>
	</div>
</form>
