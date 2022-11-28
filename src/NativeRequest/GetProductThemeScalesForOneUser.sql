SELECT
	cri_sca.profile_id,
	cri_sca.product_id,
	theme_sum.id,
	cri_sca.theme_id,
	SUM(cri_sca.absolute_weighting*cri_sca.cri_score*cri_sca.transparency) / SUM(cri_sca.absolute_weighting*cri_sca.transparency) AS theme_score,
	SUM(cri_sca.absolute_weighting*cri_sca.transparency) / theme_sum.wei_sum AS transparency
FROM(
	SELECT
		ori.profile_id,
		ori.product_id,
		ori.cri_designation,
		ori.theme_id,
		ori.cri_id,
		ori.absolute_weighting,
		SUM(ori.conso_value*ori.oriented_rating) / SUM(ori.conso_value) AS cri_score,
		SUM(ori.conso_value) / prod_conso.value_sum AS transparency
	FROM (
	/*
	Get oriented rating by country for one user
	 */	
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
			cri.theme_id AS theme_id,
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
		ori.theme_id,
		prod_conso.value_sum,
		ori.absolute_weighting
	) AS cri_sca
INNER JOIN (
	SELECT
		profile.id AS profile_id,
		cri.theme_id AS id,
		SUM(ABS(wei.value)) AS wei_sum
	FROM user
		INNER JOIN profile ON user.active_profile_id = profile.id
		INNER JOIN weighting wei ON wei.profile_id = profile.id
		INNER JOIN criterion cri ON cri.id = wei.criterion_id
	WHERE user.id = 1
	GROUP BY
		profile.id,
		cri.theme_id
	) AS theme_sum ON theme_sum.id = cri_sca.theme_id

GROUP BY
	cri_sca.profile_id,
	cri_sca.product_id,
	theme_sum.id,
	cri_sca.theme_id
;	