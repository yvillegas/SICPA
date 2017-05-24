<?php namespace SICPA\Http\Controllers;

use SICPA\Inventario;
use SICPA\Producto;
use Illuminate\Http\Request;
use SICPA\Http\Requests\CrearProductoRequest;
use SICPA\Http\Requests\EditarProductoRequest;
use Carbon\Carbon;


class InventarioController extends Controller {

	/*
	|--------------------------------------------------------------------------
	| Home Controller
	|--------------------------------------------------------------------------
	|
	| This controller renders your application's "dashboard" for users that
	| are authenticated. Of course, you are free to change or remove the
	| controller as you wish. It is just here to get your app started!
	|
	*/

	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		$this->middleware('auth');
	}

	/**
	 * Show the application dashboard to the user.
	 *
	 * @return Response
	 */
	public function getIndex()
	{
		$inventarios = Inventario::all();
		return view('inventario.mostrar',['inventarios'=> $inventarios]);
	}

	public function postIndex(Request $request)
	{
		
	}

	public function getCrear()
	{
		$productos =Producto::all();
		return view('inventario.crear',['productos'=>$productos]);
	}

	public function postCrear(Request $request)
	{
		$this->validate($request,['inv_id'=>'required']);

		Inventario::create
		(
			[
				'inv_cant'=> strtoupper($request->get('inv_cant')),
				'inv_fecha'=> Carbon::now(),
				'prod_id'=> strtoupper($request->get('prod_id')),
				'alm_id'=> '1'
			]
		);
		return redirect('/validado/inventario')->with('creado','Inventario creado');
	}

	public function getEditar(Request $request)
	{
		$this->validate($request,['inv_id'=>'required']);
		$inv_id=$request->get('inv_id');
		$inventario = Inventario::find($inv_id);
		return view('inventario.editar',['inventario'=>$inventario]);
	}

	public function postEditar(Request $request)
	{
		$inv_id=strtoupper($request->get('inv_id'));
		$inv_cant=strtoupper($request->get('inv_cant'));
		$inv_fecha=Carbon::now();
		$prod_id=strtoupper($request->get('prod_id'));
		$alm_id='1';

		$inventario = Inventario::find($inv_id);

		$inventario->inv_cant=$inv_cant;
		$inventario->inv_fecha=$inv_fecha;
		$inventario->prod_id=$prod_id;
		$inventario->alm_id=$alm_id;
		$inventario->save();

		return redirect('/validado/inventario')->with('actualizado','Inventario Actualizado');
	}

	public function missingMethod($parameters = array())
	{
		abort(404);
	}

}
