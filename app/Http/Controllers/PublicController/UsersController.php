<?php
namespace App\Http\Controllers\PublicController;
use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
class UsersController extends Controller{
	/**
	 * Display a listing of the resource.
	 * 
	 * @return \Illuminate\Http\Response
	 */
	public function index(Request $request){
		$users = User::with('purchase')->OrderBy("id", "DESC")->paginate(10)->toArray();
		$response = [ 
			"total_count" => $users["total"],
			"limit" => $users["per_page"],
			"panination" => ["next_page" => $users["next_page_url"],
							"current_page" => $users["current_page"]
			],
			"data" => $users["data"],
		];
			return response()->json($response, 200);
	}
	/**
	 * Display the specified resource.
	 * 
	 * @param int $id
	 * @return \Illuminate\Http\Response
	 */
	public function show($id){
		$user = User::with('purchase')->find($id);
		if(!$user){
			abort(404);
		}
		return response()->json($user, 200);
	}

}
