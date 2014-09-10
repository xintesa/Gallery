<h2><?php echo __d('gallery','Albums');?></h2>
<?php

if(count($photos) == 0):
	echo __d('gallery','No albums found.');
	return;
endif;

?>

<div class="photos">
<ul>
<?php foreach($photos as $photo): ?>
	<li>
		<h3><?php echo $photo['Photo']['title']; ?></h3>
		<p>
		<?php
			if (empty($photo['ThumbnailAsset'])):
				echo __d('gallery', 'This album has no photo');
				continue;
			endif;

			$path = $photo['ThumbnailAsset']['path'];
			echo $this->Html->image(
				$this->Html->webroot($path, array(
					'style' => 'float:left;margin:5px 5px 5px 0px;',
				))
			);
		?>
		</p>
	</li>
<?php endforeach; ?>
</ul>
</div>

<div class="paging"><?php echo $this->Paginator->numbers(); ?></div>
