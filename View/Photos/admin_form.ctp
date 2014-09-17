<?php

$this->extend('/Common/admin_edit');

$id = isset($this->request->data['Photo']['id']) ?
	$this->request->data['Photo']['id'] :
	null;

if (!empty($this->data['Photo']['title'])):
	$title = $this->data['Photo']['title'];
elseif (isset($id)):
	$title = $id;
else:
	$title = __d('gallery', 'New');
endif;

$this->Html
	->addCrumb('', '/admin', array('icon' => 'home'))
	->addCrumb('Gallery');

if (isset($album['Album']['title'])):
	$this->Html
		->addCrumb(__d('gallery', 'Albums'), array(
			'plugin' => 'gallery',
			'controller' => 'albums',
			'action' => 'index',
		))
		->addCrumb($album['Album']['title'], array(
			'plugin' => 'gallery',
			'controller' => 'albums',
			'action' => 'edit',
			$album['Album']['id'],
		));
endif;

$this->Html->addCrumb(__d('gallery', 'Photos'), array(
	'admin' => true, 'plugin' => 'gallery',
	'controller' => 'photos', 'action' => 'index',
));

$query = null;
if (!empty($this->request->query)):
	$query = '?' . http_build_query($this->request->query);
endif;
$this->Html->addCrumb($title, $query);

$this->append('form-start', $this->Form->create('Photo'));

$this->append('tab-heading');
	echo $this->Croogo->adminTab(__d('gallery', 'Photo'), '#photo-main');
	echo $this->Croogo->adminTab(__d('gallery', 'Setting'), '#photo-additional');
	echo $this->Croogo->adminTabs();
$this->end();

$this->append('tab-content');
	echo $this->Html->tabStart('photo-main') .
		$this->Form->input('id') .
		$this->Form->input('Album') .
		$this->Form->input('title', array(
			'label' => __d('gallery', 'Title'),
		)) .
		$this->Form->input('external_url', array(
			'label' => __d('gallery', 'External Url'),
			'help' => __d('gallery', 'External media URL. Eg.: Youtube or Vimeo video links (Optional)'),
		)) .
		$this->Form->input('description', array(
			'label' => __d('gallery', 'Description'),
		));

	echo $this->Html->tabEnd();

	echo $this->Html->tabStart('photo-additional') .
		$this->Form->input('url', array(
			'label' => __d('gallery', 'Url'),
			'help' => 'Useful to store URLs to trigger when image is clicked',
		)) .
		$this->Form->input('weight', array(
			'label' => __d('gallery', 'Weight'),
		)) .
		$this->Form->input('params', array(
			'label' => __d('gallery', 'Params'),
		));
	echo $this->Html->tabEnd();

	echo $this->Croogo->adminTabs();
$this->end();

$this->append('panels');
	echo $this->Html->beginBox(__d('gallery', 'Publishing')) .
		$this->Form->button(__d('gallery', 'Apply'), array('name' => 'apply')) .
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
		);

	echo $this->Html->endBox();

	if (isset($this->data['ThumbnailAsset']['path'])):
		echo $this->Html->beginBox(__d('gallery', 'Preview')) .
			$this->Html->link(
				$this->Html->image($this->data['ThumbnailAsset']['path'], array(
					'class' => 'img-polaroid',
				)),
				$this->data['LargeAsset']['path'],
				array('class' => 'thickbox', 'escape' => false)
			) .
			$this->Html->endBox();
	endif;

	echo $this->Croogo->adminBoxes();
$this->end();

$this->append('form-end' , $this->Form->end());
