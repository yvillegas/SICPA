<?php namespace SICPA;

use Illuminate\Database\Eloquent\Model;

class OrdenCV extends Model {

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 't_ordencv';

    protected $primaryKey="ocv_id";
	protected $fillable = [
	'ocv_nro','ocv_fecha','ocv_est','ocv_subt',
	'ocv_igv','ocv_tot','ocv_cond',
	'ocv_tipcambio','ocv_moneda','ocv_tipo',
	'ent_id','ocv_doc'];

	public function entidad(){
		return $this->belongsTo('SICPA\Entidad','ent_id');
	}
	public function detallesordencv(){
		return $this->hasMany('SICPA\DetalleOrdenCV');
	}
}
