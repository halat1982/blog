<?php

namespace App\Observers;

use App\Models\BlogCategory;
use Illuminate\Support\Str;

class BlogCategoryObserver
{
    public function creating(BlogCategory $blogCategory){
        $this->setSlug($blogCategory);
    }


    /**
     * Handle the models blog category "created" event.
     *
     * @param  \App\Models\BlogCategory  $modelsBlogCategory
     * @return void
     */
    public function created(BlogCategory $modelsBlogCategory)
    {
        //
    }


    public function updating(BlogCategory $blogCategory){
        $this->setSlug($blogCategory);
    }

    protected function setSlug($blogCategory){
        if(empty($blogCategory->slug)){
            $blogCategory->slug = Str::slug($blogCategory->title);
        }
    }
    /**
     * Handle the models blog category "updated" event.
     *
     * @param  \App\Models\BlogCategory  $modelsBlogCategory
     * @return void
     */
    public function updated(BlogCategory $modelsBlogCategory)
    {
        //
    }

    /**
     * Handle the models blog category "deleted" event.
     *
     * @param  \App\Models\BlogCategory  $modelsBlogCategory
     * @return void
     */
    public function deleted(BlogCategory $modelsBlogCategory)
    {
        //
    }

    /**
     * Handle the models blog category "restored" event.
     *
     * @param  \App\Models\BlogCategory  $modelsBlogCategory
     * @return void
     */
    public function restored(BlogCategory $modelsBlogCategory)
    {
        //
    }

    /**
     * Handle the models blog category "force deleted" event.
     *
     * @param  \App\Models\BlogCategory  $modelsBlogCategory
     * @return void
     */
    public function forceDeleted(BlogCategory $modelsBlogCategory)
    {
        //
    }
}
