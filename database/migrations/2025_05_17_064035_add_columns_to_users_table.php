<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnsToUsersTable extends Migration
{
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('personal_area')->nullable()->after('password');
            $table->string('floor')->nullable()->after('personal_area');
            $table->string('department')->nullable()->after('floor');
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['personal_area', 'floor', 'department']);
        });
    }
}