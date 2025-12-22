<?php
/**
 * COMPLETE ALL REMAINING TRANSLATIONS - NO EXCUSES
 */

$po_file = __DIR__ . '/wow-dynamic-deals-for-woo-el.po';

// Read PO file
$lines = file($po_file, FILE_IGNORE_NEW_LINES);

// MASSIVE comprehensive translation dictionary
$translations = [
	// Core basics
	'Monday' => 'Δευτέρα', 'Tuesday' => 'Τρίτη', 'Wednesday' => 'Τετάρτη', 'Thursday' => 'Πέμπτη',
	'Friday' => 'Παρασκευή', 'Saturday' => 'Σάββατο', 'Sunday' => 'Κυριακή',
	'Administrator' => 'Διαχειριστής', 'Editor' => 'Συντάκτης', 'Author' => 'Συγγραφέας',
	'Contributor' => 'Συνεισφέρων', 'Subscriber' => 'Συνδρομητής', 'Shop manager' => 'Διαχειριστής Καταστήματος',
	'Translator' => 'Μεταφραστής', 'User Roles' => 'Ρόλοι Χρήστη',
	
	// Operators and symbols
	' AND' => ' KAI', ' OR' => ' Ή',
	
	// Percentage and discount formats
	'%1$s (%2$s discount)' => '%1$s (έκπτωση %2$s)',
	'%1$s (%2$s%%)' => '%1$s (%2$s%%)',
	'*Discount applies to entire cart quantity' => '*Η έκπτωση εφαρμόζεται σε ολόκληρη την ποσότητα του καλαθιού',
	
	// Actions and conditions
	'Add Additional Trigger Condition' => 'Προσθήκη Επιπλέον Συνθήκης Ενεργοποίησης',
	'Add multiple conditions and choose AND (all must match) or OR (any can match)' => 'Προσθέστε πολλαπλές συνθήκες και επιλέξτε ΚΑΙ (όλες πρέπει να ταιριάζουν) ή Ή (οποιαδήποτε)',
	'Allow Multiple Discounts to Stack' => 'Επιτρέψτε Στοίβαση Πολλαπλών Εκπτώσεων',
	'Always Active' => 'Πάντα Ενεργό',
	'An error occurred' => 'Παρουσιάστηκε σφάλμα',
	'Applied discounts:' => 'Εφαρμοσμένες εκπτώσεις:',
	
	// Examples and scenarios
	'BOGO - Buy One Get One 50% Off' => 'BOGO - Αγοράστε 1 Πάρτε 1 στο 50%',
	'Bulk Discount - Buy More, Save More' => 'Έκπτωση Όγκου - Αγοράστε Περισσότερα, Εξοικονομήστε Περισσότερα',
	'Buy 2 Get 1 Free' => 'Αγοράστε 2 Πάρτε 1 Δωρεάν',
	'Buy 3 Books, Get 50% Off' => 'Αγοράστε 3 Βιβλία, Πάρτε 50% Έκπτωση',
	'Buy Laptop, Get Free Mouse' => 'Αγοράστε Laptop, Πάρτε Δωρεάν Ποντίκι',
	'Christmas Sale - December Special' => 'Χριστουγεννιάτικη Προσφορά - Ειδική Δεκεμβρίου',
	'Clearance - Fixed Price $9.99' => 'Εκκαθάριση - Σταθερή Τιμή $9.99',
	'Combo Deal - Phone + Case = 20% Off' => 'Προσφορά Combo - Τηλέφωνο + Θήκη = 20% Έκπτωση',
	'Customer Loyalty Tiers' => 'Βαθμίδες Πιστότητας Πελατών',
	'Daily Flash Sale' => 'Καθημερινή Flash Προσφορά',
	'Early Bird Discount' => 'Έκπτωση Πρωινής Αγοράς',
	'First Order Discount' => 'Έκπτωση Πρώτης Παραγγελίας',
	'Free Gift with Purchase' => 'Δωρεάν Δώρο με Αγορά',
	'Free Sample with Every Order' => 'Δωρεάν Δείγμα με Κάθε Παραγγελία',
	'Happy Hour - Time-Based Pricing' => 'Happy Hour - Τιμολόγηση με Βάση την Ώρα',
	'Holiday Bundle' => 'Πακέτο Αργιών',
	'Loyalty Tiers - Spend More, Save More' => 'Βαθμίδες Πιστότητας - Ξοδέψτε Περισσότερα, Εξοικονομήστε Περισσότερα',
	'Member Exclusive Pricing' => 'Αποκλειστική Τιμολόγηση Μελών',
	'Minimum Purchase Discount' => 'Έκπτωση Ελάχιστης Αγοράς',
	'New Customer Welcome Gift' => 'Δώρο Καλωσορίσματος Νέου Πελάτη',
	'Seasonal Category Discount' => 'Εποχιακή Έκπτωση Κατηγορίας',
	'Spend $100 Get Free Shipping' => 'Ξοδέψτε $100 Πάρτε Δωρεάν Αποστολή',
	'Tiered Quantity Pricing' => 'Βαθμιδωτή Τιμολόγηση Ποσότητας',
	'VIP Customer Discount' => 'Έκπτωση VIP Πελάτη',
	'Weekend Special' => 'Ειδική Προσφορά Σαββατοκύριακου',
	
	// Calculation and modes
	'Calculate tiers for each cart line separately' => 'Υπολογισμός βαθμίδων για κάθε γραμμή καλαθιού ξεχωριστά',
	'Calculation Mode' => 'Λειτουργία Υπολογισμού',
	'Combined Quantity' => 'Συνδυασμένη Ποσότητα',
	'Individual Quantity' => 'Ατομική Ποσότητα',
	
	// UI Messages
	'Click any example below to see detailed setup instructions for common promotional scenarios' => 'Κάντε κλικ σε οποιοδήποτε παράδειγμα για λεπτομερείς οδηγίες ρύθμισης',
	'Copy' => 'Αντιγραφή',
	'Create Your First Rule' => 'Δημιουργήστε τον Πρώτο σας Κανόνα',
	'Create Your First Gift Rule' => 'Δημιουργήστε τον Πρώτο σας Κανόνα Δώρου',
	'Cart & Checkout Display' => 'Εμφάνιση Καλαθιού & Ολοκλήρωσης',
	'Cart Discount' => 'Έκπτωση Καλαθιού',
	'Cart Discounts' => 'Εκπτώσεις Καλαθιού',
	'Data successfully copied to clipboard!' => 'Τα δεδομένα αντιγράφηκαν επιτυχώς!',
	'Delete' => 'Διαγραφή',
	'Description' => 'Περιγραφή',
	'Details' => 'Λεπτομέρειες',
	'Duplicate' => 'Αντιγραφή',
	'Edit' => 'Επεξεργασία',
	'Enable this rule' => 'Ενεργοποίηση κανόνα',
	'Error' => 'Σφάλμα',
	'Export' => 'Εξαγωγή',
	'Failed to copy data' => 'Αποτυχία αντιγραφής',
	'Filter' => 'Φίλτρο',
	'From' => 'Από',
	'Gift' => 'Δώρο',
	'Gift Product' => 'Προϊόν Δώρου',
	'Gifts' => 'Δώρα',
	'Help' => 'Βοήθεια',
	'Hide' => 'Απόκρυψη',
	'Home' => 'Αρχική',
	'Import' => 'Εισαγωγή',
	'Info' => 'Πληροφορίες',
	'Last Modified' => 'Τελευταία Τροποποίηση',
	'Learn More' => 'Μάθετε Περισσότερα',
	'Loading...' => 'Φόρτωση...',
	'Name' => 'Όνομα',
	'New Rule' => 'Νέος Κανόνας',
	'Next' => 'Επόμενο',
	'No data available' => 'Δεν υπάρχουν δεδομένα',
	'No items found' => 'Δεν βρέθηκαν αντικείμενα',
	'Notes' => 'Σημειώσεις',
	'Off' => 'Ανενεργό',
	'On' => 'Ενεργό',
	'Options' => 'Επιλογές',
	'Order' => 'Παραγγελία',
	'Overview' => 'Επισκόπηση',
	'Preview' => 'Προεπισκόπηση',
	'Previous' => 'Προηγούμενο',
	'Price' => 'Τιμή',
	'Pricing' => 'Τιμολόγηση',
	'Product' => 'Προϊόν',
	'Promo Code' => 'Κωδικός Προσφοράς',
	'Quick Edit' => 'Γρήγορη Επεξεργασία',
	'Refresh' => 'Ανανέωση',
	'Reset' => 'Επαναφορά',
	'Rules' => 'Κανόνες',
	'Save' => 'Αποθήκευση',
	'Save Changes' => 'Αποθήκευση Αλλαγών',
	'Search' => 'Αναζήτηση',
	'Search...' => 'Αναζήτηση...',
	'Select' => 'Επιλογή',
	'Select All' => 'Επιλογή Όλων',
	'Settings saved successfully' => 'Οι ρυθμίσεις αποθηκεύτηκαν επιτυχώς',
	'Show' => 'Εμφάνιση',
	'Show More' => 'Περισσότερα',
	'Sort' => 'Ταξινόμηση',
	'Start' => 'Έναρξη',
	'Stop' => 'Διακοπή',
	'Submit' => 'Υποβολή',
	'Success' => 'Επιτυχία',
	'Summary' => 'Σύνοψη',
	'To' => 'Έως',
	'Toggle' => 'Εναλλαγή',
	'Total' => 'Σύνολο',
	'Update' => 'Ενημέρωση',
	'Updated' => 'Ενημερώθηκε',
	'Upgrade' => 'Αναβάθμιση',
	'View' => 'Προβολή',
	'View All' => 'Προβολή Όλων',
	'Warning' => 'Προειδοποίηση',
	
	// Tier related
	'Tier' => 'Βαθμίδα',
	'Tiers' => 'Βαθμίδες',
	'Min Qty' => 'Ελάχ. Ποσ.',
	'Max Qty' => 'Μέγ. Ποσ.',
	'Adjustment' => 'Προσαρμογή',
	
	// Form elements
	'Choose products' => 'Επιλέξτε προϊόντα',
	'Choose categories' => 'Επιλέξτε κατηγορίες',
	'Enter value' => 'Εισάγετε αξία',
	'Required field' => 'Υποχρεωτικό',
	'Optional field' => 'Προαιρετικό',
	
	// Validation
	'Please enter a valid number' => 'Εισάγετε έγκυρο αριθμό',
	'This field is required' => 'Υποχρεωτικό πεδίο',
	'Value must be greater than zero' => 'Η αξία πρέπει να είναι > 0',
	
	// Success/Error
	'Rule created successfully' => 'Ο κανόνας δημιουργήθηκε',
	'Rule updated successfully' => 'Ο κανόνας ενημερώθηκε',
	'Failed to save rule' => 'Αποτυχία αποθήκευσης',
	'Failed to delete rule' => 'Αποτυχία διαγραφής',
	'Changes saved' => 'Αποθηκεύτηκαν',
	'No changes made' => 'Καμία αλλαγή',
	
	// Leave unchecked
	'Leave unchecked for all days' => 'Αφήστε κενό για όλες τις ημέρες',
	'Leave unchecked for all users' => 'Αφήστε κενό για όλους τους χρήστες',
];

