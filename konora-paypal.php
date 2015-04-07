<?php
/**
 * Plugin Name: Konora Paypal
 * Plugin URI: http://blog.konora.com/plugin/
 * Description: Personalizza il tuo pagamento con paypal
 * Version: 1.6
 * Author: Konora ltd
 * Author URI: http://www.konora.com
 * License: GPLv2 or later
 */
/*
 * @todo: 
  v aggiustare allineamnete visualizzazione dei file
  v creare tab per migliorare la visualizzazione
  v Visualizzazione campi non allineata
  v Servizio creato da konora ( in alto a destra)
 * 
 * Aggiungere effetto grafico page loader
 * Problema radio button visualizzazione scelta tipo di pagamento
 * Aggiungere personalizzazione della scritta del page loader
 * Per l'IVA migliorare con la %
 * Eliminazione dei file
 * Copia il link assoluto
 * Editor del file * Risolvere problema dell'editor
 * Video Tutorial
 * Aggiungere tasto per usare il plugin senza konora
 * Aggiungere pubblicità a konora
 * Aggiungere konora come contributor
 * Problema con i caratteri utf accentati
 * Il javascript fuinziona solo sulla prima riga!
 * Pagamento con stripe


 */

//$plugin = plugin_basename(__FILE__);
//define('PLUGIN_DIR', dirname(__FILE__) . '/');
//define('FILE_DIR', $upload_dir['baseurl']);

define('KPP_ICON', plugins_url('images/KPP.ico', __FILE__));

add_action('admin_init', 'k_paypal_plugin_admin_init');
add_action('admin_menu', 'k_paypal_add_page');

function k_paypal_plugin_admin_init() {
    /* Register our stylesheet. */
    wp_register_style('k_paypal_PluginStylesheet', plugins_url('css/k-paypal-admin-style.css', __FILE__));
    wp_register_style('fontawesome_min', plugins_url('css/font-awesome.min.css', __FILE__));
    wp_register_style('fontawesome', plugins_url('css/font-awesome.css', __FILE__));
    wp_register_style('invkp_css', plugins_url('css/invoicekingpro-styles.css', __FILE__));
    wp_register_style('invkp_jquery_ui', plugins_url('css/jquery-ui.css', __FILE__));
    wp_enqueue_script('query', plugins_url("js/jQuery.js", __FILE__), array(), '1.0.0', true);
    //    $wnm_custom = array( 'template_url' => get_bloginfo('template_url') );
    $query_url = plugins_url('konora-paypal.php', __FILE__);
    $wnm_custom = array('template_url' => $query_url);
    wp_localize_script('query', 'wnm_custom', $wnm_custom);
}

function k_paypal_add_page() {
    $k_paypal_page = add_menu_page('K Paypal', 'KPP', 'manage_options', 'k_paypal_options', 'kpp_option_help', KPP_ICON);
    /* Using registered $page handle to hook stylesheet loading */
    add_action('admin_print_styles-' . $k_paypal_page, 'k_paypal_plugin_admin_styles');
}

function k_paypal_plugin_admin_styles() {
    /*
     * It will be called only on your plugin admin page, enqueue our stylesheet here
     */
    wp_enqueue_style('invkp_css');
    wp_enqueue_style('k_paypal_PluginStylesheet');
    wp_enqueue_style('fontawesome_min');
    wp_enqueue_style('fontawesome');
    wp_enqueue_style('invkp_jquery_ui');
}

register_activation_hook(__FILE__, 'kpp_install');

function kpp_install() {
    $path = get_kpp_upload_folder('/kpp_files/');
}

/**
 * Get basedir of subfolder in UPLOAD folder
 *
 * @param type $sub_dir
 *
 * @return type
 */
function get_kpp_upload_folder($sub_dir = '', $auto_create = true) {
    $upload_dir = wp_upload_dir();
    if (is_array($upload_dir) && isset($upload_dir['basedir'])) {
        $upload_dir = $upload_dir['basedir'];
    } else {
        $upload_dir = WP_CONTENT_DIR . '/uploads';
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir);
        }
    }
    if ($auto_create && !is_dir($upload_dir . $sub_dir)) {
        mkdir($upload_dir . $sub_dir, 0777, true);
    }

    return $upload_dir . $sub_dir;
}

