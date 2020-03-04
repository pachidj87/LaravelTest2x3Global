<?php namespace Tests\Repositories;

use App\Models\Payment;
use App\Repositories\PaymentRepository;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\ApiTestTrait;

class PaymentsRepositoryTest extends TestCase
{
    use ApiTestTrait, DatabaseTransactions;

    /**
     * @var PaymentRepository
     */
    protected $paymentsRepo;

    public function setUp() : void
    {
        parent::setUp();
        $this->paymentsRepo = \App::make(PaymentRepository::class);
    }

    /**
     * @test create
     */
    public function test_create_payments()
    {
        $payments = factory(Payment::class)->make()->toArray();

        $createdPayments = $this->paymentsRepo->create($payments);

        $createdPayments = $createdPayments->toArray();
        $this->assertArrayHasKey('uuid', $createdPayments);
        $this->assertNotNull($createdPayments['uuid'], 'Created Payments must have uuid specified');
        $this->assertNotNull(Payment::find($createdPayments['uuid']), 'Payments with given uuid must be in DB');
        $this->assertModelData($payments, $createdPayments);
    }

    /**
     * @test read
     */
    public function test_read_payments()
    {
        $payments = factory(Payment::class)->create();

        $dbPayments = $this->paymentsRepo->find($payments->id);

        $dbPayments = $dbPayments->toArray();
        $this->assertModelData($payments->toArray(), $dbPayments);
    }

    /**
     * @test update
     */
    public function test_update_payments()
    {
        $payments = factory(Payment::class)->create();
        $fakePayments = factory(Payment::class)->make()->toArray();

        $updatedPayments = $this->paymentsRepo->update($fakePayments, $payments->id);

        $this->assertModelData($fakePayments, $updatedPayments->toArray());
        $dbPayments = $this->paymentsRepo->find($payments->id);
        $this->assertModelData($fakePayments, $dbPayments->toArray());
    }

    /**
     * @test delete
     */
    public function test_delete_payments()
    {
        $payments = factory(Payment::class)->create();

        $resp = $this->paymentsRepo->delete($payments->id);

        $this->assertTrue($resp);
        $this->assertNull(Payment::find($payments->id), 'Payments should not exist in DB');
    }
}
