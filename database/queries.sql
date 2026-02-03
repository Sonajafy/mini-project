-- 1. Select all buses with route info
SELECT b.bus_number, r.source, r.destination, b.bus_type
FROM buses b
JOIN routes r ON b.route_id = r.route_id;

-- 2. Update seat availability (example)
UPDATE seats
SET is_available = FALSE
WHERE seat_id = 2;

-- 3. Insert a new booking
INSERT INTO bookings (user_id, bus_id, seat_id, travel_date, booking_date, status)
VALUES (1, 1, 2, '2026-01-10', '2026-01-02', 'BOOKED');

-- 4. Join booking history with user and payment
SELECT u.name, b.booking_id, bus.bus_number, b.travel_date, p.amount, b.status
FROM bookings b
JOIN users u ON b.user_id = u.user_id
JOIN buses bus ON b.bus_id = bus.bus_id
JOIN payments p ON b.booking_id = p.booking_id;

-- 5. Delete a route example
DELETE FROM routes WHERE route_id = 2;
