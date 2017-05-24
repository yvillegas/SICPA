@extends('app')

@section('content')
@if (Session::has('creado'))
	<div class="alert alert-success">
		{{Session::get('creado')}}
	</div>
@endif
@if (Session::has('actualizado'))
	<div class="alert alert-success">
		{{Session::get('actualizado')}}
	</div>
@endif
@if (Session::has('eliminado'))
	<div class="alert alert-success">
		{{Session::get('eliminado')}}
	</div>
@endif
<div class="container-fluid">
	<div class="col-md-12 col-md-offset-0">
		<div class="panel panel-default">
			<div class="panel-heading">Búsqueda</div>
			<div class="panel-body">
				<form class="form-inline" role="form" method="POST" action="/validado/notacreditoemitida">
					<input type="hidden" name="_token" value="{{ csrf_token() }}">
					
					<div class="form-group col-md-offset-0">
						<label>Nro</label>
						<div>
							<input type="text" class="form-control text-uppercase" name="comp_nro">
						</div>
					</div>
					<div class="form-group col-md-offset-0">
						<label>Cliente</label>
						<div>
							<select class="form-control text-uppercase" name="ent_id">
								<option  value=0>Elija Cliente</option>
								@foreach ($entidades as $entidad)
								   <option  value='{{$entidad->ent_id}}'>{{$entidad->ent_rz}}</option>
								@endforeach
							</select>
						</div>
					</div>
					<div class="form-group col-md-offset-0">
						<label>Tipo</label>
						<div>
							<select class="form-control text-uppercase" name="tcompinc_id">
								<option  value=0>Elija Tipo</option>
							   @foreach ($tipocomprobanteincs as $tipocomprobanteinc)
							   		<option  value='{{$tipocomprobanteinc->tcompinc_id}}'>{{$tipocomprobanteinc->tcompinc_desc}}</option>
								@endforeach
							</select>
						</div>
					</div>
					<div class="form-group col-md-offset-0">
						<label>Fecha</label>
						<div>
							<input type="date" class="form-control text-uppercase" name="comp_fecha_ini">
							<input type="date" class="form-control text-uppercase" name="comp_fecha_fin">
						</div>
					</div>
					<div class="form-group col-md-offset-0">
						<label>Moneda</label>
						<div>
							<select class="form-control text-uppercase" name="comp_moneda">
								<option  value=0>Elija Moneda</option>
							   <option value="DOLAR">DOLÁR AMERICANO</option>
							   <option value="SOLES">SOLES</option>
							</select>
						</div>
					</div>
					<div class="form-group col-md-offset-0">
						<label>Vendedor</label>
						<div>
							<select class="form-control text-uppercase" name="vend_id">
								<option  value=0>Elija Vendedor</option>
							   @foreach ($vendedores as $vendedor)
							   		<option  value='{{$vendedor->vend_id}}'>{{$vendedor->vend_nom}}</option>
								@endforeach
							</select>
						</div>
					</div>
					<!--<div class="form-group col-md-offset-0">
						<label>IGV</label>
						<div>
							<input type="radio" name="igv" value="C">CON IGV</input>
							<input type="radio" name="igv" value="S">SIN IGV</input>
							<input type="radio" name="igv" value="A">AMBOS</input>
						</div>
					</div>-->
					<div class="col-md-offset-0">
						</br>
						<button type="submit" name="buscar" value="buscar" class="btn btn-default">
							<img src="/images/buscar.png" title="BUSCAR">
						</button>
						<button type="submit" name="imprimir" value="imprimir" class="btn btn-default">
							<img src="/images/imprimir.png" title="IMPRIMIR">
						</button>
					</div>
					
				</form>
			</div>
		</div>
	</div>

	<div class="col-md-12 col-md-offset-0">
		<div class="panel panel-default">
			<div class="panel-heading">Notas de Crédito</div>

			<div class="panel-body">
				<a href="/validado/notacreditoemitida/crear" class="btn btn-success" role="button">Nueva Nota de Crédito</a>
				<br/><br/>
				<table class="table">
						<tr>
							<th>Nro.</th>
							<th>Tipo</th>
							<th>Cliente</th>
							<th>Fecha</th>
							<th>Subtotal</th>
							<th>IGV</th>
							<th>Total</th>
							<th>Moneda</th>
							<th>Tipo de Cambio</th>	
							<th width="230">Acciones</th>	
						</tr>

				@if(sizeof($comprobantes)>0)
					

					@foreach ($comprobantes as $comprobante)
						<tr>
							<td>{{$comprobante->comp_nro}}</td>
							<td>{{$comprobante->tipocomprobanteinc->tcompinc_desc}}</td>
							<td>{{$comprobante->entidad->ent_rz}}</td>
							<td>{{date('d/m/Y', strtotime($comprobante->comp_fecha))}}</td>
							<td>{{number_format($comprobante->comp_subt,2,'.',',')}}</td>
							<td>{{number_format($comprobante->comp_igv,2,'.',',')}}</td>
							<td>{{number_format($comprobante->comp_tot,2,'.',',')}}</td>
							<td>{{$comprobante->comp_moneda}}</td>
							<td>{{$comprobante->comp_tipcambio}}</td>
							<td>

							<a href="/validado/detallenotacreditoemitida?comp_id={{$comprobante->comp_id}}"><img src="/images/detalle.png"  title="VER DETALLE"></a>
							<a href="/validado/notacreditoemitida/editar?comp_id={{$comprobante->comp_id}}"><img src="/images/editar.png" title="EDITAR"></a>
							<a href="/validado/notacreditoemitida/eliminar?comp_id={{$comprobante->comp_id}}" onclick="return confirm('Esta seguro que desea eliminar?')"><img src="/images/eliminar.png" title="ELIMINAR"></a>
							
							</td>
						</tr>
					@endforeach

				@else
					<div class="alert alert-danger">
						<p>Al parecer no tiene comprobantes</p>
					</div>
				@endif

				</table>

			</div>
		</div>
	</div>
</div>
@endsection
