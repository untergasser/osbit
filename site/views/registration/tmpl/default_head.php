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
	<td class="osbit_head_headding">
		<h1><?php echo $this->params['header'] ?></h1>
	</td>
</tr>
<?php if($this->params['registrationState'] == 1): ?>
<tr>
	<td class="osbit_head_nav">
		<table class="osbit_table" cellspacing="0">
			<tr class="osbit_head_nav_tr">
				<td class="osbit_head_left<?php echo $this->pos == 0 ? '_gn' : ''; ?>">
					<?php echo JText::_('COM_OSBIT_REGISTRATION_STEP_PERSONAL_DATA')?>
				</td>
				<td class="osbit_head_arrow<?php echo $this->pos == 0 ? '_gn' : ''; ?>">
					&nbsp;
				</td>
				<td class="osbit_head_middle<?php echo $this->pos == 1 ? '_gn' : ''; ?>">
					<?php echo JText::_('COM_OSBIT_REGISTRATION_STEP_COURSE_INFOS')?>
				</td>
				<td class="osbit_head_arrow<?php echo $this->pos == 1 ? '_gn' : ''; ?>">
					&nbsp;
				</td>
				<td class="osbit_head_middle<?php echo $this->pos == 2 ? '_gn' : ''; ?>">
					<?php echo JText::_('COM_OSBIT_REGISTRATION_STEP_SELECT_COURSES')?>
				</td>
				<td class="osbit_head_arrow<?php echo $this->pos == 2 ? '_gn' : ''; ?>">
					&nbsp;
				</td>
				<td class="osbit_head_middle<?php echo $this->pos == 3 ? '_gn' : ''; ?>">
					<?php echo JText::_('COM_OSBIT_REGISTRATION_STEP_FINISH')?>
				</td>
				<td>
				</td>
<?php if($this->pos == 2) :?>
				<td class="osbit_head_button osbit_head_button_prev">
					<a href="#" onclick="submitform('prev')" title="<?php echo JText::_('JPREVIOUS'); ?>">
						<div class="prev">
							<?php echo JText::_('JPREVIOUS'); ?>
						</div>
					</a>
				</td>
<?php endif; ?>
<?php if($this->pos >= 0 && $this->pos <= 2) :?>
				<td class="osbit_head_button osbit_head_button_next">
					<a href="#" onclick="submitform('<?php echo $this->pos == 2 ? 'submit' : 'next'; ?>')" title="<?php echo JText::_('JNEXT'); ?>">
						<div class="next">
							<?php echo JText::_('JNEXT'); ?>
						</div>
					</a>
				</td>
<?php endif; ?>
<?php if($this->pos == 3) :?>
				<td class="osbit_head_button osbit_head_button_reset">
					<a href="#" onclick="submitform('reset')" title="Neustart">
						<div class="reset">
							Neustart
						</div>
					</a>
				</td>
<?php endif; ?>
			</tr>
		</table>
	</td>
</tr>
<?php endif; ?>
<tr>
	<td class="osbit_head_nav" <?php echo ($this->params['registrationState'] != 1) ? 'style="color: red; text-align: center; font-size: 1.5em;"' : ''; ?>>
		<?php echo sprintf(JText::_('COM_OSBIT_REGISTRATION_PERIOD'), $this->params['dateBegin'], $this->params['timeBegin'], $this->params['dateEnd'], $this->params['timeEnd']); ?>
	</td>
</tr>
