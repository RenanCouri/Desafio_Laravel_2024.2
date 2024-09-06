<?php

namespace App\Http\Controllers;

use App\Http\Requests\SaqueDepositoRequest;
use App\Http\Requests\TransferenciaRequest;
use App\Models\Conta;
use App\Models\Pendencia;
use App\Models\Transacao;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Hash;

class TransacaoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user=$request->user();
        $transacoes=$user->conta->transacoesSelecionadas();

       $conta=$user->conta;
        return view('transacoes.index',compact('transacoes','conta'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function transferencia()
    {
        return view('transacoes.transferencia');
    }
    public function saque_deposito(){
        return view('transacoes.saque_deposito');
    }
    public function sacar_ou_depositar(SaqueDepositoRequest $request){
        $dados=$request->only(['valor']);
        $conta=Conta::query()->where('numero_conta',$request->conta)->where('numero_agencia',$request->agencia)->get()[0];
        $atual=$request->user();
        if($conta===null || $request->senha_alvo!==$conta->senha)
           return redirect('/saque_deposito')->withErrors('Conta não encontrada. Verifique-se de que os dados estão corretos');
        if( $atual->cargo==='gerente' && !($atual->id!=$conta->user_id || !$atual->getUsuariosComuns()->contains(User::find($conta->id))))
           return redirect('/saque_deposito')->withErrors('Você não tem permissão para fazer saques ou depósitos nessa conta');
        
        if(!Hash::check($request->senha_agente,$request->user()->password))
           return redirect('/saque_deposito')->withErrors('Crendenciais de cadastro inválidas');
        $dados['autoridade_id']=$atual->id;
        $dados['conta_destinatario_id']=$dados['conta_remetente_id']=$conta->id;
        $dados['esta_pendente']=false;
        if($request->tipo==='saque')
           return $this->sacar($conta,$dados);
        else
          return $this->depositar($conta,$dados);
    }
    public function sacar(Conta $conta,$dados)
    {
        if($conta->sacar($dados['valor'])){
        $dados['tipo']='saque';

        
           $transacao= Transacao::create($dados);
           return redirect('saque_deposito')->with('sucesso','Operação realizada com sucesso');
        }   
        else{
           return redirect('saque_deposito')->withErrors('Valor de saque é maior que o saldo disponível na conta');
        }
        
    }
    public function depositar(Conta $conta,$dados)
    {
        if($conta->depositar($dados['valor'])){
       
        $dados['tipo']='deposito';
   
        Transacao::create($dados);
        
         
        
       }
       return redirect('saque_deposito')->with('sucesso','Operação realizada com sucesso');
    }
    public function requerirTransferencia(TransferenciaRequest $request){
        
        $dados=$request->only(['valor']);
        $dados['tipo']='transferencia';
        $dados['esta_pendente']=true;
        $contaRem=$request->user()->conta;
        if($request->senha_alvo!==$contaRem->senha)
          return redirect('/transferencia')->withErrors('Dados da conta não estão corretos');
        $contaDes=Conta::query()->where('numero_conta',$request->conta)->where('numero_agencia',$request->agencia)->get()[0];
        $dados['conta_destinatario_id']=$contaDes->id;
        $dados['conta_remetente_id']=$contaRem->id;
    
        if($contaRem->saldo>=$request->valor )
        {
            $mensagem="";
           $transacao= Transacao::create($dados);
           if($contaRem->limite_transferencias<$request->valor)
           {
            $mensagem="Valor de transferência excede o limite. Ficará a cargo do gerente responsável aprová-la ou nâo. 
            Estes são os dados de contato: \nemail:{$request->user()->usuario_responsavel->email}\ttelefone:{$request->user()->usuario_responsavel->numero_telefone}";
            Pendencia::create(['titulo'=>'Valor de Transferência excede os limites',
                                'tipo'=>'transferencia',
                                'foi_resolvida'=>false,
                                'transacao_id'=>$transacao->id,
                                'emprestimo_id'=>null,
                                'autoridade_id'=>$request->user()->usuario_responsavel_id

            ]);
           }
           else{
            $mensagem="Transferência realizada com sucesso";
            $transacao->esta_pendente=false;
            $transacao->save();
            $this->transferir($contaRem,$contaDes,$request->valor);
            }
            return redirect('/transferencia')->with('sucesso',$mensagem);
        }
        return redirect('/transferencia')->withErrors('Valor de transferência excede o saldo da conta');
    }
    public function realizarTransferenciaPendente(Transacao $transferencia){
        
        if($transferencia===null )
           return null;
        $transferencia->esta_pendente=false;
        $transferencia->save();
        $this->transferir(Conta::find($transferencia->conta_remetente_id),Conta::find($transferencia->conta_destinatario_id),$transferencia->valor);
        
    }
    private function transferir(Conta $contaRem, Conta $contaDes, int $valor){
        if($contaRem!==null && $contaDes!==null){
            if($contaRem->sacar($valor))
               $contaDes->depositar($valor);
        }
    }
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Transacao $transacao)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Transacao $transacao)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Transacao $transacao)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Transacao $transacao)
    {
        //
    }
}
