<?php

$this->extend('/Common/admin_index');

$this->Html->css('/gallery/css/fileuploader', array('inline' => false));

$thumbnailClass = $this->Layout->cssClass('thumbnailClass');
$iPrefix = $this->Html->settings['iconDefaults']['classPrefix'];
$iDefault = $this->Html->settings['iconDefaults']['classDefault'];
$iDelete = "$iDefault " . $iPrefix . $_icons['delete'];
$iEdit = "$iDefault " . $iPrefix . $_icons['update'];
$iMoveUp = "$iDefault " . $iPrefix . $_icons['move-up'];
$iMoveDown = "$iDefault " . $iPrefix . $_icons['move-down'];

$editUrl = $this->Html->link($album['Album']['title'], array(
	'plugin' => 'gallery',
	'controller' => 'albums',
	'action' => 'edit',
	$album['Album']['id'],
));

$this->Html
	->addCrumb('', '/admin', array('icon' => 'home'))
	->addCrumb('Gallery')
	->addCrumb(__d('gallery', 'Albums'), array(
		'admin' => true,
		'plugin' => 'gallery',
		'controller' => 'albums',
		'action' => 'index'
	));

if (empty($album)):
	$this->Html->addCrumb(__d('gallery', 'Add'), $this->here);
else:
	$this->Html
		->addCrumb($album['Album']['title'], array(
			'action' => 'edit', $album['Album']['id'],
		))
		->addCrumb(__d('gallery', 'Photos'), $this->here);
endif;

$this->start('actions');
	echo $this->Form->postLink(__d('gallery', 'Reset weight'),
		array(
			'plugin' => 'gallery',
			'controller' => 'albums',
			'action' => 'reset_weight',
			$album['Album']['id'],
		),
		array(
			'button' => 'default',
			'escape' => false,
		),
		__d('gallery', 'You will lose existing order for this album. Continue?')
	);
$this->end();

$rowClass = $this->Layout->cssClass('row');
$columnFull = $this->Layout->cssClass('columnFull');

$photos = array();
if (isset($album['Photo'])):
	$photos = $album['Photo'];
endif;

$this->append('main');
?>
	<div id="upload" class="<?php echo $columnFull; ?>"></div>
	<div id="return">

		<?php foreach($album['Photo'] as $photo): ?>
			<div class="album-photo">
				<div class="preview">
				<?php
				$displayPath = isset($photo['LargeAsset']['path']) ? $photo['LargeAsset']['path'] : $photo['OriginalAsset']['path'];
				echo $this->Html->link(
					$this->Html->thumbnail($photo['ThumbnailAsset']['path']),
					$displayPath,
					array(
						'rel' => 'gallery-' . $photo['AlbumsPhoto']['album_id'],
						'class' => 'thickbox',
						'escape' => false,
					)
				);
				?>
				</div>

				<div class="path">
				<?php
					$filename = basename($displayPath);
					$filename = $this->Html->link(
						$this->Text->truncate($filename, 120),
						$photo['OriginalAsset']['path'],
						array('target' => '_blank', 'title' => $filename)
					);
					echo __d('gallery', 'Filename: %s', $filename);
				?>
				</div>

				<?php if (!empty($photo['title'])): ?>
				<div class="description">
					<?php echo $this->Html->tag('strong', $this->Text->truncate(strip_tags($photo['title']), 100)); ?>
					<br />
					<?php echo $this->Text->truncate(strip_tags($photo['description']), 120); ?></div>
				<?php endif; ?>

				<?php echo $this->element('Gallery.admin/album_actions', array('photo' => $photo)); ?>
			</div>
			<?php endforeach; ?>

	</div>

<?php
$this->end();

$this->Html->script('/gallery/js/fileuploader', array('block' => 'scriptBottom'));
$this->append('page-footer');
?>
<script>
function createUploader(){
	var containerTemplate = _.template(
		'<div class="album-photo">' +
		'	<div class="preview">' +
		'		<a class="thickbox" rel="gallery-<%= Album[0].id %>"' +
		'			href="<%= LargeAsset.path %>">' +
		'			<img src="<%= ThumbnailAsset.path %>" class="<?php echo $thumbnailClass; ?>">' +
		'		</a>' +
		'	</div>' +

		'	<div class="path">' +
		'		Filename: ' +
		'		<a target="_blank" title="<%= OriginalAsset.path %>"' +
		'			href="<%= OriginalAsset.path %>">' +
		'			<%= OriginalAsset.path.slice(OriginalAsset.path.lastIndexOf("/") + 1) %>' +
		'		</a>' +
		'	</div>' +

		'	<div class="photo-actions">' +
		'		<a class="edit" href="/admin/gallery/photos/edit/<%= Photo.id %>"><i class="<?php echo $iEdit; ?>"></i> <%= sEdit %></a>' +
		'		<a class="up" href="/admin/gallery/photos/moveup/<%= Photo.id %>"><i class="<?php echo $iMoveUp; ?>"></i> <%= sUp %></a>' +
		'		<a class="down" href="/admin/gallery/photos/movedown/<%= Photo.id %>"><i class="<?php echo $iMoveDown; ?>"></i> <%= sDown %></a>' +
		'	</div>' +
		'</div>'
	);

	var uploader = new qq.FileUploader({
		element: document.getElementById('upload'),
		action: '<?php echo $this->Html->url(array('action' => 'upload_photo', $album['Album']['id'])); ?>',
		onComplete: function(id, fileName, json) {
			$('.qq-upload-fail').fadeOut('fast', function(){
				$(this).remove();
			});
			var sRemove = '<?php echo __d('gallery', 'remove'); ?>';
			var sEdit = '<?php echo __d('gallery', 'edit'); ?>';
			var sUp = '<?php echo __d('gallery', 'up'); ?>';
			var sDown = '<?php echo __d('gallery', 'down'); ?>';
			var args = {
				Photo: json.Photo,
				Album: json.Album,
				OriginalAsset: json.OriginalAsset,
				ThumbnailAsset: json.ThumbnailAsset,
				LargeAsset: json.LargeAsset,
				sRemove: sRemove,
				sEdit: sEdit,
				sUp: sUp,
				sDown: sDown,
			};
			$('#return').append(containerTemplate(args));
			tb_init('a.thickbox');
		}
	});
}

// in your app create uploader as soon as the DOM is ready
// don't wait for the window to load
$(function(){
	createUploader();
	$('.remove').on('click', function(){
		var obj = $(this);
		var url = '<?php echo $this->Html->url('/admin/gallery/albums/delete_photo/');?>'+obj.attr('rel');
		$.ajax(url, {
			type: 'POST',
			success: function(data, textStatus, jqXHR) {
				var json = $.parseJSON(data);
				if (json['status'] == 1) {
					obj.parents('.album-photo').fadeOut('fast', function() {
						$(this).remove();
					});
				} else {
					alert(json['msg']);
				}
			}
		});
	});

});

</script>
<?php

$this->end();
