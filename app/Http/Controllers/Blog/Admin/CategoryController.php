<?php

namespace App\Http\Controllers\Blog\Admin;


use App\Http\Requests\BlogCategoryCreateRequest;
use App\Http\Requests\BlogCategoryUpdateRequest;
use App\Models\BlogCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Repositories\BlogCategoryRepository;
class CategoryController extends BaseController
{
    /**
     *@var BlogCategoryRepository
     */
    private $blogCategoryRepository;

    public function __construct(){
        parent::__construct();
        $this->blogCategoryRepository = app(BlogCategoryRepository::class);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $paginator = $this->blogCategoryRepository->getAllWithPaginate(10);
        return view('blog.admin.categories.index', compact('paginator'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $item = new BlogCategory();
        $categoryList = $this->blogCategoryRepository->getForCombobox();

        return view('blog.admin.categories.edit',
        compact('item', 'categoryList'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(BlogCategoryCreateRequest $request)
    {
        $data = $request->input();
        /*if(empty($data['slug'])){
            $data['slug'] = Str::slug($data['title']);
        }*/
        $item = new BlogCategory($data);
        $item->save();

        if($item){
            return redirect()->route('blog.admin.categories.edit', [$item->id])
                ->with(['success'=>'Save complete']);
        } else {
            return back()->withErrors(['msg'=>'Save error'])
                ->withInput();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @param BlogCategoryRepository $categoryRepository
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit($id, BlogCategoryRepository $categoryRepository)
    {
        $item = $this->blogCategoryRepository->getEdit($id);
        if(empty($item)){
            abort('404');
        }
        $categoryList = $this->blogCategoryRepository->getForCombobox();
        return view('blog.admin.categories.edit', compact('item', 'categoryList'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(BlogCategoryUpdateRequest $request, $id)
    {
        //dd($validateData);
        $item = BlogCategory::find($id);
        if(empty($item)){
            return back()
                ->withErrors(['msg' => "id=[{$id}] not found"])
                ->withInput();
        }
        $data = $request->all();
        //dd($data);
        $result = $item->fill($data)->save();
        if($result){
            return redirect()
                ->route('blog.admin.categories.edit', $item->id)
                ->with(['success' => 'Save complete']);
        } else {
            return back()
                ->withErrors(['msg' => 'Save error'])
                ->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
