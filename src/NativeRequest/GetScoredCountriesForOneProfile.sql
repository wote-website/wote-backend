SELECT
	SUM(sco_prio.score*sco_prio.value*sco_prio.transparency)
		/ SUM(sco_prio.value*sco_prio.transparency) AS score,
	SUM(sco_prio.value*sco_prio.transparency) 
		/ (SELECT SUM(value) AS value_sum FROM priority WHERE profile_id = :profile_param) AS transparency,
	sco_prio.country_id AS id,
	co.name AS name,
	co.fr_name AS fr_name
FROM (
	SELECT
		prio.id AS id,
		prio.profile_id AS profile_id,
		prio.theme_id AS theme_id,
		sco.country_id AS country_id,
		prio.value AS value,
		weisum.weightings_sum AS weightings_sum,
		sco.score AS score,
		sco.active_weightings_sum/weisum.weightings_sum AS transparency
	FROM priority prio
		JOIN (	
			SELECT 
				SUM(ra.rating_value*wei.value*wei.positive_flag - (100-ra.rating_value)*wei.value*wei.negative_flag) 
					/ SUM(ABS(wei.value)) AS score,
				SUM(ABS(wei.value)) AS active_weightings_sum,
				ra.country_id AS country_id,
				pri.id AS priority_id
			FROM rating ra
				INNER JOIN criterion cri ON ra.criterion_id = cri.id
				INNER JOIN weighting wei ON cri.id = wei.criterion_id
				INNER JOIN priority pri ON wei.priority_id = pri.id
			WHERE
				pri.profile_id = :profile_param
			GROUP BY 
				ra.country_id,
				pri.id
		) AS sco
			ON prio.id = sco.priority_id
		JOIN (
			SELECT
				SUM(ABS(wei.value)) AS weightings_sum,
				wei.priority_id AS priority_id
			FROM 
				weighting wei
			GROUP BY
				wei.priority_id
			) AS weisum
			ON prio.id = weisum.priority_id
	WHERE prio.profile_id = :profile_param
	) AS sco_prio

JOIN country co ON co.id = sco_prio.country_id

GROUP BY 
	sco_prio.country_id,
	co.name

ORDER BY 
	score DESC

;
