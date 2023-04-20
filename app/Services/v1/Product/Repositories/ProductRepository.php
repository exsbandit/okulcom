<?php

namespace App\Services\v1\Product\Repositories;

use App\Models\Product;
use App\Repositories\v1\Base\BaseRepository;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Arr;

class ProductRepository extends BaseRepository implements ProductRepositoryInterface
{
    public function __construct(Product $model)
    {
        parent::__construct($model);
    }

    public function prepareFilters(Builder $query, array $filters): Builder
    {
        if (isset($filters['name'])) {
            $query->where('name', 'LIKE', "{$filters['name']}%");
            unset($filters['name']);
        }

        return parent::prepareFilters($query, $filters);
    }

    public function stock(Product $product)
    {
        if ($product->stock > 0) {
            return $product->name.' has '.$product->stock . ' stock';
        }

        return $product->name.' hasn\'t any left';
    }
    public function addStock($attributes)
    {
        $productIds =  Arr::pluck($attributes['products'], 'id');
        foreach ($attributes['products'] as $product) {
            $sysProduct = Product::find($product['id']);
            $sysProduct->increment('stock', $product['quantity']);
        }
        return Product::whereIn('id', $productIds)->get();
    }
}
