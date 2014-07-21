<?php

$this->extend('/Common/admin_index');

$this->Html
	->addCrumb('', '/admin', array('icon' => 'home'))
	->addCrumb(__d('gallery', 'Gallery'))
	->addCrumb(__d('gallery', 'Albums'), $this->here);
?>
<?php echo $this->start('actions'); ?>
	<?php
	echo $this->Html->link(__d('gallery','New album'), array(
		'action'=>'add',
	), array(
		'button' => 'default',
		'icon' => 'plus',
	));
	?>
<?php echo $this->end(); ?>
<div class="row-fluid">
	<div class="span12">
		<table class="table table-striped">
		<?php
			$tableHeaders =  $this->Html->tableHeaders(array(
				$this->Paginator->sort('id'),
				__d('gallery','Order number'),
				__d('gallery', 'Title'),
				__d('gallery', 'Description'),
				__d('gallery', 'Type'),
				$this->Paginator->sort('status'),
				__d('gallery', 'Actions'),
			));
			echo $tableHeaders;

			$rows = array();
			foreach ($albums as $album) {
				$actions = array();
				$actions[] = $this->Croogo->adminRowAction('',
					array('controller' => 'albums', 'action' => 'moveup', $album['Album']['id']),
					array('icon' => 'arrow-up', 'tooltip' => __d('gallery', 'Move up'))
				);
				$actions[] = $this->Croogo->adminRowAction('',
					array('controller' => 'albums', 'action' => 'movedown', $album['Album']['id']), array('icon' => 'arrow-down', 'tooltip' => __d('gallery', 'Move down'))
				);
				$actions[] = $this->Html->link('',
					array('controller' => 'albums', 'action' => 'upload', $album['Album']['id']),
					array(
						'class' => 'icon-large icon-picture',
						'tooltip' => __d('gallery','Photos in album'),
					)
				);
				$actions[] = $this->Croogo->adminRowActions($album['Album']['id']);
				$actions[] = $this->Croogo->adminRowAction('',
					array('controller' => 'albums', 'action' => 'edit', $album['Album']['id']),
					array('icon' => 'pencil', 'tooltip' => __d('gallery', 'Edit'))
				);
				$actions[] = $this->Croogo->adminRowAction('',
					array('controller' => 'albums', 'action' => 'delete', $album['Album']['id']),
					array('icon' => 'trash', 'tooltip' => __d('gallery', 'Delete')),
					__d('gallery', 'Are you sure you want to delete this album?')
				);

				$rows[] = array(
					$album['Album']['id'],
					$album['Album']['position'],
					$album['Album']['title'],
					$this->Text->truncate($album['Album']['description'], 50),
					$album['Album']['type'],
					$this->element('admin/toggle', array(
						'id' => $album['Album']['id'],
						'status' => (int)$album['Album']['status'],
					)),
					$this->Html->div('item-actions', implode(' ', $actions)),
				);
			}

			echo $this->Html->tableCells($rows);
			echo $tableHeaders;
		?>
		</table>
	</div>
</div>