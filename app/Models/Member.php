<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Member extends Model
{
	use HasFactory, SoftDeletes;

	protected $fillable = [
		'first_name',
		'middle_initial',
		'last_name',
		'suffix',
		'joined_on'
	];
}
