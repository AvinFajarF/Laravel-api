<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class Post extends Model
{
    use HasFactory, Sluggable;

    protected $fillable =
    [
        'image',
        'title',
        'content',
        'slug',
       'created_by',
    ];

    protected $attributes = [
        'is_pinned' => '0'
    ];


      //    Slug
      public function sluggable(): array
      {
          return [
              'slug' => [
                  'source' => 'title'
              ]
          ];
      }

      // Update slug
      protected static function booted()
      {
          static::updating(function ($model) {

              if ($model->isDirty('title')) {
                  $model->slug = Str::slug($model->title);
              }


          });
      }




    //   relasi

    public function created_by(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }


    /**
     * Get all of the comments for the Post
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function comments()
    {
        return $this->hasMany(Comments::class, 'post_id', 'id');
    }

    protected $appends = ['created_at_parse','update_at_parse'];


    public function createdAtParse(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => Carbon::parse($value)->translatedFormat("d F Y"),
        );
    }

    public function updateAtParse(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => Carbon::parse($value)->translatedFormat("d F Y"),
        );
    }

}
