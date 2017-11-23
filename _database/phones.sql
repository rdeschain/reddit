SELECT 
    u.username, p.pnumber
FROM
    t_phonenumbers AS a
        INNER JOIN
    t_users AS u ON a.user_id = u.id
        INNER JOIN
    t_phones p ON a.number_id = p.id