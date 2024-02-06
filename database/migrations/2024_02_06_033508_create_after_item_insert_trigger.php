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
        DB::unprepared('
            CREATE TRIGGER after_item_insert
            AFTER INSERT ON items FOR EACH ROW
            BEGIN
                DECLARE size_name VARCHAR(255);
              
                SELECT name INTO size_name FROM sizes WHERE id = NEW.size_id ;

              
                SET NEW.name = size_name;
            END
        ');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::unprepared('DROP TRIGGER IF EXISTS after_item_insert');
    }
}
