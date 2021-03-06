<?php

$this->extend('/Common/admin_index');

$this->Html
	->addCrumb('', '/admin', array('icon' => 'home'))
	->addCrumb(__d('gallery', 'Gallery'))
	->addCrumb(__d('gallery', 'Photos'), array('admin' => true, 'plugin' => 'gallery', 'controller' => 'photos', 'action' => 'index'));

$this->set('showActions', false);

$this->append('table-heading');
	$tableHeaders = $this->Html->tableHeaders(array(
		$this->Paginator->sort('id'),
		$this->Paginator->sort('small', __d('gallery', 'Preview')),
		$this->Paginator->sort('title', __d('gallery', 'Title')),
		$this->Paginator->sort('description', __d('gallery', 'Description')),
		$this->Paginator->sort('url', __d('gallery', 'url')),
		__d('gallery', 'Albums'),
		$this->Paginator->sort('status'),
		__d('gallery', 'Actions'),
	));
	echo $tableHeaders;
$this->end();

$this->append('table-body');
	$rows = array();
	foreach ($photos AS $attachment):
		$actions = array();
		$actions[] = $this->Croogo->adminRowAction('',
			array('controller' => 'photos', 'action' => 'edit', $attachment['Photo']['id']),
			array(
				'icon' => $_icons['update'],
				'tooltip' => __d('gallery', 'Edit')
			)
		);
		$actions[] = $this->Croogo->adminRowActions($attachment['Photo']['id']);

		$thumbnail = $this->Html->link(
			$this->Html->thumbnail($attachment['ThumbnailAsset']['path']),
			$attachment['LargeAsset']['path'],
			array('class' => 'thickbox', 'escape' => false)
		);

		$albumLinks = array();
		$photoAlbums = Hash::extract($attachment, 'Album.{n}');
		foreach ($photoAlbums as $photoAlbum):
			$albumLinks[] = $this->Html->link($photoAlbum['title'], array(
				'plugin' => 'gallery',
				'controller' => 'albums',
				'action' => 'upload',
				$photoAlbum['id'],
			));
		endforeach;

		$rows[] = array(
			$attachment['Photo']['id'],
			$thumbnail,
			$attachment['Photo']['title'],
			$this->Text->truncate($attachment['Photo']['description'], 30, array(
				'html' => true
			)),
			$attachment['Photo']['url'],
			implode(', ', $albumLinks),
			$this->element('admin/toggle', array(
				'id' => $attachment['Photo']['id'],
				'status' => (int)$attachment['Photo']['status'],
			)),
			$this->Html->div('item-actions', implode(' ', $actions))
		);
	endforeach;

	echo $this->Html->tableCells($rows);
	echo $tableHeaders;
$this->end();
