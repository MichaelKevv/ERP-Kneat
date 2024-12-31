<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class TbRfqDetail
 * 
 * @property int $id
 * @property int $id_rfq
 * @property int $id_bahanbaku
 * @property int $kuantitas
 * @property int $total
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * 
 * @property TbBahanbaku $tb_bahanbaku
 * @property TbRfq $tb_rfq
 *
 * @package App\Models
 */
class TbRfqDetail extends Model
{
	protected $table = 'tb_rfq_detail';

	protected $casts = [
		'id_rfq' => 'int',
		'id_bahanbaku' => 'int',
		'kuantitas' => 'int',
		'total' => 'int'
	];

	protected $fillable = [
		'id_rfq',
		'id_bahanbaku',
		'kuantitas',
		'total'
	];

	public function tb_bahanbaku()
	{
		return $this->belongsTo(TbBahanbaku::class, 'id_bahanbaku');
	}

	public function tb_rfq()
	{
		return $this->belongsTo(TbRfq::class, 'id_rfq');
	}
}
