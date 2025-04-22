<?php

namespace App\Models;

use Illuminate\Cache\HasCacheLock;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Project extends Model
{
    use HasFactory;
    protected $fillable = ['name'];

    public function tasks()
    {
        return $this->hasMany(Task::class)->orderBy('priority');
    }
}
