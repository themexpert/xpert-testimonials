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
 * Installation class to perform additional changes during install/uninstall/update
 *
 * @since  3.4
 */
class Com_XmonialsInstallerScript
{
	/**
	 * Function to perform changes during install
	 *
	 * @param   JInstallerAdapterComponent  $parent  The class calling this method
	 *
	 * @return  void
	 *
	 * @since   3.4
	 */
	public function install($parent)
	{
		// Initialize a new category
		/** @type  JTableCategory  $category  */
		$category = JTable::getInstance('Category');

		// Check if the Uncategorised category exists before adding it
		if (!$category->load(array('extension' => 'com_xmonials', 'title' => 'Uncategorised')))
		{
			$category->extension = 'com_xmonials';
			$category->title = 'Uncategorised';
			$category->description = '';
			$category->published = 1;
			$category->access = 1;
			$category->params = '{"category_layout":"","image":""}';
			$category->metadata = '{"author":"","robots":""}';
			$category->language = '*';
			$category->checked_out_time = JFactory::getDbo()->getNullDate();

			// Set the location in the tree
			$category->setLocation(1, 'last-child');

			// Check to make sure our data is valid
			if (!$category->check())
			{
				JFactory::getApplication()->enqueueMessage(JText::sprintf('COM_XMONIALS_ERROR_INSTALL_CATEGORY', $category->getError()));

				return;
			}

			// Now store the category
			if (!$category->store(true))
			{
				JFactory::getApplication()->enqueueMessage(JText::sprintf('COM_XMONIALS_ERROR_INSTALL_CATEGORY', $category->getError()));

				return;
			}

			// Build the path for our category
			$category->rebuildPath($category->id);
		}
	}

	/**
	 * Method to run after the install routine.
	 *
	 * @param   string                      $type    The action being performed
	 * @param   JInstallerAdapterComponent  $parent  The class calling this method
	 *
	 * @return  void
	 *
	 * @since   3.4.1
	 */
	public function postflight($type, $parent)
	{
		// Only execute database changes on MySQL databases
		$dbName = JFactory::getDbo()->name;

		if (strpos($dbName, 'mysql') !== false)
		{
			// Add Missing Table Colums if needed
			$this->addColumnsIfNeeded();

			// Drop the Table Colums if needed
			$this->dropColumnsIfNeeded();
		}

		// Insert missing UCM Records if needed
		$this->insertMissingUcmRecords();
	}

	/**
	 * Method to insert missing records for the UCM tables
	 *
	 * @return  void
	 *
	 * @since   3.4.1
	 */
	private function insertMissingUcmRecords()
	{
		return true;
	}

	/**
	 * Method to drop colums from #__xpert_xmonials if they still there.
	 *
	 * @return  void
	 *
	 * @since   3.4.1
	 */
	private function dropColumnsIfNeeded()
	{
		return true;

	}

	/**
	 * Method to add colums from #__xpert_xmonials if they are missing.
	 *
	 * @return  void
	 *
	 * @since   3.4.1
	 */
	private function addColumnsIfNeeded()
	{
		return true;
	}
}
