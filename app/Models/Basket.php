<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Basket extends Model{
	protected $fillable = array('id_beli', 'id_buku', 'jumlahbeli', 'subtotal');

	public $timestamps = true;

	public function purchase(){
		return $this->belongsTo('App\Models\Purchase', 'id_beli', 'id');
	}

	public function book(){
		return $this->belongsTo('App\Models\Book', 'id_buku', 'id');
	}
}
?>