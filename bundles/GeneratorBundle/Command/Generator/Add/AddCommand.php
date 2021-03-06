<?php
/**
 * Part of Windwalker project.
 *
 * @copyright  Copyright (C) 2011 - 2014 SMS Taiwan, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

namespace GeneratorBundle\Command\Generator\Add;

use GeneratorBundle\Command\Generator\Add\AddItem\AddItemCommand;
use GeneratorBundle\Command\Generator\Add\AddList\AddListCommand;
use GeneratorBundle\Command\Generator\Add\Subsystem\SubsystemCommand;
use Windwalker\Console\Command\Command;

defined('WINDWALKER') or die;

/**
 * Class Add
 *
 * @since  2.0
 */
class AddCommand extends Command
{
	/**
	 * An enabled flag.
	 *
	 * @var bool
	 */
	public static $isEnabled = true;

	/**
	 * Console(Argument) name.
	 *
	 * @var  string
	 */
	protected $name = 'add';

	/**
	 * The command description.
	 *
	 * @var  string
	 */
	protected $description = 'Add new controller view model system classes(only component).';

	/**
	 * The usage to tell user how to use this command.
	 *
	 * @var string
	 */
	protected $usage = 'add <cmd><command></cmd> <option>[option]</option>';

	/**
	 * Configure command information.
	 *
	 * @return void
	 */
	public function initialise()
	{
		$this->addCommand(new AddItemCommand);
		$this->addCommand(new AddListCommand);
		$this->addCommand(new SubsystemCommand);

		parent::initialise();
	}

	/**
	 * Execute this command.
	 *
	 * @return int|void
	 */
	protected function doExecute()
	{
		return parent::doExecute();
	}
}
