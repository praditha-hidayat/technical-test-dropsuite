<?php

/**
 * Scan the folder
 * @param  string   $directoryPath
 * @return array    $scannedFiles
 */
function scanFolder($directoryPath) {
	$scannedFiles = [];

	$rdi = new RecursiveDirectoryIterator($directoryPath);
	foreach (new RecursiveIteratorIterator($rdi) as $filePath => $file) {
		if (is_file($file)) {
			// Calculate the md5 has of the file
			$md5_file = md5_file($filePath);

			// Push the scanned file info into array
			array_push($scannedFiles, [
        'md5_file_size' => md5_file($filePath) . "~" . $file->getSize(),
				'file_path' => $filePath
			]);
		}
	}

	return $scannedFiles;
}

/**
 * Count the same md5 file and file size
 * @param  array $scannedFiles
 * @return void
 */
function countFiles($scannedFiles) {
  // Convert the scanned files info into array which has:
  // file_path as index AND
  // md5_file_size as the value
  $sameFilesColumn = array_column($scannedFiles, 'md5_file_size', 'file_path');
  
  // Count the array which has the same md5_file_size value
  $sameFileNumber = array_count_values($sameFilesColumn);

	// Output the result
	$sameFiles = '';
	$i = 1;
	foreach ($sameFileNumber as $md5FileSize => $number) {
    // Skip the file which has no duplication
    if ($number <= 1) {
			continue;
		}

    // Get the first filepath based on md5 file size to be used to get file content
    $filePaths = array_filter($scannedFiles, function($fileInfo) use($md5FileSize) {
      if ($fileInfo['md5_file_size'] == $md5FileSize) {
        return $fileInfo['file_path'];
      }
    });

    // If the path is empty then skip it
    if (empty($filePaths)) continue;

    // Take the first element
    $filePath = reset($filePaths);

    // Get the file content
		$fileContent = getFileContent($filePath['file_path']);

    // Output the same file content
		$sameFiles .= "-== File No.{$i} ==-\n  Same File Number: {$number}\n  File Content: {$fileContent}\n\n";

    // Set the most same file
		if ($i == 1) $theMostSameFile = $sameFiles;
		$i++;
	}

	// Output the most same file
	echo "The file content that has the most duplication is:\n";
	echo $theMostSameFile . "\n";

	// Output the same files
	echo "Duplicate file list from the most duplication number: \n";
	echo $sameFiles;
	echo "\n";
}

/**
 * Get the file content
 * @param  string	  $filePath
 * @return string   $fileContent
 */
function getFileContent($filePath) {
	$handle = @fopen($filePath, "r");
	$fileContent = "";

	if ($handle) {
		$maxIteration = 1;	// To limit the iteration
		$i = 1;

		// Read the content for max 2kb
    while (($buffer = fgets($handle, 2000)) !== false && $i <= $maxIteration) {
      $fileContent .= $buffer;
      $i++;
    }

    fclose($handle);
	}

	return $fileContent;
}

function main() {
	// Load the configuration file
	$configs = include 'config.php';

	try {
		// scan the folder
		$scannedFolder = scanFolder($configs['path']);

		// count files
		countFiles($scannedFolder);

	} catch (Exception $e) {
		echo $e->getMessage();
	}
}

main();