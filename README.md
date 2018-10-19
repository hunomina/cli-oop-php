# CLI OOP PHP

Description : Implementation of CLI classes for PHP7.1 or higher.

This class can be used in a php file called by a CLI with input redirection.

## Console

The [hunomina\Console\Console](https://github.com/hunomina/cli-oop-php/blob/master/src/Console/Console.php) class allow you to create script that you can use for background tasks, cron tasks...

To instantiate a Console, you have to pass a folder path to the constructor. This path is where your commands locate.

The folder is then sort out and only files will be parsed.

The Console parses all the files and store all class implementing the [hunomina\Command\CommandInterface](https://github.com/hunomina/cli-oop-php/blob/master/src/Command/CommandInterface.php) in the *$_commands* attribute

The *execute($params)* method allow you to execute commands.

It the parameters match a command, it will execute it.

If it does not, it will return a failure message.

If *$params* is null, an empty array or equals to "\['help'\]", the console will print all the available commands.

The console will also print a message based on the command success.

## CommandInterface

The [hunomina\Command\CommandInterface](https://github.com/hunomina/cli-oop-php/blob/master/src/Command/CommandInterface.php) has 3 methods :

- *array getCommand()* : Return a list of string composing the command
- *string getDescription()* : Return the string description of the command (used by the help command)
- *bool execute()* : Logic of the command. Return *true* on success and *false* on failure

## Examples

See [/tests](https://github.com/hunomina/cli-oop-php/tree/master/tests) for examples