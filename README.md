# PhpHelpers

An assortment of PHP scripts and classes that aid in the development of PHP projects

All scripts are written for PHP 7.4 & 8

---

## List of helpers
- UpdateSqlClasses.php
    - this script is used to create php classes representing your database tables
    - see the top section of the script to confiure the script's paramters
    - run from the root directory of project
        ```
        php UpdateSqlClasses.php
        ```
- PdoWrapper.php
    - this class was written beacuse I hated copying and pasting the same pdo query execution code and only needing to change the sql query
    - initalize the object with your PDO instance, then use this class instead of your PDO
    - SqlExecution is the method you'll use the most (usage examples here)
        ```php
        $this->SqlExecution($sql, $vars, PDO::FETCH_CLASS, "user", "fetch");

        $this->SqlExecution($sql, [], PDO::FETCH_CLASS, "stores", "fetchAll");
        ```
    - MAJOR NOTE: with this first release, this class is very rough because it's simply a rough abstraction of what I purpose built for a project. In later versions, this will be made more usable

---

### Dev notes

Some of these scripts/classes are designed to work in conjunction together. If you try to use some of these files alone, you may run into errors.
