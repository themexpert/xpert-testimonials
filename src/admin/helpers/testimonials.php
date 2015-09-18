<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_xpert_testimonials
 *
 * @copyright   Copyright (C) 2005 - 2015 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

/**
 * Xpert Testimonials helper.
 *
 * @since  1.6
 */
class Xpert_TestimonialsTableHelper extends JHelperContent
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
	public static function addSubmenu($vName = 'testimonials')
	{
		JHtmlSidebar::addEntry(
			JText::_('COM_XPERT_TEXTIMONIALS_SUBMENU_TESTIMONIALS'),
			'index.php?option=com_xpert_testimonials&view=testimonials',
			$vName == 'testimonials'
		);

		JHtmlSidebar::addEntry(
			JText::_('COM_XPERT_TEXTIMONIALS_SUBMENU_CATEGORIES'),
			'index.php?option=com_categories&extension=com_xpert_testimonials',
			$vName == 'categories'
		);
	}
}
