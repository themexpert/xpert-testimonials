<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_xmonials
 *
 * @copyright   Copyright (C) 2005 - 2015 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

JHtml::_('behavior.keepalive');
JHtml::_('behavior.formvalidation');
JHtml::_('formbehavior.chosen', 'select');
JHtml::_('behavior.modal', 'a.modal_jform_contenthistory');

// Create shortcut to parameters.
$params = $this->state->get('params');
?>

<script type="text/javascript">
	Joomla.submitbutton = function(task)
	{
		if (task == 'xmonial.cancel' || document.formvalidator.isValid(document.getElementById('adminForm')))
		{
			<?php echo $this->form->getField('description')->save(); ?>
			Joomla.submitform(task);
		}
	}
</script>
<div class="edit<?php echo $this->pageclass_sfx; ?>">
	<?php if ($this->params->get('show_page_heading')) : ?>
	<h1>
		<?php echo $this->escape($this->params->get('page_heading')); ?>
	</h1>
	<?php endif; ?>
	<form action="<?php echo JRoute::_('index.php?option=com_xmonials&view=form&w_id='.(int) $this->item->id); ?>"
		method="post" name="adminForm" id="adminForm" class="form-validate form-horizontal"
		enctype="multipart/form-data">
		<div class="btn-toolbar">
			<div class="btn-group">
				<button type="button" class="btn btn-primary" onclick="Joomla.submitbutton('xmonial.save')">
					<span class="icon-ok"></span> <?php echo JText::_('JSAVE') ?>
				</button>
			</div>
			<div class="btn-group">
				<button type="button" class="btn" onclick="Joomla.submitbutton('xmonial.cancel')">
					<span class="icon-cancel"></span> <?php echo JText::_('JCANCEL') ?>
				</button>
			</div>
			<?php if ($params->get('save_history', 0)) : ?>
				<div class="btn-group">
					<?php echo $this->form->getInput('contenthistory'); ?>
				</div>
			<?php endif; ?>
		</div>

		<hr class="hr-condensed" />
		<?php echo $this->form->renderField('catid'); ?>
		<?php echo $this->form->renderField('title'); ?>
		<?php echo $this->form->renderField('name'); ?>
		<?php echo $this->form->renderField('designation'); ?>
		<?php echo $this->form->renderField('email'); ?>
		<?php echo $this->form->renderField('url'); ?>

		<?php if ($params->get('save_history', 0)) : ?>
			<?php echo $this->form->renderField('version_note'); ?>
		<?php endif; ?>

		<?php if ($this->user->authorise('core.edit.state', 'com_xmonials.xmonial')) : ?>
			<?php echo $this->form->renderField('state'); ?>
		<?php endif; ?>

		<?php echo $this->form->getControlGroup('images'); ?>
		<?php foreach ($this->form->getGroup('images') as $field) : ?>
			<?php echo $field->getControlGroup(); ?>
		<?php endforeach; ?>

		<?php echo $this->form->renderField('description'); ?>

		<input type="hidden" name="return" value="<?php echo $this->return_page;?>" />
		<input type="hidden" name="task" value="" />
		<?php echo JHtml::_('form.token'); ?>
	</form>
</div>
