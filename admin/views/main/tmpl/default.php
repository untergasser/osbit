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
<form action="<?php echo JRoute::_('index.php?option=com_osbit'); ?>" method="post" name="adminForm" id="adminForm">
	<table width="90%" border="0" cellpadding="2" cellspacing="2" class="adminform">
		<tr>
			<td width="75%" valign="top">
				<table width="100%">
					<tr>
						<td>
							<div id="cpanel">
								<div style="float:left;">
									<div class="icon">
										<a href="index.php?option=com_osbit&view=users">
											<?php echo JHtml::image("../../media/com_osbit/images/icon-48-users.png", JText::_("COM_OSBIT_SUBMENU_USERS"), null, true, false);?>
											<span><?php echo JText::_("COM_OSBIT_SUBMENU_USERS"); ?></span>
										</a>
									</div>
								</div>
								<div style="float:left;">
									<div class="icon">
										<a href="index.php?option=com_osbit&view=courses">
											<?php echo JHtml::image("../../media/com_osbit/images/icon-48-courses.png", JText::_("COM_OSBIT_SUBMENU_COURSES"), null, true, false);?>
											<span><?php echo JText::_("COM_OSBIT_SUBMENU_COURSES"); ?></span>
										</a>
									</div>
								</div>
								<div style="float:left;">
									<div class="icon">
										<a href="index.php?option=com_osbit&view=sections">
											<?php echo JHtml::image("../../media/com_osbit/images/icon-48-sections.png", JText::_("COM_OSBIT_SUBMENU_SECTIONS"), null, true, false);?>
											<span><?php echo JText::_("COM_OSBIT_SUBMENU_SECTIONS"); ?></span>
										</a>
									</div>
								</div>
								<div style="float:left;">
									<div class="icon">
										<a href="index.php?option=com_osbit&view=registrations">
											<?php echo JHtml::image("../../media/com_osbit/images/icon-48-registrations.png", JText::_("COM_OSBIT_SUBMENU_REGISTRATIONS"), null, true, false);?>
											<span><?php echo JText::_("COM_OSBIT_SUBMENU_REGISTRATIONS"); ?></span>
										</a>
									</div>
								</div>
							</div>
						</td>
					</tr>
					<tr>
						<td>
							<table width="100%" cellspacing="0" style="border-collapse: separate; border: 1px solid #999;">
<?php 
if ($this->courses):
	foreach ($this->courses as $sectionID => $courseSection):
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
										<strong><?php echo $course['courseName']; ?></strong>
									</td>
			<?php endforeach;
			if(count($courseRow) < 3): ?>
									<td colspan="<?php echo 3 - count($courseRow); ?>" class="osbit_step2_name">
									</td>
			<?php endif; ?>
								</tr>
								<tr>
			<?php foreach ($courseRow as $ID => $course): ?>
									<td class="osbit_step2_room">
										<?php echo $course['room']; ?>
									</td>
			<?php endforeach;
			if(count($courseRow) < 3): ?>
									<td colspan="<?php echo 3 - count($courseRow); ?>" class="osbit_step2_room">
									</td>
			<?php endif; ?>	
								</tr>
								<tr style="border: 0px solid #FFFs;">
			<?php foreach ($courseRow as $ID => $course): ?>
									<td class="osbit_step2_registrations<?php echo $rows == count($courseSection['rows']) ? '_last' : ''; ?>" style="background-color: <?php echo '#FF' . (255 - $course['registrations'] / $course['maxRegistrations'] * 250 < 16 ? '0' : '') . dechex(255 - $course['registrations'] / $course['maxRegistrations'] * 250) . (255 - $course['registrations'] / $course['maxRegistrations'] * 250 < 16 ? '0' : '') .  dechex(255 - $course['registrations'] / $course['maxRegistrations'] * 250); ?>;">
										<?php echo $course['registrations']; ?>/<?php echo $course['maxRegistrations']?>
									</td>
			<?php endforeach;
			if(count($courseRow) < 3): ?>
									<td colspan="<?php echo 3 - count($courseRow); ?>" class="osbit_step2_registrations<?php echo $rows == count($courseSection['rows']) ? '_last' : ''; ?>">
									</td>
			<?php endif; ?>
								</tr>
	<?php endforeach;
	endforeach; 
endif; ?>
							</table>
						</td>
					</tr>
				</table>
			</td>
			<td width="20%" style="vertical-align: top;">
				<strong>OSBIT</strong><br />
				<?php echo JText::_('COM_OSBIT_MAIN_COPYRIGHT'); ?><br /><br />
				<small>Copyright: (c) 2010 - 2015 Mathias Gebhardt and Andreas Untergasser<br />
				E-Mail: <?php echo JHTML::_('email.cloak', 'a.untergasser@gy-mi.de'); ?><br />
				Lizenz: GNU/GPL</small>
			</td>
		</tr>
	</table>
	<input type="hidden" name="task" value="" />
	<input type="hidden" name="act" value="" />
</form>