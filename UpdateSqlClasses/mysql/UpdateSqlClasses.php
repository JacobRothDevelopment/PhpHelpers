<?php

////////////////////////////////////////////////////////////////////
// Script Variables ////////////////////////////////////////////////
/* 	
Alter these values to configure your class creation
Alternatively, create a config/env file and include these settings here
	this would allow you to keep database info out of your repo
*/
$VARS = [
	'doTinyIntAsBool' => true,
	'dsn' => '',
	'dbUsername' => '',
	'dbPassword' => '',
	'pdoOptions' => null,
	'classDirectory' => '',
	'namespace' => '',
];
// End Script Variables ////////////////////////////////////////////
////////////////////////////////////////////////////////////////////



// translate sql data types to php data types
$PhpDataType = array(
	'varchar' => 'string',
	'nvarchar' => 'string',
	'char' => 'string',
	'blob' => 'string',
	'text' => 'string',
	'tinytext' => 'string',
	'smalltext' => 'string',
	'mediumtext' => 'string',
	'largetext' => 'string',
	'tinyint' => $VARS['doTinyIntAsBool'] ? 'bool' : 'int',
	'int' => 'int',
	'smallint' => 'int',
	'mediumint' => 'int',
	'bigint' => 'int',
	'decimal' => 'float',
	'real' => 'float',
	'double' => 'float',
	'float' => 'float',
	'json' => 'object',
	'datetime' => 'string',
	'date' => 'string',
	'timestamp' => 'string',
);

// used to designate a paramter as nullable or not
$Nullable = array(
	'YES' => '?',
	'NO' => '',
);

// this will be the 'this file was created automatically' message
$autoCreateMessage = '/* This file was created automatically
Do not alter this file as it may interfere with data handling in your database
If you wish to update this class, make your changes in the database
	then run UpdateSqlClasses.php again
*/';

// create db connaction
$pdo = new PDO(
	$VARS['dsn'],
	$VARS['dbUsername'],
	$VARS['dbPassword'],
	$VARS['pdoOptions'],
);

// Get array of table names
$sqlGetTables = 'SELECT 
		TABLE_NAME 
	FROM information_schema.tables 
	WHERE table_schema = database()';
$stmtTables = $pdo->prepare($sqlGetTables);
$stmtTables->execute();
$tableArray = $stmtTables->fetchAll();

// Get array of column data
$sqlGetColumnDefinitions = 'SELECT 
		TABLE_NAME, COLUMN_NAME, IS_NULLABLE, DATA_TYPE 
	FROM information_schema.columns 
	WHERE table_schema = database()';
$stmtColumns = $pdo->prepare($sqlGetColumnDefinitions);
$stmtColumns->execute();
$columnArray = $stmtColumns->fetchAll();

// Create db classes & files
foreach ($tableArray as $tableData) {
	$table = $tableData['TABLE_NAME'];
	$dir = __DIR__ . "/" . $VARS['classDirectory'] . "/";
	$file = $dir . $table . ".php";
	if (!file_exists($dir)) {
		$success = mkdir($dir, 0777, true);
		if (!$success)
			throw new Exception("Failed to create class directory");
	}
	$DbClassFile = fopen($file, "w");
	if ($DbClassFile === false)
		throw new Exception("Failed to open class file: $table");

	$classText = "<?php\n$autoCreateMessage\n\nnamespace " .
		$VARS['namespace'] . ";\n\nclass " . $table . "\n{\n";
	foreach ($columnArray as $column) {
		if ($column['TABLE_NAME'] === $table) {
			$classText .= "\tpublic "
				. $Nullable[$column['IS_NULLABLE']]
				. $PhpDataType[$column['DATA_TYPE']]
				. " $" . $column['COLUMN_NAME'] . ";\n";
		}
	}
	$classText .= "}\n";
	fwrite($DbClassFile, $classText);
	fclose($DbClassFile);
	chmod($file, 0777);
}
