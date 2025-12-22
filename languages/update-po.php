<?php
/**
 * Update Greek PO file with translations from POT
 */

$pot_file = __DIR__ . '/wow-dynamic-deals-for-woo.pot';
$po_file = __DIR__ . '/wow-dynamic-deals-for-woo-el.po';

// Greek translations dictionary
$translations = [
	// Core
	'Woo Dynamic Deals' => 'Woo Δυναμικές Προσφορές',
	'requires WooCommerce to be installed and activated.' => 'απαιτεί το WooCommerce να είναι εγκατεστημένο και ενεργοποιημένο.',
	'requires PHP 8.0 or higher. Your current PHP version is' => 'απαιτεί PHP 8.0 ή νεότερο. Η τρέχουσα έκδοση PHP είναι',
	'Plugin activation failed. Please check the requirements.' => 'Η ενεργοποίηση του plugin απέτυχε. Παρακαλώ ελέγξτε τις απαιτήσεις.',
	'Plugin Activation Error' => 'Σφάλμα Ενεργοποίησης Plugin',
	'Settings' => 'Ρυθμίσεις',
	
	// Modal form fields
	'Rule Title' => 'Τίτλος Κανόνα',
	'Adjustment Type' => 'Τύπος Προσαρμογής',
	'Select type...' => 'Επιλέξτε τύπο...',
	'Apply To' => 'Εφαρμογή Σε',
	'Regular Price' => 'Κανονική Τιμή',
	'Sale Price' => 'Τιμή Προσφοράς',
	'Both' => 'Και τα δύο',
	'Product Selection' => 'Επιλογή Προϊόντων',
	'Specific Products' => 'Συγκεκριμένα Προϊόντα',
	'Leave empty to apply to all products' => 'Αφήστε κενό για εφαρμογή σε όλα τα προϊόντα',
	'Product Categories' => 'Κατηγορίες Προϊόντων',
	'Start Date' => 'Ημερομηνία Έναρξης',
	'End Date' => 'Ημερομηνία Λήξης',
	'Start Time' => 'Ώρα Έναρξης',
	'End Time' => 'Ώρα Λήξης',
	'Cancel' => 'Ακύρωση',
	'Save Rule' => 'Αποθήκευση Κανόνα',
	'Save Gift Rule' => 'Αποθήκευση Κανόνα Δώρου',
	
	// Dashboard
	'Dynamic Deals for WooCommerce' => 'Δυναμικές Προσφορές για WooCommerce',
	'Price Rules' => 'Κανόνες Τιμών',
	'Create dynamic pricing rules to adjust product prices based on conditions' => 'Δημιουργήστε δυναμικούς κανόνες τιμολόγησης για προσαρμογή των τιμών προϊόντων με βάση συνθήκες',
	'Discount Rules' => 'Κανόνες Εκπτώσεων',
	'Set up cart and product-level discounts with flexible conditions' => 'Ρυθμίστε εκπτώσεις σε επίπεδο καλαθιού και προϊόντος με ευέλικτες συνθήκες',
	'Free Gifts' => 'Δωρεάν Δώρα',
	'Delight customers with automatic free gifts based on purchase conditions' => 'Ευχαριστήστε τους πελάτες με αυτόματα δωρεάν δώρα με βάση συνθήκες αγοράς',
	'Examples' => 'Παραδείγματα',
	'Explore real-world examples and use cases' => 'Εξερευνήστε παραδείγματα πραγματικού κόσμου και περιπτώσεις χρήσης',
	'Documentation' => 'Τεκμηρίωση',
	'Learn how to use the plugin effectively' => 'Μάθετε πώς να χρησιμοποιείτε αποτελεσματικά το plugin',
	'Support' => 'Υποστήριξη',
	'Get help and support' => 'Λάβετε βοήθεια και υποστήριξη',
	
	// Actions
	'Add Price Rule' => 'Προσθήκη Κανόνα Τιμής',
	'Add Discount Rule' => 'Προσθήκη Κανόνα Έκπτωσης',
	'Add Gift Rule' => 'Προσθήκη Κανόνα Δώρου',
	'Edit' => 'Επεξεργασία',
	'Delete' => 'Διαγραφή',
	'Active' => 'Ενεργό',
	'Inactive' => 'Ανενεργό',
	'Enabled' => 'Ενεργοποιημένο',
	'Disabled' => 'Απενεργοποιημένο',
	'Yes' => 'Ναι',
	'No' => 'Όχι',
	
	// Table headers
	'Title' => 'Τίτλος',
	'Type' => 'Τύπος',
	'Value' => 'Αξία',
	'Priority' => 'Προτεραιότητα',
	'Status' => 'Κατάσταση',
	'Actions' => 'Ενέργειες',
	'Products' => 'Προϊόντα',
	'Categories' => 'Κατηγορίες',
	'Schedule' => 'Προγραμματισμός',
	
	// Pricing types
	'Percentage Discount' => 'Ποσοστιαία Έκπτωση',
	'Fixed Discount' => 'Σταθερή Έκπτωση',
	'Fixed Price' => 'Σταθερή Τιμή',
	'Price Increase' => 'Αύξηση Τιμής',
	
	// Messages
	'No rules found. Create your first rule to get started!' => 'Δεν βρέθηκαν κανόνες. Δημιουργήστε τον πρώτο σας κανόνα για να ξεκινήσετε!',
	'Are you sure you want to delete this rule?' => 'Είστε σίγουροι ότι θέλετε να διαγράψετε αυτόν τον κανόνα;',
	'Rule deleted successfully' => 'Ο κανόνας διαγράφηκε επιτυχώς',
	'Rule saved successfully' => 'Ο κανόνας αποθηκεύτηκε επιτυχώς',
	'Error saving rule' => 'Σφάλμα κατά την αποθήκευση του κανόνα',
	
	// Cart message
	'Free Gifts in Your Cart!' => 'Δωρεάν Δώρα στο Καλάθι σας!',
	
	// Gift triggers
	'Cart Subtotal' => 'Υποσύνολο Καλαθιού',
	'Specific Products in Cart' => 'Συγκεκριμένα Προϊόντα στο Καλάθι',
	'Product Quantity' => 'Ποσότητα Προϊόντος',
	'Category Products in Cart' => 'Προϊόντα Κατηγορίας στο Καλάθι',
	
	// Settings
	'General Settings' => 'Γενικές Ρυθμίσεις',
	'Enable Dynamic Pricing' => 'Ενεργοποίηση Δυναμικής Τιμολόγησης',
	'Enable Discount Rules' => 'Ενεργοποίηση Κανόνων Έκπτωσης',
	'Enable Free Gifts' => 'Ενεργοποίηση Δωρεάν Δώρων',
	'Display Settings' => 'Ρυθμίσεις Εμφάνισης',
	'Show savings in cart' => 'Εμφάνιση εξοικονόμησης στο καλάθι',
	'Show discount badge on products' => 'Εμφάνιση σήματος έκπτωσης στα προϊόντα',
];

