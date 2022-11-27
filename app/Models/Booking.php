<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */

    public function member()
    {
        return $this->belongsTo('App\Models\Member', 'member_id');
    }

    public function product()
    {
        return $this->belongsTo('App\Models\Product', 'product_id');
    }

    public function status()
    {
        return $this->belongsTo('App\Models\Status');
    }
}
