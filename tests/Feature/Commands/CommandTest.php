<?php

namespace Tests\Feature\Commands;

use Illuminate\Testing\PendingCommand;
use Tests\Feature\FeatureTestCase;

class CommandTest extends FeatureTestCase
{
    protected string $signature;

    /**
     * @param array<string, string|int> $arguments
     */
    private function getPendingCommand(string $signature, array $arguments)
    {
        $command = $this->artisan($signature, $arguments);
        $this->assertInstanceOf(PendingCommand::class, $command);

        return $command;
    }

    /***
     * @param array<string, string|int> $arguments
     */
    protected function getCommand(array $arguments = []): PendingCommand
    {
        return $this->getPendingCommand($this->signature, $arguments);
    }
}
