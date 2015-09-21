<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_xmonials
 *
 * @copyright   Copyright (C) 2005 - 2015 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

JTable::addIncludePath(JPATH_COMPONENT_ADMINISTRATOR . '/tables');

/**
 * Xpert Xmonials Component Model for a Xmonial record
 *
 * @since  1.5
 */
class XmonialsModelXmonial extends JModelItem
{
	/**
	 * Model context string.
	 *
	 * @var  string
	 */
	protected $_context = 'com_xmonials.xmonial';

	/**
	 * Method to auto-populate the model state.
	 *
	 * Note. Calling getState in this method will result in recursion.
	 *
	 * @since   1.6
	 */
	protected function populateState()
	{
		$app = JFactory::getApplication();
		$params	= $app->getParams();

		// Load the object state.
		$id	= $app->input->getInt('id');
		$this->setState('xmonial.id', $id);

		// Load the parameters.
		$this->setState('params', $params);
	}

	/**
	 * Method to get an object.
	 *
	 * @param   integer	The id of the object to get.
	 *
	 * @return  mixed  Object on success, false on failure.
	 */
	public function getItem($id = null)
	{
		if ($this->_item === null)
		{
			$this->_item = false;

			if (empty($id))
			{
				$id = $this->getState('xmonial.id');
			}

			// Get a level row instance.
			$table = JTable::getInstance('Xmonial', 'Xmonials');

			// Attempt to load the row.
			if ($table->load($id))
			{
				// Check published state.
				if ($published = $this->getState('filter.published'))
				{
					if ($table->state != $published)
					{
						return $this->_item;
					}
				}

				// Convert the JTable to a clean JObject.
				$properties = $table->getProperties(1);
				$this->_item = JArrayHelper::toObject($properties, 'JObject');
			}
			elseif ($error = $table->getError())
			{
				$this->setError($error);
			}
		}

		return $this->_item;
	}

	/**
	 * Returns a reference to the a Table object, always creating it.
	 *
	 * @param	type	The table type to instantiate
	 * @param	string	A prefix for the table class name. Optional.
	 * @param	array	Configuration array for model. Optional.
	 *
	 * @return	JTable	A database object
	 *
	 * @since	1.6
	 */
	public function getTable($type = 'Xmonial', $prefix = 'Xmonials', $config = array())
	{
		return JTable::getInstance($type, $prefix, $config);
	}

	/**
	 * Method to increment the hit counter for the xmonial
	 *
	 * @param   integer  $id  Optional ID of the xmonial.
	 *
	 * @return  boolean  True on success
	 */
	public function hit($id = null)
	{
		if (empty($id))
		{
			$id = $this->getState('xmonial.id');
		}

		$xmonial = $this->getTable('Xmonial', 'Xmonials');

		return $xmonial->hit($id);
	}

	/**
	 * Method to save the form data.
	 *
	 * @param   array  $data  The form data.
	 *
	 * @return  boolean  True on success.
	 *
	 * @since	3.1
	 */
	public function save($data)
	{
		print_r($data);die;
		$app = JFactory::getApplication();

		// Alter the name for save as copy
		if ($app->input->get('task') == 'save2copy')
		{
			list($name, $alias) = $this->generateNewTitle($data['catid'], $data['alias'], $data['name']);
			$data['name']	= $name;
			$data['alias']	= $alias;
			$data['published']	= 0;
		}

		$images = array(
			'image_intro'	=> $data['image_intro'],
			'image_full'	=> $data['image_full']
		);

		$registry = new Registry;
		$registry->loadArray($images);
		$data['images'] = (string) $registry;

		if (isset($data['attribs']) && is_array($data['attribs']))
		{
			$registry = new Registry;
			$registry->loadArray($data['attribs']);
			$data['attribs'] = (string) $registry;
		}

		if(parent::save($data))
		{
			//hook the files here
			$recordId = $this->getState('product.id');

			if (isset($data['file']) && is_array($data['file']))
      {
          $files = $data['file'];
          foreach($files as $key => $file){
              $filesTable = $this->getTable('Files');
              $filesTable->id = $file['id'];
              $filesTable->product_id = $recordId;
              $filesTable->name = ($file['name'] ? $file['name'] : JText::sprintf('COM_DIGICOM_PRODUCT_FILE_NAME',$key));
              $filesTable->url = $file['url'];
              $filesTable->ordering = $file['ordering'];
              $filesTable->store();
          }
          if (isset($data['files_remove_id']) && !empty($data['files_remove_id'])){
              $filesTable = JTable::getInstance('Files', 'Table');
              $filesTable->removeUnmatch($data['files_remove_id'],$recordId);
          }
      }

      // hook bundle item
			if (isset($data['bundle_category']) && is_array($data['bundle_category']))
      {
          $bTable = $this->getTable('Bundle');
          $bTable->removeUnmatchBundle($data['bundle_category'],$recordId);

          $bundleTable = $this->getTable('Bundle');
          $bundle_category = $data['bundle_category'];
          $bundleTable->bundle_type = 'category';

          foreach($bundle_category as $bundle){
              $bundleTable->id = '';
              $bundleTable->product_id = $recordId;
              $bundleTable->bundle_id = $bundle;
              $bundleTable->store();
          }
      }

      if (isset($data['bundle_product']) && is_array($data['bundle_product']))
      {

          $bTable = $this->getTable('Bundle');
          $bTable->removeUnmatchBundle($data['bundle_product'],$recordId,'product');

          $bundleTable = $this->getTable('Bundle');
          $bundle_product = $data['bundle_product'];
          $bundleTable->bundle_type = 'product';
          foreach($bundle_product as $bundle){
              $bundleTable->id = '';
              $bundleTable->product_id = $recordId;
              $bundleTable->bundle_id = $bundle;
              $bundleTable->store();
          }

      }

			$assoc = JLanguageAssociations::isEnabled();
			if ($assoc)
			{
				$id = (int) $this->getState($this->getName() . '.id');
				$item = $this->getItem($id);

				// Adding self to the association
				$associations = $data['associations'];

				foreach ($associations as $tag => $id)
				{
					if (empty($id))
					{
						unset($associations[$tag]);
					}
				}

				// Detecting all item menus
				$all_language = $item->language == '*';

				if ($all_language && !empty($associations))
				{
					JError::raiseNotice(403, JText::_('COM_DIGICOM_ERROR_ALL_LANGUAGE_ASSOCIATED'));
				}

				$associations[$item->language] = $item->id;

				// Deleting old association for these items
				$db = JFactory::getDbo();
				$query = $db->getQuery(true)
					->delete('#__associations')
					->where('context=' . $db->quote('com_digicom.product'))
					->where('id IN (' . implode(',', $associations) . ')');
				$db->setQuery($query);
				$db->execute();

				if ($error = $db->getErrorMsg())
				{
					$this->setError($error);

					return false;
				}

				if (!$all_language && count($associations))
				{
					// Adding new association for these items
					$key = md5(json_encode($associations));
					$query->clear()
						->insert('#__associations');

					foreach ($associations as $id)
					{
						$query->values($id . ',' . $db->quote('com_digicom.product') . ',' . $db->quote($key));
					}

					$db->setQuery($query);
					$db->execute();

					if ($error = $db->getErrorMsg())
					{
						$this->setError($error);
						return false;
					}
				}
			}

      return true;

		}

		return false;

	}
}
