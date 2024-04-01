<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssetUser extends Model
{
    use HasFactory;
    protected $table = 'asset_user';

    public function blogs(){
        return $this->hasMany(Asset::class, 'created_by')->whereRaw('sport is not null and sport!=""');
    }
}


