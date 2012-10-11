TM4B API Wrapper written in PHP
=======================================
**(API Version 2.1)**

A powerful PHP class for [TM4B](https://tm4b.com/)'s [API](http://www.tm4b.com/kb/docs/tm4b-http-api-ca-2.1.pdf) (version 2.1).
Supports all features that TM4Bs API provides.

Only requires PHP >=5.3! HTTPS requests are supported natively if necessary!

__That's all!__

Take a look at the [Wiki](https://github.com/nicoSWD/TM4B-API/wiki/) and [TM4B's API documentation](http://www.tm4b.com/kb/docs/tm4b-http-api-ca-2.1.pdf) to get started.

**Pull requests are welcome! Fix, improve, suggest!**

You can also find me on Twitter: @[nicoSWD](https://twitter.com/nicoSWD)


Examples
========

```php
require 'TM4B/Autoloader.php';
$tm4b = new TM4B\API($username, $password);
$tm4b->setFrom('Nico');

// Broadcast an SMS message:
$response = $tm4b->broadcast('34612345678', 'Hi there');

// Check a message's status:
$status = $tm4b->checkStatus($response['broadcastID']);

// Check remaining credits:
$credits = $tm4b->checkCredit();

// ...
```


License
=======
Copyright (C) 2012  Nicolas Oelgart

This program is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program.  If not, see <http://www.gnu.org/licenses/>.