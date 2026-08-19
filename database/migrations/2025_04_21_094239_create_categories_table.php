<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CreateCategoriesTable extends Migration
{
    public function up()
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->timestamps();
        });

        // Insert default categories
        DB::table('categories')->insert([
            ['name' => 'Berita Nasional dan Internasional'],
            ['name' => 'Ekonomi dan Bisnis'],
            ['name' => 'Teknologi dan Sains'],
            ['name' => 'Olahraga'],
            ['name' => 'Hiburan dan Lifestyle'],
            ['name' => 'Politik dan Hukum'],
        ]);
    }

    public function down()
    {
        Schema::dropIfExists('categories');
    }
}
