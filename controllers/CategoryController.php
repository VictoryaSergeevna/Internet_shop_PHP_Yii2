<?php


namespace app\controllers;
use app\models\Category;
use app\models\Product;
use Yii;
use yii\data\Pagination;

class CategoryController extends AppController
{
    public function actionIndex() {
        $hits = Product::find()->where(['hit' => '1'])->limit(6)->all();

        $this->setMeta('Bazzar-shop');

        return $this->render('index', compact('hits'));
    }

    public function actionView($id) {
        //get route
//       $id = Yii::$app->request->get('id');

//debug($id);
        $category = Category::findOne($id);
        if(empty($category))
            throw new \yii\web\HttpException(404, 'Выбранной категории не существует');


//        $products = Product::find()->where(['category_id' => $id])->all();
        $query = Product::find()->where(['category_id' => $id]);
        $pages = new Pagination(['totalCount' => $query->count(),
           'pageSize' => 3,'forcePageParam' => FALSE, 'pageSizeParam' => FALSE]);
        $products = $query->offset($pages->offset)->limit($pages->limit)->all();

       $this->setMeta('Bazzar-shop | ' . $category->name, $category->keywords, $category->description);
       return $this->render('view', compact('products', 'pages', 'category'));
//        return $this->render('view', compact('products','category'));
    }

    public function actionSearch(){

        $q = trim(Yii::$app->request->get('q'));
        $this->setMeta('Bazzar-shop | Поиск:' . $q);
        if(!$q)
            return $this->render('search');
        $query = Product::find()->where(['like', 'name', $q]);
        $pages = new Pagination(['totalCount' => $query->count(),
            'pageSize' => 3,
            'forcePageParam' => FALSE,
            'pageSizeParam' => FALSE]);
        $products = $query->offset($pages->offset)->limit($pages->limit)->all();
        return $this->render('search', compact('products', 'pages', 'q'));
    }
}