<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            // Remove the remember_token column
            $table->dropColumn('remember_token');
            // Add the refresh_token column
            $table->text('refresh_token')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            // Add the remember_token column back
            $table->rememberToken();
            // Remove the refresh_token column
            $table->dropColumn('refresh_token');
        });
    }
}
