<?php

namespace hunomina\Command;

/**
 * Interface CommandInterface
 * @package hunomina\Command
 */
interface CommandInterface
{
    /**
     * @return array of each words composing the command
     */
    public function getCommand(): array;

    /**
     * @return string description of the command
     */
    public function getDescription(): string;

    /**
     * @return bool on success
     */
    public function execute(): bool;
}