/*
Requete extraite de la construction de product scale. 
Il faut la finaliser pour pouvoir l'injecter dans des objets.
 */

SELECT 
	profile.id AS profile_id,
	product.id AS product_id,
	co.name,
	cri.designation,
	ra.rating_value,
	wei.value,
	wei.positive_flag,
	wei.negative_flag,
	(ra.rating_value*wei.positive_flag + (100-ra.rating_value)*wei.negative_flag) AS oriented_rating,
	ABS(wei.value) AS absolute_weighting
FROM product_scale sca
	INNER JOIN product ON sca.product_id = product.id
	INNER JOIN user ON sca.user_id = user.id
	INNER JOIN profile ON user.active_profile_id = profile.id
	INNER JOIN production ON product.id = production.product_id
	INNER JOIN operation ope ON ope.id = production.operation_id
	INNER JOIN country co ON production.country_id = co.id
	INNER JOIN rating ra ON ra.country_id = co.id
	INNER JOIN criterion cri ON ra.criterion_id = cri.id
	INNER JOIN weighting wei ON ra.criterion_id = wei.criterion_id and wei.profile_id = profile.id
WHERE user.id = 1
;	