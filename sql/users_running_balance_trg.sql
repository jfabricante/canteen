DELIMITER $$

USE `canteenv2`$$

DROP TRIGGER /*!50032 IF EXISTS */ `users_running_balance_trg`$$

CREATE
    /*!50017 DEFINER = 'root'@'localhost' */
    TRIGGER `users_running_balance_trg` AFTER INSERT ON `transaction_tbl` 
    FOR EACH ROW BEGIN
	DECLARE v_current_amount DECIMAL(10,2);
	
	SELECT 
	  current_amount
	INTO v_current_amount
	FROM
	  `canteenv2`.`users_running_balance_tbl` 
	WHERE user_id = NEW.user_id 
	ORDER BY id DESC
	LIMIT 1;

    
	INSERT INTO `canteenv2`.`users_running_balance_tbl` (
	  reference_id,
	  credit_amount,
	  source,
	  user_id,
	  current_amount
	) 
	VALUES
	  (
	    NEW.id,
	    NEW.credit_used,
	    'transaction',
	    NEW.user_id,
	    v_current_amount - NEW.credit_used
	  ) ;

    END;
$$

DELIMITER ;