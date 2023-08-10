<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class LoginTest extends TestCase
{
    
    private const fakePass = 'dev';

    public function testRetornoDeErroQuandoOTokenEstiverExpiradoOuInexistente(){
        
        $user = new User([
            'name' => 'dev',
            'email' => time(),
            'password' => Hash::make(self::fakePass)
        ]);

        $user->save();

        $login = $this->post('/api/login', [
            'email' => $user->email, 
            'password' => self::fakePass
        ]);

        // $this->post('/api/logout');

        // $valida = $this->get('/api/verifica', [], ['Authorization' => 'Bearer ' . $login->json()['token']]);

        // dd($valida->json());

        // $valida->assertStatus(500);

    }

}
