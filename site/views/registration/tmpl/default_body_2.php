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
	<td class="osbit_stepX_td osbit_step2_email">
		<label for="send_email"><?php echo JText::_('COM_OSBIT_REGISTRATION_STEP_REGISTRATION_SEND_EMAIL'); ?></label>
		<input type="checkbox" name="send_email" checked="checked" value="1" />
	</td>
</tr>
<tr>
	<td class="osbit_stepX_td">
		<table class="osbit_table" cellspacing="0">
<?php
// TODO - check $courseSections array here
foreach ($this->courses as $sectionID => $courseSection):
	// TODO - check $courseSection array here
	$rows = 0;
	foreach ($courseSection['rows'] as $rowID => $courseRow): 
		$rows++; ?>
			<tr>
		<?php if($rowID == 0): ?>
				<td rowspan="<?php echo count($courseSection['rows']) * 3; ?>" class="osbit_step2_time">
					<strong><?php echo substr($courseSection['begin'], 0, 5); ?> - <?php echo substr($courseSection['end'], 0, 5); ?></strong>
				</td>
		<?php endif; 
		// TODO - check $courseRow array here		
		foreach ($courseRow as $ID => $course): ?>
				<td class="osbit_step2_name">
					<input type="radio" id="<?php echo $ID; ?>" value="<?php echo $ID; ?>" name="sec<?php echo $sectionID; ?>" <?php echo $course['selected'] ? 'checked="checked" ' : ($course['registrations'] >= $course['maxRegistrations'] ? 'disabled="disabled"' : ''); ?>> 
					<strong><?php echo $course['courseName']; ?></strong>
				</td>
		<?php endforeach;
		if(count($courseRow) < $this->columns): ?>
				<td colspan="<?php echo $this->columns - count($courseRow); ?>" class="osbit_step2_name">
				</td>
		<?php endif; ?>
			</tr>
			<tr>
		<?php foreach ($courseRow as $ID => $course): ?>
				<td class="osbit_step2_room">
					<?php echo $course['room']; ?>
				</td>
		<?php endforeach;
		if(count($courseRow) < $this->columns): ?>
				<td colspan="<?php echo $this->columns - count($courseRow); ?>" class="osbit_step2_room">
				</td>
		<?php endif; ?>	
			</tr>
			<tr style="border: 0px solid #FFF">
		<?php foreach ($courseRow as $ID => $course): ?>
				<td class="osbit_step2_registrations<?php echo $rows == count($courseSection['rows']) ? '_last' : ''; ?>">
					<?php echo $course['registrations']; ?>/<?php echo $course['maxRegistrations']?>
				</td>
		<?php endforeach;
		if(count($courseRow) < $this->columns): ?>
				<td colspan="<?php echo $this->columns - count($courseRow); ?>" class="osbit_step2_registrations<?php echo $rows == count($courseSection['rows']) ? '_last' : ''; ?>">
				</td>
		<?php endif; ?>
			</tr>
<?php endforeach;
endforeach; ?>
		</table>
	</td>
</tr>