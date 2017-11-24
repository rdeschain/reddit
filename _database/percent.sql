SELECT 
    COUNT(t.multi) / (SELECT 
            COUNT(id)
        FROM
            t_users) AS percent
FROM
    (SELECT 
        COUNT(user_id) AS multi
    FROM
        t_addresses
    GROUP BY user_id
    HAVING multi > 1) AS t