<?php

////////////////////////////////////////////////////////////////////
// Script Variables ////////////////////////////////////////////////
/* 	
Alter these values to configure your class creation
Alternatively, create a config/env file and include these settings here
	this would allow you to keep database info out of your repo
*/
$VARS = [
	'dsn' => '',
	'dbUsername' => '',
	'dbPassword' => '',
	'pdoOptions' => null,
	'classDirectory' => '',
	'namespace' => null, // null means no namespace
];
// End Script Variables ////////////////////////////////////////////
////////////////////////////////////////////////////////////////////



// translate sql data types to php data types
$PhpDataType = array(
	'' => 'string',
	'INTEGER' => 'int',
	'REAL' => 'float',
	'TEXT' => 'string',
	'BLOB' => 'string',
);

// used to designate a parameter as nullable or not
$notNull = array(
	'2' => '',
	'1' => '',
	'0' => '?',
);

// this will be the 'this file was created automatically' message
$autoCreateMessage = '/* This file was created automatically
Do not alter this file as it may interfere with data handling in your database
If you wish to update this class, make your changes in the database
	then run UpdateSqlClasses.php again
*/';

// create db connection
$pdo = new PDO(
	$VARS['dsn'],
	$VARS['dbUsername'],
	$VARS['dbPassword'],
	$VARS['pdoOptions'],
);

// Get array of table names
$sqlGetTables = "SELECT tbl_name 
	FROM sqlite_master
	where type = 'table'
	ORDER BY name;";
$stmtTables = $pdo->prepare($sqlGetTables);
$stmtTables->execute();
$tableArray = $stmtTables->fetchAll();

// Create db classes & files
foreach ($tableArray as $tableData) {
	$table = $tableData['tbl_name'];
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

	$phpText = "<?php\n$autoCreateMessage\n\n";
	$namespaceText = $VARS['namespace'] === null ? "" : "namespace " . $VARS['namespace'] . ";\n\n";
	$tableClassText = "class $table\n{\n";
	$classText = $phpText . $namespaceText . $tableClassText;

	// Get array of column data
	$sqlGetColumnDefinitions = "PRAGMA table_info($table)";
	$stmtColumns = $pdo->prepare($sqlGetColumnDefinitions);
	$success = $stmtColumns->execute([]);
	$columnArray = $stmtColumns->fetchAll();

	foreach ($columnArray as $column) {
		$classText .= "\tpublic "
			. $notNull[(int)$column['notnull'] + (int)$column['pk']]
			. $PhpDataType[$column['type']]
			. " $" . $column['name'] . ";\n";
	}
	$classText .= "}\n";
	fwrite($DbClassFile, $classText);
	fclose($DbClassFile);
	chmod($file, 0777);
}
