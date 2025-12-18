USE gamo_dental;

ALTER TABLE appointments ADD COLUMN nurse_note TEXT DEFAULT NULL;
ALTER TABLE appointments ADD COLUMN pharmacy_note TEXT DEFAULT NULL;
ALTER TABLE appointments ADD COLUMN cashier_note TEXT DEFAULT NULL;
