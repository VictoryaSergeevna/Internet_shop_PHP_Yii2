<?php


namespace app\models;


use yii\base\Model;
use yii\db\ActiveRecord;

class Cart extends ActiveRecord
{

     public function behaviors()
    {
        return [
            'image' => [               
                'class' => 'rico\yii2images\behaviors\ImageBehave',
            ]
        ];
    }
public function addToCart($product,$quantity=1){
    $mainImg=$product->getImage();
    if(isset($_SESSION['cart'][$product->id])){
        $_SESSION['cart'][$product->id]['quantity'] += $quantity;
    }else{
        $_SESSION['cart'][$product->id] = [
            'quantity' => $quantity,
            'name' => $product->name,
            'price' => $product->price,
            'img' =>  $mainImg->getUrl('x50')
        ];
    }
    $_SESSION['cart.quantity'] = isset($_SESSION['cart.quantity']) ? $_SESSION['cart.quantity'] + $quantity: $quantity;
    $_SESSION['cart.sum'] = isset($_SESSION['cart.sum']) ? $_SESSION['cart.sum'] + $quantity * $product->price: $quantity * $product->price;
}
    public function recalc($id) {
        if(!isset($_SESSION['cart'][$id])) return false;
        $quantityMinus = $_SESSION['cart'][$id]['quantity'];
        $sumMinus = $_SESSION['cart'][$id]['quantity'] * $_SESSION['cart'][$id]['price'];
        $_SESSION['cart.quantity'] -= $quantityMinus;
        $_SESSION['cart.sum'] -= $sumMinus;
        unset($_SESSION['cart'][$id]);//delete item
    }
}