<?php

$galleryPath = CakePlugin::path('Gallery');

if (file_exists($galleryPath. 'Config/gallery.php')) {
	Configure::load('Gallery.gallery');
}

if (file_exists($galleryPath . 'Vendor/ffmpeg-php/FFmpegAutoloader.php')) {
	require $galleryPath . 'Vendor/ffmpeg-php/FFmpegAutoloader.php';
}

Croogo::hookRoutes('Gallery');

Croogo::hookComponent('*', 'Gallery.Gallery');

Croogo::hookHelper('*', 'Gallery.Gallery');

Croogo::hookAdminMenu('Gallery');

Croogo::mergeConfig('Wysiwyg.actions', array(
	'Albums/admin_add' => array(
		array(
			'elements' => 'AlbumDescription',
		),
	),
	'Albums/admin_edit' => array(
		array(
			'elements' => 'AlbumDescription',
		),
	),
	'Photos/admin_add' => array(
		array(
			'elements' => 'PhotoDescription',
		),
	),
	'Photos/admin_edit' => array(
		array(
			'elements' => 'PhotoDescription',
		),
	)
));

$cacheConfig = array(
	'duration' => '+1 hour',
	'engine' => Configure::read('Cache.defaultEngine'),
);
Cache::config('gallery', $cacheConfig);

if (!CakePlugin::loaded('Imagine')) {
	CakePlugin::load('Imagine', array('bootstrap' => true));
}

if (CakePlugin::loaded('Assets')) {
	App::uses('StorageManager', 'Assets.Lib');
	if (class_exists('StorageManager')) {
		StorageManager::config('Gallery', array(
			'adapterOptions' => array(WWW_ROOT . 'galleries', true),
			'adapterClass' => '\Gaufrette\Adapter\Local',
			'class' => '\Gaufrette\Filesystem',
		));
	} else {
		CakeLog::critical('StorageManager class not found. Gallery plugin now requires Assets plugin');
	}
}
