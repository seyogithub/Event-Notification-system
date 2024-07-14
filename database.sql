
CREATE TABLE user (
    user_id INT PRIMARY KEY AUTO_INCREMENT NOT NULL,
    fname VARCHAR(250) NOT NULL UNIQUE,
    lname VARCHAR(250) NOT NULL,
    gender VARCHAR(20) NOT NULL,
    age INT NOT NULL,
    coll VARCHAR(250) NOT NULL,
    department VARCHAR(250) NOT NULL,
    username VARCHAR(250) NOT NULL UNIQUE,
    password VARCHAR(250) NOT NULL,
    phone VARCHAR(250) NOT NULL,
    role VARCHAR(250) NOT NULL,
    date VARCHAR(250) NOT NULL
);

CREATE TABLE event (
    event_id INT PRIMARY KEY AUTO_INCREMENT NOT NULL,
    title VARCHAR(250) NOT NULL,
    description TEXT NOT NULL,
    event_date DATE NOT NULL,
    event_time TIME NOT NULL,
    location VARCHAR(250) NOT NULL,
    created_by VARCHAR(250) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (created_by) REFERENCES user(user_id)
);


CREATE TABLE assRole (
    rol_id INT PRIMARY KEY AUTO_INCREMENT NOT NULL,
    role_name VARCHAR(250) NOT NULL
);


CREATE TABLE login (
    log_id INT PRIMARY KEY AUTO_INCREMENT NOT NULL,
    logged_by INT NOT NULL,
    date VARCHAR(250) NOT NULL,
   
    FOREIGN KEY (logged_by) REFERENCES user(user_id)
);
