<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Task
 * @package App\Models
 * @property $title
 * @property $status
 */
class Task extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'status'];
}
