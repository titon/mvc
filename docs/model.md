# Model #

A model can be used to represent a single entity of data. It provides an object-like interface with passive getter and setter support.

```php
$user = new User($data);
$user->id; // get
$user->username = 'miles'; // set
isset($user->firstName); // has
unset($user->lastName); // remove
```

Alternate methods can be used to manipulate data as well. The same schema applies to set, has and remove.

```php
$user->getUsername(); // same as
$user->get('username'); // same as
$user->username;
```

Furthermore, a model supports passive getters and setters. These callbacks will be triggered anytime a field is written to or read.

```php
class User extends AbstractModel {
	protected $_getters = [
		'created' => 'getDate',
		'modified' => 'getDate'
	];

	protected $_setters = [
		'created' => 'setDate',
		'modified' => 'setDate'
	];

	public function getDate($value) {
		return date('Y-m-d H:i:s', $value);
	}

	public function setDate($value) {
		return is_numeric($value) ? $value : strtotime($value);
	}
}
```

In the example above, any time a date is set the unix timestamp is used. When the date is retrieved, it is formatted.