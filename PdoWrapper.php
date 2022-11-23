<?php

namespace PhpHelpers;

/**
 * A wrapper for calling PDO functions
 */
class PdoWrapper
{
    protected \PDO $db;

    public function __construct(\PDO $pdo)
    {
        $this->db = $pdo;
    }

    #region private functions
    protected function LogFailure(\PDOStatement $stmt): void
    {
        $errorInfo = $stmt->errorInfo();
        error_log(print_r($errorInfo, true));
    }

    /**
     * Execute PDO commands
     *
     * @param string $sql SQL command
     * @param array $varSet PDO variables used in execute()
     * @param array|null $fetchMode array of values used in setFetchMode();
     * eg [PDO::FETCH_CLASS, YourPhpClass::class]
     * @param string|null $fetchMethod 'fetch' or 'fetchAll'
     * @return mixed if no records are found, either null or an empty array
     * will be returned, depending on whether you use 'fetch or 'fetchAll'
     * @throws Exception if using invalid fetchMethod
     */
    protected function SqlExecution(
        string $sql,
        array $varSet,
        ?array $fetchMode = null,
        ?string $fetchMethod = null
    ) {
        // force transaction because why not
        if (!$this->db->inTransaction()) {
            $this->db->beginTransaction();
        }

        // cast ?bool/bool as 1/0/NULL
        foreach ($varSet as $key => $value) {
            switch (gettype($value)) {
                case "boolean":
                    $varSet[$key] = $value ? 1 : 0;
                    break;
                default:
                    break;
            }
        }

        // get and handle success status of query
        $stmt = $this->db->prepare($sql);
        $success = $stmt->execute($varSet);
        if (!$success) {
            $this->LogFailure($stmt);
            return false;
        }

        // handle fetch mode
        if ($fetchMode !== null) {
            $stmt->setFetchMode(...$fetchMode);
            // eg [PDO::FETCH_CLASS, 'CLASS_NAME']
        }

        // handle fetch method
        $result = null;
        switch ($fetchMethod) {
            case "fetch":
                /*
                fetch() returns false if no rows are found.
                To combat this idiocrasy, I'll fetch all rows and if no rows
                are found, return null.
                If rows are found, return the first
                */
                $allResults = $stmt->fetchAll();
                if (count($allResults) > 0){
                    $result = $allResults[0];
                }
                break;
            case "fetchAll":
                $result = $stmt->fetchAll();
                break;
            default:
                break;
        }

        return $result;
    }
    #endregion

    #region TRANSACTIONS
    /**
     * Commits The changes made during the database transaction;
     * A transaction is created when using SqlExecution
     *
     * @return boolean true if commit was successful; false if not
     */
    public function SaveChanges(): bool
    {
        if (!$this->db->inTransaction()) {
            // if no transaction, all changes are final anyway lol
            return true;
        }
        return $this->db->commit();
    }
    #endregion
}
