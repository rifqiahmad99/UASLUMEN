<?php
namespace App\Http\Controllers\PublicController;
use App\Models\Purchase;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
class PurchasesController extends Controller{
	/**
	 * Display a listing of the resource.
	 * 
	 * @return \Illuminate\Http\Response
	 */
	public function index(Request $request){
		$purchases = Purchase::with('basket')->OrderBy("id", "DESC")->paginate(10)->toArray();
		$response = [ 
			"total_count" => $purchases["total"],
			"limit" => $purchases["per_page"],
			"panination" => ["next_page" => $purchases["next_page_url"],
							"current_page" => $purchases["current_page"]
			],
			"data" => $purchases["data"],
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
		$purchase = Purchase::with('basket')->find($id);
		if(!$purchase){
			abort(404);
		}
		return response()->json($purchase, 200);
	}

}
