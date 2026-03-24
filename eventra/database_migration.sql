-- Eventra Cancellation/Refund Migration
USE eventra;

-- Add status fields to bookings
ALTER TABLE bookings 
ADD COLUMN booking_status ENUM('confirmed', 'cancelled') DEFAULT 'confirmed',
ADD COLUMN payment_status ENUM('paid', 'refunded') DEFAULT 'paid',
ADD COLUMN refund_status ENUM('none', 'pending', 'completed', 'rejected') DEFAULT 'none',
ADD COLUMN cancelled_at TIMESTAMP NULL;

-- Add refund_amount column
ALTER TABLE bookings ADD COLUMN refund_amount DECIMAL(10,2) DEFAULT 0.00;

-- Update existing bookings
UPDATE bookings SET booking_status = 'confirmed', payment_status = 'paid', refund_status = 'none';

-- Create indexes
CREATE INDEX idx_booking_status ON bookings(booking_status);
CREATE INDEX idx_payment_status ON bookings(payment_status);
CREATE INDEX idx_refund_status ON bookings(refund_status);

