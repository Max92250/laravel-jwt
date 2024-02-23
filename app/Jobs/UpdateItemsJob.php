<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;
use App\Models\Item;

class UpdateItemsJob implements ShouldQueue
{
   /* use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function handle()
    {
        $response = Http::withHeaders([
            'apiToken' => '08d3abae99badba40441ca74519c0e11',
        ])->post('https://voxshipsapi.shikhartech.com/inventoryItems/A2S');

        if ($response->successful()) {
            // Retrieve the items from the response
            $itemsData = $response->json()['result']['customerItems'];
            // Iterate through the retrieved items
            foreach ($itemsData as $itemData) {
                $sku = $itemData['itemSkuNumber'];
                $quantity = $itemData['A2S'];
                $status = $itemData['status'];
                $mappedStatus = ($status == 1) ? 'active' : 'inactive';
                $item = Item::where('sku', $sku)->first();
                if ($item) {
                    $item->quantity = $quantity;
                    $item->status = $mappedStatus;
                    $item->save();
                }
            }
        }
    }
}
*/