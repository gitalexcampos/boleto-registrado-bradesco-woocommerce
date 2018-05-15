<?php
include('ValidaCPFCNPJ.php');

class BoletoBradesco {

    const URL_HOMOLOCAGACAO = 'https://homolog.meiosdepagamentobradesco.com.br/apiboleto';
    const URL_PRODUCAO = 'https://meiosdepagamentobradesco.com.br/apiboleto';


    private $_numero_pedido;
    private $_valor;
    private $_descricao;


    private $_comprador_endereco_cep;
    private $_comprador_endereco_logradouro;
    private $_comprador_endereco_numero;
    private $_comprador_endereco_complemento;
    private $_comprador_endereco_bairro;
    private $_comprador_endereco_cidade;
    private $_comprador_endereco_uf;



    private $_comprador_nome;
    private $_comprador_documento;


    private $_instrucao_linha_1;
    private $_instrucao_linha_2;
    private $_instrucao_linha_3;
    private $_instrucao_linha_4;
    private $_instrucao_linha_5;
    private $_instrucao_linha_6;
    private $_instrucao_linha_7;
    private $_instrucao_linha_8;
    private $_instrucao_linha_9;
    private $_instrucao_linha_10;
    private $_instrucao_linha_11;
    private $_instrucao_linha_12;



    private $_beneficiario;
    private $_carteira;
    private $_nosso_numero;
    private $_valor_titulo;
    private $_url_logotipo;
    private $_mensagem_cabecalho;
    private $_tipo_renderizacao;


    private $_merchant_id;
    private $_token_request_confirmacao_pagamento;

    private $_dias_vencimento;
    private $_chave_de_seguranca;
    private $_producao;


    private $_emitido;    //boleto já foi registrado?  1 - Sim
                                                    // 2 - Não
    private $_linha_digitavel;
    private $_linha_digitavel_formatada;
    private $_url_boleto;
    private $_codigo_retorno;
    private $_mensagem_retorno;
    private $_detalhes_retorno;

    private $_erro;

    /**
     * @return mixed
     */
    public function getNumeroPedido()
    {
        return $this->_numero_pedido;
    }

