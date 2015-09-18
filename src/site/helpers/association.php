<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_xpert_testimonials
 *
 * @copyright   Copyright (C) 2005 - 2015 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

JLoader::register('Xpert_TestimonialsHelper', JPATH_ADMINISTRATOR . '/components/com_xpert_testimonials/helpers/xpert_testimonials.php');
JLoader::register('CategoryHelperAssociation', JPATH_ADMINISTRATOR . '/components/com_categories/helpers/association.php');

/**
 * Xpert Testimonials Component Association Helper
 *
 * @since  3.0
 */
abstract class Xpert_TestimonialsHelperAssociation extends CategoryHelperAssociation
{
	/**
	 * Method to get the associations for a given item
	 *
	 * @param   integer  $id    Id of the item
	 * @param   string   $view  Name of the view
	 *
	 * @return  array   Array of associations for the item
	 *
	 * @since   3.0
	 */
	public static function getAssociations($id = 0, $view = null)
	{
		jimport('helper.route', JPATH_COMPONENT_SITE);

		$jinput = JFactory::getApplication()->input;
		$view = is_null($view) ? $jinput->get('view') : $view;
		$id = empty($id) ? $jinput->getInt('id') : $id;

		if ($view == 'category' || $view == 'categories')
		{
			return self::getCategoryAssociations($id, 'com_xpert_testimonials');
		}

		return array();
	}
}
