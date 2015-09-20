<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_xpert_testimonials
 *
 * @copyright   Copyright (C) 2005 - 2015 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

jimport('joomla.filesystem.file');
jimport('joomla.filesystem.folder');

use Joomla\Utilities\ArrayHelper;
use Joomla\Registry\Registry;

/**
 * Xpert Testimonials class.
 *
 * @since  1.5
 */
class Xpert_TestimonialsControllerTestimonial extends JControllerForm
{
	/**
	 * The uploadedfile
	 *
	 * @var    string
	 * @since  1.6
	 */
	protected $uploadedfile;

	/**
	 * The URL view item variable.
	 *
	 * @var    string
	 * @since  1.6
	 */
	protected $view_item = 'form';

	/**
	 * The URL view list variable.
	 *
	 * @var    string
	 * @since  1.6
	 */
	protected $view_list = 'categories';

	/**
	 * The URL edit variable.
	 *
	 * @var    string
	 * @since  3.2
	 */
	protected $urlVar = 'a.id';

	/**
	 * Method to add a new record.
	 *
	 * @return  boolean  True if the article can be added, false if not.
	 *
	 * @since   1.6
	 */
	public function add()
	{
		if (!parent::add())
		{
			// Redirect to the return page.
			$this->setRedirect($this->getReturnPage());
		}
	}

	/**
	 * Method override to check if you can add a new record.
	 *
	 * @param   array  $data  An array of input data.
	 *
	 * @return  boolean
	 *
	 * @since   1.6
	 */
	protected function allowAdd($data = array())
	{
		$categoryId	= ArrayHelper::getValue($data, 'catid', $this->input->getInt('id'), 'int');
		$allow      = null;

		if ($categoryId)
		{
			// If the category has been passed in the URL check it.
			$allow = JFactory::getUser()->authorise('core.create', $this->option . '.category.' . $categoryId);
		}

		if ($allow !== null)
		{
			return $allow;
		}

		// In the absense of better information, revert to the component permissions.
		return parent::allowAdd($data);
	}

	/**
	 * Method to check if you can add a new record.
	 *
	 * @param   array   $data  An array of input data.
	 * @param   string  $key   The name of the key for the primary key.
	 *
	 * @return  boolean
	 *
	 * @since   1.6
	 */
	protected function allowEdit($data = array(), $key = 'id')
	{
		$recordId   = (int) isset($data[$key]) ? $data[$key] : 0;
		$categoryId = 0;

		if ($recordId)
		{
			$categoryId = (int) $this->getModel()->getItem($recordId)->catid;
		}

		if ($categoryId)
		{
			// The category has been set. Check the category permissions.
			return JFactory::getUser()->authorise('core.edit', $this->option . '.category.' . $categoryId);
		}

		// Since there is no asset tracking, revert to the component permissions.
		return parent::allowEdit($data, $key);
	}

	/**
	 * Method to cancel an edit.
	 *
	 * @param   string  $key  The name of the primary key of the URL variable.
	 *
	 * @return  boolean  True if access level checks pass, false otherwise.
	 *
	 * @since   1.6
	 */
	public function cancel($key = 'w_id')
	{
		$return = parent::cancel($key);

		// Redirect to the return page.
		$this->setRedirect($this->getReturnPage());

		return $return;
	}

	/**
	 * Method to edit an existing record.
	 *
	 * @param   string  $key     The name of the primary key of the URL variable.
	 * @param   string  $urlVar  The name of the URL variable if different from the primary key (sometimes required to avoid router collisions).
	 *
	 * @return  boolean  True if access level check and checkout passes, false otherwise.
	 *
	 * @since   1.6
	 */
	public function edit($key = null, $urlVar = 'w_id')
	{
		return parent::edit($key, $urlVar);
	}

	/**
	 * Method to get a model object, loading it if required.
	 *
	 * @param   string  $name    The model name. Optional.
	 * @param   string  $prefix  The class prefix. Optional.
	 * @param   array   $config  Configuration array for model. Optional.
	 *
	 * @return  object  The model.
	 *
	 * @since   1.5
	 */
	public function getModel($name = 'form', $prefix = '', $config = array('ignore_request' => true))
	{
		return parent::getModel($name, $prefix, $config);
	}

	/**
	 * Gets the URL arguments to append to an item redirect.
	 *
	 * @param   integer  $recordId  The primary key id for the item.
	 * @param   string   $urlVar    The name of the URL variable for the id.
	 *
	 * @return  string  The arguments to append to the redirect URL.
	 *
	 * @since   1.6
	 */
	protected function getRedirectToItemAppend($recordId = null, $urlVar = null)
	{
		$append = parent::getRedirectToItemAppend($recordId, $urlVar);
		$itemId	= $this->input->getInt('Itemid');
		$return	= $this->getReturnPage();

		if ($itemId)
		{
			$append .= '&Itemid=' . $itemId;
		}

		if ($return)
		{
			$append .= '&return=' . base64_encode($return);
		}

		return $append;
	}

