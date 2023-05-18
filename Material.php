<?php

require 'bootstrap.php';

use Illuminate\Database\Eloquent\Model;

class Material extends Model
{
    protected $table = 'materials';

    protected $fillable = [
        'title',
        'description',
        'anoFundamental',
        'disciplina',
        'assuntos',
        'pathToFile',
        'url',
        'img',
    ];

    public $timestamps = false;
}
