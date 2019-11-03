<?php

use Illuminate\Database\Seeder;

class MovementsTableSeeder extends Seeder
{
    private $numberOfDays = 60;
    private $avgFrequency = 600;   // Um movimento a cada X segundos (em média)
    private $deltaFreq    = 600;  // Variação da frequencia (em mínutos) - calcula o random a partir da frequencia e delta
    							   // mas valor minimo é sempre 1 minuto	
    private $limiteMax    = 10000; // Limite máximo para uma conta
    private $transferRatio = 15;
    private $noCategoryRatio = 10;
    private $users = [];	
    private $usersIds = [];	
    private $totalUsers;							    
    private $totalUsersFixo;							    

    private function cleanUsers() {
    	foreach ($this->users as $key => $user) {    		
    		if ($user["type"] != 'u') {
    			unset($this->users[$key]);
    		} else {
    			$user["fixo"] = strpos($user["name"], "user") === 0;
    		}
    	}
    }

    private function randomInfoUsers() {
    	// $factorCredito - factor de multiplicação dos valores de credito
    	// $factorDebito - factor de multiplicação dos valores de debito
    	// $racioDebitoCredito normal = 3 (3 movimentos debito para 1 de credito)
    	$this->totalUsersFixo = 0;
    	foreach ($this->users as $key => $user) {
    		$user["balance"] = 0;
    		if ($user["fixo"]) {
    			$this->totalUsersFixo++;
    			if ($user["name"] == "user4") {  //user4 - mais pobre
		    		$user["factorCredito"] = 0.1;
		    		$user["factorDebito"] = 0.2;
		    		$user["racioDebitoCredito"] = 10;
    			} else if ($user["name"] == "user5") {  //user5 - mais rico
		    		$user["factorCredito"] = 3;
		    		$user["factorDebito"] = 3;
		    		$user["racioDebitoCredito"] = 5;
    			} else {
		    		$user["factorCredito"] = 1;
		    		$user["factorDebito"] = 1;
		    		$user["racioDebitoCredito"] = 8;
    			}
    		} else {
	    		$user["factorCredito"] = rand(1,30) * 0.1;
	    		$user["factorDebito"] = rand(2,30) * 0.1;
	    		$user["racioDebitoCredito"] = rand(40,100) * 0.1;
    		}
    	}
    	$this->totalUsers = count($this->users);
    	$this->usersIds = array_keys($this->users);
    }

    private function getRandomUser() {
    	$n = rand(0, $this->totalUsers + 20);
    	if ($n >= $this->totalUsers) {
    		$n = $n % $this->totalUsersFixo;    		
    	}
    	return $this->users[$this->usersIds[$n]];
    }

    private function getValor($user, $category, $fator = 1) {
    	$valor = $category["avg"] * 100 + rand(1, intdiv($category["delta"],2) * 100); 
    	if ($category["type"] == "i") {
    		$valor = $valor * $user["factorCredito"];
    	} else {
    		$valor = $valor * $user["factorDebito"];
    	}
    	$valor = $valor * $fator;
    	if ($valor < 1) {
    		$valor = 1;
    	}
    	if ($valor > 500000) {
    		$valor = 500000;
    	}
    	return round($valor / 100, 2);
    }

    private function getIban($faker) {
    	$ibanPreCodes = ["PT50", "ES91", "FR14", "GB29", "DE89"];
    	$n = rand(0,20);
    	$n = $n > 4 ? 0 : $n;
    	return $ibanPreCodes[$n] . $faker->randomNumber($nbDigits = 7, $strict = true) . 
    			$faker->randomNumber($nbDigits = 7, $strict = true) . 
    			$faker->randomNumber($nbDigits = 7, $strict = true);
    }

    private function criarMovimentoArray($faker, $date, $user, $category, $valor, $transfer = false) {    	
    	$idCategory = (rand(1, $this->noCategoryRatio) == 1) ? null: $category["id"];
    	if ($transfer) {
    		$type_payment = null;
    	} else {
	    	if ($category["type"] == "e") {
	    		$type_payment = $faker->randomElement(["bt","mb","mb","mb"]);	
	    	} else {
	    		$type_payment = $faker->randomElement(["c","bt","bt"]);	
	    	}    		
    	}
    	switch ($type_payment) {
    		case 'c':
    			$iban = null;
    			$mb_entity_code = null;
    			$mb_payment_reference = null;
    			break;

    		case 'bt':
    			$iban = $this->getIban($faker);	    			
    			$mb_entity_code = null;
    			$mb_payment_reference = null;

    			break;

    		case 'mb':
    			$iban = null;
    			$mb_entity_code = $faker->randomNumber($nbDigits = 5, $strict = true);
    			$mb_payment_reference = $faker->randomNumber($nbDigits = 9, $strict = true);

    			break;
    		
    		default: // No payment - a transfer
    			$iban = null;
    			$mb_entity_code = null;
    			$mb_payment_reference = null;
    			break;
    	}
    	$start_balance = $user["balance"];
    	$end_balance = round($category["type"] == "e" ? $start_balance - $valor : $start_balance + $valor,2);
    	$user["balance"] = $end_balance;

     	return [
     		"wallet_id" => $user["id"],
     		"type" => $category["type"],
     		"transfer" => $transfer,
     		"transfer_movement_id" => null,
     		"transfer_wallet_id" => null,
     		"type_payment" => $type_payment,
     		"category_id" => $idCategory,
     		"iban" => $iban,
			"mb_entity_code" => $mb_entity_code,
			"mb_payment_reference" => $mb_payment_reference,
			"description" => rand(1,5) == 1 ? $faker->realText() : null, 
			"source_description" =>  (($category["type"] == "i") && rand(1,5)) == 1 ? $faker->realText() : null, 
			"date" => $date,
			"start_balance" => $start_balance,
			"end_balance" => $end_balance,
			"value" => $valor
     	];
    }

