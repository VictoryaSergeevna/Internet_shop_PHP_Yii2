<?php


namespace app\controllers;
use app\models\Cart;
use app\models\Category;
use app\models\Order;
use app\models\OrderItems;
use app\models\Product;
use Yii;
use yii\data\Pagination;

class CartController extends AppController
{
public function actionAdd(){
    $id = Yii::$app->request->get('id');
    $quantity=(int)Yii::$app->request->get('quantity');
    $quantity=!$quantity?1:$quantity;
//    debug($id);

    $product = Product::findOne($id);
    if(empty($product)) return false;
//
    $session = Yii::$app->session;
    $session->open();
    $cart = new Cart();
    $cart->addToCart($product, $quantity);
//    debug($session['cart']);
//    debug($session['cart.quantity']);
//    debug($session['cart.sum']);
//
    if(!Yii::$app->request->isAjax){
        return $this->redirect(Yii::$app->request->referrer);
    }
//
    $this->layout = false;
    return $this->render('cart-modal', compact('session'));
}

    public function actionClear() {
        $session = Yii::$app->session;
        $session->open();
        $session->remove('cart');
        $session->remove('cart.quantity');
        $session->remove('cart.sum');
        $this->layout = false;
        return $this->render('cart-modal', compact('session'));
    }

    public function actionDelItem() {
        $id = Yii::$app->request->get('id');
        $session = Yii::$app->session;
        $session->open();
        $cart = new Cart();
        $cart->recalc($id);
        $this->layout = false;
        return $this->render('cart-modal', compact('session'));
    }
    public function actionShow() {
        $session = Yii::$app->session;
        $session->open();
        $this->layout = false;
        return $this->render('cart-modal', compact('session'));
    }
    public function actionView() {
        $session = Yii::$app->session;
        $session->open();
        $this->setMeta('Корзина');
        $order = new Order();
        if($order->load(Yii::$app->request->post())) {
            //debug($order);
            $order->quantity = $session['cart.quantity'];
            $order->sum = $session['cart.sum'];
            if($order->save()) {
               $this->saveOrderItems($session['cart'], $order->id);
                Yii::$app->session->setFlash('success', 'Ваш заказ принят. Менеджер вскоре свяжется с Вами в ближайшее время');

                //Отправка почты
                Yii::$app->mailer->compose('order', ['session' => $session])
                    ->setFrom(['vikusyachk@gmail.com' => 'shop-bazzar'])
                    ->setTo($order->email)
                    ->setSubject('Заказ')
                    ->send();


                $session->remove('cart');
                $session->remove('cart.quantity');
                $session->remove('cart.sum');
                return $this->refresh();
            }else {
                Yii::$app->session->setFlash('error', 'Ошибка офорления заказа');
            }
        }
        return $this->render('view', compact('session', 'order'));
    }

    protected function saveOrderItems($items, $order_id) {
        foreach ($items as $id => $item) {
            $order_items = new OrderItems();
            $order_items->order_id = $order_id;
            $order_items->product_id = $id;
            $order_items->name = $item['name'];
            $order_items->price = $item['price'];
            $order_items->quantity_item = $item['quantity'];
            $order_items->sum_item = $item['quantity'] * $item['price'];
            $order_items->save();
        }
    }
}