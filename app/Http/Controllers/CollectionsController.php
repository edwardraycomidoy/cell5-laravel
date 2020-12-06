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

	public function __construct()
	{
		$this->middleware(['auth']);
	}

	public function index()
	{
		$sql = '(SELECT `id` FROM `'. DB::getTablePrefix() . 'beneficiaries` WHERE `id` = `'. DB::getTablePrefix() . 'c`.`beneficiary_id` AND `deleted_at` IS NULL LIMIT 1)';

		$collections = DB::table('collections AS c')
						 ->select('c.id', 'c.member_id', 'm.first_name AS member_first_name', 'm.middle_initial AS member_middle_initial', 'm.last_name AS member_last_name', 'm.suffix AS member_suffix', 'c.beneficiary_id AS claimant_id', 'b.first_name AS claimant_first_name', 'b.middle_initial AS claimant_middle_initial', 'b.last_name AS claimant_last_name', 'b.suffix AS claimant_suffix', 'c.due_on', 'c.released_on')
						 ->join('members AS m', 'm.id', '=', 'c.member_id')
						 ->leftJoin('beneficiaries AS b', 'b.id', '=', DB::raw($sql))
						 ->whereNull('c.deleted_at')
						 ->whereNull('m.deleted_at')
						 ->orderBy('c.due_on', 'desc')
						 ->simplePaginate(25);
		return view('pages.collections.index', compact('collections'));
	}

	public function create()
	{
		$members = Member::orderBy('last_name', 'asc')
						 ->orderBy('first_name', 'asc')
						 ->orderBy('middle_initial', 'asc')
						 ->get();
		return view('pages.collections.create', compact('members'));
	}

	public function store(Request $request)
	{
		$this->validate($request, [
			'member_id' => 'required',
			'claimant' => 'required|in:0,1',
			'due_on' => 'required|date'
		]);

		$current_time = Carbon::now()->toDateTimeString();

		$collection = new Collection;
		$collection->member_id = (int)$request->member_id;
		$collection->due_on = date('Y-m-d', strtotime($request->due_on));
		$collection->created_at = $current_time;
		$collection->updated_at = $current_time;

		if((int)$request->claimant == 1)
		{
			$this->validate($request, [
				'first_name' => 'required|max:255',
				'middle_initial' => 'nullable|max:1',
				'last_name' => 'required|max:255',
				'suffix' => 'nullable|max:5'
			]);

			$beneficiary = new Beneficiary;
			$beneficiary->first_name = $request->first_name;
			$beneficiary->middle_initial = $request->middle_initial;
			$beneficiary->last_name = $request->last_name;
			$beneficiary->suffix = $request->suffix;
			$beneficiary->created_at = $current_time;
			$beneficiary->updated_at = $current_time;
			$beneficiary->save();

			$collection->beneficiary_id = $beneficiary->id;
		}

		$collection->save();

		return redirect()->route('collections.show', ['collection' => $collection]);
	}

	public function show($id)
	{
		if(preg_match($this->regex, $id) == 0)
			return redirect()->route('collections.index');

		$sql = '(SELECT `id` FROM `'. DB::getTablePrefix() . 'beneficiaries` WHERE `id` = `'. DB::getTablePrefix() . 'c`.`beneficiary_id` AND `deleted_at` IS NULL LIMIT 1)';

		$collection = DB::table('collections AS c')
						->select('c.id', 'c.member_id', 'm.first_name AS member_first_name', 'm.middle_initial AS member_middle_initial', 'm.last_name AS member_last_name', 'm.suffix AS member_suffix', 'c.beneficiary_id AS claimant_id', 'b.first_name AS claimant_first_name', 'b.middle_initial AS claimant_middle_initial', 'b.last_name AS claimant_last_name', 'b.suffix AS claimant_suffix', 'c.due_on', 'c.released_on')
						->join('members AS m', 'm.id', '=', 'c.member_id')
						->leftJoin('beneficiaries AS b', 'b.id', '=', DB::raw($sql))
						->where('c.id', '=', $id)
						->whereNull('c.deleted_at')
						->whereNull('m.deleted_at')
						->first();

		if(is_null($collection))
			return redirect()->route('collections.index');

		$sql = '(SELECT `id` FROM `'. DB::getTablePrefix() . 'payments` WHERE `member_id` = `'. DB::getTablePrefix() . 'm`.`id` AND `collection_id` = '. $id . ' AND `deleted_at` IS NULL ORDER BY `created_at` DESC LIMIT 1)';

		$order = 'if(`'. DB::getTablePrefix() . "p`.`id` IS NOT NULL, true, false)";

		$select = "{$order} AS `paid`";

		$members = DB::table('members AS m')
					 ->select('m.id', 'm.first_name', 'm.middle_initial', 'm.last_name', 'm.suffix', DB::raw($select))
					 ->leftJoin('payments AS p', 'p.id', '=', DB::raw($sql))
					 ->whereNull('m.deleted_at')
					 ->orderBy(DB::raw($order), 'asc')
					 ->orderBy('m.last_name', 'asc')
					 ->orderBy('m.first_name', 'asc')
					 ->orderBy('m.middle_initial', 'asc')
					 ->simplePaginate(20);

		return view('pages.collections.show', compact('collection', 'members'));
	}

	public function edit($id)
	{
		if(preg_match($this->regex, $id) == 0)
			return redirect()->route('collections.index');

		$sql = '(SELECT `id` FROM `'. DB::getTablePrefix() . 'beneficiaries` WHERE `id` = `'. DB::getTablePrefix() . 'c`.`beneficiary_id` AND `deleted_at` IS NULL LIMIT 1)';

		$collection = DB::table('collections AS c')
						->select('c.id', 'c.member_id', 'm.first_name AS member_first_name', 'm.middle_initial AS member_middle_initial', 'm.last_name AS member_last_name', 'm.suffix AS member_suffix', 'c.beneficiary_id AS claimant_id', 'b.first_name AS claimant_first_name', 'b.middle_initial AS claimant_middle_initial', 'b.last_name AS claimant_last_name', 'b.suffix AS claimant_suffix', 'c.due_on', 'c.released_on')
						->join('members AS m', 'm.id', '=', 'c.member_id')
						->leftJoin('beneficiaries AS b', 'b.id', '=', DB::raw($sql))
						->where('c.id', '=', $id)
						->whereNull('c.deleted_at')
						->whereNull('m.deleted_at')
						->first();

		if(is_null($collection))
			return redirect()->route('collections.index');

		$members = Member::orderBy('last_name', 'asc')
						 ->orderBy('first_name', 'asc')
						 ->orderBy('middle_initial', 'asc')
						 ->get();
		return view('pages.collections.edit', compact('collection', 'members'));
	}

	public function update(Request $request, $id)
	{
		if(preg_match($this->regex, $id) == 0)
			return redirect()->route('collections.index');

		$sql = '(SELECT `id` FROM `'. DB::getTablePrefix() . 'beneficiaries` WHERE `id` = `'. DB::getTablePrefix() . 'c`.`beneficiary_id` AND `deleted_at` IS NULL LIMIT 1)';

		$collection = DB::table('collections AS c')
						->select('c.id', 'c.member_id', 'm.first_name AS member_first_name', 'm.middle_initial AS member_middle_initial', 'm.last_name AS member_last_name', 'm.suffix AS member_suffix', 'b.id AS claimant_id', 'b.first_name AS claimant_first_name', 'b.middle_initial AS claimant_middle_initial', 'b.last_name AS claimant_last_name', 'b.suffix AS claimant_suffix', 'c.due_on', 'c.released_on')
						->join('members AS m', 'm.id', '=', 'c.member_id')
						->leftJoin('beneficiaries AS b', 'b.id', '=', DB::raw($sql))
						->where('c.id', '=', $id)
						->whereNull('c.deleted_at')
						->whereNull('m.deleted_at')
						->first();

		if(is_null($collection))
			return redirect()->route('collections.index');

		$current_time = Carbon::now()->toDateTimeString();

		$collection = Collection::find($id);
		$collection->member_id = (int)$request->member_id;
		$collection->due_on = date('Y-m-d', strtotime($request->due_on));
		$collection->updated_at = $current_time;

		$beneficiary_id = null;

		if((int)$request->claimant == 1)
		{
			$this->validate($request, [
				'first_name' => 'required|max:255',
				'middle_initial' => 'nullable|max:1',
				'last_name' => 'required|max:255',
				'suffix' => 'nullable|max:5'
			]);

			$beneficiary = is_null($collection->beneficiary_id) ? new Beneficiary : Beneficiary::find($collection->beneficiary_id);

			$beneficiary->first_name = $request->first_name;
			$beneficiary->middle_initial = $request->middle_initial;
			$beneficiary->last_name = $request->last_name;
			$beneficiary->suffix = $request->suffix;
			$beneficiary->updated_at = $current_time;

			if(is_null($collection->beneficiary_id))
				$beneficiary->created_at = $current_time;

			$beneficiary->save();

			$beneficiary_id = is_null($collection->beneficiary_id) ? $beneficiary->id : $collection->beneficiary_id;
		}

		$collection->beneficiary_id = $beneficiary_id;
		$collection->save();

		return redirect()->route('collections.show', ['collection' => $id]);
	}

	public function destroy($id)
	{
		
	}
}
