<script type="text/javascript">
	function regresar()
	{
		window.location.href="/validado/detallesalida/regresar?comp_id={{$comprobante->comp_id}}" ;
	}
</script>
<!--onload="javascript:history.back()"-->
<body onload="regresar()">

<?php
//Creamos el archivo datos.txt
//ponemos tipo "a' para añadir lineas sin borrar

	$doc_corre="0000000001";
	$fechaemision=date('ymd', strtotime($comprobante->comp_fecha));
	$tipocomp=$comprobante->tipocomprobante->tcomp_cod;
	$rucemisor="20447609674";

	if($comprobante->comp_fven=="0000-00-00")
		$fechavenc='';
	else
		$fechavenc=date('ymd', strtotime($comprobante->comp_fven));

	if($comprobante->comp_moneda=='SOLES')
			$moneda='USD';
	else
		$moneda='PEN';

	$totalbase=number_format($comprobante->comp_subt-$comprobante->comp_desc,2,'.',',');
	$nro_serie=substr($comprobante->comp_nro,0,strpos($comprobante->comp_nro, '-'));
	$nro_doc=substr($comprobante->comp_nro,strpos($comprobante->comp_nro, '-')+1,strlen($comprobante->comp_nro));

	if(strlen($comprobante->entidad->ent_ruc)>8)
		$tipodoc_iden='06';
	else
		$tipodoc_iden='01';

	if($comprobante->comp_cond=="MUESTRA GRATUITA")
	{
		$trans_gratuita='01';
		$desc_global='01';
	}
	else
	{
		$trans_gratuita='00';
		$desc_global='00';
	}

	if($comprobante->comp_igv>0)
		$tipo_exo='GRAVADO';
	else
		$tipo_exo='EXONERADO';

	// NOMBRE ARCHIVO
	
	$nombre=$rucemisor."001"."DOC".$doc_corre.$fechaemision.$tipocomp;

	$file=fopen($nombre.".txt","a") or die("Problemas");
	//vamos añadiendo el contenido

	// CABECERA ARCHIVO

	$cabecera=$comprobante->comp_id."|".$tipocomp."|"."00"."|".$rucemisor."|".$comprobante->entidad->ent_ruc."|".$fechaemision."|".$fechavenc."|".$moneda."|".$comprobante->comp_subt."|".$comprobante->comp_desc."|".$totalbase."|"."0.00"."|".$comprobante->comp_igv."|"."0.00"."|"."0.00"."|".$comprobante->comp_tot."|"."0"."|"."NA"."|".""."|".$nro_serie."|".$nro_doc."|".$comprobante->vend_id."|".$comprobante->comp_id."|".""."|".$tipodoc_iden."|"."01"."|"."NO"."|".""."|".""."|".$trans_gratuita."|".$desc_global."|"."00"."|".""."|"."";
	
	fputs($file,$cabecera);
	fputs($file,PHP_EOL);

	$detalle="";
	$cont=1;
	foreach($detallecomprobantes as $detallecomprobante)
	{
		$correlativo=str_pad($cont,10, "0", STR_PAD_LEFT);
		$uni_cod=$detallecomprobante->unidadproducto->unidadmedida->unidad->uni_cod;
		$prod_cod=$detallecomprobante->unidadproducto->producto->prod_cod;
		$prod_desc=$detallecomprobante->unidadproducto->producto->prod_desc;
		$imp_tot=$detallecomprobante->dcomp_cant*$detallecomprobante->dcomp_prec - $prod_desc;		

		if($detallecomprobante->unidadproducto->producto->prod_exo=='NO')
			$prod_igv=number_format(($imp_tot - $imp_tot/1.18),2,'.',',');
		else
			$prod_igv="0.00";

		$base_impo=$imp_tot-$prod_igv;

		$detalle=$correlativo."|".$doc_corre."|"."BIEN"."|".$tipo_exo."|".$uni_cod."|".$prod_cod."|".$prod_desc."|".rtrim($detallecomprobante->dcomp_cant,'.0')."|".number_format(($detallecomprobante->dcomp_prec),2,'.',',')."|".$detallecomprobante->dcomp_desc."|".$base_impo."|".$prod_igv."|"."0.00"."|"."0"."|"."0.00"."|"."0.00"."|".number_format($imp_tot,2,'.',',')."|"."00";
		fputs($file,$detalle);
		fputs($file,PHP_EOL);
		$cont++;
	}

	$cabecera="C"."|".$comprobante->entidad->ent_ruc."|".$comprobante->entidad->ent_rz."|".$comprobante->entidad->ent_dir."|".$comprobante->entidad->ent_tel."|".$comprobante->entidad->ent_email."|"."PE"."|".$comprobante->entidad->ent_dpto."|".$comprobante->entidad->ent_ciu."|".$comprobante->entidad->ent_cont."|".$comprobante->entidad->ent_rz."|".$tipodoc_iden;
	
	fputs($file,$cabecera);
	fputs($file,PHP_EOL);

	fclose($file);

?>

</body>