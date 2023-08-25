<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use App\Models\FunctionModel;

class DBModel extends Model
{
    protected $table = 'rzd_requests';
    public $timestamps = true;
    protected $fillable = [
		'train',
        'id',
        'created_at'
	];

}
