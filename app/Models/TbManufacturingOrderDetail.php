<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class TbManufacturingOrderDetail
 * 
 * @property int $id
 * @property int $id_manufacturing_order
 * @property int $id_bahanbaku
 * @property int $reserved
 * @property int $consumed
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * 
 * @property TbBahanbaku $tb_bahanbaku
 * @property TbManufacturingOrder $tb_manufacturing_order
 *
 * @package App\Models
 */
class TbManufacturingOrderDetail extends Model
{
	protected $table = 'tb_manufacturing_order_detail';

	protected $casts = [
		'id_manufacturing_order' => 'int',
		'id_bahanbaku' => 'int',
		'reserved' => 'int',
		'consumed' => 'int'
	];

	protected $fillable = [
		'id_manufacturing_order',
		'id_bahanbaku',
		'reserved',
		'consumed'
	];

	public function tb_bahanbaku()
	{
		return $this->belongsTo(TbBahanbaku::class, 'id_bahanbaku');
	}

	public function tb_manufacturing_order()
	{
		return $this->belongsTo(TbManufacturingOrder::class, 'id_manufacturing_order');
	}
}
