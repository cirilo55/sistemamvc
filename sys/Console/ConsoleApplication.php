<?php

namespace Sys\Console;

class ConsoleApplication
{
    private string $scriptName = 'mvc';

    /**
     * @param list<CommandInterface> $commands
     */
    public function __construct(private array $commands)
    {
    }

    public function run(array $argv): int
    {
        $this->scriptName = basename($argv[0] ?? 'mvc');
        $commandName = $argv[1] ?? 'list';
        $arguments = array_slice($argv, 2);
        $command = $this->find($commandName);

        if ($command === null) {
            $this->line("Command not found: {$commandName}");
            $this->line('');
            $this->printCommands();

            return 1;
        }

        return $command->handle($arguments);
    }

    private function find(string $name): ?CommandInterface
    {
        if ($name === 'list') {
            return new ListCommandsCommand($this->commands, $this->scriptName);
        }

        foreach ($this->commands as $command) {
            if ($command->name() === $name) {
                return $command;
            }
        }

        return null;
    }

    private function printCommands(): void
    {
        (new ListCommandsCommand($this->commands, $this->scriptName))->handle();
    }

    private function line(string $message): void
    {
        echo $message . PHP_EOL;
    }
}
