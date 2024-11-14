<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class TbProduk
 * 
 * @property int $id
 * @property string $nama_produk
 * @property int $id_kategori
 * @property int $harga_jual
 * @property int $biaya_produk
 * @property string $exp
 * @property string|null $internal_reference
 * @property string $barcode
 * @property string|null $satuan
 * @property int|null $pajak
 * @property string|null $note
 * @property string|null $foto
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * 
 * @property TbKategori $tb_kategori
 * @property Collection|TbBom[] $tb_boms
 *
 * @package App\Models
 */
class TbProduk extends Model
{
	protected $table = 'tb_produk';

	protected $casts = [
		'id_kategori' => 'int',
		'harga_jual' => 'int',
		'biaya_produk' => 'int',
		'pajak' => 'int'
	];

	protected $fillable = [
		'nama_produk',
		'id_kategori',
		'harga_jual',
		'biaya_produk',
		'exp',
		'internal_reference',
		'barcode',
		'satuan',
		'pajak',
		'note',
		'foto'
	];

	public function tb_kategori()
	{
		return $this->belongsTo(TbKategori::class, 'id_kategori');
	}

	public function tb_boms()
	{
		return $this->hasMany(TbBom::class, 'id_produk');
	}
}
