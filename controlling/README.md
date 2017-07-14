### controlling

PHP-Files to automate the controlling process.

### execute_all.php

#### Usage

php execute_all.php <interpreter> <deepth> file1.php file2.php

Allowd interpreter:

 - php
 - phpunit

Allowed deepths:

 - 0(equals ./)
 - 1(equals ../)
 - 2(equals ../../)
 
If you are two deepths away from the root of SchemeRealizer than your deepth is 2.

Execute execute_all.php from the directory which includes the files to execute!
