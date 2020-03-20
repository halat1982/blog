<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BlogCategory extends Model
{
    use SoftDeletes;
    const ROOT = 1;
    protected $fillable = [
      'title',
      'slug',
      'parent_id',
      'description',
    ];

    public function parentCategory(){
        return $this->belongsTo(BlogCategory::class, 'parent_id', 'id');
    }

    public function getParentTitleAttribute()
    {
        $title = $this->parentCategory->title
        ?? ($this->isRoot()
        ? 'Root' : '???');

        return $title;
    }

    public function isRoot()
    {
        return $this->id === BlogCategory::ROOT;
    }

    /**
     * Accessor
     */
    public function getTitleAttribute($valueFromObject)
    {
        return mb_strtoupper($valueFromObject);
    }

    /**
     * Mutator
     */
    public function setTitleAttribute($incomingValue)
    {
        $this->attributes['title'] = mb_strtolower($incomingValue);
    }
}
