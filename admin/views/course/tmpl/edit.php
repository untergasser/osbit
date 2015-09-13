<?php
/**
 * @version		1.0.0
 * @package		Joomla
 * @subpackage	OSBIT
 * @copyright	(C) 2010 - 2012 Mathias Gebhardt
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access
defined('_JEXEC') or die('Restricted access');
JHtml::_('behavior.tooltip');
JHtml::_('behavior.formvalidation');
$params = $this->form->getFieldsets('params');
?>
<form action="<?php echo JRoute::_('index.php?option=com_osbit&layout=edit&ID='.(int) $this->item->ID); ?>" method="post" name="adminForm" id="adminForm" class="form-validate">
	<div class="width-60 fltlft">
		<fieldset class="adminform">
			<legend><?php echo JText::_( 'JDETAILS' ); ?></legend>
			<ul class="adminformlist">
<?php foreach($this->form->getFieldset('details') as $field): ?>
				<li><?php echo $field->label;echo $field->input;?></li>
<?php endforeach; ?>
			</ul>
		</fieldset>
	</div>

	<div class="width-40 fltrt">
		<?php echo JHtml::_('sliders.start', 'osbit-slider'); ?>

<?php foreach ($params as $name => $fieldset): ?>
		<?php echo JHtml::_('sliders.panel', JText::_($fieldset->label), $name.'-params');?>
	<?php if (isset($fieldset->description) && trim($fieldset->description)): ?>
		<p class="tip"><?php echo $this->escape(JText::_($fieldset->description));?></p>
	<?php endif;?>
		<fieldset class="panelform" >
			<ul class="adminformlist">
	<?php foreach ($this->form->getFieldset($name) as $field) : ?>
				<li><?php echo $field->label; ?><?php echo $field->input; ?></li>
	<?php endforeach; ?>
			</ul>
		</fieldset>
<?php endforeach; ?>

		<?php echo JHtml::_('sliders.end'); ?>
	</div>

	<div class="width-60 fltlft">
		<fieldset class="adminform">
			<legend><?php echo JText::_('COM_OSBIT_COURSE_SECTIONS'); ?></legend>
<?php
$count 	= sizeof($this->sections);
$i = 0;
if ($count) :
?>					
			<table class="table">
				<thead>
					<tr>
						<th width="20">
							&nbsp;
						</th>
						<th width="110">
							Zeit
						</th>
						<th>
							Raum
						</th>
					</tr>
				</thead>
				<tfoot>
					<tr>
						<td colspan="3">
						</td>
					</tr>
				</tfoot>
				<tbody>
<?php
	foreach ($this->sections as $section) :
		$checked = in_array($section->ID, $this->item->sections) ? ' checked="checked"' : '';
?>
					<tr class="row<?php echo $i++ % 2; ?>">
						<td><input type="checkbox" name="jform[sections][]" value="<?php echo (int) $section->ID;?>" id="sec<?php echo (int) $section->ID;?>"<?php echo $checked; ?> onchange="this.checked = (this.checked ? true: !confirm('Wenn Sie den Kurs speichern werden auch alle Schüler aus diesem Kurs ausgetragen.\n Ein Wiederherstellen der Einwahlen ist dann nicht mehr möglich.\nWählen Sie Ok zum löschen der Kurseinheit oder Abbrechen um diese zu behalten.')); return true;" /></td>
						<td><?php echo $section->begin . ' - ' . $section->end; ?></td>
						<td><input type="text" size="25" name="jform[rooms][<?php echo (int) $section->ID;?>]" value="<?php echo isset($this->item->rooms[$section->ID]) ? $this->item->rooms[$section->ID] : "" ;?>" /></td>
					</tr>				
<?php 
	endforeach;
?>
				</tbody>
			</table>			
<?php
endif;
?>
		</fieldset>
	</div>

	<div>
		<input type="hidden" name="task" value="course.edit" />
		<?php echo JHtml::_('form.token'); ?>
	</div>
</form>

