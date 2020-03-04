<?php

namespace App\Jobs;

use App\Models\DailyExchange;
use App\Models\Payment;
use GuzzleHttp\Client as HttpClient;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SetDailyExchange implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Target Payment to update
     *
     * @var Payment
     */
    protected $target_payment;

    /**
     * The number of times the job may be attempted.
     *
     * @var int
     */
    public $tries = 2;

    /**
     * Delete the job if its models no longer exist.
     *
     * @var bool
     */
    public $deleteWhenMissingModels = true;

    /**
     * Create a new job instance.
     *
     * @param Payment $payment
     */
    public function __construct (Payment $payment)
    {
        $this->target_payment = $payment;
    }

    /**
     * Determine the time at which the job should timeout.
     *
     * @return \DateTime
     */
    public function retryUntil ()
    {
        return now()->addSeconds(5);
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle ()
    {
        try {
            $daily_exchange = DailyExchange::query()->whereDate('created_at', date('Y-m-d'))->first();

            if (!$daily_exchange) {
                $client = new HttpClient([
                    'base_uri' => env('MINDICATOR_API_URL')
                ]);

                $response = $client->request('GET', 'dolar/' . date('d-m-Y'));

                $response_data = json_decode($response->getBody()->getContents());

                if (empty($response_data->serie[0])) {
                    throw new \Exception('Data not available');
                }

                $daily_exchange = DailyExchange::create([
                    'exchange_value' => $response_data->serie[0]->valor
                ]);
            }

            $this->target_payment->clp_usd = $daily_exchange->exchange_value;
            $this->target_payment->update();
        } catch (\Exception $e) {
            $this->fail($e);
        }
    }
}
