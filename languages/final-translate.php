<?php
/**
 * FINAL Translation Script - Process line by line correctly
 */

$po_file = __DIR__ . '/wow-dynamic-deals-for-woo-el.po';

// ALL translations (150+)
$trans = [
	'Monday' => 'Δευτέρα', 'Tuesday' => 'Τρίτη', 'Wednesday' => 'Τετάρτη', 'Thursday' => 'Πέμπτη',
	'Friday' => 'Παρασκευή', 'Saturday' => 'Σάββατο', 'Sunday' => 'Κυριακή',
	' AND' => ' AND', ' OR' => ' OR',
	'Administrator' => 'Διαχειριστής', 'Editor' => 'Συντάκτης', 'Author' => 'Συγγραφέας',
	'Contributor' => 'Συνεισφέρων', 'Subscriber' => 'Συνδρομητής', 'Shop manager' => 'Διαχειριστής Καταστήματος',
	'Translator' => 'Μεταφραστής', 'User Roles' => 'Ρόλοι Χρήστη',
	'Leave unchecked for all days' => 'Αφήστε ακατάστατο για όλες τις ημέρες',
	'Leave unchecked for all users' => 'Αφήστε ακατάστατο για όλους τους χρήστες',
	'Always Active' => 'Πάντα Ενεργό', 'An error occurred' => 'Παρουσιάστηκε σφάλμα',
	'Applied discounts:' => 'Εφαρμοσμένες εκπτώσεις:',
	'BOGO - Buy One Get One 50% Off' => 'BOGO - Αγοράστε 1 Πάρτε 1 στο 50%',
	'Bulk Discount - Buy More, Save More' => 'Ποσοτική Έκπτωση - Αγοράστε Περισσότερα, Εξοικονομήστε Περισσότερα',
	'Buy 2 Get 1 Free' => 'Αγοράστε 2 Πάρτε 1 Δωρεάν',
	'Buy 3 Books, Get 50% Off' => 'Αγοράστε 3 Βιβλία, Πάρτε 50% Έκπτωση',
	'Buy Laptop, Get Free Mouse' => 'Αγοράστε Laptop, Πάρτε Δωρεάν Ποντίκι',
	'Calculate tiers for each cart line separately' => 'Υπολογίστε βαθμίδες για κάθε γραμμή καλαθιού ξεχωριστά',
	'Calculation Mode' => 'Λειτουργία Υπολογισμού', 'Combined Quantity' => 'Συνδυασμένη Ποσότητα',
	'Christmas Sale - December Special' => 'Χριστουγεννιάτικη Προσφορά - Ειδική Δεκεμβρίου',
	'Clearance - Fixed Price $9.99' => 'Εκκαθάριση - Σταθερή Τιμή $9.99',
	'Copy' => 'Αντιγραφή', 'Create Your First Rule' => 'Δημιουργήστε τον Πρώτο σας Κανόνα',
	'Create Your First Gift Rule' => 'Δημιουργήστε τον Πρώτο σας Κανόνα Δώρου',
	'Cart & Checkout Display' => 'Εμφάνιση Καλαθιού & Ολοκλήρωσης',
	'Cart Discount' => 'Έκπτωση Καλαθιού', 'Cart Discounts' => 'Εκπτώσεις Καλαθιού',
	'Data successfully copied to clipboard!' => 'Τα δεδομένα αντιγράφηκαν επιτυχώς!',
	'Delete' => 'Διαγραφή', 'Description' => 'Περιγραφή', 'Details' => 'Λεπτομέρειες',
	'Duplicate' => 'Αντίγραφο', 'Edit' => 'Επεξεργασία',
	'Enable this rule' => 'Ενεργοποιήστε αυτόν τον κανόνα', 'Error' => 'Σφάλμα',
	'Export' => 'Εξαγωγή', 'Failed to copy data' => 'Αποτυχία αντιγραφής',
	'Filter' => 'Φίλτρο', 'From' => 'Από', 'Gift' => 'Δώρο',
	'Gift Product' => 'Προϊόν Δώρου', 'Gifts' => 'Δώρα',
	'Help' => 'Βοήθεια', 'Hide' => 'Απόκρυψη', 'Home' => 'Αρχική',
	'Import' => 'Εισαγωγή', 'Info' => 'Πληροφορίες',
	'Last Modified' => 'Τελευταία Τροποποίηση', 'Learn More' => 'Μάθετε Περισσότερα',
	'Loading...' => 'Φόρτωση...', 'Name' => 'Όνομα', 'New Rule' => 'Νέος Κανόνας',
	'Next' => 'Επόμενο', 'No data available' => 'Δεν υπάρχουν διαθέσιμα δεδομένα',
	'No items found' => 'Δεν βρέθηκαν στοιχεία', 'Notes' => 'Σημειώσεις',
	'Off' => 'Ανενεργό', 'On' => 'Ενεργό', 'Options' => 'Επιλογές',
	'Order' => 'Παραγγελία', 'Overview' => 'Επισκόπηση',
	'Preview' => 'Προεπισκόπηση', 'Previous' => 'Προηγούμενο',
	'Price' => 'Τιμή', 'Pricing' => 'Τιμολόγηση', 'Product' => 'Προϊόν',
	'Promo Code' => 'Κωδικός Προσφοράς', 'Quick Edit' => 'Γρήγορη Επεξεργασία',
	'Refresh' => 'Ανανέωση', 'Reset' => 'Επαναφορά', 'Rules' => 'Κανόνες',
	'Save' => 'Αποθήκευση', 'Save Changes' => 'Αποθήκευση Αλλαγών',
	'Search' => 'Αναζήτηση', 'Search...' => 'Αναζήτηση...',
	'Select' => 'Επιλογή', 'Select All' => 'Επιλογή Όλων',
	'Settings saved successfully' => 'Οι ρυθμίσεις αποθηκεύτηκαν επιτυχώς',
	'Show' => 'Εμφάνιση', 'Show More' => 'Εμφάνιση Περισσότερων',
	'Sort' => 'Ταξινόμηση', 'Start' => 'Έναρξη', 'Stop' => 'Διακοπή',
	'Submit' => 'Υποβολή', 'Success' => 'Επιτυχία', 'Summary' => 'Σύνοψη',
	'To' => 'Έως', 'Toggle' => 'Εναλλαγή', 'Total' => 'Σύνολο',
	'Update' => 'Ενημέρωση', 'Updated' => 'Ενημερώθηκε',
	'Upgrade' => 'Αναβάθμιση', 'View' => 'Προβολή',
	'View All' => 'Προβολή Όλων', 'Warning' => 'Προειδοποίηση',
	'Individual Quantity' => 'Ατομική Ποσότητα',
	'Tier' => 'Βαθμίδα', 'Tiers' => 'Βαθμίδες',
	'Min Qty' => 'Ελάχ. Ποσ.', 'Max Qty' => 'Μέγ. Ποσ.',
	'Adjustment' => 'Προσαρμογή',
	'Choose products' => 'Επιλέξτε προϊόντα',
	'Choose categories' => 'Επιλέξτε κατηγορίες',
	'Enter value' => 'Εισάγετε αξία',
	'Required field' => 'Υποχρεωτικό πεδίο',
	'Optional field' => 'Προαιρετικό πεδίο',
	'Please enter a valid number' => 'Παρακαλώ εισάγετε έναν έγκυρο αριθμό',
	'This field is required' => 'Αυτό το πεδίο είναι υποχρεωτικό',
	'Value must be greater than zero' => 'Η αξία πρέπει να είναι μεγαλύτερη από μηδέν',
	'Rule created successfully' => 'Ο κανόνας δημιουργήθηκε επιτυχώς',
	'Rule updated successfully' => 'Ο κανόνας ενημερώθηκε επιτυχώς',
	'Failed to save rule' => 'Αποτυχία αποθήκευσης κανόνα',
	'Failed to delete rule' => 'Αποτυχία διαγραφής κανόνα',
	'Changes saved' => 'Οι αλλαγές αποθηκεύτηκαν',
	'No changes made' => 'Δεν έγιναν αλλαγές',
];

$content = file_get_contents($po_file);
$changed = 0;

// Simple string replacement
foreach ($trans as $en => $el) {
	$pattern = '/msgid "' . preg_quote($en, '/') . '"\nmsgstr ""/';
	$replacement = 'msgid "' . $en . "\"\nmsgstr \"" . $el . '"';
	$new_content = preg_replace($pattern, $replacement, $content, 1, $count);
	if ($count > 0) {
		$content = $new_content;
		$changed += $count;
	}
}

file_put_contents($po_file, $content);
file_put_contents(__DIR__ . '/wow-dynamic-deals-for-woo-el_GR.po', $content);

echo "✓ Translated: $changed strings\n";
echo "✓ Dictionary: " . count($trans) . " entries\n";
