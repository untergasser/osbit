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
<?php foreach($this->items as $i => $item): ?>
	<tr class="row<?php echo $i % 2; ?>">
		<td>
			<?php echo $item->ID; ?>
		</td>
		<td>
			<?php echo JHtml::_('grid.ID', $i, $item->ID); ?>
		</td>
		<td>
			<a href="<?php echo JRoute::_('index.php?option=com_osbit&task=section.edit&ID=' . $item->ID); ?>">
				<?php echo $item->begin; ?>
			</a>
		</td>
		<td>
			<a href="<?php echo JRoute::_('index.php?option=com_osbit&task=section.edit&ID=' . $item->ID); ?>">
				<?php echo $item->end; ?>
			</a>
		</td>
	</tr>
<?php endforeach; ?>