    /**
     * @param mixed $numero_pedido
     */
    public function setNumeroPedido($numero_pedido)
    {
        $this->_numero_pedido = $numero_pedido;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getValor()
    {
        return $this->_valor;
    }

    /**
     * @param mixed $valor
     */
    public function setValor($valor)
    {
        $valor = $this::limparNumero($valor);
        $this->_valor = $valor;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getDescricao()
    {
        return $this->_descricao;
    }

    /**
     * @param mixed $descricao
     */
    public function setDescricao($descricao)
    {
        $descricao = $this::limparString($descricao);
        $this->_descricao = $descricao;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getCompradorEnderecoCep()
    {
        return $this->_comprador_endereco_cep;
    }

    /**
     * @param mixed $comprador_endereco_cep
     */
    public function setCompradorEnderecoCep($comprador_endereco_cep)
    {
        $comprador_endereco_cep = str_replace('-','',$comprador_endereco_cep);
        $this->_comprador_endereco_cep = $comprador_endereco_cep;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getCompradorEnderecoLogradouro()
    {

        return $this->_comprador_endereco_logradouro;
    }

    /**
     * @param mixed $comprador_endereco_logradouro
     */
    public function setCompradorEnderecoLogradouro($comprador_endereco_logradouro)
    {
        $comprador_endereco_logradouro = $this::limparString($comprador_endereco_logradouro);
        $this->_comprador_endereco_logradouro = $comprador_endereco_logradouro;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getCompradorEnderecoNumero()
    {
        return $this->_comprador_endereco_numero;
    }

    /**
     * @param mixed $comprador_endereco_numero
     */
    public function setCompradorEnderecoNumero($comprador_endereco_numero)
    {
        $this->_comprador_endereco_numero = $comprador_endereco_numero;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getCompradorEnderecoComplemento()
    {

        return $this->_comprador_endereco_complemento;
    }

    /**
     * @param mixed $comprador_endereco_complemento
     */
    public function setCompradorEnderecoComplemento($comprador_endereco_complemento)
    {
        $comprador_endereco_complemento = $this::limparString($comprador_endereco_complemento);
        $this->_comprador_endereco_complemento = $comprador_endereco_complemento;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getCompradorEnderecoBairro()
    {
        return $this->_comprador_endereco_bairro;
    }

    /**
     * @param mixed $comprador_endereco_bairro
     */
    public function setCompradorEnderecoBairro($comprador_endereco_bairro)
    {
        $comprador_endereco_bairro = $this::limparString($comprador_endereco_bairro);
        $this->_comprador_endereco_bairro = $comprador_endereco_bairro;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getCompradorEnderecoCidade()
    {
        return $this->_comprador_endereco_cidade;
    }

    /**
     * @param mixed $comprador_endereco_cidade
     */
    public function setCompradorEnderecoCidade($comprador_endereco_cidade)
    {
        $comprador_endereco_cidade = $this::limparString($comprador_endereco_cidade);
        $this->_comprador_endereco_cidade = $comprador_endereco_cidade;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getCompradorEnderecoUf()
    {
        return $this->_comprador_endereco_uf;
    }

    /**
     * @param mixed $comprador_endereco_uf
     */
    public function setCompradorEnderecoUf($comprador_endereco_uf)
    {
        $this->_comprador_endereco_uf = $comprador_endereco_uf;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getCompradorNome()
    {
        return $this->_comprador_nome;
    }

    /**
     * @param mixed $comprador_nome
     */
    public function setCompradorNome($comprador_nome)
    {
        $comprador_nome = $this::limparString($comprador_nome);
        $this->_comprador_nome = $comprador_nome;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getCompradorDocumento()
    {
        return $this->_comprador_documento;
    }

    /**
     * @param mixed $comprador_documento
     */
    public function setCompradorDocumento($comprador_documento)
    {
        $comprador_documento = str_replace(array('-','.','/'),array('','',''),$comprador_documento);
        $this->_comprador_documento = $comprador_documento;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getInstrucaoLinha1()
    {
        return $this->_instrucao_linha_1;
    }

    /**
     * @param mixed $instrucao_linha_1
     */
    public function setInstrucaoLinha1($instrucao_linha_1)
    {
        $instrucao_linha_1 = $this::limparString($instrucao_linha_1);
        $this->_instrucao_linha_1 = $instrucao_linha_1;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getInstrucaoLinha2()
    {
        return $this->_instrucao_linha_2;
    }

    /**
     * @param mixed $instrucao_linha_2
     */
    public function setInstrucaoLinha2($instrucao_linha_2)
    {
        $instrucao_linha_2 = $this::limparString($instrucao_linha_2);
        $this->_instrucao_linha_2 = $instrucao_linha_2;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getInstrucaoLinha3()
    {
        return $this->_instrucao_linha_3;
    }

    /**
     * @param mixed $instrucao_linha_3
     */
    public function setInstrucaoLinha3($instrucao_linha_3)
    {
        $instrucao_linha_3 = $this::limparString($instrucao_linha_3);
        $this->_instrucao_linha_3 = $instrucao_linha_3;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getInstrucaoLinha4()
    {
        return $this->_instrucao_linha_4;
    }

    /**
     * @param mixed $instrucao_linha_4
     */
    public function setInstrucaoLinha4($instrucao_linha_4)
    {
        $instrucao_linha_4 = $this::limparString($instrucao_linha_4);
        $this->_instrucao_linha_4 = $instrucao_linha_4;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getInstrucaoLinha5()
    {
        return $this->_instrucao_linha_5;
    }

    /**
     * @param mixed $instrucao_linha_5
     */
    public function setInstrucaoLinha5($instrucao_linha_5)
    {
        $instrucao_linha_5 = $this::limparString($instrucao_linha_5);
        $this->_instrucao_linha_5 = $instrucao_linha_5;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getInstrucaoLinha6()
    {
        return $this->_instrucao_linha_6;
    }

    /**
     * @param mixed $instrucao_linha_6
     */
    public function setInstrucaoLinha6($instrucao_linha_6)
    {
        $instrucao_linha_6 = $this::limparString($instrucao_linha_6);
        $this->_instrucao_linha_6 = $instrucao_linha_6;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getInstrucaoLinha7()
    {
        return $this->_instrucao_linha_7;
    }

    /**
     * @param mixed $instrucao_linha_7
     */
    public function setInstrucaoLinha7($instrucao_linha_7)
    {
        $instrucao_linha_7 = $this::limparString($instrucao_linha_7);
        $this->_instrucao_linha_7 = $instrucao_linha_7;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getInstrucaoLinha8()
    {
        return $this->_instrucao_linha_8;
    }

    /**
     * @param mixed $instrucao_linha_8
     */
    public function setInstrucaoLinha8($instrucao_linha_8)
    {
        $instrucao_linha_8 = $this::limparString($instrucao_linha_8);
        $this->_instrucao_linha_8 = $instrucao_linha_8;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getInstrucaoLinha9()
    {
        return $this->_instrucao_linha_9;
    }

    /**
     * @param mixed $instrucao_linha_9
     */
    public function setInstrucaoLinha9($instrucao_linha_9)
    {
        $instrucao_linha_9 = $this::limparString($instrucao_linha_9);
        $this->_instrucao_linha_9 = $instrucao_linha_9;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getInstrucaoLinha10()
    {
        return $this->_instrucao_linha_10;
    }

    /**
     * @param mixed $instrucao_linha_10
     */
    public function setInstrucaoLinha10($instrucao_linha_10)
    {
        $instrucao_linha_10 = $this::limparString($instrucao_linha_10);
        $this->_instrucao_linha_10 = $instrucao_linha_10;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getInstrucaoLinha11()
    {
        return $this->_instrucao_linha_11;
    }

    /**
     * @param mixed $instrucao_linha_11
     */
    public function setInstrucaoLinha11($instrucao_linha_11)
    {
        $instrucao_linha_11 = $this::limparString($instrucao_linha_11);
        $this->_instrucao_linha_11 = $instrucao_linha_11;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getInstrucaoLinha12()
    {
        return $this->_instrucao_linha_12;
    }

    /**
     * @param mixed $instrucao_linha_12
     */
    public function setInstrucaoLinha12($instrucao_linha_12)
    {
        $instrucao_linha_12 = $this::limparString($instrucao_linha_12);
        $this->_instrucao_linha_12 = $instrucao_linha_12;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getBeneficiario()
    {
        return $this->_beneficiario;
    }

    /**
     * @param mixed $beneficiario
     */
    public function setBeneficiario($beneficiario)
    {
        $beneficiario = $this::limparString($beneficiario);
        $this->_beneficiario = $beneficiario;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getCarteira()
    {
        return $this->_carteira;
    }

    /**
     * @param mixed $carteira
     */
    public function setCarteira($carteira)
    {
        $this->_carteira = $carteira;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getNossoNumero()
    {
        return $this->_nosso_numero;
    }

    /**
     * @param mixed $nosso_numero
     */
    public function setNossoNumero($nosso_numero)
    {
        $this->_nosso_numero = $nosso_numero;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getValorTitulo()
    {
        return $this->_valor_titulo;
    }

    /**
     * @param mixed $valor_titulo
     */
    public function setValorTitulo($valor_titulo)
    {
        $valor_titulo = $this::limparNumero($valor_titulo);
        $this->_valor_titulo = $valor_titulo;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getUrlLogotipo()
    {
        return $this->_url_logotipo;
    }

    /**
     * @param mixed $url_logotipo
     */
    public function setUrlLogotipo($url_logotipo)
    {
        $this->_url_logotipo = $url_logotipo;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getMensagemCabecalho()
    {
        return $this->_mensagem_cabecalho;
    }

    /**
     * @param mixed $mensagem_cabecalho
     */
    public function setMensagemCabecalho($mensagem_cabecalho)
    {
        $mensagem_cabecalho = $this::limparString($mensagem_cabecalho);
        $this->_mensagem_cabecalho = $mensagem_cabecalho;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getTipoRenderizacao()
    {
        return $this->_tipo_renderizacao;
    }

    /**
     * @param mixed $tipo_renderizacao
     */
    public function setTipoRenderizacao($tipo_renderizacao)
    {
        $this->_tipo_renderizacao = $tipo_renderizacao;
        return $this;
    }



    /**
     * @return integer
     */
    public function getMerchantId()
    {
        return $this->_merchant_id;
    }

    /**
     * @param integer $merchant_id
     */
    public function setMerchantId($merchant_id)
    {
        $this->_merchant_id = $merchant_id;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getTokenRequestConfirmacaoPagamento()
    {
        return $this->_token_request_confirmacao_pagamento;
    }

    /**
     * @param mixed $token_request_confirmacao_pagamento
     */
    public function setTokenRequestConfirmacaoPagamento($token_request_confirmacao_pagamento)
    {
        $this->_token_request_confirmacao_pagamento = $token_request_confirmacao_pagamento;
        return $this;
    }


    /**
     * @return integer
     */
    public function getDiasVencimento()
    {
        return $this->_dias_vencimento;
    }

    /**
     * @param integer $dias_vencimento
     */
    public function setDiasVencimento($dias_vencimento)
    {
        $this->_dias_vencimento = $dias_vencimento;
        return $this;
    }

    /**
     * @return string
     */
    public function getChaveDeSeguranca()
    {
        return $this->_chave_de_seguranca;
    }

    /**
     * @param string $chave_de_seguranca
     */
    public function setChaveDeSeguranca($chave_de_seguranca)
    {
        $this->_chave_de_seguranca = $chave_de_seguranca;
        return $this;
    }

    /**
     * @return bool
     */
    public function getProducao()
    {
        return $this->_producao;
    }

    /**
     * @param bool $producao
     */
    public function setProducao($producao)
    {
        $this->_producao = $producao;
        return $this;
    }

    /**
     * @return bool
     */
    public function emitido()
    {
        return $this->_emitido;
    }

    /**
     * @param bool $registrado
     */
    public function setEmitido($emitido)
    {
        $this->_emitido = $emitido;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getLinhaDigitavel()
    {
        return $this->_linha_digitavel;
    }

    /**
     * @param mixed $linha_digitavel
     */
    public function setLinhaDigitavel($linha_digitavel)
    {
        $this->_linha_digitavel = $linha_digitavel;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getLinhaDigitavelFormatada()
    {
        return $this->_linha_digitavel_formatada;
    }

    /**
     * @param mixed $linha_digitavel_formatada
     */
    public function setLinhaDigitavelFormatada($linha_digitavel_formatada)
    {
        $this->_linha_digitavel_formatada = $linha_digitavel_formatada;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getUrlBoleto()
    {
        return $this->_url_boleto;
    }

    /**
     * @param mixed $url_boleto
     */
    public function setUrlBoleto($url_boleto)
    {
        $this->_url_boleto = $url_boleto;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getCodigoRetorno()
    {
        return $this->_codigo_retorno;
    }

    /**
     * @param mixed $codigo_retorno
     */
    public function setCodigoRetorno($codigo_retorno)
    {
        $this->_codigo_retorno = $codigo_retorno;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getMensagemRetorno()
    {
        return $this->_mensagem_retorno;
    }

    /**
     * @param mixed $mensagem_retorno
     */
    public function setMensagemRetorno($mensagem_retorno)
    {
        $this->_mensagem_retorno = $mensagem_retorno;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getDetalhesRetorno()
    {
        return $this->_detalhes_retorno;
    }

    /**
     * @param mixed $detalhes_retorno
     */
    public function setDetalhesRetorno($detalhes_retorno)
    {
        $this->_detalhes_retorno = $detalhes_retorno;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getErro()
    {
        return $this->_erro;
    }

    /**
     * @param mixed $erro
     */
    public function setErro($erro)
    {
        $this->_erro = $erro;
        return $this;
    }



    public function __construct()
    {
        $this->setEmitido(0);
        $this->setErro('');
    }

    public function validate(){
        //verifica campos obrigatórios e os valida
        if(!is_bool($this->getProducao())){
            $this->setErro('Parâmetro inválido para para setProducao. true para produção e false para homologação.');
        }

        if($this->getChaveDeSeguranca() == ''){
            $this->setErro('A chave de segurança não foi informada. Utilizar setChaveDeSeguranca()');
        }

        if($this->getMerchantId() == ''){
            $this->setErro('O campo obrigatório merchantid não foi informado. Utilizar setMerchantId()');
        }

        if(!is_numeric($this->getNumeroPedido())){
            $this->setErro('O campo Número do pedido deve ser numérico. Utilizar setNumeroPedido()');
        }


        if(strlen($this->getNossoNumero()) != 11){
            $this->setErro('O campo Nosso número deve conter 11 digítos. Utilizar setNossoNumero()');
        }

        if(!is_numeric($this->getNossoNumero())){
            $this->setErro('O campo Nosso número deve ser numérico. Utilizar setNossoNumero()');
        }


        if($this->getBeneficiario() == ''){
            $this->setErro('O campo Beneficiário é obrigatório. Utilizar setBeneficiario()');
        }

        if($this->getCarteira() == ''){
            $this->setErro('O campo Carteira é obrigatório. Utilizar setCarteira()');
        }

        if($this->getValor() == ''){
            $this->setErro('O campo Valor é obrigatório. Utilizar setValor()');
        }

        if(!is_numeric($this->getValor())){
            $this->setErro('O campo Valor é deve ser numerico. Utilizar setValor()');
        }

        if($this->getValorTitulo() == ''){
            $this->setErro('O campo Valor Título é obrigatório. Utilizar setValorTitulo()');
        }

        if(!is_numeric($this->getValorTitulo())){
            $this->setErro('O campo Valor Título é deve ser numerico. Utilizar setValorTitulo()');
        }


        if($this->getDescricao() == ''){
            $this->setErro('O campo Descrição é obrigatório. Use alguma coisa como "Boleto para pagamento do pedido 00001". Utilizar setDescricao()');
        }

        if(!is_integer($this->getDiasVencimento())){
            $this->setErro('O campo Dias para o vencimento deve ser um valor inteiro. Utilizar setDiasVencimento()');
        }

        if($this->getDiasVencimento() == ''){
            $this->setErro('O campo Dias para o vencimento é Obrigatório. Utilizar setDiasVencimento()');
        }

        if($this->getCompradorDocumento() == '') {
            $this->setErro('O campo Comprador Documento é Obrigatório. Utilizar setCompradorDocumento()');
        }

        // Cria um objeto sobre a classe
        $cpf_cnpj = new ValidaCPFCNPJ($this->getCompradorDocumento());



        // Verifica se o CPF ou CNPJ é válido
        if ( !$cpf_cnpj->valida() ) {
            $this->setErro('O cpf ou cnpj do campo CompradorDocumento é inválido. Utilizar setCompradorDocumento()');
        }

        if($this->getCompradorEnderecoBairro() == ''){
            $this->setErro('O campo Comprador Bairro é obrigatório. Utilizar setCompradorEnderecoBairro()');
        }

        if($this->getCompradorEnderecoCep() == ''){
            $this->setErro('O campo Comprador Cep é obrigatório. Utilizar setCompradorEnderecoCep()');
        }

        if($this->getCompradorEnderecoCidade() == ''){
            $this->setErro('O campo Comprador Cidade é obrigatório. Utilizar setCompradorEnderecoCidade()');
        }

        /*if($this->getCompradorEnderecoComplemento() == ''){
            $this->setErro('O campo Comprador Complemento é obrigatório. Utilizar setCompradorEnderecoComplemento()');
        }*/

        if($this->getCompradorEnderecoLogradouro() == ''){
            $this->setErro('O campo Comprador Logradouro é obrigatório. Utilizar setCompradorEnderecoLogradouro()');
        }

        if($this->getCompradorEnderecoNumero() == ''){
            $this->setErro('O campo Comprador Número é obrigatório. Utilizar setCompradorEnderecoNumero()');
        }

        if($this->getCompradorEnderecoUf() == ''){
            $this->setErro('O campo Comprador UF é obrigatório. Utilizar setCompradorEnderecoUf()');
        }

        if($this->getCompradorNome() == ''){
            $this->setErro('O campo Comprador Nome é obrigatório. Utilizar setCompradorNome()');
        }

        if($this->getTipoRenderizacao() == ''){
            $this->setErro('O campo Tipo Renderizacao deve é obrigatório. Utilizar 1 para html e 2 para pdf setTipoRenderizacao()');
        }

        if(!is_numeric($this->getTipoRenderizacao())){
            $this->setErro('O campo Tipo Renderizacao deve ser numerico. Utilizar 1 para html e 2 para pdf setTipoRenderizacao()');
        }

        if($this->getTokenRequestConfirmacaoPagamento() == ''){
            $this->setErro('O campo Token para confirmação do pagamento é obrigatório. Ele pode ser qualquer string. Utilizar setTokenRequestConfirmacaoPagamento()');
        }

        if($this->getUrlLogotipo() == ''){
            $this->setErro('O campo URL do logotipo é obrigatório.  Utilizar setUrlLogotipo()');
        }
        $this->getUrlLogotipo();

        return true;
    }


    public function limparNumero($numero){
        return str_replace(array('-',',','(',')', ''), array('','.','','',''), $numero);
    }

    public function limparString($string){
        $string = $this->normalizar($string);
        $string = strtoupper($string);
        return $string;
    }

    function normalizar($string) {
        $a = 'ÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖØÙÚÛÜÝÞßàáâãäåæçèéêëìíîïðñòóôõöøùúûýýþÿŔŕ|';
        $b = 'aaaaaaaceeeeiiiidnoooooouuuuybsaaaaaaaceeeeiiiidnoooooouuuyybyRr/';
        $string = utf8_decode($string);
        $string = strtr($string, utf8_decode($a), $b);
        $string = strtolower($string);
        return utf8_encode($string);
    }

    public function formatarValor($valor){

        return $valor;
    }

    public function MontarJson(){


        $data_service_pedido = array(
            "numero"    => $this->getNumeroPedido(),
            "valor"     => number_format($this->getValor(),2,"",""),
            "descricao" => $this->getDescricao());



        $data_service_comprador_endereco = array(
            "cep"           => $this->getCompradorEnderecoCep(),
            "logradouro"    => $this->getCompradorEnderecoLogradouro(),
            "numero"        => $this->getCompradorEnderecoNumero(),
            "complemento"   => $this->getCompradorEnderecoComplemento(),
            "bairro"        => $this->getCompradorEnderecoBairro(),
            "cidade"        => $this->getCompradorEnderecoCidade(),
            "uf"            => $this->getCompradorEnderecoUf());



        $data_service_comprador = array(
            "nome"          => $this->getCompradorNome(),
            "documento"     => $this->getCompradorDocumento(),
            "endereco"      => $data_service_comprador_endereco,
            "ip"            => $_SERVER["REMOTE_ADDR"],
            "user_agent"    => $_SERVER["HTTP_USER_AGENT"]);

        $data_service_boleto_registro = null;



        $data_service_boleto_instrucoes = array(
            "instrucao_linha_1"     => $this->getInstrucaoLinha1(),
            "instrucao_linha_2"     => $this->getInstrucaoLinha2(),
            "instrucao_linha_3"     => $this->getInstrucaoLinha3(),
            "instrucao_linha_4"     => $this->getInstrucaoLinha4(),
            "instrucao_linha_5"     => $this->getInstrucaoLinha5(),
            "instrucao_linha_6"     => $this->getInstrucaoLinha6(),
            "instrucao_linha_7"     => $this->getInstrucaoLinha7(),
            "instrucao_linha_8"     => $this->getInstrucaoLinha8(),
            "instrucao_linha_9"     => $this->getInstrucaoLinha9(),
            "instrucao_linha_10"    => $this->getInstrucaoLinha10(),
            "instrucao_linha_11"    => $this->getInstrucaoLinha11(),
            "instrucao_linha_12"    => $this->getInstrucaoLinha12());


        $data_vencimento =  "86400" * $this->getDiasVencimento() + mktime(0,0,0,date('m'),date('d'),date('Y'));
        $data_vencimento = date ("Y-m-d", $data_vencimento);



        $data_service_boleto = array(
            "beneficiario"      => $this->getBeneficiario(),
            "carteira"          => $this->getCarteira(),
            "nosso_numero"      => $this->getNossoNumero(), //precisa ter 11 digitos
            "data_emissao"      => date ("Y-m-d"),
            "data_vencimento"   => $data_vencimento,
            "valor_titulo"      => number_format($this->getValorTitulo(),2,"",""),
            "url_logotipo"      => $this->getUrlLogotipo(),
            "mensagem_cabecalho"=> $this->getMensagemCabecalho(),
            "tipo_renderizacao" => $this->getTipoRenderizacao(),
            "instrucoes"        => $data_service_boleto_instrucoes,
            "registro"          => $data_service_boleto_registro);



        $data_service_request = array(
            "merchant_id"       => $this->getMerchantId(),
            "meio_pagamento"    => "300",
            "pedido"            => $data_service_pedido,
            "comprador"         => $data_service_comprador,
            "boleto"            => $data_service_boleto,
            "token_request_confirmacao_pagamento" => $this->getTokenRequestConfirmacaoPagamento());

        $data_post = json_encode($data_service_request, JSON_UNESCAPED_SLASHES);


        return $data_post;

    }

    public function emitir(){

        if(!$this->validate()){
            return $this;
        }

        $jsonBoleto     =   $this->MontarJson();

        $jsonRetorno    =   $this->registrarNoBradesco($jsonBoleto);

        if($this->emitido()){
            $this->setErro('O boleto já foi emitido!');
        }


        if($this->getErro() == ''){

            $json  =  json_decode($jsonRetorno);


            if($json->status->codigo == '0'){
                $this->setEmitido(1);
                $this->setUrlBoleto($json->boleto->url_acesso);
                $this->setLinhaDigitavel($json->boleto->linha_digitavel);
                $this->setLinhaDigitavelFormatada($json->boleto->linha_digitavel_formatada);

            } else {
                $this->setEmitido(0);
                $this->setErro($json->status->mensagem);
            }

            $this->setCodigoRetorno($json->status->codigo);
            $this->setMensagemRetorno($json->status->mensagem);
            $this->setDetalhesRetorno($json->status->detalhes);

        }


        return $this;
    }


    private function registrarNoBradesco($json){

        $jsonRetorno        =   '';

        if($this->getProducao()){
            $url = $this::URL_PRODUCAO."/transacao";
        } else {
            $url = $this::URL_HOMOLOCAGACAO."/transacao";
        }



        $headers = array();
        $headers[] = "Accept: application/json";
        $headers[] = "Accept-Charset: UTF-8";
        $headers[] = "Accept-Encoding:  application/json";
        $headers[] = "Content-Type: application/json; charset=UTF-8";
        $AuthorizationHeader = $this->getMerchantId().":". $this->getChaveDeSeguranca();
        $AuthorizationHeaderBase64 = base64_encode($AuthorizationHeader);
        $headers[] = "Authorization: Basic ".$AuthorizationHeaderBase64;


        try{
            $ch = curl_init();
            if ( is_resource( $ch ) ){
                curl_setopt($ch, CURLOPT_URL, $url);
                curl_setopt($ch, CURLOPT_POST, 1);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
                curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);


                $jsonRetorno    = curl_exec($ch);
                $ern            = curl_errno($ch); //numero do erro
                $err            = curl_error($ch); //mensagem do erro
                $header         = curl_getinfo($ch);
                curl_close( $ch );


                if ( (bool) $ern ) {
                    $this->setErro('Ocorreu um erro ao enviar pedido por curl:' . $err);
                }
            } else {
                $this->setErro('Ocorreu um erro ao carregar o módulo curl');
            }
        } catch (Exception $e){
            $this->setErro($e);
        }


        return $jsonRetorno;

    }
}