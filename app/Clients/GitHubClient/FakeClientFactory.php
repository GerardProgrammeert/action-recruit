<?php

namespace App\Clients\GitHubClient;

use App\Clients\ClientFactoryInterface;
use GuzzleHttp\HandlerStack;
use Tests\Feature\Fixtures\FakeClients\GitHub\GitHubFakeClient;

class FakeClientFactory extends AbstractClientFactory implements ClientFactoryInterface
{
    public function getStack(): HandlerStack
    {
        $stack = HandlerStack::create();

        $stack->setHandler(new GitHubFakeClient());

        return $stack;
    }
}
