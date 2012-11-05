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
<form action="<?php echo JRoute::_('index.php?option=com_osbit&layout=edit&ID='.(int) $this->item->ID); ?>" method="post" name="adminForm" id="osbit-form" class="form-validate">
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

	<div>
		<input type="hidden" name="task" value="user.edit" />
		<?php echo JHtml::_('form.token'); ?>
	</div>
</form>
