create database if not exists ecommerce;
use ecommerce;
create table users(
id                  int(255) auto_increment not null,
name                varchar(50) not null,
surname             varchar(100),
address             varchar(255),
email               varchar(255) not null,
password            varchar(200) not null,
phone               varchar(20),
image               varchar(200),
role_user           varchar(20) not null,
remember_token      varchar(20),
created_at          datetime DEFAULT NULL,
updated_at          datetime DEFAULT NULL,
constraint pk_user primary key (id)
)ENGINE=InnoDb;

create table categories(
id                  int(255) auto_increment not null,
name                varchar(100) not null,
description         varchar(250) not null,
created_at          datetime DEFAULT NULL,
updated_at          datetime DEFAULT NULL,
constraint pk_categories primary key (id)
)ENGINE=InnoDb;

create table subcategories(
id                  int(255) auto_increment not null,
name                varchar(100) not null,
description         varchar(250) not null,
category_id         int(255) not null,
created_at          datetime DEFAULT NULL,
updated_at          datetime DEFAULT NULL,
constraint pk_subcategories primary key (id),
constraint fk_subcat_cat foreign key(category_id) references categories(id)
)ENGINE=InnoDB;

create table products(
id                  int(255) auto_increment not null,
name                varchar(50) not null,
description         varchar(250) not null,
price               float not null,
stock               int(255) not null,
subcategory_id         int(255) not null,
image               varchar(200),
image2              varchar(200),
image3              varchar(200),
user_id             int(255) not null,
created_at          datetime DEFAULT NULL,
updated_at          datetime DEFAULT NULL,
constraint pk_products primary key(id),
constraint fk_product_user foreign key(user_id) references users(id),
constraint fk_product_subcategory foreign key(subcategory_id) references subcategories(id)
)ENGINE=InnoDb;

create table shopping_cart(
id                  int(255) auto_increment not null,
user_id             int(255) not null,
product_id          int(255) not null,
amount              int(255) not null,
created_at          datetime DEFAULT NULL,
updated_at          datetime DEFAULT NULL,
constraint pk_shopping_cart primary key (id),
constraint fk_cart_user foreign key (user_id) references  users(id),
constraint fk_cart_product foreign key (product_id ) references  products(id)
)ENGINE=InnoDb;

create table orders(
id                  int(255) auto_increment not null,
user_id             int(255) not null,
state               varchar(200),
address_order       varchar(250),
paymen_methods      varchar(200),
created_at          datetime DEFAULT NULL,
updated_at          datetime DEFAULT NULL,
constraint pk_order primary key (id),
constraint fk_order_user foreign key (user_id) references  users(id)
)ENGINE=InnoDB;

create table order_details (
id                  int(255) auto_increment not null,
order_id            int(255) not null,
product_id          int(255) not null,
amount              int(255) not null,
price_unit          float not null,
total               float not null,
constraint pk_order_detail primary key(id),
constraint fk_oder_detail_order foreign key (order_id ) references  orders(id),
constraint fk_oder_detail_product foreign key (product_id ) references  products(id)
)ENGINE=InnoDB;
