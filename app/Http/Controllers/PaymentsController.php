<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Payment;

class PaymentsController extends Controller
{
	private $regex = '/^[1-9]+\d*$/';

	public function __construct()
	{
		$this->middleware(['auth']);
	}

	public function store(Request $request)
	{
		if(preg_match($this->regex, $request->member_id) == 0 || preg_match($this->regex, $request->collection_id) == 0)
		{
			echo json_encode([]);
			die;
		}

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

		echo json_encode([]);
	}

	public function destroy(Request $request)
	{
		if(preg_match($this->regex, $request->member_id) == 0 || preg_match($this->regex, $request->collection_id) == 0)
		{
			echo json_encode([]);
			die;
		}

		Payment::where('member_id', $request->member_id)
			   ->where('collection_id', $request->collection_id)
			   ->delete();

		echo json_encode([]);
	}
}
