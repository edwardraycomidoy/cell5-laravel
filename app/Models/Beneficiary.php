<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Beneficiary extends Model
{
	use HasFactory, SoftDeletes;

	//protected $table = 'beneficiaries';

	protected $fillable = [
		'first_name',
		'middle_initial',
		'last_name',
		'suffix',
	];
}
