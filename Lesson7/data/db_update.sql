USE `geek_brains_shop`;

ALTER TABLE products
    ADD deleted tinyint DEFAULT 0 NULL;

ALTER TABLE products
    ALTER COLUMN category_id SET DEFAULT 1;