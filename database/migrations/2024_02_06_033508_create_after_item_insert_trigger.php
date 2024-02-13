<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAfterItemInsertTrigger extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::unprepared('DROP TRIGGER IF EXISTS after_item_insert');
    }
}
