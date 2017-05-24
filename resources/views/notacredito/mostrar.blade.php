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
	<div class="col-md-8 col-md-offset-2">
		<div class="panel panel-default">
			<div class="panel-heading">Notas de Credito</div>

			<div class="panel-body">
				<br/><br/>
				<table class="table">
						<tr>
							<th>Nro. Nota de Crédito</th>
							<th>Comprobante</th>
							<th>Total</th>
							<th>Observaciones</th>
						</tr>

				@if(sizeof($notacreditos)>0)
					

					@foreach ($notacreditos as $notacredito)
						<tr>
							<td>{{$notacredito->ncred_num}}</td>
							<td>{{$notacredito->comprobante->comp_nro}}</td>
							<td>{{number_format($notacredito->ncred_tot,2,'.',',')}}</td>
							<td>{{$notacredito->ncred_obs}}</td>
						</tr>
					@endforeach

				@else
					<div class="alert alert-danger">
						<p>Al parecer no tiene Notas de credito</p>
					</div>
				@endif

				</table>

			</div>
		</div>
	</div>
</div>
@endsection
