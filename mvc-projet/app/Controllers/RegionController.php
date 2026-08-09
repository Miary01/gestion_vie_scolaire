<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\Region;

class RegionController extends Controller
{
    /** GET /region/search?q=... (ex search_region.php) */
    public function search(): void
    {
        $query = $_GET['q'] ?? '';
        $resultats = (new Region())->search($query);

        $this->json($resultats);
    }
}
