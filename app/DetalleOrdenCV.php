<?php namespace SICPA;

use Illuminate\Database\Eloquent\Model;

class DetalleOrdenCV extends Model {

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 't_detalleordencv';

    protected $primaryKey="docv_id";
	protected $fillable = ['docv_cant','docv_prec','ocv_id','up_id'];
	public function ordencv(){
		return $this->belongsTo('SICPA\OrdenCV','ocv_id');
	}
	public function unidadproducto(){
		return $this->belongsTo('SICPA\UnidadProducto','up_id');
	}
}
