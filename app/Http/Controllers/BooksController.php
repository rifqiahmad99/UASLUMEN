<?php
namespace App\Http\Controllers;
use App\Models\Book;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
class BooksController extends Controller{
	/**
	 * Display a listing of the resource.
	 * 
	 * @return \Illuminate\Http\Response
	 */
	public function index(Request $request){
		$books = Book::OrderBy("id", "DESC")->paginate(2)->toArray();

		//authorization
		// check if current user is authorized to do this action
		
		if(Gate::denies('read-book')){
			return response()->json([
				'sucsess' => false,
				'status' => 403,
				'message' => 'you are unauthorized'
			], 403);
		}
		
		
		$response = [ 
			"total_count" => $books["total"],
			"limit" => $books["per_page"],
			"panination" => ["next_page" => $books["next_page_url"],
							"current_page" => $books["current_page"]
			],
			"data" => $books["data"],
		];
			return response()->json($response, 200);


		/*$distributors = Distributor::OrderBy("id", "DESC")->paginate(10);
		$output = [
			"message" => "distributors",
			"results" => $distributors
		];
		return response()->json($distributors,200);
		 $acceptHeader = $request->header('Accept');

        // validasi: hanya application/json atau application/xml yang valid
        if ($acceptHeader === 'application/json' || $acceptHeader === 'application/xml') {
            $distributors = Distributor::OrderBy("id", "DESC")->paginate(10);

            if ($acceptHeader === 'application/json') {
                // response json
                return response()->json($distributors->items('data'), 200);
            } else {
                // create xml posts element
                $xml = new \SimpleXMLElement('<ditributors/>');
                foreach ($distributors->items('data') as $item) {
                    // create xml posts element
                    $xmlItem = $xml->addChild('distributor');

                    // mengubah setiap field post menjadi bentuk xml
                    $xmlItem->addChild('id', $item->id);
                    $xmlItem->addChild('id_distributor', $item->id_distributor);
                    $xmlItem->addChild('nama_distributor', $item->nama_distributor);
                    $xmlItem->addChild('alamat_distributor', $item->alamat_distributor);
                    $xmlItem->addChild('notelfon', $item->notelfon);
                    $xmlItem->addChild('email', $item->email);
                    $xmlItem->addChild('created_at', $item->created_at);
                    $xmlItem->addChild('updated_at', $item->updated_at);
                }
                return $xml->asXML();
            }
        } else {
            return response('Not Acceptable!', 406);
        }*/
	}
	/**
	 * Store a newly created resource in storage.
	 * @param \Illuminate\Http\Request $request
	 * @return \Illuminate\Http\Response
	 */
	public function store(Request $request){
		$input = $request->all();

		if(Gate::denies('create-book')){
			return response()->json([
				'sucsess' => false,
				'status' => 403,
				'message' => 'you are unauthorized'
			], 403);
		}

		$validationRules = [ 
			'judul_buku' => 'required|min:5',
			'penerbit' => 'required|min:3',
			'tahun_terbit' => 'required|min:4',
			'penulis' => 'required|min:3',
			'deskripsi' => 'required|min:10',
			'harga' => 'required|integer|min:4',
			'id_distributor' => 'required|integer'];
			$validator = \Validator::make($input, $validationRules);
			if($validator->fails()){
				return response()->json($validator->errors(),400);
			}

		$book = new Book();
		$book->judul_buku = $request->input('judul_buku');
		$book->penerbit = $request->input('penerbit');
		$book->tahun_terbit = $request->input('tahun_terbit');
		$book->penulis = $request->input('penulis');
		$book->deskripsi = $request->input('deskripsi');
		$book->harga = $request->input('harga');
		$book->foto = $request->file('foto');
			$id = str_replace(' ', '_', $request->input('id'));
			$judulbuku = str_replace(' ', '_', $request->input('judul_buku'));
			$tahunterbit = str_replace(' ', '_', $request->input('tahun_buku'));
			$imagName = $id . '_' . $judulbuku . '_' . $tahunterbit;
			$request->file('foto')->move(storage_path('uploads/foto_buku'), $imagName);
			$current_image_path = storage_path('buku') . '/' . $book->foto;
			if (file_exists($current_image_path)){
				unlink($current_image_path);
			}
			$book->foto = $imagName;
		$book->id_distributor = $request->input('id_distributor');
		
		/*if ($request->hasFile('foto')){
			$id = str_replace(' ', '_', $request->input('id'));
			$judulbuku = str_replace(' ', '_', $request->input('judul_buku'));
			$tahunterbit = str_replace(' ', '_', $request->input('tahun_buku'));
			$imagName = $id . '_' . $judulbuku . '_' . $tahunterbit;
			$request->file('foto')->move(storage_path('uploads/foto_buku'), $imagName);
			$current_image_path = storage_path('buku') . '/' . $book->image;
			if (file_exists($current_image_path)){
				unlink($current_image_path);
			}
			$book->image = $imagName;

		}*/

		$book->save();
		return response()->json($book, 200);
			
		/*$input = $request->all();
		$distributor = Distributor::create($input);
		return response()->json($distributor, 200);
		 $acceptHeader = $request->header('Accept');

        // validasi: hanya application/json atau application/xml yang valid
        if ($acceptHeader === 'application/json' || $acceptHeader === 'application/xml') {

            $contentTypeHeader = $request->header('Content-Type');

            // validasi: hanya application/json yang valid
            if ($contentTypeHeader === 'application/json') {
                $input = $request->all();
                $distributor = Distributor::create($input);

                return response()->json($distributor, 200);
            } else {
                return response('Unsupported Media Type', 415);
            }
        } else {
            return response('Not Acceptable!', 406);
        }*/
	}
	/**
	 * Display the specified resource.
	 * 
	 * @param int $id
	 * @return \Illuminate\Http\Response
	 */
	public function show($id){
		$book = Book::find($id);
		if(!$book){
			abort(404);
		}

		if(Gate::denies('read-book')){
			return response()->json([
				'sucsess' => false,
				'status' => 403,
				'message' => 'you are unauthorized'
			], 403);
		}

		return response()->json($book, 200);
	}

