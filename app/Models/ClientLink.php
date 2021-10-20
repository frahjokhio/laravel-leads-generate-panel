<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ClientLink extends Model
{
	use SoftDeletes;
	protected $table = "client_generated_links";

	protected $dates = ['link_expire'];

	public function client()
    {
        return $this->belongsTo('App\Models\Client');
    }
}