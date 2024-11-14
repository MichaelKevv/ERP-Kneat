<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class TbVendor
 * 
 * @property int $id
 * @property string $tipe_vendor
 * @property string $nama
 * @property string $alamat
 * @property string|null $npwp
 * @property string $email
 * @property string $no_hp
 * @property string|null $foto
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @package App\Models
 */
class TbVendor extends Model
{
	protected $table = 'tb_vendor';

	protected $fillable = [
		'tipe_vendor',
		'nama',
		'alamat',
		'npwp',
		'email',
		'no_hp',
		'foto'
	];
}
