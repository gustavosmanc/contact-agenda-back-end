<?php
require_once "../DatabaseConstructor.php";

try {
    $db = new DatabaseConstructor("../contacts.db");
    echo "Database connection succesfully opened!";
} catch (Exception $e) {
    echo $db->lastErrorMsg() . "\n" . $e;
}

$sql = <<<EOF
    CREATE TABLE contact
    (
        contact_id INTEGER PRIMARY KEY AUTOINCREMENT,
        name TEXT NOT NULL,
        surname TEXT,
        email TEXT UNIQUE
    );

    CREATE TABLE address (
        contact_id INTEGER PRIMARY KEY,
        state_initials TEXT NOT NULL,
        city TEXT NOT NULL,
        neighborhood TEXT,
        zip_code TEXT,
        public_area TEXT,
        number TEXT,

        FOREIGN KEY(contact_id) REFERENCES contact(contact_id)
    );

    CREATE TABLE phone (
        phone_id INTEGER PRIMARY KEY AUTOINCREMENT,
        contact_id INTEGER NOT NULL,
        phone_number TEXT,
        main_phone INTEGER,

        FOREIGN KEY(contact_id) REFERENCES contact(contact_id)
    );
EOF;

$result = $db->exec($sql);
if (!$result) {
    echo $db->lastErrorMsg();
} else {
    echo "<br>Tables created successfully!";
}
$db->close();
