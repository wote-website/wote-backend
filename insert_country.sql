LOAD DATA INFILE '/var/lib/mysql-files/test.csv'
INTO TABLE rating
FIELDS TERMINATED BY ';'
ENCLOSED BY '"'
LINES TERMINATED BY '\n'
IGNORE 1 ROWS
(rating_value, source_index, country_alpha3, criterion_code);