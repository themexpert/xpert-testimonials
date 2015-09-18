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
 * View class for a list of testimonials.
 *
 * @since  1.5
 */
class Xpert_TestimonialsTableViewXpert Testimonials extends JViewLegacy
{
	protected $items;

	protected $pagination;

	protected $state;

	/**
	 * Display the view
	 *
	 * @return  void
	 */
	public function display($tpl = null)
	{
		$this->state      = $this->get('State');
		$this->items      = $this->get('Items');
		$this->pagination = $this->get('Pagination');

		Xpert_TestimonialsTableHelper::addSubmenu('testimonials');

		// Check for errors.
		if (count($errors = $this->get('Errors')))
		{
			JError::raiseError(500, implode("\n", $errors));
			return false;
		}

		$this->addToolbar();
		$this->sidebar = JHtmlSidebar::render();
		parent::display($tpl);
	}

	/**
	 * Add the page title and toolbar.
	 *
	 * @since   1.6
	 */
	protected function addToolbar()
	{
		require_once JPATH_COMPONENT . '/helpers/testimonials.php';

		$state = $this->get('State');
		$canDo = JHelperContent::getActions('com_xpert_testimonials', 'category', $state->get('filter.category_id'));
		$user  = JFactory::getUser();

		// Get the toolbar object instance
		$bar = JToolBar::getInstance('toolbar');

		JToolbarHelper::title(JText::_('COM_XPERT_TEXTIMONIALS_MANAGER_TESTIMONIALS'), 'link testimonials');

		if (count($user->getAuthorisedCategories('com_xpert_testimonials', 'core.create')) > 0)
		{
			JToolbarHelper::addNew('testimonial.add');
		}

		if ($canDo->get('core.edit'))
		{
			JToolbarHelper::editList('testimonial.edit');
		}

		if ($canDo->get('core.edit.state'))
		{
			JToolbarHelper::publish('testimonials.publish', 'JTOOLBAR_PUBLISH', true);
			JToolbarHelper::unpublish('testimonials.unpublish', 'JTOOLBAR_UNPUBLISH', true);

			JToolbarHelper::archiveList('testimonials.archive');
			JToolbarHelper::checkin('testimonials.checkin');
		}

		if ($state->get('filter.state') == -2 && $canDo->get('core.delete'))
		{
			JToolbarHelper::deleteList('', 'testimonials.delete', 'JTOOLBAR_EMPTY_TRASH');
		}
		elseif ($canDo->get('core.edit.state'))
		{
			JToolbarHelper::trash('testimonials.trash');
		}

		// Add a batch button
		if ($user->authorise('core.create', 'com_xpert_testimonials') && $user->authorise('core.edit', 'com_xpert_testimonials')
			&& $user->authorise('core.edit.state', 'com_xpert_testimonials'))
		{
			JHtml::_('bootstrap.modal', 'collapseModal');
			$title = JText::_('JTOOLBAR_BATCH');

			// Instantiate a new JLayoutFile instance and render the batch button
			$layout = new JLayoutFile('joomla.toolbar.batch');

			$dhtml = $layout->render(array('title' => $title));
			$bar->appendButton('Custom', $dhtml, 'batch');
		}

		if ($user->authorise('core.admin', 'com_xpert_testimonials') || $user->authorise('core.options', 'com_xpert_testimonials'))
		{
			JToolbarHelper::preferences('com_xpert_testimonials');
		}

		JToolbarHelper::help('JHELP_COMPONENTS_TESTIMONIALS_LINKS');

		JHtmlSidebar::setAction('index.php?option=com_xpert_testimonials&view=testimonials');

		JHtmlSidebar::addFilter(
			JText::_('JOPTION_SELECT_PUBLISHED'),
			'filter_state',
			JHtml::_('select.options', JHtml::_('jgrid.publishedOptions'), 'value', 'text', $this->state->get('filter.state'), true)
		);

		JHtmlSidebar::addFilter(
			JText::_('JOPTION_SELECT_CATEGORY'),
			'filter_category_id',
			JHtml::_('select.options', JHtml::_('category.options', 'com_xpert_testimonials'), 'value', 'text', $this->state->get('filter.category_id'))
		);

		JHtmlSidebar::addFilter(
			JText::_('JOPTION_SELECT_ACCESS'),
			'filter_access',
			JHtml::_('select.options', JHtml::_('access.assetgroups'), 'value', 'text', $this->state->get('filter.access'))
		);

		JHtmlSidebar::addFilter(
			JText::_('JOPTION_SELECT_LANGUAGE'),
			'filter_language',
			JHtml::_('select.options', JHtml::_('contentlanguage.existing', true, true), 'value', 'text', $this->state->get('filter.language'))
		);

		JHtmlSidebar::addFilter(
			JText::_('JOPTION_SELECT_TAG'),
			'filter_tag',
			JHtml::_('select.options', JHtml::_('tag.options', true, true), 'value', 'text', $this->state->get('filter.tag'))
		);

	}

	/**
	 * Returns an array of fields the table can be sorted by
	 *
	 * @return  array  Array containing the field name to sort by as the key and display text as value
	 *
	 * @since   3.0
	 */
	protected function getSortFields()
	{
		return array(
			'a.ordering' => JText::_('JGRID_HEADING_ORDERING'),
			'a.state' => JText::_('JSTATUS'),
			'a.title' => JText::_('JGLOBAL_TITLE'),
			'a.access' => JText::_('JGRID_HEADING_ACCESS'),
			'a.hits' => JText::_('JGLOBAL_HITS'),
			'a.language' => JText::_('JGRID_HEADING_LANGUAGE'),
			'a.id' => JText::_('JGRID_HEADING_ID')
		);
	}
}
