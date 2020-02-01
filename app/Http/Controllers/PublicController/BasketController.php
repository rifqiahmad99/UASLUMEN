<?php
namespace App\Http\Controllers\PublicController;
use App\Models\Basket;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
class BasketController extends Controller{
	/**
	 * Display a listing of the resource.
	 * 
	 * @return \Illuminate\Http\Response
	 */
	public function index(Request $request){
		$baskets = Basket::with('book')->OrderBy("id", "DESC")->paginate(10)->toArray();
		$response = [ 
			"total_count" => $baskets["total"],
			"limit" => $baskets["per_page"],
			"panination" => ["next_page" => $baskets["next_page_url"],
							"current_page" => $baskets["current_page"]
			],
			"data" => $baskets["data"],
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
		$basket = Basket::with('book')->find($id);
		if(!$basket){
			abort(404);
		}
		return response()->json($basket, 200);
	}

}
