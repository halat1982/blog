<?php

namespace App\Http\Controllers\Blog\Admin;

use App\Http\Requests\BlogPostUpdateRequest;
use App\Http\Requests\BlogPostCreateRequest;
use App\Jobs\BlogPostAfterCreateJob;
use App\Jobs\BlogPostAfterDeleteJob;
use App\Models\BlogPost;
use App\Repositories\BlogCategoryRepository;
use App\Repositories\BlogPostRepository;
use Carbon\Carbon;
use Illuminate\Http\Request;

class PostController extends BaseController
{
    /**
     *@var BlogPostRepository
     */
    private $blogPostRepository;

    /**
     * @var $blogCategoryRepository;
     */
    private $blogCategoryRepository;

    /**
     * PostController constructor.
     */

    public function __construct(){
        parent::__construct();
        $this->blogPostRepository = app(BlogPostRepository::class);
        $this->blogCategoryRepository = (app(BlogCategoryRepository::class));
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $paginator = $this->blogPostRepository->getAllWithPaginate(20);
        return view('blog.admin.posts.index', compact('paginator'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $item = new BlogPost();
        $categoryList
            = $this->blogCategoryRepository->getForCombobox();

        return view('blog.admin.posts.edit',
            compact('item', 'categoryList'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(BlogPostCreateRequest $request)
    {
        $data = $request->input();
        $item = (new Blogpost())->create($data);

        if($item) {
            $job = new BlogPostAfterCreateJob($item);
            $this->dispatch($job);

            return redirect()->route('blog.admin.posts.edit', [$item->id])
                ->with(['success' => 'Save complete']);
        } else {
            return back()
                ->withErrors(['msg' => 'Save error'])
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
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $item = $this->blogPostRepository->getEdit($id);
        //dd($item);
        if(empty($item)) {
            abort(404);
        }

        $categoryList
            = $this->blogCategoryRepository->getForCombobox();

        return view('blog.admin.posts.edit',
        compact('item', 'categoryList'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  BlogPostUpdateRequest  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(BlogPostUpdateRequest $request, $id)
    {
        $item = $this->blogPostRepository->getEdit($id);

        if(empty($item)){
           return back()
                ->withErrors(['msg' => 'id=[{$id}] not found'])
               ->withInput();
        }

        $data = $request->all();

        $result = $item->update($data);
        if($result){
            return redirect()
                ->route('blog.admin.posts.edit', $item->id)
                ->with(['success' => 'Save succesfully']);
        } else {
            return back()
                ->withErrors(['msg' => 'Success Error'])
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
        //soft delete
        $result = BlogPost::destroy($id);

        //full delete from db
        //$result = BlogPost::find($id)->forceDelete();
        if($result){
            BlogPostAfterDeleteJob::dispatch($id);
            return redirect()
                ->route('blog.admin.posts.index')
                ->with(['success' => "Article id[$id] was deleted"]);
        } else {
            return back()->withErrors(['msg'=>'Delete error']);
        }
    }
}
