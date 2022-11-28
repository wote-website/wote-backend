# For the moment, there must be only ONE rating per country per criterion
# As there is no automatic selection of the last rating
# So this request detects if there is a double rating of a country in one criterion
/* Then the rating must be manually deleted
*/

SELECT
	COUNT(co.id) AS country_occurrency,
	MAX(ra.id) AS last_rating,
	MAX(ra.rating_value)/MIN(ra.rating_value) AS equal_value_check,
	co.name AS country,
	co.id AS country_id,
	cri.designation AS criterion,
	cri.id AS criterion_id
FROM 
	rating ra
	JOIN criterion cri ON ra.criterion_id = cri.id
	JOIN country co ON ra.country_id = co.id
GROUP BY
	country,
	country_id,
	criterion,
	criterion_id
HAVING 
	-- country IN ('Yemen', 'South Africa') AND
	country_occurrency > 1
;
