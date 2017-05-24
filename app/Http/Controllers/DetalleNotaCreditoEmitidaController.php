<?php namespace SICPA\Http\Controllers;

use SICPA\DetalleComprobante;
use SICPA\Comprobante;
use SICPA\Producto;
use SICPA\UnidadMedida;
use SICPA\UnidadProducto;
use SICPA\Inventario;
use SICPA\Conversion;
use Illuminate\Http\Request;
use SICPA\Http\Requests\CrearDetalleComprobanteRequest;
use SICPA\Http\Requests\EditarDetalleComprobanteRequest;
use Carbon\Carbon;

class DetalleNotaCreditoEmitidaController extends Controller {

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
	public function getIndex(Request $request)
	{
		$this->validate($request,['comp_id'=>'required']);
		$comp_id=$request->get('comp_id');
		$detallecomprobantes = DetalleComprobante::where('comp_id',$comp_id)->get();
		$comprobante = Comprobante::find($comp_id);

		if($comprobante->comp_moneda=='SOLES')
			$moneda='S/. ';
		else
			$moneda='$. ';

		return view('detallenotacreditoemitida.mostrar',['detallecomprobantes'=> $detallecomprobantes,'comprobante'=> $comprobante,'moneda'=> $moneda]);
	}

	public function getCrear(Request $request)
	{
		$this->validate($request,['comp_id'=>'required']);
		$comp_id=$request->get('comp_id');
		$comp_ref=Comprobante::find($comp_id)->comp_ref;
		//$dcomp_referencia=DetalleComprobante::where('comp_id',$comp_ref);
		$productos=Comprobante::join('t_detallecomprobante','t_detallecomprobante.comp_id','=','t_comprobante.comp_id')->join('t_unidadproducto','t_unidadproducto.up_id','=','t_detallecomprobante.up_id')->join('t_producto','t_producto.prod_id','=','t_unidadproducto.prod_id')->select('t_producto.*')->where('t_comprobante.comp_id',$comp_ref)->orderBy('prod_desc','asc')->get();
		$unidadmedidas = UnidadMedida::orderBy('um_desc','asc')->get();
		return view('detallenotacreditoemitida.crear',['productos'=> $productos,'unidadmedidas'=> $unidadmedidas,'comp_id'=> $comp_id]);
	}

	public function postCrear(CrearDetalleComprobanteRequest $request)
	{
		$comp_id=strtoupper($request->get('comp_id'));
		$dcomp_cant= strtoupper($request->get('dcomp_cant'));
		$dcomp_prec= strtoupper($request->get('dcomp_prec'));
		$prod_id= strtoupper($request->get('prod_id'));
		$um_id= strtoupper($request->get('um_id'));

		$up_id=UnidadProducto::where('prod_id',$prod_id)->where('um_id',$um_id)->get();
		$up_id=$up_id[0]->up_id;

		if(DetalleComprobante::where('comp_id',$comp_id)->where('up_id',$up_id)->count()>0)
		{
			return redirect("/validado/detallesalida?comp_id={$comp_id}")->with('error','ESTE PRODUCTO YA EXISTE');
		}

		else
		{
			
			$dcomp_id=DetalleComprobante::create
			(
				[
					'dcomp_cant'=> $request->get('dcomp_cant'),
					'dcomp_prec'=> $request->get('dcomp_prec'),
					'comp_id'=> $request->get('comp_id'),
					'up_id'=> $up_id
				]
			)->dcomp_id;

			$detallecomprobante = DetalleComprobante::find($dcomp_id);
			$detalles = DetalleComprobante::where('comp_id',$comp_id)->get();
			
			///////////////////////// EDITANDO INVENTARIO ///////////////////////////////////////////////////

			$inventario=Inventario::where('prod_id',$detallecomprobante->unidadproducto->prod_id)->get();

			if($inventario->count()==0)
			{
				$inv_id=Inventario::create
				(
					[
						'inv_cant'=> '0',
						'inv_fecha'=> Carbon::now(),
						'prod_id'=> $detallecomprobante->unidadproducto->prod_id,
						'alm_id'=> '1'
					]
				)->inv_id;	
			}
			else 
			{
				$inv_id=$inventario[0]->inv_id;
			}			

			$inventario=Inventario::find($inv_id);

			$um_producto=Producto::find($detallecomprobante->unidadproducto->prod_id)->um_id;
			$um_detalle=$detallecomprobante->unidadproducto->um_id;
			$cantidad=$detallecomprobante->dcomp_cant;
			$cantidad_ant=$inventario->inv_cant;


			if ($um_producto != $um_detalle) {
				if((Conversion::where('um_id1',$um_producto)->where('um_id2',$um_detalle)->count())>(Conversion::where('um_id2',$um_producto)->where('um_id1',$um_detalle)->count()))
				{
					$conversion=Conversion::where('um_id1',$um_producto)->where('um_id2',$um_detalle)->get();
					$factor=$conversion[0]->conv_fact;
					$cantidad= ($cantidad/$factor);
				}
				else
				{
					$conversion=Conversion::where('um_id2',$um_producto)->where('um_id1',$um_detalle)->get();
					$factor=$conversion[0]->conv_fact;
					$cantidad= ($cantidad*$factor);
				}
			}

			$inventario->inv_cant=$cantidad_ant + $cantidad;
			$inventario->inv_fecha=Carbon::now();
			$inventario->save();
			
			///////////////////////// EDITANDO Comprobante ////////////////////////////////////////////////////////////////
			

			$totalcigv=0;
			$totalsigv=0;

			foreach ($detalles as $detalle) {
				$producto=Producto::find($detalle->unidadproducto->prod_id);
				if($producto->prod_exo=='NO')
				{
					$totalcigv=$totalcigv + ($detalle->dcomp_cant * $detalle->dcomp_prec);
				}
				else
				{
					$totalsigv=$totalsigv + ($detalle->dcomp_cant * $detalle->dcomp_prec);

				}
			}

			$preciotot=$totalcigv+$totalsigv;
			$subtigv=($totalcigv/1.18);

			$comprobante=Comprobante::find($comp_id);			
			$comprobante->comp_subt=$totalsigv+$subtigv;
			$comprobante->comp_igv=$totalcigv-$subtigv;
			$comprobante->comp_tot=$preciotot;
			if($comprobante->comp_cond=="AL CREDITO")
				$comprobante->comp_saldo=$preciotot;
			$comprobante->save();

			///////////////////////////////////////////////////////////////////////////////////////////////////////////

			return redirect("/validado/detallesalida?comp_id={$comp_id}")->with('creado','Detalle Comprobante creado');
		}
	}

