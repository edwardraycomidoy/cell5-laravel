<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Member;

use DB;

class SpreadsheetController extends Controller
{
	private $per_page = 20;

	public function index()
	{
		$members = Member::orderBy('last_name', 'asc')
						 ->orderBy('first_name', 'asc')
						 ->orderBy('middle_initial', 'asc')
						 ->simplePaginate($this->per_page);

		$sql = '(SELECT `id` FROM `'. DB::getTablePrefix() . 'beneficiaries` WHERE `id` = `'. DB::getTablePrefix() . 'c`.`beneficiary_id` AND `deleted_at` IS NULL LIMIT 1)';

		$collections = DB::table('collections AS c')
						 ->select('c.id', 'c.member_id', 'm.first_name AS member_first_name', 'm.middle_initial AS member_middle_initial', 'm.last_name AS member_last_name', 'm.suffix AS member_suffix', 'c.beneficiary_id AS claimant_id', 'b.first_name AS claimant_first_name', 'b.middle_initial AS claimant_middle_initial', 'b.last_name AS claimant_last_name', 'b.suffix AS claimant_suffix', 'c.due_on', 'c.released_on')
						 ->join('members AS m', 'm.id', '=', 'c.member_id')
						 ->leftJoin('beneficiaries AS b', 'b.id', '=', DB::raw($sql))
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

		//dd($members->toArray());
		//dd($payments);

		return view('pages.spreadsheet', compact('members', 'collections', 'payments'));
	}
}
