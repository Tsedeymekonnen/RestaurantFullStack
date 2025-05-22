<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
public function up()
{
    // Update existing "Export finance" records to "Export" (or "Finance" as needed)
    DB::table('users')
        ->where('department', 'Export finance')
        ->update(['department' => 'Export']);
}

public function down()
{
    // Revert if needed
    DB::table('users')
        ->where('department', 'Export')
        ->orWhere('department', 'Finance')
        ->update(['department' => 'Export finance']);
}
};
