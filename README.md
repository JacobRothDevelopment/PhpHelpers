# PhpHelpers

A library of PHP scripts and classes that aid in the development of PHP projects

All scripts are written for PHP 7.4 | 8.\*

---

## List of Helpers

- `UpdateSqlClasses.php`

  - These scripts are used to create php classes representing your database tables
  - Choose a script from `UpdateSqlClasses/` based on which sql implementation you're using

    - Supported Implementations

      - MySQL: `/UpdateSqlClasses/mysql/UpdateSqlClasses.php`

      - SQLite: `/UpdateSqlClasses/sqlite/UpdateSqlClasses.php`

  - See the top section of the script to configure the script's parameters
  - Tun from the root directory of project

    ```bash
    php UpdateSqlClasses.php
    ```

- `PdoWrapper.php`

  - I wrote this class because I hated copying and pasting the same PDO query & execution code and only needing to change the sql query
  - Initialize the object with your PDO instance, then use this class instead of your PDO
  - SqlExecution is the method you'll use the most (usage examples here)

    ```php
    $PdoWrapperInstance->SqlExecution($sql, $vars, [PDO::FETCH_CLASS, User::class], "fetch");

    $PdoWrapperInstance->SqlExecution($sql, [], [PDO::FETCH_COLUMN, 0], "fetchAll");
    ```

  - Each `SqlExecution()` tries to begin a transaction if one hasn't already been opened. Because of this, you'll need to run `SaveChanges()` to commit that transaction when you insert into of update your tables
    ```php
    $PdoWrapperInstance->SaveChanges();
    ```

- `Logger.php`

  - This class helps create a custom log file.
  - Use like

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

- `Util.php`

  - `DebugPrint()`

    - A wrapper around `print_r()` that will output data to the browser in a readable format
    - Optional string title and stringifies a given value of any type
    - Use like

    ```php
    \PhpHelpers\Util::DebugPrint($dataToOutput, "A Label To Describe Data");
    ```
