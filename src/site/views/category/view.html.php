<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_xpert_testimonials
 *
 * @copyright   Copyright (C) 2005 - 2015 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

/**
 * HTML View class for the Testimonials component
 *
 * @since  1.5
 */
class Xpert_TestimonialsTableViewCategory extends JViewCategory
{
	/**
	 * Execute and display a template script.
	 *
	 * @param   string  $tpl  The name of the template file to parse; automatically searches through the template paths.
	 *
	 * @return  mixed  A string if successful, otherwise a Error object.
	 */
	public function display($tpl = null)
	{
		parent::commonCategoryDisplay();

		// Prepare the data.
		// Compute the testimonial slug & link url.
		foreach ($this->items as $item)
		{
			$item->slug = $item->alias ? ($item->id . ':' . $item->alias) : $item->id;

			if ($item->params->get('count_clicks', $this->params->get('count_clicks')) == 1)
			{
				$item->link = JRoute::_('index.php?option=com_xpert_testimonials&task=testimonial.go&id=' . $item->id);
			}
			else
			{
				$item->link = $item->url;
			}

			$temp = new JRegistry;
			$temp->loadString($item->params);
			$item->params = clone($this->params);
			$item->params->merge($temp);
		}

		return parent::display($tpl);
	}

	/**
	 * Prepares the document
	 *
	 * @return  void
	 */
	protected function prepareDocument()
	{
		parent::prepareDocument();

		$app		= JFactory::getApplication();
		$menus		= $app->getMenu();
		$pathway	= $app->getPathway();
		$title 		= null;

		// Because the application sets a default page title,
		// we need to get it from the menu item itself
		$menu = $menus->getActive();

		if ($menu)
		{
			$this->params->def('page_heading', $this->params->get('page_title', $menu->title));
		}
		else
		{
			$this->params->def('page_heading', JText::_('COM_XPERT_TEXTIMONIALS_DEFAULT_PAGE_TITLE'));
		}

		$id = (int) @$menu->query['id'];

		if ($menu && ($menu->query['option'] != 'com_xpert_testimonials' || $id != $this->category->id))
		{
			$this->params->set('page_subheading', $this->category->title);
			$path = array(array('title' => $this->category->title, 'link' => ''));
			$category = $this->category->getParent();

			while (($menu->query['option'] != 'com_xpert_testimonials' || $id != $category->id) && $category->id > 1)
			{
				$path[] = array('title' => $category->title, 'link' => Xpert_TestimonialsTableHelperRoute::getCategoryRoute($category->id));
				$category = $category->getParent();
			}

			$path = array_reverse($path);

			foreach ($path as $item)
			{
				$pathway->addItem($item['title'], $item['link']);
			}
		}

		parent::addFeed();
	}
}
