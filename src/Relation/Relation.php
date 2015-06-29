<?php
/**
 * Part of joomla341c project. 
 *
 * @copyright  Copyright (C) 2015 {ORGANIZATION}. All rights reserved.
 * @license    GNU General Public License version 2 or later;
 */

namespace Windwalker\Relation;

use Windwalker\Relation\Handler\OneToManyRelation;
use Windwalker\Relation\Handler\RelationHandlerInterface;
use Windwalker\Table\Table;

/**
 * The Relation class.
 * 
 * @since  {DEPLOY_VERSION}
 */
class Relation implements RelationHandlerInterface
{
	/**
	 * Property parent.
	 *
	 * @var  Table
	 */
	protected $parent;

	/**
	 * Property prefix.
	 *
	 * @var  string
	 */
	protected $prefix;

	/**
	 * Property relations.
	 *
	 * @var  RelationHandlerInterface[]
	 */
	protected $relations = array();

	/**
	 * Class init.
	 *
	 * @param Table $parent
	 */
	public function __construct(Table $parent, $prefix = 'JTable')
	{
		$this->parent = $parent;
	}

	public function addOneToMany($field, $table, $fks = array(), $onUpdate = Action::CASCADE, $onDelete = Action::CASCADE,
		$options = array())
	{
		if (!($table instanceof \JTable))
		{
			$table = $this->getTable($table, $this->prefix);
		}

		$relation = new OneToManyRelation($this->parent, $field, $table, $fks, $onUpdate, $onDelete, $options);

		$relation->setParent($this->parent);

		$this->relations[$field] = $relation;
	}

	public function load()
	{
		foreach ($this->relations as $relation)
		{
			$relation->load();
		}
	}

	public function store()
	{
		foreach ($this->relations as $relation)
		{
			$relation->store();
		}
	}

	public function delete()
	{
		foreach ($this->relations as $relation)
		{
			$relation->delete();
		}
	}

	/**
	 * getTable
	 *
	 * @param string $table
	 * @param string $prefix
	 *
	 * @return  \JTable
	 */
	protected function getTable($table, $prefix = null)
	{
		if (!is_string($table))
		{
			throw new \InvalidArgumentException('Table name should be string.');
		}

		if ($table = \JTable::getInstance($table, $prefix ? : $this->prefix))
		{
			return $table;
		}

		return new Table($table);
	}
}
