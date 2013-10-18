Mock Function for PHP
=====================

simple mocking user/system function using runkit extension.

## Examples

### Simple replacement

```PHP
MockFunction::replace('file_get_contents', 'mock string');
// file_get_contents() returns "mock string" until MockFunction::restore() is called.

echo file_get_contents('text_file'); // "mock string" will be echo.

MockFunction::restore('file_get_contents'); // restore original function.

echo file_get_contents('text_file'); // contents of 'text_file' will be echo.
```

### Nesting

```PHP
var_dump(date_create()); // today

MockFunction::replace('date_create', date_create('yesterday'));

var_dump(date_create()); // yesterday

MockFunction::replace('date_create', date_create('tomorrow'));

var_dump(date_create()); // tomorrow

MockFunction::restore('date_create');

var_dump(date_create()); // yesterday

MockFunction::restore('date_create');

var_dump(date_create()); // today
```

### Variable expansion

```PHP
MockFunction::replace('file_get_contents', 'args[0]: {$args[0]}', true);

echo file_get_contents('text_file'); // "args[0]: text_file" will be echo
```

### Calling function

```PHP
MockFunction::replace('file_get_contents', 'dirname', true);

echo file_get_contents('/proc/version'); // "/proc"
```

### Calling original function from replacing function.

```PHP
$a = array(1,2,3,4,5);

MockFunction::replace('implode', function($glue, $pieces){
		return __original__implode("={$glue}=", $pieces);
	}, true);

echo implode('+',$a); // "1=+=2=+=3=+=4=+=5"
```
