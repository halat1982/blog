<?php
namespace App\Repositories;

use App\Models\BlogPost as Model;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
class BlogPostRepository extends CoreRepository
{
    protected function getModelClass()
    {
        return Model::class;
    }

    public function getAllWithPaginate($perPage = null){
        //$paginator = DB::table('blog_categories')->paginate(10);
        $columns = [
            'id',
            'title',
            'slug',
            'is_published',
            'published_at',
            'user_id',
            'category_id'
        ];
        /*$result = DB::table('blog_posts')
            ->select($columns)
            ->orderBy('blog_posts.id', 'DESC')
            ->join('users', 'blog_posts.user_id', '=', 'users.id')
            //->with(['category', 'user'])
            ->paginate($perPage);*/
        $result=$this->startConditions()->select($columns)->orderBy('blog_posts.id', 'DESC')->with(['category:id,title', 'user:id,name'])->paginate($perPage);
        //dd($result);
        return $result;
    }

    /**
     * @param int $id
     * @return Model
     */
    public function getEdit($id)
    {
        return $this->startConditions()->find($id);
    }
}
