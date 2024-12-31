<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class TbInventory
 * 
 * @property int $id
 * @property int|null $id_bahanbaku
 * @property int|null $id_produk
 * @property float $on_hand
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * 
 * @property TbProduk|null $tb_produk
 * @property TbBahanbaku|null $tb_bahanbaku
 *
 * @package App\Models
 */
class TbInventory extends Model
{
	protected $table = 'tb_inventory';

	protected $casts = [
		'id_bahanbaku' => 'int',
		'id_produk' => 'int',
		'on_hand' => 'float'
	];

	protected $fillable = [
		'id_bahanbaku',
		'id_produk',
		'on_hand'
	];

	public function tb_produk()
	{
		return $this->belongsTo(TbProduk::class, 'id_produk');
	}

	public function tb_bahanbaku()
	{
		return $this->belongsTo(TbBahanbaku::class, 'id_bahanbaku');
	}
}
