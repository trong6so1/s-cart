<?php
namespace App\Validators\Traits;

use App\Data\Models\Category;
use App\Data\Models\CategoryDescription;

trait CategoryTrait
{
    public function validateUniqueTitle($attribute, $value)
    {
        $countTitle = CategoryDescription::where('lang', explode('_', $attribute)[1])->Where('title', $value);
        return !$countTitle->count() >=1 ;
    }

    public function validateParent($attribute, $value)
    {
        if($value !== 0)
        {
            $category = Category::find($value);
            return $category;
        }
        return true;
    }
}

?>
