<?php
/**
 * Add ALL missing Greek translations
 */

$po_file = __DIR__ . '/wow-dynamic-deals-for-woo-el.po';
$po_content = file_get_contents($po_file);

// COMPREHENSIVE Greek translations dictionary
$translations = [
	// Days of Week
	'Days of Week' => 'Ημέρες Εβδομάδας',
	'Monday' => 'Δευτέρα',
	'Tuesday' => 'Τρίτη',
	'Wednesday' => 'Τετάρτη',
	'Thursday' => 'Πέμπτη',
	'Friday' => 'Παρασκευή',
	'Saturday' => 'Σάββατο',
	'Sunday' => 'Κυριακή',
	'Leave unchecked for all days' => 'Αφήστε ακατάσταπτο για όλες τις ημέρες',
	
	// User Restrictions
	'User Restrictions' => 'Περιορισμοί Χρήστη',
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
	'Specific Users' => 'Συγκεκριμένοι Χρήστες',
	
	// Additional Options
	'Additional Options' => 'Πρόσθετες Επιλογές',
	'Stop processing further rules after this one' => 'Διακοπή επεξεργασίας περαιτέρω κανόνων μετά από αυτόν',
	
	// Cart Discounts
	'Cart Discount Rules' => 'Κανόνες Έκπτωσης Καλαθιού',
	'Apply automatic discounts and free shipping based on cart conditions' => 'Εφαρμόστε αυτόματες εκπτώσεις και δωρεάν αποστολή με βάση συνθήκες καλαθιού',
	'Add Cart Discount' => 'Προσθήκη Έκπτωσης Καλαθιού',
	'No Cart Discount Rules Yet' => 'Δεν Υπάρχουν Κανόνες Έκπτωσης Καλαθιού Ακόμα',
	'Boost conversions with smart cart-level discounts!' => 'Ενισχύστε τις μετατροπές με έξυπνες εκπτώσεις σε επίπεδο καλαθιού!',
	'Create Your First Discount' => 'Δημιουργήστε την Πρώτη σας Έκπτωση',
	
	// Discount Settings
	'Discount Settings' => 'Ρυθμίσεις Έκπτωσης',
	'Discount Type' => 'Τύπος Έκπτωσης',
	'Discount Value' => 'Αξία Έκπτωσης',
	'Apply free shipping when this rule is active' => 'Εφαρμογή δωρεάν αποστολής όταν αυτός ο κανόνας είναι ενεργός',
	
	// Cart Conditions
	'Cart Conditions' => 'Συνθήκες Καλαθιού',
	'Apply only to first order (new customers)' => 'Εφαρμογή μόνο στην πρώτη παραγγελία (νέοι πελάτες)',
	'Discount will only apply if customer has never placed an order before. Works for both logged-in users and guest email addresses.' => 'Η έκπτωση θα εφαρμοστεί μόνο αν ο πελάτης δεν έχει κάνει ποτέ παραγγελία πριν. Λειτουργεί τόσο για συνδεδεμένους χρήστες όσο και για email επισκεπτών.',
	'Min Cart Total' => 'Ελάχιστο Σύνολο Καλαθιού',
	'Max Cart Total' => 'Μέγιστο Σύνολο Καλαθιού',
	'Unlimited' => 'Απεριόριστο',
	'Min Cart Quantity' => 'Ελάχιστη Ποσότητα Καλαθιού',
	'Max Cart Quantity' => 'Μέγιστη Ποσότητα Καλαθιού',
	'Save Cart Discount' => 'Αποθήκευση Έκπτωσης Καλαθιού',
	
	// Modals and buttons
	'Add Price Rule' => 'Προσθήκη Κανόνα Τιμής',
	'Add Discount Rule' => 'Προσθήκη Κανόνα Έκπτωσης',
	'Add Gift Rule' => 'Προσθήκη Κανόνα Δώρου',
	'Edit Rule' => 'Επεξεργασία Κανόνα',
	'Delete Rule' => 'Διαγραφή Κανόνα',
	
	// Pricing types
	'Percentage Discount' => 'Ποσοστιαία Έκπτωση',
	'Fixed Discount' => 'Σταθερή Έκπτωση',
	'Fixed Price' => 'Σταθερή Τιμή',
	'Price Increase' => 'Αύξηση Τιμής',
	
	// Status
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
	
	// Tiered Pricing
	'Tiered Pricing Rules' => 'Κανόνες Βαθμιδωτής Τιμολόγησης',
	'Add Tiered Rule' => 'Προσθήκη Βαθμιδωτού Κανόνα',
	'Tier Type' => 'Τύπος Βαθμίδας',
	'Quantity' => 'Ποσότητα',
	'Add Tier' => 'Προσθήκη Βαθμίδας',
	'Remove Tier' => 'Αφαίρεση Βαθμίδας',
	
	// Gift Rules
	'Gift Rules' => 'Κανόνες Δώρων',
	'Trigger Type' => 'Τύπος Ενεργοποίησης',
	'Trigger Value' => 'Αξία Ενεργοποίησης',
	'Gift Products' => 'Προϊόντα Δώρου',
	'Max Gifts Per Order' => 'Μέγιστα Δώρα Ανά Παραγγελία',
	'Select products to give as free gifts' => 'Επιλέξτε προϊόντα για να δώσετε ως δωρεάν δώρα',
	
	// Trigger types
	'Cart Subtotal' => 'Υποσύνολο Καλαθιού',
	'Specific Products in Cart' => 'Συγκεκριμένα Προϊόντα στο Καλάθι',
	'Product Quantity' => 'Ποσότητα Προϊόντος',
	'Category Products in Cart' => 'Προϊόντα Κατηγορίας στο Καλάθι',
	
	// Messages
	'No rules found. Create your first rule to get started!' => 'Δεν βρέθηκαν κανόνες. Δημιουργήστε τον πρώτο σας κανόνα για να ξεκινήσετε!',
	'Are you sure you want to delete this rule?' => 'Είστε σίγουροι ότι θέλετε να διαγράψετε αυτόν τον κανόνα;',
	'Rule deleted successfully' => 'Ο κανόνας διαγράφηκε επιτυχώς',
	'Rule saved successfully' => 'Ο κανόνας αποθηκεύτηκε επιτυχώς',
	'Error saving rule' => 'Σφάλμα κατά την αποθήκευση του κανόνα',
	'Please fill in all required fields' => 'Παρακαλώ συμπληρώστε όλα τα απαιτούμενα πεδία',
	
	// Settings
	'General Settings' => 'Γενικές Ρυθμίσεις',
	'Enable Dynamic Pricing' => 'Ενεργοποίηση Δυναμικής Τιμολόγησης',
	'Enable Discount Rules' => 'Ενεργοποίηση Κανόνων Έκπτωσης',
	'Enable Free Gifts' => 'Ενεργοποίηση Δωρεάν Δώρων',
	'Display Settings' => 'Ρυθμίσεις Εμφάνισης',
	'Show savings in cart' => 'Εμφάνιση εξοικονόμησης στο καλάθι',
	'Show discount badge on products' => 'Εμφάνιση σήματος έκπτωσης στα προϊόντα',
	'Badge Text' => 'Κείμενο Σήματος',
	'Badge Color' => 'Χρώμα Σήματος',
	'Sale Price Color' => 'Χρώμα Τιμής Προσφοράς',
	'Original Price Color' => 'Χρώμα Αρχικής Τιμής',
	'Savings Text Color' => 'Χρώμα Κειμένου Εξοικονόμησης',
];

// Update PO content with translations
foreach ($translations as $english => $greek) {
	// Find msgid and replace empty msgstr
	$pattern = '/msgid "' . preg_quote($english, '/') . '"\nmsgstr ""/';
	$replacement = 'msgid "' . $english . '"' . "\n" . 'msgstr "' . $greek . '"';
	$po_content = preg_replace($pattern, $replacement, $po_content);
}

// Write updated PO file
file_put_contents($po_file, $po_content);

// Also update el_GR version
$po_gr_file = __DIR__ . '/wow-dynamic-deals-for-woo-el_GR.po';
file_put_contents($po_gr_file, $po_content);

echo "Added " . count($translations) . " Greek translations!\n";
echo "Updated files:\n";
echo "- $po_file\n";
echo "- $po_gr_file\n";
