<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ColabPassword extends Model
{
    use HasFactory;

    protected $table = 'colab_password';

    protected $fillable = [
        'candidato_id',
        'password'
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }
}
