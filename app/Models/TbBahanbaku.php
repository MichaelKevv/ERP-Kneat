<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class TbBahanbaku
 * 
 * @property int $id
 * @property string $nama
 * @property int $harga_beli
 * @property string|null $internal_reference
 * @property string $barcode
 * @property string|null $satuan
 * @property string|null $note
 * @property string|null $foto
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * 
 * @property Collection|TbBomDetail[] $tb_bom_details
 *
 * @package App\Models
 */
class TbBahanbaku extends Model
{
	protected $table = 'tb_bahanbaku';

	protected $casts = [
		'harga_beli' => 'int'
	];

	protected $fillable = [
		'nama',
		'harga_beli',
		'internal_reference',
		'barcode',
		'satuan',
		'note',
		'foto'
	];

	public function tb_bom_details()
	{
		return $this->hasMany(TbBomDetail::class, 'id_bahanbaku');
	}
}
