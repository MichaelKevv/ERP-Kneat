<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class TbKategori
 * 
 * @property int $id
 * @property string $nama_kategori
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * 
 * @property Collection|TbProduk[] $tb_produks
 *
 * @package App\Models
 */
class TbKategori extends Model
{
	protected $table = 'tb_kategori';

	protected $fillable = [
		'nama_kategori'
	];

	public function tb_produks()
	{
		return $this->hasMany(TbProduk::class, 'id_kategori');
	}
}
