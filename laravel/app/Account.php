<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Account extends Model
{
  protected $fillable = [
    'title',
    'agency',
    'account_number',
    'balance',
    'initial_balance',
    'bank_id'
  ];

  protected $table = 'accounts';

  public function bank() {
    return $this->belongsTo('App\Bank');
  }

}
