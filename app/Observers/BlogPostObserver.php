<?php

namespace App\Observers;

use App\Models\BlogPost;
use Carbon\Carbon;

class BlogPostObserver
{

    public function creating(BlogPost $blogPost)
    {
        $this->setPublishedAt($blogPost);
        $this->setSlug($blogPost);
        $this->setHtml($blogPost);
        $this->setUser($blogPost);
    }

    /**
     * Handle the models blog post "created" event.
     *
     * @param  \App\Models\BlogPost  $modelsBlogPost
     * @return void
     */
    public function created(BlogPost $modelsBlogPost)
    {
        //
    }
    /**
     *
     */
    public function updating(BlogPost $blogPost){
        $this->setPublishedAt($blogPost);
        $this->setSlug($blogPost);
        //dd($blogPost);
    }

    protected function setPublishedAt(Blogpost $blogPost){
        if(empty($blogPost->published_at) && $blogPost['is_published']){
            $blogPost->published_at = Carbon::now();
        }
    }

    protected function setSlug(Blogpost $blogPost){
        if(empty($blogPost->slug)){
            $blogPost->slug = \Str::slug($blogPost->title);
        }
    }


    /**
     * Handle the models blog post "updated" event.
     *
     * @param  \App\Models\BlogPost  $modelsBlogPost
     * @return void
     */
    public function updated(BlogPost $modelsBlogPost)
    {
        //
    }

    /**
     * Handle the models blog post "deleted" event.
     *
     * @param  \App\Models\BlogPost  $modelsBlogPost
     * @return void
     */
    public function deleted(BlogPost $modelsBlogPost)
    {
        //
    }

    /**
     * Handle the models blog post "restored" event.
     *
     * @param  \App\Models\BlogPost  $modelsBlogPost
     * @return void
     */
    public function restored(BlogPost $modelsBlogPost)
    {
        //
    }

    /**
     * Handle the models blog post "force deleted" event.
     *
     * @param  \App\Models\BlogPost  $modelsBlogPost
     * @return void
     */
    public function forceDeleted(BlogPost $modelsBlogPost)
    {
        //
    }

    protected function setHtml(BlogPost $blogPost)
    {
        if($blogPost->isDirty('content_row')){
            //TODO: there are need generation markdown->html
            $blogPost->content_html = $blogPost->content_row;
        }
    }

    protected function setUser(BlogPost $blogPost)
    {
        $blogPost->user_id = auth()->id ?? BlogPost::UNKNOWN_USER;
    }

}
