<?php

namespace ModularShopify\ModularShopify\Commands;

use Illuminate\Console\Command;

class ModularShopifyCommand extends Command
{
    public $signature = 'modular-middleware-shopify';

    public $description = 'My command';

    public function handle(): int
    {
        $this->comment('All done');

        return self::SUCCESS;
    }
}
