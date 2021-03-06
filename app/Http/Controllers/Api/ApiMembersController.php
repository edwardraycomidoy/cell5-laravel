<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

use App\Models\Member;

use DB;

class ApiMembersController extends Controller
{
	private $regex = '/^[1-9]+\d*$/';

	private $per_page = 20;

	private $validation_rules = [
		'first_name' => 'required|max:255',
		'middle_initial' => 'nullable|max:1',
		'last_name' => 'required|max:255',
		'suffix' => 'nullable|max:5',
		'joined_on' => 'required|date'
	];

	public function index(Request $request)
	{
		$route = route('members.index');

		$keywords = trim($request->keywords);

		if($keywords == '')
			$where = 'TRUE';

		else
		{
			$keywords = array_filter(explode(' ', $keywords), function($string){ return addslashes(trim($string)) != ''; });

			$where = array_map(function($keyword){ return "(last_name LIKE '{$keyword}%' OR first_name LIKE '{$keyword}%')"; }, $keywords);
			$where = implode(' AND ', $where);

			$keywords = urlencode(implode(' ', $keywords));

			$route .= "?keywords={$keywords}";
		}

		$members = Member::whereRaw(DB::raw($where))
						 ->orderBy('last_name', 'asc')
						 ->orderBy('first_name', 'asc')
						 ->orderBy('middle_initial', 'asc')
						 ->paginate($this->per_page);

		$members->setPath($route);

		$sql = '(SELECT `id` FROM `'. DB::getTablePrefix() . 'beneficiaries` WHERE `id` = `'. DB::getTablePrefix() . 'c`.`beneficiary_id` AND `deleted_at` IS NULL LIMIT 1)';

		$collections = DB::table('collections AS c')
						 ->select('c.id', 'c.member_id', 'm.first_name AS member_first_name', 'm.middle_initial AS member_middle_initial', 'm.last_name AS member_last_name', 'm.suffix AS member_suffix', 'c.beneficiary_id AS claimant_id', 'b.first_name AS claimant_first_name', 'b.middle_initial AS claimant_middle_initial', 'b.last_name AS claimant_last_name', 'b.suffix AS claimant_suffix', 'c.due_on', 'c.released_on')
						 ->join('members AS m', 'm.id', '=', 'c.member_id')
						 ->leftJoin('beneficiaries AS b', 'b.id', '=', DB::raw($sql))
						 ->whereNull('c.deleted_at')
						 ->whereNull('m.deleted_at')
						 ->orderBy('c.due_on', 'asc')
						 ->get();

		$payments = [];

		foreach($members as $member)
		{
			$sql = '(SELECT `id` FROM `'. DB::getTablePrefix() . 'payments` WHERE `member_id` = ' . $member->id . ' AND `collection_id` = `'. DB::getTablePrefix() . 'c`.`id` AND `deleted_at` IS NULL ORDER BY `created_at` DESC LIMIT 1)';

			$payments[$member->id] = [];

			$temp_payments = DB::table('collections AS c')
							   ->select('c.id', 'p.id AS payment_id')
							   ->leftJoin('payments AS p', 'p.id', '=', DB::raw($sql))
							   ->whereNull('c.deleted_at')
							   ->orderBy('c.due_on', 'asc')
							   ->get();

			foreach($temp_payments as $row)
				$payments[$member->id][$row->id] = !is_null($row->payment_id);
		}

		return response()->json([
			'members' => $members,
			'collections' => $collections,
			'payments' => $payments
		]);
	}

	public function show($id)
	{
		if(preg_match($this->regex, $id) == 0 || is_null($member = Member::find($id, ['first_name', 'middle_initial', 'last_name', 'suffix', 'joined_on'])))
		{
			throw ValidationException::withMessages([
				'error' => ['Invalid member ID.'],
			]);
		}

		$sql = '(SELECT `id` FROM `'. DB::getTablePrefix() . 'beneficiaries` WHERE `id` = `'. DB::getTablePrefix() . 'c`.`beneficiary_id` AND `deleted_at` IS NULL LIMIT 1)';

		$sql2 = '(SELECT `id` FROM `'. DB::getTablePrefix() . 'payments` WHERE `member_id` = ' . $id . ' AND `collection_id` = `'. DB::getTablePrefix() . 'c`.`id` AND `deleted_at` IS NULL ORDER BY `created_at` DESC LIMIT 1)';

		$collections = DB::table('collections AS c')
						 ->select('c.id', 'c.member_id', 'm.first_name AS member_first_name', 'm.middle_initial AS member_middle_initial', 'm.last_name AS member_last_name', 'm.suffix AS member_suffix', 'b.id AS claimant_id', 'b.first_name AS claimant_first_name', 'b.middle_initial AS claimant_middle_initial', 'b.last_name AS claimant_last_name', 'b.suffix AS claimant_suffix', 'c.due_on', 'c.released_on', 'p.id AS payment_id')
						 ->join('members AS m', 'm.id', '=', 'c.member_id')
						 ->leftJoin('beneficiaries AS b', 'b.id', '=', DB::raw($sql))
						 ->leftJoin('payments AS p', 'p.id', '=', DB::raw($sql2))
						 ->where('c.due_on', '>=', $member->joined_on)
						 ->whereNull('c.deleted_at')
						 ->whereNull('m.deleted_at')
						 ->orderBy('c.due_on', 'desc')
						 ->get();

		$members = Member::orderBy('last_name', 'asc')
						 ->orderBy('first_name', 'asc')
						 ->orderBy('middle_initial', 'asc')
						 ->get(['id']);

		$payments = [];

		foreach($collections as $collection)
		{
			$sql = '(SELECT `id` FROM `'. DB::getTablePrefix() . 'payments` WHERE `member_id` = ' . $id . ' AND `collection_id` = `' . DB::getTablePrefix() . 'c`.`id` AND `deleted_at` IS NULL ORDER BY `created_at` DESC LIMIT 1)';

			$payment = DB::table('collections AS c')
						 ->select('c.id AS collection_id', 'p.id AS payment_id')
						 ->leftJoin('payments AS p', 'p.id', '=', DB::raw($sql))
						 ->where('c.id', '=', $collection->id)
						 ->whereNull('c.deleted_at')
						 ->orderBy('c.due_on', 'asc')
						 ->first();

			$payments[$collection->id] = !is_null($payment->payment_id);
		}

		$array = array_map(function($member){ return $member['id']; }, $members->toArray());
		$array = array_flip($array);
		$current_page = floor($array[$id] / $this->per_page) + 1;

		return response()->json([
			'member' => $member,
			'collections' => $collections,
			'payments' => $payments,
			'current_page' => $current_page
		]);
	}

	public function store(Request $request)
	{
		$this->validate($request, $this->validation_rules);
		$member = Member::create($request->all());
		return response()->json([
			'id' => $member->id,
			'type' => 'success',
			'message' => 'Member added.'
		]);
	}

	public function update(Request $request, $id)
	{
		$this->validate($request, $this->validation_rules);
		$member = Member::find($id);
		if(is_null($member))
		{
			return response()->json([
				'class' => 'danger',
				'message' => 'Member not found.'
			]);
		}
		$member->update($request->all());
		return response()->json([
			'class' => 'success',
			'message' => 'Member updated.'
		]);
	}

	public function destroy($id)
	{
		if(preg_match($this->regex, $id) == 0)
			return redirect()->route('members.index');

		$member = Member::find($id);
		if(!is_null($member))
			$member->delete();

		return response()->json([
			'type' => 'success',
			'message' => 'Member deleted.'
		]);
	}
}
