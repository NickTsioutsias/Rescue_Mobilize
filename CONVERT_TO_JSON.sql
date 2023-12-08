-- Here we convert sql tables from database to JSON
SELECT name as [Citizen.name],
lastname as [Citizen.lastname],
phone as [Citizen.phone]
FROM users WHERE role = citizen
FOR JSON PATH, ROOT('Citizens');