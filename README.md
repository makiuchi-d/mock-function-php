Mock Function for PHP
=====================

simple mocking user/system function using runkit extension.

## Example
```PHP
MockFunction::replace('file_get_contents', 'mock string');
// file_get_contents() returns "mock string" until MockFunction::restore() is called.

echo file_get_contents('text_file'); // "mock string" will be echo.

MockFunction::restore('file_get_contents'); // restore original function.

echo file_get_contents('text_file'); // contents of 'text_file' will be echo.
```
