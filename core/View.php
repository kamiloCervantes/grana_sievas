<?php


include_once CORE_PATH.'/i18n/Translator.php';
class View
{
    private static $_content = '';
    
    private static $_array_css = array();
    private static $_array_js = array();
   
    public static function render($view, $vars = null, $tpl = 'default.php')
    {        
        $tablet_browser = 0;
        $mobile_browser = 0;
        $body_class = 'desktop';

        if (preg_match('/(tablet|ipad|playbook)|(android(?!.*(mobi|opera mini)))/i', strtolower($_SERVER['HTTP_USER_AGENT']))) {
            $tablet_browser++;
            $body_class = "tablet";
        }

        if (preg_match('/(up.browser|up.link|mmp|symbian|smartphone|midp|wap|phone|android|iemobile)/i', strtolower($_SERVER['HTTP_USER_AGENT']))) {
            $mobile_browser++;
            $body_class = "mobile";
        }

        if ((strpos(strtolower($_SERVER['HTTP_ACCEPT']),'application/vnd.wap.xhtml+xml') > 0) or ((isset($_SERVER['HTTP_X_WAP_PROFILE']) or isset($_SERVER['HTTP_PROFILE'])))) {
            $mobile_browser++;
            $body_class = "mobile";
        }

        $mobile_ua = strtolower(substr($_SERVER['HTTP_USER_AGENT'], 0, 4));
        $mobile_agents = array(
            'w3c ','acs-','alav','alca','amoi','audi','avan','benq','bird','blac',
            'blaz','brew','cell','cldc','cmd-','dang','doco','eric','hipt','inno',
            'ipaq','java','jigs','kddi','keji','leno','lg-c','lg-d','lg-g','lge-',
            'maui','maxo','midp','mits','mmef','mobi','mot-','moto','mwbp','nec-',
            'newt','noki','palm','pana','pant','phil','play','port','prox',
            'qwap','sage','sams','sany','sch-','sec-','send','seri','sgh-','shar',
            'sie-','siem','smal','smar','sony','sph-','symb','t-mo','teli','tim-',
            'tosh','tsm-','upg1','upsi','vk-v','voda','wap-','wapa','wapi','wapp',
            'wapr','webc','winw','winw','xda ','xda-');

        if (in_array($mobile_ua,$mobile_agents)) {
            $mobile_browser++;
        }

        if (strpos(strtolower($_SERVER['HTTP_USER_AGENT']),'opera mini') > 0) {
            $mobile_browser++;
            //Check for tablets on opera mini alternative headers
            $stock_ua = strtolower(isset($_SERVER['HTTP_X_OPERAMINI_PHONE_UA'])?$_SERVER['HTTP_X_OPERAMINI_PHONE_UA']:(isset($_SERVER['HTTP_DEVICE_STOCK_UA'])?$_SERVER['HTTP_DEVICE_STOCK_UA']:''));
            if (preg_match('/(tablet|ipad|playbook)|(android(?!.*mobile))/i', $stock_ua)) {
              $tablet_browser++;
            }
        }
        if ($tablet_browser > 0) {
        // Si es tablet has lo que necesites
//           print 'es tablet';
        }
        else if ($mobile_browser > 0) {
        // Si es dispositivo mobil has lo que necesites
//           print 'es un mobil';
        }
        else {
        // Si es ordenador de escritorio has lo que necesites
//           print 'es un ordenador de escritorio';
        }  
        
        $t = new Translator();
        
        if (is_null($view)) {
            die('La vista es requerida');
        }

        $config = Config::singleton();

        $viewFile = $config->get('modulesFolder') . $_GET['mod'].'/' . $config->get('viewsFolder') .
            $view;

        // Check the view file exists
        if (!file_exists($viewFile)) {
            die('La vista solicitada no existe');
        }

        // Check the template file exists
        if (!is_null($tpl)) {
           
            if(is_readable(MODULES_PATH.$_GET['mod'].DS.'views'.DS.'template'.DS.$tpl)){
                $tpl_path = MODULES_PATH.$_GET['mod'].DS.'views'.DS.'template'.DS.$tpl;
            }elseif(is_readable(TEMPLATE_PATH.$tpl)){
                $tpl_path = TEMPLATE_PATH.$tpl;
            }else{
                die('El template solicitado no existe'.MODULES_PATH.$_GET['mod'].DS.'views'.DS.'template'.DS.$tpl);
            }
        }

        // Extract the variables to be used by the view
        if (!is_null($vars)) {
            extract($vars);
        }
        
        $vars['tablet_browser'] = $tablet_browser;
        $vars['mobile_browser'] = $mobile_browser;

        ob_start();

        require $viewFile;

        $view = ob_get_contents();

        ob_end_clean();
        //return $view;
        

        if (is_null($tpl)) {
            echo $view;
        } else {
            self::$_content = $view;
            require $tpl_path;
        }
    }

    public static function content()
    {
        return self::$_content;
    }

    public static function add_css($filecss, $clave = 'default')
    {
        self::$_array_css[$clave][] = $filecss;
    }

    public static function print_css($clave = 'default')
    {
        $s = '';
        if(!isset(self::$_array_css[$clave])){
            return $s;
        }
        foreach (self::$_array_css[$clave] as $url) {
            $s .= sprintf('<link href="%s" rel="stylesheet" type="text/css" >', $url);
        }

        return $s;
    }

    public static function add_js($filejs, $clave = 'default')
    {
        self::$_array_js[$clave][] = $filejs;
    }

    public static function print_js($clave = 'default')
    {
        $s = '';
        if(!isset(self::$_array_js[$clave])){
            return $s;
        }
        foreach (self::$_array_js[$clave] as $url) {
            $s .= sprintf('<script type="text/javascript" src="%s" ></script>', $url);
        }

        return $s;
    }
}
?>