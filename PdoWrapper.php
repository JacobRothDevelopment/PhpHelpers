<?php

class PdoWrapper
{
    private PDO $db;

    public function __construct(PDO $pdo)
    {
        $this->db = $pdo;
    }

    #region private fucntions
    private function LogFailure(PDOStatement $stmt): void
    {
        $errorInfo = $stmt->errorInfo();
        error_log($errorInfo[2]);
    }

    // a wrapper for calling pdo functions
    // if the query fails, returns null
    private function SqlExecution(
        string $sql,
        array $varSet,
        ?int $fetchMode = null,
        ?string $fetchClassName = null,
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
        // TODO: MAKE BETTER
        if ($fetchMode !== null) {
            $stmt->setFetchMode($fetchMode, $fetchClassName); // eg PDO::FETCH_CLASS
        }

        // handle fetch method
        $result = null;
        switch ($fetchMethod) {
            case "fetch":
                $result = $stmt->fetch();
                // NOTE: if the query fails, fetch will return false
                // in order to account for this and to remain sane while programming,
                //  I will return null instead.
                if ($result === false) $result = null;
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

    #region TRANACTIONS
    public function SaveChanges(): bool
    {
        if (!$this->db->inTransaction()) {
            return true; // if no transaction, all changes are final anyway lol
        }
        return $this->db->commit();
    }
    #endregion
}
