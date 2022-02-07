# PhpHelpers

A library of PHP scripts and classes that aid in the development of PHP projects

All scripts are written for PHP 7.4 | 8.\*

---

## List of helpers

- UpdateSqlClasses.php
  - this script is used to create php classes representing your database tables
  - see the top section of the script to configure the script's parameters
  - run from the root directory of project
    ```bash
    php UpdateSqlClasses.php
    ```
- PdoWrapper.php

  - this class was written because I hated copying and pasting the same pdo query execution code and only needing to change the sql query
  - initialize the object with your PDO instance, then use this class instead of your PDO
  - SqlExecution is the method you'll use the most (usage examples here)

    ```php
    $this->SqlExecution($sql, $vars, PDO::FETCH_CLASS, "user", "fetch");

    $this->SqlExecution($sql, [], PDO::FETCH_CLASS, "stores", "fetchAll");
    ```

  - MAJOR NOTE: with this first release, this class is very rough because it's simply a loose abstraction of what I purpose built for a project. In later versions, this will be made more usable

- Logger.php
  - this class helps create a custom log file.
  - use like
    ```php
    $logger = new Logger("path/to/file.log");
    $logger->log("hello world!");
    $logger->log("hello world!", LogLevel::Warn);
    ```
    outputs
    ```log
    [ 2021-09-25 07:18:52 ] [ INFO ] hello world!
    [ 2021-09-25 07:18:40 ] [ WARN ] hello world!
    ```
- Util.php
  - DebugPrint
    - a wrapper around `print_r` which will output data to the browser in a readable format
    - optional string title and stringifies a given value of any type

---

### Dev notes

Some of these scripts/classes may be designed to work in conjunction together. If you try to use some of these files alone, you may run into errors.
