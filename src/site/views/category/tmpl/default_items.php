<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_xmonials
 *
 * @copyright   Copyright (C) 2005 - 2015 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

JHtml::addIncludePath(JPATH_COMPONENT . '/helpers/html');

JHtml::_('behavior.framework');

// Create a shortcut for params.
$params = &$this->item->params;

// Get the user object.
$user = JFactory::getUser();

// Check if user is allowed to add/edit based on xmonials permissinos.
$canEdit = $user->authorise('core.edit', 'com_xmonials.category.' . $this->category->id);
$canCreate = $user->authorise('core.create', 'com_xmonials');
$canEditState = $user->authorise('core.edit.state', 'com_xmonials');

$n = count($this->items);
$listOrder	= $this->escape($this->state->get('list.ordering'));
$listDirn	= $this->escape($this->state->get('list.direction'));
?>

<?php if (empty($this->items)) : ?>
	<p> <?php echo JText::_('COM_XMONIALS_NO_XMONIALS'); ?></p>
<?php else : ?>

<form action="<?php echo htmlspecialchars(JUri::getInstance()->toString()); ?>" method="post" name="adminForm" id="adminForm">
	<?php if ($this->params->get('filter_field') != 'hide' || $this->params->get('show_pagination_limit')) :?>
	<fieldset class="filters btn-toolbar">
		<?php if ($this->params->get('show_pagination_limit')) : ?>
			<div class="btn-group pull-right">
				<label for="limit" class="element-invisible">
					<?php echo JText::_('JGLOBAL_DISPLAY_NUM'); ?>
				</label>
				<?php echo $this->pagination->getLimitBox(); ?>
			</div>
		<?php endif; ?>
	</fieldset>
	<?php endif; ?>
		<ul class="category list-striped list-condensed">

			<?php foreach ($this->items as $i => $item) : ?>
				<?php if (in_array($item->access, $this->user->getAuthorisedViewLevels())) : ?>
					<?php if ($this->items[$i]->state == 0) : ?>
						<li class="system-unpublished cat-list-row<?php echo $i % 2; ?>">
					<?php else: ?>
						<li class="cat-list-row<?php echo $i % 2; ?>" >
					<?php endif; ?>

					<?php if ($canEdit) : ?>
						<span class="list-edit pull-left width-50">
							<?php echo JHtml::_('icon.edit', $item, $params); ?>
						</span>
					<?php endif; ?>

					<div class="list-title">
						<?php
							// Compute the correct link
							$menuclass = 'category' . $this->pageclass_sfx;
							$link = $item->link;
							if ($this->items[$i]->state == 0) : ?>
								<span class="label label-warning">Unpublished</span>
							<?php endif; ?>

							<h3 class="title xmonial-title">
								<?php echo $item->name; ?> <small><?php echo $item->designation; ?></small>
							</h3>

							<p>
								<?php if(!empty($item->email)): ?>
									<span class="label label-info"><?php echo $item->email; ?></span>
								<?php endif; ?>
								<?php if(!empty($item->url)): ?>
									<a class="label label-warning" href="<?php echo $item->url; ?>" rel="nofollow" target="_blank">
										<?php echo $item->url; ?>
									</a>
								<?php endif; ?>
							</p>

						</div>
						<?php $tagsData = $item->tags->getItemTags('com_xmonials.xmonial', $item->id); ?>
						<?php if ($this->params->get('show_tags', 1)) : ?>
							<?php $this->item->tagLayout = new JLayoutFile('joomla.content.tags'); ?>
							<?php echo $this->item->tagLayout->render($tagsData); ?>
						<?php endif; ?>

						<?php if (($this->params->get('show_link_description')) and ($item->description != '')) : ?>
							<?php $images = json_decode($item->images); ?>
							<?php  if (isset($images->author_image) and !empty($images->author_image)) : ?>
								<?php $imgfloat = (empty($images->float_author_image)) ? $this->params->get('float_author_image') : $images->float_author_image; ?>
								<div class="img-intro-<?php echo htmlspecialchars($imgfloat); ?>">
									<img
									<?php if ($images->author_image_caption):
										echo 'class="caption"'.' title="' .htmlspecialchars($images->author_image_caption) .'"';
									endif; ?>
									src="<?php echo htmlspecialchars($images->author_image); ?>" alt="<?php echo htmlspecialchars($images->author_image_alt); ?>"/>
								</div>
							<?php else: ?>
								<img src="http://www.gravatar.com/avatar/<?php echo md5( strtolower( trim($item->email) ) ); ?>" />
							<?php endif; ?>

							<p class="description">
								<?php echo $item->description; ?>
							</p>

						<?php endif; ?>

						</li>
				<?php endif;?>
			<?php endforeach; ?>
		</ul>

		<?php // Code to add a link to submit a xmonial. ?>
		<?php /* if ($canCreate) : // TODO This is not working due to some problem in the router, I think. Ref issue #23685 ?>
			<?php echo JHtml::_('icon.create', $item, $item->params); ?>
		<?php  endif; */ ?>
		<?php if ($this->params->get('show_pagination')) : ?>
		 <div class="pagination">
			<?php if ($this->params->def('show_pagination_results', 1)) : ?>
				<p class="counter">
					<?php echo $this->pagination->getPagesCounter(); ?>
				</p>
			<?php endif; ?>
				<?php echo $this->pagination->getPagesLinks(); ?>
			</div>
		<?php endif; ?>
	</form>
<?php endif; ?>
