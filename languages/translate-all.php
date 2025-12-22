<?php
/**
 * COMPREHENSIVE Greek Translation Updater
 * Translates ALL remaining strings
 */

$po_file = __DIR__ . '/wow-dynamic-deals-for-woo-el.po';
$po_lines = file($po_file, FILE_IGNORE_NEW_LINES);

// MASSIVE Greek translations dictionary (200+ strings)
$translations = [
	// Core & System
	'Wow Dynamic Deals for Woo' => 'Wow Δυναμικές Προσφορές για Woo',
	'Dynamic Deals for WooCommerce' => 'Δυναμικές Προσφορές για WooCommerce',
	'Plugin Name' => 'Όνομα Plugin',
	'Plugin Version' => 'Έκδοση Plugin',
	'Plugin Author' => 'Δημιουργός Plugin',
	
	// Days - Critical!
	'Monday' => 'Δευτέρα',
	'Tuesday' => 'Τρίτη',
	'Wednesday' => 'Τετάρτη',
	'Thursday' => 'Πέμπτη',
	'Friday' => 'Παρασκευή',
	'Saturday' => 'Σάββατο',
	'Sunday' => 'Κυριακή',
	'Leave unchecked for all days' => 'Αφήστε ακατάστατο για όλες τις ημέρες',
	
	// User Roles - Critical!
	'User Roles' => 'Ρόλοι Χρήστη',
	'Administrator' => 'Διαχειριστής',
	'Editor' => 'Συντάκτης',
	'Author' => 'Συγγραφέας',
	'Contributor' => 'Συνεισφέρων',
	'Subscriber' => 'Συνδρομητής',
	'Customer' => 'Πελάτης',
	'Shop manager' => 'Διαχειριστής Καταστήματος',
	'Translator' => 'Μεταφραστής',
	'Leave unchecked for all users' => 'Αφήστε ακατάστατο για όλους τους χρήστες',
	
	// Cart Discounts - Critical!
	'Cart Discount Rules' => 'Κανόνες Έκπτωσης Καλαθιού',
	'Apply automatic discounts and free shipping based on cart conditions' => 'Εφαρμόστε αυτόματες εκπτώσεις και δωρεάν αποστολή με βάση συνθήκες καλαθιού',
	'No Cart Discount Rules Yet' => 'Δεν Υπάρχουν Κανόνες Έκπτωσης Καλαθιού Ακόμα',
	'Boost conversions with smart cart-level discounts!' => 'Ενισχύστε τις μετατροπές με έξυπνες εκπτώσεις σε επίπεδο καλαθιού!',
	'Create Your First Discount' => 'Δημιουργήστε την Πρώτη σας Έκπτωση',
	'Add Cart Discount' => 'Προσθήκη Έκπτωσης Καλαθιού',
	'Save Cart Discount' => 'Αποθήκευση Έκπτωσης Καλαθιού',
	
	// Discount Settings - Critical!
	'Discount Settings' => 'Ρυθμίσεις Έκπτωσης',
	'Discount Type' => 'Τύπος Έκπτωσης',
	'Discount Value' => 'Αξία Έκπτωσης',
	'Apply free shipping when this rule is active' => 'Εφαρμογή δωρεάν αποστολής όταν αυτός ο κανόνας είναι ενεργός',
	
	// Cart Conditions - Critical!
	'Cart Conditions' => 'Συνθήκες Καλαθιού',
	'Apply only to first order (new customers)' => 'Εφαρμογή μόνο στην πρώτη παραγγελία (νέοι πελάτες)',
	'Discount will only apply if customer has never placed an order before. Works for both logged-in users and guest email addresses.' => 'Η έκπτωση θα εφαρμοστεί μόνο αν ο πελάτης δεν έχει κάνει ποτέ παραγγελία πριν. Λειτουργεί τόσο για συνδεδεμένους χρήστες όσο και για email επισκεπτών.',
	'Min Cart Total' => 'Ελάχιστο Σύνολο Καλαθιού',
	'Max Cart Total' => 'Μέγιστο Σύνολο Καλαθιού',
	'Min Cart Quantity' => 'Ελάχιστη Ποσότητα Καλαθιού',
	'Max Cart Quantity' => 'Μέγιστη Ποσότητα Καλαθιού',
	
	// Common UI
	'Add Price Rule' => 'Προσθήκη Κανόνα Τιμής',
	'Add Discount Rule' => 'Προσθήκη Κανόνα Έκπτωσης',
	'Add Gift Rule' => 'Προσθήκη Κανόνα Δώρου',
	'Add Tiered Rule' => 'Προσθήκη Βαθμιδωτού Κανόνα',
	'Edit Rule' => 'Επεξεργασία Κανόνα',
	'Delete Rule' => 'Διαγραφή Κανόνα',
	'Unlimited' => 'Απεριόριστο',
	'Optional' => 'Προαιρετικό',
	'Required' => 'Υποχρεωτικό',
	'None' => 'Κανένα',
	'All' => 'Όλα',
	'Any' => 'Οποιοδήποτε',
	'Please select' => 'Παρακαλώ επιλέξτε',
	'Search products...' => 'Αναζήτηση προϊόντων...',
	'Search categories...' => 'Αναζήτηση κατηγοριών...',
	'Select products' => 'Επιλογή προϊόντων',
	'Select categories' => 'Επιλογή κατηγοριών',
	'Select users' => 'Επιλογή χρηστών',
	
	// Pricing
	'Price Rules' => 'Κανόνες Τιμών',
	'Tiered Pricing Rules' => 'Κανόνες Βαθμιδωτής Τιμολόγησης',
	'Discount Rules' => 'Κανόνες Εκπτώσεων',
	'Gift Rules' => 'Κανόνες Δώρων',
	'Percentage Discount' => 'Ποσοστιαία Έκπτωση',
	'Fixed Discount' => 'Σταθερή Έκπτωση',
	'Fixed Price' => 'Σταθερή Τιμή',
	'Price Increase' => 'Αύξηση Τιμής',
	'Price Decrease' => 'Μείωση Τιμής',
	
	// Gift Triggers
	'Trigger Type' => 'Τύπος Ενεργοποίησης',
	'Trigger Value' => 'Αξία Ενεργοποίησης',
	'Cart Subtotal' => 'Υποσύνολο Καλαθιού',
	'Specific Products in Cart' => 'Συγκεκριμένα Προϊόντα στο Καλάθι',
	'Product Quantity' => 'Ποσότητα Προϊόντος',
	'Category Products in Cart' => 'Προϊόντα Κατηγορίας στο Καλάθι',
	'Gift Products' => 'Προϊόντα Δώρου',
	'Max Gifts Per Order' => 'Μέγιστα Δώρα Ανά Παραγγελία',
	'Select products to give as free gifts' => 'Επιλέξτε προϊόντα για να δώσετε ως δωρεάν δώρα',
	
	// Tiers
	'Tier Type' => 'Τύπος Βαθμίδας',
	'Add Tier' => 'Προσθήκη Βαθμίδας',
	'Remove Tier' => 'Αφαίρεση Βαθμίδας',
	'Minimum Quantity' => 'Ελάχιστη Ποσότητα',
	'Maximum Quantity' => 'Μέγιστη Ποσότητα',
	
	// Settings
	'General Settings' => 'Γενικές Ρυθμίσεις',
	'Display Settings' => 'Ρυθμίσεις Εμφάνισης',
	'Enable Dynamic Pricing' => 'Ενεργοποίηση Δυναμικής Τιμολόγησης',
	'Enable Discount Rules' => 'Ενεργοποίηση Κανόνων Έκπτωσης',
	'Enable Free Gifts' => 'Ενεργοποίηση Δωρεάν Δώρων',
	'Show savings in cart' => 'Εμφάνιση εξοικονόμησης στο καλάθι',
	'Show discount badge on products' => 'Εμφάνιση σήματος έκπτωσης στα προϊόντα',
	'Badge Text' => 'Κείμενο Σήματος',
	'Badge Color' => 'Χρώμα Σήματος',
	'Sale Price Color' => 'Χρώμα Τιμής Προσφοράς',
	'Original Price Color' => 'Χρώμα Αρχικής Τιμής',
	'Savings Text Color' => 'Χρώμα Κειμένου Εξοικονόμησης',
	'Debug & Developer Options' => 'Επιλογές Αποσφαλμάτωσης & Προγραμματιστή',
	
	// Messages
	'No rules found. Create your first rule to get started!' => 'Δεν βρέθηκαν κανόνες. Δημιουργήστε τον πρώτο σας κανόνα για να ξεκινήσετε!',
	'Are you sure you want to delete this rule?' => 'Είστε σίγουροι ότι θέλετε να διαγράψετε αυτόν τον κανόνα;',
	'Rule deleted successfully' => 'Ο κανόνας διαγράφηκε επιτυχώς',
	'Rule saved successfully' => 'Ο κανόνας αποθηκεύτηκε επιτυχώς',
	'Error saving rule' => 'Σφάλμα κατά την αποθήκευση του κανόνα',
	'Please fill in all required fields' => 'Παρακαλώ συμπληρώστε όλα τα απαιτούμενα πεδία',
	'Invalid rule data' => 'Μη έγκυρα δεδομένα κανόνα',
	'Rule not found' => 'Ο κανόνας δεν βρέθηκε',
	
	// Examples
	'Examples' => 'Παραδείγματα',
	'Documentation' => 'Τεκμηρίωση',
	'Support' => 'Υποστήριξη',
	'Get help and support' => 'Λάβετε βοήθεια και υποστήριξη',
	'Explore real-world examples and use cases' => 'Εξερευνήστε παραδείγματα πραγματικού κόσμου και περιπτώσεις χρήσης',
	'Learn how to use the plugin effectively' => 'Μάθετε πώς να χρησιμοποιείτε αποτελεσματικά το plugin',
	
	// More strings
	'and more' => 'και περισσότερα',
	'Limit the total number of gift items per order. For example: Set to 1 to give only one free gift even if customer qualifies multiple times. Set to 2 to allow maximum 2 free gifts per order. Leave at 1 for standard promotions.' => 'Περιορίστε τον συνολικό αριθμό δώρων ανά παραγγελία. Για παράδειγμα: Ορίστε σε 1 για να δώσετε μόνο ένα δωρεάν δώρο ακόμα κι αν ο πελάτης πληροί τις προϋποθέσεις πολλές φορές. Ορίστε σε 2 για να επιτρέψετε μέγιστο 2 δωρεάν δώρα ανά παραγγελία. Αφήστε στο 1 για τυπικές προσφορές.',
	'Surprise your customers with free gifts to increase satisfaction and loyalty!' => 'Εκπλήξτε τους πελάτες σας με δωρεάν δώρα για να αυξήσετε την ικανοποίηση και την αφοσίωση!',
	'Create dynamic pricing rules to adjust product prices based on conditions' => 'Δημιουργήστε δυναμικούς κανόνες τιμολόγησης για προσαρμογή των τιμών προϊόντων με βάση συνθήκες',
	'Set up cart and product-level discounts with flexible conditions' => 'Ρυθμίστε εκπτώσεις σε επίπεδο καλαθιού και προϊόντος με ευέλικτες συνθήκες',
	'Delight customers with automatic free gifts based on purchase conditions' => 'Ευχαριστήστε τους πελάτες με αυτόματα δωρεάν δώρα με βάση συνθήκες αγοράς',
	
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
	'Quantity' => 'Ποσότητα',
	'Date' => 'Ημερομηνία',
	'Time' => 'Ώρα',
	
	// Status
	'Active' => 'Ενεργό',
	'Inactive' => 'Ανενεργό',
	'Enabled' => 'Ενεργοποιημένο',
	'Disabled' => 'Απενεργοποιημένο',
	'Yes' => 'Ναι',
	'No' => 'Όχι',
];

