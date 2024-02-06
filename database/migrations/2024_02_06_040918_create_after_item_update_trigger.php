<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::unprepared('
            CREATE TRIGGER after_item_update
            AFTER UPDATE ON items FOR EACH ROW
            BEGIN
                DECLARE size_name VARCHAR(255);

                
                SELECT name INTO size_name FROM sizes WHERE id = NEW.size_id;

             
                SET NEW.name = size_name;
            END
        ');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('after_item_update_trigger');
    }
};
