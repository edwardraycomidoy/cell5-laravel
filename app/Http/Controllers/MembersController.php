<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Member;

use DB;

class MembersController extends Controller
{
	private $regex = '/^[1-9]+\d*$/';

	private $per_page = 15;

	public function __construct()
	{
		$this->middleware(['auth']);
	}

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
						 ->simplePaginate($this->per_page);

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

			$payments[$member->id] = DB::table('collections AS c')
									   ->select('c.id AS collection_id', 'p.id AS payment_id', 'c.due_on')
									   ->leftJoin('payments AS p', 'p.id', '=', DB::raw($sql))
									   ->whereNull('c.deleted_at')
									   ->orderBy('c.due_on', 'asc')
									   ->get();
		}

		return view('pages.members.index', compact('members', 'keywords', 'collections', 'payments'));
	}

	public function search(Request $request)
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

			$route .= '?keywords=' . urlencode(implode(' ', $keywords));
		}

		$members = Member::whereRaw(DB::raw($where))
						 ->orderBy('last_name', 'asc')
						 ->orderBy('first_name', 'asc')
						 ->orderBy('middle_initial', 'asc')
						 ->simplePaginate($this->per_page);

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

			$payments[$member->id] = DB::table('collections AS c')
									   ->select('c.id AS collection_id', 'p.id AS payment_id', 'c.due_on')
									   ->leftJoin('payments AS p', 'p.id', '=', DB::raw($sql))
									   ->whereNull('c.deleted_at')
									   ->orderBy('c.due_on', 'asc')
									   ->get();
		}

		return view('components.members-list', compact('members', 'collections', 'payments'));
	}

	public function create()
	{
		return view('pages.members.create');
	}

	public function store(Request $request)
	{
		$this->validate($request, [
			'first_name' => 'required|max:255',
			'middle_initial' => 'nullable|max:1',
			'last_name' => 'required|max:255',
			'suffix' => 'nullable|max:5',
			'joined_on' => 'required|date'
		]);

		$member = new Member;
		$member->first_name = $request->first_name;
		$member->middle_initial = $request->middle_initial;
		$member->last_name = $request->last_name;
		$member->suffix = $request->suffix;
		$member->joined_on = date('Y-m-d', strtotime($request->joined_on));
		$member->save();

		return redirect()->route('members.show', ['member' => $member])->with(['type' => 'success', 'message' => 'Member added.']);
	}

	public function show($id)
	{
		if(preg_match($this->regex, $id) == 0)
			return redirect()->route('members.index');

		$member = Member::find($id);

		if(is_null($member))
			return redirect()->route('members.index');

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

		$back_url = route('members.index');

		$members = Member::orderBy('last_name', 'asc')
						 ->orderBy('first_name', 'asc')
						 ->orderBy('middle_initial', 'asc')
						 ->get(['id']);

		$array = array_map(function($member){ return $member['id']; }, $members->toArray());
		$array = array_flip($array);

		$page = floor($array[$id] / $this->per_page) + 1;

		if($page > 1)
			$back_url .= "?page={$page}";

		return view('pages.members.show', compact('member', 'collections', 'back_url'));
	}

	public function edit($id)
	{
		if(preg_match($this->regex, $id) == 0)
			return redirect()->route('members.index');

		$member = Member::find($id);

		if(is_null($member))
			return redirect()->route('members.index');

		return view('pages.members.edit', compact('member'));
	}

	public function update(Request $request, $id)
	{
		$this->validate($request, [
			'first_name' => 'required|max:255',
			'middle_initial' => 'nullable|max:1',
			'last_name' => 'required|max:255',
			'suffix' => 'nullable|max:5',
			'joined_on' => 'required|date'
		]);

		$member = Member::find($id);

		if(is_null($member))
			return redirect()->route('members.index');

		$member->first_name = $request->first_name;
		$member->middle_initial = $request->middle_initial;
		$member->last_name = $request->last_name;
		$member->suffix = $request->suffix;
		$member->joined_on = date('Y-m-d', strtotime($request->joined_on));
		$member->save();

		return redirect()->route('members.show', ['member' => $member])->with(['type' => 'success', 'message' => 'Member updated.']);
	}

	public function destroy($id)
	{
		if(preg_match($this->regex, $id) == 0)
			return redirect()->route('members.index');

		$member = Member::find($id);

		if(!is_null($member))
			$member->delete();

		return redirect()->route('members.index')->with(['type' => 'success', 'message' => 'Member removed.']);
	}
}
