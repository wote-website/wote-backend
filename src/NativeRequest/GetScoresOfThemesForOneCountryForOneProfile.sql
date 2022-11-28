SELECT
	prio.profile_id AS profile_id,
	prio.theme_id AS theme_id,
	sco.country_id AS country_id,
	weisum.weightings_sum AS weightings_sum,
	sco.score AS value,
	sco.active_weightings_sum/weisum.weightings_sum AS coverage
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
			pri.profile_id = :profile_param AND ra.country_id = :country_param
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
;
