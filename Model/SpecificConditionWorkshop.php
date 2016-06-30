<?php
App::uses('AppModel', 'Model');
/**
 * SpecificConditionWorkshop Model
 *
 * @property Workshop $Workshop
 * @property SpecificCondition $SpecificCondition
 */
class SpecificConditionWorkshop extends AppModel {

/**
 * Use table
 *
 * @var mixed False or table name
 */
	public $useTable = 'specific_condition_workshop';

/**
 * Primary key field
 *
 * @var string
 */
	public $primaryKey = 'specific_condition_id';

/**
 * Validation rules
 *
 * @var array
 */
	public $validate = array(
		'workshop_id' => array(
			'notEmpty' => array(
				'rule' => array('notEmpty'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		'specific_condition_id' => array(
			'notEmpty' => array(
				'rule' => array('notEmpty'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
	);

	//The Associations below have been created with all possible keys, those that are not needed can be removed

/**
 * belongsTo associations
 *
 * @var array
 */
	public $belongsTo = array(
		'Workshop' => array(
			'className' => 'Workshop',
			'foreignKey' => 'workshop_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'SpecificCondition' => array(
			'className' => 'SpecificCondition',
			'foreignKey' => 'specific_condition_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
	);
}
