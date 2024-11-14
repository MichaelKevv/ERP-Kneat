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
 * @property int $id_bahanbaku
 * @property float $on_hand
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * 
 * @property TbBahanbaku $tb_bahanbaku
 *
 * @package App\Models
 */
class TbInventory extends Model
{
	protected $table = 'tb_inventory';

	protected $casts = [
		'id_bahanbaku' => 'int',
		'on_hand' => 'float'
	];

	protected $fillable = [
		'id_bahanbaku',
		'on_hand'
	];

	public function tb_bahanbaku()
	{
		return $this->belongsTo(TbBahanbaku::class, 'id_bahanbaku');
	}
}
