<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class TbRfq
 *
 * @property int $id
 * @property int $id_vendor
 * @property string $kode_rfq
 * @property Carbon $tanggal_order
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @property TbVendor $tb_vendor
 * @property Collection|TbRfqDetail[] $tb_rfq_details
 *
 * @package App\Models
 */
class TbRfq extends Model
{
    protected $table = 'tb_rfq';

    protected $casts = [
        'id_vendor' => 'int',
        'tanggal_order' => 'datetime'
    ];

    protected $fillable = [
        'id_vendor',
        'kode_rfq',
        'tanggal_order'
    ];

    public function tb_vendor()
    {
        return $this->belongsTo(TbVendor::class, 'id_vendor');
    }

    public function tb_rfq_details()
    {
        return $this->hasMany(TbRfqDetail::class, 'id_rfq');
    }
}
