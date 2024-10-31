<?php 
/*
Plugin Name: Pub.lica.me para Wordpress
Version: 0.3.4
Plugin URI: http://aichholzer.name/item/publicame-para-wordpress
Author: Stefan Aichholzer S.
Author URI: http://aichholzer.name/
Description: Automáticamente publica un título y un enlace de cada nueva entrada en <a href="http://pub.lica.me">http://pub.lica.me</a> - Pub.lica.me es una plataforma para micro-bloggear y para difundir aún más tu blog y lo que en él escribas. Una vez que hayas activado el plug-in visita la página de opciones: <a href="options-general.php?page=pub-lica-me.php">Opciones/Pub.lica.me</a> y configura tu plug-in.

*/
 
 global $wpdb;
 global $table_prefix;
 $pub = new publicame($wpdb, $table_prefix);
 
 if (class_exists('publicame')) {
	return;
 }
 
 class publicame {

 	private $_ovrs = array();
 	function __set($name, $value) { $this->_ovrs[$name] = $value; }
 	function __get($name) { return $this->_ovrs[$name]; }
	function __call($func, $args)
	 {
		echo $this->_read_tpl(preg_replace('/_/', '', $func));
	 }

	function __construct($db, $prefix)
	 {
	 	$this->version = '0.3.4';
		$this->db = $db;
		$this->dictionary = 'pub-lica-me';
		$this->table = $prefix . "publicame";
		$this->tpls = dirname(__FILE__) . '/templates/';
		$this->purl = WP_PLUGIN_URL .'/'. plugin_basename(dirname(__FILE__));

		$this->_add_actions();

		if (isset($_POST['do']) && $_POST['do'] == 'save_publicame_config') {
			$this->_save_options($_POST);
		}

		$this->ops = unserialize(get_option('publicame_options'));
	 }


	public function _add_actions()
	 {
		register_activation_hook(__FILE__,	array(&$this, '_install'));
		add_action('admin_menu',			array(&$this, '_add_options'));
		add_action('admin_head',			array(&$this, '_add_scripts'));
		add_action('wp_insert_post',		array(&$this, '_insert'));
		add_action('wp_delete_post',		array(&$this, '_delete'));
		
		load_plugin_textdomain($this->dictionary, false, dirname(plugin_basename(__FILE__)) . '/lang');
	 }


	public function _install()
	 {
		if ($this->db->get_var("SHOW TABLES LIKE '" . $this->table . "'") != $this->table) {
			$sql = "CREATE TABLE " . $this->table . " (
					id int(11) NOT NULL,
					status varchar(25) character set utf8 NOT NULL,
					errmsg varchar(200) character set utf8 NOT NULL,
					UNIQUE KEY id (id) );";

			$this->db->query($sql);
		}

	 }


	public function _save_options($post)
	 {
		$opts = serialize($post);

		if (!get_option('publicame_options')) {
			add_option("publicame_options", $opts);
		} else {
			update_option('publicame_options', $opts);
		}
	 }


	public function _insert($pid)
	 {
		if (!$this->_exists($pid)) {
			$post = get_post($pid);
			$this->_update($pid, $post->post_status);
	 	}

		$this->_processor();
	 }


	public function _exists($pid)
	 {
		$query = "SELECT id, status from ".$this->table." WHERE id = " . $this->db->escape($pid);
		return ($this->db->get_var($query, 1) == 'publicado') ? true : false;
	 }

	
	public function _update($pid, $status, $msg = false)
	 {
		$this->_delete($pid);
		$query = "INSERT INTO ".$this->table." VALUES(".$this->db->escape($pid).", '".$this->db->escape($status)."', '".$msg."')";
		return $this->db->query($query);
	 }
 

	public function _delete($pid)
	 {
		return $this->db->query("DELETE FROM ".$this->table." WHERE id = " . $this->db->escape($pid));
	 }
 

	public function _processor()
	 {
		$posts = $this->db->get_results("SELECT * FROM ".$this->table." WHERE status = 'publish'", ARRAY_A);

		if ($posts) {
			foreach($posts as $item) {
				$result = $this->_publicame($item['id']);
				if ($result) {
					$this->_update($item['id'], 'publicado', $result[1]);
				} else {
					$this->_update($item['id'], 'puberror', $result[1]);
				}
			}
		}

		$clean = $this->db->query("DELETE FROM ".$this->table." WHERE status = 'inherit'");
	 }


	public function _publicame($pid)
	 {
 		$post = get_post($pid);

		$domain = $_SERVER['SERVER_NAME'];
		$user = ($this->ops['publicame_name']) ? $this->ops['publicame_name'] : 'Anónimo';
		$post_string = 'do=ext&return=bool&sender='.$domain.'&user='.$this->ops['publicame_username'].'&pass='.md5($this->ops['publicame_pass']).'&text='.$post->post_title.'%20'.$post->guid;

		$pst = curl_init();
		curl_setopt($pst, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
		curl_setopt($pst, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($pst, CURLOPT_POST, 1);
		curl_setopt($pst, CURLOPT_POSTFIELDS, $post_string);
		curl_setopt($pst, CURLOPT_URL, 'http://pub.lica.me/xpublica');

		$result = curl_exec($pst);
		curl_close($pst);

		return $result;
	 }


	public function _list_options()
	 {
		$segment = (isset($_GET['do'])) ? $_GET['do'] : '';

		echo '	<div id="div_publicame_options">
					<h2 id="publicame_logo">Logo</h2>
					<div id="options_links">
						<ul>
							<li><a href="'.$_SERVER['PHP_SELF'].'?page=pub-lica-me.php&amp;do=pub-options">'.__('Configuración', $this->dictionary).'</a></li>
							<li><a href="'.$_SERVER['PHP_SELF'].'?page=pub-lica-me.php&amp;do=pub-stats">'.__('Estadísticas', $this->dictionary).'</a></li>
							<li><a href="'.$_SERVER['PHP_SELF'].'?page=pub-lica-me.php&amp;do=pub-info">'.__('Información / Ayuda', $this->dictionary).'</a></li>
						</ul>
					</div>';
				
					if ($segment) {
						echo '<div id="publicame_contents">';
					}

					switch ($segment) {
						case 'pub-options':	$this->_options();
	 										break;
		 	
						case 'pub-stats':	$this->_stats();
	 										break;
	 	
						case 'pub-info':	$this->_info();
		 									break;
					}

					if ($segment) { 
						echo '</div>';
					}
				
		echo '		<div id="footer_note">
						<div style="float:left; display:inline; margin-right:6px;">
							<a href="http://aichholzer.name"><img src="http://www.gravatar.com/avatar/2645cdde14233b260c8bc576e1299d8d.jpg?d=monsterid&amp;s=30" title="Stefan Aichholzer" alt="Stefan Aichholzer" /></a>
						</div>
						<div style="float:left; display:inline; line-height:15px">
							'.sprintf(__('Un experimento de: %s - Distribuído bajo licencia GNU/GPL | Versión: <strong>%s</strong>', $this->dictionary), '<a href="http://aichholzer.name">Stefan Aichholzer</a>', $this->version) . '<br />' . sprintf(__('Logotipo: %s', $this->dictionary), 'David Bugeja / <a href="http://www.reohix.com">Reohix.com</a>') . 
						'</div>
					</div>';
	 }


	public function _stats()
	 {
		$limit = ($this->ops['publicame_errors']) ? $this->ops['publicame_errors'] : 10;

		$publicados = $this->db->get_results("SELECT * FROM ".$this->table." WHERE status = 'publicado'", ARRAY_A);
		$fallidos = $this->db->get_results("SELECT * FROM ".$this->table." WHERE status = 'puberror' LIMIT " . $limit, ARRAY_A);
		$fallidos_count = count($fallidos);
		$limit = ($fallidos_count < $limit) ? $fallidos_count : $limit;

		echo '	<h3>'.__('Estadísticas', $this->dictionary).'</h3>
				<p>'.sprintf(__('En total se han publicado <strong>%d</strong> entradas de tu blog en %s', $this->dictionary), count($publicados), '<a href="http://pub.lica.me">pub.lica.me</a>').'</p>';
				
				if ($fallidos_count > 0) {
					echo '<p style="color:red;">'.sprintf(__('Hay <strong>%d</strong> intentos que han reportado un error al intentar publicar.', $this->dictionary), $fallidos_count).'</p>';
					echo '<p>'.sprintf(__('Aquí un detalle de los últimos %d errores', $this->dictionary), $limit).'</p>';
					echo '<ul>';
					
						foreach($fallidos as $fallo) {
							echo '<li>ID de entrada <strong>'.$fallo['id'].'</strong> : <strong>'.$fallo['errmsg'].'</strong></li>';
						}

					echo '</ul>';
				} else {
					echo '<p style="color:green;">'.__('No hay reportes de errores al publicar. <a href="http://aichholzer.name/item/def-sonadito/"><strong>!Soñadito!</strong></a>', $this->dictionary).'</p>';
				}
	 }


	public function _add_options() 
	 {
		add_options_page('Pub.lica.me para WP', 'Pub.lica.me', 1, 'pub-lica-me.php', array($this, '_list_options'));
	 }


	public function _add_scripts()
	 {
		if (is_feed()) {
			return false;
		}

	 	if (is_plugin_page() && $_GET['page'] == basename(__FILE__)) {
			echo "\n\n". '<!-- Pub.lica.me por Stefan Aichholzer - http://aichholzer.name -->' . "\n";

			wp_register_style('pub-lica-me-css', $this->purl . '/css/publicame_style.css');
			wp_print_styles(array('pub-lica-me-css'));

			echo '<!-- Pub.lica.me -->' . "\n";
		}
	 }


	public function _read_tpl($tpl)
	 {
	 	ob_start();
		include($this->tpls . $tpl . '.tpl');
    	$cts = ob_get_contents();
		ob_end_clean();

		return preg_replace_callback('/\{(.*)\}/', array($this, "_replace_tpl_vars"), $cts);
	 }


	public function _replace_tpl_vars($txt)
	 {
		if (preg_match('/^@/', $txt[1])) {
			return $this->ops[strtr($txt[1], array('@' => ''))];
		} elseif (preg_match('/^&/', $txt[1])) {
		 	$nf = preg_split('/,(?=(?:[^\']*\'[^\']*\')*(?![^\']*\'))/', strtr($txt[1], array('&' => '')));
			foreach($nf as &$item) {
				$item = strtr($item, array('\'' => ''));
			}

			$trn = $nf[0];
			unset($nf[0]);
			return vsprintf(__($trn, $this->dictionary), $nf);
		} else {
			return __($txt[1], $this->dictionary);
		}
	 }

  }

?>