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
 * View to edit a testimonial.
 *
 * @since  1.5
 */
class Xpert_TestimonialsViewTestimonial extends JViewLegacy
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
		$canDo		= JHelperContent::getActions('com_xpert_testimonials', 'category', $this->item->catid);

		JToolbarHelper::title($isNew ? JText::_('COM_XPERT_TESTIMONIALS_MANAGER_TESTIMONIAL_NEW') : JText::_('COM_XPERT_TESTIMONIALS_MANAGER_TESTIMONIAL_EDIT'), 'link testimonials');

		// If not checked out, can save the item.
		if (!$checkedOut && ($canDo->get('core.edit')||(count($user->getAuthorisedCategories('com_xpert_testimonials', 'core.create')))))
		{
			JToolbarHelper::apply('testimonial.apply');
			JToolbarHelper::save('testimonial.save');
		}
		if (!$checkedOut && (count($user->getAuthorisedCategories('com_xpert_testimonials', 'core.create'))))
		{
			JToolbarHelper::save2new('testimonial.save2new');
		}
		// If an existing item, can save to a copy.
		if (!$isNew && (count($user->getAuthorisedCategories('com_xpert_testimonials', 'core.create')) > 0))
		{
			JToolbarHelper::save2copy('testimonial.save2copy');
		}
		if (empty($this->item->id))
		{
			JToolbarHelper::cancel('testimonial.cancel');
		}
		else
		{
			if ($this->state->params->get('save_history', 0) && $user->authorise('core.edit'))
			{
				JToolbarHelper::versions('com_xpert_testimonials.testimonial', $this->item->id);
			}

			JToolbarHelper::cancel('testimonial.cancel', 'JTOOLBAR_CLOSE');
		}

		JToolbarHelper::divider();
		JToolbarHelper::help('JHELP_COMPONENTS_TESTIMONIALS_LINKS_EDIT');
	}
}
