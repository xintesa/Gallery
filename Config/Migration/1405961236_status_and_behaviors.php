<?php

class StatusAndBehaviors extends CakeMigration {

/**
 * Migration description
 *
 * @var string
 * @access public
 */
	public $description = '';

/**
 * Actions to be performed
 *
 * @var array $migration
 * @access public
 */
	public $migration = array(
		'up' => array(
			'alter_field' => array(
				'albums' => array(
					'status' => array('type' => 'integer', 'null' => false, 'default' => 0),
				),
				'photos' => array(
					'status' => array('type' => 'integer', 'null' => false, 'default' => 0),
				),
			),
			'create_field' => array(
				'albums' => array(
					'publish_start' => array('type' => 'datetime', 'after' => 'params', 'null' => true),
					'publish_end' => array('type' => 'datetime', 'after' => 'publish_start', 'null' => true),
					'created_by' => array('type' => 'integer', 'after' => 'created'),
					'updated' => array('type' => 'datetime', 'after' => 'created_by', 'null' => true),
					'updated_by' => array('type' => 'integer', 'after' => 'updated'),
				),
				'photos' => array(
					'publish_start' => array('type' => 'datetime', 'after' => 'params', 'null' => true),
					'publish_end' => array('type' => 'datetime', 'after' => 'publish_start', 'null' => true),
					'created_by' => array('type' => 'integer', 'after' => 'created'),
					'updated' => array('type' => 'datetime', 'after' => 'created_by', 'null' => true),
					'updated_by' => array('type' => 'integer', 'after' => 'updated'),
				),
			),
		),

		'down' => array(
			'alter_field' => array(
				'albums' => array(
					'status' => array('type' => 'boolean', 'null' => false, 'default' => false),
				),
				'photos' => array(
					'status' => array('type' => 'boolean', 'null' => false, 'default' => false),
				),
			),
			'drop_field' => array(
				'albums' => array(
					'publish_start', 'publish_end', 'created_by', 'updated', 'updated_by',
				),
				'photos' => array(
					'publish_start', 'publish_end', 'created_by', 'updated', 'updated_by',
				),
			),
		),
	);

/**
 * Before migration callback
 *
 * @param string $direction, up or down direction of migration process
 * @return boolean Should process continue
 * @access public
 */
	public function before($direction) {
		return true;
	}

/**
 * After migration callback
 *
 * @param string $direction, up or down direction of migration process
 * @return boolean Should process continue
 * @access public
 */
	public function after($direction) {
		return true;
	}
}