    private function criarMovimento($faker, $date) {
    	$user = $this->getRandomUser();
    	$category = CategoriesTableSeeder::getRandomMovement($user["racioDebitoCredito"]);

    	if (($category["type"] == "i") && ($user["balance"] > $this->limiteMax)) {
    	 	$category = CategoriesTableSeeder::getRandomExpense();
    	} 

    	$valor = $this->getValor($user, $category);

    	if (($category["type"] == "e") && ($valor > $user["balance"])) {
    	 	$category = CategoriesTableSeeder::getRandomIncome();
    	 	$valor = $this->getValor($user, $category, 2);
    	}
    	$mov = $this->criarMovimentoArray($faker, $date, $user, $category, $valor);
    	
		return DB::table('movements')->insertGetId($mov);
    }

    private function criarTransferencia($faker, $date) {
    	$userSource = $this->getRandomUser();
    	$userDest = $userSource;

    	$i=0;
    	while (($userSource == $userDest) || ($userDest["balance"] > $this->limiteMax)){
    		$userDest = $this->getRandomUser();	
    		$i++;
    		if ($i > 50) {  // Se houver mais do que 50 tentativas, sai
    			return;
    		}
    	} 
    	$categorySource = CategoriesTableSeeder::getRandomExpense();
    	$categoryDest = CategoriesTableSeeder::getRandomIncome();

    	$valor = $this->getValor($userSource, $categorySource);

    	if ($valor > $userSource["balance"]) {
    		if ($userSource["balance"] < 10) {  // Se userSource não tem sequer 10 euros, ignora transferencia
    			return;
    		}
    		$valor = round(rand(990, intval($userSource["balance"]*100)) / 100, 2); 
    	}
    	if ($valor > $userSource["balance"]) {
    		return;
    	}
    	// Aqui já temos a garantia que user Source tem dinheiro para a transferência
    	
    	$mov = $this->criarMovimentoArray($faker, $date, $userSource, $categorySource, $valor, true);
    	$newIDSource = DB::table('movements')->insertGetId($mov);
    	$mov = $this->criarMovimentoArray($faker, $date, $userDest, $categoryDest, $valor, true);
    	$newIDDest = DB::table('movements')->insertGetId($mov);

    	DB::table('movements')->where('id', $newIDSource)
    		->update([  'transfer_movement_id' => $newIDDest, 
    			 		'transfer_wallet_id' => $userDest["id"]]);
    	DB::table('movements')->where('id', $newIDDest)
    		->update([  'transfer_movement_id' => $newIDSource, 
    			 		'transfer_wallet_id' => $userSource["id"]]);
    }

    private function updateAllBalances($users) {
    	foreach ($users as $user) {
    		DB::table('wallets')->where('id', $user["id"])->update(['balance' => $user["balance"]]);
    	}
    }


    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if (DatabaseSeeder::$seedType == "full") {
            $this->numberOfDays = 5*365;   // 5 ANOS
        } else {
            $this->numberOfDays = 30;      // 1 mês
        }

    	$this->command->info("Movimentos seeder - Start");

    	$this->users = UsersTableSeeder::$allUsers;
    	$this->cleanUsers();
    	$this->randomInfoUsers();

        $faker = \Faker\Factory::create('pt_PT');

        $today = Carbon\Carbon::today();
        $this->start_date = $today->copy();
        $this->start_date->subDays($this->numberOfDays);
        $d = $this->start_date->copy();

    	$i=0;
    	while ($d->lessThanOrEqualTo($today)) {
	    	if (rand(1,$this->transferRatio) == 1) {
	    		$movimento = $this->criarTransferencia($faker, $d);
	    	} else {
	    		$movimento = $this->criarMovimento($faker, $d);
	    	}
    		

    		if ($i % 15000 == 0) {  // mais ou menos de 3 em 3 meses, aumenta o nº de movimento por seg.
    			$n = rand(1,intdiv($this->avgFrequency, 10));
    			$this->avgFrequency = $this->avgFrequency - $n;
    		}
    		if ($i % 100 == 0) {
    			$this->command->info("Created movement " . $i . " for date " . $d->format('Y-m-d'));
    		}
    		$i++;

    		$deltaSegundos = $this->avgFrequency - intdiv($this->deltaFreq, 2) + rand(0, $this->deltaFreq);

    		if ($d->month == 12) {
    			$deltaSegundos = intval($deltaSegundos * 0.8);	
    		} else if ($d->month == 8) {
    			$deltaSegundos = intval($deltaSegundos * 0.9);	
    		} else if ($d->month == 2) {
    			$deltaSegundos = intval($deltaSegundos * 1.3);	
    		} else if ($d->month == 3) {
    			$deltaSegundos = intval($deltaSegundos * 1.1);	
    		}

    		if ($deltaSegundos < 1) {
    			$deltaSegundos = 1;
    		}
    		$d->addSeconds($deltaSegundos);
    	}
    	$this->command->info("");
    	$this->command->info("Created a total of $i movements");
    	$this->command->info("");

    	$this->updateAllBalances($this->users); 
    	$this->command->info("Updated all Wallet Balances");
    	$this->command->info("");

    }
}
