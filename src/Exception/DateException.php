<?php

	namespace App\Exception;

	class DateException extends \Exception
	{
		public function verifDate($td, $d)
		{
			if ($td>$d){
				throw new \Exception('La date indiquée ne peut être inférieure à la date du jour',50);
			}

		}
	}