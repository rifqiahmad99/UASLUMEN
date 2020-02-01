<?php
namespace App\Http\Controllers;
use App\Models\Distributor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
class DistributorsController extends Controller{
	/**
	 * Display a listing of the resource.
	 * 
	 * @return \Illuminate\Http\Response
	 */
	public function index(Request $request){
		$distributors = Distributor::OrderBy("id", "DESC")->paginate(2)->toArray();
		//authorization
		// check if current user is authorized to do this action
		
		if(Gate::denies('read-distributor')){
			return response()->json([
				'sucsess' => false,
				'status' => 403,
				'message' => 'you are unauthorized'
			], 403);
		}
		
		// authorization end
		$response = [ 
			"total_count" => $distributors["total"],
			"limit" => $distributors["per_page"],
			"panination" => ["next_page" => $distributors["next_page_url"],
							"current_page" => $distributors["current_page"]
			],
			"data" => $distributors["data"],
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

		if(Gate::denies('create-distributor')){
			return response()->json([
				'sucsess' => false,
				'status' => 403,
				'message' => 'you are unauthorized'
			], 403);
		}

		$validationRules = [ 
			'nama_distributor' => 'required|string|min:3',
			'alamat_distributor' => 'required|min:5',
			'notelfon' => 'required|min:10',
			'email' => 'required|email|unique:distributors'];
			$validator = \Validator::make($input, $validationRules);
			if($validator->fails()){
				return response()->json($validator->errors(),400);
			}
			$distributor = Distributor::create($input);
			return response()->json($distributor, 200);
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
		$distributor = Distributor::find($id);
		if(!$distributor){
			abort(404);
		}

		if(Gate::denies('read-distributor')){
			return response()->json([
				'sucsess' => false,
				'status' => 403,
				'message' => 'you are unauthorized'
			], 403);
		}

		return response()->json($distributor, 200);
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
		$distributor = Distributor::find($id);
		if(!$distributor){
			abort(404);
		}

		if(Gate::denies('update-distributor', $distributor)){
			return response()->json([
				'sucsess' => false,
				'status' => 403,
				'message' => 'you are unauthorized'
			], 403);
		}

		$validationRules = [ 
			'nama_distributor' => 'required|string|min:3',
			'alamat_distributor' => 'required|min:5',
			'notelfon' => 'required|min:10',
			'email' => 'required|email|unique:distributors'
		];

		$validator = \Validator::make($input, $validationRules);
			
			if($validator->fails()){
				return response()->json($validator->errors(),400);
			}
		
			$distributor->fill($input);
		$distributor->save();
		return response()->json($distributor, 200);
	}

	/**
	 * Remove the specified resource from storage.
	 * 
	 * @param int $id
	 * @return \Illuminate\Http\Response
	 */
	public function destroy($id){
		$distributor = Distributor::find($id);
		if(!$distributor){
			abort(404);
		}

		if(Gate::denies('delete-distributor', $distributor)){
			return response()->json([
				'sucsess' => false,
				'status' => 403,
				'message' => 'you are unauthorized'
			], 403);
		}

		$distributor->delete();
		$message = ['message' => 'deleted successfully', 'id' => $id];
		return response()->json($message, 200);
	}
}