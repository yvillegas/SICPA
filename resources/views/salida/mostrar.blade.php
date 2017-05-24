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
				<form class="form-inline" role="form" method="POST" action="/validado/salida">
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
						<label>Guía de Remisión</label>
						<div>
							<input type="text" class="form-control text-uppercase" name="comp_guia">
						</div>
					</div>
					<div class="form-group col-md-offset-0">
						<label>Tipo</label>
						<div>
							<select class="form-control text-uppercase" name="tcomp_id">
								<option  value=0>Elija Tipo</option>
							   @foreach ($tipocomprobantes as $tipocomprobante)
							   		<option  value='{{$tipocomprobante->tcomp_id}}'>{{$tipocomprobante->tcomp_desc}}</option>
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
						<label>Condición</label>
						<div>
							<select class="form-control text-uppercase" name="comp_cond">
									<option  value=0>Elija Condición</option>
									<option >AL CONTADO</option>
									<option >MUESTRA GRATUITA</option>
									<option >AL CREDITO</option>
									<option >Otro</option>
							</select>
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
					<div class="form-group col-md-offset-0">
						<label>IGV</label>
						<div>
							<input type="radio" name="igv" value="C">CON IGV</input>
							<input type="radio" name="igv" value="S">SIN IGV</input>
							<input type="radio" name="igv" value="A">AMBOS</input>
						</div>
					</div>
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
			<div class="panel-heading">Venta</div>

			<div class="panel-body">
				<a href="/validado/salida/crear" class="btn btn-success" role="button">Nueva Venta</a>
				<br/><br/>
				<table class="table">
						<tr>
							<th>Nro.</th>
							<th>Tipo</th>
							<th>Cliente</th>
							<th>Fecha</th>
							<th>Guía</th>
							<th>Subtotal</th>
							<th>IGV</th>
							<th>Total</th>
							<th>Saldo</th>
							<th>Estado</th>
							<th>Condición</th>
							<th>Días</th>
							<th>Moneda</th>
							<th>Tipo de Cambio</th>	
							<th width="230">Acciones</th>	
						</tr>

				@if(sizeof($comprobantes)>0)
					

					@foreach ($comprobantes as $comprobante)
						<tr>
							<td>{{$comprobante->comp_nro}}</td>
							<td>{{$comprobante->tipocomprobante->tcomp_desc}}</td>
							<td>{{$comprobante->entidad->ent_rz}}</td>
							<td>{{date('d/m/Y', strtotime($comprobante->comp_fecha))}}</td>
							<td>{{$comprobante->comp_guia}}</td>
							<td>{{number_format($comprobante->comp_subt,2,'.',',')}}</td>
							<td>{{number_format($comprobante->comp_igv,2,'.',',')}}</td>
							<td>{{number_format($comprobante->comp_tot,2,'.',',')}}</td>
							<td>{{number_format($comprobante->comp_saldo,2,'.',',')}}</td>
							<td>{{$comprobante->comp_est}}</td>
							<td>{{$comprobante->comp_cond}}</td>
							@if($comprobante->comp_cond=='AL CREDITO')
							<?php $hoy=(strtotime(date('Y-m-d',strtotime($comprobante->comp_fven)))-strtotime(date('Y-m-d')))/86400; ?>
								<td>{{$hoy}}</td>
							@else
								<td>-</td>
							@endif
							<td>{{$comprobante->comp_moneda}}</td>
							<td>{{$comprobante->comp_tipcambio}}</td>
							<td>

							<a href="/validado/detallesalida?comp_id={{$comprobante->comp_id}}"><img src="/images/detalle.png"  title="VER DETALLE"></a>
							<a href="/validado/salida/editar?comp_id={{$comprobante->comp_id}}"><img src="/images/editar.png" title="EDITAR"></a>
							<a href="/validado/salida/eliminar?comp_id={{$comprobante->comp_id}}" onclick="return confirm('Esta seguro que desea eliminar?')"><img src="/images/eliminar.png" title="ELIMINAR"></a>
							@if($comprobante->comp_saldo>0)
								<a href="/validado/pago?comp_id={{$comprobante->comp_id}}"><img src="/images/pagar.png" title="REALIZAR PAGO"></a>
							@endif
							@if($comprobante->comp_est!='ANULADO')
								<a href="/validado/salida/sanular?comp_id={{$comprobante->comp_id}}" onclick="return confirm('Esta seguro que desea anular?')"><img src="/images/anular.png" title="ANULAR"></a>
								<a href="/validado/notacredito/crearncemitida?comp_id={{$comprobante->comp_id}}" onclick="return confirm('Esta seguro que desea anular con NOTA DE CRÉDITO?')"><img src="/images/nc.png" title="ANULAR CON NOTA DE CRÉDITO"></a>
							@endif
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
