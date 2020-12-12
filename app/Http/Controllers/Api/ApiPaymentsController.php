<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Payment;

class ApiPaymentsController extends Controller
{
	private $regex = '/^[1-9]+\d*$/';

	public function store(Request $request)
	{
		if(preg_match($this->regex, $request->member_id) == 0 || preg_match($this->regex, $request->collection_id) == 0)
			return response()->json([]);

		$payment = Payment::where('member_id', $request->member_id)
						  ->where('collection_id', $request->collection_id)
						  ->first();
		if(is_null($payment))
		{
			$payment = Payment::create([
				'member_id' => $request->member_id,
				'collection_id' => $request->collection_id,
				'amount' => 150
			]);
		}

		return response()->json([]);
	}

	public function destroy(Request $request)
	{
		if(preg_match($this->regex, $request->member_id) == 0 || preg_match($this->regex, $request->collection_id) == 0)
			return response()->json([]);

		Payment::where('member_id', $request->member_id)
			   ->where('collection_id', $request->collection_id)
			   ->delete();

		return response()->json([]);
	}
}
