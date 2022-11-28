SELECT
	ori.profile_id,
	ori.product_id,
	ori.cri_designation,
	ori.cri_id,
	SUM(ori.conso_value*ori.oriented_rating) / SUM(ori.conso_value) AS cri_score,
	SUM(ori.conso_value) / prod_conso.value_sum AS cri_transparency
FROM (
	SELECT 
		conso.gdp_ppp_per_capita AS rev_conso,
		profile.id AS profile_id,
		product.id AS product_id,
		production.value AS production_value,
		production.value / co.gdp_ppp_per_capita * conso.gdp_ppp_per_capita AS conso_value,
		co.name AS co_name,
		co.gdp_ppp_per_capita AS rev_prod,
		cri.designation AS cri_designation,
		cri.id AS cri_id,
		(ra.rating_value*wei.positive_flag + (100-ra.rating_value)*wei.negative_flag) AS oriented_rating,
		ABS(wei.value) AS absolute_weighting
	FROM product_scale sca
		INNER JOIN product ON sca.product_id = product.id
		INNER JOIN user ON sca.user_id = user.id
		INNER JOIN country conso ON user.country_id = conso.id
		INNER JOIN profile ON user.active_profile_id = profile.id
		INNER JOIN production ON product.id = production.product_id
		INNER JOIN operation ope ON ope.id = production.operation_id
		INNER JOIN country co ON production.country_id = co.id
		INNER JOIN rating ra ON ra.country_id = co.id
		INNER JOIN criterion cri ON ra.criterion_id = cri.id
		INNER JOIN weighting wei ON ra.criterion_id = wei.criterion_id and wei.profile_id = profile.id
	WHERE user.id = 1
	) AS ori

JOIN (
	SELECT 
		profile.id AS profile_id,
		product.id AS product_id,
		SUM(production.value / co.gdp_ppp_per_capita * conso.gdp_ppp_per_capita) AS value_sum
	FROM product_scale sca
		INNER JOIN product ON sca.product_id = product.id
		INNER JOIN user ON sca.user_id = user.id
		INNER JOIN country conso ON user.country_id = conso.id
		INNER JOIN profile ON user.active_profile_id = profile.id
		INNER JOIN production ON product.id = production.product_id
		INNER JOIN operation ope ON ope.id = production.operation_id
		INNER JOIN country co ON production.country_id = co.id
	WHERE user.id = 1 /* AND sca.status LIKE("ACTIVE") */
	GROUP BY
		profile.id,
		product.id
	) AS prod_conso
	ON ori.product_id = prod_conso.product_id

GROUP BY
	ori.profile_id,
	ori.product_id,
	ori.cri_designation,
	ori.cri_id,
	prod_conso.value_sum
;	