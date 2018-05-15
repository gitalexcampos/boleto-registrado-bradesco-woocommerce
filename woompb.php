<?php
/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              alexbc.com.br
 * @since             1.0.0
 * @package           Woompb
 *
 * @wordpress-plugin
 * Plugin Name:       Boleto Bradesco - WooMPB
 * Plugin URI:        www.icamas.com.br
 * Description:       Plugin para integração do woocommerce com o meio de pagamento bradesco. Boleto bancário.
 * Version:           1.0.0
 * Author:            Alex
 * Author URI:        alexbc.com.br
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       woompb
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
    die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'PLUGIN_NAME_VERSION', '1.0.0' );



// Make sure WooCommerce is active
if ( ! in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
    return;
}


/**
 * Add the gateway to WC Available Gateways
 *
 * @since 1.0.0
 * @param array $gateways all available WC gateways
 * @return array $gateways all WC gateways + offline gateway
 */
function woompb_add_to_gateways( $gateways ) {
    $gateways[] = 'Woompb';
    return $gateways;
}
add_filter( 'woocommerce_payment_gateways', 'woompb_add_to_gateways' );


/**
 * Adds plugin page links
 *
 * @since 1.0.0
 * @param array $links all plugin links
 * @return array $links all plugin links + our custom links (i.e., "Settings")
 */
function woompb_plugin_links( $links ) {

    $plugin_links = array(
        '<a href="' . admin_url( 'admin.php?page=wc-settings&tab=checkout&section=woompb' ) . '">Configurar</a>'
    );

    return array_merge( $plugin_links, $links );
}
add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), 'woompb_plugin_links' );


/**
 * Meios de pagamento bradesco
 *
 * Faz a integração do woocommerce com o boleto dos Meios de pagamento Bradesco.
 * We load it later to ensure WC is loaded first since we're extending it.
 *
 * @class 		WC_Gateway_Offline
 * @extends		WC_Payment_Gateway
 * @version		1.0.0
 * @package		WooCommerce/Classes/Payment
 * @author 		Alex
 */

require_once(plugin_dir_path( __FILE__ ) . 'includes/BoletoBradesco.php');

