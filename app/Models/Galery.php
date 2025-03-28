<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Galery extends Model
{
    protected $fillable =  [
        'name',
        'photo_path',
        'description',
    ];

    protected static function boot()
    {
        parent::boot();

        // Delete image when the model is deleted
        static::deleted(function ($model) {
            if ($model->photo_path) {
                Storage::disk('public')->delete($model->photo_path);
            }
        });

        // Delete old image when updating the model
        static::updating(function ($model) {
            // Check if 'photo_path' is changing
            if ($model->isDirty('photo_path')) {
                $oldImage = $model->getOriginal('photo_path');

                if ($oldImage) {
                    Storage::disk('public')->delete($oldImage);
                }
            }
        });
    }
}
