SELECT
	@n := @n + 1 AS reference,
	designation
FROM criterion, (SELECT @n:=50) m
;