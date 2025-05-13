<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class QuestionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('questions')->insert([
        ['question' => 'What is your name?', 'answer' => 'My Name Is Laravel Agent'],
        ['question' => 'How are you?', 'answer' => 'I am fine, Thank You'],
        ['question' => 'Where You From?', 'answer' => 'Bangladesh'],
    ]);
    }
}
