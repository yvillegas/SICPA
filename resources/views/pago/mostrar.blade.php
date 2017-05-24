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
	<div class="col-md-9 col-md-offset-0">
		<div class="panel panel-default">
			<div class="panel-heading">Historial de Pagos</div>

			<div class="panel-body">
				<table class="table">
						<tr>
							<th>Fecha</th>
							<th>Monto</th>
							<th>Entidad</th>
							<th>Nro. de Operación</th>
							@if($comprobante->comp_moneda=="DOLAR")
							<th>Tip. Cambio</th>
							@endif
							<th>Acciones</th>							
						</tr>

				@if(sizeof($pagos)>0)
					

					@foreach ($pagos as $pago)
						<tr>
							<td>{{date('d/m/Y', strtotime($pago->pago_fecha))}}</td>
							<td>{{$moneda.$pago->pago_monto}}</td>
							<td>{{$pago->pago_banco}}</td>
							<td>{{$pago->pago_nope}}</td>
							@if($comprobante->comp_moneda=="DOLAR")
							<td>{{$pago->pago_tipcambio}}</td>
							@endif
							<td>
							<a href="/validado/pago/eliminar?pago_id={{$pago->pago_id}}" onclick="return confirm('Esta seguro que desea eliminar?')" class="btn btn-danger">Eliminar</a>
						</tr>
					@endforeach
				@else
					<div class="alert alert-danger">
						<p>Al parecer no tiene pagos</p>
					</div>
				@endif
						<tr></tr>
						<tr>
							<td></td>
							<td></td>
							<td></td>
							<td>Monto Inicial {{$moneda.$comprobante->comp_tot}}</td>
							<td>Saldo Actual {{$moneda.$comprobante->comp_saldo}}</td>
						</tr>
				</table>

			</div>
		</div>
		<div class="form-group">
			<div class="col-md-9 col-md-offset-0">
				<a href="/validado/salida" class="btn btn-danger" role="button">Regresar</a>
			</div>
		</div>
	</div>

	<div class="col-md-3 col-md-offset-0">
		<div class="panel panel-default">
			<div class="panel-heading">Nueva Cuota</div>
			<div class="panel-body">
					<form class="form-horizontal" role="form" method="POST" action="/validado/pago/crear">
						<input type="hidden" name="_token" value="{{ csrf_token() }}">
						<input type="hidden" name="comp_id" value="{{$comp_id}}" >

						<div class="form-group">
							<label class="col-md-3 control-label">Fecha</label>
							<div class="col-md-8 col-md-offset-1">
								<input type="date" class="form-control text-uppercase" name="pago_fecha">
							</div>
						</div>
						<div class="form-group">
							<label class="col-md-3 control-label">Monto</label>
							<div class="col-md-8 col-md-offset-1">
								<input type="text" class="form-control text-uppercase" name="pago_monto">
							</div>
						</div>
						<div class="form-group">
							<label class="col-md-3 control-label">Entidad Finaciera</label>
							<div class="col-md-8 col-md-offset-1">
								<input type="text" class="form-control text-uppercase" name="pago_banco">
							</div>
						</div>
						<div class="form-group">
							<label class="col-md-3 control-label">Nro. de Operación</label>
							<div class="col-md-8 col-md-offset-1">
								<input type="text" class="form-control text-uppercase" name="pago_nope">
							</div>
						</div>
						@if($comprobante->comp_moneda=="DOLAR")
						
							<div class="form-group">
								<label class="col-md-3 control-label">Tipo de Cambio</label>
								<div class="col-md-8 col-md-offset-1">
									<input type="text" class="form-control text-uppercase" name="pago_tipcambio">
								</div>
							</div>
						@endif

						<div class="form-group">
							<div class="col-md-3 col-md-offset-2">
								<button type="submit" class="btn btn-primary">
									Guardar
								</button>
							</div>
							<div class="col-md-3 col-md-offset-2">
								<a href="/validado/pago?comp_id={{$comp_id}}" class="btn btn-danger" role="button">Cancelar</a>
							</div>
						</div>
					</form>
				</div>
		</div>
	</div>
</div>
@endsection
