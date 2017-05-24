<?php namespace SICPA\Http\Controllers;

use Illuminate\Http\Request;
use SICPA\Http\Requests;
use SICPA\Http\Controllers\Controller;
use Illuminate\Support\Facades\App;
use SICPA\Comprobante;
use SICPA\DetalleComprobante;
use SICPA\Operacion;
use SICPA\TipoComprobante;
use SICPA\TipoOperacion;
use SICPA\Entidad;
use SICPA\Producto;
use SICPA\Inventario;
use SICPA\Conversion;
use SICPA\NotaCredito;
use SICPA\Vendedor;

class ReporteController extends Controller {

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
		$entidades = Entidad::where('tent_id','2')->orderBy('ent_rz','asc')->get(); // tipo proveedor
		$clientes = Entidad::where('tent_id','1')->where('ent_id','<>','1')->orderBy('ent_rz','asc')->get();
		$vendedores = Vendedor::orderBy('vend_nom','asc')->get();
		$tipocomprobantes = TipoComprobante::all();
		$productos = Producto::orderBy('prod_desc','asc')->get();
		return view('reporte.mostrar',['tipocomprobantes'=> $tipocomprobantes,'vendedores'=> $vendedores,'entidades'=> $entidades,'clientes'=> $clientes,'productos'=> $productos]);
	}

    public function getDvencidas() 
    {
        /*$data = $this->getData();
        $date = date('Y-m-d');
        $invoice = "2222";
        $view =  view('pdf.dvencidas',['data'=> $data,'date'=> $date,'invoice'=> $invoice]);
        $pdf = App::make('dompdf.wrapper');
        $pdf->loadHTML($view);
        return $pdf->stream('invoice');*/
        $date = date('Y-m-d');

        $dvencidas = Comprobante::join('t_entidad','t_entidad.ent_id','=','t_comprobante.ent_id')->select('*')->where('t_comprobante.comp_fven','<',$date)->where('t_comprobante.comp_saldo','>','0')->get();

        return view('reporte.dvencidasexcel',['dvencidas'=> $dvencidas,'date'=> $date]);
    }

    public function getDporvencer()
	{
		$comprobantes = Comprobante::join('t_operacion','t_operacion.comp_id','=','t_comprobante.comp_id')->select('t_comprobante.*')->where('t_operacion.tope_id','=','2')->where('t_comprobante.comp_id','<>','1')->get();
		$entidades = Entidad::where('tent_id','1')->where('ent_id','<>','1')->get(); // tipo cliente
		$tipocomprobantes = TipoComprobante::all();
		return view('reporte.dporvencerexcel',['comprobantes'=> $comprobantes,'tipocomprobantes'=> $tipocomprobantes,'entidades'=> $entidades]);
	}

    public function getData() 
    {
        $data =  [
            'quantity'      => '1' ,
            'description'   => 'some ramdom text',
            'price'   => '500',
            'total'     => '500'
        ];
        return $data;
    }

    public function postIngreso(Request $request)
	{
		$comp_nro = strtoupper($request->get('comp_nro'));
		$comp_fecha_ini = strtoupper($request->get('comp_fecha_ini'));
		$comp_fecha_fin = strtoupper($request->get('comp_fecha_fin'));
		$comp_guia = strtoupper($request->get('comp_guia'));
		$comp_cond = strtoupper($request->get('comp_cond'));
		$comp_moneda = strtoupper($request->get('comp_moneda'));
		$tcomp_id = strtoupper($request->get('tcomp_id'));
		$ent_id = strtoupper($request->get('ent_id'));
		$vend_id = strtoupper($request->get('vend_id'));
		$igv = strtoupper($request->get('igv'));

		$comprobantes = Comprobante::join('t_operacion','t_operacion.comp_id','=','t_comprobante.comp_id')->select('t_comprobante.*')->where('t_operacion.tope_id','=','1')->where('t_comprobante.comp_id','<>','1')->where('comp_nro','like','%'.$comp_nro.'%')->where('comp_guia','like','%'.$comp_guia.'%');

		if($comp_cond != "0")
		{
			$comprobantes = $comprobantes->where('comp_cond','=',$comp_cond);
		}
		if($comp_moneda != "0")
		{
			$comprobantes = $comprobantes->where('comp_moneda','=',$comp_moneda);
		}
		if($comp_fecha_ini != "")
		{
			$comprobantes = $comprobantes->where('comp_fecha','>=',$comp_fecha_ini);
		}
		if($comp_fecha_fin != "")
		{
			$comprobantes = $comprobantes->where('comp_fecha','<=',$comp_fecha_fin);
		}
		if($tcomp_id != "0")
		{
			$comprobantes = $comprobantes->where('tcomp_id','=',$tcomp_id);
		}
		if($ent_id != "0")
		{
			$comprobantes = $comprobantes->where('ent_id','=',$ent_id);
		}
		if($vend_id != "0")
		{
			$comprobantes = $comprobantes->where('vend_id','=',$vend_id);
		}
		if($igv=="C")
		{
			$comprobantes = $comprobantes->where('comp_igv','>','0');
		}
		if($igv=="S")
		{
			$comprobantes = $comprobantes->where('comp_igv','=','0');
		}


		$comprobantes=$comprobantes->orderBy('comp_fecha')->get();
		$entidades = Entidad::where('tent_id','2')->get(); // tipo proveedor
		$clientes = Entidad::where('tent_id','1')->where('ent_id','<>','1')->orderBy('ent_rz','asc')->get();
		$tipocomprobantes = TipoComprobante::all();

		return view('reporte.ingresoexcel',['comprobantes'=> $comprobantes,'tipocomprobantes'=> $tipocomprobantes,'clientes'=> $clientes,'entidades'=> $entidades]);
		
	}

	public function postDetalleingreso(Request $request)
	{
		$comp_nro = strtoupper($request->get('comp_nro'));
		$comp_fecha_ini = strtoupper($request->get('comp_fecha_ini'));
		$comp_fecha_fin = strtoupper($request->get('comp_fecha_fin'));
		$comp_guia = strtoupper($request->get('comp_guia'));
		$comp_cond = strtoupper($request->get('comp_cond'));
		$comp_moneda = strtoupper($request->get('comp_moneda'));
		$tcomp_id = strtoupper($request->get('tcomp_id'));
		$ent_id = strtoupper($request->get('ent_id'));
		$prod_id = strtoupper($request->get('prod_id'));
		$vend_id = strtoupper($request->get('vend_id'));
		$igv = strtoupper($request->get('igv'));

		$detallecomprobantes = DetalleComprobante::join('t_unidadproducto','t_unidadproducto.up_id','=','t_detallecomprobante.up_id')->join('t_comprobante','t_comprobante.comp_id','=','t_detallecomprobante.comp_id')->join('t_operacion','t_operacion.comp_id','=','t_comprobante.comp_id')->select('t_detallecomprobante.*')->where('t_operacion.tope_id','=','1')->where('t_comprobante.comp_id','<>','1')->where('t_comprobante.comp_est','<>','ANULADO');

		if($comp_cond != "0")
		{
			$detallecomprobantes = $detallecomprobantes->where('comp_cond','=',$comp_cond);
		}
		if($comp_moneda != "0")
		{
			$detallecomprobantes = $detallecomprobantes->where('comp_moneda','=',$comp_moneda);
		}
		if($comp_fecha_ini != "")
		{
			$detallecomprobantes = $detallecomprobantes->where('comp_fecha','>=',$comp_fecha_ini);
		}
		if($comp_fecha_fin != "")
		{
			$detallecomprobantes = $detallecomprobantes->where('comp_fecha','<=',$comp_fecha_fin);
		}
		if($tcomp_id != "0")
		{
			$detallecomprobantes = $detallecomprobantes->where('tcomp_id','=',$tcomp_id);
		}
		if($ent_id != "0")
		{
			$detallecomprobantes = $detallecomprobantes->where('ent_id','=',$ent_id);
		}
		if($vend_id != "0")
		{
			$detallecomprobantes = $detallecomprobantes->where('vend_id','=',$vend_id);
		}
		if($igv=="C")
		{
			$detallecomprobantes = $detallecomprobantes->where('comp_igv','>','0');
		}
		if($igv=="S")
		{
			$detallecomprobantes = $detallecomprobantes->where('comp_igv','=','0');
		}
		if($prod_id != "0")
		{
			$detallecomprobantes = $detallecomprobantes->where('prod_id','=',$prod_id);
		}


		$detallecomprobantes=$detallecomprobantes->orderBy('comp_fecha')->orderBy('comp_nro')->get();
		$entidades = Entidad::where('tent_id','2')->get(); // tipo proveedor
		$tipocomprobantes = TipoComprobante::all();

		return view('reporte.detalleingresoexcel',['detallecomprobantes'=> $detallecomprobantes]);
		
	}


    public function postSalida(Request $request)
	{
		$comp_nro = strtoupper($request->get('comp_nro'));
		$comp_fecha_ini = strtoupper($request->get('comp_fecha_ini'));
		$comp_fecha_fin = strtoupper($request->get('comp_fecha_fin'));
		$comp_guia = strtoupper($request->get('comp_guia'));
		$comp_cond = strtoupper($request->get('comp_cond'));
		$comp_moneda = strtoupper($request->get('comp_moneda'));
		$tcomp_id = strtoupper($request->get('tcomp_id'));
		$ent_id = strtoupper($request->get('ent_id'));
		$vend_id = strtoupper($request->get('vend_id'));
		$igv = strtoupper($request->get('igv'));

		$comprobantes = Comprobante::join('t_operacion','t_operacion.comp_id','=','t_comprobante.comp_id')->select('t_comprobante.*')->where('t_operacion.tope_id','=','2')->where('t_comprobante.comp_id','<>','1')->where('comp_nro','like','%'.$comp_nro.'%')->where('comp_guia','like','%'.$comp_guia.'%');

		if($comp_cond != "0")
		{
			$comprobantes = $comprobantes->where('comp_cond','=',$comp_cond);
		}
		if($comp_moneda != "0")
		{
			$comprobantes = $comprobantes->where('comp_moneda','=',$comp_moneda);
		}
		if($comp_fecha_ini != "")
		{
			$comprobantes = $comprobantes->where('comp_fecha','>=',$comp_fecha_ini);
		}
		if($comp_fecha_fin != "")
		{
			$comprobantes = $comprobantes->where('comp_fecha','<=',$comp_fecha_fin);
		}
		if($tcomp_id != "0")
		{
			$comprobantes = $comprobantes->where('tcomp_id','=',$tcomp_id);
		}
		if($ent_id != "0")
		{
			$comprobantes = $comprobantes->where('ent_id','=',$ent_id);
		}
		if($vend_id != "0")
		{
			$comprobantes = $comprobantes->where('vend_id','=',$vend_id);
		}
		if($igv=="C")
		{
			$comprobantes = $comprobantes->where('comp_igv','>','0');
		}
		if($igv=="S")
		{
			$comprobantes = $comprobantes->where('comp_igv','=','0');
		}


		$comprobantes=$comprobantes->orderBy('comp_fecha')->get();
		$entidades = Entidad::where('tent_id','2')->get(); // tipo proveedor
		$tipocomprobantes = TipoComprobante::all();

		return view('reporte.ingresoexcel',['comprobantes'=> $comprobantes]);
		
	}

	public function postDetallesalida(Request $request)
	{
		$comp_nro = strtoupper($request->get('comp_nro'));
		$comp_fecha_ini = strtoupper($request->get('comp_fecha_ini'));
		$comp_fecha_fin = strtoupper($request->get('comp_fecha_fin'));
		$comp_guia = strtoupper($request->get('comp_guia'));
		$comp_cond = strtoupper($request->get('comp_cond'));
		$comp_moneda = strtoupper($request->get('comp_moneda'));
		$tcomp_id = strtoupper($request->get('tcomp_id'));
		$ent_id = strtoupper($request->get('ent_id'));
		$prod_id = strtoupper($request->get('prod_id'));
		$vend_id = strtoupper($request->get('vend_id'));
		$igv = strtoupper($request->get('igv'));

		$detallecomprobantes = DetalleComprobante::join('t_unidadproducto','t_unidadproducto.up_id','=','t_detallecomprobante.up_id')->join('t_comprobante','t_comprobante.comp_id','=','t_detallecomprobante.comp_id')->join('t_operacion','t_operacion.comp_id','=','t_comprobante.comp_id')->select('t_detallecomprobante.*')->where('t_operacion.tope_id','=','2')->where('t_comprobante.comp_id','<>','1')->where('t_comprobante.comp_est','<>','ANULADO');

		if($comp_cond != "0")
		{
			$detallecomprobantes = $detallecomprobantes->where('comp_cond','=',$comp_cond);
		}
		if($comp_moneda != "0")
		{
			$detallecomprobantes = $detallecomprobantes->where('comp_moneda','=',$comp_moneda);
		}
		if($comp_fecha_ini != "")
		{
			$detallecomprobantes = $detallecomprobantes->where('comp_fecha','>=',$comp_fecha_ini);
		}
		if($comp_fecha_fin != "")
		{
			$detallecomprobantes = $detallecomprobantes->where('comp_fecha','<=',$comp_fecha_fin);
		}
		if($tcomp_id != "0")
		{
			$detallecomprobantes = $detallecomprobantes->where('tcomp_id','=',$tcomp_id);
		}
		if($ent_id != "0")
		{
			$detallecomprobantes = $detallecomprobantes->where('ent_id','=',$ent_id);
		}
		if($vend_id != "0")
		{
			$detallecomprobantes = $detallecomprobantes->where('vend_id','=',$vend_id);
		}
		if($igv=="C")
		{
			$detallecomprobantes = $detallecomprobantes->where('comp_igv','>','0');
		}
		if($igv=="S")
		{
			$detallecomprobantes = $detallecomprobantes->where('comp_igv','=','0');
		}
		if($prod_id != "0")
		{
			$detallecomprobantes = $detallecomprobantes->where('prod_id','=',$prod_id);
		}


		$detallecomprobantes=$detallecomprobantes->orderBy('comp_fecha')->orderBy('comp_nro')->get();
		$entidades = Entidad::where('tent_id','2')->get(); // tipo proveedor
		$tipocomprobantes = TipoComprobante::all();

		return view('reporte.detalleingresoexcel',['detallecomprobantes'=> $detallecomprobantes]);
		
	}

	public function missingMethod($parameters = array())
	{
		abort(404);
	}

}
