<?php

use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::unprepared('
        CREATE TRIGGER after_place_order_trigger AFTER INSERT ON orders
        FOR EACH ROW
        BEGIN
            DECLARE initial_amount DECIMAL(10, 2);
            DECLARE added_amount DECIMAL(10, 2);
            DECLARE final_amount DECIMAL(10, 2);

            SELECT SUM(amount) INTO initial_amount
            FROM credit_member
            WHERE member_id = NEW.member_id;


            -- Get added amount (total of the new order)
            SELECT NEW.total INTO added_amount;

            -- Calculate final amount
            SET final_amount = initial_amount - added_amount;

            -- Insert into credit_logs table
            INSERT INTO credit_logs (member_id, initial_amount, added_amount, final_amount, created_at, updated_at)
            VALUES (NEW.member_id, initial_amount, added_amount, final_amount, NOW(), NOW());
        END
    ');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::unprepared('DROP TRIGGER after_place_order_trigger');
    }
};