	public function getEditar(Request $request)
	{
		$this->validate($request,['dcomp_id'=>'required']);
		$dcomp_id=$request->get('dcomp_id');
		$detallecomprobante = DetalleComprobante::find($dcomp_id);
		$productos = Producto::orderBy('prod_desc','asc')->get();
		$unidadmedidas = UnidadMedida::orderBy('um_desc','asc')->get();

		return view('detallenotacreditoemitida.editar',['detallecomprobante'=>$detallecomprobante,'productos'=>$productos,'unidadmedidas'=>$unidadmedidas]);

	}

	public function postEditar(EditarDetalleComprobanteRequest $request)
	{
		$dcomp_id=strtoupper($request->get('dcomp_id'));
		$dcomp_cant = strtoupper($request->get('dcomp_cant'));
		$dcomp_prec = strtoupper($request->get('dcomp_prec'));
		$comp_id = strtoupper($request->get('comp_id'));
		$prod_id = strtoupper($request->get('prod_id'));
		$um_id= strtoupper($request->get('um_id'));

		$detallecomprobante = DetalleComprobante::find($dcomp_id);

		$up_id=UnidadProducto::where('prod_id',$prod_id)->where('um_id',$um_id)->get();
		$up_id=$up_id[0]->up_id;

		$detallecomprobante->dcomp_cant=$dcomp_cant;
		$detallecomprobante->dcomp_prec=$dcomp_prec;
		$detallecomprobante->up_id=$up_id;
		$detallecomprobante->save();

		///////////////////////// EDITANDO Comprobante ///////////////////////////////////////////////////////////////

		$detalles = DetalleComprobante::where('comp_id',$comp_id)->get();

		$totalcigv=0;
		$totalsigv=0;

		foreach ($detalles as $detalle) {
			$producto=Producto::find($detalle->unidadproducto->prod_id);
			if($producto->prod_exo=='NO')
			{
				$totalcigv=$totalcigv + ($detalle->dcomp_cant * $detalle->dcomp_prec);
			}
			else
			{
				$totalsigv=$totalsigv + ($detalle->dcomp_cant * $detalle->dcomp_prec);

			}
		}

		$preciotot=$totalcigv+$totalsigv;
		$subtigv=($totalcigv/1.18);

		$comprobante=Comprobante::find($comp_id);
		$comprobante->comp_subt=$totalsigv+$subtigv;
		$comprobante->comp_igv=$totalcigv-$subtigv;
		$comprobante->comp_tot=$preciotot;
		if($comprobante->comp_cond=="AL CREDITO")
				$comprobante->comp_saldo=$preciotot;
		$comprobante->save();

		return redirect("/validado/detallesalida?comp_id={$comp_id}")->with('actualizado','Detalle Comprobante actualizado');
	}

