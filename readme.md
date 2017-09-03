# Implement Simple MVC framework for Training Intern Student

Tài liệu này sẽ hướng dẫn các bạn implement 1 web framework theo mô hình MVC đơn giản.

## Giới thiệu về Mô hình MVC

![MVC Pattern](https://raw.githubusercontent.com/phuc-ngo/php-simple-mvc/master/ss/mvc.png)

Để tìm hiểu về MVC pattern và các lợi ích của pattern này các bạn đọc một trong các tài liệu sau.

* [Wikipedia](https://en.wikipedia.org/wiki/Model–view–controller)
* [Apple Wiki](https://developer.apple.com/library/content/documentation/General/Conceptual/DevPedia-CocoaCore/MVC.html)

----
Trước tiên chúng ta cần set các yêu cầu cơ bản cho framework.

1. Dùng modem php syntax,
2. Dùng composer quản lý package và autoload.
3.
----
Trước tiên chúng ta cần set các yêu cầu cơ bản cho framework.

1. Dùng modem php syntax,
2. Dùng composer quản lý package và autoload.
3. Phải phân tách được các đối tượng Model, View, Controller.
4. Database dùng PDO.
5. Có config và config được truy vấn theo syntax `config('app.secret_key')` thì sẽ lấy secret_key trong config/app.php
6. View sẽ cũng tương tự  ở controller action ta goi `return view('login.index', $data)` thì sẽ lấy file view index.html trong folder app/Views/login/index.html
5. Cấu trúc các folder như sau.

```
.
├── app
│   ├── Config
│   ├── Controllers
│   ├── Core
│   ├── Models
│   ├── Services
│   ├── Utils
│   └── Views
├── bootstrap
│   └── app.php
├── composer.json
├── public
│   └── index.php
├── readme.md
├── ss
│   └── mvc.png
└── vendor
    ├── autoload.php
    └── composer
```

---

## Implementation


1. để bắt đầu implement chúng ta tạo 1 folder tên là simple-mvc

```
mkdir simple-mvc && cd simple-mvc
```

2. Tiếp theo chúng ta sẽ cần tạo project và config composer.

```
# Chạy composer init và điền các thông tin theo gọi ý từ composer
composer init

```

sau khi làm xong bước trên ta sẽ có 1 file là composer.json trong folder simple-composer
đây sẽ là file mà composer dùng để quản lý package, và vesion của các package cũng như dùng cho tính năng
autoload file trong php thay vì dùng require hay include.


```
    tree -L 1

    .
    └── composer.json

```

3. Tiếp theo ta sẽ tạo 1 số folder và file chính


```

mkdir -p public

mkdir -p app

mkdir -p bootstrap

mkdir -p app/Config

mkdir -p app/Controllers

mkdir -p app/Models

mkdir -p app/Core

mkdir -p app/Utils

mkdir -p app/Views

touch public/index.php

touch public/index.php

touch bootstrap/app.php

touch app/Utils/Utils.php

```

Chạy xong các lệnh trên ta có structure sau

```
.
├── app
│   ├── Config
│   ├── Controllers
│   ├── Core
│   ├── Models
│   ├── Utils
│   └── Views
├── bootstrap
│   └── app.php
├── composer.json
└── public
    └── index.php
```


4. Sau đó chúng ta sẽ cần config composer để dùng được theo chuẩn autoload PSR-4

ta thêm phần sau vào file composer.json

```
    "autoload": {
        "psr-4": {
            "App\\": "app/"
        },
        "files": [
            "app/Utils/Utils.php"
        ]
    }

```

trong đó thì `"App\\": "app/"` sẽ map PHP namespace App vào folder app khi đó ta có thể viết namespace vd cho controller
như sau `App\Controllers`

Ngoài ra ta sẽ có 1 số hàm helper để trong 1 file app/Utils/Utils.php và ta cũng cần autoload file
syntax autoload ở composer hơi khác với autoload class 1 chút ta dung syntax sau.

```

"files": [
    "app/Utils/Utils.php"
]

```

Cuối cùng toàn bộ file composer của ta như sau



// composer.json

```

    {
        "name": "phucngo/simplemvc",
        "description": "Implement Simple MVC Framework in PHP for training Intern Student\"",
        "type": "project",
        "license": "MIT",
        "authors": [
            {
                "name": "Phuc Ngo",
                "email": "ngovanphuc@hotmail.com"
            }
        ],
        "require": {},
        "autoload": {
            "psr-4": {
                "App\\": "app/"
            },
            "files": [
                "app/Utils/Utils.php"
            ]
        }
    }


```

Tiếp theo ta chạy `composer dump-autoload -o` để composer generate ra file meta data phục vụ cho autoload
sau khi chạy xong ta sẽ có thêm 1 folder tên là vendor trong folder này sẽ chứa các thư viện bên thứ 3 và
các file autoload meta data do composer quản lý.

5. Do tất cả request đều qua file public/index.php sử lý và điều hướng trước tiên ta sẽ kiểm tra xem project đã chạy chưa
bằng cách thêm nội dung sau vào file public/index.php

// public/index.php

    <?php

    phpinfo();

Mở trình duyệt và truy cập vào địa chỉ localhost:8080/simple-mvc/public

nếu ta thấy in ra thông tin về php và thì có nghĩa là chúng ta đã config đúng

Nếu ko in ra gì thì ta cần kiểm tra lại folder simple-mvc có nằm trong webroot folder của apache chưa

tiếp theo ta cần config prety url
vd ta truy cập  `http://simplemvc.dev/index.php?url=login/index`
nhung ta muốn viết thành `http://simplemvc.dev/login`

để có được url như trên ta cần config mod rewrite của web server. Ta sẽ tạo thêm 1 file mới
trong public folder

// public/.htaccess

```

    RewriteEngine On
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteCond %{REQUEST_FILENAME} !-l
    RewriteRule ^(.*)$ index.php?url=$1 [QSA,L]

```


6. Phần tiếp theo chúg ta cần làm là từ thông tin request ta cần phải xác định được Controller
và action tương ứng sau đó khỏi tạo controller và gọi hàm action của controller đó

vd: khi truy cập vào địa chỉ `http://simplemvc.dev/users/show/1`
thì khi đó ta coi users là controller và  action là show và 1 là request path param

nghia là khi đó ta muốn request này sẽ được hàm `show` của UsersController class sử lý

```

    <?php

    namespace App\Controllers;

    class UsersController extends Controller
    {
        public function show($id)
        {
            echo $id;
        }
    }

```

để làm được điều đó ta chỉ cần khỏi tạo object của UsersController trong file public/index.php
rồi gọi hàm show là sẽ chạy.

Nhưng bạn khí số lượng controller/action nhiều lên thì có vẻ không ổn vì khi đó file index.php
sẽ chứa rất nhiều code và khó để mở rộng và maintain


Cách khác là ta sẽ tạo 1 class. class này sẽ nhận thông tin request và xác định controller và action tương ứng
rồi khỏi tạo chúng khi đó trong file index.php ta chỉ cần khởi tạo duy nhất 1 object controller này.
và controller này thường gọi là FrontController

7. Tạo FrontController

FrontController thường sẽ ko thuộc code của app và sẽ thuộc code của framework, thông thường
nhưng phần này sẽ tạo 1 package rồi cài qua composer. để đơn giản cho vd này
ta sẽ tạo 1 class trong `app/Core/Application.php`

// app/Core/Application.php

```

    <?php

    namespace App\Core;

    use App\Core\Session;


    /**
     * Front controller
     *
     * Parser request url and dispatch to coresponding controller action
     */
    class Application
    {
        const DEFAULT_CONTROLLER = "\App\Controllers\HomeController";
        const DEFAULT_ACTION = 'index';

        private $controller;
        private $action;
        private $params;

        public function __construct()
        {
            $this->setup();
            $this->parseUri();
        }

        /**
         * Common setup
         *
         */
        public function setup()
        {
            Session::start();
        }

        /*
         * Parser uri request and determine controller, action, param
         *
         */
        protected function parseUri()
        {
            $path = trim(parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH), "/");
            $pathInfo = explode("/", $path, 3);
            $this->params = [];
            $this->action = 'index';
            $this->controller = self::DEFAULT_CONTROLLER;

            if (count($pathInfo) >= 1 && $pathInfo[0] !== '') {
                if (isset($pathInfo[0])) {
                    $this->setController($pathInfo[0]);
                }

                if (isset($pathInfo[1])) {
                    $this->setAction($pathInfo[1]);
                }

                if (isset($pathInfo[2])) {
                    $this->params = [$pathInfo[2]];
                }
            }
        }

        /**
         * Get controller from uri and check if the controller exists in app/Controllers/ folder
         *
         * @param String $controller controller name
         *
         * @return App\Core\Application
         */
        public function setController($controller)
        {
            $controller = sprintf("\App\Controllers\%sController", ucfirst($controller));
            if (!class_exists($controller)) {
                throw new \Exception("Controller {$controller} not found!");
            }

            $this->controller = $controller;
            return $this;
        }

        /**
         * Get action from uri and check if the action exists
         *
         * @param String $action controller action name
         *
         * @return App\Core\Application
         */
        public function setAction($action)
        {
            $reflector = new \ReflectionClass($this->controller);
            if (!$reflector->hasMethod($action)) {
                throw new \Exception("Controller {$this->controller} action: {$action} not found!");
            }

            $this->action = $action;
            return $this;
        }


        /**
         * Dispath request to coresponding controller action with params
         *
         */
        public function run()
        {
            call_user_func_array(array(new $this->controller, $this->action), $this->params);
        }

    }

```

Logic của class này về cở bản ta sẽ làm những việc sau

```

    parse thông tin request cụ thể là request uri

    $path = trim(parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH), "/");

    Thông tin này sẽ có dạng $path = /users/show/1

    ta cần tách ra controller (users), action (show) và path parameter(1)

    ta sẽ dùng hàm explode của php để tác ra được array (0 => "users", 1 => "show", 2 => 1)

    khi đấy ta có controller là path[0], action = path[1], param[2]

    Khi ta có được thông tin controller và action ta sẽ khởi tạo class controller và gọi  hàm tương ứng

    vd: ta biết request đến users/show/1
    thì controller tương ứng sẽ là

    $controller = new \App\Controllers\UsersController();
    $controller->show(1);

    do chúng ta không biết controller và action tương ứng có tồn tại không lên ta cần check trước khi tạo object controller và
    gọi hàm tương ứng. trong 2 hàm setController và setAction sẽ làm việc trên

    hàm Application::run()
    hàm này gọi action tương ứng và pass parameter

```

khi ta có FrontController rồi thì ta chỉ cẩn khỏi tạo FrontController này và gọi hàm run() trong public/index.php
khi đó toạn bộ request đến app sẽ được frontController này sử lý trước và điều hướng cho các controller/action tương ứng khác.


Do trong 1 framework ngoài khởi tạo FrontController thì ta còn config nhiều thành phần khác nữa
như Database, config,...., khi đó thì file public/index.php  có thể sẽ nhiều

nên trong Framework này ta sẽ tạo 1 file `bootstrap/app.php`

file này sẽ chứa khỏi tạo FrontController, config và khởi tạo các component khác khi cần.

// bootstrap/app.php
```

    <?php

    require_once APP_ROOT . 'vendor/autoload.php';

    $app = new \App\Core\Application();


    $app->run();

```

File index.php giờ ta chỉ cần require file này vào là được

//public/index.php

```

    <?php

    define('APP_ROOT', '../');
    define('APP_PATH', APP_ROOT . 'app/');

    require_once(APP_ROOT . 'bootstrap/app.php');

```

Giờ các bạn có thể tạo các controller trong folder app/Controllers và request thử thì chúng ta sẽ thấy
FrontController sẽ điều hướng đến các Controller tương ứng rồi đó :D.

8. Ta đã có controller giờ ta sẽ làm tiêp phần view

phần view ta muốn dùng theo kiểu như sau

```

    // app/Controllers/LoginController.php
    <?php

    namespace App\Controllers;

    class LoginController extends Controller
    {
        public function index()
        {
            return view('login.index');
        }
    }


    trong controller action khi ta gọi return view('login.index'); hoặc return view('login.index', $data);

    thì sẽ đọc file view app/Views/login/index.html  và parse variable nếu có rồi return page về cho user


```

Ta sẽ tạo hàm view này trong helper

//app/Utils/Utils.php
```

    <?php

    if (!function_exists('dd')) {
        /**
         * Debug helper function
         *
         */
        function dd($data = null)
        {
            var_dump($data); die;
        }
    }

    if (!function_exists('view')) {
        /**
         * View Helper function
         *
         * @param string $view view path
         *
         */
        function view($view, array $params = [])
        {
            $view = str_replace('.', '/', $view);
            ob_start();
            extract($params, EXTR_SKIP);
            require_once APP_PATH . "Views/{$view}.html";
            ob_end_flush();
        }
    }

    if (!function_exists('config')) {
        /**
         * Debug helper function
         *
         */
        function config($key)
        {
            return \App\Core\Config::get($key);
        }
    }

```

trên file trên mình thêm luôn 2 hàm helper khác nhưng các bạn tạm thời để ý đên hàm
`function view($view, array $params = [])`

hàm này sẽ mở output buffer và export các variables để file view truy cập được rồi
parse file view tương ứng rồi end output buffer


9. Tiếp theo ta sẽ làm phần model.
phần này càn khởi tạo database và viết basemodel

trong folder app/Core ta sẽ tạo 1 class Database extend class PDO và pass các thông tin
cần thiết để connect đến database.

// app/Core/Database.php
```

    <?php

    namespace App\Core;

    use PDO;

    class Database extends PDO
    {
        public function __construct()
        {
            try {
                $config = config('database');
                $dns = sprintf("%s:host=%s;dbname=%s;port=%d;charset=%s",
                    $config['driver'],
                    $config['host'],
                    $config['db'],
                    $config['port'],
                    $config['charset']);

                $options = [
                    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES   => false,
                ];

                parent::__construct($dns, $config['user'], $config['password'], $options);
            } catch (\Exception $e) {
                error_log("Could not connect to database: " . $e->getMessage());
            }
        }
    }

```


Tạo Base Model. Base Model ta sẽ tạo 1 object PDO và 1 vài hàm cơ bản để truy vấn db

//app/Models/Model.php
````

    <?php

    namespace App\Models;

    use App\Core\Database;


    class Model
    {
        public static $db;
        protected $table;
        protected $primaryKey = 'id';

        public function __construct()
        {
            if (!isset($this->table)) {
                throw \Exception("table must be defined");
            }

            static::$db = new Database();
        }

        public function db()
        {
            static::$db;
        }

        public function close()
        {
            static::$db = null;
        }

        public function find($id)
        {
            $sql = "SELECT * FROM {$this->table} WHERE {$this->primaryKey}=:{$this->primaryKey}";
            $stmt = static::$db->prepare($sql);
            $stmt->execute([$this->primaryKey => $id]);

            return $stmt->fetch();
        }

        public function all($fields = ['*'])
        {
            $fields = implode(',', $fields);
            $sql = "SELECT {$fields} FROM {$this->table}";
            $stmt = static::$db->prepare($sql);
            $stmt->execute();

            return $stmt->fetchAll();
        }
    }

````


Khi ta đã có basemodel thì cần tao model mới ta chỉ cần tạo class tương ứng
trong app/Models như sau


// app/Models/User.php
```
    <?php

    namespace App\Models;

    class User extends Model
    {
        protected $table = 'users';


    }

```


Vậy là chúng ta đã có 1 frameowkr MVC đơn giản rồi đấy :D. ngoài ra còn 1 số class mình
wrapper Session, Cookie các bạn tham khảo trong folder app/Core nhé.

Các framework moderm framework như laravel hay các framework MVC khác thì sẽ có nhiều component
hơn và logic cũng phức tạp hơn nhưng về cơ bản flow sử lý là như ta đã làm ở đây.
