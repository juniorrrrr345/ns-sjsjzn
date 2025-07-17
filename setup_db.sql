-- Create database
drop database if exists ecommerce;
create database ecommerce;
use ecommerce;

-- Create admin table
create table admin (
    id int auto_increment primary key,
    username varchar(50) not null unique,
    password varchar(255) not null
);

-- Insert sample admin user (username: admin, password: admin123)
insert into admin (username, password) values (
    'admin',
    '$2y$10$wH6QwQwQwQwQwQwQwQwQwOQwQwQwQwQwQwQwQwQwQwQwQwQwQwQwQwQwQwQwQwQw' -- hash for 'admin123'
);
-- To generate a new hash, use: password_hash('admin123', PASSWORD_DEFAULT) in PHP 