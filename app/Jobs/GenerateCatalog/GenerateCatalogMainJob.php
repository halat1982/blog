<?php

namespace App\Jobs\GenerateCatalog;


class GenerateCatalogMainJob extends AbstractJob
{
     /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $this->debug('start');

        //before add products in cache
        GenerateCatalogCacheJob::dispatchNow();

        //then we create tasks chain for creating files with prices
        $chainPrices = $this->getChainPrices();

        // Main subtasks

        $chainMain = [
            new GenerateCategoriesJob, //category generation
            new GenerateDeliveriesJob, // delivery methods generation
            new GeneratePointsJob, //issuing points generation
        ];

        //last subtasks

        $chainLast = [
            //archiving files and transferring the archive to a public folder
            new ArchiveUploadsJob,
            //sending a notification to a third-party service that a new catalog file can be downloaded.
            new SendPriceRequestJob
        ];

        $chain = array_merge($chainPrices, $chainMain, $chainLast);

        GenerateGoodsFileJob::withChain($chain)->dispatch();
        //GenerateGoodsFileJob::dispatch()->chain($chain);

        $this->debug('finish');
    }

    private function getChainPrices()
    {
        $result =[];
        $products = collect([1,2,3,4,5]);
        $fileNum = 1;

        foreach($products->chunk(1) as $chunk) {
            $result[] = new GeneratePricesFileChunkJob($chunk, $fileNum);
            $fileNum ++;
        }

        return $result;
    }
}
