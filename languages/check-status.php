<?php
$po = 'wow-dynamic-deals-for-woo-el.po';
$content = file_get_contents($po);
$count = preg_match_all('/msgstr ""$/m', $content);
echo "Empty translations: $count\n";
echo "File size: " . filesize($po) . " bytes\n";
