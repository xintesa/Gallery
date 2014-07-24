<?php

$this->extend('/Common/admin_edit');

$title = $this->data['Photo']['title'] ? $this->data['Photo']['title'] : basename($this->data['Photo']['original']);

$this->Html
	->addCrumb('', '/admin', array('icon' => 'home'))
	->addCrumb('Gallery')
	->addCrumb(__d('gallery', 'Photos'), array('admin' => true, 'plugin' => 'gallery', 'controller' => 'photos', 'action' => 'index'))
	->addCrumb($title, $this->here);

echo $this->Form->create('Photo', array(
	'url' => array(
		'controller' => 'photos',
		'action' => 'edit',
	),
));

?>
<div class="row-fluid">
	<div class="span8">

		<ul class="nav nav-tabs">
			<li><a href="#photo-main" data-toggle="tab"><?php echo __d('gallery', 'Photo'); ?></a></li>
			<?php echo $this->Croogo->adminTabs(); ?>
		</ul>

		<div class="tab-content">
			<div id="photo-main" class="tab-pane">
			<?php
				echo $this->Form->input('id');

				echo $this->Form->input('Album');
				$this->Form->inputDefaults(array(
					'class' => 'span10',
				));
				echo $this->Form->input('title', array(
					'label' => __d('gallery', 'Title'),
				));
				echo $this->Form->input('description', array(
					'label' => __d('gallery', 'Description'),
				));
				echo $this->Form->input('url', array(
					'label' => __d('gallery', 'Url'),
				));
				echo $this->Form->input('params', array(
					'label' => __d('gallery', 'Params'),
				));
				echo $this->Form->input('weight', array(
					'label' => __d('gallery', 'Weight'),
				));
			?>
			</div>
			<?php echo $this->Croogo->adminTabs(); ?>
		</div>
	</div>

	<div class="span4">
	<?php
		echo $this->Html->beginBox(__d('gallery', 'Publishing')) .
			$this->Form->button(__d('gallery', 'Apply'), array('name' => 'apply', 'button' => 'default')) .
			$this->Form->button(__d('gallery', 'Save'), array('button' => 'primary')) .
			$this->Html->link(__d('gallery', 'Cancel'), array('controller' => 'photos', 'action' => 'index'), array('button' => 'danger')) .

			$this->Form->input('status', array(
				'legend' => false,
				'type' => 'radio',
				'class' => false,
				'default' => CroogoStatus::UNPUBLISHED,
				'options' => $this->Croogo->statuses(),
			)) .

			$this->Form->input('created', array(
				'type' => 'text',
				'placeholder' => __d('gallery', 'Created'),
				'readonly' => true,
			)) .

			$this->Html->div('input-daterange',
				$this->Form->input('publish_start', array(
					'label' => __d('croogo', 'Publish Start'),
					'type' => 'text',
				)) .
				$this->Form->input('publish_end', array(
					'label' => __d('croogo', 'Publish End'),
					'type' => 'text',
				))
			) .

			$this->Html->endBox();

		echo $this->Html->beginBox(__d('gallery', 'Preview')) .
			$this->Html->link(
				$this->Html->image($this->data['ThumbnailAsset']['path'], array(
					'class' => 'img-polaroid',
				)),
				$this->data['LargeAsset']['path'],
				array('class' => 'thickbox', 'escape' => false)
			) .
			$this->Html->endBox();

		echo $this->Croogo->adminBoxes();
	?>
	</div>
</div>
<?php echo $this->Form->end(); ?>