	public function image($imageName){
		$imagePath = storage_path('uploads/foto_buku') . '/' . $imageName;
		if(file_exists($imagePath)){
			$file = file_get_contents($imagePath);
			return response($file, 200)->header('Content-Type', 'image/jpeg');
		}

		if(Gate::denies('read-imagebook')){
			return response()->json([
				'sucsess' => false,
				'status' => 403,
				'message' => 'you are unauthorized'
			], 403);
		}

		return response()->json(array("message" => "image not found"), 401);
	}
	/**
	 * Update the specified resource in storage.
	 * 
	 * @param \Illuminate\Http\Request $request
	 * @param int $id
	 * @return \Illuminate\Http\Response
	 */
	public function update(Request $request, $id){
		/*$input = $request->all();
		$distributor = Distributor::find($id);
		if(!$distributor){
			abort(404);
		}
		$distributor->fill($input);
		$distributor->save();
		return response()->json($distributor,200);*/
		$input = $request->all();
		$book = Book::find($id);
		if(!$book){
			abort(404);
		}

		if(Gate::denies('update-book', $book)){
			return response()->json([
				'sucsess' => false,
				'status' => 403,
				'message' => 'you are unauthorized'
			], 403);
		}

		$validationRules = [ 
			'judul_buku' => 'required|min:5',
			'penerbit' => 'required|min:3',
			'tahun_terbit' => 'required|min:4',
			'penulis' => 'required|min:3',
			'deskripsi' => 'required|min:10',
			'harga' => 'required|integer|min:4',
			'id_distributor' => 'required|integer'];
			$validator = \Validator::make($input, $validationRules);
			if($validator->fails()){
				return response()->json($validator->errors(),400);
			}

		$book = new Book();
		$book->judul_buku = $request->input('judul_buku');
		$book->penerbit = $request->input('penerbit');
		$book->tahun_terbit = $request->input('tahun_terbit');
		$book->penulis = $request->input('penulis');
		$book->deskripsi = $request->input('deskripsi');
		$book->harga = $request->input('harga');
		if($request->file('foto')==''){
			$book->foto = $book->foto;
		}
		else{
			$book->foto = $request->file('foto');
			$id = str_replace(' ', '_', $request->input('id'));
			$judulbuku = str_replace(' ', '_', $request->input('judul_buku'));
			$tahunterbit = str_replace(' ', '_', $request->input('tahun_buku'));
			$imagName = $id . '_' . $judulbuku . '_' . $tahunterbit;
			$request->file('foto')->move(storage_path('uploads/foto_buku'), $imagName);
			$current_image_path = storage_path('buku') . '/' . $book->foto;
			if (file_exists($current_image_path)){
				unlink($current_image_path);
			}
			$book->foto = $imagName;
		}
		
		$book->id_distributor = $request->input('id_distributor');
		$book->fill($input);
		$book->save();
		return response()->json($book, 200);
		
	}

	/**
	 * Remove the specified resource from storage.
	 * 
	 * @param int $id
	 * @return \Illuminate\Http\Response
	 */
	public function destroy($id){
		$book = Book::find($id);
		if(!$book){
			abort(404);
		}

		if(Gate::denies('delete-book', $book)){
			return response()->json([
				'sucsess' => false,
				'status' => 403,
				'message' => 'you are unauthorized'
			], 403);
		}

		$book->delete();
		$message = ['message' => 'deleted successfully', 'id' => $id];
		return response()->json($message, 200);
	}
}