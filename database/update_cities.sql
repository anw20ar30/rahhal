USE rahhal_db;
UPDATE experiences SET city='نيوم'     WHERE experience_id=11;
UPDATE experiences SET city='السويد'   WHERE experience_id=12;
UPDATE experiences SET city='المالديف' WHERE experience_id=13;
UPDATE experiences SET city='السويد'   WHERE experience_id=14;
UPDATE experiences SET city='إدنبرة'   WHERE experience_id=15;
SELECT experience_id, hotel_name, city FROM experiences ORDER BY experience_id;
