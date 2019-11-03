<?php

use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    private $photoPath = 'public/fotos';
    private $numberOfAdmin = 5;     // primeiros 2 fixos
    private $numberOfOperator = 5;  // primeiros 2 fixos
    private $numberOfUsers = 20;   // primeiros 5 fixos
    private $files_M = [];
    private $files_F = [];
    static public $allUsers = [];


    private function limparFicheirosFotos() {
        Storage::deleteDirectory($this->photoPath);
        Storage::makeDirectory($this->photoPath);
    }

    private function preencherNomesFicheirosFotos() {
        // Preencher files_M com fotos de Homens e files_F com fotos de mulheres
        $allFiles = collect(File::files(database_path('seeds/fotos')));
        foreach ($allFiles as $f) {
            if (strpos($f->getPathname(), 'M_')) {
                $this->files_M[] = $f->getPathname();
            }
            else {
                $this->files_F[] = $f->getPathname();
            }
        }
    }

    private function newFakerUser($faker, $tipo = 'u', $userByNumber = 0)
    {
        $gender = $faker->randomElement(['male','female']);

        if ($userByNumber) {
        	switch ($tipo) {
        		case 'a':
        			$fullname = 'admin' . $userByNumber;
        			break;
        		case 'o':
        			$fullname = 'op' . $userByNumber;
        			break;
        		case 'u':
        			$fullname = 'user' . $userByNumber;
        			break;
        	}
	        $email = $fullname . '@mail.pt';

        } else {
	        $firstname = $faker->firstName($gender);
	        $lastname = $faker->lastName();

	        $secondname = $faker->numberBetween(1,3) == 2 ? "" : " " . $faker->firstName($gender);
	        $number_middlenames = $faker->numberBetween(1,6);
	        $number_middlenames = $number_middlenames == 1 ? 0 : ($number_middlenames >= 5 ? $number_middlenames-3 : 1);
	        $middlenames = "";
	        for($i=0; $i<$number_middlenames; $i++) {
	            $middlenames.= " " . $faker->lastName();
	        }
	        $fullname = $firstname.$secondname.$middlenames." ".$lastname;
	        $email = $faker->unique()->safeEmail;
        }


        $createdAt = $faker->dateTimeBetween('-10 years', '-3 months');
        $email_verified_at = $faker->dateTimeBetween($createdAt, '-2 months');
        $updatedAt = $faker->dateTimeBetween($email_verified_at, '-1 months');

        // um user em cada 15 está desativado, os restantes estão sempre ativos
        $activo =  ($tipo == 'u') && (!$userByNumber) ? $faker->numberBetween(1,15) !== 3 : true;
        $nif = ($tipo == 'u') ? $faker->randomNumber($nbDigits = 9, $strict = true) : null;

        return [
            'name' => $fullname,
            'email' =>  $email,
            'email_verified_at' => $email_verified_at,
            'password' => bcrypt('123'),
            'remember_token' => $faker->asciify('**********'), //str_random(10),
            'created_at' => $createdAt,
            'updated_at' => $updatedAt,
            'type' => $tipo,
            'active' => $activo,
            'photo' => null,
            'nif' => $nif,
            'gender' => $gender
        ];
    }

    private function insertUser($user){
    	$userInfo = new ArrayObject($user);
    	$gender = $user['gender'];
    	unset($user['gender']);
    	$newId = DB::table('users')->insertGetId($user);
    	$userInfo['id'] = $newId;
    	UsersTableSeeder::$allUsers[$newId] = $userInfo;
    	if ($user['type'] == 'u') {
    		DB::table('wallets')->insert([
    			'id' => $newId,
    			'email' => $user['email'],
    			'balance' => 0,
    			'created_at' => $user['created_at'],
    			'updated_at' => $user['updated_at'],
    		]);
    	}
    }

    private function gravarFoto($id, $file)
    {
        $targetDir = storage_path('app/'.$this->photoPath);
        //$sourceDir = database_path('seeds/fotos'); 
        $newfilename = $id . "_" . uniqid(). '.jpg';
        File::copy( $file, $targetDir.'/'.$newfilename);
        DB::table('users')->where('id', $id)->update(['photo' => $newfilename]);
        $this->command->info("Updated Photo of User $id. File $file copied as $newfilename");
    }

    private function updateFotos() {
    	$allUserIds = array_keys(UsersTableSeeder::$allUsers);
    	$totalFixos = 20;
    	while ($totalFixos > 0) {
    		$id = array_shift($allUserIds);
    		if (UsersTableSeeder::$allUsers[$id]['gender'] == 'male') {
    			$fileName = array_shift($this->files_M);
    		} else {
    			$fileName = array_shift($this->files_F);
    		}
    		$this->gravarFoto($id, $fileName);
    		$totalFixos--;    		
    	}
        shuffle($allUserIds);
        while ((count($allUserIds) > 0) && ((count($this->files_M)>0) || (count($this->files_F)>0))) {
            $id = array_shift($allUserIds); 
            $user = UsersTableSeeder::$allUsers[$id];
            if ($user['gender'] == 'male') {
                if (count($this->files_M)>0) {
                    $fileName = array_shift($this->files_M);
                    $this->gravarFoto($user["id"], $fileName);
                }
            } else {
                if (count($this->files_F)>0) {
                    $fileName = array_shift($this->files_F);
                    $this->gravarFoto($user["id"], $fileName);
                }
            }
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
            $this->numberOfUsers = 290;
        } else {
            $this->numberOfUsers = 10;
        }
        $this->command->table(['Users table seeder notice'], [
            ['Photos will be stored on path '.storage_path('app/'.$this->photoPath)]
        ]);

        $this->limparFicheirosFotos();
        $this->preencherNomesFicheirosFotos();

        $faker = \Faker\Factory::create('pt_PT');

        $this->allGenders = [];        

        for ($i=1; $i<= $this->numberOfAdmin; $i++) {        	
        	if ($i <= 2) {
        		$userRow = $this->newFakerUser($faker, 'a', $i);
        	} else {
        		$userRow = $this->newFakerUser($faker, 'a');
        	}
        	$this->insertUser($userRow);
            $this->command->info("Created User 'Administrator' - $i / " . $this->numberOfAdmin);
        }        

        for ($i=1; $i<= $this->numberOfOperator; $i++) {
        	if ($i <= 2) {
        		$userRow = $this->newFakerUser($faker, 'o', $i);
        	} else {
        		$userRow = $this->newFakerUser($faker, 'o');
        	}
        	$this->insertUser($userRow);
            $this->command->info("Created User 'Operator' - $i / " . $this->numberOfOperator);
        }        

        for ($i=1; $i<= $this->numberOfUsers; $i++) {
        	if ($i <= 5) {
        		$userRow = $this->newFakerUser($faker, 'u', $i);
        	} else {
        		$userRow = $this->newFakerUser($faker, 'u');
        	}
        	$this->insertUser($userRow);
            $this->command->info("Created User 'Platform User' - $i / " . $this->numberOfUsers);
            $this->command->info("Created Virtual Wallet - $i / " . $this->numberOfUsers);
        }    

        
        $this->updateFotos();    

    }
}
