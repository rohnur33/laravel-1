<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class food extends Model
{
    use HasFactory,SoftDeletes;
}
protected $fillable = [
'name','description','ingredients','price','rate','types','picturespath'
];

public function getCreatedAtAtribute($value)
{
    return Carbon::parse($value)->timestamp();
}
public function getUpdateAtribute($value)
{
    return Carbon::parse($value)->timestamp();
}
public function toArray()
{
    $toArray = parent::toArray();
    $toArray['picturespath'] = $this->picturespath;
    return toArray;
}

    public function getPicturePathAttribute()
    {
        return url('') . Storage::url($this->attributes['picturespath']);
    }