	public function getEliminar(Request $request)
	{
		$this->validate($request,['dcomp_id'=>'required']);
		$dcomp_id=$request->get('dcomp_id');

		$detallecomprobante = DetalleComprobante::find($dcomp_id);
		$comp_id=$detallecomprobante->comp_id;

		DetalleComprobante::find($dcomp_id)->delete();

		///////////////////////// EDITANDO INVENTARIO ///////////////////////////////////////////////////

		$inventario=Inventario::where('prod_id',$detallecomprobante->unidadproducto->prod_id)->get();
		$inv_id=$inventario[0]->inv_id;
		
		$inventario=Inventario::find($inv_id);

		$um_producto=Producto::find($detallecomprobante->unidadproducto->prod_id)->um_id;
		$um_detalle=$detallecomprobante->unidadproducto->um_id;
		$cantidad=$detallecomprobante->dcomp_cant;
		$cantidad_ant=$inventario->inv_cant;

		if ($um_producto != $um_detalle) {
			if((Conversion::where('um_id1',$um_producto)->where('um_id2',$um_detalle)->count())>(Conversion::where('um_id2',$um_producto)->where('um_id1',$um_detalle)->count()))
			{
				$conversion=Conversion::where('um_id1',$um_producto)->where('um_id2',$um_detalle)->get();
				$factor=$conversion[0]->conv_fact;
				$cantidad= ($cantidad/$factor);
			}
			else
			{
				$conversion=Conversion::where('um_id2',$um_producto)->where('um_id1',$um_detalle)->get();
				$factor=$conversion[0]->conv_fact;
				$cantidad= ($cantidad*$factor);
			}
		}

		$inventario->inv_cant=$cantidad_ant + $cantidad;
		$inventario->inv_fecha=Carbon::now();
		$inventario->save();
		
		///////////////////////// EDITANDO Comprobante ////////////////////////////////////////////////////////////////
		

		$detalles = DetalleComprobante::where('comp_id',$comp_id)->get();

		$totalcigv=0;
		$totalsigv=0;

		foreach ($detalles as $detalle) {
			$producto=Producto::find($detalle->unidadproducto->prod_id);
			if($producto->prod_exo=='NO')
			{
				$totalcigv=$totalcigv + ($detalle->dcomp_cant * $detalle->dcomp_prec);
			}
			else
			{
				$totalsigv=$totalsigv + ($detalle->dcomp_cant * $detalle->dcomp_prec);

			}
		}

		$preciotot=$totalcigv+$totalsigv;
		$subtigv=($totalcigv/1.18);

		$comprobante=Comprobante::find($comp_id);
		$comprobante->comp_subt=$totalsigv+$subtigv;
		$comprobante->comp_igv=$totalcigv-$subtigv;
		$comprobante->comp_tot=$preciotot;
		if($comprobante->comp_cond=="AL CREDITO")
				$comprobante->comp_saldo=$preciotot;
		$comprobante->save();

		return redirect('/validado/detallesalida')->with('eliminado','Detalle Comprobante eliminado');
	}

	public function getImprimir(Request $request)
	{
		$this->validate($request,['comp_id'=>'required']);
		$comp_id=$request->get('comp_id');
		$detallecomprobantes = DetalleComprobante::where('comp_id',$comp_id)->get();
		$comprobante = Comprobante::find($comp_id);

		if($comprobante->comp_moneda=='SOLES')
			$moneda='S/. ';
		else
			$moneda='$. ';

		if($comprobante->tipocomprobante->tcomp_desc=='Boleta')
			return view('detallenotacreditoemitida.imprimirboleta',['detallecomprobantes'=> $detallecomprobantes,'comprobante'=> $comprobante,'moneda'=> $moneda]);
		else
			return view('detallenotacreditoemitida.imprimirfactura',['detallecomprobantes'=> $detallecomprobantes,'comprobante'=> $comprobante,'moneda'=> $moneda]);
	}

	public function getGenerartxt(Request $request)
	{
		$this->validate($request,['comp_id'=>'required']);
		$comp_id=$request->get('comp_id');
		$detallecomprobantes = DetalleComprobante::where('comp_id',$comp_id)->get();
		$comprobante = Comprobante::find($comp_id);

		return view('detallenotacreditoemitida.txt',['detallecomprobantes'=> $detallecomprobantes,'comprobante'=> $comprobante]);
	}

	public function getRegresar(Request $request)
	{
		$this->validate($request,['comp_id'=>'required']);
		$comp_id=$request->get('comp_id');
		return redirect("/validado/detallesalida?comp_id={$comp_id}")->with('creado','SE GENERÓ .TXT');
	}

	public function missingMethod($parameters = array())
	{
		abort(404);
	}

}
