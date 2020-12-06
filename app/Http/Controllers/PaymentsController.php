<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Payment;

use Carbon\Carbon;

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

		$current_time = Carbon::now()->toDateTimeString();

		$payment = Payment::where('member_id', $request->member_id)->where('collection_id', $request->collection_id)->first();
		if(is_null($payment))
		{
			$payment = new Payment;
			$payment->member_id = $request->member_id;
			$payment->collection_id = $request->collection_id;
			$payment->amount = 150;
			$payment->created_at = $current_time;
		}
		$payment->updated_at = $current_time;
        $payment->save();

		echo json_encode([]);
	}

	public function destroy($id)
	{
		//
	}
}
