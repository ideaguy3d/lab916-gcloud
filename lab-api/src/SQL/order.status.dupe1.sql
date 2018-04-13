SELECT
  id, amazon_order_id, order_status, asin, COUNT(*)
FROM
  ptp_fba_sales_v1
GROUP BY
  id, amazon_order_id, order_status, asin
HAVING
  COUNT(*) >1;

SELECT
  id, amazon_order_id, order_status, asin, COUNT(amazon_order_id)
FROM
  ptp_fba_sales_v1
GROUP BY
  id, amazon_order_id, order_status, asin
HAVING
  COUNT(amazon_order_id) >1;