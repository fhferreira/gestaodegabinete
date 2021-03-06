<form class="form" method="post" action={{route('unidadeDocumento.update', $uniDoc)}}>
    @method("PUT")
    @csrf
        <h1 class="titulo" style="text-align:center; padding-right:10%;">Unidade Administrativa</h1>  
        <div class="cadastro-menor">
            <input id="nom_uni_doc" type="text" class="form-control col-sm-6" name="nom_uni_doc" value="{{$uniDoc->nom_uni_doc}}" required autocomplete="nom_uni_doc" autofocus >
            <label class="control-label negrito" for="radios">Status</label>
            <label for="ativo" style="margin-top:8px;margin-left:5px">
                <input type="radio" name="ind_uni_doc" id="ativo" value="A" @if($uniDoc->ind_uni_doc == 'A') checked @endif>
                    Ativo&nbsp&nbsp&nbsp
                </label>
            <label for="inativo">
                <input type="radio" name="ind_uni_doc" id="inativo" value="I" @if($uniDoc->ind_uni_doc == 'I') checked @endif>
                    Inativo
            </label>
        </div>
       
    <div style="text-align:center; padding-right:10%; padding-top:2%;">    
        <button type="submit" class="btn btn-primary alterar">Alterar</button>
        <a href="javascript:history.back()" class="btn btn-primary alterar">Voltar</a>
    </div>  
        
</form>


