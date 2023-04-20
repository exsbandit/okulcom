<?php

namespace App\Rules\v1;

use App\Models\Product;
use Illuminate\Contracts\Validation\Rule;

class ProductStockRule implements Rule
{
    public array $products;
    public string $ruleMessage;

    /**
     * ProductStockRule constructor.
     * @param int $products
     */
    public function __construct(array $products)
    {
        $this->products = $products;
    }

    /**
     * @param string $attribute
     * @param mixed $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $index = explode('.', $attribute)[1];
        $product = Product::find($this->products[$index]['id']);
        if ($value > $product->stock) {
            $this->ruleMessage = 'Product.stock.error';
            return false;
        }

        return true;
    }

    /**
     * @return array|mixed|string
     */
    public function message()
    {
        return $this->ruleMessage;
    }
}
