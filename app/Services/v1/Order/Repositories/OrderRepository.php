<?php

namespace App\Services\v1\Order\Repositories;

use App\Exceptions\v1\Order\OrderCreateException;
use App\Exceptions\v1\User\UserDetailException;
use App\Exceptions\v1\Order\OrderStatusException;
use App\Models\Order;
use App\Models\OrderedProduct;
use App\Models\Product;
use App\Models\User;
use App\Repositories\v1\Base\BaseRepository;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

use App\Helpers\TwilioHelper;


class OrderRepository extends BaseRepository implements OrderRepositoryInterface
{
    public function __construct(Order $model)
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


    public function orderedProducts(): Collection
    {
        $orderedProducts = OrderedProduct::groupBy('order_id')->pluck('order_id');
        return Order::query()
            ->whereIn('id', $orderedProducts)
            ->get();
    }

    public function create(array $attributes): Model
    {
        if ($control = $this->checkUserDetail()) {
            throw new UserDetailException('user.detail.information.not.found' . '(' . implode(",", $control) . ')');
        }
        $user = request()->user();

        DB::beginTransaction();
        try {
            $totalPrice = 0;
            $order = $this->model::firstOrCreate([
                'user_id' => $user->id,
                'total' => 0,
            ]);
            foreach ($attributes['products'] as $product) {
                $orderedProduct = Product::find($product['id']);
                $totalPrice += $this->dropStock($orderedProduct, $order, $product['quantity']);

            }
            $order->total = $totalPrice;
            $order->save();

            DB::commit();
            TwilioHelper::sendMessage($user->detail->phone_number, 'Order #'.$order->id.' created successfully, Waiting for approve');
            return $order->with('products');
        } catch (\Throwable $e) {
            DB::rollBack();
            throw new OrderCreateException($e->getMessage());
        }

    }

    private function dropStock(Model $orderedProduct, $order, $quantity): float
    {
        DB::beginTransaction();
        try {
            $orderedProduct->stock = $orderedProduct->stock - $quantity;
            $orderedProduct->save();
            OrderedProduct::create([
                'order_id' => $order->id,
                'product_id' => $orderedProduct->id,
                'category_id' => $orderedProduct->category_id,
                'quantity' => $quantity,
                'unitPrice' => $orderedProduct->price,
                'total' => $orderedProduct->price * $quantity,
            ]);
            DB::commit();
            return $orderedProduct->price * $quantity;
        } catch (\Throwable $e) {
            DB::rollBack();
            throw new \Exception($e->getMessage());
        }
    }

    /**
     * @param Order $order
     */
    public function approve(Order $order)
    {
        if ($order->status == 'awaiting' && $order->update(['status' => 'approved'])) {
            $user = User::find($order->user_id);
            TwilioHelper::sendMessage($user->detail->phone_number, 'Order #'.$order->id.' approved successfully');
            return $order->with('products');
        }
        throw new OrderStatusException('sometings.went.wrong');
    }

    /**
     * @param Order $order
     */
    public function reject(Order $order)
    {
        try {
            if ($order->status == 'awaiting') {
                $products = $order->products;
                foreach ($products as $product) {
                    $orderedProduct = Product::find($product['product_id']);
                    $orderedProduct->increment('stock', $product->quantity);
                }
                $order->update(['status' => 'rejected']);
                $user = User::find($order->user_id);
                TwilioHelper::sendMessage($user->detail->phone_number, 'Order #'.$order->id.' rejected!!');
                return $order;
            } else {
                throw new OrderStatusException('order.status.already.changed');
            }
        } catch (\Throwable $e) {
            throw new OrderStatusException($e->getMessage());
        }
    }

    public function checkUserDetail()
    {
        $user = request()->user();
        $control = [];

        if (!isset($user->detail->first_name)) {
            $control[] = 'first_name';
        }
        if (!isset($user->detail->last_name)) {
            $control[] = 'last_name';
        }
        if (!isset($user->detail->phone_number)) {
            $control[] = 'phone_number';
        }

        return $control ?? true;
    }


}
