<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\CategoryRequest;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Validator;

class CategoriesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $request = request();

        //SELECT a.*,b.name as parent_name
        //FROM categories as a
        //LEFT JOIN categories as b ON b.id = parent_id

        $categories = Category::with('parent')
            /*leftJoin('categories as parents', 'parents.id', '=', 'categories.parent_id')
            ->select([
                'categories.*',
                'parents.name as parent_name'
            ])*/
            ->select('categories.*')
            ->selectRaw('(SELECT COUNT(*) FROM products WHERE category_id = categories.id) as products_number')
            //->addSelect(DB::raw('(SELECT COUNT(*) FROM products WHERE category_id = categories.id) as products_count'))
//            ->withCount([
//                'products as products_number' => function($query) {
//                    $query->where('status', '=', 'active');
//                }
//            ])
            ->filter($request->query())
            ->orderBy('categories.name')
            ->paginate(); // Return Collection object

        return view('dashboard.categories.index', compact('categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $parents = Category::all();
        $category = new Category();
        return view('dashboard.categories.create',compact('category','parents'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $clean_data = $request->validate(Category::rules(),[
            'name.required' =>'This field (:attribute)is Required',
            'unique' =>'This name is already exists!'
        ]);

        //Request merge
        $request->merge([
            'slug'=> Str::slug($request->post('name'))
        ]);

        $data =$request->except('image');
        $data['image'] = $this->uploadImage($request);

        //Mass Assigment
        $category = Category::create($data);
        //PRG
        return redirect()-> route('dashboard.categories.index')
            ->with('success','Category Created!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Category $category)
    {
//        if (Gate::denies('categories.view')) {
//            abort(403);
//        }
        return view('dashboard.categories.show', [
            'category' => $category
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $category = Category::findOrFail($id);
        //SELECT * FROM category WHERE id <> $id (لا تساوي)
        // AND (parent_id isNull OR parent_id <> $id)
        $parents = Category::where('id', '<>',$id)
            ->where(function ($query) use ($id){
            $query ->whereNull('parent_id')
                ->orWhere('parent_id','<>',$id);
        })
            ->get();

        return view('dashboard.categories.edit',compact('category','parents'));
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(CategoryRequest $request, $id)
    {
        // $request->validate(Category::rule($id));
        $request -> validate(Category::rules($id));
        $category = Category::findOrFail($id);

        $old_image = $category->image;

        $data =$request->except('image');
        $new_image = $this->uploadImage($request);

        if ($new_image){
            $data['image']=$new_image;
        }
        $category->update($data);
        if($old_image && $new_image){
            Storage::disk('public')->delete($old_image);
        }
        //PRG

        return redirect()
            ->route('dashboard.categories.index')  // Redirest to this route
            ->with('success', "Category ({$category->name}) Updated!");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category)
    {
       // Category::destroy($id);

        // as the same:
        //$category = Category::findOrFail($id);
        $category ->delete();

        //PRG
        return redirect()-> route('dashboard.categories.index')
            ->with('success','Category Deleted!');
    }
    public function uploadImage(Request $request){

        if (!$request ->hasFile('image')) {
                return;
        }

            $file = $request->file('image'); //upload fill object
            $path = $file->store('uploads',[
                'disk' =>'public'
            ]);
            return $path;
        }
        public function trash()
        {
            $categories = Category::onlyTrashed()->paginate();
            return view('dashboard.categories.trash',compact('categories'));
        }
        public function restore(Request $request,$id)
        {
            $category = Category::onlyTrashed()->findOrFail($id);
            $category->restore();
            return redirect()->route('dashboard.categories.trash')
                ->with('success','Categories Restored!');
        }
        public function forceDelete($id)
        {
            $category = Category::onlyTrashed()->findOrFail($id);
            $category->forceDelete();

            if($category->image){
                Storage::disk('public')->delete($category->image);
            }

            return redirect()->route('dashboard.categories.trash')
                ->with('success','Categories Deleted Forever!');
        }

}
