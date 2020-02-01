<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Book extends Model{
	protected $fillable = array('judul_buku', 'penerbit', 'tahun_terbit', 'penulis','deskripsi','harga','foto','id_distributor');

	public $timestamps = true;

	public function distributor(){
		return $this->belongsTo('App\Models\Distributor', 'id_distributor', 'id');
	}

	public function basket(){
		return $this->hasMany('App\Models\Basket', 'id_buku', 'id');
	}
}
?>