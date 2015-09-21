<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_xmonials
 *
 * @copyright   Copyright (C) 2005 - 2015 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

/**
 * HTML View class for the Xmonials component
 *
 * @since  1.5
 */
class XmonialsViewXmonial extends JViewLegacy
{
	protected $state;

	protected $item;

	public function display($tpl = null)
	{
		// Get some data from the models
		$item		= $this->get('Item');

		if ($this->getLayout() == 'edit')
		{
			$this->_displayEdit($tpl);
			return;
		}

		if ($item->url)
		{
			// redirects to url if matching id found
			JFactory::getApplication()->redirect($item->url);
		}
		else
		{
			//TODO create proper error handling
			JFactory::getApplication()->redirect(JRoute::_('index.php'), JText::_('COM_XMONIALS_ERROR_XMONIAL_NOT_FOUND'), 'notice');
		}
	}
}
