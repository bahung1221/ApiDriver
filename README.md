# API Driver For Laravel 5.2

An Eloquent model and Query builder with support for Restful Api Server, using the original Laravel API. This library extends the original Laravel classes, so it uses exactly the same methods.

### Installation
---------------
Installation using composer:
```bash
composer require hungnguyenba/apidriver
```

And add the service provider in `config/app.php`:
```php
Hungnguyenba\Apidriver\DatabaseServiceProvider::class
```

### Configuration
----------------
Change your default database connection name in `config/database.php`:

```php
'default' => 'api'
```

And add a new api server connection:

```php
'api' => [
        'driver' => 'api',
        'host' => 'localhost/v1/',
        'database' => '',
        'prefix' => '',
]
```

### Usage
--------

Create new Model extend Api Eloquent Model:

```php
use Hungnguyenba\Apidriver\Model\Model;

class User extends Model
{

}
```

Using the original Eloquent API:

```php
$users = User::where('id', '<', 100)->take(3)->get();
$user = User::find(3);
$user->delete();
```