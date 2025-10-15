<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CmsHistoryBuyebook extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'tb_history_buyebook';

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    protected $dates = ['buy_date'];

    public static function isReadValid($customerId, $documentId)
    {
        $latestRecord = self::where('customer_id', $customerId)
                            ->where('document_id', $documentId)
                            ->where('status', 1)
                            ->orderBy('buy_date', 'desc')
                            ->orderBy('id', 'desc')
                            ->first();

        if ($latestRecord) {
            $expiryDate = $latestRecord->buy_date->addDays($latestRecord->time_read);
            return now()->lessThanOrEqualTo($expiryDate);
        }

        return false;
    }

    public static function buyEbookUser($customerId)
    {
        $buyEbookUser = self::where('customer_id', $customerId)
                            ->where('status', 1)
                            ->orderBy('buy_date', 'desc')
                            ->orderBy('id', 'desc')
                            ->with('document')
                            ->get();
        return $buyEbookUser;
    }

    public function document()
    {
        return $this->belongsTo(Document::class, 'document_id')->where('status', 1);
    }
}
