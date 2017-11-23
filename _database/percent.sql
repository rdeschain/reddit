SELECT 
    multi / total AS multi_percent
FROM
    (SELECT 
        (SELECT 
                    COUNT(id) AS multi
                FROM
                    t_addresses
                GROUP BY user_id
                HAVING multi > 1) AS multi,
            COUNT(id) AS total
    FROM
        t_addresses AS total) AS tt