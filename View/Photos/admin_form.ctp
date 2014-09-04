<?php

$this->extend('/Common/admin_edit');

$title = $this->data['Photo']['title'] ? $this->data['Photo']['title'] : $this->data['Photo']['id'];

$this->Html
	->addCrumb('', '/admin', array('icon' => 'home'))
	->addCrumb('Gallery')
	->addCrumb(__d('gallery', 'Photos'), array('admin' => true, 'plugin' => 'gallery', 'controller' => 'photos', 'action' => 'index'))
	->addCrumb($title, $this->here);

$this->append('form-start', $this->Form->create('Photo', array(
	'url' => array(
		'controller' => 'photos',
		'action' => 'edit',
	),
)));

$this->append('tab-heading');
	echo $this->Croogo->adminTab(__d('gallery', 'Photo'), '#photo-main');
	echo $this->Croogo->adminTabs();
$this->end();

$this->append('tab-content');
	echo $this->Html->tabStart('photo-main') .
		$this->Form->input('id') .
		$this->Form->input('Album') .
		$this->Form->input('title', array(
			'label' => __d('gallery', 'Title'),
		)) .
		$this->Form->input('description', array(
			'label' => __d('gallery', 'Description'),
		)) .
		$this->Form->input('url', array(
			'label' => __d('gallery', 'Url'),
		)) .
		$this->Form->input('params', array(
			'label' => __d('gallery', 'Params'),
		)) .
		$this->Form->input('weight', array(
			'label' => __d('gallery', 'Weight'),
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
$this->end();

$this->append('form-end' , $this->Form->end());