// Parse POT file
$pot_content = file_get_contents($pot_file);
preg_match_all('/#: (.+?)\nmsgid "(.+?)"\nmsgstr ""/s', $pot_content, $matches, PREG_SET_ORDER);

// Build PO content
$po_content = <<<PO
# Greek translation for Wow Dynamic Deals for Woo
# Copyright (C) 2025 Theodore Sfakianakis
# This file is distributed under the same license as the Wow Dynamic Deals for Woo plugin.
msgid ""
msgstr ""
"Project-Id-Version: Wow Dynamic Deals for Woo 1.1.0\\n"
"Report-Msgid-Bugs-To: https://github.com/TheoSfak/wow-dynamic-deals-for-woo/issues\\n"
"POT-Creation-Date: 2025-12-22 12:00+0000\\n"
"PO-Revision-Date: 2025-12-22 12:10+0000\\n"
"Last-Translator: Theodore Sfakianakis\\n"
"Language-Team: Greek\\n"
"Language: el\\n"
"MIME-Version: 1.0\\n"
"Content-Type: text/plain; charset=UTF-8\\n"
"Content-Transfer-Encoding: 8bit\\n"
"Plural-Forms: nplurals=2; plural=(n != 1);\\n"


PO;

$translated_count = 0;
$untranslated_count = 0;

foreach ($matches as $match) {
	$reference = $match[1];
	$msgid = $match[2];
	
	// Add reference
	$po_content .= "#: $reference\n";
	
	// Add msgid
	$po_content .= 'msgid "' . $msgid . "\"\n";
	
	// Add msgstr (translated or empty)
	if (isset($translations[$msgid])) {
		$po_content .= 'msgstr "' . $translations[$msgid] . "\"\n\n";
		$translated_count++;
	} else {
		$po_content .= 'msgstr ""' . "\n\n";
		$untranslated_count++;
	}
}

// Special case for cart message with context
$po_content .= <<<CART
#: includes/class-frontend-display.php:270
msgctxt "Header message shown when free gifts are in cart"
msgid "Free Gifts in Your Cart!"
msgstr "Δωρεάν Δώρα στο Καλάθι σας!"

CART;

// Write PO file
file_put_contents($po_file, $po_content);

// Also write el_GR version
$po_gr_file = __DIR__ . '/wow-dynamic-deals-for-woo-el_GR.po';
file_put_contents($po_gr_file, $po_content);

echo "PO files updated!\n";
echo "Translated: $translated_count strings\n";
echo "Untranslated: $untranslated_count strings\n";
echo "Total: " . ($translated_count + $untranslated_count) . " strings\n";
echo "\nFiles:\n";
echo "- $po_file\n";
echo "- $po_gr_file\n";
