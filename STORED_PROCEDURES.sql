-- Procedure for user inserts and correctly allocating user_id to admin_id
DELIMITER //

CREATE PROCEDURE InsertUserAdmin(
    IN username_param VARCHAR(32),
    IN password_param VARCHAR(32),
    IN name_param VARCHAR(32),
    IN lastname_param VARCHAR(32)
)
BEGIN
    -- Insert into users table
    INSERT INTO users (username, password, name, lastname, role) VALUES (username_param, password_param, name_param, lastname_param, 'admin');

    -- Get the last inserted user_id, admin_id
    SET @last_user_id = LAST_INSERT_ID();

    -- Insert into admin table
    INSERT INTO admin (admin_id) VALUES (@last_user_id);
END //

DELIMITER ;

-- CALL InsertUserAdmin('admin_user', 'password123', 'admin_name', 'admin_lastname');


-- Procedure for user inserts and correctly allocating user_id to resc_id
DELIMITER //

CREATE PROCEDURE InsertUserRescuer(
    IN username_param VARCHAR(32),
    IN password_param VARCHAR(32),
    IN name_param VARCHAR(32),
    IN lastname_param VARCHAR(32),
    IN r_cords_param POINT,
    IN car_name_param VARCHAR(32)
)
BEGIN
    -- Insert into users table
    INSERT INTO users (username, password, name, lastname, role) VALUES (username_param, password_param, name_param, lastname_param, 'rescuer');

    -- Get the last inserted user_id
    SET @last_user_id = LAST_INSERT_ID();

    -- Insert into rescuer table
    INSERT INTO rescuer (resc_id, r_cords, car_name) VALUES (@last_user_id, r_cords_param, car_name_param);
END //

DELIMITER ;

-- CALL InsertUserRescuer('rescuer_user', 'password123', 'rescuer_name', 'rescuer_lastname', POINT(38.246229, 21.735412), 'Oxhma_name');


-- Procedure for user inserts and correctly allocating user_id to citizen_id
DELIMITER //

CREATE PROCEDURE InsertUserCitizen(
    IN username_param VARCHAR(32),
    IN password_param VARCHAR(32),
    IN name_param VARCHAR(32),
    IN lastname_param VARCHAR(32),
    IN phone_param VARCHAR(10),
    IN country_param VARCHAR(255),
    IN city_param VARCHAR(255),
    IN address_param VARCHAR(200),
    IN zip_param varchar(5),
    IN c_cords_param POINT
)
BEGIN
    -- Insert into User table
    INSERT INTO users (username, password, name, lastname, phone, country, city, address, zip, role) 
    VALUES (username_param, password_param, name_param, lastname_param, phone_param, country_param, city_param, address_param, zip_param, 'citizen');

    -- Get the last inserted user_id
    SET @last_user_id = LAST_INSERT_ID();

    -- Insert into citizen table
    INSERT INTO citizen (citizen_id, c_cords) VALUES (@last_user_id, c_cords_param);
END //

DELIMITER ;

-- CALL InsertUserCitizen('citizen_user', 'password123', 'citizen_name', 'citizen_lastname', '6944444444', 'Greece', 'Patras', 'Kronou 14', 26334);