<?php

// app/Providers/EventServiceProvider.php

namespace App\Providers;

use App\Events\ItemCreated;
use App\Listeners\SetSizeName;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        ItemCreated::class => [
            SetSizeName::class,
        ],
    ];

    public function boot()
    {
        parent::boot();
    }
}
