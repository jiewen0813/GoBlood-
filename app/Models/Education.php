<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Education extends Model
{
    use HasFactory;

    protected $table = 'educations';

    // Define the fillable fields
    protected $fillable = [
        'title',
        'content',
        'thumbnail',
        'created_by',
    ];

    /**
     * Relationship to BloodBankAdmin.
     * This allows accessing the admin who created the educational resource.
     */
    public function bloodBankAdmin()
    {
        return $this->belongsTo(BloodBankAdmin::class, 'created_by');
    }
}
