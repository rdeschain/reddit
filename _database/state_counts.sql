SELECT 
    COUNT(a.id) AS people_in_state, s.state
FROM
    t_addresses AS a
        INNER JOIN
    t_users AS u ON a.user_id = u.id
        INNER JOIN
    t_states s ON a.state_id = s.id
GROUP BY a.state_id