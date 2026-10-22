INSERT INTO user (firstname, middlename, lastname, date_of_birth, contact, email, password, role)
VALUES (
  'Admin', 'Clinic', 'User',
  '2000-01-01',
  '09171234567',
  'admin@test',
  '$2y$12$S5/zlfTEiiIkQxP52AOvwO/KI0zXagbPIvuF2Imd.thHPWhKUQ3j.',
  'admin'
);

INSERT INTO chatbot (knowledge) VALUES
('Our dental clinic is open from Monday to Saturday, 8:00 AM to 5:00 PM.'),
('You can book your appointment online or directly at the clinic.'),
('We offer general dentistry, orthodontics, pediatric dental care, and more.'),
('All our dentists are licensed and experienced in various dental specialties.'),
('Please arrive 10 minutes before your approved appointment.');

INSERT INTO service (name, description, duration, price, appointment_status_default) VALUES
('Dental Consultation', 'Basic dental check-up and oral health assessment.', 30, 500.00, 'approved'),
('Tooth Extraction', 'Removal of decayed or damaged tooth.', 45, 1500.00, 'approved'),
('Teeth Cleaning', 'Professional dental cleaning and plaque removal.', 40, 1000.00, 'approved'),
('Dental Filling', 'Restoration of tooth cavities with dental materials.', 60, 2000.00, 'approved'),
('Orthodontic Consultation', 'Initial assessment for braces and alignment treatment.', 30, 800.00, 'approved');

INSERT INTO api (content, user_id) VALUES
('ghPxAWItNihvb2oNqNpQzqqFXpvcHwER', 1);