<?php


namespace App\Models\Enums;

use Spatie\Enum\Enum;

class PaymentStatus extends Enum
{
    /**
     * Pending status
     * @return PaymentStatus
     */
    public static function PENDING(): PaymentStatus {
        return new class() extends PaymentStatus {
            public function getValue(): string
            {
                return 'pending';
            }
            public function getIndex(): int
            {
                return 1;
            }
        };
    }

    /**
     * Paid status
     * @return PaymentStatus
     */
    public static function PAID(): PaymentStatus {
        return new class() extends PaymentStatus {
            public function getValue(): string
            {
                return 'paid';
            }
            public function getIndex(): int
            {
                return 2;
            }
        };
    }
}