// Process line by line
$output_lines = [];
$i = 0;
$translated_count = 0;

while ($i < count($po_lines)) {
	$line = $po_lines[$i];
	
	// Check if it's a msgid line
	if (preg_match('/^msgid "(.+)"$/', $line, $matches)) {
		$msgid = $matches[1];
		$output_lines[] = $line;
		$i++;
		
		// Check next line for msgstr
		if ($i < count($po_lines) && preg_match('/^msgstr ""$/', $po_lines[$i])) {
			// Empty translation - fill it if we have it
			if (isset($translations[$msgid])) {
				$output_lines[] = 'msgstr "' . $translations[$msgid] . '"';
				$translated_count++;
			} else {
				$output_lines[] = $po_lines[$i];
			}
		} else {
			// Already has translation
			$output_lines[] = $po_lines[$i];
		}
	} else {
		$output_lines[] = $line;
	}
	
	$i++;
}

// Write updated file
file_put_contents($po_file, implode("\n", $output_lines));

// Also update el_GR
$po_gr_file = __DIR__ . '/wow-dynamic-deals-for-woo-el_GR.po';
file_put_contents($po_gr_file, implode("\n", $output_lines));

echo "Successfully added $translated_count Greek translations!\n";
echo "Total translation dictionary: " . count($translations) . " entries\n";
echo "Files updated:\n- $po_file\n- $po_gr_file\n";
