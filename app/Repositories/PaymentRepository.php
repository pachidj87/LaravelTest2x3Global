<?php

namespace App\Repositories;

use App\Models\Payment;

/**
 * Class PaymentRepository
 * @package App\Repositories
 * @version March 3, 2020, 9:52 pm UTC
*/

class PaymentRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'uuid',
        'payment_date',
        'expires_at',
        'status',
        'user_id',
        'clp_usd'
    ];

    /**
     * Return searchable fields
     *
     * @return array
     */
    public function getFieldsSearchable()
    {
        return $this->fieldSearchable;
    }

    /**
     * Configure the Model
     **/
    public function model()
    {
        return Payment::class;
    }
}
