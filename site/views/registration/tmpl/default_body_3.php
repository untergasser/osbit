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
	<td class="osbit_stepX_td">
		<table class="osbit_table" cellspacing="0">
			<tr>
				<td colspan="2" class="osbit_step3_username">
					<strong><?php echo JText::_( 'COM_OSBIT_REGISTRATION_STEP_FINISH_COURSES_FOR') . ' ' . $this->summary->person->firstName . ' ' . $this->summary->person->lastName ?>:</strong>
				</td>
			</tr>
<?php foreach ($this->summary->courses as $sectionID => $course): ?>
			<tr>
				<td rowspan="2" class="osbit_step3_time">
					<?php echo substr($course['begin'], 0, 5); ?> - <?php echo substr($course['end'], 0, 5); ?>
				</td>
				<td class="osbit_step3_name">
					<?php echo $course['name']; ?>
				</td>
			</tr>	
			<tr>
				<td class="osbit_step3_room">
					<?php echo $course['room']; ?>
				</td>
			</tr>	
<?php endforeach; ?>
		</table>
	</td>
</tr>