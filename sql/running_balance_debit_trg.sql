DELIMITER $$

USE `canteenv2`$$

DROP TRIGGER /*!50032 IF EXISTS */ `running_balance_debit_trg`$$

CREATE
    /*!50017 DEFINER = 'root'@'localhost' */
    TRIGGER `running_balance_debit_trg` AFTER INSERT ON `users_meal_history_tbl` 
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
	  debit_amount,
	  current_amount,
	  source,
	  user_id
	) 
	VALUES
	  (
	    NEW.id,
	    NEW.adj_amount,
	    (NEW.adj_amount + v_current_amount),
	    'Payroll',
	    NEW.user_id
	  ) ;
    END;
$$

DELIMITER ;