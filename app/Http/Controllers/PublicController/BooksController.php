<?php
namespace App\Http\Controllers\PublicController;
use App\Models\Book;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
class BooksController extends Controller{
	/**
	 * Display a listing of the resource.
	 * 
	 * @return \Illuminate\Http\Response
	 */
	public function index(Request $request){
		$books = Book::with('distributor')->OrderBy("id", "DESC")->paginate(10)->toArray();
		$response = [ 
			"total_count" => $books["total"],
			"limit" => $books["per_page"],
			"panination" => ["next_page" => $books["next_page_url"],
							"current_page" => $books["current_page"]
			],
			"data" => $books["data"],
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
		$book = Book::with(['distributor'=>function($query){
			$query->select('id','nama_distributor');
		}])->find($id);
		if(!$book){
			abort(404);
		}
		return response()->json($book, 200);
	}

}
