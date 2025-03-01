<?php

namespace App\Controllers;

use Framework\Authorisation;
use Framework\Database;
use Framework\Session;
use Framework\Validation;

class ProductController
{
    protected $db;

    public function __construct()
    {
        $config = basePath('config/db.php');
        $this->db = new Database($config);
    }
    
    public function index()
    {
        $sql = "SELECT * FROM products ORDER BY create_at DESC";

        $products = $this->db->query($sql)->fetchAll();
        loadView('products/index', [
            'products'=>$products
        ]);
    }

    public function create()
    {
        loadView('products/create');
    }

    public function show(array $params):void
    {
        $id = $params['id'] ?? NULL;

        if ($id) {
            $params = ['id'=>$id];
            $sql = 'SELECT * FROM products WHERE id = :id';
            $product = $this->db->query($sql,$params)->fetch();

            if (!$product){
                ErrorController::notFound('Product not found.');
                return;
            }

            loadView('products/show',[
                'product' => $product
            ]);

        }
    }

    public function store()
    {
        $allowedFields = ['name','description','price'];
        $newProductsData = array_intersect_key($_POST, array_flip($allowedFields));
        $newProductsData['user_id'] = Session::get('user')['id'];
        $newProductsData = array_map('sanitize', $newProductsData);
        $requiredFields = ['name','price'];
        $errors = [];

        foreach ($requiredFields as $field){
            if (empty($newProductsData[$field]) || !Validation::string($newProductsData[$field])){
                $errors[$field] = ucfirst($field) . ' is required.';
            }
        }

        if (!empty($errors)){
            loadView('products/create',
            ['errors' => $errors,
            'products' => $newProductsData
        ]);
        }

        // Save the submitted data
        $fields = [];

        foreach ($newProductsData as $field => $value) {
            $fields[] = $field;
        }

        $fields = implode(', ' , $fields);

        $values = [];

        foreach ($newProductsData as $field => $value){
            // convert empty string to null for inserting into data base
        if ($value === ''){
            $newProductsData[$field] = null;
        }
        $values[] = ':' . $field;
        }

        $values = implode(', ' , $values);

        $insertquery = "INSERT INTO products ({$fields}) VALUES ({$values})";

        $this->db->query($insertquery, $newProductsData);

        Session::setFlashMessage('seccess_message', 'Product created successfully.');

        redirect('/products');

    }

    /**
     * Show the product edit form
     * 
     * @param array $params
     * @return null
     * @throws \Exception
     */
    public function edit($params)
    {
        $id = $params['id'] ?? '';

        $params = [
            'id' => $id
        ];

        $product = $this->db->query("SELECT * FORM products WHERE id = :id", $params)->fetch();

        // check if product exists
        if (!$product){
            ErrorController::notFound('Product not found.');
            exit();
        }

        // authorisation
        if (!Authorisation::isOwner($product->user_id)){
            Session::setFlashMessage('error_message', 'you are not authorised to update this product');
            return redirect('/products/' . $product->id);
        }

        loadView('products/edit', [
            'product' => $product
        ]);
    }

    /**
     * 2 46 16
     */

}