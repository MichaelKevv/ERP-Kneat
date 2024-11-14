<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class TbManufacturingOrder
 * 
 * @property int $id
 * @property int $id_produk
 * @property int $id_bom
 * @property string $kode_mo
 * @property int $kuantitas_produk
 * @property string $status
 * @property Carbon $tanggal_produksi
 * @property Carbon $tanggal_deadline
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * 
 * @property TbBom $tb_bom
 * @property TbProduk $tb_produk
 * @property Collection|TbManufacturingOrderDetail[] $tb_manufacturing_order_details
 *
 * @package App\Models
 */
class TbManufacturingOrder extends Model
{
	protected $table = 'tb_manufacturing_order';

	protected $casts = [
		'id_produk' => 'int',
		'id_bom' => 'int',
		'kuantitas_produk' => 'int',
		'tanggal_produksi' => 'datetime',
		'tanggal_deadline' => 'datetime'
	];

	protected $fillable = [
		'id_produk',
		'id_bom',
		'kode_mo',
		'kuantitas_produk',
		'status',
		'tanggal_produksi',
		'tanggal_deadline'
	];

	public function tb_bom()
	{
		return $this->belongsTo(TbBom::class, 'id_bom');
	}

	public function tb_produk()
	{
		return $this->belongsTo(TbProduk::class, 'id_produk');
	}

	public function tb_manufacturing_order_details()
	{
		return $this->hasMany(TbManufacturingOrderDetail::class, 'id_manufacturing_order');
	}
}
