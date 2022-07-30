<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Company;
class Hotel extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'local',
        'company_id',
        'image',
        'type',
        'vote',
        'price',
        'sale',
        'desc',
        'city_id'
    ];
    public function company(){
        return $this->belongsTo(Company::class, 'company_id', 'id');
    }
    public function hotel_detail(){
        return $this->hasOne(HotelDetail::class );
    }
}
