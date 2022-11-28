SELECT 
	cri.id AS criterion_id,
	ra.country_id AS country_id,
	cri.theme_id AS theme_id,
	ra.rating_value*wei.positive_flag + (100-ra.rating_value)*wei.negative_flag AS value,
	1 AS coverage, 
	NOW() AS creation_date
FROM rating ra
	INNER JOIN criterion cri ON ra.criterion_id = cri.id
	INNER JOIN weighting wei ON cri.id = wei.criterion_id
WHERE
	wei.profile_id = :profile_param AND ra.country_id = :country_param
;
