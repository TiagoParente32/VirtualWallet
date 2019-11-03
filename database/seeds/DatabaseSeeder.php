<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public static $seedType = "small";
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->command->info("-----------------------------------------------");
        $this->command->info("START of database seeder");
        $this->command->info("-----------------------------------------------");

        DatabaseSeeder::$seedType = $this->command->choice('What is the size of seed data (choose "full" for publishing)?', ['small', 'full'], 0);

        DB::statement("SET foreign_key_checks=0");

        DB::table('users')->delete();
        DB::table('categories')->delete();
        DB::table('wallets')->delete();
        DB::table('movements')->delete();

        DB::statement('ALTER TABLE users AUTO_INCREMENT = 0');
        DB::statement('ALTER TABLE categories AUTO_INCREMENT = 0');
        DB::statement('ALTER TABLE wallets AUTO_INCREMENT = 0');
        DB::statement('ALTER TABLE movements AUTO_INCREMENT = 0');
        
        DB::statement("SET foreign_key_checks=1");


        $this->call(CategoriesTableSeeder::class);
        $this->call(UsersTableSeeder::class);
        $this->call(MovementsTableSeeder::class);

        $this->command->info("-----------------------------------------------");
        $this->command->info("END of database seeder");
        $this->command->info("-----------------------------------------------");
    }
}
