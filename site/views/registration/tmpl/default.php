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

<script type="text/javascript">
<!--
/**
* Submit the admin form
* taken form joomla (/include/js/joomla.javascript.js
*/
function submitform(pressbutton){
	if (pressbutton) {
		document.comOSBITRegistration.task.value=pressbutton;
	}
	document.comOSBITRegistration.submit();
}
//-->
</script>

<form action="<?php echo JRoute::_('index.php?option=com_osbit'); ?>" method="post" name="comOSBITRegistration">
	<table class="adminlist osbit_table" cellspacing="0">
		<thead><?php echo $this->loadTemplate('head');?></thead>
		<tfoot><?php echo $this->loadTemplate('foot');?></tfoot>
		<tbody><?php echo $this->loadTemplate('body_' . ($this->params['registrationState'] != 0 ? $this->pos : 'closed')); ?></tbody>
	</table>
	<div>
		<input type="hidden" name="option" value="com_osbit" />
		<input type="hidden" name="view" value="registration" />
		<input type="hidden" name="task" value="" />
		<input type="hidden" name="pos" value="<?php echo $this->pos; ?>" />
		<?php echo JHtml::_('form.token'); ?>
		
<?php if($this->pos == 1 || $this->pos == 2): ?>
		<input type="hidden" name="uID" value="<?php echo JRequest::getString('uID'); ?>" />
		<input type="hidden" name="code" value="<?php echo JRequest::getString('code'); ?>" />
<?php endif; ?>
	</div>
</form>