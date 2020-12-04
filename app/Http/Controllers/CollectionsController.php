<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Member;
use App\Models\Collection;
use App\Models\Beneficiary;

use Carbon\Carbon;
use DB;

class CollectionsController extends Controller
{
	private $regex = '/^[1-9]+\d*$/';

	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index()
	{
		$collections = DB::table('collections AS c')
						 ->select('c.id', 'c.member_id', 'm.first_name AS member_first_name', 'm.middle_initial AS member_middle_initial', 'm.last_name AS member_last_name', 'm.suffix AS member_suffix', 'c.beneficiary_id AS claimant_id', 'b.first_name AS claimant_first_name', 'b.middle_initial AS claimant_middle_initial', 'b.last_name AS claimant_last_name', 'b.suffix AS claimant_suffix', 'c.due_on', 'c.released_on')
						 ->join('members AS m', 'm.id', '=', 'c.member_id')
						 ->leftJoin('beneficiaries AS b', 'b.id', '=', 'c.beneficiary_id')
						 ->orderBy('c.due_on', 'desc')
						 ->simplePaginate(25);
		return view('pages.collections.index', compact('collections'));
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function create()
	{
		$members = Member::orderBy('last_name', 'asc')
						 ->orderBy('first_name', 'asc')
						 ->orderBy('middle_initial', 'asc')
						 ->get();
		return view('pages.collections.create', compact('members'));
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function store(Request $request)
	{
		$this->validate($request, [
			'member_id' => 'required',
			'claimant' => 'required|in:0,1',
			'due_on' => 'required|date'
		]);

		$current_time = Carbon::now()->toDateTimeString();

		$data = [
			'member_id' => (int)$request->member_id,
			'due_on' => date('Y-m-d', strtotime($request->due_on)),
			'created_at' => $current_time,
			'updated_at' => $current_time
		];

		if((int)$request->claimant == 1)
		{
			$this->validate($request, [
				'first_name' => 'required|max:255',
				'middle_initial' => 'nullable|max:1',
				'last_name' => 'required|max:255',
				'suffix' => 'nullable|max:5'
			]);

			$id = Beneficiary::insertGetId([
				'first_name' => $request->first_name,
				'middle_initial' => $request->middle_initial,
				'last_name' => $request->last_name,
				'suffix' => $request->suffix,
				'created_at' => $current_time,
				'updated_at' => $current_time
			]);

			$data['beneficiary_id'] = $id;
		}

		$collection = Collection::create($data);

		return redirect()->route('collections.show', ['collection' => $collection]);
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function show($id)
	{
		if(preg_match($this->regex, $id) == 0)
			return redirect()->route('collections.index');

		$collection = DB::table('collections AS c')
						->select('c.id', 'c.member_id', 'm.first_name AS member_first_name', 'm.middle_initial AS member_middle_initial', 'm.last_name AS member_last_name', 'm.suffix AS member_suffix', 'c.beneficiary_id AS claimant_id', 'p.first_name AS claimant_first_name', 'p.middle_initial AS claimant_middle_initial', 'p.last_name AS claimant_last_name', 'p.suffix AS claimant_suffix', 'c.due_on', 'c.released_on')
						->join('members AS m', 'm.id', '=', 'c.member_id')
						->leftJoin('beneficiaries AS p', 'p.id', '=', 'c.beneficiary_id')
						->where('c.id', '=', $id)
						->first();

		if(is_null($collection))
			return redirect()->route('collections.index');

		$paid_members = DB::table('members AS m')
						  ->select('m.id', 'm.first_name', 'm.middle_initial', 'm.last_name', 'm.suffix', 'p.id AS payment_id', 'p.amount')
						  ->join('payments AS p', 'p.member_id', '=', 'm.id')
						  ->where('m.id', '!=', $collection->member_id)
						  ->where('p.collection_id', '=', $id)
						  ->whereNull('m.deleted_at')
						  ->whereNull('p.deleted_at')
						  ->orderBy('m.last_name', 'asc')
						  ->orderBy('m.first_name', 'asc')
						  ->orderBy('m.middle_initial', 'asc')
						  ->get();

		$exclude_ids = array_map(function($row){ return $row->id; }, $paid_members->toArray());

		$unpaid_members = DB::table('members AS m')
							->select('m.id', 'm.first_name', 'm.middle_initial', 'm.last_name', 'm.suffix')
							->where('m.id', '!=', $collection->member_id)
							->whereNotIn('m.id', $exclude_ids)
							->orderBy('m.last_name', 'asc')
							->orderBy('m.first_name', 'asc')
							->orderBy('m.middle_initial', 'asc')
							->get();

		return view('pages.collections.show', compact('collection', 'unpaid_members', 'paid_members'));
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function edit($id)
	{
		//
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function update(Request $request, $id)
	{
		//
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