function woompb_gateway_init() {

    class Woompb extends WC_Payment_Gateway {

        private $instructions;

        /**
         * Constructor for the gateway.
         */
        public function __construct() {

            $this->id                 = 'woompb';
            $this->icon               = apply_filters('woompb_icon', '');
            $this->has_fields         = false;
            $this->method_title       = __( 'Boleto Bradesco WooMPB', 'woompb' );
            $this->method_description = __( 'Emite os boletos do Bradesco.', 'woompb' );

            // Load the settings.
            $this->init_form_fields();
            $this->init_settings();

            // Define user set variables
            $this->title                =   $this->get_option( 'title' );
            $this->enabled              =   $this->get_option( 'enabled' );
            $this->producao             =   $this->get_option( 'producao' );
            $this->description          =   $this->get_option( 'description' );
            $this->chavedeseguranca     =   $this->get_option( 'chavedeseguranca' );
            $this->merchantid           =   $this->get_option( 'merchantid' );
            $this->beneficiario         =   $this->get_option( 'beneficiario' );
            $this->carteira             =   $this->get_option( 'carteira' );
            $this->diasvencimento       =   $this->get_option( 'diasvencimento' );
            $this->token                =   $this->get_option( 'token' );
            $this->urllogotipo          =   $this->get_option( 'urllogotipo' );
            $this->descricaopedido      =   $this->get_option( 'descricaopedido' );
            $this->instrucaolinha1      =   $this->get_option( 'instrucaolinha1' );
            $this->instrucaolinha2      =   $this->get_option( 'instrucaolinha2' );
            $this->instrucaolinha3      =   $this->get_option( 'instrucaolinha3' );
            $this->instrucaolinha4      =   $this->get_option( 'instrucaolinha4' );
            $this->mensagemcabecalho    =   $this->get_option( 'mensagemcabecalho' );
            $this->tiporenderizacao     =   $this->get_option( 'tiporenderizacao' );



                // Actions
                add_action( 'woocommerce_update_options_payment_gateways_' . $this->id, array( $this, 'process_admin_options' ) );
                add_action( 'woocommerce_thankyou_' . $this->id, array( $this, 'thankyou_page' ),20,1 );

                // Customer Emails
                add_action( 'woocommerce_email_before_order_table', array( $this, 'email_instructions' ), 10, 3 );

                //for token confirmation
                add_action( 'woocommerce_api_woompbtoken', array( $this, 'comfirm_token' ) );


        }


        /**
         * Initialize Gateway Settings Form Fields
         */
        public function init_form_fields() {

            $this->form_fields = apply_filters( 'woompb_form_fields', array(

                'enabled' => array(
                    'title'   => __( 'Enable/Disable', 'woompb' ),
                    'type'    => 'checkbox',
                    'label'   => __( 'Liga e desliga o meio de pagamento', 'woompb' ),
                    'default' => 'yes'
                ),

                'producao' => array(
                    'title'   => __( 'Trabalhar em produção?', 'woompb' ),
                    'type'    => 'checkbox',
                    'label'   => __( 'Marque para utilizar o ambiente de produção do bradesco. Deixe desmarcado para homologação.', 'woompb' ),
                    'default' => 'no'
                ),

                'title' => array(
                    'title'       => __( 'Titulo', 'woompb' ),
                    'type'        => 'text',
                    'description' => __( 'Nome do método de pagamento que aparecerá para o cliente no checkout.', 'woompb' ),
                    'default'     => __( 'Pagamento por Boleto', 'woompb' ),
                    'desc_tip'    => true,
                ),

                'description' => array(
                    'title'       => __( 'Description', 'woompb' ),
                    'type'        => 'textarea',
                    'description' => __( 'Descrição que aparecerá para o cliente no checkout. Abaixo do método de pagamento.', 'woompb' ),
                    'default'     => __( 'O boleto será exibido na próxima página, após a finalização do pedido.', 'woompb' ),
                    'desc_tip'    => true,
                ),

                'chavedeseguranca' => array(
                    'title'       => __( 'Chave de segurança', 'woompb' ),
                    'type'        => 'text',
                    'description' => __( 'Chave de segurança fornecida pelo Bradesco.', 'woompb' ),
                    'default'     => '',
                    'desc_tip'    => true,
                ),

                'merchantid' => array(
                    'title'       => __( 'Merchant ID', 'woompb' ),
                    'type'        => 'text',
                    'description' => __( 'Número da loja no bradesco.', 'woompb' ),
                    'default'     => '',
                    'desc_tip'    => true,
                ),

                'beneficiario' => array(
                    'title'       => __( 'Nome do beneficiário', 'woompb' ),
                    'type'        => 'text',
                    'description' => __( 'Nome da empresa que vai vender no boleto.', 'woompb' ),
                    'default'     => '',
                    'desc_tip'    => true,
                ),
                'carteira' => array(
                    'title'       => __( 'Número da carteira', 'woompb' ),
                    'type'        => 'text',
                    'description' => __( 'Número da carteira registrada no boleto. Ex: 26.', 'woompb' ),
                    'default'     => '',
                    'desc_tip'    => true,
                ),
                'token' => array(
                    'title'       => __( 'Token', 'woompb' ),
                    'type'        => 'text',
                    'description' => __( 'Senha para confirmar que o pedido realmente veio desta loja. Pode ser qualquer string. Esta string é inventada pelo logista. Não é fornecida pelo bradesco. Ex: stringqueinventeiagora', 'woompb' ),
                    'default'     => '',
                    'desc_tip'    => true,
                ),
                'urllogotipo' => array(
                    'title'       => __( 'Logotipo da loja', 'woompb' ),
                    'type'        => 'text',
                    'description' => __( 'Url do logotipo que aparecerá no e-mail.', 'woompb' ),
                    'default'     => '',
                    'desc_tip'    => true,
                ),
                'descricaopedido' => array(
                    'title'       => __( 'Descrição do pedido que irá aparecer no boleto', 'woompb' ),
                    'type'        => 'text',
                    'description' => __( 'ex: Pedido Realizado Na Loja X. Nº  {order_number}', 'woompb' ),
                    'default'     => 'Pedido Realizado Na Loja X. Nº  {order_number}',
                    'desc_tip'    => true,
                ),
                'diasvencimento' => array(
                    'title'       => __( 'Dias para vencimento do boleto', 'woompb' ),
                    'type'        => 'select',
                    'description' => __( 'Número de dias para o vencimento do boleto.', 'woompb' ),
                    'default'     => '',
                    'desc_tip'    => true,
                    'options' => array(
                        '1' => '1 dia',
                        '2' => '2 dias',
                        '3' => '3 dias',
                        '4' => '4 dias',
                        '5' => '5 dias',
                        '6' => '6 dias',
                        '7' => '7 dias',
                        '8' => '8 dias',
                        '9' => '9 dias',
                        '10' => '10 dias',
                        '11' => '11 dias',
                        '12' => '12 dias',
                        '13' => '13 dias',
                        '14' => '14 dias',
                        '15' => '15 dias',
                        '30' => '30 dias',
                        '45' => '45 dias',
                        '60' => '60 dias',
                        '90' => '90 dias',
                    )
                ),
                'tiporenderizacao' => array(
                    'title'       => __( 'Tipo de renderização do boleto', 'woompb' ),
                    'type'        => 'select',
                    'description' => __( 'Forma que o boleto será exibido para o cliente.', 'woompb' ),
                    'default'     => '2',
                    'desc_tip'    => true,
                    'options' => array(
                        '0' => 'Boleto em HTML',
                        '1' => 'Apenas exibir link para o pdf do boleto',
                        '2' => 'Boleto em pdf',
                    )
                ),
                'mensagemcabecalho' => array(
                    'title'       => __( 'Mensagem do cabeçalho', 'woompb' ),
                    'type'        => 'text',
                    'description' => __( 'Mensagem que aparecerá no cabeçalho do boleto para o cliente.', 'woompb' ),
                    'default'     => '',
                    'desc_tip'    => true,
                ),
                'instrucaolinha1' => array(
                    'title'       => __( 'Instrução 1', 'woompb' ),
                    'type'        => 'text',
                    'description' => __( '1º Linha de instrução fornecida para o cliente no boleto', 'woompb' ),
                    'default'     => '',
                    'desc_tip'    => true,
                ),

                'instrucaolinha2' => array(
                    'title'       => __( 'Instrução 2', 'woompb' ),
                    'type'        => 'text',
                    'description' => __( '2º Linha de instrução fornecida para o cliente no boleto', 'woompb' ),
                    'default'     => '',
                    'desc_tip'    => true,
                ),

                'instrucaolinha3' => array(
                    'title'       => __( 'Instrução 3', 'woompb' ),
                    'type'        => 'text',
                    'description' => __( '3º Linha de instrução fornecida para o cliente no boleto', 'woompb' ),
                    'default'     => '',
                    'desc_tip'    => true,
                ),

                'instrucaolinha4' => array(
                    'title'       => __( 'Instrução 4', 'woompb' ),
                    'type'        => 'text',
                    'description' => __( '4º Linha de instrução fornecida para o cliente no boleto', 'woompb' ),
                    'default'     => '',
                    'desc_tip'    => true,
                ),
            ) );
        }


        public function comfirm_token(){

            $token_recebido = $_REQUEST['token'];


            if($token_recebido == $this->token){
                header( 'HTTP/1.1 200 OK' );
            } else {
                wp_die( __( 'Token não confirmado!', 'woompb' ) );
            }

        }


        /**
         * Output for the order received page.
         */
        public function thankyou_page($order_id) {

            $order = wc_get_order( $order_id );

            $url_boleto = get_post_meta( $order->id, '_url_boleto',true);
            $linha_digitavel = get_post_meta( $order->id, '_linha_digitavel',true);


            $html = '<p>Lembre-se que o prazo de entrega só começa a valer após a confirmação de pagamento do seu boleto!</p>' .
                '<p><iframe src="' . $url_boleto . '" style="width:100%; height:1000px;"></iframe></p>' .
                '<a id="submit-payment" target="_blank" href="' . $url_boleto . '" class="button alt"' .
                ' style="font-size:1.25rem; width:75%; height:48px; line-height:24px; text-align:center;">Imprimir Boleto</a> ';
            $html2 = '<p>Você também pode pagar o boleto pela linha digitável:</p><p>' . $linha_digitavel . '</p>';

            $added_text = '<p>' . $html . '</p>' . '<p>' . $html2 . '</p>';
            echo $added_text;

        }


        /**
         * Add content to the WC emails.
         *
         * @access public
         * @param WC_Order $order
         * @param bool $sent_to_admin
         * @param bool $plain_text
         */
        public function email_instructions( $order, $sent_to_admin, $plain_text = false ) {

            if ( ! $sent_to_admin && $this->id === $order->payment_method && $order->has_status( 'on-hold' ) ) {
                $order = wc_get_order( $order->id );

                $url_boleto = get_post_meta( $order->id, '_url_boleto',true);
                $linha_digitavel = get_post_meta( $order->id, '_linha_digitavel',true);

                $texto = "Para efetuar o pagamento do boleto utilize a linha digitável a seguir: " . $linha_digitavel . " <br>Ou se preferir clique <a href='" . $url_boleto . "'>aqui para imprimi-lo</a>";

                echo wpautop( wptexturize( $texto ) ) . PHP_EOL;
            }
        }


        /**
         * Process the payment and return the result
         *
         * @param int $order_id
         * @return array
         */
        public function process_payment( $order_id ) {

            $order = wc_get_order( $order_id );

            $order_number = $order->get_order_number();
            if($order_number == '' || $order_number == null){
                $order_number = $order_id;
            }

            $boletoBradesco = new BoletoBradesco();

            $boletoBradesco
                ->setProducao((bool)$this->producao)
                ->setChaveDeSeguranca($this->chavedeseguranca) //2Hnx3EgTY8C4LcbNEDk13XQfbaR7d9IcXn4l402cx7M
                ->setMerchantId($this->merchantid) //100004015
                ->setNumeroPedido($order_number)
                ->setNossoNumero(str_pad($order_number, 11, "0", STR_PAD_LEFT))
                ->setBeneficiario($this->beneficiario)
                ->setCarteira($this->carteira)
                ->setValor((float)$order->get_total())
                ->setValorTitulo((float)$order->get_total())
                ->setDescricao(str_replace('{order_number}',$order_number,$this->descricaopedido))
                ->setDiasVencimento((int)$this->diasvencimento)
                ->setCompradorDocumento($order->billing_cpf)
                ->setCompradorEnderecoBairro($order->billing_neighborhood)
                ->setCompradorEnderecoCep($order->billing_postcode)
                ->setCompradorEnderecoCidade( $order->billing_city)
                ->setCompradorEnderecoComplemento($order->billing_address_2)
                ->setCompradorEnderecoLogradouro($order->billing_address_1)
                ->setCompradorEnderecoNumero($order->billing_number)
                ->setCompradorEnderecoUf($order->billing_state)
                ->setCompradorNome($order->billing_first_name)
                ->setInstrucaoLinha1($this->instrucaolinha1)
                ->setInstrucaoLinha2($this->instrucaolinha2)
                ->setInstrucaoLinha3($this->instrucaolinha3)
                ->setInstrucaoLinha4($this->instrucaolinha4)
                ->setMensagemCabecalho($this->mensagemcabecalho)
                ->setTipoRenderizacao((int)$this->tiporenderizacao)
                ->setTokenRequestConfirmacaoPagamento($this->token)
                ->setUrlLogotipo($this->urllogotipo);

            $boletoBradesco->emitir();

            if($boletoBradesco->emitido()){

                update_post_meta($order_id, '_url_boleto', $boletoBradesco->getUrlBoleto());
                update_post_meta($order_id, '_linha_digitavel', $boletoBradesco->getLinhaDigitavelFormatada());


                // Mark as on-hold (we're awaiting the payment)
                $order->update_status( 'on-hold', 'Aguardando o pagamento do boleto bancário.' );

                $texto = "Para efetuar o pagamento do boleto utilize a linha digitável a seguir: " . $boletoBradesco->getLinhaDigitavelFormatada() . " <br>Ou se preferir clique <a href='" . $boletoBradesco->getUrlBoleto() . "'>aqui para imprimi-lo</a>";
                $order->add_order_note(
                    $texto, true
                );

                // Reduce stock levels
                $order->reduce_order_stock();

                // Remove cart
                WC()->cart->empty_cart();

                // Return thankyou redirect
                return array(
                    'result' 	=> 'success',
                    'redirect'	=> $this->get_return_url( $order )
                );



            } else {
                error_log('Ocorreu um erro ao emitir o boleto.',3,'/var/logs/mpb.log');
                error_log("Descrição do erro: " . $boletoBradesco->getErro(),3,'/var/logs/mpb.log');
                error_log("Código de retorno do erro: " . $boletoBradesco->getCodigoRetorno() ,3,'/var/logs/mpb.log');
                error_log("Detalhes do erro: " . $boletoBradesco->getDetalhesRetorno() ,3,'/var/logs/mpb.log');

                $error = "Ocorreu um erro ao emitir o boleto. Detalhes do erro: " . $boletoBradesco->getErro();
                throw new Exception($error);


            }



        }

    }
}

add_action( 'plugins_loaded', 'woompb_gateway_init', 0 );