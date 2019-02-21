<?php

ini_set('display_errors', true);

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/TestCommand.php'; // require because is not autoloaded

use hunomina\Console\Console;
use hunomina\Console\ConsoleException;
use PHPUnit\Framework\TestCase;

class ConsoleTest extends TestCase
{
    private const COMMAND_FOLDER = __DIR__;

    /**
     * @throws ReflectionException
     * @throws ConsoleException
     */
    public function testInstanciation(): void
    {
        $console = new Console(self::COMMAND_FOLDER);
        $this->assertInstanceOf(Console::class, $console);
        $this->assertNotEmpty($console->getCommands());
    }

    /**
     * @throws ReflectionException
     * @throws ConsoleException
     */
    public function testExecuteCommand(): void
    {
        $console = new Console(self::COMMAND_FOLDER);

        $this->assertTrue($console->execute());
        $this->assertTrue($console->execute(['help'])); // same thing

        $this->assertTrue($console->execute(['test']));
    }
}
