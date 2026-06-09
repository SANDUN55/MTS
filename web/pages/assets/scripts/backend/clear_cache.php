<?php
// Clear any existing cache files
$files = [
    'debug_query.txt',
    'debug_error.txt', 
    'o.txt',
    'example3.txt'
];

foreach ($files as $file) {
    if (file_exists($file)) {
        unlink($file);
        echo "Cleared: $file\n";
    }
}

echo "Cache cleared successfully!";
?>
