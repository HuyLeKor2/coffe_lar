<?php

namespace App\Livewire;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Client\Request;
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
        $products = Product::where(['status' => true]);
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
            $products = $products
//                ->where('name', 'like', '%' . $this->searchTerm . '%')
                ->whereRaw('name="'.$this->searchTerm.'"')
                //dùng phương pháp whereRaw để viết và chúng trước biến này phải " " trước $this->searchTerm vì cần chuỗi

                ->orWhere('description', 'like', '%' . $this->searchTerm . '%');
        }

//        dd($products->toSql());
//        if ($request->filled('range-min') && $request->filled('range-max')) {
//            $from = $request->input('range-min');
//            $to = $request->input('range-max');
//            $products = $products->where('price', '>=', $from)->where('price', '<=', $to);
//        }
        $products = $products->orderBy('id', 'DESC')->paginate(8);

        return view('livewire.product-search', [
            'products' => $products,
            'categories' => $categories,
            'curCategorySlug'=>$this->categorySlug
        ]);
    }
}
