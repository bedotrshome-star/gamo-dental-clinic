USE gamo_dental;

-- Add columns for parallel workflow if they don't exist
-- 0 = Not Sent, 1 = Sent/Active, 2 = Done
ALTER TABLE appointments ADD COLUMN sent_to_nurse TINYINT DEFAULT 0;
ALTER TABLE appointments ADD COLUMN sent_to_pharmacy TINYINT DEFAULT 0;
ALTER TABLE appointments ADD COLUMN sent_to_cashier TINYINT DEFAULT 0;
