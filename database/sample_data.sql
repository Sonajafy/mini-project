-- Users
INSERT INTO users (name, email, phone, password, role)
VALUES
('Rahul', 'rahul@gmail.com', '9876543210', 'rahul123', 'USER'),
('Admin', 'admin@gmail.com', '9876500000', 'admin123', 'ADMIN');

-- Routes
INSERT INTO routes (source, destination, distance_km)
VALUES
('Kochi', 'Trivandrum', 220),
('Kochi', 'Bangalore', 550);

-- Stops
INSERT INTO stops (route_id, stop_name, stop_order)
VALUES
(1, 'Aluva', 1),
(1, 'Kottayam', 2),
(1, 'Kollam', 3);

-- Buses
INSERT INTO buses (route_id, bus_number, bus_type, total_seats, departure_time, arrival_time)
VALUES
(1, 'KL01AB1234', 'AC', 40, '08:00:00', '14:00:00');

-- Seats
INSERT INTO seats (bus_id, seat_number, is_available)
VALUES
(1, 'A1', TRUE),
(1, 'A2', TRUE),
(1, 'A3', TRUE);

-- Bookings (UPDATED with travel_date)
INSERT INTO bookings (user_id, bus_id, seat_id, booking_date, travel_date, status)
VALUES
(1, 1, 1, '2026-01-01', '2026-01-10', 'BOOKED');

-- Payments
INSERT INTO payments (booking_id, amount, payment_method, payment_status)
VALUES
(1, 500.00, 'UPI', 'SUCCESS');
