SELECT 
    u.username, sn.street, c.city, s.state
FROM
    t_addresses AS a
        INNER JOIN
    t_users AS u ON a.user_id = u.id
        INNER JOIN
    t_cities c ON a.city_id = c.id
        INNER JOIN
    t_states s ON a.state_id = s.id AND s.state = 'CA'
        INNER JOIN
    t_streetnumbers AS sn ON a.street_id = sn.id