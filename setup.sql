CREATE TABLE IF NOT EXISTS commitments (
	cert_id STRING,
	cert_type STRING, -- maybe this should be a foreign key
	name STRING,
	extra_info STRING,
	expiry_date DATE
);
