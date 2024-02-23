<?php
// app/Listeners/SetSizeName.php

namespace App\Listeners;

use App\Events\ItemCreated;
use App\Models\Size;
use Illuminate\Contracts\Queue\ShouldQueue;

class SetSizeName implements ShouldQueue
{
    public function handle(ItemCreated $event)
    {
        $item = $event->item;

        // Retrieve the size name based on the size_id
        $size = Size::find($item->size_id);

        if ($size) {
            // Set the name value for the new item
            $item->name = $size->name;
            $item->save();
        }
    }
}

