SELECT
	prio2.id AS id,
	prio2.profile_id AS profile_id,
	prio2.theme_id AS theme_id,
	prio2.value AS value,
	weisum.weightings_sum AS weightings_sum,
	sco.score AS score,
	sco.active_weightings_sum/weisum.weightings_sum AS transparency
FROM priority prio2
	LEFT JOIN (	
		SELECT 
			SUM(ra.rating_value*wei.value*wei.positive_flag - (100-ra.rating_value)*wei.value*wei.negative_flag) 
				/ SUM(ABS(wei.value)) AS score,
			SUM(ABS(wei.value)) AS active_weightings_sum,
			pri.id AS priority_id
		FROM rating ra
			INNER JOIN country co ON ra.country_id = co.id
			INNER JOIN criterion cri ON ra.criterion_id = cri.id
			INNER JOIN weighting wei ON cri.id = wei.criterion_id
			INNER JOIN priority pri ON wei.priority_id = pri.id
		WHERE co.id = :country_param 
			AND pri.profile_id = :profile_param
		GROUP BY pri.id
		) AS sco
		ON prio2.id = sco.priority_id
	LEFT JOIN (
		SELECT
			SUM(ABS(wei.value)) AS weightings_sum,
			wei.priority_id AS priority_id
		FROM 
			weighting wei
		GROUP BY
			wei.priority_id
		) AS weisum
		ON prio2.id = weisum.priority_id
WHERE prio2.profile_id = :profile_param
;
