<?php
/**
 * Translate ALL remaining strings EXCEPT settings.php
 */

$po_file = __DIR__ . '/wow-dynamic-deals-for-woo-el.po';
$po_content = file_get_contents($po_file);

// Find all empty translations with their file references
preg_match_all('/#: (.+?)\nmsgid "(.+?)"\nmsgstr ""/s', $po_content, $matches, PREG_SET_ORDER);

$settings_count = 0;
$others_count = 0;
$to_translate = [];

foreach ($matches as $match) {
	$file_ref = $match[1];
	$msgid = $match[2];
	
	// Skip settings.php strings
	if (strpos($file_ref, 'admin/views/settings.php') !== false) {
		$settings_count++;
		continue;
	}
	
	// Collect others for translation
	$to_translate[] = $msgid;
	$others_count++;
}

echo "Settings strings (skipping): $settings_count\n";
echo "Other empty strings (translating): $others_count\n\n";

if ($others_count > 0) {
	echo "Sample strings to translate:\n";
	foreach (array_slice($to_translate, 0, 30) as $str) {
		echo "- $str\n";
	}
}
