<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
       User::create([
            'name'=>'Sebastian Gomez Sanchez',
            'email'=>'sebas@hotmail.com',
            'password' => bcrypt('1997Sebas')
        ])->assignRole('SuperAdministrador');
       
        User::create([
            'name'=>'Cruz Arisbel Rios Aguilar',
            'email'=>'Arisbel@hotmail.com',
            'password' => bcrypt('2022Aris')
        ]);
        User::create([
            'name'=>'Jesus Alejandro Velasquez Rios',
            'email'=>'Alex@hotmail.com',
            'password' => bcrypt('Alex2022')
        ]);
        User::create([
            'name'=>'Jose Antonio Ballinas Caballero',
            'email'=>'Jose@hotmail.com',
            'password' => bcrypt('Jose2022')
        ]);
        User::create([
            'name'=>'Maria del Carmen Gomez Perez',
            'email'=>'Carmen@hotmail.com',
            'password' => bcrypt('Carmen2022')
        ]);
        User::create([
            'name'=>'Gilmar Ivan Gomez Lopez',
            'email'=>'Gilmar@hotmail.com',
            'password' => bcrypt('Gilmar*$22')
        ]);
        User::create([
            'name'=>'Manuela Gomez Sanchez',
            'email'=>'Manuela@hotmail.com',
            'password' => bcrypt('Ma*$22s')
        ]);
        User::create([
            'name'=>'Ovidio Hernandez Jimenez',
            'email'=>'Ovidio@hotmail.com',
            'password' => bcrypt('Ov*$09.')
        ]);
        User::create([
            'name'=>'Osiris Revueltas Lopez',
            'email'=>'Osiris@hotmail.com',
            'password' => bcrypt('Osi20m0kl')
        ]);
         User::create([
            'name'=>'Blanca Irene Henandez Albores',
            'email'=>'Irene@hotmail.com',
            'password' => bcrypt('Ire22*_')
        ]);
        User::create([
            'name'=>'Erick Gonzalez Hernandez',
            'email'=>'Erick@hotmail.com',
            'password' => bcrypt('Eri9*_1')
        ]);
        User::create([
            'name'=>'Maydeth Adriana Lopez Perez',
            'email'=>'Maydeth@hotmail.com',
            'password' => bcrypt('Maydeth*_1')
        ]);
        User::create([
            'name'=>'Luis Ignacio Penagos Cruz',
            'email'=>'Ignacio@hotmail.com',
            'password' => bcrypt('IgNac*_2')
        ]);

        User::create([
        'name'=>'ALBERTO ANTONIO SILVANO MENDEZ',
        'email'=>'Alberto@hotmail.com',
        'password' => bcrypt('Alber*/32')
        ]);
        
        User::create([
            'name'=>'Susana Lopez Santiz',
            'email'=>'Susana@hotmail.com',
            'password' => bcrypt('Susa_na23')
        ]);
        //User::factory(10)->create(); //crear usuarios aleotoriamente
    }
}
