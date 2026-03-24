BEGIN;
INSERT INTO packaging (id, width, height, length, max_weight, volume) VALUES (1, 3.0, 2.5, 1.0, 20, 7.5);
INSERT INTO packaging (id, width, height, length, max_weight, volume) VALUES (2, 4.0, 4.0, 4.0, 20, 64.0);
INSERT INTO packaging (id, width, height, length, max_weight, volume) VALUES (3, 10.0, 2.0, 2.0, 20, 40.0);
INSERT INTO packaging (id, width, height, length, max_weight, volume) VALUES (4, 7.5, 6.0, 5.5, 30, 247.5);
INSERT INTO packaging (id, width, height, length, max_weight, volume) VALUES (5, 9.0, 9.0, 9.0, 30, 729.0);
COMMIT;
