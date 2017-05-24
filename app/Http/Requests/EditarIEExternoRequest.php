<?php namespace SICPA\Http\Requests;

use SICPA\Http\Requests\Request;

class EditarIEExternoRequest extends Request {

	/**
	 * Determine if the user is authorized to make this request.
	 *
	 * @return bool
	 */
	public function authorize()
	{
		return true;
	}

	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array
	 */
	public function rules()
	{
		return [
			/*'ie_comp' =>'required',
			'ie_ruc' =>'required',
			'ie_rz' =>'required',*/
			'ie_fecha' =>'required',
			/*'ie_tcomp' =>'required',	
			'ie_moneda' =>'required',
			'ie_tipcambio' =>'required'*/
		];
	}

}
