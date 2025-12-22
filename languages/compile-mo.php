<?php
/**
 * Improved PO to MO compiler
 * Run: php compile-mo.php
 */

$po_file = __DIR__ . '/wow-dynamic-deals-for-woo-el.po';
$mo_file = __DIR__ . '/wow-dynamic-deals-for-woo-el.mo';

if (!file_exists($po_file)) {
    die("PO file not found: $po_file\n");
}

// Parse PO file
$entries = [];
$current_msgid = '';
$current_msgstr = '';
$in_msgid = false;
$in_msgstr = false;

$lines = file($po_file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
foreach ($lines as $line) {
    // Skip comments
    if ($line[0] === '#') {
        continue;
    }
    
    // Start of msgid
    if (preg_match('/^msgid\s+"(.*)"\s*$/', $line, $matches)) {
        // Save previous entry
        if ($current_msgid !== '' && $current_msgstr !== '') {
            $entries[$current_msgid] = $current_msgstr;
        }
        $current_msgid = stripcslashes($matches[1]);
        $current_msgstr = '';
        $in_msgid = true;
        $in_msgstr = false;
    }
    // Start of msgstr
    elseif (preg_match('/^msgstr\s+"(.*)"\s*$/', $line, $matches)) {
        $current_msgstr = stripcslashes($matches[1]);
        $in_msgid = false;
        $in_msgstr = true;
    }
    // Continuation line
    elseif (preg_match('/^"(.*)"\s*$/', $line, $matches)) {
        $text = stripcslashes($matches[1]);
        if ($in_msgid) {
            $current_msgid .= $text;
        } elseif ($in_msgstr) {
            $current_msgstr .= $text;
        }
    }
}

// Save last entry
if ($current_msgid !== '' && $current_msgstr !== '') {
    $entries[$current_msgid] = $current_msgstr;
}

// Remove empty msgid (header)
unset($entries['']);

// Sort entries by msgid for consistent offsets
ksort($entries);

$count = count($entries);
echo "Found $count translations\n";

// Build MO file structure
// Header: magic (4) + version (4) + count (4) + originals offset (4) + translations offset (4) + hash size (4) + hash offset (4)
$magic = 0x950412de; // Little endian magic number
$version = 0;
$hash_size = 0;
$hash_offset = 0;

// Calculate offsets
$originals_offset = 28; // Header size
$translations_offset = $originals_offset + ($count * 8); // Each entry: length (4) + offset (4)

// Build strings tables
$originals_table = '';
$translations_table = '';
$originals_strings = '';
$translations_strings = '';

$orig_offset = $translations_offset + ($count * 8);
$trans_offset = $orig_offset;

foreach ($entries as $msgid => $msgstr) {
    // Calculate current offsets
    $trans_offset = $orig_offset + strlen($originals_strings);
    
    // Add to tables
    $originals_table .= pack('II', strlen($msgid), $orig_offset + strlen($originals_strings));
    $translations_table .= pack('II', strlen($msgstr), $trans_offset + strlen($originals_strings) + strlen($msgstr) + $count);
    
    // Add strings
    $originals_strings .= $msgid . "\0";
    $translations_strings .= $msgstr . "\0";
}

// Recalculate translation string offset
$strings_offset = $originals_offset + ($count * 8) + ($count * 8);

// Rebuild tables with correct offsets
$originals_table = '';
$translations_table = '';
$current_orig_offset = $strings_offset;
$current_trans_offset = $strings_offset + strlen($originals_strings);

foreach ($entries as $msgid => $msgstr) {
    $originals_table .= pack('II', strlen($msgid), $current_orig_offset);
    $translations_table .= pack('II', strlen($msgstr), $current_trans_offset);
    
    $current_orig_offset += strlen($msgid) + 1;
    $current_trans_offset += strlen($msgstr) + 1;
}

// Build final MO file
$mo_data = pack('IIIIIII',
    $magic,
    $version,
    $count,
    $originals_offset,
    $translations_offset,
    $hash_size,
    $hash_offset
);
$mo_data .= $originals_table;
$mo_data .= $translations_table;
$mo_data .= $originals_strings;
$mo_data .= $translations_strings;

file_put_contents($mo_file, $mo_data);

echo "Successfully compiled $po_file to $mo_file\n";
echo "File size: " . strlen($mo_data) . " bytes\n";
echo "Total translations: " . $count . "\n";
