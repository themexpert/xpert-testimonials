<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_xmonials
 *
 * @copyright   Copyright (C) 2005 - 2015 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

/**
 * Xpert Xmonials helper.
 *
 * @since  1.6
 */
class XmonialsHelper extends JHelperContent
{
	/**
	 * Configure the Linkbar.
	 *
	 * @param   string  $vName  The name of the active view.
	 *
	 * @return  void
	 *
	 * @since   1.6
	 */
	public static function addSubmenu($vName = 'xmonials')
	{
		JHtmlSidebar::addEntry(
			JText::_('COM_XMONIALS_SUBMENU_XMONIALS'),
			'index.php?option=com_xmonials&view=xmonials',
			$vName == 'xmonials'
		);

		JHtmlSidebar::addEntry(
			JText::_('COM_XMONIALS_SUBMENU_CATEGORIES'),
			'index.php?option=com_categories&extension=com_xmonials',
			$vName == 'categories'
		);
	}
}
