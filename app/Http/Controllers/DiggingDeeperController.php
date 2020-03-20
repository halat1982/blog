<?php

namespace App\Http\Controllers;

use App\Jobs\GenerateCatalog\GenerateCatalogMainJob;
use App\Jobs\ProcessVideoJob;
use App\Models\BlogPost;
use Illuminate\Http\Request;

class DiggingDeeperController extends Controller
{
    public function collections()
    {
        $result = array();
        $eloquentCollection = BlogPost::withTrashed()->get();
        //dd(__METHOD__, $eloquentCollection, $eloquentCollection->toArray());
        // create collection
        $collection = collect($eloquentCollection->toArray());
        dd(
            get_class($eloquentCollection),
            get_class($collection),
            $collection
        );
    }

    public function processVideo()
    {
        ProcessVideoJob::dispatch();
    }

    /**
     * @link http:127.0.0.1:8000/diggin_deeper/prepare-catalog
     * php artisan queue:listen --queue=generate-catalog --tries=3 --delay=10
     */
    public function prepareCatalog()
    {
        GenerateCatalogMainJob::dispatch();
    }
}
