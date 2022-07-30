<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Type;
use App\Models\City;

class Tour extends Model
{
    use HasFactory;
    protected $fillable = [
        'company_id',
        'name',
        'local',
        'time',
        'price',
        'desc',
        'image'
    ];
    public function company(){
        return $this->hasOne(Company::class);
    }
    public function types(){
        return $this->belongsToMany(Type::class);
    }
    public function city(){
        return $this->hasOne(City::class);
    }
}
