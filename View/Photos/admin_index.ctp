<?php

$this->extend('/Common/admin_index');

$this->Html
	->addCrumb('', '/admin', array('icon' => 'home'))
	->addCrumb(__d('gallery', 'Gallery'))
	->addCrumb(__d('gallery', 'Photos'), array('admin' => true, 'plugin' => 'gallery', 'controller' => 'photos', 'action' => 'index'));

$this->assign('actions', ' ');

?>
<div class="photos index table-container">

	<table class="table table-striped">
	<?php
		$tableHeaders = $this->Html->tableHeaders(array(
			$this->Paginator->sort('id'),
			$this->Paginator->sort('small', __d('gallery', 'Preview')),
			$this->Paginator->sort('title', __d('gallery', 'Title')),
			$this->Paginator->sort('description', __d('gallery', 'Description')),
			$this->Paginator->sort('url', __d('gallery', 'url')),
			__d('gallery', 'Albums'),
			__d('gallery', 'Actions'),
		));
		echo $tableHeaders;

		$rows = array();
		foreach ($photos AS $attachment) {
			$actions = array();
			$actions[] = $this->Croogo->adminRowAction('',
				array('controller' => 'photos', 'action' => 'edit', $attachment['Photo']['id']),
				array('icon' => 'pencil', 'tooltip' => __d('gallery', 'Edit'))
			);
			$actions[] = $this->Croogo->adminRowActions($attachment['Photo']['id']);

			$thumbnail = $this->Html->link(
				$this->Html->image($attachment['ThumbnailAsset']['path'], array(
					'class' => 'img-polaroid',
					'style' => 'max-width: 300px',
				)),
				$attachment['LargeAsset']['path'],
				array('class' => 'thickbox', 'escape' => false)
			);

			$albumLinks = array();
			$photoAlbums = Hash::extract($attachment, 'Album.{n}');
			foreach ($photoAlbums as $photoAlbum) {
				$albumLinks[] = $this->Html->link($photoAlbum['title'], array(
					'plugin' => 'gallery',
					'controller' => 'albums',
					'action' => 'upload',
					$photoAlbum['id'],
				));
			}
			$rows[] = array(
				$attachment['Photo']['id'],
				$thumbnail,
				$attachment['Photo']['title'],
				$this->Text->truncate(strip_tags($attachment['Photo']['description']), 30),
				$attachment['Photo']['url'],
				implode(', ', $albumLinks),
				$this->Html->div('item-actions', implode(' ', $actions))
			);
		}

		echo $this->Html->tableCells($rows);
		echo $tableHeaders;
	?>
	</table>
</div>