	/**
	 * Get the return URL if a "return" variable has been passed in the request
	 *
	 * @return  string  The return URL.
	 *
	 * @since   1.6
	 */
	protected function getReturnPage()
	{
		$return = $this->input->get('return', null, 'base64');

		if (empty($return) || !JUri::isInternal(base64_decode($return)))
		{
			return JUri::base();
		}

		return base64_decode($return);
	}

	/**
	 * Method to save a record.
	 *
	 * @param   string  $key     The name of the primary key of the URL variable.
	 * @param   string  $urlVar  The name of the URL variable if different from the primary key (sometimes required to avoid router collisions).
	 *
	 * @return  boolean  True if successful, false otherwise.
	 *
	 * @since   1.6
	 */
	public function save($key = null, $urlVar = 'w_id')
	{
		$app	= JFactory::getApplication();
		$model	= $this->getModel('Form', 'Xpert_TestimonialsModel');

		// Get the user data.
		$data = $app->input->post->get('jform', array(), 'array');

		// Validate the posted data.
		$form = $model->getForm();

		if (!$form)
		{
			JError::raiseError(500, $model->getError());

			return false;
		}

		// Validate the posted data.
		$data = $model->validate($form, $data);


		// Check for errors.
		if ($data === false)
		{
			// Get the validation messages.
			$errors	= $model->getErrors();

			// Push up to three validation messages out to the user.
			for ($i = 0, $n = count($errors); $i < $n && $i < 3; $i++)
			{
				if ($errors[$i] instanceof Exception)
				{
					$app->enqueueMessage($errors[$i]->getMessage(), 'warning');
				}
				else
				{
					$app->enqueueMessage($errors[$i], 'warning');
				}
			}

			$data = $app->input->post->get('jform', array(), 'array');

			// Save the data in the session.
			$app->setUserState('com_xpert_testimonials.edit.testimonial.data', $data);
			$this->setRedirect(JRoute::_('index.php?option=com_xpert_testimonials&view=form&layout=edit', false));

			return false;

		}

		$files  = $app->input->files->get('jform', '', 'array');
		if (isset($files['images']) && is_array($files['images']))
		{
			$uploadedfile = $this->uploadImage($files['images']);
			if(false != $uploadedfile){
				$registry = new Registry;
				$registry->loadArray($uploadedfile);
				$data['images'] = (string) $registry;
			}
		}
		// print_r($data);die;
		// Attempt to save the data.
		$return	= $model->save($data);
		// $return = false;
		// Check for errors.
		if ($return === false)
		{
			$app->setUserState('com_xpert_testimonials.edit.testimonial.data', $data);
			$this->setRedirect(JRoute::_('index.php?option=com_xpert_testimonials&view=form&layout=edit', false));
			return false;
		}else{
			$app->setUserState('com_xpert_testimonials.edit.testimonial.data', '');
			$this->setRedirect($this->getReturnPage(),JText::_('COM_XPERT_TESTIMONIALS_SUBMIT_SAVE_SUCCESS'));
		}

		return $result;
	}

	/**
	 * Upload one or more files
	 *
	 * @return  boolean
	 *
	 * @since   1.5
	 */

	public function uploadImage($files){

		$params = JComponentHelper::getParams('com_media');
		$image_path = $params->get('image_path', 'images');
		define('COM_MEDIA_BASE',    JPATH_ROOT . '/' . $image_path);
		$pathFolder = COM_MEDIA_BASE .'/xpert_testimonials';
		if( !JFolder::exists($pathFolder) )
		{
				try{
						JFolder::create($pathFolder);
				}
				catch (Exception $e)
				{
						echo JText::sprintf('COM_XPERT_TESTIMONIALS_ERROR_CREATE_FOLDER', $e->getCode(), $e->getMessage()) . '<br />';
						return false;
				}
		}
		$return = array();
		// Perform basic checks on file info before attempting anything
		foreach ($files as $key=>$file)
		{
			//Clean up filename to get rid of strange characters like spaces etc
			$filename = JFile::makeSafe($file['name']);
			$ext = strtolower(JFile::getExt($filename));
			$filename = md5($filename.time()).'.'.$ext;

			//Set up the source and destination of the file
			$src = $file['tmp_name'];
			$dest = $pathFolder . '/' . $filename;
			$url = $image_path . '/xpert_testimonials/' . $filename;

			//First check if the file has the right extension, we need jpg only
			if (
				$ext == 'jpg'
				or
				$ext == 'png'
				or
				$ext == 'jpeg'
				or
				$ext == 'bmp'
			) {
			   if ( JFile::upload($src, $dest) ) {
			      $return[$key] = $url; 
			   } else {
						JError::raiseWarning(100, JText::_('COM_XPERT_TESTIMONIALS_ERROR_UNABLE_TO_UPLOAD_FILE'));
						return false;
			   }
			} else {
		   	JError::raiseWarning(100, JText::_('COM_XPERT_TESTIMONIALS_ERROR_UNABLE_TO_UPLOAD_FILE'));
				return false;
			}

		}

		return $return;
	}
}
