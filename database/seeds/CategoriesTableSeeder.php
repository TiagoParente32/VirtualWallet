<?php

use Illuminate\Database\Seeder;

class CategoriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    
    static public $expenses = [
    	['id' => 1, 'type' => 'e', 'name' => 'groceries', 'avg' => 80, 'delta' => 79, 'random_start' => 420],
    	['id' => 2, 'type' => 'e', 'name' => 'restaurant', 'avg' => 30, 'delta' => 25, 'random_start' => 380],
    	['id' => 3, 'type' => 'e', 'name' => 'clothes', 'avg' => 60, 'delta' => 55, 'random_start' => 340],
    	['id' => 4, 'type' => 'e', 'name' => 'shoes', 'avg' => 40, 'delta' => 30, 'random_start' => 325],
    	['id' => 5, 'type' => 'e', 'name' => 'school', 'avg' => 100, 'delta' => 90, 'random_start' => 315],
    	['id' => 6, 'type' => 'e', 'name' => 'services', 'avg' => 60, 'delta' => 50, 'random_start' => 260],
    	['id' => 7, 'type' => 'e', 'name' => 'electricity', 'avg' => 50, 'delta' => 40, 'random_start' => 250],
    	['id' => 8, 'type' => 'e', 'name' => 'phone', 'avg' => 40, 'delta' => 30, 'random_start' => 240],
    	['id' => 9, 'type' => 'e', 'name' => 'fuel', 'avg' => 40, 'delta' => 30, 'random_start' => 210],
    	['id' => 10, 'type' => 'e', 'name' => 'mortgage payment', 'avg' => 200, 'delta' => 190, 'random_start' => 200],
    	['id' => 11, 'type' => 'e', 'name' => 'car payment', 'avg' => 200, 'delta' => 190, 'random_start' => 190],
    	['id' => 12, 'type' => 'e', 'name' => 'entertainment', 'avg' => 40, 'delta' => 35, 'random_start' => 160],
    	['id' => 13, 'type' => 'e', 'name' => 'gadget', 'avg' => 100, 'delta' => 95, 'random_start' => 150],
    	['id' => 14, 'type' => 'e', 'name' => 'computer', 'avg' => 300, 'delta' => 200, 'random_start' => 145],
    	['id' => 15, 'type' => 'e', 'name' => 'vacation', 'avg' => 600, 'delta' => 500, 'random_start' => 140],
    	['id' => 16, 'type' => 'e', 'name' => 'hobby', 'avg' => 100, 'delta' => 90, 'random_start' => 110],
    	['id' => 17, 'type' => 'e', 'name' => 'loan repayment', 'avg' => 500, 'delta' => 450, 'random_start' => 80], // User repays a loan
    	['id' => 18, 'type' => 'e', 'name' => 'loan', 'avg' => 500, 'delta' => 450, 'random_start' => 50],           // User gives a loan to someone
    	['id' => 19, 'type' => 'e', 'name' => 'other expense', 'avg' => 50, 'delta' => 49, 'random_start' => 0],
    ];

    static public $incomes = [
        ['id' => 20, 'type' => 'i', 'name' => 'salary', 'avg' => 1000, 'delta' => 600, 'random_start' => 120],
        ['id' => 21, 'type' => 'i', 'name' => 'bonus', 'avg' => 1000, 'delta' => 900, 'random_start' => 115],
        ['id' => 22, 'type' => 'i', 'name' => 'royalties', 'avg' => 1000, 'delta' => 800, 'random_start' => 110],
        ['id' => 23, 'type' => 'i', 'name' => 'interests', 'avg' => 100, 'delta' => 80, 'random_start' => 105],
        ['id' => 24, 'type' => 'i', 'name' => 'gifts', 'avg' => 200, 'delta' => 180, 'random_start' => 95],
        ['id' => 25, 'type' => 'i', 'name' => 'dividends', 'avg' => 200, 'delta' => 190, 'random_start' => 90],
        ['id' => 26, 'type' => 'i', 'name' => 'sales', 'avg' => 80, 'delta' => 78, 'random_start' => 70],
  		['id' => 27, 'type' => 'i', 'name' => 'loan repayment', 'avg' => 500, 'delta' => 450, 'random_start' => 55], // User is repayed of a loan it gave previously
        ['id' => 28, 'type' => 'i', 'name' => 'loan', 'avg' => 500, 'delta' => 450, 'random_start' => 50],                // User receives a loan
    	['id' => 29, 'type' => 'i', 'name' => 'other income', 'avg' => 100, 'delta' => 90, 'random_start' => 0],
    ];

    static public function getRandomExpense() {
    	$rnd = rand(1,500);
	    foreach (CategoriesTableSeeder::$expenses as $item) {
	    	if ($item['random_start'] < $rnd) {
	    		return $item;
	    	}
    	}
    }

    static public function getRandomIncome() {
    	$rnd = rand(1,150);
	    foreach (CategoriesTableSeeder::$incomes as $item) {
	    	if ($item['random_start'] < $rnd) {
	    		return $item;
	    	}
    	}
    }

    // $racioDebitoCredito normal = 3 (3 movimentos debito para 1 de credito)
    static public function getRandomMovement($racioDebitoCredito) {
        if (rand(1,$racioDebitoCredito*10) < 10) {
            return CategoriesTableSeeder::getRandomIncome();
        } else {
            return CategoriesTableSeeder::getRandomExpense();
        }
    }

    private function reduceArrayToDB($array)    
	{
		$arrayDB = [];
		foreach($array as $row) {
			$arrayDB[] = ['id' => $row['id'], 'type' => $row['type'], 'name' => $row['name']];
		}
    	return $arrayDB;
	}

    public function run()
    {

        DB::table('categories')->insert($this->reduceArrayToDB(CategoriesTableSeeder::$expenses));

        DB::table('categories')->insert($this->reduceArrayToDB(CategoriesTableSeeder::$incomes));
    }
}
