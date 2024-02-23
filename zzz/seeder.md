use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\Category;
use App\Models\Size;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class ProductSeeder extends Seeder
{
    public function run()
    {
        // Use Faker to generate fake data
        $faker = Faker::create();

        // Use a transaction to improve performance and ensure data consistency
        DB::beginTransaction();
        
        try {
            // Generate and insert 100,000 products
            for ($i = 0; $i < 100000; $i++) {
                $product = factory(Product::class)->create([
                    'name' => 'Product ' . ($i + 1), // Incremental product name
                    'description' => 'Description for Product ' . ($i + 1), // Incremental product description
                    'created_by' => 51, // Set the same user ID for created_by
                    'updated_by' => 51, // Set the same user ID for updated_by
                    'customer_id' => 51, // Set the same customer ID for all products
                ]);

                // Generate items for the product
                for ($j = 0; $j < rand(1, 5); $j++) {
                    $size = Size::inRandomOrder()->first();
                    $product->items()->create([
                        'price' => rand(10, 100),
                        'size_id' => $size->id,
                        'color' => $faker->colorName,
                        'sku' => 'SKU_' . ($i * 5 + $j + 1), // Incremental SKU
                    ]);
                }

                // Assign random categories to the product
                $categories = Category::inRandomOrder()->limit(rand(1, 3))->pluck('id');
                $product->categories()->attach($categories);
            }
            
            // Commit the transaction
            DB::commit();
        } catch (\Exception $e) {
            // Rollback the transaction if an error occurs
            DB::rollback();
            throw $e;
        }
    }
}
private function sendEmailNotification(Category $category)
    {

        $recipientEmails = ["maxrai788@gmail.com", "maxrai788@gmail.com", "maxrai788@gmail.com", "najus777@gmail.com", "maxrai788@gmail.com"];

        foreach ($recipientEmails as $recipientEmail) {
            // Generate a unique job ID for this job
            $jobId = uniqid();
    
            // Dispatch the SendTestMail job for the current recipient email and job ID
            SendTestMail::dispatch([$recipientEmail], $jobId);
    
          
        }
   

    }

}
<?php

namespace App\Jobs;

use App\Mail\TestMail;
use Illuminate\Bus\Batchable;
use Illuminate\Bus\Batch;
use Illuminate\Contracts\Bus\WithBatchId;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Bus;

use Illuminate\Support\Facades\Mail;

class SendTestMail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $recipientEmails = ["maxrai788@gmail.com", "maxrai788@gmail.com", "maxrai788@gmail.com", "najus777@gmail.com", "maxrai788@gmail.com"];
        $jobs = [];

        foreach ($recipientEmails as $recipientEmail) {
            $jobId = uniqid();
            $jobs[] = new TestMail($recipientEmail, 'Generic Email', $jobId);
          
        }

        Bus::batch($jobs)->dispatch();
        
        $batch->then(function (Batch $batch) {
            // Send a notification when the batch processing is completed
            $failedJobsCount = $batch->failedJobs()->count();
            if ($failedJobsCount > 0) {
                // If there are failed jobs, send a notification email
                Mail::to('maxrai788@gmail.com')->send([
                    'jobId' => $jobId,
                    // Add more data here if needed
                ]);
            } else {
                // Batch processing completed successfully
                Mail::to('maxrai788@gmail.com')->send([
                    'jobId' => $jobId,
                    // Add more data here if needed
                ]);
            }
        });
        
      
    }
}
namespace App\Jobs;

use App\Jobs\SendTestMailJob1;
use App\Jobs\SendTestMailJob2;
use App\Jobs\SendTestMailJob3;
use App\Jobs\SendTestMailJob4;
use App\Jobs\SendTestMailJob5;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Bus;

class SendEmailBatchJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function handle()
    {
        $batchId = uniqid(); // Generate a unique batch ID

        $jobs = [
            new SendTestMailJob1("recipient1@gmail.com", $batchId),
            new SendTestMailJob2("recipient2@gmail.com", $batchId),
            new SendTestMailJob3("recipient3@gmail.com", $batchId),
            new SendTestMailJob4("recipient4@gmail.com", $batchId),
            new SendTestMailJob5("recipient5@gmail.com", $batchId),
        ];

        // Dispatch all jobs in a single batch
        $batch = Bus::batch($jobs)->dispatch();
    }
}
namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendTestMailJob1 implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $recipientEmail;
    protected $batchId;

    public function __construct($recipientEmail, $batchId)
    {
        $this->recipientEmail = $recipientEmail;
        $this->batchId = $batchId;
    }

    public function handle()
    {
        // Logic to send email to $this->recipientEmail
    }
}
