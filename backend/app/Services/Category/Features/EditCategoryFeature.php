<?php

namespace App\Services\Category\Features;

use App\Data\Models\Category;
use App\Domains\Auth\Jobs\CheckPermissionJob;
use App\Domains\Category\Jobs\EditCategoryJob;
use App\Domains\Category\Jobs\ValidationEditCategoryJob;
use App\Domains\Http\Jobs\FileModelJob;
use App\Domains\Http\Jobs\RespondWithJsonErrorJob;
use App\Domains\Http\Jobs\RespondWithJsonJob;
use Illuminate\Http\Request;
use Lucid\Units\Feature;

class EditCategoryFeature extends Feature
{
    private $id;
    function __construct(string $id)
    {
        $this->id  = $id;
    }

    public function handle(Request $request)
    {
        $permission = $this->run(CheckPermissionJob::class);

        if(!$permission)
        {
            $this->run(RespondWithJsonErrorJob::class,[
                'message' => FORBIDDEN_MSG,
                'status' => FORBIDDEN_STATUS
            ]);
        };

        $category = $this->run(FileModelJob::class,[
            'model' => Category::class,
            'id' => $this->id,
            'nestedRelations' => []
        ]);
        $request->request->add(['id' => $category->id]);
        if(!$category)
        {
            return $this->run(RespondWithJsonErrorJob::class,[
                'message' => "Category not found",
                'status' => ERROR_STATUS
            ]);
        }

        $validation = $this->run(ValidationEditCategoryJob::class,[
            'input' => $request->input()
        ]);

        if($validation->fails())
        {
           return $this->run(RespondWithJsonErrorJob::class, [
            'message' => $validation->errors()->getMessages(),
            'status' => ERROR_STATUS
           ]);
        };

        $result = $this->run(EditCategoryJob::class,[
            'input' => $request->input(),
            'category' => $category
        ]);
        return $this->run(new RespondWithJsonJob(['id'=> $category->id], $result['isDirty'] ? 'Update Successfully': 'Update Not Change'));
    }
}
