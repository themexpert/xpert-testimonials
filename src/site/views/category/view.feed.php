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
 * @since  1.0
 */
class Xpert_TestimonialsViewCategory extends JViewCategoryfeed
{
	/**
	 * @var    string  The name of the view to link individual items to
	 * @since  3.2
	 */
	protected $viewName = 'testimonial';
}
