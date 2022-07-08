<?php

	namespace App\Exception;

	class AmountException extends \Exception
	{

		public function verifAmount($a)
		{
			if ($a < 0)
			{
				throw new \Exception('Le montant ne peut être nul, si c\'est le cas, modifier le type d\'opération',60);
			}

		}
	}