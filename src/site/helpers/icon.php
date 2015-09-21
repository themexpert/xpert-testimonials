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
 * Xmonial Component HTML Helper.
 *
 * @since  1.5
 */
class JHtmlIcon
{
	/**
	 * Create a link to create a new xmonial
	 *
	 * @param   mixed  $xmonial  Unused
	 * @param   mixed  $params   Unused
	 *
	 * @return  string
	 */
	public static function create($xmonial, $params)
	{
		JHtml::_('bootstrap.tooltip');

		$uri = JUri::getInstance();
		$url = JRoute::_(XmonialsRoute::getFormRoute(0, base64_encode($uri)));
		$text = JHtml::_('image', 'system/new.png', JText::_('JNEW'), null, true);
		$button = JHtml::_('link', $url, $text);

		return '<span class="hasTooltip" title="' . JHtml::tooltipText('COM_XMONIALS_FORM_CREATE_XMONIAL') . '">' . $button . '</span>';
	}

	/**
	 * Create a link to edit an existing xmonial
	 *
	 * @param   object                     $xmonial  Xmonial data
	 * @param   \Joomla\Registry\Registry  $params   Item params
	 * @param   array                      $attribs  Unused
	 *
	 * @return  string
	 */
	public static function edit($xmonial, $params, $attribs = array())
	{
		$uri = JUri::getInstance();

		if ($params && $params->get('popup'))
		{
			return;
		}

		if ($xmonial->state < 0)
		{
			return;
		}

		JHtml::_('bootstrap.tooltip');

		$url	= XmonialsRoute::getFormRoute($xmonial->id, base64_encode($uri));
		$icon	= $xmonial->state ? 'edit.png' : 'edit_unpublished.png';
		$text	= JHtml::_('image', 'system/'.$icon, JText::_('JGLOBAL_EDIT'), null, true);

		if ($xmonial->state == 0)
		{
			$overlib = JText::_('JUNPUBLISHED');
		}
		else
		{
			$overlib = JText::_('JPUBLISHED');
		}

		$date = JHtml::_('date', $xmonial->created);
		$author = $xmonial->created_by_alias ? $xmonial->created_by_alias : $xmonial->author;

		$overlib .= '&lt;br /&gt;';
		$overlib .= $date;
		$overlib .= '&lt;br /&gt;';
		$overlib .= htmlspecialchars($author, ENT_COMPAT, 'UTF-8');

		$button = JHtml::_('link', JRoute::_($url), $text);

		return '<span class="hasTooltip" title="' . JHtml::tooltipText('COM_XMONIALS_EDIT') . ' :: ' . $overlib . '">' . $button . '</span>';
	}
}
