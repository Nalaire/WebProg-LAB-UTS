CREATE DATABASE web_programming_lab_uts;
USE web_programming_lab_uts;

CREATE TABLE users (
    user_id INTEGER AUTO_INCREMENT PRIMARY KEY,
    user_name VARCHAR(50) UNIQUE,
    email VARCHAR(50) UNIQUE,
    password VARCHAR(100) DEFAULT ''
);

CREATE TABLE to_do_lists (
    list_id INTEGER AUTO_INCREMENT PRIMARY KEY,
    list_name VARCHAR(100) NOT NULL,
    user_id INTEGER,
    CONSTRAINT FOREIGN KEY (user_id) REFERENCES users(user_id)
);

CREATE TABLE tasks (
    task_id INTEGER AUTO_INCREMENT PRIMARY KEY,
    task_name VARCHAR(100) NOT NULL,
    task_completion BOOLEAN DEFAULT '0',
    list_id INTEGER,
    CONSTRAINT FOREIGN KEY (list_id) REFERENCES to_do_lists(list_id)
);

-- Example of inserting values into the table
INSERT INTO users (user_name, email, password)
VALUES ('username1', 'email1', '$2y$10$dCOlmVumQqAUuzEjCw33Ku7L2OLR1bUmA33X/NYhax4Wi4Wn8yz8e');

INSERT INTO to_do_lists(list_name, user_id)
VALUES ('listname1', '1');

INSERT INTO tasks(task_name, list_id)
VALUES ('taskname1', '1'),
('taskname2', '1'),
('taskname3', '1');

DROP USER IF EXISTS 'userlab'@'localhost';

CREATE USER 'userlab'@'localhost' IDENTIFIED BY 'endc';
GRANT SELECT, UPDATE, DELETE, INSERT ON web_programming_lab_uts.* TO 'userlab'@'localhost';
