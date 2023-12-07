CREATE TABLE users (
  users_id INT NOT NULL AUTO_INCREMENT,
  username varchar(32) NOT NULL,
  password varchar(255) NOT NULL,
  name varchar(32),
  lastname varchar(32),
  phone varchar(10),
  country varchar(255),
  city varchar(255),
  address varchar(200),
  zip varchar(5),
  role ENUM('admin', 'citizen', 'rescuer') NOT NULL, 
  PRIMARY KEY (users_id)
);

CREATE TABLE admin (
  admin_id INT NOT NULL,
  CONSTRAINT fk_admin_id FOREIGN KEY(admin_id)
  REFERENCES users(users_id)
  ON DELETE CASCADE
  ON UPDATE CASCADE
);

CREATE TABLE rescuer(
  resc_id INT NOT NULL,
  curr_task TINYINT DEFAULT 0,
  r_cords POINT NOT NULL,
  car_name VARCHAR (32) NOT NULL,
  CONSTRAINT fk_rescuer1_id FOREIGN KEY(resc_id)
  REFERENCES users(users_id)
  ON DELETE CASCADE
  ON UPDATE CASCADE
);

CREATE TABLE citizen(
  citizen_id INT NOT NULL,
  CONSTRAINT fk_citizen_id FOREIGN KEY(citizen_id)
  REFERENCES users(users_id)
  ON DELETE CASCADE
  ON UPDATE CASCADE
);

CREATE TABLE announcements(
  announ_id INT NOT NULL AUTO_INCREMENT,
  description TEXT NOT NULL,
  PRIMARY KEY(announ_id)
);

CREATE TABLE category(
  categ_id INT NOT NULL AUTO_INCREMENT,
  categ_name VARCHAR(32) NOT NULL,
  PRIMARY KEY(categ_id)
);

CREATE TABLE inventory(
  prod_id INT NOT NULL AUTO_INCREMENT,
  prod_name VARCHAR(50) NOT NULL,
  description TEXT,
  quantity INT DEFAULT 0,
  categ_id INT NOT NULL,
  PRIMARY KEY(prod_id),
  CONSTRAINT fk_categ_id FOREIGN KEY(categ_id)
  REFERENCES category(categ_id)
  ON DELETE CASCADE
  ON UPDATE CASCADE
);

CREATE TABLE car_inv(
  resc_id INT NOT NULL,
  prod_id INT NOT NULL,
  CONSTRAINT fk_rescuer2_id FOREIGN KEY(resc_id)
  REFERENCES rescuer(resc_id)
  ON DELETE CASCADE
  ON UPDATE CASCADE,

  CONSTRAINT fk_prod1_id FOREIGN KEY(prod_id)
  REFERENCES inventory(prod_id)
  ON DELETE CASCADE
  ON UPDATE CASCADE
);

CREATE TABLE announced_item(
  prod_id INT NOT NULL,
  announ_id INT NOT NULL,
  CONSTRAINT fk_prod2_id FOREIGN KEY(prod_id)
  REFERENCES inventory(prod_id)
  ON DELETE CASCADE
  ON UPDATE CASCADE,
  
  CONSTRAINT fk_announ1_id FOREIGN KEY(announ_id)
  REFERENCES announcements(announ_id)
  ON DELETE CASCADE
  ON UPDATE CASCADE
);

CREATE TABLE task(
  task_id INT NOT NULL AUTO_INCREMENT,
  t_cords POINT NOT NULL,
  active BOOLEAN DEFAULT FALSE,
  publish_date DATETIME NOT NULL,
  PRIMARY KEY(task_id)  
);

CREATE TABLE request(
  request_id INT NOT NULL,
  citizen_id INT NOT NULL,
  resc_id INT NOT NULL,
  prod_id INT NOT NULL,
  occ_date DATETIME DEFAULT NULL,
  ppl INT DEFAULT 1,
  CONSTRAINT fk_request1_id FOREIGN KEY(request_id)
  REFERENCES task(task_id)
  ON DELETE CASCADE
  ON UPDATE CASCADE,

  CONSTRAINT fk_prod3_id FOREIGN KEY(prod_id)
  REFERENCES inventory(prod_id)
  ON DELETE CASCADE
  ON UPDATE CASCADE,

  CONSTRAINT fk_citizen2_id FOREIGN KEY(citizen_id)
  REFERENCES citizen(citizen_id)
  ON DELETE CASCADE
  ON UPDATE CASCADE,

  CONSTRAINT fk_rescuer3_id FOREIGN KEY(resc_id)
  REFERENCES rescuer(resc_id)
  ON DELETE CASCADE
  ON UPDATE CASCADE
);

CREATE TABLE donation(
  donate_id INT NOT NULL,
  citizen_id INT NOT NULL,
  resc_id INT DEFAULT NULL,
  prod_id INT NOT NULL,
  occ_date DATETIME DEFAULT NULL,
  quantity INT DEFAULT 1,
  CONSTRAINT fk_donate1_id FOREIGN KEY(donate_id)
  REFERENCES task(task_id)
  ON DELETE CASCADE
  ON UPDATE CASCADE,

  CONSTRAINT fk_prod4_id FOREIGN KEY(prod_id)
  REFERENCES inventory(prod_id)
  ON DELETE CASCADE
  ON UPDATE CASCADE,

  CONSTRAINT fk_citizen3_id FOREIGN KEY(citizen_id)
  REFERENCES citizen(citizen_id)
  ON DELETE CASCADE
  ON UPDATE CASCADE,

  CONSTRAINT fk_rescuer4_id FOREIGN KEY(resc_id)
  REFERENCES rescuer(resc_id)
  ON DELETE CASCADE
  ON UPDATE CASCADE
);