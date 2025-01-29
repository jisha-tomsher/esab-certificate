<?php

namespace App\Models;

use App\Models\Certificates\Certificate;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VisitorHistory extends Model
{
    use HasFactory;

    protected $fillable = [
        'email',
        'certificate_id',
    ];

    public function certificate(){
        return $this->hasOne(Certificate::class,'id','certificate_id');
    }
}
