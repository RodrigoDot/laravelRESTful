<?php

use Illuminate\Database\Seeder;

class BanksTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
          ['code' => '000', 'title' => 'Banco 00'],
          ['code' => '001', 'title' => 'Banco 01'],
          ['code' => '002', 'title' => 'Banco 02'],
          ['code' => '003', 'title' => 'Banco 03'],
          ['code' => '004', 'title' => 'Banco 04'],
          ['code' => '005', 'title' => 'Banco 05'],
          ['code' => '006', 'title' => 'Banco 06'],
          ['code' => '007', 'title' => 'Banco 07'],
          ['code' => '008', 'title' => 'Banco 08'],
          ['code' => '009', 'title' => 'Banco 09'],
          ['code' => '010', 'title' => 'Banco 10'],
          ['code' => '011', 'title' => 'Banco 11'],
          ['code' => '012', 'title' => 'Banco 12'],
          ['code' => '013', 'title' => 'Banco 13'],
          ['code' => '014', 'title' => 'Banco 14'],
          ['code' => '015', 'title' => 'Banco 15'],
          ['code' => '016', 'title' => 'Banco 16'],
          ['code' => '017', 'title' => 'Banco 17'],
          ['code' => '018', 'title' => 'Banco 18'],
          ['code' => '019', 'title' => 'Banco 19'],
          ['code' => '020', 'title' => 'Banco 20'],
        ];

        DB::table('banks')->insert($data);
    }
}
