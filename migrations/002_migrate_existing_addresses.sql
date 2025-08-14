INSERT INTO user_addresses (user_id, type, company_name, kvk_number, vat_number, phone_number, street, postal_code, city, country)
SELECT userid, 'billing', company_name, kvk_number, vat_number, phone_number, street, postal_code, city, country FROM site_users_address;
