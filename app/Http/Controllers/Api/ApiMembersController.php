<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Member;

use DB;

class ApiMembersController extends Controller
{
	private $regex = '/^[1-9]+\d*$/';

	private $per_page = 18;

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

			$payments[$member->id] = DB::table('collections AS c')
									   ->select('c.id AS collection_id', 'p.id AS payment_id', 'c.due_on')
									   ->leftJoin('payments AS p', 'p.id', '=', DB::raw($sql))
									   ->whereNull('c.deleted_at')
									   ->orderBy('c.due_on', 'asc')
									   ->get();
		}

		return response()->json([
			'members' => $members,
			'collections' => $collections,
			'payments' => $payments
		]);
	}
}