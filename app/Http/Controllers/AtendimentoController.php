<?php

namespace App\Http\Controllers;

use App\Models\atendimento;
use App\Models\statusAtendimento;//Model de estrangeira
use App\Models\pessoa;//Model de chave estrangeira
use App\Models\tipoAtendimento;//Model de chave estrangeira
use Illuminate\Http\Request;

class AtendimentoController extends Controller
{
    private $Atendimento;

    public function __construct(Atendimento $atendimentoModal){ 
        $this->middleware('auth'); //verificar se o usuario esta logado
        $this->atendimentoModal = $atendimentoModal;
    }

    public function index()
    {
        $alteracao=false;
        $tipoAtendimento = tipoAtendimento::where('ind_tipo','A')->get();
        $statusAtendimento = statusAtendimento::where('ind_status','A')->get();
    
        return view('form_atendimento',compact('alteracao','tipoAtendimento','statusAtendimento'));
    }

    public function create()
    {

    }

    public function show()
    {

    }

    public function store(Request $request)
    {
        $dataform = $request->all();

        if($dataform['GAB_PESSOA_cod_pessoa']==null){
            return redirect()
            ->back()
            ->with('error', 'Falha ao inserir. Por favor selecione uma pessoa cadastrada.');
        } else{
            if($dataform['ind_status'] == NULL){
                $dataform['ind_status'] = 'A';
            }
            $dataform['nom_operacao_log'] = 'INSERT';
            $dataform['nom_usuario_log'] = auth()->user()->name;
        
            try{
                $insert = $this->atendimentoModal->create($dataform);
                if ($insert)
                return redirect()
                            ->route('atendimento.index')
                            ->with('success', 'Atendimento inserido com sucesso!');
            } catch (\Exception $e) {
                // Redireciona de volta com uma mensagem de erro
                return redirect()
                    ->back()
                    ->with('error', 'Falha ao inserir');
            }
        }
    }

    public function edit($id)
    {
        $alteracao = true;
        $atendimentoC = $this->atendimentoModal->where('cod_atendimento',$id)->first();
        $tipoAtendimento = tipoAtendimento::all();
        $statusAtendimento = statusAtendimento::all();
        $dataFormatada = trim($atendimentoC['dat_atendimento']);
        $dataFormatada = date("Y-m-d",strtotime($dataFormatada));

        return view('form_atendimento',compact('alteracao','atendimentoC','tipoAtendimento','statusAtendimento','dataFormatada'));
    }

    public function update(Request $request, $id)
    {
        $atendimentoModal = Atendimento::findOrFail($id);
        
        try{
            $atendimentoModal->update($request->all());
                return redirect()
                    ->route('atendimento.index')
                    ->with('success', 'Atendimento Alterado com sucesso!');
        } catch (\Exception $e) {
            // Redireciona de volta com uma mensagem de erro
            return redirect()
                ->back()
                ->with('error', 'Falha ao inserir');
        }

    }

    public function destroy(Request $request)
    {
        $atendimentoModal = Atendimento::findOrFail($request->id_exclusao);
        //$atendimentoModal->delete();
        $inativo = array('ind_status'=> 'I','nom_usuario_log' => auth()->user()->name,'nom_operacao_log'=>'DELETE' );
        $atendimentoModal->update($inativo);
        return redirect()
                    ->route('atendimento.index')
                    ->with('success', 'Atendimento excluído com sucesso!');
    }

    /*Ajax request*/ 
    public function seleciona_pessoa(Request $request){
        $search = $request->search;

        if($search==''){
            $pessoas = pessoa::orderby('nom_nome','asc')->select('cod_pessoa','ind_pessoa','nom_nome','cod_rg','cod_ie','cod_cpf_cnpj','image')->where('ind_status','A')->limit(5)->get();
        }else{
            $pessoas = pessoa::orderby('nom_nome','asc')->select('cod_pessoa','ind_pessoa','nom_nome','cod_rg','cod_ie','cod_cpf_cnpj','image')->where('nom_nome', 'like', '%' .$search.'%')->where('ind_status','A')->limit(5)->get();
                                                                                                                            //  ('nom_nome', 'like', '%' .$search . '%') para pesquisar em qualquer parte do nome   
        }
        $response = array();
        foreach($pessoas as $pessoa){
            if($pessoa->ind_pessoa=="PF"){
                $response[] = array("value"=>$pessoa->cod_pessoa,"label"=>$pessoa->nom_nome." - CPF:".$pessoa->cod_cpf_cnpj." - RG:".$pessoa->cod_rg,"nome"=>$pessoa->nom_nome,"path_imagem"=>$pessoa->image);
            }else{
                $response[] = array("value"=>$pessoa->cod_pessoa,"label"=>$pessoa->nom_nome." - CNPJ:".$pessoa->cod_cpf_cnpj." - I.E:".$pessoa->cod_ie,"nome"=>$pessoa->nom_nome,"path_imagem"=>$pessoa->image);     
            }
        }
  
        return response()->json($response);
    }

    public function pesquisaAtendimento(Request $request,atendimento $atendimentoModel){
        $dataform = $request->except('_token');
        $Atendimento = $atendimentoModel->pesquisaLimitada($dataform);
        $Atendimento = $Atendimento->paginate(20)->onEachSide(1);
        //Passar para a função de paginação a url principal (encontrada no .env) e continuar a rota "/pessoa/pesquisa"
        $Atendimento->withPath(config('app.url')."/atendimento/pesquisaAtendimento");

        $alteracao = false;
        $mostraPesq=true;
        $tipoAtendimento = tipoAtendimento::all();
        $statusAtendimento = statusAtendimento::all();   
        return view('form_atendimento',compact('Atendimento','alteracao','mostraPesq','dataform','tipoAtendimento','statusAtendimento'));
    }

}
