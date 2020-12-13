<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Member;
use App\Models\Collection;
use App\Models\Beneficiary;

use DB;

class ApiCollectionsController extends Controller
{
	private $regex = '/^[1-9]+\d*$/';

	private $per_page = 20;

	private $validation_rules_1 = [
		'member_id' => 'required',
		'claimant' => 'required|in:0,1',
		'due_on' => 'required|date'
	];

	private $validation_rules_2 = [
		'first_name' => 'required|max:255',
		'middle_initial' => 'nullable|max:1',
		'last_name' => 'required|max:255',
		'suffix' => 'nullable|max:5'
	];

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
						 ->paginate($this->per_page);

		return response()->json(['collections' => $collections]);
	}

	public function create()
	{
		$members = Member::orderBy('last_name', 'asc')
						 ->orderBy('first_name', 'asc')
						 ->orderBy('middle_initial', 'asc')
						 ->get();
		return response()->json(['members' => $members]);
	}

	public function store(Request $request)
	{
		$this->validate($request, $this->validation_rules_1);

		$beneficiary_id = null;

		if((int)$request->claimant == 1)
		{
			$this->validate($request, $this->validation_rules_2);

			$beneficiary = Beneficiary::create([
				'first_name' => $request->first_name,
				'middle_initial' => $request->middle_initial,
				'last_name' => $request->last_name,
				'suffix' => $request->suffix
			]);

			$beneficiary_id = $beneficiary->id;
		}

		$collection = Collection::create([
			'member_id' => (int)$request->member_id,
			'beneficiary_id' => $beneficiary_id,
			'due_on' => $request->due_on
		]);

		return response()->json([
			'id' => $collection->id,
			'class' => 'success',
			'message' => 'Collection added.'
		]);
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
					 ->paginate($this->per_page);

		$payments = [];

		foreach($members as $member)
		{
			$sql = '(SELECT `id` FROM `'. DB::getTablePrefix() . 'payments` WHERE `member_id` = ' . $member->id . ' AND `collection_id` = `' . DB::getTablePrefix() . 'c`.`id` AND `deleted_at` IS NULL ORDER BY `created_at` DESC LIMIT 1)';

			$payment = DB::table('collections AS c')
						 ->select('c.id', 'p.id AS payment_id')
						 ->leftJoin('payments AS p', 'p.id', '=', DB::raw($sql))
						 ->where('c.id', '=', $id)
						 ->whereNull('c.deleted_at')
						 ->orderBy('c.due_on', 'asc')
						 ->first();

			$payments[$member->id] = !is_null($payment->payment_id);
		}

		return response()->json([
			'collection' => $collection,
			'members' => $members,
			'payments' => $payments
		]);
	}

	public function edit($id)
	{
		if(preg_match($this->regex, $id) == 0)
			return redirect()->route('collections.index');

		$sql = '(SELECT `id` FROM `'. DB::getTablePrefix() . 'beneficiaries` WHERE `id` = `'. DB::getTablePrefix() . 'c`.`beneficiary_id` AND `deleted_at` IS NULL LIMIT 1)';

		$collection = DB::table('collections AS c')
						->select('c.member_id', 'm.first_name AS member_first_name', 'm.middle_initial AS member_middle_initial', 'm.last_name AS member_last_name', 'm.suffix AS member_suffix', 'b.id AS claimant_id', 'b.first_name AS claimant_first_name', 'b.middle_initial AS claimant_middle_initial', 'b.last_name AS claimant_last_name', 'b.suffix AS claimant_suffix', 'c.due_on', 'c.released_on')
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

		return response()->json([
			'collection' => $collection,
			'members' => $members
		]);
	}

	public function update(Request $request, $id)
	{
		if(preg_match($this->regex, $id) == 0)
			return redirect()->route('collections.index');

		$sql = '(SELECT `id` FROM `'. DB::getTablePrefix() . 'beneficiaries` WHERE `id` = `'. DB::getTablePrefix() . 'c`.`beneficiary_id` AND `deleted_at` IS NULL LIMIT 1)';

		$collection = DB::table('collections AS c')
						->select('c.beneficiary_id')
						->join('members AS m', 'm.id', '=', 'c.member_id')
						->leftJoin('beneficiaries AS b', 'b.id', '=', DB::raw($sql))
						->where('c.id', '=', $id)
						->whereNull('c.deleted_at')
						->whereNull('m.deleted_at')
						->first();

		if(is_null($collection))
		{
			return response()->json([
				'class' => 'danger',
				'message' => 'Collection not found.'
			]);
		}

		$this->validate($request, $this->validation_rules_1);

		$beneficiary_id = null;

		if((int)$request->claimant == 1)
		{
			$this->validate($request, $this->validation_rules_2);

			$data = [
				'first_name' => $request->first_name,
				'middle_initial' => $request->middle_initial,
				'last_name' => $request->last_name,
				'suffix' => $request->suffix
			];

			if(!is_null($collection->beneficiary_id))
			{
				$beneficiary_id = $collection->beneficiary_id;
				Beneficiary::find($beneficiary_id)->update($data);
			}
			else
			{
				$beneficiary = Beneficiary::create($data);
				$beneficiary_id = $beneficiary->id;
			}
		}
		elseif(!is_null($collection->beneficiary_id))
			Beneficiary::find($collection->beneficiary_id)->delete();

		$collection = Collection::find($id)->update([
			'member_id' => (int)$request->member_id,
			'beneficiary_id' => $beneficiary_id,
			'due_on' => $request->due_on
		]);

		$collection = DB::table('collections AS c')
						->select('c.member_id', 'm.first_name AS member_first_name', 'm.middle_initial AS member_middle_initial', 'm.last_name AS member_last_name', 'm.suffix AS member_suffix', 'b.id AS claimant_id', 'b.first_name AS claimant_first_name', 'b.middle_initial AS claimant_middle_initial', 'b.last_name AS claimant_last_name', 'b.suffix AS claimant_suffix', 'c.due_on', 'c.released_on')
						->join('members AS m', 'm.id', '=', 'c.member_id')
						->leftJoin('beneficiaries AS b', 'b.id', '=', DB::raw($sql))
						->where('c.id', '=', $id)
						->whereNull('c.deleted_at')
						->whereNull('m.deleted_at')
						->first();

		return response()->json([
			'collection' => $collection,
			'class' => 'success',
			'message' => 'Collection updated.'
		]);
	}

	public function destroy($id)
	{
		if(preg_match($this->regex, $id) == 0)
			return redirect()->route('collections.index');

		$collection = Collection::find($id);
		if(!is_null($collection))
			$collection->delete();

		return response()->json([
			'type' => 'success',
			'message' => 'Collection deleted.'
		]);
	}
}
