<?php

namespace Tests\Feature;
use App\Http\Services\ZaposleniService;
use App\Models\User;
use App\Models\Zaposleni;
use App\Models\Usluga;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

describe('ZaposleniService Unit Testovi', function () {

    beforeEach(function () {
        $this->service = new ZaposleniService();
    });

    /**
     * TEST: syncUsluge
     */
    it('može uspešno da sinhronizuje usluge za zaposlenog', function () {
        // Kreiramo zaposlenog (User + Zaposleni profil)
        $user = User::factory()->create(['type' => 'sminkerka']);
        $zaposleni = Zaposleni::factory()->create(['user_id' => $user->id]);
        
        // Kreiramo usluge
        $usluge = Usluga::factory()->count(3)->create();
        $uslugaIds = $usluge->pluck('id')->toArray();

        // Akcija
        $this->service->syncUsluge($user->id, $uslugaIds);

        // Provera baze
        expect($zaposleni->fresh()->usluge)->toHaveCount(3);
    });

    /**
     * TEST: getAllZaposleni (Filtriranje)
     */
    it('ispravno filtrira zaposlene prema imenu i tipu', function () {
        // Priprema: Ana je sminkerka, Milica je manikirka
        $ana = User::factory()->create(['ime' => 'Ana', 'type' => 'sminkerka']);
        Zaposleni::factory()->create(['user_id' => $ana->id]);

        $milica = User::factory()->create(['ime' => 'Milica', 'type' => 'manikirka']);
        Zaposleni::factory()->create(['user_id' => $milica->id]);

        // Testiranje filtera po imenu
        $rezultatIme = $this->service->getAllZaposleni(['ime' => 'Ana']);
        expect($rezultatIme->total())->toBe(1);
        expect($rezultatIme->first()->ime)->toBe('Ana');

        // Testiranje filtera po tipu
        $rezultatTip = $this->service->getAllZaposleni(['type' => 'manikirka']);
        expect($rezultatTip->total())->toBe(1);
        expect($rezultatTip->first()->type)->toBe('manikirka');
    });

    /**
     * TEST: getAllZaposleni (Sortiranje i Join)
     */
    it('ispravno sortira zaposlene po radnom stažu (JOIN test)', function () {
        // Korisnik sa 2 godine staža
        $u1 = User::factory()->create(['type' => 'sminkerka']);
        Zaposleni::factory()->create(['user_id' => $u1->id, 'radni_staz' => 2]);

        // Korisnik sa 10 godina staža
        $u2 = User::factory()->create(['type' => 'sminkerka']);
        Zaposleni::factory()->create(['user_id' => $u2->id, 'radni_staz' => 10]);

        // Tražimo sortiranje po stažu opadajuće (najiskusniji prvi)
        $rezultat = $this->service->getAllZaposleni([
            'sort_by' => 'radni_staz',
            'order' => 'desc'
        ]);

        expect($rezultat->first()->zaposleni->radni_staz)->toBe(10);
    });

    /**
     * TEST: getAllZaposleni (Napredno filtriranje)
     */
    it('može da filtrira zaposlene koji imaju više od minimalnog staža', function () {
        // Junior sa 1 godinom
        $junior = User::factory()->create(['type' => 'manikirka']);
        Zaposleni::factory()->create(['user_id' => $junior->id, 'radni_staz' => 1]);

        // Senior sa 15 godina
        $senior = User::factory()->create(['type' => 'manikirka']);
        Zaposleni::factory()->create(['user_id' => $senior->id, 'radni_staz' => 15]);

        // Tražimo one sa min 5 godina staža
        $rezultat = $this->service->getAllZaposleni(['min_staz' => 5]);

        expect($rezultat->total())->toBe(1);
        expect($rezultat->first()->zaposleni->radni_staz)->toBe(15);
    });

});