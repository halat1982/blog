<?php
namespace App\Repositories;

use App\Models\BlogCategory as Model;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
class BlogCategoryRepository extends CoreRepository
{
    public function getEdit($id)
    {
        return $this->startConditions()->find($id);
    }

    protected function getModelClass()
    {
        return Model::class;
    }

    public function getForCombobox()
    {
        //return $this->startConditions()->all();
        $columns = implode(', ', [
            'id',
            'CONCAT (id, ". ", title) AS id_title',
        ]);
        //dd($columns);
        $result = $this
        ->startConditions()
        ->selectRaw($columns)
        ->toBase()
        ->get();
        //dd($result);
        return $result;
    }

    public function getAllWithPaginate($perPage = null){
        //$paginator = DB::table('blog_categories')->paginate(10);
        $fields = ['id', 'title', 'parent_id'];
        $result = $this->startConditions()
            ->select($fields)
            ->with('parentCategory:id,title')
            ->paginate($perPage);
        return $result;
    }
}
