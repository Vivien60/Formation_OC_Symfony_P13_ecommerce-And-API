<?php

namespace App\Service;

use Pagerfanta\PagerfantaInterface;

class PagerConfiguratorService
{
    public function __construct()
    {

    }

    public function configure(PagerFantaInterface $pagerWithResults, int $page, int $maxPerPage) : PagerFantaInterface
    {
        $pagerWithResults->setMaxPerPage($maxPerPage);
        $pagerWithResults->setCurrentPage($page);
        return $pagerWithResults;
    }

}