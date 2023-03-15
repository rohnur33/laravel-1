<?php

namespace App\Models;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class transaction extends Model
{
    use HasFactory,SoftDeletes;

    protected $fillable = [
        'user_id','food_id','qyt','total','status','payment_url'
    ];

    public function Food()
    {
        return $this->hasOne(food::class,'id','food_id');
    }
    public function user()
    {
        return $this->hasOne(User::class,'id','user_id');
    }

    public function getCreatedAtAtribute($value)
    {
        return Carbon::parse($value)->timestamp();
    }
    public function getUpdateAtribute($value)
    {
        return Carbon::parse($value)->timestamp();
    }
}
