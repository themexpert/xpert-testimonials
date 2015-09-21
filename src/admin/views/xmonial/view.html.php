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
 * View to edit a xmonial.
 *
 * @since  1.5
 */
class XmonialsViewXmonial extends JViewLegacy
{
	protected $state;

	protected $item;

	protected $form;

	/**
	 * Display the view
	 */
	public function display($tpl = null)
	{
		$this->state	= $this->get('State');
		$this->item		= $this->get('Item');
		$this->form		= $this->get('Form');

		// Check for errors.
		if (count($errors = $this->get('Errors')))
		{
			JError::raiseError(500, implode("\n", $errors));
			return false;
		}

		$this->addToolbar();
		parent::display($tpl);
	}

	/**
	 * Add the page title and toolbar.
	 *
	 * @since   1.6
	 */
	protected function addToolbar()
	{
		JFactory::getApplication()->input->set('hidemainmenu', true);

		$user		= JFactory::getUser();
		$isNew		= ($this->item->id == 0);
		$checkedOut	= !($this->item->checked_out == 0 || $this->item->checked_out == $user->get('id'));

		// Since we don't track these assets at the item level, use the category id.
		$canDo		= JHelperContent::getActions('com_xmonials', 'category', $this->item->catid);

		JToolbarHelper::title($isNew ? JText::_('COM_XMONIALS_MANAGER_XMONIAL_NEW') : JText::_('COM_XMONIALS_MANAGER_XMONIAL_EDIT'), 'link xmonials');

		// If not checked out, can save the item.
		if (!$checkedOut && ($canDo->get('core.edit')||(count($user->getAuthorisedCategories('com_xmonials', 'core.create')))))
		{
			JToolbarHelper::apply('xmonial.apply');
			JToolbarHelper::save('xmonial.save');
		}
		if (!$checkedOut && (count($user->getAuthorisedCategories('com_xmonials', 'core.create'))))
		{
			JToolbarHelper::save2new('xmonial.save2new');
		}
		// If an existing item, can save to a copy.
		if (!$isNew && (count($user->getAuthorisedCategories('com_xmonials', 'core.create')) > 0))
		{
			JToolbarHelper::save2copy('xmonial.save2copy');
		}
		if (empty($this->item->id))
		{
			JToolbarHelper::cancel('xmonial.cancel');
		}
		else
		{
			if ($this->state->params->get('save_history', 0) && $user->authorise('core.edit'))
			{
				JToolbarHelper::versions('com_xmonials.xmonial', $this->item->id);
			}

			JToolbarHelper::cancel('xmonial.cancel', 'JTOOLBAR_CLOSE');
		}

		JToolbarHelper::divider();
		JToolbarHelper::help('JHELP_COMPONENTS_XMONIALS_LINKS_EDIT');
	}
}
