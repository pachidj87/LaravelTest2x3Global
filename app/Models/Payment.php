<?php

namespace App\Models;

use App\Models\Enums\PaymentStatus;
use Carbon\Traits\Timestamp;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
use Spatie\Enum\Laravel\HasEnums;

/**
 * @SWG\Definition(
 *      definition="Payment",
 *      required={"expires_at", "user_id", "clp_usd"},
 *      @SWG\Property(
 *          property="id",
 *          description="id",
 *          type="integer",
 *          format="int32"
 *      ),
 *      @SWG\Property(
 *          property="uuid",
 *          description="uuid",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="payment_date",
 *          description="payment_date",
 *          type="string",
 *          format="date"
 *      ),
 *      @SWG\Property(
 *          property="expires_at",
 *          description="expires_at",
 *          type="string",
 *          format="date"
 *      ),
 *      @SWG\Property(
 *          property="status",
 *          description="status",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="user_id",
 *          description="user_id",
 *          type="integer",
 *          format="int32"
 *      ),
 *      @SWG\Property(
 *          property="clp_usd",
 *          description="clp_usd",
 *          type="integer",
 *          format="int32"
 *      ),
 *      @SWG\Property(
 *          property="created_at",
 *          description="created_at",
 *          type="string",
 *          format="date-time"
 *      ),
 *      @SWG\Property(
 *          property="updated_at",
 *          description="updated_at",
 *          type="string",
 *          format="date-time"
 *      )
 * )
 */
class Payment extends Model
{
    use SoftDeletes;
    use Timestamp;
    use HasEnums;

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'uuid';

    /**
     * The "type" of the primary key ID.
     *
     * @var string
     */
    protected $keyType = 'string';

    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = false;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    public $table = 'payments';

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['created_at', 'updated_at', 'deleted_at'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    public $fillable = [
        'uuid',
        'payment_date',
        'expires_at',
        'status',
        'user_id',
        'clp_usd'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'payment_date' => 'date',
        'expires_at' => 'date',
        'status' => 'string',
        'user_id' => 'integer',
        'clp_usd' => 'float'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'payment_date' => 'nullable|date',
        'expires_at' => 'required|date',
        'user_id' => 'required|integer',
        'clp_usd' => 'nullable|numeric'
    ];

    protected $enums = [
        'status' => PaymentStatus::class
    ];

    public static function boot()
    {
        parent::boot();

        self::creating(function ($model) {
            $model->setAttribute('uuid', Str::uuid());
        });
    }

    /**
     * Relation with Clients
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function client() {
        return $this->belongsTo(Client::class, 'user_id');
    }
}
