<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Purchase extends Model{
	protected $fillable = array('id_user', 'tglbeli', 'total_beli','total_bayar');

	public $timestamps = true;

	public function basket(){
		return $this->hasMany('App\Models\Basket', 'id_beli', 'id');
	}

	public function user(){
		return $this->belongsTo('App\Models\User', 'id_user', 'id');
	}
}
?>