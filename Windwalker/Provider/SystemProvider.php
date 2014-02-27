<?php

namespace Windwalker\Provider;

use Joomla\DI\Container;
use Joomla\Registry\Registry;
use Windwalker\DI\ServiceProvider;

/**
 * Class SystemProvider
 *
 * @since 1.0
 */
class SystemProvider extends ServiceProvider
{
	/**
	 * Registers the service provider with a DI container.
	 *
	 * @param   Container $container The DI container.
	 *
	 * @return  Container  Returns itself to support chaining.
	 *
	 * @since   1.0
	 */
	public function register(Container $container)
	{
		// Global Config
		$container->share('joomla.config', array('JFactory', 'getConfig'));

		// Windwalker Config
		$container->share('windwalker.config', array($this, 'loadConfig'));

		// Database
		$this->share($container, 'db', 'JDatabaseDriver', array('JFactory', 'getDbo'));

		// Language
		$this->share($container, 'language', 'JLanguage', array('JFactory', 'getLanguage'));

		// Dispatcher
		$this->share($container, 'event.dispatcher', 'JEventDispatcher', array('JEventDispatcher', 'getInstance'));

		// Date
		$this->set($container, 'date', 'JDate', array('Windwalker\\Helper\\DateHelper', 'getDate'));

		// Global
		$container->set('SplPriorityQueue',
			function()
			{
				return new \SplPriorityQueue;
			}
		);

		// Detect deferent environment
		if (defined('WINDWALKER_CONSOLE'))
		{
			$container->registerServiceProvider(new CliProvider);
		}
		else
		{
			$container->registerServiceProvider(new WebProvider);
		}
	}

	/**
	 * loadConfig
	 *
	 * @return  Registry
	 */
	public function loadConfig()
	{
		$file = WINDWALKER . '/config.json';

		if (!is_file($file))
		{
			\JFile::copy(WINDWALKER . '/config.dist.json', $file);
		}

		$config = new Registry;

		return $config->loadFile($file, 'json');
	}
}
