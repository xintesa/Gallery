<?php

App::uses('GalleryAppController', 'Gallery.Controller');
App::uses('MediaType', 'Gallery.Lib');

/**
 * Gallery Pictures Controller
 *
 * Uploading pictures into gallery, and edit them
 *
 * PHP version 5
 *
 * @category Controller
 * @package  Croogo
 * @version  1.1
 * @author   Zijad Redžić <zijad.redzic@gmail.com>
 * @license  http://www.opensource.org/licenses/mit-license.php The MIT License
 * @link     http://www.demoveo.com
 */
class PhotosController extends GalleryAppController {

	public $components = array(
		'Search.Prg' => array(
			'presetForm' => array(
				'paramType' => 'querystring',
			),
			'commonProcess' => array(
				'paramType' => 'querystring',
			),
		),
	);

	public $presetVars = true;

	public function beforeFilter() {
		parent::beforeFilter();
		$this->Auth->allow('index');
		$noCsrf = array('admin_toggle');
		if (in_array($this->action, $noCsrf) && $this->request->is('ajax')) {
			$this->Security->csrfCheck = false;
		}
	}

/**
 * Toggle Photo status
 *
 * @param string $id Photo id
 * @param integer $status Current Photo status
 * @return void
 */
	public function admin_toggle($id = null, $status = null) {
		$this->Croogo->fieldToggle($this->{$this->modelClass}, $id, $status);
	}

	public function admin_index() {
		$searchFields = array(
			'title' => array('type' => 'text'),
			'description' => array('type' => 'text'),
			'status' => array('type' => 'radio',
				'legend' => false,
				'options' => array(
					0 => 'Unpublished',
					1 => 'Published',
				)
			),
			'url',
		);
		$this->Prg->commonProcess();

		$this->paginate['contain'] = array(
			'OriginalAsset', 'ThumbnailAsset', 'LargeAsset',
		);

		$this->paginate['conditions'] = $this->Photo->parseCriteria($this->passedArgs);
		$photos = $this->paginate();
		$this->set(compact('photos', 'searchFields'));
		if (!empty($this->request->query['chooser'])) {
			$this->layout = 'admin_popup';
			$this->render('admin_chooser');
		}
	}

	public function admin_add() {
		if ($this->request->is('post')) {
			$saved = $this->Photo->save($this->request->data);
			if ($saved) {
				$id = $saved['Photo']['id'];
				$this->Session->setFlash(__d('gallery', 'Photo has been saved.'), 'flash', array('class' => 'success'));
				$this->Croogo->redirect(array('action' => 'edit', $id));
			} else {
				$this->Session->setFlash(__d('gallery', 'Photo cannot be saved.'), 'flash', array('class' => 'error'));
			}
		}

		$albumId = $this->request->query('album_id');
		if ($albumId) {
			$album = $this->Photo->Album->find('first', array(
				'recursive' => -1,
				'conditions' => array(
					'id' => $albumId,
				),
			));
			$this->request->data['Album']['Album'] = array($albumId);
			$this->set(compact('album'));
		}
		$albums = $this->Photo->Album->find('list');
		$this->set(compact('albums'));
	}

	public function admin_edit($id) {
		$this->Photo->id = $id;
		if ($this->request->is('post') || $this->request->is('put')) {
			if ($this->Photo->save($this->request->data)) {
				$this->Session->setFlash(__d('gallery', 'Photo has been saved.'), 'flash', array('class' => 'success'));
				$this->Croogo->redirect(array('action' => 'edit', $id));
			} else {
				$this->Session->setFlash(__d('gallery', 'Photo cannot be saved.'));
			}
		}
		$this->Photo->recursive = -1;
		$this->Photo->contain(array('Album', 'OriginalAsset', 'ThumbnailAsset', 'LargeAsset'));
		$this->request->data = $this->Photo->read(null, $id);
		$albums = $this->Photo->Album->find('list');
		$this->set(compact('albums'));
	}

	public function index($slug = null) {
		$this->set('title_for_layout',__d('gallery', "Photos"));

		if ($slug === null) {
			$slug = $this->request->param('slug');
		}

		if (!$slug) {
			$this->Session->setFlash(__d('gallery', 'Invalid album. Please try again.'), 'flash' , array('class' => 'error'));
			$this->redirect(array('controller' => 'albums', 'action' => 'index'));
		}
		$this->AlbumsPhoto = ClassRegistry::init('Gallery.AlbumsPhoto');
		$this->Album = ClassRegistry::init('Gallery.Album');

		$this->Photo->recursive = -1;
		$this->Photo->Behaviors->attach('Containable');
		$this->paginate = array(
			'fields' => array('*', 'Album.*'),
			'conditions' => array(
				'Photo.status' => CroogoStatus::PUBLISHED,
				'Album.slug' => $slug,
				'Album.status' => CroogoStatus::PUBLISHED
			),
			'contain' => array('ThumbnailAsset', 'LargeAsset', 'OriginalAsset'),
			'joins' => array(
				array(
					'alias' => $this->AlbumsPhoto->alias,
					'table' => $this->AlbumsPhoto->useTable,
					'conditions' => 'Photo.id = AlbumsPhoto.photo_id'
				),
				array(
					'alias' => $this->Album->alias,
					'table' => $this->Album->useTable,
					'conditions' => 'Album.id = AlbumsPhoto.album_id'
				),
			),
			'limit' => Configure::read('Gallery.album_limit_pagination'),
			'order' => 'AlbumsPhoto.weight ASC',
		);

		$mediaType = $this->request->query('media-type');
		switch ($mediaType) {
			case 'video':
				$this->paginate['conditions']['Photo.media_type'] = MediaType::VIDEO;
			break;
			case 'photo':
				$this->paginate['conditions']['Photo.media_type'] = MediaType::PHOTO;
			break;
		}
		$photos = $this->paginate();
		if (isset($this->params['requested'])) {
			return $photos;
		}
		$this->set(compact('photos'));
	}

	public function admin_moveup($id, $step = 1) {
		if ($this->Photo->AlbumsPhoto->moveUp($id, $step)) {
			$this->Session->setFlash(__d('gallery', 'Moved up successfully'), 'flash', array('class' => 'success'));
		} else {
			$this->Session->setFlash(__d('gallery', 'Could not move up'), 'flash', array('class' => 'error'));
		}
		$this->redirect($this->referer());
	}

	public function admin_movedown($id, $step = 1) {
		if ($this->Photo->AlbumsPhoto->moveDown($id, $step)) {
			$this->Session->setFlash(__d('gallery', 'Moved down successfully'), 'flash', array('class' => 'success'));
		} else {
			$this->Session->setFlash(__d('gallery', 'Could not move down'), 'flash', array('class' => 'error'));
		}
		$this->redirect($this->referer());
	}

}