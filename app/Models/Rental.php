<?php

namespace App\Models;

use App\User;
use Illuminate\Database\Eloquent\Model;

class Rental extends Model
{

    const STATUS_A_APPLY = 1;
    const STATUS_B_CONFIRM = 5;
    const STATUS_A_CONFIRM = 10;
    const STATUS_A_COMPLETE = 15;
    const STATUS_COMPLETE = 20;
    const STATUS_REJECT = 99;

    protected $table = 'rentals';

    protected $fillable = [
        'product_id', 'a_user_id', 'b_user_id', 'a_address', 'b_address',
        'status', 'charge', 'deposit', 'reject_reason',
        'a_apply_tx_hash', 'b_confirm_tx_hash', 'a_confirm_tx_hash',
        'a_complete_apply_tx_hash', 'b_complete_tx_hash',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function aUser()
    {
        return $this->belongsTo(User::class, 'a_user_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function bUser()
    {
        return $this->belongsTo(User::class, 'b_user_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    /**
     * @return string
     */
    public function statusText()
    {
        $s = '';
        switch ($this->status) {
            case self::STATUS_A_APPLY:
                $s = 'A APPLY';
                break;
            case self::STATUS_B_CONFIRM:
                $s = 'B CONFIRM';
                break;
            case self::STATUS_A_CONFIRM:
                $s = 'A CONFIRM';
                break;
            case self::STATUS_A_COMPLETE:
                $s = 'A COMPLETE';
                break;
            case self::STATUS_COMPLETE:
                $s = 'CONFIRM';
                break;
            case self::STATUS_REJECT:
                $s = 'REJECT';
                break;
        }
        return $s;
    }

    public static function removeHash(HashResult $hashResult)
    {
        $key = '';
        switch ($hashResult->request_type) {
            case HashResult::REQUEST_TYPE_APPLY:
                $key = 'a_apply_tx_hash';
                break;
            case HashResult::REQUEST_TYPE_A_CONFIRM:
                $key = 'a_confirm_tx_hash';
                break;
            case HashResult::REQUEST_TYPE_B_CONFIRM:
                $key = 'b_confirm_tx_hash';
                break;
            case HashResult::REQUEST_TYPE_B_COMPLETE:
                $key = 'b_complete_tx_hash';
                break;
            case HashResult::REQUEST_TYPE_A_COMPLETE:
                $key = 'a_complete_apply_tx_hash';
                break;
        }
        if (!$key) {
            return;
        }
        $rental = self::where($key, $hashResult->tx_hash)->first();
        if ($rental) {
            $rental->$key = '';
            $rental->save();
        }
    }

}
