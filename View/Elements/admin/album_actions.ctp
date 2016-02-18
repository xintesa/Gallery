<div class="photo-actions">
<?php
	echo $this->Form->postLink(__d('gallery', 'remove'), 'javascript:;',
		array(
			'rel' => $photo['id'],
			'class' => 'remove red',
			'icon' => $this->Theme->getIcon('delete'),
		)
	);
?>

<?php
	echo $this->Html->link(__d('gallery', 'edit'), array(
		'controller' => 'photos',
		'action' => 'edit',
		$photo['id'],
	), array(
		'class' => 'edit',
		'icon' => $this->Theme->getIcon('update'),
	));

?>

<?php
	echo $this->Html->link('up', array(
		'controller' => 'photos',
		'action' => 'moveup',
		$photo['AlbumsPhoto']['id'],
	), array(
		'class' => 'up',
		'icon' => $this->Theme->getIcon('move-up'),
	));
?>

<?php
	echo $this->Html->link('down', array(
		'controller' => 'photos',
		'action' => 'movedown',
		$photo['AlbumsPhoto']['id'],
	), array(
		'class' => 'down',
		'icon' => $this->Theme->getIcon('move-down'),
	));
?>
</div>
