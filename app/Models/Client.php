<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Client extends Model
{
	use SoftDeletes;

	public function client_links()
    {
        return $this->hasMany('App\Models\ClientLink');
    }

    public function client_files()
    {
        return $this->hasMany('App\Models\File');
    }

}