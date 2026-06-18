<?php

namespace Sys\Console;

class ListCommandsCommand implements CommandInterface
{
    /**
     * @param list<CommandInterface> $commands
     */
    public function __construct(private array $commands, private string $scriptName = 'mvc')
    {
    }

    public function name(): string
    {
        return 'list';
    }

    public function description(): string
    {
        return 'List available commands.';
    }

    public function handle(array $arguments = []): int
    {
        $this->line('MVC command prompt');
        $this->line('');
        $this->line('Usage:');
        $this->line('  php ' . $this->scriptName . ' <command>');
        $this->line('');
        $this->line('Available commands:');
        $this->printCommand($this);

        foreach ($this->commands as $command) {
            $this->printCommand($command);
        }

        return 0;
    }

    private function printCommand(CommandInterface $command): void
    {
        $this->line('  ' . str_pad($command->name(), 16) . $command->description());
    }

    private function line(string $message): void
    {
        echo $message . PHP_EOL;
    }
}
