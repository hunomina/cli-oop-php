<?php

namespace hunomina\Console;

use hunomina\Command\CommandInterface;

/**
 * Class Console
 * @package hunomina\Console
 */
class Console
{
    /** @var array $_commands */
    private $_commands = [];

    /**
     * Console constructor.
     * @param string $path
     * @throws ConsoleException
     * @throws \ReflectionException
     */
    public function __construct(string $path)
    {
        if (is_dir($path)) {
            $files = scandir($path, SCANDIR_SORT_NONE);
            $commands = [];
            foreach ($files as $file) {
                if ($file !== '.' && $file !== '..' && is_file($path . '/' . $file)) {
                    $command = $this->getCommandFromFile($path . '/' . $file);
                    if ($command !== null) {
                        $commands[] = $command;
                    }
                }
            }

            $this->_commands = $commands;
        } else {
            throw new ConsoleException('This path does not exist or is not a folder');
        }
    }

    /**
     * @param string $file
     * @return CommandInterface|null
     * @throws \ReflectionException
     */
    private function getCommandFromFile(string $file): ?CommandInterface
    {
        $class = $this->getClassFromFile($file);
        if ($class !== null && class_exists($class)) {
            $reflexion = new \ReflectionClass($class);
            if ($reflexion->implementsInterface(CommandInterface::class)) {
                return new $class();
            }
        }

        return null;
    }

    /**
     * Use Tokenizer to find class (+namespace) into file
     * Return the first class found in the file
     * @param string $file
     * @return null|string
     */
    private function getClassFromFile(string $file): ?string
    {
        $fileContent = @file_get_contents($file);

        $fqn = null;
        if ($fileContent){
            $class = $namespace = '';

            $tokens = token_get_all($fileContent);

            $countTokens = \count($tokens);
            for ($i = 0; $i < $countTokens; $i++) {
                if ($tokens[$i][0] === T_NAMESPACE) {
                    for ($j = $i + 1; $j < $countTokens; $j++) {
                        if ($tokens[$j][0] === T_STRING) {
                            $namespace .= '\\' . $tokens[$j][1];
                        } else if ($tokens[$j] === '{' || $tokens[$j] === ';') {
                            break;
                        }
                    }
                }

                if ($tokens[$i][0] === T_CLASS) {
                    for ($j = $i + 1; $j < $countTokens; $j++) {
                        if ($tokens[$j] === '{') {
                            $class = $tokens[$i + 2][1];
                        }
                    }
                }
            }

            if ($class !== '') {
                if ($namespace !== '') {
                    $fqn = $namespace . '\\' . $class;
                } else {
                    $fqn = $class;
                }
            }
        }

        return $fqn;
    }

    /**
     * Find a command based on the parameters and then execute it
     * @param array $params
     * @return bool => success
     */
    public function execute(array $params = []): bool
    {
        if ($params === ['help'] || \count($params) === 0) {
            $this->printCommands();
            return true;
        }

        $command = $this->getCommand($params);
        if ($command instanceof CommandInterface) {
            if ($command->execute()) {
                print "Command succeed.\n";
                return true;
            }

            print "Command failed.\n";
            return false;
        }

        print "Command not found.\n";
        return false;
    }

    /**
     * Display all available commands
     */
    private function printCommands(): void
    {
        if (\count($this->_commands) === 0) {
            print "There is no commands to execute :\n";
        } else {
            print "These commands are available :\n";
            /** @var CommandInterface $command */
            foreach ($this->_commands as $command) {
                print '- ' . implode(' ', $command->getCommand()) . ' : ' . $command->getDescription() . "\n";
            }
        }
    }

    /**
     * Find a command based on the parameters
     * @param array $params
     * @return CommandInterface|null
     */
    private function getCommand(array $params): ?CommandInterface
    {
        /** @var CommandInterface $command */
        foreach ($this->_commands as $command) {
            if ($params === $command->getCommand()) {
                return $command;
            }
        }

        return null;
    }
}