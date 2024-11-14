<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class TbBomDetail
 * 
 * @property int $id
 * @property int $id_bom
 * @property int $id_bahanbaku
 * @property float $kuantitas_bahan
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property int $reserved
 * 
 * @property TbBahanbaku $tb_bahanbaku
 * @property TbBom $tb_bom
 *
 * @package App\Models
 */
class TbBomDetail extends Model
{
	protected $table = 'tb_bom_detail';

	protected $casts = [
		'id_bom' => 'int',
		'id_bahanbaku' => 'int',
		'kuantitas_bahan' => 'float',
		'reserved' => 'int'
	];

	protected $fillable = [
		'id_bom',
		'id_bahanbaku',
		'kuantitas_bahan',
		'reserved'
	];

	public function tb_bahanbaku()
	{
		return $this->belongsTo(TbBahanbaku::class, 'id_bahanbaku');
	}

	public function tb_bom()
	{
		return $this->belongsTo(TbBom::class, 'id_bom');
	}
}
