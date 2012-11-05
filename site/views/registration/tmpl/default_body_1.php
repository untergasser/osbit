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
<?php
// TODO - check $courseInfos array here
foreach ($this->courseInfos as $info): ?>
			<tr>
				<td colspan="3" class="osbit_step1_name">
					<strong><?php echo $info['courseName'] ?></strong>
				</td>
			</tr>
			<tr>
				<td class="osbit_step1_desc_tab">
					&nbsp;
				</td>
				<td class="osbit_step1_desc" colspan="2">
					<?php echo $info['courseInfo']?>
				</td>
			</tr>
			<tr>
				<td class="osbit_step1_firms_line<?php echo $info['lector2'] ? '1' : '2'; ?>_tab">
					&nbsp;
				</td>
				<td class="osbit_step1_firms_line<?php echo $info['lector2'] ? '1' : '2'; ?>_lector">
					<?php echo $info['lector1']; ?>
				</td>
				<td class="osbit_step1_firms_line<?php echo $info['lector2'] ? '1' : '2'; ?>_firm">
					<?php echo $info['lector1firm']; ?>
				</td>
			</tr>
	<?php if($info['lector2']): ?>
			<tr>
				<td class="osbit_step1_firms_line2_tab">
					&nbsp;
				</td>
				<td class="osbit_step1_firms_line2_lector">
					<?php echo $info['lector2']; ?>
				</td>
				<td class="osbit_step1_firms_line2_firm">
					<?php echo $info['lector2firm']; ?>
				</td>
			</tr>
	<?php endif;
endforeach; ?>
		</table>
	</td>
</tr>