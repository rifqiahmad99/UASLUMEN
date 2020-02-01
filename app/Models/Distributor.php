<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class Distributor extends Model{
	protected $fillable = array('nama_distributor', 'alamat_distributor', 'notelfon', 'email');
	public $timestamps = true;

	public function books(){
		return $this->hasMany('App\Models\Book', 'id_distributor', 'id');
	}
}