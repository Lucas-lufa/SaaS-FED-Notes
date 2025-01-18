<?php
namespace App\Controller;

use Framework\Database;

class HomeController
{
    protected $db;

    public function __construct()
    {
        $config = require basePath('config/db.php');
        $this->db = new Database($config);
    } 

    public function index()
    {
        $products = $this->db->query('SELECT * FROM products ORDER BY created at DESC LIMIT 6')
        ->fetchAll();
        $productCount = $this->db->query('SELECT count(id) as total FROM products')
        ->fetch();
        $userCount =$this->db->query('SELECT count(id) as total FROM users')
        ->fetch();

        loadView('home',[
            'products' => $products,
            'productCount' => $productCount,
            'userCount' => $userCount,
        ]);

    }
}