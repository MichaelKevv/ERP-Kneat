<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class TbBom
 * 
 * @property int $id
 * @property int $id_produk
 * @property string $reference
 * @property int $kuantitas
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * 
 * @property TbProduk $tb_produk
 * @property Collection|TbBomDetail[] $tb_bom_details
 *
 * @package App\Models
 */
class TbBom extends Model
{
	protected $table = 'tb_bom';

	protected $casts = [
		'id_produk' => 'int',
		'kuantitas' => 'int'
	];

	protected $fillable = [
		'id_produk',
		'reference',
		'kuantitas'
	];

	public function tb_produk()
	{
		return $this->belongsTo(TbProduk::class, 'id_produk');
	}

	public function tb_bom_details()
	{
		return $this->hasMany(TbBomDetail::class, 'id_bom');
	}
}
