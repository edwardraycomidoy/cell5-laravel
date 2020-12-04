<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Payment;

use Carbon\Carbon;
//use DB;

class PaymentsController extends Controller
{
	private $regex = '/^[1-9]+\d*$/';

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function store(Request $request)
	{
		if(preg_match($this->regex, $request->member_id) == 0 || preg_match($this->regex, $request->collection_id) == 0)
		{
			echo json_encode([]);
			die;
		}

		$current_time = Carbon::now()->toDateTimeString();

		$payment = new Payment;
        $payment->member_id = $request->member_id;
        $payment->collection_id = $request->collection_id;
        $payment->amount = 150;
        $payment->created_at = $current_time;
        $payment->updated_at = $current_time;
        $payment->save();

		echo json_encode([]);

		/*
		$this->validate($request, [
			'collection_id' => 'required|numeric',
			'member_id' => 'required|numeric'
		]);

		if(preg_match($this->regex, $request->member_id) == 0)
			return redirect()->route('members.index');

		if(preg_match($this->regex, $request->collection_id) == 0)
			return redirect()->route('members.index');

		$current_time = Carbon::now()->toDateTimeString();

		$payment = new Payment;
        $payment->member_id = $request->member_id;
        $payment->collection_id = $request->collection_id;
        $payment->amount = 150;
        $payment->created_at = $current_time;
        $payment->updated_at = $current_time;
        $payment->save();

		//return redirect()->route('members.show', ['member' => $request->member_id]);
		return back();
		*/
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function destroy($id)
	{
		//
	}
}


/*
		$this->validate($request, [
			'collection_id' => 'required|numeric',
			'member_id' => 'required|numeric'
		]);

		$member_id = $request->member_id;
		if(preg_match($this->regex, $member_id) == 0)
			return redirect()->route('collections.index');

		$collection_id = $request->collection_id;
		if(preg_match($this->regex, $collection_id) == 0)
			return redirect()->route('collections.index');

		$current_time = Carbon::now()->toDateTimeString();

		DB::table('payments')->insert([
			'member_id' => $member_id,
			'collection_id' => $collection_id,
			'amount' => 150,
			'created_at' => $current_time,
			'updated_at' => $current_time
		]);

		return redirect()->route('collections.show', ['collection' => $collection_id]);
	}

	public function destroy($id)
	{
		if(preg_match($this->regex, $id) == 0)
			return redirect()->route('collections.index');

		$payment = DB::table('payments AS p')
					 ->select('p.collection_id')
					 ->join('collections AS c', 'c.id', '=', 'p.collection_id')
					 ->where('p.id', '=', $id)
					 ->first();

		if(is_null($payment))
			return redirect()->route('collections.index');

		DB::table('payments')
		  ->where('id', '=', $id)
		  ->update(['deleted_at' => Carbon::now()->toDateTimeString()]);

		return redirect()->route('collections.show', ['collection' => $payment->collection_id]);



*/