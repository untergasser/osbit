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
        <th width="5">
                <?php echo JText::_('COM_OSBIT_HEADING_ID'); ?>
        </th>
        <th width="20">
                <input type="checkbox" name="toggle" value="" onclick="checkAll(<?php echo count($this->items); ?>);" />
        </th>                     
        <th>
                <?php echo JText::_('COM_OSBIT_COURSES_HEADING_NAME'); ?>
        </th>
        <th>
                <?php echo JText::_('COM_OSBIT_COURSES_HEADING_DESCRIPTION'); ?>
        </th>
        <th>
                <?php echo JText::_('COM_OSBIT_COURSES_HEADING_LECTOR_1'); ?>
        </th>
        <th>
                <?php echo JText::_('COM_OSBIT_COURSES_HEADING_LECTOR_1_FIRM'); ?>
        </th>
        <th>
                <?php echo JText::_('COM_OSBIT_COURSES_HEADING_LECTOR_2'); ?>
        </th>
        <th>
                <?php echo JText::_('COM_OSBIT_COURSES_HEADING_LECTOR_2_FIRM'); ?>
        </th>
        <th>
                <?php echo JText::_('COM_OSBIT_COURSES_HEADING_MAX_REGISTRATIONS'); ?>
        </th>
</tr>