function kpp_option_help() {
    ?>
    <div class="kpp_admin">
        <div class="title">
            <h1 style="margin-left: 25%;">Crea il tuo file Paypal <em>v1.6</em></h1><h1 style="margin-left: 25%;"></h1>
        </div>

        <div class="kpp_block filled">
            <div class="kpp-list-group">
                <h4>Trovato un problema? Posta il probelma sul 
                    <a href="#" target="_blank">Forum di supporto</a>. Se preferisci, inviami un'email direttamente a 
                    <a href="mailto:roberto@konora.com">roberto@konora.com</a>       
<!--
                    <a class="list-group-item" href="#"><i class="fa fa-home fa-fw"></i>&nbsp; Home</a>
                    <a class="list-group-item" href="#"><i class="fa fa-book fa-fw"></i>&nbsp; Library</a>
                    <a class="list-group-item" href="#"><i class="fa fa-pencil fa-fw"></i>&nbsp; Applications</a>
                    <a class="list-group-item" href="#"><i class="fa fa-cog fa-fw"></i>&nbsp; Settings</a>
-->
                </h4>
            </div>
        </div>
        <div class="logo">
            <div id="kpp_social">
                <div class="kpp_social facebook"><a href="https://www.facebook.com/KonoraItalia?fref=ts" target="_blank">
                        <i class="fa fa-facebook"></i> <span class="kpp_width"><span class="kpp_opacity">Facebook</span></span></a></div>
                <div class="kpp_social linkedin"><a href="https://www.linkedin.com/company/konora" target="_blank">
                        <i class="fa fa-linkedin"></i> <span class="kpp_width"><span class="kpp_opacity">Linkedin</span></span></a></div>
                <div class="kpp_social google"><a href="https://plus.google.com/+Konora/videos" target="_blank">
                        <i class="fa fa-google-plus"></i> <span class="kpp_width"><span class="kpp_opacity">Google+</span></span></a></div>
                <div class="kpp_social konora"><a href="http://www.konora.com" target="_blank">
                        <i class="fa fa-heart"></i> <span class="kpp_width"><span class="kpp_opacity">Konora</span></span></a></div>
            </div>
            <!--<img width="46" src="<?php // echo plugins_url('images/konora-logo-icona.png', __FILE__)        ?>"/>-->
        </div>


        <div class="main">
            <ul class="tabs">
                <li>
                    <input type="radio" checked name="tabs" id="tab1">
                    <label for="tab1">File Creati</label>
                    <div id="tab-content1" class="tab-content">
                        <div class="animated  fadeInRight">
                            <div id="file_scann">
                                <?php scan_kpp_files(); ?>
                            </div>
                        </div>
                    </div>
                </li>
                <li>
                    <input type="radio" name="tabs" id="tab2">
                    <label for="tab2">Crea Nuovo File</label>
                    <div id="tab-content2" class="tab-content">
                        <div class="animated  fadeInRight">
                            <div id="settings_files" style="margin-left: 1%; margin-top: 1%;">

                                <form method="post">

                                    <!-- - Configurazioni Generale - -->        

                                    <div class="Config_box">
                                        <hr>
                                        <p><em><strong>Configurazioni Generale</strong></em></p>
                                        <hr>
                                        <?php
                                        if (isset($_POST['kpp_nome_file'])) {
                                            $kpp_nome_file = $_POST['kpp_nome_file'];
                                        } else {
                                            $kpp_nome_file = "";
                                        }
                                        ?>

                                        <div class="Config_box_item"> 
                                            <p class="left">
                                                <label for="kpp_nome_file">Nome del file </label>
                                            </p>
                                            <p class="right">
                                                <input type="text" name="kpp_nome_file" value="<?php echo $kpp_nome_file; ?>" required placeholder="Nome file"/> 
                                                <em>(Nome del file che si vuole creare. <strong>ATTENZIONE se il file esiste viene sovrascritto con le nuove informazioni</strong>)</em>
                                            </p>
                                        </div>

                                        <?php
                                        if (isset($_POST['kpp_email_paypal'])) {
                                            $kpp_email_paypal = $_POST['kpp_email_paypal'];
                                        } else {
                                            $kpp_email_paypal = "";
                                        }
                                        ?>
                                        <div class="Config_box_item"> 
                                            <p class="left">
                                                <label for="kpp_email_paypal">Email di paypal </label>
                                            </p>
                                            <p class="right">
                                                <input type="text" name="kpp_email_paypal" value="<?php echo $kpp_email_paypal; ?>" required placeholder="Paypal email"/> 
                                                <em>(email di paypal attiva)</em>
                                            </p>      
                                        </div>

                                        <?php
                                        if (isset($_POST['kpp_nome_prodotto'])) {
                                            $kpp_nome_prodotto = $_POST['kpp_nome_prodotto'];
                                        } else {
                                            $kpp_nome_prodotto = "";
                                        }
                                        ?>
                                        <div class="Config_box_item"> 
                                            <p class="left">
                                                <label for="kpp_nome_prodotto">Nome prodotto </label>
                                            </p>
                                            <p class="right">
                                                <input type="text" name="kpp_nome_prodotto" value="<?php echo $kpp_nome_prodotto; ?>" required placeholder="Nome prodotto"/> 
                                                <em>(Nome del prodotto che comprerà su paypal)</em>
                                            </p>      
                                            </p>
                                        </div>
                                    </div>

                                    <!-- - Subscription - -->         

                                    <div class="Config_box">
                                        <hr>
                                        <p><em><strong>Configurazioni Sottoscrizione</strong></em></p>
                                        <hr>

                                        <div class="Config_box_item2"> 

                                            <?php
                                            $kpp_subscription_1 = $_POST['kpp_subscription_1'];
                                            if (empty($kpp_subscription_1)) {
                                                $kpp_subscription_1 = "one_shot";
                                                $_POST['kpp_subscription_1'] = "one_shot";
                                            } else {
                                                $kpp_subscription_1 = get_option('kpp_subscription_1');
                                            }
                                            ?>

                                            <p><input id="payment_radio" type="radio" id="1" name="kpp_subscription_1" 
                                                      <?php if ($_POST['kpp_subscription_1'] == 'one_shot') echo 'checked'; ?> value="one_shot" />one_shot.
                                                <em>(Pagamento unico Una Tantum)</em></p>
                                            <p><input id="payment_radio" type="radio" id="2" name="kpp_subscription_1" 
                                                      <?php if ($_POST['kpp_subscription_1'] == 'promo_2_mesi') echo 'checked'; ?> value="promo_2_mesi" />promo_2_mesi.
                                                <em>(Pagamento con promozione di due mesi)</em></p>
                                            <p><input id="payment_radio" type="radio" id="3" name="kpp_subscription_1" 
                                                      <?php if ($_POST['kpp_subscription_1'] == 'promo_1_mese') echo 'checked'; ?> value="promo_1_mese" />promo_1_mese.
                                                <em>(Pagamento con promozione di un mese)</em></p>
                                            <p><input id="payment_radio" type="radio" id="4" name="kpp_subscription_1" 
                                                      <?php if ($_POST['kpp_subscription_1'] == 'no_promo') echo 'checked='; ?> value="no_promo" />no_promo.
                                                <em>(Pagamento unico ricorrente mensile)</em></p>

                                            <hr>

                                            <?php
                                            if (isset($_POST['kpp_importo_a3'])) {
                                                $kpp_importo_a3 = $_POST['kpp_importo_a3'];
                                            } else {
                                                $kpp_importo_a3 = "";
                                            }
                                            ?>
                                            <div class="Config_box_item">                       
                                                <p class="left">
                                                    <label for="kpp_importo_a3">Importo fisso oppure one shot</label>
                                                </p>
                                                <p class="right">
                                                    <input type="text" name="kpp_importo_a3" value="<?php echo $kpp_importo_a3; ?>" required placeholder="Importo (eg.: 11.00)"/> 
                                                    <em>(prezzo ricorrente fisso oppure <strong>one shot (Una Tantum)</strong> NOTA: inserisci sempre il punto "." (eg.: 11.00)</em>
                                                </p>                        
                                            </div>

                                            <?php
                                            if (isset($_POST['kpp_importo_a1'])) {
                                                $kpp_importo_a1 = $_POST['kpp_importo_a1'];
                                            } else {
                                                $kpp_importo_a1 = "";
                                            }
                                            ?>
                                            <div class="Config_box_item">                        
                                                <p class="left">
                                                    <label for="kpp_importo_a1">Importo del primo mese</label>
                                                </p>
                                                <p class="right">
                                                    <input type="text" name="kpp_importo_a1" value="<?php echo $kpp_importo_a1; ?>"/>
                                                    <em>(Importo del primo mese come periodo di prova o trial)</em>
                                                </p>                        
                                            </div>

                                            <?php
                                            if (isset($_POST['kpp_importo_a2'])) {
                                                $kpp_importo_a2 = $_POST['kpp_importo_a2'];
                                            } else {
                                                $kpp_importo_a2 = "";
                                            }
                                            ?>
                                            <div id="Config_box_item">
                                                <p class="left">
                                                    <label for="kpp_importo_a2">Importo del secondo mese</label>
                                                </p>
                                                <p class="right">
                                                    <input type="text" name="kpp_importo_a2" value="<?php echo $kpp_importo_a2; ?>"/>
                                                    <em>(Importo del secondo mese di prova)</em>
                                                </p>                       
                                            </div>

                                        </div>
                                    </div>

                                    <!-- - redirect - -->  

                                    <div class="Config_box">
                                        <hr>
                                        <p><em><strong>Configurazioni dei redirect</strong></em></p>
                                        <hr>

                                        <div class="Config_box_item2"> 
                                            <?php
                                            if (isset($_POST['kpp_cancel_return'])) {
                                                $kpp_cancel_return = $_POST['kpp_cancel_return'];
                                            } else {
                                                $kpp_cancel_return = "";
                                            }
                                            ?>
                                            <div id="Config_box_item">
                                                <p class="left">
                                                    <label for="kpp_cancel_return">Url pagina rescue </label>
                                                </p>
                                                <p class="right">
                                                    <input type="text" name="kpp_cancel_return" value="<?php echo $kpp_cancel_return; ?>"/>
                                                    <em>(Url della pagina su cui gli utenti vengono reindirizzati se qualcosa va storto con il pagamento)</em>
                                                </p>
                                            </div>

                                            <?php
                                            if (isset($_POST['kpp_return'])) {
                                                $kpp_return = $_POST['kpp_return'];
                                            } else {
                                                $kpp_return = "";
                                            }
                                            ?>
                                            <div id="Config_box_item">
                                                <p class="left">
                                                    <label for="kpp_return">Url pagina di ringraziamento </label>
                                                </p>
                                                <p class="right">
                                                    <input type="text" name="kpp_return" value="<?php echo $kpp_return; ?>" required placeholder="Url thanks page"/>
                                                    <em>(Url della pagina a cui gli utenti ritornano dopo aver effettuato il pagamento)</em>
                                                </p>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- - speciali - -->  

                                    <div class="Config_box">
                                        <hr>
                                        <p><em><strong>Configurazioni Speciali</strong></em></p>
                                        <hr>

                                        <div class="Config_box_item2"> 
                                            <?php
                                            if (isset($_POST['kpp_iva'])) {
                                                $kpp_iva = $_POST['kpp_iva'];
                                            } else {
                                                $kpp_iva = "";
                                            }
                                            ?>
                                            <div id="Config_box_item">
                                                <p class="left">
                                                    <label for="kpp_iva">IVA </label>
                                                </p>
                                                <p class="right">
                                                    <input type="text" name="kpp_iva" value="<?php echo $kpp_iva; ?>"/>
                                                    <em>(Inserisci l'importo dell'IVA se vuoi visualizzarla separatamente su paypal)</em>
                                                </p>
                                            </div>

                                            <?php
                                            if (isset($_POST['kpp_shipping'])) {
                                                $kpp_shipping = $_POST['kpp_shipping'];
                                            } else {
                                                $kpp_shipping = "";
                                            }
                                            ?>
                                            <div id="Config_box_item">
                                                <p class="left">
                                                    <label for="kpp_shipping">Shipping ? </label>
                                                </p>
                                                <p class="right">
                                                    <input type="checkbox"  name="kpp_shipping" id="kpp_shipping" <?php if (isset($_POST['kpp_shipping'])) echo 'checked="checked"'; ?>>
                                                    <em>(Spunta il box se vuoi vedere comparire le informazioni per la spedizione durante il processo di pagamento)</em>
                                                </p>
                                            </div>

                                        </div>
                                    </div>

                                    <!-- - campo-custom - -->  

                                    <div class="Config_box">

                                        <hr>
                                        <p><em><strong>Configurazioni Campo Custom - Konora</strong></em></p>
                                        <p><em><strong>(Usa questi campi solo se intendi integrare il pagamento con <a href="http://konora.com/">Konora.com</a>)</strong></em></p>
                                        <hr>

                                        <div class="Config_box_item2"> 

                                            <?php
                                            if (isset($_POST['kpp_user'])) {
                                                $kpp_user = $_POST['kpp_user'];
                                            } else {
                                                $kpp_user = "";
                                            }
                                            ?>
                                            <div id="Config_box_item">
                                                <p class="left">
                                                    <label for="kpp_user">User @: </label>
                                                </p>
                                                <p class="right">
                                                    <input type="text" name="kpp_user" value="<?php echo $kpp_user; ?>"required placeholder="Email konora"/> 
                                                    <em>(Email dell'Utente di konora...)</em>
                                                </p>
                                            </div>

                                            <?php
                                            if (isset($_POST['kpp_circle'])) {
                                                $kpp_circle = $_POST['kpp_circle'];
                                            } else {
                                                $kpp_circle = "";
                                            }
                                            ?>
                                            <div id="Config_box_item">
                                                <p class="left">
                                                    <label for="kpp_circle">Circolo di iscrizione: </label>
                                                </p>
                                                <p class="right">
                                                    <input type="text" name="kpp_circle" value="<?php echo $kpp_circle; ?>"required placeholder="Circolo Iscritti Konora"/> 
                                                    <em>(Circolo nel quale verranno iscritti gli utenti che hanno completato il pagamento)</em>
                                                </p>
                                            </div>

                                            <?php
                                            if (isset($_POST['kpp_service'])) {
                                                $kpp_service = $_POST['kpp_service'];
                                            } else {
                                                $kpp_service = "";
                                            }
                                            ?>
                                            <div id="Config_box_item">
                                                <p class="left">
                                                    <label for="kpp_service">Circolo di service: </label>
                                                </p>
                                                <p class="right">
                                                    <input type="text" name="kpp_service" value="<?php echo $kpp_service; ?>"/>
                                                    <em>(Descrizione manca...)</em>
                                                </p>
                                            </div>
                                            <?php
                                            if (isset($_POST['kpp_tipo'])) {
                                                $kpp_tipo = $_POST['kpp_tipo'];
                                            } else {
                                                $kpp_tipo = "";
                                            }
                                            ?>
                                            <div id="Config_box_item">
                                                <p class="left">
                                                    <label for="kpp_tipo">Tipo: </label>
                                                </p>
                                                <p class="right">
                                                    <input type="text" name="kpp_tipo" value="<?php echo $kpp_tipo; ?>"/>
                                                    <em>(Descrizione manca...)</em>
                                                </p>
                                            </div>

                                        </div>
                                    </div>

                                    <br/><br/>
                                    <!-- - campo-custom - -->  

                                    <div class="Config_box">
                                        <div id="Config_box_item">
                                            <hr>
                                            <p class="submit">
                                                <input type="submit" id="round" name="k_paypal_create_btn" class="button button-primary" value="Crea">
                                            </p>
                                            <hr>
                                            <div id="crea_file">
                                                <?php
                                                /*
                                                 * Chiamata alla funzione di creazione del file php
                                                 */

                                                if (isset($_POST['k_paypal_create_btn'])) {

                                                    k_paypal_create_btn();

                                                    $_POST['k_paypal_create_btn'] = "";
                                                } else {
                                                    $_POST['k_paypal_create_btn'] = "";
                                                    echo '<div class="warning"><i class="fa fa-exclamation-triangle fa-lg"></i> <strong>Nessun file ancora creato</strong></div>';
                                                }
                                                ?>
                                            </div>
                                        </div>
                                    </div>
                                    <?php // submit_button();          ?>
                                </form>
                                <!--
                                                            <input type="hidden" name="shipping" value="1.00">
                                                            <input type="hidden" name="fallback_insurance_amount" value="1">
                                                            <input type="hidden" name="shipping2" value="1.00">
                                                            <input type="hidden" name="handling" value="1.00">
                                                            <input type="hidden" name="tax" value="1.00">
                                -->


                            </div>


                        </div>
                    </div>
                </li>
                <li>
                    <input type="radio" name="tabs" id="tab3">
                    <label for="tab3">Informazioni</label>
                    <div id="tab-content3" class="tab-content">
                        <div class="animated  fadeInRight ">
                            "Con questo plugin puoi creare facilmente il tuo file .php per creare un link diretto a paypal per qualsiasi tuo servizio"
                            <br/><br/>
                            Nasce principalmente per integrare i pagamenti paypal con i Circoli di Konora (Piattaforma di Digital Marketing).<br/><br/>
                            Nel tempo svilupperemo altre integrazioni con altri processori di pagamento oltre Paypal<br/><br/>
                            Per qualsiasi suggerimento puoi mandare un'email a roberto@konora.com<br/><br/>
                            Per avere informazioni sulla <a href="http://www.konora.com">Piattaforma Konora clicca qui</a>.</div>
                        <div class="kpp_plugin">
                            <img src="<?php echo plugins_url('images/kpp_akp.jpg', __FILE__); ?>" alt="Ad King Pro" />
                            <span class="title_b">Konora</span>
                            <span class="description">Digital Marketing</span>
                            <span class="links"><a href="http://www.konora.com" class="thickbox" title="More information about Konora">SITO WEB</a></span>
                        </div>
                    </div>
                </li>
            </ul>
        </div>  
    </div>    
    <?php
}

function scan_kpp_files() {

    /*
     * Scansione delle directory dei file salvati
     *  genera la lista dei file creati precedentemente
     * 
     */

    echo '<br>Elenco dei file creati precedentemente su <em>' . $_SERVER['SERVER_NAME'] . '</em><br/><br/>'
    . '';


    echo '<div class="ElencoFiles">';
    echo '<div class="row">';
    echo '<div class="col col1">';
    echo 'n°';
    echo '</div>';
    echo '<div class="col col2">';
    echo 'Nome del file';
    echo '</div>';
    echo '<div class="col col3">';
    echo 'link';
    echo '</div>';
    echo '<div class="col col4">';
    echo 'editor';
    echo '</div>';
    echo '<div class="col col5">';
    echo 'copia link';
    echo '</div>';
    echo '<div class="col col6">';
    echo 'elimina file';
    echo '</div>';
    echo '</div>';

    $upload_dir = wp_upload_dir();
    $url = $upload_dir['url'] . '../../kpp_files/';
    $root = get_kpp_upload_folder('/kpp_files/');

    $style_forms = Array();

    if ($directory_handle = opendir($root)) {

        //Scorro l'oggetto fino a quando non è termnato cioè false
        $i = 0;

        while (($file = readdir($directory_handle)) !== false) {

            $path = get_kpp_upload_folder('/kpp_files/') . $file;

            if ((!is_dir($path)) & ($file != ".") & ($file != "..")) {

                list($name, $extension) = explode('.', $file);

                if (($name != "") and ( $extension == "php")) {

                    $file_url = $upload_dir['url'] . '/../../kpp_files/' . $file;

//                    $dominio = $_SERVER['SERVER_NAME'];

                    if (is_multisite()) {
                        $wp_editor_link = network_admin_url();
                    } else {
                        $wp_editor_link = admin_url();
                    }
//                     echo '<br> $wp_editor_link => '.$wp_editor_link;
//                    $sub_url = "plugin-editor.php?file=konora-paypal%2Ffiles%2F";
//                    $end_url = "&plugin=konora-paypal%2Fkonora-paypal.php";

                    echo '<div class="row">';
                    echo '<div class="col col1">';
                    echo $i++;
                    echo '</div>';
                    echo '<div class="col col2">';
                    echo '<a href="' . $file_url . '"> <i class="fa fa-file-text-o"></i> -- ' . $file . '</a> ';
                    echo '</div>';
                    echo '<div class="col col3">';
                    echo '<a alt="link" href="' . $file_url . '"> <i class="fa fa-link fa-lg"></i></a><div class="image b">Tasto dx mouse,<br> copia link ;)</div> ';
                    echo '</div>';
                    echo '<div class="col col4">';
//                      echo '<a alt="editor" href="http://' . $dominio . $wp_folder . $sub_url . $file . $end_url . '"> <img alt="editor" class="icon" width="14" src="'
                    echo '<a alt="editor" href="#"> <i class="fa fa-pencil-square-o fa-lg"></i></a><div class="image r">Coming soon...</div>';
                    echo '</div>';
                    echo '<div class="col col5">';
                    echo '<a alt="editor" href="#"> <div id="test"><div id="link">' . $file_url . '</div><i class="fa fa-files-o fa-lg"></i></div></a>';
                    echo '</div>';
                    echo '<div class="col col6">';

                    echo '<a alt="editor" href="#"> <div id="delete"><div id="link">' . $file_url . '</div><i class="fa fa-times fa-lg"></i></a><div class="image r">Coming soon...</div>';
                    echo '</div>';
                    echo '</div>';
                }
            }
        }

//Chiudo la lettura della directory.
        closedir($directory_handle);
        if (isset($konora_form_style)) {
            update_shortcode('style', $konora_form_style);
        }
    }
    echo '</div>';
}

function check_file_exist($file_to_check) {

    $upload_dir = wp_upload_dir();
    $url = $upload_dir['url'] . '/kpp_files/';
    $root = get_kpp_upload_folder('/kpp_files/');

    $find = FALSE;

    $style_forms = Array();

    $file_to_check = $file_to_check . ".php";

    if ($directory_handle = opendir($root)) {

//Scorro l'oggetto fino a quando non è termnato cioè false
        $i = 0;

        while (($file = readdir($directory_handle)) !== false) {

            $path = get_kpp_upload_folder('/kpp_files/') . $file;

            if ((!is_dir($path)) & ($file != ".") & ($file != "..")) {

                list($name, $extension) = explode('.', $file);

                if (($name != "") and ( $extension == "php")) {

                    if ($file == $file_to_check) {
                        echo '<script language="javascript">';
                        echo 'alert("Il file ' . $file_to_check . ' esiste già!")';
                        echo '</script>';
                        $find = TRUE;
                    }
                }
            }
        }

//Chiudo la lettura della directory.
        closedir($directory_handle);
        if (isset($konora_form_style)) {
            update_shortcode('style', $konora_form_style);
        }
    }

    return $find;
}

function k_paypal_create_btn() {
    $Error = "";
    $kpp_nome_file = sanitize_text_field($_POST['kpp_nome_file']);
    $kpp_email_paypal = sanitize_text_field($_POST['kpp_email_paypal']);
    $kpp_nome_prodotto = sanitize_text_field($_POST['kpp_nome_prodotto']);
    $kpp_importo_a1 = sanitize_text_field($_POST['kpp_importo_a1']);
    $kpp_importo_a2 = sanitize_text_field($_POST['kpp_importo_a2']);
    $kpp_importo_a3 = sanitize_text_field($_POST['kpp_importo_a3']);
    $kpp_subscription_1 = sanitize_text_field($_POST['kpp_subscription_1']);

//    $kpp_shipping = $_POST['kpp_shipping'];
    $kpp_iva = sanitize_text_field($_POST['kpp_iva']);

    $kpp_cancel_return = sanitize_text_field($_POST['kpp_cancel_return']);
    $kpp_return = sanitize_text_field($_POST['kpp_return']);

    $kpp_circle = sanitize_text_field($_POST['kpp_circle']);
    $kpp_user = sanitize_text_field($_POST['kpp_user']);
    $kpp_service = sanitize_text_field($_POST['kpp_service']);
    $kpp_tipo = sanitize_text_field($_POST['kpp_tipo']);

//    $path = plugin_dir_path(__FILE__) . 'files/';
    $upload_dir = wp_upload_dir();
    $url = $upload_dir['url'] . '/kpp_files/';
    $path = get_kpp_upload_folder('/kpp_files/');

    $file_url = $url . $kpp_nome_file . '.php';

    echo '<i class="fa fa-cog fa-spin"></i> Creazione file in corso.. ';

    if (check_file_exist($kpp_nome_file)) {
        
    } else {
        switch ($kpp_subscription_1):
            case "one_shot":
//            echo '<br> $kpp_subscription_1 => ' . $kpp_subscription_1;

                $text_subscription = ""
                        . "\n"
                        . "<input type=\"hidden\" name=\"amount\" value=\"" . $kpp_importo_a3 . "\">\n"
                        . "\n";
                $_xclick = "_xclick";
                break;
            case "promo_2_mesi":
//            echo '<br> $kpp_subscription_1 => ' . $kpp_subscription_1;

                $text_subscription = ""
                        . "\n"
                        . "<input type=\"hidden\" name=\"a1\" value=\"" . $kpp_importo_a1 . "\">\n"
                        . " <input type=\"hidden\" name=\"p1\" value=\"1\">\n"
                        . "<input type=\"hidden\" name=\"t1\" value=\"M\">\n"
                        . "\n"
                        . "<input type=\"hidden\" name=\"a2\" value=\"" . $kpp_importo_a2 . "\">\n"
                        . "<input type=\"hidden\" name=\"p2\" value=\"1\">\n"
                        . "<input type=\"hidden\" name=\"t2\" value=\"M\">\n"
                        . "\n"
                        . "<input type=\"hidden\" name=\"a3\" value=\"" . $kpp_importo_a3 . "\">\n"
                        . "<input type=\"hidden\" name=\"p3\" value=\"1\">\n"
                        . "<input type=\"hidden\" name=\"t3\" value=\"M\">\n"
                        . "\n"
                        . "<input type=\"hidden\" name=\"src\" value=\"1\">\n"
                        . "<input type=\"hidden\" name=\"sra\" value=\"1\">\n"
                        . "\n";
                $_xclick = "_xclick-subscriptions";
                break;
            case "promo_1_mese":
//            echo '<br> $kpp_subscription_1 => ' . $kpp_subscription_1;

                $text_subscription = ""
                        . "\n"
                        . "<input type=\"hidden\" name=\"a1\" value=\"" . $kpp_importo_a1 . "\">\n"
                        . "<input type=\"hidden\" name=\"p1\" value=\"1\">\n"
                        . "<input type=\"hidden\" name=\"t1\" value=\"M\">\n"
                        . "\n"
                        . "<input type=\"hidden\" name=\"a3\" value=\"" . $kpp_importo_a3 . "\">\n"
                        . "<input type=\"hidden\" name=\"p3\" value=\"1\">\n"
                        . "<input type=\"hidden\" name=\"t3\" value=\"M\">\n"
                        . "\n"
                        . "<input type=\"hidden\" name=\"src\" value=\"1\">\n"
                        . "<input type=\"hidden\" name=\"sra\" value=\"1\">\n"
                        . "\n";
                $_xclick = "_xclick-subscriptions";
                break;
            case "no_promo":
//            echo '<br> $kpp_subscription_1 => ' . $kpp_subscription_1;

                $text_subscription = ""
                        . "\n"
                        . "<input type=\"hidden\" name=\"a3\" value=\"" . $kpp_importo_a3 . "\">\n"
                        . "<input type=\"hidden\" name=\"p3\" value=\"1\">\n"
                        . "<input type=\"hidden\" name=\"t3\" value=\"M\">\n"
                        . "\n"
                        . "<input type=\"hidden\" name=\"src\" value=\"1\">\n"
                        . "<input type=\"hidden\" name=\"sra\" value=\"1\">\n"
                        . "\n";
                $_xclick = "_xclick-subscriptions";
                break;
            default:
                echo '<br> $kpp_subscription_1 => ERRORE';
        endswitch;

        if (isset($_POST['kpp_shipping'])) {
            $text_shipping = "";
        } else {
            $text_shipping = "" . "<input type=\"hidden\" name=\"no_shipping\" value=\"1\">\n";
        }
        if ($kpp_iva != "") {
            $text_iva = "<input type=\"hidden\" name=\"tax\" value=\"" . $kpp_iva . "\">";
        } else {
            $text_iva = "";
        }

        $kpp_myfile = fopen($path . $kpp_nome_file . ".php", "w") or die("Unable to open file!");
        $txt = "<form action=\"https://www.paypal.com/cgi-bin/webscr\" method=\"post\" target=\"_top\" name=\"frm\">\n"
                . "<input type=\"hidden\" name=\"business\" value=\"" . $kpp_email_paypal . "\">\n"
                . "\n"
                . "<input type=\"hidden\" name=\"cmd\" value=\"" . $_xclick . "\">\n"
                . "<input type=\"hidden\" name=\"lc\" value=\"IT\">\n"
                . "<input type=\"hidden\" name=\"item_name\" value=\"" . $kpp_nome_prodotto . "\">\n"
                . "<input type=\"hidden\" name=\"currency_code\" value=\"EUR\">\n"
                . "<input type=\"hidden\" name=\"button_subtype\" value=\"services\">\n"
                . "<input type=\"hidden\" name=\"no_note\" value=\"0\">\n"
                . "\n"
                . "<input type=\"hidden\" id=\"cancel_return\" name=\"cancel_return\" value=\"" . $kpp_cancel_return . "\">\n"
                . "<input type=\"hidden\" id=\"return\" name=\"return\" value=\"" . $kpp_return . "\">\n"
                . ""
                . $text_subscription
                . "\n"
                . $text_shipping
                . "\n"
                . $text_iva
                . "\n"
                . "<input type=\"hidden\" name=\"custom\" id=\"custom\" "
                . "value=\"user=" . $kpp_user . ";"
                . "circle_code=" . $kpp_circle . ";"
                . "sponsor=" . "<?php echo \$_COOKIE['sponsor'];?>" . ";"
                . "email=" . "<?php echo \$_COOKIE['email'];?>" . ";"
                . "service=" . $kpp_service . ";"
                . "tipo=" . $kpp_tipo . ""
                . ""
                . "\">\n"
                . "<input type=\"hidden\" name=\"notify_url\" value=\"http://www.konora.com/payment/user_ipn\">\n"
                . "\n"
                . "\n";



        $txt = $txt . "</form>\n"
                . "<p align=\"center\">" . $kpp_nome_prodotto . " - Pagamento attraverso sistemi KONORA ... processo ordine in corso... stai per essere rendirizzato su paypal...</p>"
                . "<script language=\"JavaScript\">\n"
                . "document.frm.submit();\n"
                . "</script>\n";


        fwrite($kpp_myfile, $txt);
        fclose($kpp_myfile);


        $dominio = "http://127.0.0.1:4001/";
        $wp_folder = "wordpress/";
        $sub_url = "wp-admin/plugin-editor.php?file=konora-paypal%2Ffiles%2F";
        $end_url = "&plugin=konora-paypal%2Fkonora-paypal.php";



        if ($Error != "") {
            echo '<div class="errore"><i class="fa fa-frown-o fa-lg"></i>'
            . '<br>Sono stati riscontrati i seguenti errori nella creazione del FILE'
            . '<br><br>' . $Error . '</div>';
        }

        echo '<div class="info"><i class="fa fa-info fa-lg"></i><strong> Il file è stato creato</strong>';
        echo ' => ' . $kpp_nome_file . '.php ' . ' <a href="' . $file_url . '">(LINK) </a> '
        . '<a href="' . $dominio . $wp_folder . $sub_url . $kpp_nome_file . ".php" . $end_url . '"> (Editor)</a>';
        echo '</div>';
//        echo '<script language="javascript">';
//        echo 'window.location.reload()';
//        echo '</script>';
    }
}

function delete_file() {

    /*
     * http://stackoverflow.com/questions/20738329/how-to-call-a-php-function-on-the-click-of-a-button
     */

    echo '<br> delete file...';

//        $fileArray = $files;
//
//        foreach ($fileArray as $value) {
//            if (file_exists($value) and ($value == $file)) {
//                unlink($value);
//            } else {
//                // code when file not found
//            }
//        }
}
?>