<?php

use App\Models\User;
use App\Models\Zaposleni;
use App\Models\Usluga;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;

uses(RefreshDatabase::class);

describe('Zaposleni i Usluge Feature Testovi', function () {

    beforeEach(function () {
        // Kreiramo vlasnicu jer ona ima pristup većini ruta
        $this->vlasnica = User::factory()->create(['type' => 'vlasnica']);
        
        // Kreiramo jednog zaposlenog za testove prikaza
        $this->userZaposleni = User::factory()->create(['ime' => 'Maja', 'type' => 'sminkerka']);
        $this->zaposleni = Zaposleni::factory()->create(['user_id' => $this->userZaposleni->id, 'radni_staz' => 5]);
    });

    /**
     * TEST: ZaposleniController @index
     */
    it('vlasnica može da vidi listu zaposlenih sa filterima', function () {
        Sanctum::actingAs($this->vlasnica);

        $response = $this->getJson('/api/vlasnica/zaposleni?ime=Maja&type=sminkerka');

        $response->assertStatus(200)
                 ->assertJsonCount(1, 'data')
                 ->assertJsonPath('data.0.ime_prezime', 'Maja ' . $this->userZaposleni->prezime);
    });

    it('zabranjuje pristup listi zaposlenih ako korisnik nije vlasnica', function () {
        $klijent = User::factory()->create(['type' => 'klijent']);
        Sanctum::actingAs($klijent);

        $response = $this->getJson('/api/vlasnica/zaposleni');

        $response->assertStatus(403);
    });

    /**
     * TEST: ZaposleniUslugaController @mojeUsluge
     */
    it('zaposleni može da vidi samo svoje usluge', function () {
        Sanctum::actingAs($this->userZaposleni);

        // Kreiramo uslugu i povežemo je sa zaposlenom
        $usluga = Usluga::factory()->create(['kategorija' => 'sminkanje']);
        $this->zaposleni->usluge()->attach($usluga->id);

        $response = $this->getJson('/api/zaposleni/moje-usluge');

        $response->assertStatus(200)
                 ->assertJsonCount(1, 'data')
                 ->assertJsonPath('data.0.naziv', $usluga->naziv);
    });

    /**
     * TEST: ZaposleniUslugaController @store (VALIDACIJA I LOGIKA)
     */
    it('vlasnica uspešno dodeljuje dozvoljene usluge zaposlenom', function () {
        Sanctum::actingAs($this->vlasnica);

        // Kreiramo uslugu koja odgovara tipu (sminkanje za sminkerku)
        $usluga = Usluga::factory()->create(['kategorija' => 'sminkanje']);

        $payload = [
            'user_id' => $this->userZaposleni->id,
            'usluge' => [$usluga->id]
        ];

        $response = $this->postJson('/api/vlasnica/zaposleni/usluge', $payload);

        $response->assertStatus(200)
                 ->assertJson(['success' => true]);

        expect($this->zaposleni->fresh()->usluge)->toHaveCount(1);
    });

    it('ne dozvoljava dodelu usluge koja ne odgovara tipu radnice (Custom Validation Test)', function () {
        Sanctum::actingAs($this->vlasnica);

        // Maja je SMINKERKA, kreiramo MANIKIR uslugu
        $manikirUsluga = Usluga::factory()->create([
            'naziv' => 'Gellac',
            'kategorija' => 'manikir'
        ]);

        $payload = [
            'user_id' => $this->userZaposleni->id,
            'usluge' => [$manikirUsluga->id]
        ];

        $response = $this->postJson('/api/vlasnica/zaposleni/usluge', $payload);

        // Proveravamo 422 Unprocessable Entity zbog tvog withValidator(after)
        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['usluge']);
        
        // Provera specifične poruke koju si definisao u Requestu
        $response->assertJsonFragment([
            "Radnica (sminkerka) ne može raditi uslugu: Gellac"
        ]);
    });

    it('vraća 404/422 ako se pokuša dodela nepostojeće usluge', function () {
        Sanctum::actingAs($this->vlasnica);

        $payload = [
            'user_id' => $this->userZaposleni->id,
            'usluge' => [999] // Nepostojeći ID
        ];

        $response = $this->postJson('/api/vlasnica/zaposleni/usluge', $payload);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['usluge.0']);
    });

    /**
     * TEST: ZaposleniUslugaController @show
     */
    it('vlasnica može da vidi usluge bilo kog zaposlenog preko ID-a', function () {
        Sanctum::actingAs($this->vlasnica);

        $response = $this->getJson("/api/vlasnica/zaposleni/{$this->userZaposleni->id}/usluge");

        $response->assertStatus(200);
    });
});