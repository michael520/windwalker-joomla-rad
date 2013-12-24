<?php

namespace Windwalker\View;

use Joomla\DI\Container as JoomlaContainer;
use Joomla\DI\ContainerAwareInterface;
use Windwalker\DI\Container;

/**
 * Class View
 *
 * @since 1.0
 */
abstract class AbstractView implements \JView, ContainerAwareInterface
{
	/**
	 * The model object.
	 *
	 * @var    array
	 */
	protected $model = array();

	/**
	 * Property defaultModel.
	 *
	 * @var string
	 */
	protected $defaultModel;

	/**
	 * Property data.
	 *
	 * @var \JData
	 */
	protected $data;

	/**
	 * Property container.
	 *
	 * @var Container
	 */
	protected $container;

	/**
	 * Method to instantiate the view.
	 *
	 * @param   \JModel  $model  The model object.
	 *
	 * @since  12.1
	 */
	public function __construct(\JModel $model = null)
	{
		// Setup dependencies.
		if ($model)
		{
			$modelName = $model->getName();

			$this->defaultModel = strtolower($modelName);

			$this->model[strtolower($modelName)] = $model;
		}

		$this->data = new \JData;
	}

	/**
	 * Method to escape output.
	 *
	 * @param   string  $output  The output to escape.
	 *
	 * @return  string  The escaped output.
	 *
	 * @see     JView::escape()
	 * @since   12.1
	 */
	public function escape($output)
	{
		return $output;
	}

	/**
	 * Method to get the model object
	 *
	 * @param   string  $name  The name of the model (optional)
	 *
	 * @return  mixed  JModelLegacy object
	 *
	 * @since   12.2
	 */
	public function getModel($name = null)
	{
		if (!$name)
		{
			$name = $this->defaultModel;
		}

		$name = strtolower($name);

		if (empty($this->model[$name]))
		{
			return null;
		}

		return $this->model[$name];
	}

	/**
	 * Method to add a model to the view.  We support a multiple model single
	 * view system by which models are referenced by classname.  A caveat to the
	 * classname referencing is that any classname prepended by JModel will be
	 * referenced by the name without JModel, eg. JModelCategory is just
	 * Category.
	 *
	 * @param   \JModel $model    The model to add to the view.
	 * @param   boolean $default  Is this the default model?
	 *
	 * @return  object   The added model.
	 */
	public function setModel($model, $default = false)
	{
		$name = strtolower($model->getName());
		$this->model[$name] = $model;

		if ($default)
		{
			$this->defaultModel = $name;
		}

		return $model;
	}

	/**
	 * Method to get data from a registered model or a property of the view.
	 *
	 * @param   string  $cmd    The name of the method to call on the model or the property to get
	 * @param   string  $model  The name of the model to reference or the default value [optional]
	 * @param   array   $args   The arguments to send to model methods.
	 *
	 * @return  mixed  The return value of the method
	 */
	public function get($cmd, $model = null, $args = array())
	{
		// If $model is null we use the default model
		$model = $this->getModel($model);

		// First check to make sure the model requested exists
		if (!$model)
		{
			return null;
		}

		// Model exists, let's build the method name
		$method = 'get' . ucfirst($cmd);

		// Does the method exist?
		if (!method_exists($model, $method))
		{
			// $method = 'load' . ucfirst($cmd);

			return null;
		}

		// The method exists, let's call it and return what we get
		$result = call_user_func_array(array($model, $method), $args);

		return $result;
	}

	/**
	 * getData
	 *
	 * @return \JData
	 */
	public function getData()
	{
		if (!$this->data)
		{
			$this->data = new \JData;
		}

		return $this->data;
	}

	/**
	 * setData
	 *
	 * @param $data
	 *
	 * @return $this
	 */
	public function setData($data)
	{
		$this->data = $data;

		return $this;
	}

	/**
	 * Get the DI container.
	 *
	 * @return  Container
	 *
	 * @since   1.0
	 * @throws  \UnexpectedValueException May be thrown if the container has not been set.
	 */
	public function getContainer()
	{
		if (!$this->container)
		{
			$this->container = Container::getInstance($this->option);
		}

		return $this->container;
	}

	/**
	 * Set the DI container.
	 *
	 * @param   JoomlaContainer $container The DI container.
	 *
	 * @return  $this
	 *
	 * @since   1.0
	 */
	public function setContainer(JoomlaContainer $container)
	{
		$this->container = $container;

		return $this;
	}
}
