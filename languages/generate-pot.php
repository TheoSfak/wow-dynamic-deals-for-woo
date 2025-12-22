<?php
/**
 * Generate POT file from PHP source files
 */

$plugin_dir = dirname(__DIR__);
$domain = 'wow-dynamic-deals-for-woo';
$strings = [];

// Scan all PHP files
function scan_directory($dir, $domain) {
	$strings = [];
	$iterator = new RecursiveIteratorIterator(
		new RecursiveDirectoryIterator($dir, RecursiveDirectoryIterator::SKIP_DOTS),
		RecursiveIteratorIterator::SELF_FIRST
	);
	
	foreach ($iterator as $file) {
		if ($file->isFile() && $file->getExtension() === 'php') {
			$content = file_get_contents($file->getPathname());
			$relative = str_replace(rtrim($dir, '/\\') . DIRECTORY_SEPARATOR, '', $file->getPathname());
			$relative = str_replace('\\', '/', $relative);
			
			// Skip languages directory
			if (strpos($relative, 'languages/') === 0) {
				continue;
			}
			
			// Match translation function patterns
			// Pattern 1: esc_html_e( 'String', 'domain' )
			// Pattern 2: esc_html__( 'String', 'domain' )
			// Pattern 3: __( 'String', 'domain' )
			// Pattern 4: _e( 'String', 'domain' )
			preg_match_all('/(?:esc_html_e|esc_html__|__|_e|esc_attr_e|esc_attr__|esc_attr_x|esc_html_x|_x|_ex|_n|_nx)\s*\(\s*[\'"]([^\'"]+)[\'"]\s*,\s*[\'"]('. preg_quote($domain) .')[\'"]/s', $content, $matches, PREG_SET_ORDER | PREG_OFFSET_CAPTURE);
			
			foreach ($matches as $match) {
				$string = $match[1][0];
				$string = str_replace('\\"', '"', $string); // Unescape quotes
				$string = str_replace("\\'", "'", $string);
				
				if (!isset($strings[$string])) {
					$strings[$string] = [];
				}
				$strings[$string][] = $relative;
			}
		}
	}
	return $strings;
}

echo "Scanning directory: $plugin_dir\n";
$strings = scan_directory($plugin_dir, $domain);
ksort($strings);

echo "Found " . count($strings) . " unique translatable strings\n\n";

// Generate POT content
$pot_content = <<<POT
# Copyright (C) 2025 Theodore Sfakianakis
# This file is distributed under the same license as the Wow Dynamic Deals for Woo plugin.
msgid ""
msgstr ""
"Project-Id-Version: Wow Dynamic Deals for Woo 1.1.0\\n"
"Report-Msgid-Bugs-To: https://github.com/TheoSfak/wow-dynamic-deals-for-woo/issues\\n"
"POT-Creation-Date: 2025-12-22 12:00+0000\\n"
"PO-Revision-Date: YEAR-MO-DA HO:MI+ZONE\\n"
"Last-Translator: FULL NAME <EMAIL@ADDRESS>\\n"
"Language-Team: LANGUAGE <LL@li.org>\\n"
"Language: en\\n"
"MIME-Version: 1.0\\n"
"Content-Type: text/plain; charset=UTF-8\\n"
"Content-Transfer-Encoding: 8bit\\n"
"X-Generator: Manual\\n"
"Plural-Forms: nplurals=2; plural=(n != 1);\\n"


POT;

foreach ($strings as $string => $files) {
	// Add file references
	foreach (array_unique($files) as $file) {
		$pot_content .= "#: $file\n";
	}
	
	// Add msgid
	$pot_content .= 'msgid "' . addcslashes($string, '"\\') . "\"\n";
	$pot_content .= 'msgstr ""' . "\n\n";
}

// Write POT file
$pot_file = __DIR__ . '/wow-dynamic-deals-for-woo.pot';
file_put_contents($pot_file, $pot_content);

echo "POT file generated: $pot_file\n";
echo "File size: " . filesize($pot_file) . " bytes\n";
