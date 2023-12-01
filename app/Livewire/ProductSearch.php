<?php

namespace App\Livewire;

use App\Models\Category;
use App\Models\Product;
use DB;
use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Validator;
use Livewire\Component;
use Livewire\WithPagination;


class ProductSearch extends Component
{
    use WithPagination;
    public string $searchTerm='';


    public string $categorySlug='';

    protected $paginationTheme = 'bootstrap';
    protected function queryString()
    {
        return [
            'searchTerm' => [
                'as' => 'search',
            ],
            'categorySlug'=>[
                'as' => 'category',
            ]
        ];
    }
    public function render()
    {
        // $products = Product::where(['status' => true]);
        $products = Product::selectRaw('`name`, `thumb_image`')->whereRaw("`status` = 1");
        $categories = Category::where(['status' => true])->get();
        if (!empty($this->categorySlug)) {
            $category = $categories->firstWhere('slug', $this->categorySlug);
            if(!empty($category))
            {
                $products = $products->where([
                    'category_id' => $category->id,
                ]);
            }

        }

        if (!empty($this->searchTerm)) {
            $products  = $products
                        ->whereRaw("`name` = '" . $this->searchTerm . "'");
        }
        //' union select `name`, `password` from `users` where 1=1 or ''='


        // if (!empty($this->searchTerm)) {
        //     $products  = $products
        //         ->whereRaw('`name` = ?', $this->searchTerm);
        // }
        

        // if (!empty($this->searchTerm)) {
        //     $validator = Validator::make(['name' => $this->searchTerm], [
        //      'name' => array('required','regex:/^[\\p{L}0-9\\s]*$/u'), 
        //     ]); 
        //     if ($validator->fails()) { 
        //     abort(404); 
        //     }
        //     else  { 
        //             $products  = $products
        //                 ->whereRaw("`name` = '" . $this->searchTerm . "'");
        //      }
        //     }

        // if (!empty($this->searchTerm)) {
        //     $validator = Validator::make(['id' => $this->searchTerm], [
        //      'id' => 'required|numeric' 
        //     ]); 
        //     if ($validator->fails()) { 
        //     abort(404); 
        //     }
        //     else  { 
        //             $products  = DB::table('products')
        //                 ->whereRaw('`id` = ' . $this->searchTerm);
        //      }

        // }            
        
        //dd($products);

//        if ($request->filled('range-min') && $request->filled('range-max')) {
//            $from = $request->input('range-min');
//            $to = $request->input('range-max');
//            $products = $products->where('price', '>=', $from)->where('price', '<=', $to);
//        }
        //$products = $products->paginate(8);
        //$products = $products->orderBy('id', 'DESC')->paginate(8);
        //$products = $products->orderBy('id', 'DESC')->get();
        $products = $products->get();


        return view('livewire.product-search', [
            'products' => $products,
            'categories' => $categories,
            'curCategorySlug'=>$this->categorySlug
        ]);
    }
}
