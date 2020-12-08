<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ApiCollectionsController extends Controller
{
	private $regex = '/^[1-9]+\d*$/';

	private $per_page = 18;

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

		return response()->json(['collections' => $collections]);
	}
}
