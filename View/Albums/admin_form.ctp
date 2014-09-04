<?php

$this->extend('/Common/admin_edit');

$this->Html
	->addCrumb('', '/admin', array('icon' => 'home'))
	->addCrumb('Gallery')
	->addCrumb(__d('gallery', 'Albums'), array('admin' => true, 'plugin' => 'gallery', 'controller' => 'albums', 'action' => 'index'));

if (empty($this->data['Album']['title'])) {
	$this->Html->addCrumb(__d('gallery', 'Add'), $this->here);
} else {
	$this->Html->addCrumb($this->data['Album']['title'], $this->here);
}

$inputDefaults = $this->Form->settings;
$inputClass = isset($inputDefaults['class']) ? $inputDefaults['class'] : null;
if (empty($inputClass)):
	$inputClass = $this->Layout->cssClass('formInput');
endif;

$this->start('actions');
	if (!empty($this->data['Album']['title'])):
		echo $this->Html->link(__d('gallery','Photos'),
			array('action'=>'upload', $this->data['Album']['id']),
			array('button' => 'default', 'tooltip' => array(
				'title' => __d('gallery', 'Manage/upload photos for this album'),
				'data-placement' => 'right',
			))
		);
	endif;
$this->end();

$this->append('form-start', $this->Form->create('Album'));

$this->append('tab-heading');
	echo $this->Croogo->adminTab(__d('gallery', 'Album'), "#album-main");
	echo $this->Croogo->adminTab(__d('gallery', 'Settings'), '#album-settings');
	echo $this->Croogo->adminTabs();
$this->end();

$this->append('tab-content');
	echo $this->Html->tabStart('album-main') .
		$this->Form->input('id') .
		$this->Form->input('title', array(
			'label' => __d('gallery', 'Title')
		)) .
		$this->Form->input('slug', array(
			'label' => __d('gallery', 'Slug'),
		)) .
		$this->Form->input('description', array(
			'label' => __d('gallery', 'Description'),
		)) .
		$this->Form->input('type', array(
			'label' => __d('gallery', 'Type'),
			'empty' => true,
			'default' => key($types),
		));
	echo $this->Html->tabEnd();

	echo $this->Html->tabStart('album-settings') .
		$this->Form->input('quality', array(
			'label' => __d('gallery', 'Quality'),
		)) .
		$this->Form->input('max_width', array(
			'label' => __d('gallery', 'Max. width'),
		)) .
		$this->Form->input('max_height', array(
			'label' => __d('gallery', 'Max. height'),
		)) .
		$this->Form->input('max_width_thumbnail', array(
			'label' => __d('gallery', 'Max. thumbnail width'),
		)) .
		$this->Form->input('max_height_thumbnail', array(
			'label' => __d('gallery', 'Max. thumbnail height'),
		)) .
		$this->Form->input('params', array(
			'label' => __d('gallery', 'Parameters'),
		));
	echo $this->Html->tabEnd();
$this->end();

$this->append('panels');
		echo $this->Html->beginBox(__d('gallery', 'Publishing')) .
			$this->Form->button(__d('gallery', 'Apply'), array('name' => 'apply', 'button' => 'default')) .
			$this->Form->button(__d('gallery', 'Save'), array('button' => 'success')) .
			$this->Html->link(__d('gallery', 'Cancel'), array('action' => 'index'), array('class' => 'cancel btn btn-danger')) .

			$this->Form->input('status', array(
				'legend' => false,
				'type' => 'radio',
				'class' => false,
				'default' => CroogoStatus::UNPUBLISHED,
				'options' => $this->Croogo->statuses(),
			)) .

			$this->Form->input('created', array(
				'type' => 'text',
				'class' => 'input-datetime ' . trim($inputClass)
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

		echo $this->Croogo->adminBoxes();
$this->end();

$this->append('form-end', $this->Form->end());