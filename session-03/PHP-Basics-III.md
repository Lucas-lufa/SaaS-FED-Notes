# PHP Basics III
php object
if you create a __construct() function, php will automatically call this function when an object is created from this class

class Car {
    public $colour;
    public $model;
    public function __construct($colour, $model) {
        $this->colour = $colour;
        $this->model = $model;
    }
    public function message() {
        return "My car is a " . $this->colour . " " . $this->model . "!";
    }
}

$myCar = new Car("red", "volvo");
var_dump($myCar);

# OOP in PHP

