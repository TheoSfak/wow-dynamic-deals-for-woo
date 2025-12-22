<?php
/**
 * Simple PO to MO compiler
 * Run: php compile-mo.php
 */

$po_file = __DIR__ . '/wow-dynamic-deals-for-woo-el.po';
$mo_file = __DIR__ . '/wow-dynamic-deals-for-woo-el.mo';

if (!file_exists($po_file)) {
    die("PO file not found: $po_file\n");
}

$entries = [];
$current_msgid = '';
$current_msgstr = '';
$in_msgid = false;
$in_msgstr = false;

$lines = file($po_file);
foreach ($lines as $line) {
    $line = trim($line);
    
    if (empty($line) || $line[0] === '#') {
        if ($current_msgid && $current_msgstr) {
            $entries[$current_msgid] = $current_msgstr;
        }
        $current_msgid = '';
        $current_msgstr = '';
        $in_msgid = false;
        $in_msgstr = false;
        continue;
    }
    
    if (strpos($line, 'msgid ') === 0) {
        if ($current_msgid && $current_msgstr) {
            $entries[$current_msgid] = $current_msgstr;
        }
        $current_msgid = '';
        $current_msgstr = '';
        $in_msgid = true;
        $in_msgstr = false;
        $current_msgid = substr($line, 6);
        $current_msgid = trim($current_msgid, '"');
    } elseif (strpos($line, 'msgstr ') === 0) {
        $in_msgid = false;
        $in_msgstr = true;
        $current_msgstr = substr($line, 7);
        $current_msgstr = trim($current_msgstr, '"');
    } elseif ($line[0] === '"') {
        $text = trim($line, '"');
        if ($in_msgid) {
            $current_msgid .= $text;
        } elseif ($in_msgstr) {
            $current_msgstr .= $text;
        }
    }
}

if ($current_msgid && $current_msgstr) {
    $entries[$current_msgid] = $current_msgstr;
}

// Remove empty msgid
unset($entries['']);

// Build MO file
$mo_data = pack('Iiiiiii', 0x950412de, 0, count($entries), 28, 28 + count($entries) * 8, 0, 28 + count($entries) * 16);

$offsets = [];
$ids = '';
$strs = '';

foreach ($entries as $id => $str) {
    $offsets[] = [strlen($ids), strlen($id), strlen($strs), strlen($str)];
    $ids .= $id . "\0";
    $strs .= $str . "\0";
}

foreach ($offsets as $offset) {
    $mo_data .= pack('ii', $offset[1], $offset[0]);
}

foreach ($offsets as $offset) {
    $mo_data .= pack('ii', $offset[3], $offset[2]);
}

$mo_data .= $ids . $strs;

file_put_contents($mo_file, $mo_data);

echo "Successfully compiled $po_file to $mo_file\n";
echo "Total translations: " . count($entries) . "\n";