// Process line by line
$output = [];
$i = 0;
$translated = 0;
$skipped_settings = 0;
$file_ref = '';

while ($i < count($lines)) {
	$line = $lines[$i];
	
	// Track file reference
	if (preg_match('/^#: (.+)$/', $line)) {
		$file_ref = $line;
		$output[] = $line;
		$i++;
		continue;
	}
	
	// Check for msgid
	if (preg_match('/^msgid "(.+)"$/', $line, $m)) {
		$msgid = $m[1];
		$output[] = $line;
		$i++;
		
		// Check next line
		if ($i < count($lines) && $lines[$i] === 'msgstr ""') {
			// Empty msgstr
			if (strpos($file_ref, 'settings.php') !== false) {
				// Skip settings
				$output[] = $lines[$i];
				$skipped_settings++;
			} elseif (isset($translations[$msgid])) {
				// Translate it
				$output[] = 'msgstr "' . $translations[$msgid] . '"';
				$translated++;
			} else {
				$output[] = $lines[$i];
			}
			$i++;
			continue;
		}
	}
	
	$output[] = $line;
	$i++;
}

// Write
file_put_contents($po_file, implode("\n", $output));
file_put_contents(__DIR__ . '/wow-dynamic-deals-for-woo-el_GR.po', implode("\n", $output));

echo "✓ Translated: $translated strings\n";
echo "✗ Skipped settings: $skipped_settings\n";
echo "📖 Dictionary: " . count($translations) . " entries\n";
