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
 * Testimonial Component HTML Helper.
 *
 * @since  1.5
 */
class JHtmlIcon
{
	/**
	 * Create a link to create a new testimonial
	 *
	 * @param   mixed  $testimonial  Unused
	 * @param   mixed  $params   Unused
	 *
	 * @return  string
	 */
	public static function create($testimonial, $params)
	{
		JHtml::_('bootstrap.tooltip');

		$uri = JUri::getInstance();
		$url = JRoute::_(Xpert_TestimonialsRoute::getFormRoute(0, base64_encode($uri)));
		$text = JHtml::_('image', 'system/new.png', JText::_('JNEW'), null, true);
		$button = JHtml::_('link', $url, $text);

		return '<span class="hasTooltip" title="' . JHtml::tooltipText('COM_XPERT_TESTIMONIALS_FORM_CREATE_TESTIMONIAL') . '">' . $button . '</span>';
	}

	/**
	 * Create a link to edit an existing testimonial
	 *
	 * @param   object                     $testimonial  Testimonial data
	 * @param   \Joomla\Registry\Registry  $params   Item params
	 * @param   array                      $attribs  Unused
	 *
	 * @return  string
	 */
	public static function edit($testimonial, $params, $attribs = array())
	{
		$uri = JUri::getInstance();

		if ($params && $params->get('popup'))
		{
			return;
		}

		if ($testimonial->state < 0)
		{
			return;
		}

		JHtml::_('bootstrap.tooltip');

		$url	= Xpert_TestimonialsRoute::getFormRoute($testimonial->id, base64_encode($uri));
		$icon	= $testimonial->state ? 'edit.png' : 'edit_unpublished.png';
		$text	= JHtml::_('image', 'system/'.$icon, JText::_('JGLOBAL_EDIT'), null, true);

		if ($testimonial->state == 0)
		{
			$overlib = JText::_('JUNPUBLISHED');
		}
		else
		{
			$overlib = JText::_('JPUBLISHED');
		}

		$date = JHtml::_('date', $testimonial->created);
		$author = $testimonial->created_by_alias ? $testimonial->created_by_alias : $testimonial->author;

		$overlib .= '&lt;br /&gt;';
		$overlib .= $date;
		$overlib .= '&lt;br /&gt;';
		$overlib .= htmlspecialchars($author, ENT_COMPAT, 'UTF-8');

		$button = JHtml::_('link', JRoute::_($url), $text);

		return '<span class="hasTooltip" title="' . JHtml::tooltipText('COM_XPERT_TESTIMONIALS_EDIT') . ' :: ' . $overlib . '">' . $button . '</span>';
	}
}
