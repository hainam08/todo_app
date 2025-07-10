<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class Task extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = ['user_id', 'title','description', 'category', 'due_date','remind_at','is_reminder_enabled', 'status', 'priority'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    protected $casts = [
        'due_date' => 'date',
        'is_reminder_enabled'=>'boolean',
    ];
}
