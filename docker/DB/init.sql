USE carRoute;
CREATE TABLE Users(
                      id INT(4) AUTO_INCREMENT PRIMARY KEY,
                      name VARCHAR(20) NOT NULL,
                      password VARCHAR(255) NOT NULL,
                      reg_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);