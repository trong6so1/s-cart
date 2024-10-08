<?php

namespace App\Services\Brand\Http\Controllers;

use App\Services\Brand\Features\CreateBrandFeature;
use App\Services\Brand\Features\DeleteBrandFeature;
use App\Services\Brand\Features\EditBrandFeature;
use App\Services\Brand\Features\GetBrandFeature;
use App\Services\Brand\Features\ListBrandFeature;
use Lucid\Units\Controller;

class BrandController extends Controller
{
    public function index() {
        return $this->serve(ListBrandFeature::class);
    }
    public function getBrand($id){
        return $this->serve(GetBrandFeature::class,[
            'id' => $id
        ]);
    }
    public function deleteBrand($id)
    {
        return $this->serve(DeleteBrandFeature::class,[
            'id' => $id
        ]);
    }
    public function createBrand(){
        return $this->serve(CreateBrandFeature::class);
    }
    public function editBrand($id)
    {
        return $this->serve(EditBrandFeature::class,[
            'id' => $id
        ]);
    }
}
