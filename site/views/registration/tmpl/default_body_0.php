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

function rG0Change()
{
		document.comOSBITRegistration.password.disabled=
			document.comOSBITRegistration.userName.disabled=
				!document.comOSBITRegistration.login.checked;

		document.comOSBITRegistration.firstName.disabled=
			document.comOSBITRegistration.lastName.disabled=
			document.comOSBITRegistration.email.disabled=
			document.comOSBITRegistration.school.disabled=
			document.comOSBITRegistration.class.disabled=
				document.comOSBITRegistration.login.checked;
}
//-->
</script>
<tr>
	<td class="osbit_stepX_td osbit_step0_info">
		<span>
			<?php echo $this->params['personalDataInfo']; ?>
		</span>
	</td>
</tr>
<tr>
	<td class="osbit_stepX_td">
		<table class="osbit_table" cellspacing="0">
			<tr>
				<td colspan="2" class="osbit_step0_radio">
					<input type="radio" name="RG0" id="login" value="login" onchange="rG0Change()" <?php echo ($rG0 = JRequest::getString('RG0')) == 'login' ? 'checked="checked"' : ''; ?> /> <?php echo JText::_( 'COM_OSBIT_REGISTRATION_STEP_PERSONAL_DATA_INFO'); ?>
				</td>
			</tr>
			<tr>
				<td class="osbit_step0_label">
					<?php echo JText::_( 'COM_OSBIT_REGISTRATION_STEP_PERSONAL_DATA_USERNAME'); ?>:
				</td>
				<td class="osbit_step0_field">
					<input type="text" name="userName" value="<?php echo JRequest::getString('userName'); ?>" maxlength="20" disabled="disabled" />
				</td>
			</tr>
			<tr>
				<td class="osbit_step0_label">
					<?php echo JText::_( 'COM_OSBIT_REGISTRATION_STEP_PERSONAL_DATA_PASSWORD'); ?>:
				</td>
				<td class="osbit_step0_field">
					<input type="password" name="password" maxlength="20" disabled="disabled" />
				</td>
			</tr>
		</table>
	</td>
</tr>
<tr>
	<td class="osbit_stepX_td">
		<table class="osbit_table" cellspacing="0">
			<tr>
				<td colspan="2" class="osbit_step0_radio">
					<input type="radio" name="RG0" id="register" value="register" onchange="rG0Change()" <?php echo $rG0 == 'register' ? 'checked="checked"' : ''; ?> /> <?php echo JText::_( 'COM_OSBIT_REGISTRATION_STEP_PERSONAL_DATA_INFO_2'); ?>
				</td>
			</tr>
			<tr>
				<td class="osbit_step0_label">
					<?php echo JText::_( 'COM_OSBIT_REGISTRATION_STEP_PERSONAL_DATA_FIRST_NAME'); ?>:
				</td>
				<td class="osbit_step0_field">
					<input type="text" name="firstName" value="<?php echo JRequest::getString('firstName'); ?>" maxlength="20" <?php echo $rG0 == 'register' ? '' : 'disabled="disabled"'; ?> />
				</td>
			</tr>
			<tr>
				<td class="osbit_step0_label">
					<?php echo JText::_( 'COM_OSBIT_REGISTRATION_STEP_PERSONAL_DATA_LAST_NAME'); ?>:
				</td>
				<td class="osbit_step0_field">
					<input type="text" name="lastName" value="<?php echo JRequest::getString('lastName'); ?>" maxlength="20" <?php echo $rG0 == 'register' ? '' : 'disabled="disabled"'; ?> />
				</td>
			</tr>
			<tr>
				<td class="osbit_step0_label">
					<?php echo JText::_( 'COM_OSBIT_REGISTRATION_STEP_PERSONAL_DATA_EMAIL'); ?>:
				</td>
				<td class="osbit_step0_field">
					<input type="text" name="email" value="<?php echo JRequest::getString('email'); ?>" maxlength="50" <?php echo $rG0 == 'register' ? '' : 'disabled="disabled"'; ?> />
				</td>
			</tr>
			<tr>
				<td class="osbit_step0_label">
					<?php echo JText::_( 'COM_OSBIT_REGISTRATION_STEP_PERSONAL_DATA_SCHOOL'); ?>:
				</td>
				<td class="osbit_step0_field">
					<select name="school" <?php echo $rG0 == 'register' ? '' : 'disabled="disabled"'; ?>>
						<?php echo $this->get('SchoolList'); ?>
					</select>
				</td>
			</tr>
			<tr>
				<td class="osbit_step0_label">
					<?php echo JText::_( 'COM_OSBIT_REGISTRATION_STEP_PERSONAL_DATA_CLASS'); ?>:
				</td>
				<td class="osbit_step0_field">
					<select name="class" <?php echo $rG0 == 'register' ? '' : 'disabled="disabled"'; ?>>
						<?php echo $this->get('ClassList'); ?>
					</select>
				</td>
			</tr>
		</table>
	</td>
</tr>