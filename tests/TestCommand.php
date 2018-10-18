<?php

use hunomina\Command\CommandInterface;

class TestCommand implements CommandInterface
{
    /**
     * @return array of each words composing the command
     */
    public function getCommand(): array
    {
        return ['test'];
    }

    /**
     * @return string description of the command
     */
    public function getDescription(): string
    {
        return 'It\'s a test command';
    }

    /**
     * @return bool on success
     */
    public function execute(): bool
    {
        print "test\n";
        return true;
    }
}