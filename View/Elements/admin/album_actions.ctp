<div class="photo-actions">
<?php
	echo $this->Form->postLink(__d('gallery', 'remove'), 'javascript:;',
		array(
			'rel' => $photo['id'],
			'class' => 'remove red',
			'icon' => $_icons['delete'],
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
		'icon' => $_icons['update'],
	));

?>

<?php
	echo $this->Html->link('up', array(
		'controller' => 'photos',
		'action' => 'moveup',
		$photo['AlbumsPhoto']['id'],
	), array(
		'class' => 'up',
		'icon' => $_icons['move-up'],
	));
?>

<?php
	echo $this->Html->link('down', array(
		'controller' => 'photos',
		'action' => 'movedown',
		$photo['AlbumsPhoto']['id'],
	), array(
		'class' => 'down',
		'icon' => $_icons['move-down'],
	));
?>
</div>
