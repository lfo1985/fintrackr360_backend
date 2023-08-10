<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ClientTest extends TestCase
{

    private const headers = ['token' => 'aloha'];

    private function requisicaoGravacao($dados){
        return $this->post('/api/clientes', $dados, self::headers);
    }

    public function testNaoPermitirGravarClienteComEmailIncorreto(){

        $request = $this->requisicaoGravacao([
            'nome' => 'Luiz Fernando de Oliveira',
            'email' => 'lo1985'
        ]);

        $request
            ->assertStatus(400)
            ->assertJson([
                'sucesso' => false,
                'msg' => 'E-mail incorreto!'
            ]);

    }

    public function testNaoPermitirCadastrarUsuariosComNomeEmBranco(){

        $request = $this->requisicaoGravacao([
            'nome' => '',
            'email' => ''
        ]);

        $request
            ->assertStatus(400)
            ->assertJson([
                'sucesso' => false,
                'msg' => 'Nome obrigatório!'
            ]);
    }

    public function testNaoPermitirCadastrarUsuariosComEmailEmBranco(){

        $request = $this->requisicaoGravacao([
            'nome' => 'Fernando',
            'email' => ''
        ]);

        $request
            ->assertStatus(400)
            ->assertJson([
                'sucesso' => false,
                'msg' => 'E-mail obrigatório!'
            ]);
    }

    public function testRealizarGravacaoDosDadosDoClienteNormalSemErros(){

        $request = $this->requisicaoGravacao([
            'nome' => 'João do Caminhão',
            'email' => 'joaozinho23232@gmail.com'
        ]);

        $request->assertStatus(200);
        
    }

}
