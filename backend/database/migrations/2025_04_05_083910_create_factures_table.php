<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFacturesTable extends Migration
{
    public function up()
    {
        Schema::create('factures', function (Blueprint $table) {
            $table->id();
            $table->string('facture_type'); // Champ pour le type de facture
            $table->string('bordereau')->nullable();
            $table->string('fournisseur')->nullable();
            $table->string('dossier')->nullable();
            $table->string('structure')->nullable();
            $table->string('direction')->nullable();
            $table->date('date_fact')->nullable();
            $table->string('period_conso')->nullable();
            $table->string('num_fact')->nullable();
            $table->string('devise')->nullable();
            $table->decimal('montant', 10, 2)->nullable();
            $table->string('objet')->nullable();
            $table->string('num_po')->nullable();
            $table->string('status')->nullable();
            $table->string('factname')->nullable();
            $table->string('pathpdf')->nullable();
            $table->date('datereception')->nullable();
            $table->string('created_by')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('factures');
    }
}
