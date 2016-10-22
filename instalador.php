From: "Guardado por Internet Explorer 11"
Subject: 
Date: Tue, 18 Oct 2016 15:57:46 -0600
MIME-Version: 1.0
Content-Type: text/html;
	charset="Windows-1252"
Content-Transfer-Encoding: quoted-printable
Content-Location: file://C:\Users\VIGIL\Downloads\installer
X-MimeOLE: Produced By Microsoft MimeOLE V6.3.9600.18500

<!DOCTYPE HTML>
<!DOCTYPE html PUBLIC "" ""><HTML><HEAD><META content=3D"IE=3D11.0000"=20
http-equiv=3D"X-UA-Compatible">

<META http-equiv=3D"Content-Type" content=3D"text/html; =
charset=3Dwindows-1252">
<META name=3D"GENERATOR" content=3D"MSHTML 11.00.9600.18500"></HEAD>
<BODY>
<PRE>&lt;?php=0A=
=0A=
/*=0A=
 * This file is part of Composer.=0A=
 *=0A=
 * (c) Nils Adermann &lt;naderman@naderman.de&gt;=0A=
 *     Jordi Boggiano &lt;j.boggiano@seld.be&gt;=0A=
 *=0A=
 * For the full copyright and license information, please view the =
LICENSE=0A=
 * file that was distributed with this source code.=0A=
 */=0A=
=0A=
process(is_array($argv) ? $argv : array());=0A=
=0A=
/**=0A=
 * processes the installer=0A=
 */=0A=
function process($argv)=0A=
{=0A=
    // Determine ANSI output from --ansi and --no-ansi flags=0A=
    setUseAnsi($argv);=0A=
=0A=
    if (in_array('--help', $argv)) {=0A=
        displayHelp();=0A=
        exit(0);=0A=
    }=0A=
=0A=
    $check      =3D in_array('--check', $argv);=0A=
    $help       =3D in_array('--help', $argv);=0A=
    $force      =3D in_array('--force', $argv);=0A=
    $quiet      =3D in_array('--quiet', $argv);=0A=
    $channel    =3D in_array('--snapshot', $argv) ? 'snapshot' : =
(in_array('--preview', $argv) ? 'preview' : 'stable');=0A=
    $disableTls =3D in_array('--disable-tls', $argv);=0A=
    $installDir =3D getOptValue('--install-dir', $argv, false);=0A=
    $version    =3D getOptValue('--version', $argv, false);=0A=
    $filename   =3D getOptValue('--filename', $argv, 'composer.phar');=0A=
    $cafile     =3D getOptValue('--cafile', $argv, false);=0A=
=0A=
    if (!checkParams($installDir, $version, $cafile)) {=0A=
        exit(1);=0A=
    }=0A=
=0A=
    $ok =3D checkPlatform($warnings, $quiet, $disableTls);=0A=
=0A=
    if ($check) {=0A=
        // Only show warnings if we haven't output any errors=0A=
        if ($ok) {=0A=
            showWarnings($warnings);=0A=
            showSecurityWarning($disableTls);=0A=
        }=0A=
        exit($ok ? 0 : 1);=0A=
    }=0A=
=0A=
    if ($ok || $force) {=0A=
        installComposer($version, $installDir, $filename, $quiet, =
$disableTls, $cafile, $channel);=0A=
        showWarnings($warnings);=0A=
        showSecurityWarning($disableTls);=0A=
        exit(0);=0A=
    }=0A=
=0A=
    exit(1);=0A=
}=0A=
=0A=
/**=0A=
 * displays the help=0A=
 */=0A=
function displayHelp()=0A=
{=0A=
    echo &lt;&lt;&lt;EOF=0A=
Composer Installer=0A=
------------------=0A=
Options=0A=
--help               this help=0A=
--check              for checking environment only=0A=
--force              forces the installation=0A=
--ansi               force ANSI color output=0A=
--no-ansi            disable ANSI color output=0A=
--quiet              do not output unimportant messages=0A=
--install-dir=3D"..."  accepts a target installation directory=0A=
--preview            install the latest version from the preview =
(alpha/beta/rc) channel instead of stable=0A=
--snapshot           install the latest version from the snapshot (dev =
builds) channel instead of stable=0A=
--version=3D"..."      accepts a specific version to install instead of =
the latest=0A=
--filename=3D"..."     accepts a target filename (default: composer.phar)=0A=
--disable-tls        disable SSL/TLS security for file downloads=0A=
--cafile=3D"..."       accepts a path to a Certificate Authority (CA) =
certificate file for SSL/TLS verification=0A=
=0A=
EOF;=0A=
}=0A=
=0A=
/**=0A=
 * Sets the USE_ANSI define for colorizing output=0A=
 *=0A=
 * @param array $argv Command-line arguments=0A=
 */=0A=
function setUseAnsi($argv)=0A=
{=0A=
    // --no-ansi wins over --ansi=0A=
    if (in_array('--no-ansi', $argv)) {=0A=
        define('USE_ANSI', false);=0A=
    } elseif (in_array('--ansi', $argv)) {=0A=
        define('USE_ANSI', true);=0A=
    } else {=0A=
        // On Windows, default to no ANSI, except in ANSICON and ConEmu.=0A=
        // Everywhere else, default to ANSI if stdout is a terminal.=0A=
        define(=0A=
            'USE_ANSI',=0A=
            (DIRECTORY_SEPARATOR =3D=3D '\\')=0A=
                ? (false !=3D=3D getenv('ANSICON') || 'ON' =3D=3D=3D =
getenv('ConEmuANSI'))=0A=
                : (function_exists('posix_isatty') &amp;&amp; =
posix_isatty(1))=0A=
        );=0A=
    }=0A=
}=0A=
=0A=
/**=0A=
 * Returns the value of a command-line option=0A=
 *=0A=
 * @param string $opt The command-line option to check=0A=
 * @param array $argv Command-line arguments=0A=
 * @param mixed $default Default value to be returned=0A=
 *=0A=
 * @return mixed The command-line value or the default=0A=
 */=0A=
function getOptValue($opt, $argv, $default)=0A=
{=0A=
    $optLength =3D strlen($opt);=0A=
=0A=
    foreach ($argv as $key =3D&gt; $value) {=0A=
        $next =3D $key + 1;=0A=
        if (0 =3D=3D=3D strpos($value, $opt)) {=0A=
            if ($optLength =3D=3D=3D strlen($value) &amp;&amp; =
isset($argv[$next])) {=0A=
                return trim($argv[$next]);=0A=
            } else {=0A=
                return trim(substr($value, $optLength + 1));=0A=
            }=0A=
        }=0A=
    }=0A=
=0A=
    return $default;=0A=
}=0A=
=0A=
/**=0A=
 * Checks that user-supplied params are valid=0A=
 *=0A=
 * @param mixed $installDir The required istallation directory=0A=
 * @param mixed $version The required composer version to install=0A=
 * @param mixed $cafile Certificate Authority file=0A=
 *=0A=
 * @return bool True if the supplied params are okay=0A=
 */=0A=
function checkParams($installDir, $version, $cafile)=0A=
{=0A=
    $result =3D true;=0A=
=0A=
    if (false !=3D=3D $installDir &amp;&amp; !is_dir($installDir)) {=0A=
        out("The defined install dir ({$installDir}) does not exist.", =
'info');=0A=
        $result =3D false;=0A=
    }=0A=
=0A=
    if (false !=3D=3D $version &amp;&amp; 1 !=3D=3D =
preg_match('/^\d+\.\d+\.\d+(\-(alpha|beta)\d+)*$/', $version)) {=0A=
        out("The defined install version ({$version}) does not match =
release pattern.", 'info');=0A=
        $result =3D false;=0A=
    }=0A=
=0A=
    if (false !=3D=3D $cafile &amp;&amp; (!file_exists($cafile) || =
!is_readable($cafile))) {=0A=
        out("The defined Certificate Authority (CA) cert file =
({$cafile}) does not exist or is not readable.", 'info');=0A=
        $result =3D false;=0A=
    }=0A=
    return $result;=0A=
}=0A=
=0A=
/**=0A=
 * Checks the platform for possible issues running Composer=0A=
 *=0A=
 * Errors are written to the output, warnings are saved for later =
display.=0A=
 *=0A=
 * @param array $warnings Populated by method, to be shown later=0A=
 * @param bool $quiet Quiet mode=0A=
 * @param bool $disableTls Bypass tls=0A=
 *=0A=
 * @return bool True if there are no errors=0A=
 */=0A=
function checkPlatform(&amp;$warnings, $quiet, $disableTls)=0A=
{=0A=
    getPlatformIssues($errors, $warnings);=0A=
=0A=
    // Make openssl warning an error if tls has not been specifically =
disabled=0A=
    if (isset($warnings['openssl']) &amp;&amp; !$disableTls) {=0A=
        $errors['openssl'] =3D $warnings['openssl'];=0A=
        unset($warnings['openssl']);=0A=
    }=0A=
=0A=
    if (!empty($errors)) {=0A=
        out('Some settings on your machine make Composer unable to work =
properly.', 'error');=0A=
        out('Make sure that you fix the issues listed below and run this =
script again:', 'error');=0A=
        outputIssues($errors);=0A=
        return false;=0A=
    }=0A=
=0A=
    if (empty($warnings) &amp;&amp; !$quiet) {=0A=
        out('All settings correct for using Composer', 'success');=0A=
    }=0A=
    return true;=0A=
}=0A=
=0A=
/**=0A=
 * Checks platform configuration for common incompatibility issues=0A=
 *=0A=
 * @param array $errors Populated by method=0A=
 * @param array $warnings Populated by method=0A=
 *=0A=
 * @return bool If any errors or warnings have been found=0A=
 */=0A=
function getPlatformIssues(&amp;$errors, &amp;$warnings)=0A=
{=0A=
    $errors =3D array();=0A=
    $warnings =3D array();=0A=
=0A=
    if ($iniPath =3D php_ini_loaded_file()) {=0A=
        $iniMessage =3D PHP_EOL.'The php.ini used by your command-line =
PHP is: ' . $iniPath;=0A=
    } else {=0A=
        $iniMessage =3D PHP_EOL.'A php.ini file does not exist. You will =
have to create one.';=0A=
    }=0A=
    $iniMessage .=3D PHP_EOL.'If you can not modify the ini file, you =
can also run `php -d option=3Dvalue` to modify ini values on the fly. =
You can use -d multiple times.';=0A=
=0A=
    if (ini_get('detect_unicode')) {=0A=
        $errors['unicode'] =3D array(=0A=
            'The detect_unicode setting must be disabled.',=0A=
            'Add the following to the end of your `php.ini`:',=0A=
            '    detect_unicode =3D Off',=0A=
            $iniMessage=0A=
        );=0A=
    }=0A=
=0A=
    if (extension_loaded('suhosin')) {=0A=
        $suhosin =3D ini_get('suhosin.executor.include.whitelist');=0A=
        $suhosinBlacklist =3D =
ini_get('suhosin.executor.include.blacklist');=0A=
        if (false =3D=3D=3D stripos($suhosin, 'phar') &amp;&amp; =
(!$suhosinBlacklist || false !=3D=3D stripos($suhosinBlacklist, =
'phar'))) {=0A=
            $errors['suhosin'] =3D array(=0A=
                'The suhosin.executor.include.whitelist setting is =
incorrect.',=0A=
                'Add the following to the end of your `php.ini` or =
suhosin.ini (Example path [for Debian]: =
/etc/php5/cli/conf.d/suhosin.ini):',=0A=
                '    suhosin.executor.include.whitelist =3D phar =
'.$suhosin,=0A=
                $iniMessage=0A=
            );=0A=
        }=0A=
    }=0A=
=0A=
    if (!function_exists('json_decode')) {=0A=
        $errors['json'] =3D array(=0A=
            'The json extension is missing.',=0A=
            'Install it or recompile php without --disable-json'=0A=
        );=0A=
    }=0A=
=0A=
    if (!extension_loaded('Phar')) {=0A=
        $errors['phar'] =3D array(=0A=
            'The phar extension is missing.',=0A=
            'Install it or recompile php without --disable-phar'=0A=
        );=0A=
    }=0A=
=0A=
    if (!extension_loaded('filter')) {=0A=
        $errors['filter'] =3D array(=0A=
            'The filter extension is missing.',=0A=
            'Install it or recompile php without --disable-filter'=0A=
        );=0A=
    }=0A=
=0A=
    if (!extension_loaded('hash')) {=0A=
        $errors['hash'] =3D array(=0A=
            'The hash extension is missing.',=0A=
            'Install it or recompile php without --disable-hash'=0A=
        );=0A=
    }=0A=
=0A=
    if (!extension_loaded('iconv') &amp;&amp; =
!extension_loaded('mbstring')) {=0A=
        $errors['iconv_mbstring'] =3D array(=0A=
            'The iconv OR mbstring extension is required and both are =
missing.',=0A=
            'Install either of them or recompile php without =
--disable-iconv'=0A=
        );=0A=
    }=0A=
=0A=
    if (!ini_get('allow_url_fopen')) {=0A=
        $errors['allow_url_fopen'] =3D array(=0A=
            'The allow_url_fopen setting is incorrect.',=0A=
            'Add the following to the end of your `php.ini`:',=0A=
            '    allow_url_fopen =3D On',=0A=
            $iniMessage=0A=
        );=0A=
    }=0A=
=0A=
    if (extension_loaded('ionCube Loader') &amp;&amp; =
ioncube_loader_iversion() &lt; 40009) {=0A=
        $ioncube =3D ioncube_loader_version();=0A=
        $errors['ioncube'] =3D array(=0A=
            'Your ionCube Loader extension ('.$ioncube.') is =
incompatible with Phar files.',=0A=
            'Upgrade to ionCube 4.0.9 or higher or remove this line =
(path may be different) from your `php.ini` to disable it:',=0A=
            '    zend_extension =3D =
/usr/lib/php5/20090626+lfs/ioncube_loader_lin_5.3.so',=0A=
            $iniMessage=0A=
        );=0A=
    }=0A=
=0A=
    if (version_compare(PHP_VERSION, '5.3.2', '&lt;')) {=0A=
        $errors['php'] =3D array(=0A=
            'Your PHP ('.PHP_VERSION.') is too old, you must upgrade to =
PHP 5.3.2 or higher.'=0A=
        );=0A=
    }=0A=
=0A=
    if (version_compare(PHP_VERSION, '5.3.4', '&lt;')) {=0A=
        $warnings['php'] =3D array(=0A=
            'Your PHP ('.PHP_VERSION.') is quite old, upgrading to PHP =
5.3.4 or higher is recommended.',=0A=
            'Composer works with 5.3.2+ for most people, but there might =
be edge case issues.'=0A=
        );=0A=
    }=0A=
=0A=
    if (!extension_loaded('openssl')) {=0A=
        $warnings['openssl'] =3D array(=0A=
            'The openssl extension is missing, which means that secure =
HTTPS transfers are impossible.',=0A=
            'If possible you should enable it or recompile php with =
--with-openssl'=0A=
        );=0A=
    }=0A=
=0A=
    if (extension_loaded('openssl') &amp;&amp; OPENSSL_VERSION_NUMBER =
&lt; 0x1000100f) {=0A=
        // Attempt to parse version number out, fallback to whole string =
value.=0A=
        $opensslVersion =3D trim(strstr(OPENSSL_VERSION_TEXT, ' '));=0A=
        $opensslVersion =3D substr($opensslVersion, 0, =
strpos($opensslVersion, ' '));=0A=
        $opensslVersion =3D $opensslVersion ? $opensslVersion : =
OPENSSL_VERSION_TEXT;=0A=
=0A=
        $warnings['openssl_version'] =3D array(=0A=
            'The OpenSSL library ('.$opensslVersion.') used by PHP does =
not support TLSv1.2 or TLSv1.1.',=0A=
            'If possible you should upgrade OpenSSL to version 1.0.1 or =
above.'=0A=
        );=0A=
    }=0A=
=0A=
    if (!defined('HHVM_VERSION') &amp;&amp; !extension_loaded('apcu') =
&amp;&amp; ini_get('apc.enable_cli')) {=0A=
        $warnings['apc_cli'] =3D array(=0A=
            'The apc.enable_cli setting is incorrect.',=0A=
            'Add the following to the end of your `php.ini`:',=0A=
            '    apc.enable_cli =3D Off',=0A=
            $iniMessage=0A=
        );=0A=
    }=0A=
=0A=
    if (extension_loaded('xdebug')) {=0A=
        $warnings['xdebug_loaded'] =3D array(=0A=
            'The xdebug extension is loaded, this can slow down Composer =
a little.',=0A=
            'Disabling it when using Composer is recommended.'=0A=
        );=0A=
=0A=
        if (ini_get('xdebug.profiler_enabled')) {=0A=
            $warnings['xdebug_profile'] =3D array(=0A=
                'The xdebug.profiler_enabled setting is enabled, this =
can slow down Composer a lot.',=0A=
                'Add the following to the end of your `php.ini` to =
disable it:',=0A=
                '    xdebug.profiler_enabled =3D 0',=0A=
                $iniMessage=0A=
            );=0A=
        }=0A=
    }=0A=
=0A=
    ob_start();=0A=
    phpinfo(INFO_GENERAL);=0A=
    $phpinfo =3D ob_get_clean();=0A=
    if (preg_match('{Configure Command(?: *&lt;/td&gt;&lt;td =
class=3D"v"&gt;| *=3D&gt; *)(.*?)(?:&lt;/td&gt;|$)}m', $phpinfo, =
$match)) {=0A=
        $configure =3D $match[1];=0A=
=0A=
        if (false !=3D=3D strpos($configure, '--enable-sigchild')) {=0A=
            $warnings['sigchild'] =3D array(=0A=
                'PHP was compiled with --enable-sigchild which can cause =
issues on some platforms.',=0A=
                'Recompile it without this flag if possible, see also:',=0A=
                '    https://bugs.php.net/bug.php?id=3D22999'=0A=
            );=0A=
        }=0A=
=0A=
        if (false !=3D=3D strpos($configure, '--with-curlwrappers')) {=0A=
            $warnings['curlwrappers'] =3D array(=0A=
                'PHP was compiled with --with-curlwrappers which will =
cause issues with HTTP authentication and GitHub.',=0A=
                'Recompile it without this flag if possible'=0A=
            );=0A=
        }=0A=
    }=0A=
=0A=
    // Stringify the message arrays=0A=
    foreach ($errors as $key =3D&gt; $value) {=0A=
        $errors[$key] =3D PHP_EOL.implode(PHP_EOL, $value);=0A=
    }=0A=
=0A=
    foreach ($warnings as $key =3D&gt; $value) {=0A=
        $warnings[$key] =3D PHP_EOL.implode(PHP_EOL, $value);=0A=
    }=0A=
=0A=
    return !empty($errors) || !empty($warnings);=0A=
}=0A=
=0A=
=0A=
/**=0A=
 * Outputs an array of issues=0A=
 *=0A=
 * @param array $issues=0A=
 */=0A=
function outputIssues($issues)=0A=
{=0A=
    foreach ($issues as $issue) {=0A=
        out($issue, 'info');=0A=
    }=0A=
    out('');=0A=
}=0A=
=0A=
/**=0A=
 * Outputs any warnings found=0A=
 *=0A=
 * @param array $warnings=0A=
 */=0A=
function showWarnings($warnings)=0A=
{=0A=
    if (!empty($warnings)) {=0A=
        out('Some settings on your machine may cause stability issues =
with Composer.', 'error');=0A=
        out('If you encounter issues, try to change the following:', =
'error');=0A=
        outputIssues($warnings);=0A=
    }=0A=
}=0A=
=0A=
/**=0A=
 * Outputs an end of process warning if tls has been bypassed=0A=
 *=0A=
 * @param bool $disableTls Bypass tls=0A=
 */=0A=
function showSecurityWarning($disableTls)=0A=
{=0A=
    if ($disableTls) {=0A=
        out('You have instructed the Installer not to enforce SSL/TLS =
security on remote HTTPS requests.', 'info');=0A=
        out('This will leave all downloads during installation =
vulnerable to Man-In-The-Middle (MITM) attacks', 'info');=0A=
    }=0A=
}=0A=
=0A=
/**=0A=
 * installs composer to the current working directory=0A=
 */=0A=
function installComposer($version, $installDir, $filename, $quiet, =
$disableTls, $cafile, $channel)=0A=
{=0A=
    $installPath =3D (is_dir($installDir) ? rtrim($installDir, '/').'/' =
: '') . $filename;=0A=
    $installDir  =3D realpath($installDir) ? realpath($installDir) : =
getcwd();=0A=
    $file        =3D $installDir.DIRECTORY_SEPARATOR.$filename;=0A=
=0A=
    if (is_readable($file)) {=0A=
        @unlink($file);=0A=
    }=0A=
=0A=
    $home =3D getHomeDir();=0A=
=0A=
    if (!is_dir($home)) {=0A=
        @mkdir($home, 0777, true);=0A=
    }=0A=
=0A=
    file_put_contents($home.'/keys.dev.pub', &lt;&lt;&lt;DEVPUBKEY=0A=
-----BEGIN PUBLIC KEY-----=0A=
MIICIjANBgkqhkiG9w0BAQEFAAOCAg8AMIICCgKCAgEAnBDHjZS6e0ZMoK3xTD7f=0A=
FNCzlXjX/Aie2dit8QXA03pSrOTbaMnxON3hUL47Lz3g1SC6YJEMVHr0zYq4elWi=0A=
i3ecFEgzLcj+pZM5X6qWu2Ozz4vWx3JYo1/a/HYdOuW9e3lwS8VtS0AVJA+U8X0A=0A=
hZnBmGpltHhO8hPKHgkJtkTUxCheTcbqn4wGHl8Z2SediDcPTLwqezWKUfrYzu1f=0A=
o/j3WFwFs6GtK4wdYtiXr+yspBZHO3y1udf8eFFGcb2V3EaLOrtfur6XQVizjOuk=0A=
8lw5zzse1Qp/klHqbDRsjSzJ6iL6F4aynBc6Euqt/8ccNAIz0rLjLhOraeyj4eNn=0A=
8iokwMKiXpcrQLTKH+RH1JCuOVxQ436bJwbSsp1VwiqftPQieN+tzqy+EiHJJmGf=0A=
TBAbWcncicCk9q2md+AmhNbvHO4PWbbz9TzC7HJb460jyWeuMEvw3gNIpEo2jYa9=0A=
pMV6cVqnSa+wOc0D7pC9a6bne0bvLcm3S+w6I5iDB3lZsb3A9UtRiSP7aGSo7D72=0A=
8tC8+cIgZcI7k9vjvOqH+d7sdOU2yPCnRY6wFh62/g8bDnUpr56nZN1G89GwM4d4=0A=
r/TU7BQQIzsZgAiqOGXvVklIgAMiV0iucgf3rNBLjjeNEwNSTTG9F0CtQ+7JLwaE=0A=
wSEuAuRm+pRqi8BRnQ/GKUcCAwEAAQ=3D=3D=0A=
-----END PUBLIC KEY-----=0A=
DEVPUBKEY=0A=
);=0A=
    file_put_contents($home.'/keys.tags.pub', &lt;&lt;&lt;TAGSPUBKEY=0A=
-----BEGIN PUBLIC KEY-----=0A=
MIICIjANBgkqhkiG9w0BAQEFAAOCAg8AMIICCgKCAgEA0Vi/2K6apCVj76nCnCl2=0A=
MQUPdK+A9eqkYBacXo2wQBYmyVlXm2/n/ZsX6pCLYPQTHyr5jXbkQzBw8SKqPdlh=0A=
vA7NpbMeNCz7wP/AobvUXM8xQuXKbMDTY2uZ4O7sM+PfGbptKPBGLe8Z8d2sUnTO=0A=
bXtX6Lrj13wkRto7st/w/Yp33RHe9SlqkiiS4MsH1jBkcIkEHsRaveZzedUaxY0M=0A=
mba0uPhGUInpPzEHwrYqBBEtWvP97t2vtfx8I5qv28kh0Y6t+jnjL1Urid2iuQZf=0A=
noCMFIOu4vksK5HxJxxrN0GOmGmwVQjOOtxkwikNiotZGPR4KsVj8NnBrLX7oGuM=0A=
nQvGciiu+KoC2r3HDBrpDeBVdOWxDzT5R4iI0KoLzFh2pKqwbY+obNPS2bj+2dgJ=0A=
rV3V5Jjry42QOCBN3c88wU1PKftOLj2ECpewY6vnE478IipiEu7EAdK8Zwj2LmTr=0A=
RKQUSa9k7ggBkYZWAeO/2Ag0ey3g2bg7eqk+sHEq5ynIXd5lhv6tC5PBdHlWipDK=0A=
tl2IxiEnejnOmAzGVivE1YGduYBjN+mjxDVy8KGBrjnz1JPgAvgdwJ2dYw4Rsc/e=0A=
TzCFWGk/HM6a4f0IzBWbJ5ot0PIi4amk07IotBXDWwqDiQTwyuGCym5EqWQ2BD95=0A=
RGv89BPD+2DLnJysngsvVaUCAwEAAQ=3D=3D=0A=
-----END PUBLIC KEY-----=0A=
TAGSPUBKEY=0A=
);=0A=
=0A=
    if (false =3D=3D=3D $disableTls &amp;&amp; empty($cafile) &amp;&amp; =
!HttpClient::getSystemCaRootBundlePath()) {=0A=
        $errorHandler =3D new ErrorHandler();=0A=
        set_error_handler(array($errorHandler, 'handleError'));=0A=
=0A=
        $target =3D $home . '/cacert.pem';=0A=
        $write =3D file_put_contents($target, =
HttpClient::getPackagedCaFile(), LOCK_EX);=0A=
        @chmod($target, 0644);=0A=
=0A=
        restore_error_handler();=0A=
=0A=
        if (!$write) {=0A=
            throw new RuntimeException('Unable to write bundled =
cacert.pem to: '.$target);=0A=
        }=0A=
        $cafile =3D $target;=0A=
    }=0A=
=0A=
    $httpClient =3D new HttpClient($disableTls, $cafile);=0A=
    $uriScheme =3D false =3D=3D=3D $disableTls ? 'https' : 'http';=0A=
=0A=
    if (!$version) {=0A=
        $versions =3D json_decode($httpClient-&gt;get($uriScheme . =
'://getcomposer.org/versions'), true);=0A=
        foreach ($versions[$channel] as $candidate) {=0A=
            if ($candidate['min-php'] &lt;=3D PHP_VERSION_ID) {=0A=
                $version =3D $candidate['version'];=0A=
                $downloadUrl =3D $candidate['path'];=0A=
                break;=0A=
            }=0A=
        }=0A=
=0A=
        if (!$version) {=0A=
            out('None of the '.count($versions[$channel]).' '.$channel.' =
version(s) of Composer matches your PHP version ('.PHP_VERSION.' / ID: =
'.PHP_VERSION_ID.')', 'error');=0A=
            exit(1);=0A=
        }=0A=
    } else {=0A=
        $downloadUrl =3D "/download/{$version}/composer.phar";=0A=
    }=0A=
=0A=
    $retries =3D 3;=0A=
    while ($retries--) {=0A=
        if (!$quiet) {=0A=
            out("Downloading $version...", 'info');=0A=
        }=0A=
=0A=
        $url       =3D "{$uriScheme}://getcomposer.org{$downloadUrl}";=0A=
        $errorHandler =3D new ErrorHandler();=0A=
        set_error_handler(array($errorHandler, 'handleError'));=0A=
=0A=
        // download signature file=0A=
        if (false =3D=3D=3D $disableTls) {=0A=
            $signature =3D $httpClient-&gt;get($url.'.sig');=0A=
            if (!$signature) {=0A=
                out('Download failed: '.$errorHandler-&gt;message, =
'error');=0A=
            } else {=0A=
                $signature =3D json_decode($signature, true);=0A=
                $signature =3D base64_decode($signature['sha384']);=0A=
            }=0A=
        }=0A=
=0A=
        $fh =3D fopen($file, 'w');=0A=
        if (!$fh) {=0A=
            out('Could not create file '.$file.': =
'.$errorHandler-&gt;message, 'error');=0A=
        }=0A=
        if (!fwrite($fh, $httpClient-&gt;get($url))) {=0A=
            out('Download failed: '.$errorHandler-&gt;message, 'error');=0A=
        }=0A=
        fclose($fh);=0A=
=0A=
        restore_error_handler();=0A=
        if ($errorHandler-&gt;message) {=0A=
            continue;=0A=
        }=0A=
=0A=
        try {=0A=
            // create a temp file ending in .phar since the Phar class =
only accepts that=0A=
            if ('.phar' !=3D=3D substr($file, -5)) {=0A=
                copy($file, $file.'.tmp.phar');=0A=
                $pharFile =3D $file.'.tmp.phar';=0A=
            } else {=0A=
                $pharFile =3D $file;=0A=
            }=0A=
=0A=
            // verify signature=0A=
            if (false =3D=3D=3D $disableTls) {=0A=
                $pubkeyid =3D =
openssl_pkey_get_public('file://'.$home.'/' . =
(preg_match('{^[0-9a-f]{40}$}', $version) ? 'keys.dev.pub' : =
'keys.tags.pub'));=0A=
                $algo =3D defined('OPENSSL_ALGO_SHA384') ? =
OPENSSL_ALGO_SHA384 : 'SHA384';=0A=
                if (!in_array('SHA384', openssl_get_md_methods())) {=0A=
                    out('SHA384 is not supported by your openssl =
extension, could not verify the phar file integrity', 'error');=0A=
                    exit(1);=0A=
                }=0A=
                $verified =3D 1 =3D=3D=3D =
openssl_verify(file_get_contents($file), $signature, $pubkeyid, $algo);=0A=
                openssl_free_key($pubkeyid);=0A=
                if (!$verified) {=0A=
                    out('Signature mismatch, could not verify the phar =
file integrity', 'error');=0A=
                    exit(1);=0A=
                }=0A=
            }=0A=
=0A=
            // test the phar validity=0A=
            if (!ini_get('phar.readonly')) {=0A=
                $phar =3D new Phar($pharFile);=0A=
                // free the variable to unlock the file=0A=
                unset($phar);=0A=
            }=0A=
            // clean up temp file if needed=0A=
            if ($file !=3D=3D $pharFile) {=0A=
                unlink($pharFile);=0A=
            }=0A=
            break;=0A=
        } catch (Exception $e) {=0A=
            if (!$e instanceof UnexpectedValueException &amp;&amp; !$e =
instanceof PharException) {=0A=
                throw $e;=0A=
            }=0A=
            // clean up temp file if needed=0A=
            if ($file !=3D=3D $pharFile) {=0A=
                unlink($pharFile);=0A=
            }=0A=
            unlink($file);=0A=
            if ($retries) {=0A=
                if (!$quiet) {=0A=
                   out('The download is corrupt, retrying...', 'error');=0A=
                }=0A=
            } else {=0A=
                out('The download is corrupt ('.$e-&gt;getMessage().'), =
aborting.', 'error');=0A=
                exit(1);=0A=
            }=0A=
        }=0A=
    }=0A=
=0A=
    if ($errorHandler-&gt;message) {=0A=
        out('The download failed repeatedly, aborting.', 'error');=0A=
        exit(1);=0A=
    }=0A=
=0A=
    chmod($file, 0755);=0A=
=0A=
    if (!$quiet) {=0A=
        out(PHP_EOL."Composer successfully installed to: " . $file, =
'success', false);=0A=
        out(PHP_EOL."Use it: php $installPath", 'info');=0A=
    }=0A=
}=0A=
=0A=
/**=0A=
 * colorize output=0A=
 */=0A=
function out($text, $color =3D null, $newLine =3D true)=0A=
{=0A=
    $styles =3D array(=0A=
        'success' =3D&gt; "\033[0;32m%s\033[0m",=0A=
        'error' =3D&gt; "\033[31;31m%s\033[0m",=0A=
        'info' =3D&gt; "\033[33;33m%s\033[0m"=0A=
    );=0A=
=0A=
    $format =3D '%s';=0A=
=0A=
    if (isset($styles[$color]) &amp;&amp; USE_ANSI) {=0A=
        $format =3D $styles[$color];=0A=
    }=0A=
=0A=
    if ($newLine) {=0A=
        $format .=3D PHP_EOL;=0A=
    }=0A=
=0A=
    printf($format, $text);=0A=
}=0A=
=0A=
/**=0A=
 * Returns the system-dependent Composer home location, which may not =
exist=0A=
 *=0A=
 * @return string=0A=
 */=0A=
function getHomeDir()=0A=
{=0A=
    $home =3D getenv('COMPOSER_HOME');=0A=
=0A=
    if (!$home) {=0A=
        $userDir =3D getUserDir();=0A=
=0A=
        if (defined('PHP_WINDOWS_VERSION_MAJOR')) {=0A=
            $home =3D $userDir.'/Composer';=0A=
        } else {=0A=
            $home =3D $userDir.'/.composer';=0A=
=0A=
            if (!is_dir($home) &amp;&amp; useXdg()) {=0A=
                // XDG Base Directory Specifications=0A=
                if (!($xdgConfig =3D getenv('XDG_CONFIG_HOME'))) {=0A=
                    $xdgConfig =3D $userDir.'/.config';=0A=
                }=0A=
                $home =3D $xdgConfig.'/composer';=0A=
            }=0A=
        }=0A=
    }=0A=
    return $home;=0A=
}=0A=
=0A=
/**=0A=
 * Returns the location of the user directory from the environment=0A=
 * @throws Runtime Exception If the environment value does not exists=0A=
 *=0A=
 * @return string=0A=
 */=0A=
function getUserDir()=0A=
{=0A=
    $userEnv =3D defined('PHP_WINDOWS_VERSION_MAJOR') ? 'APPDATA' : =
'HOME';=0A=
    $userDir =3D getenv($userEnv);=0A=
=0A=
    if (!$userDir) {=0A=
        throw new RuntimeException('The '.$userEnv.' or COMPOSER_HOME =
environment variable must be set for composer to run correctly');=0A=
    }=0A=
=0A=
    return rtrim(strtr($userDir, '\\', '/'), '/');=0A=
}=0A=
=0A=
/**=0A=
 * @return bool=0A=
 */=0A=
function useXdg()=0A=
{=0A=
    foreach (array_keys($_SERVER) as $key) {=0A=
        if (substr($key, 0, 4) =3D=3D=3D 'XDG_') {=0A=
            return true;=0A=
        }=0A=
    }=0A=
    return false;=0A=
}=0A=
=0A=
function validateCaFile($contents)=0A=
{=0A=
    // assume the CA is valid if php is vulnerable to=0A=
    // =
https://www.sektioneins.de/advisories/advisory-012013-php-openssl_x509_pa=
rse-memory-corruption-vulnerability.html=0A=
    if (=0A=
        PHP_VERSION_ID &lt;=3D 50327=0A=
        || (PHP_VERSION_ID &gt;=3D 50400 &amp;&amp; PHP_VERSION_ID &lt; =
50422)=0A=
        || (PHP_VERSION_ID &gt;=3D 50500 &amp;&amp; PHP_VERSION_ID &lt; =
50506)=0A=
    ) {=0A=
        return !empty($contents);=0A=
    }=0A=
=0A=
    return (bool) openssl_x509_parse($contents);=0A=
}=0A=
=0A=
class ErrorHandler=0A=
{=0A=
    public $message =3D '';=0A=
=0A=
    public function handleError($code, $msg)=0A=
    {=0A=
        if ($this-&gt;message) {=0A=
            $this-&gt;message .=3D "\n";=0A=
        }=0A=
        $this-&gt;message .=3D preg_replace('{^copy\(.*?\): }', '', =
$msg);=0A=
    }=0A=
}=0A=
=0A=
class HttpClient {=0A=
=0A=
    private $options =3D array('http' =3D&gt; array());=0A=
    private $disableTls =3D false;=0A=
=0A=
    public function __construct($disableTls =3D false, $cafile =3D false)=0A=
    {=0A=
        $this-&gt;disableTls =3D $disableTls;=0A=
        if ($this-&gt;disableTls =3D=3D=3D false) {=0A=
            if (!empty($cafile) &amp;&amp; !is_dir($cafile)) {=0A=
                if (!is_readable($cafile) || =
!validateCaFile(file_get_contents($cafile))) {=0A=
                    throw new RuntimeException('The configured cafile (' =
.$cafile. ') was not valid or could not be read.');=0A=
                }=0A=
            }=0A=
            $options =3D $this-&gt;getTlsStreamContextDefaults($cafile);=0A=
            $this-&gt;options =3D =
array_replace_recursive($this-&gt;options, $options);=0A=
        }=0A=
    }=0A=
=0A=
    public function get($url)=0A=
    {=0A=
        $context =3D $this-&gt;getStreamContext($url);=0A=
        $result =3D file_get_contents($url, false, $context);=0A=
=0A=
        if ($result &amp;&amp; extension_loaded('zlib')) {=0A=
            $decode =3D false;=0A=
            foreach ($http_response_header as $header) {=0A=
                if (preg_match('{^content-encoding: *gzip *$}i', =
$header)) {=0A=
                    $decode =3D true;=0A=
                    continue;=0A=
                } elseif (preg_match('{^HTTP/}i', $header)) {=0A=
                    $decode =3D false;=0A=
                }=0A=
            }=0A=
=0A=
            if ($decode) {=0A=
                if (version_compare(PHP_VERSION, '5.4.0', '&gt;=3D')) {=0A=
                    $result =3D zlib_decode($result);=0A=
                } else {=0A=
                    // work around issue with gzuncompress &amp; co that =
do not work with all gzip checksums=0A=
                    $result =3D =
file_get_contents('compress.zlib://data:application/octet-stream;base64,'=
.base64_encode($result));=0A=
                }=0A=
=0A=
                if (!$result) {=0A=
                    throw new RuntimeException('Failed to decode zlib =
stream');=0A=
                }=0A=
            }=0A=
        }=0A=
=0A=
        return $result;=0A=
    }=0A=
=0A=
    protected function getStreamContext($url)=0A=
    {=0A=
        if ($this-&gt;disableTls =3D=3D=3D false) {=0A=
            $host =3D parse_url($url, PHP_URL_HOST);=0A=
            if (PHP_VERSION_ID &lt; 50600) {=0A=
                $this-&gt;options['ssl']['CN_match'] =3D $host;=0A=
                $this-&gt;options['ssl']['SNI_server_name'] =3D $host;=0A=
            }=0A=
        }=0A=
        // Keeping the above mostly isolated from the code copied from =
Composer.=0A=
        return $this-&gt;getMergedStreamContext($url);=0A=
    }=0A=
=0A=
    protected function getTlsStreamContextDefaults($cafile)=0A=
    {=0A=
        $ciphers =3D implode(':', array(=0A=
            'ECDHE-RSA-AES128-GCM-SHA256',=0A=
            'ECDHE-ECDSA-AES128-GCM-SHA256',=0A=
            'ECDHE-RSA-AES256-GCM-SHA384',=0A=
            'ECDHE-ECDSA-AES256-GCM-SHA384',=0A=
            'DHE-RSA-AES128-GCM-SHA256',=0A=
            'DHE-DSS-AES128-GCM-SHA256',=0A=
            'kEDH+AESGCM',=0A=
            'ECDHE-RSA-AES128-SHA256',=0A=
            'ECDHE-ECDSA-AES128-SHA256',=0A=
            'ECDHE-RSA-AES128-SHA',=0A=
            'ECDHE-ECDSA-AES128-SHA',=0A=
            'ECDHE-RSA-AES256-SHA384',=0A=
            'ECDHE-ECDSA-AES256-SHA384',=0A=
            'ECDHE-RSA-AES256-SHA',=0A=
            'ECDHE-ECDSA-AES256-SHA',=0A=
            'DHE-RSA-AES128-SHA256',=0A=
            'DHE-RSA-AES128-SHA',=0A=
            'DHE-DSS-AES128-SHA256',=0A=
            'DHE-RSA-AES256-SHA256',=0A=
            'DHE-DSS-AES256-SHA',=0A=
            'DHE-RSA-AES256-SHA',=0A=
            'AES128-GCM-SHA256',=0A=
             'AES256-GCM-SHA384',=0A=
            'ECDHE-RSA-RC4-SHA',=0A=
            'ECDHE-ECDSA-RC4-SHA',=0A=
            'AES128',=0A=
            'AES256',=0A=
            'RC4-SHA',=0A=
            'HIGH',=0A=
            '!aNULL',=0A=
            '!eNULL',=0A=
            '!EXPORT',=0A=
            '!DES',=0A=
            '!3DES',=0A=
            '!MD5',=0A=
            '!PSK'=0A=
        ));=0A=
=0A=
        /**=0A=
         * CN_match and SNI_server_name are only known once a URL is =
passed.=0A=
         * They will be set in the getOptionsForUrl() method which =
receives a URL.=0A=
         *=0A=
         * cafile or capath can be overridden by passing in those =
options to constructor.=0A=
         */=0A=
        $options =3D array(=0A=
            'ssl' =3D&gt; array(=0A=
                'ciphers' =3D&gt; $ciphers,=0A=
                'verify_peer' =3D&gt; true,=0A=
                'verify_depth' =3D&gt; 7,=0A=
                'SNI_enabled' =3D&gt; true,=0A=
            )=0A=
        );=0A=
=0A=
        /**=0A=
         * Attempt to find a local cafile or throw an exception.=0A=
         * The user may go download one if this occurs.=0A=
         */=0A=
        if (!$cafile) {=0A=
            $cafile =3D self::getSystemCaRootBundlePath();=0A=
        }=0A=
        if (is_dir($cafile)) {=0A=
            $options['ssl']['capath'] =3D $cafile;=0A=
        } elseif ($cafile) {=0A=
            $options['ssl']['cafile'] =3D $cafile;=0A=
        } else {=0A=
            throw new RuntimeException('A valid cafile could not be =
located automatically.');=0A=
        }=0A=
=0A=
        /**=0A=
         * Disable TLS compression to prevent CRIME attacks where =
supported.=0A=
         */=0A=
        if (version_compare(PHP_VERSION, '5.4.13') &gt;=3D 0) {=0A=
            $options['ssl']['disable_compression'] =3D true;=0A=
        }=0A=
=0A=
        return $options;=0A=
    }=0A=
=0A=
    /**=0A=
     * function copied from =
Composer\Util\StreamContextFactory::getContext=0A=
     *=0A=
     * Any changes should be applied there as well, or backported here.=0A=
     *=0A=
     * @param string $url URL the context is to be used for=0A=
     * @return resource Default context=0A=
     * @throws \RuntimeException if https proxy required and OpenSSL =
uninstalled=0A=
     */=0A=
    protected function getMergedStreamContext($url)=0A=
    {=0A=
        $options =3D $this-&gt;options;=0A=
=0A=
        // Handle system proxy=0A=
        if (!empty($_SERVER['HTTP_PROXY']) || =
!empty($_SERVER['http_proxy'])) {=0A=
            // Some systems seem to rely on a lowercased version =
instead...=0A=
            $proxy =3D parse_url(!empty($_SERVER['http_proxy']) ? =
$_SERVER['http_proxy'] : $_SERVER['HTTP_PROXY']);=0A=
        }=0A=
=0A=
        if (!empty($proxy)) {=0A=
            $proxyURL =3D isset($proxy['scheme']) ? $proxy['scheme'] . =
'://' : '';=0A=
            $proxyURL .=3D isset($proxy['host']) ? $proxy['host'] : '';=0A=
=0A=
            if (isset($proxy['port'])) {=0A=
                $proxyURL .=3D ":" . $proxy['port'];=0A=
            } elseif ('http://' =3D=3D substr($proxyURL, 0, 7)) {=0A=
                $proxyURL .=3D ":80";=0A=
            } elseif ('https://' =3D=3D substr($proxyURL, 0, 8)) {=0A=
                $proxyURL .=3D ":443";=0A=
            }=0A=
=0A=
            // http(s):// is not supported in proxy=0A=
            $proxyURL =3D str_replace(array('http://', 'https://'), =
array('tcp://', 'ssl://'), $proxyURL);=0A=
=0A=
            if (0 =3D=3D=3D strpos($proxyURL, 'ssl:') &amp;&amp; =
!extension_loaded('openssl')) {=0A=
                throw new RuntimeException('You must enable the openssl =
extension to use a proxy over https');=0A=
            }=0A=
=0A=
            $options['http'] =3D array(=0A=
                'proxy'           =3D&gt; $proxyURL,=0A=
            );=0A=
=0A=
            // enabled request_fulluri unless it is explicitly disabled=0A=
            switch (parse_url($url, PHP_URL_SCHEME)) {=0A=
                case 'http': // default request_fulluri to true=0A=
                    $reqFullUriEnv =3D =
getenv('HTTP_PROXY_REQUEST_FULLURI');=0A=
                    if ($reqFullUriEnv =3D=3D=3D false || $reqFullUriEnv =
=3D=3D=3D '' || (strtolower($reqFullUriEnv) !=3D=3D 'false' &amp;&amp; =
(bool) $reqFullUriEnv)) {=0A=
                        $options['http']['request_fulluri'] =3D true;=0A=
                    }=0A=
                    break;=0A=
                case 'https': // default request_fulluri to true=0A=
                    $reqFullUriEnv =3D =
getenv('HTTPS_PROXY_REQUEST_FULLURI');=0A=
                    if ($reqFullUriEnv =3D=3D=3D false || $reqFullUriEnv =
=3D=3D=3D '' || (strtolower($reqFullUriEnv) !=3D=3D 'false' &amp;&amp; =
(bool) $reqFullUriEnv)) {=0A=
                        $options['http']['request_fulluri'] =3D true;=0A=
                    }=0A=
                    break;=0A=
            }=0A=
=0A=
=0A=
            if (isset($proxy['user'])) {=0A=
                $auth =3D urldecode($proxy['user']);=0A=
                if (isset($proxy['pass'])) {=0A=
                    $auth .=3D ':' . urldecode($proxy['pass']);=0A=
                }=0A=
                $auth =3D base64_encode($auth);=0A=
=0A=
                $options['http']['header'] =3D "Proxy-Authorization: =
Basic {$auth}\r\n";=0A=
            }=0A=
        }=0A=
=0A=
        if (isset($options['http']['header'])) {=0A=
            $options['http']['header'] .=3D "Connection: close\r\n";=0A=
        } else {=0A=
            $options['http']['header'] =3D "Connection: close\r\n";=0A=
        }=0A=
        if (extension_loaded('zlib')) {=0A=
            $options['http']['header'] .=3D "Accept-Encoding: gzip\r\n";=0A=
        }=0A=
        $options['http']['header'] .=3D "User-Agent: Composer =
Installer\r\n";=0A=
        $options['http']['protocol_version'] =3D 1.1;=0A=
=0A=
        return stream_context_create($options);=0A=
    }=0A=
=0A=
    /**=0A=
    * This method was adapted from Sslurp.=0A=
    * https://github.com/EvanDotPro/Sslurp=0A=
    *=0A=
    * (c) Evan Coury &lt;me@evancoury.com&gt;=0A=
    *=0A=
    * For the full copyright and license information, please see below:=0A=
    *=0A=
    * Copyright (c) 2013, Evan Coury=0A=
    * All rights reserved.=0A=
    *=0A=
    * Redistribution and use in source and binary forms, with or without =
modification,=0A=
    * are permitted provided that the following conditions are met:=0A=
    *=0A=
    *     * Redistributions of source code must retain the above =
copyright notice,=0A=
    *       this list of conditions and the following disclaimer.=0A=
    *=0A=
    *     * Redistributions in binary form must reproduce the above =
copyright notice,=0A=
    *       this list of conditions and the following disclaimer in the =
documentation=0A=
    *       and/or other materials provided with the distribution.=0A=
    *=0A=
    * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND =
CONTRIBUTORS "AS IS" AND=0A=
    * ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, =
THE IMPLIED=0A=
    * WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE =
ARE=0A=
    * DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT OWNER OR CONTRIBUTORS =
BE LIABLE FOR=0A=
    * ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR =
CONSEQUENTIAL DAMAGES=0A=
    * (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR =
SERVICES;=0A=
    * LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER =
CAUSED AND ON=0A=
    * ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR =
TORT=0A=
    * (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE =
USE OF THIS=0A=
    * SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.=0A=
    */=0A=
    public static function getSystemCaRootBundlePath()=0A=
    {=0A=
        static $caPath =3D null;=0A=
=0A=
        if ($caPath !=3D=3D null) {=0A=
            return $caPath;=0A=
        }=0A=
=0A=
        // If SSL_CERT_FILE env variable points to a valid =
certificate/bundle, use that.=0A=
        // This mimics how OpenSSL uses the SSL_CERT_FILE env variable.=0A=
        $envCertFile =3D getenv('SSL_CERT_FILE');=0A=
        if ($envCertFile &amp;&amp; is_readable($envCertFile) &amp;&amp; =
validateCaFile(file_get_contents($envCertFile))) {=0A=
            return $caPath =3D $envCertFile;=0A=
        }=0A=
=0A=
        // If SSL_CERT_DIR env variable points to a valid =
certificate/bundle, use that.=0A=
        // This mimics how OpenSSL uses the SSL_CERT_FILE env variable.=0A=
        $envCertDir =3D getenv('SSL_CERT_DIR');=0A=
        if ($envCertDir &amp;&amp; is_dir($envCertDir) &amp;&amp; =
is_readable($envCertDir)) {=0A=
            return $caPath =3D $envCertDir;=0A=
        }=0A=
=0A=
        $configured =3D ini_get('openssl.cafile');=0A=
        if ($configured &amp;&amp; strlen($configured) &gt; 0 &amp;&amp; =
is_readable($configured) &amp;&amp; =
validateCaFile(file_get_contents($configured))) {=0A=
            return $caPath =3D $configured;=0A=
        }=0A=
=0A=
        $configured =3D ini_get('openssl.capath');=0A=
        if ($configured &amp;&amp; is_dir($configured) &amp;&amp; =
is_readable($configured)) {=0A=
            return $caPath =3D $configured;=0A=
        }=0A=
=0A=
        $caBundlePaths =3D array(=0A=
            '/etc/pki/tls/certs/ca-bundle.crt', // Fedora, RHEL, CentOS =
(ca-certificates package)=0A=
            '/etc/ssl/certs/ca-certificates.crt', // Debian, Ubuntu, =
Gentoo, Arch Linux (ca-certificates package)=0A=
            '/etc/ssl/ca-bundle.pem', // SUSE, openSUSE (ca-certificates =
package)=0A=
            '/usr/local/share/certs/ca-root-nss.crt', // FreeBSD =
(ca_root_nss_package)=0A=
            '/usr/ssl/certs/ca-bundle.crt', // Cygwin=0A=
            '/opt/local/share/curl/curl-ca-bundle.crt', // OS X =
macports, curl-ca-bundle package=0A=
            '/usr/local/share/curl/curl-ca-bundle.crt', // Default cURL =
CA bunde path (without --with-ca-bundle option)=0A=
            '/usr/share/ssl/certs/ca-bundle.crt', // Really old RedHat?=0A=
            '/etc/ssl/cert.pem', // OpenBSD=0A=
            '/usr/local/etc/ssl/cert.pem', // FreeBSD 10.x=0A=
        );=0A=
=0A=
        foreach ($caBundlePaths as $caBundle) {=0A=
            if (@is_readable($caBundle) &amp;&amp; =
validateCaFile(file_get_contents($caBundle))) {=0A=
                return $caPath =3D $caBundle;=0A=
            }=0A=
        }=0A=
=0A=
        foreach ($caBundlePaths as $caBundle) {=0A=
            $caBundle =3D dirname($caBundle);=0A=
            if (is_dir($caBundle) &amp;&amp; glob($caBundle.'/*')) {=0A=
                return $caPath =3D $caBundle;=0A=
            }=0A=
        }=0A=
=0A=
        return $caPath =3D false;=0A=
    }=0A=
=0A=
    public static function getPackagedCaFile()=0A=
    {=0A=
        return &lt;&lt;&lt;CACERT=0A=
##=0A=
## Bundle of CA Root Certificates=0A=
##=0A=
## Certificate data from Mozilla as of: Wed Oct 28 22:42:42 2015=0A=
##=0A=
## This is a bundle of X.509 certificates of public Certificate =
Authorities=0A=
## (CA). These were automatically extracted from Mozilla's root =
certificates=0A=
## file (certdata.txt).  This file can be found at=0A=
## =
https://raw.githubusercontent.com/bagder/ca-bundle/master/ca-bundle.crt=0A=
##=0A=
## It contains the certificates in PEM format and therefore=0A=
## can be directly used with curl / libcurl / php_curl, or with=0A=
## an Apache+mod_ssl webserver for SSL client authentication.=0A=
## Just configure this file as the SSLCACertificateFile.=0A=
##=0A=
## Conversion done with mk-ca-bundle.pl version 1.25.=0A=
## SHA1: 6d7d2f0a4fae587e7431be191a081ac1257d300a=0A=
##=0A=
=0A=
=0A=
Equifax Secure CA=0A=
=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=0A=
-----BEGIN CERTIFICATE-----=0A=
MIIDIDCCAomgAwIBAgIENd70zzANBgkqhkiG9w0BAQUFADBOMQswCQYDVQQGEwJVUzEQMA4GA=
1UE=0A=
ChMHRXF1aWZheDEtMCsGA1UECxMkRXF1aWZheCBTZWN1cmUgQ2VydGlmaWNhdGUgQXV0aG9ya=
XR5=0A=
MB4XDTk4MDgyMjE2NDE1MVoXDTE4MDgyMjE2NDE1MVowTjELMAkGA1UEBhMCVVMxEDAOBgNVB=
AoT=0A=
B0VxdWlmYXgxLTArBgNVBAsTJEVxdWlmYXggU2VjdXJlIENlcnRpZmljYXRlIEF1dGhvcml0e=
TCB=0A=
nzANBgkqhkiG9w0BAQEFAAOBjQAwgYkCgYEAwV2xWGcIYu6gmi0fCG2RFGiYCh7+2gRvE4RiI=
cPR=0A=
fM6fBeC4AfBONOziipUEZKzxa1NfBbPLZ4C/QgKO/t0BCezhABRP/PvwDN1Dulsr4R+AcJkVV=
5MW=0A=
8Q+XarfCaCMczE1ZMKxRHjuvK9buY0V7xdlfUNLjUA86iOe/FP3gx7kCAwEAAaOCAQkwggEFM=
HAG=0A=
A1UdHwRpMGcwZaBjoGGkXzBdMQswCQYDVQQGEwJVUzEQMA4GA1UEChMHRXF1aWZheDEtMCsGA=
1UE=0A=
CxMkRXF1aWZheCBTZWN1cmUgQ2VydGlmaWNhdGUgQXV0aG9yaXR5MQ0wCwYDVQQDEwRDUkwxM=
BoG=0A=
A1UdEAQTMBGBDzIwMTgwODIyMTY0MTUxWjALBgNVHQ8EBAMCAQYwHwYDVR0jBBgwFoAUSOZo+=
SvS=0A=
spXXR9gjIBBPM5iQn9QwHQYDVR0OBBYEFEjmaPkr0rKV10fYIyAQTzOYkJ/UMAwGA1UdEwQFM=
AMB=0A=
Af8wGgYJKoZIhvZ9B0EABA0wCxsFVjMuMGMDAgbAMA0GCSqGSIb3DQEBBQUAA4GBAFjOKer89=
961=0A=
zgK5F7WF0bnj4JXMJTENAKaSbn+2kmOeUJXRmm/kEd5jhW6Y7qj/WsjTVbJmcVfewCHrPSqnI=
0kB=0A=
BIZCe/zuf6IWUrVnZ9NA2zsmWLIodz2uFHdh1voqZiegDfqnc1zqcPGUIWVEX/r87yloqaKHe=
e95=0A=
70+sB3c4=0A=
-----END CERTIFICATE-----=0A=
=0A=
GlobalSign Root CA=0A=
=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=0A=
-----BEGIN CERTIFICATE-----=0A=
MIIDdTCCAl2gAwIBAgILBAAAAAABFUtaw5QwDQYJKoZIhvcNAQEFBQAwVzELMAkGA1UEBhMCQ=
kUx=0A=
GTAXBgNVBAoTEEdsb2JhbFNpZ24gbnYtc2ExEDAOBgNVBAsTB1Jvb3QgQ0ExGzAZBgNVBAMTE=
kds=0A=
b2JhbFNpZ24gUm9vdCBDQTAeFw05ODA5MDExMjAwMDBaFw0yODAxMjgxMjAwMDBaMFcxCzAJB=
gNV=0A=
BAYTAkJFMRkwFwYDVQQKExBHbG9iYWxTaWduIG52LXNhMRAwDgYDVQQLEwdSb290IENBMRswG=
QYD=0A=
VQQDExJHbG9iYWxTaWduIFJvb3QgQ0EwggEiMA0GCSqGSIb3DQEBAQUAA4IBDwAwggEKAoIBA=
QDa=0A=
DuaZjc6j40+Kfvvxi4Mla+pIH/EqsLmVEQS98GPR4mdmzxzdzxtIK+6NiY6arymAZavpxy0Sy=
6sc=0A=
THAHoT0KMM0VjU/43dSMUBUc71DuxC73/OlS8pF94G3VNTCOXkNz8kHp1Wrjsok6Vjk4bwY8i=
Glb=0A=
Kk3Fp1S4bInMm/k8yuX9ifUSPJJ4ltbcdG6TRGHRjcdGsnUOhugZitVtbNV4FpWi6cgKOOvyJ=
BNP=0A=
c1STE4U6G7weNLWLBYy5d4ux2x8gkasJU26Qzns3dLlwR5EiUWMWea6xrkEmCMgZK9FGqkjWZ=
CrX=0A=
gzT/LCrBbBlDSgeF59N89iFo7+ryUp9/k5DPAgMBAAGjQjBAMA4GA1UdDwEB/wQEAwIBBjAPB=
gNV=0A=
HRMBAf8EBTADAQH/MB0GA1UdDgQWBBRge2YaRQ2XyolQL30EzTSo//z9SzANBgkqhkiG9w0BA=
QUF=0A=
AAOCAQEA1nPnfE920I2/7LqivjTFKDK1fPxsnCwrvQmeU79rXqoRSLblCKOzyj1hTdNGCbM+w=
6Dj=0A=
Y1Ub8rrvrTnhQ7k4o+YviiY776BQVvnGCv04zcQLcFGUl5gE38NflNUVyRRBnMRddWQVDf9VM=
OyG=0A=
j/8N7yy5Y0b2qvzfvGn9LhJIZJrglfCm7ymPAbEVtQwdpf5pLGkkeB6zpxxxYu7KyJesF12Kw=
vhH=0A=
hm4qxFYxldBniYUr+WymXUadDKqC5JlR3XC321Y9YeRq4VzW9v493kHMB65jUr9TU/Qr6cf9t=
veC=0A=
X4XSQRjbgbMEHMUfpIBvFSDJ3gyICh3WZlXi/EjJKSZp4A=3D=3D=0A=
-----END CERTIFICATE-----=0A=
=0A=
GlobalSign Root CA - R2=0A=
=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=0A=
-----BEGIN CERTIFICATE-----=0A=
MIIDujCCAqKgAwIBAgILBAAAAAABD4Ym5g0wDQYJKoZIhvcNAQEFBQAwTDEgMB4GA1UECxMXR=
2xv=0A=
YmFsU2lnbiBSb290IENBIC0gUjIxEzARBgNVBAoTCkdsb2JhbFNpZ24xEzARBgNVBAMTCkdsb=
2Jh=0A=
bFNpZ24wHhcNMDYxMjE1MDgwMDAwWhcNMjExMjE1MDgwMDAwWjBMMSAwHgYDVQQLExdHbG9iY=
WxT=0A=
aWduIFJvb3QgQ0EgLSBSMjETMBEGA1UEChMKR2xvYmFsU2lnbjETMBEGA1UEAxMKR2xvYmFsU=
2ln=0A=
bjCCASIwDQYJKoZIhvcNAQEBBQADggEPADCCAQoCggEBAKbPJA6+Lm8omUVCxKs+IVSbC9N/h=
HD6=0A=
ErPLv4dfxn+G07IwXNb9rfF73OX4YJYJkhD10FPe+3t+c4isUoh7SqbKSaZeqKeMWhG8eoLrv=
ozp=0A=
s6yWJQeXSpkqBy+0Hne/ig+1AnwblrjFuTosvNYSuetZfeLQBoZfXklqtTleiDTsvHgMCJiEb=
KjN=0A=
S7SgfQx5TfC4LcshytVsW33hoCmEofnTlEnLJGKRILzdC9XZzPnqJworc5HGnRusyMvo4KD0L=
5CL=0A=
TfuwNhv2GXqF4G3yYROIXJ/gkwpRl4pazq+r1feqCapgvdzZX99yqWATXgAByUr6P6TqBwMhA=
o6C=0A=
ygPCm48CAwEAAaOBnDCBmTAOBgNVHQ8BAf8EBAMCAQYwDwYDVR0TAQH/BAUwAwEB/zAdBgNVH=
Q4E=0A=
FgQUm+IHV2ccHsBqBt5ZtJot39wZhi4wNgYDVR0fBC8wLTAroCmgJ4YlaHR0cDovL2NybC5nb=
G9i=0A=
YWxzaWduLm5ldC9yb290LXIyLmNybDAfBgNVHSMEGDAWgBSb4gdXZxwewGoG3lm0mi3f3BmGL=
jAN=0A=
BgkqhkiG9w0BAQUFAAOCAQEAmYFThxxol4aR7OBKuEQLq4GsJ0/WwbgcQ3izDJr86iw8bmEbT=
Usp=0A=
9Z8FHSbBuOmDAGJFtqkIk7mpM0sYmsL4h4hO291xNBrBVNpGP+DTKqttVCL1OmLNIG+6KYnX3=
ZHu=0A=
01yiPqFbQfXf5WRDLenVOavSot+3i9DAgBkcRcAtjOj4LaR0VknFBbVPFd5uRHg5h6h+u/N5G=
JG7=0A=
9G+dwfCMNYxdAfvDbbnvRG15RjF+Cv6pgsH/76tuIMRQyV+dTZsXjAzlAcmgQWpzU/qlULRuJ=
Q/7=0A=
TBj0/VLZjmmx6BEP3ojY+x1J96relc8geMJgEtslQIxq/H5COEBkEveegeGTLg=3D=3D=0A=
-----END CERTIFICATE-----=0A=
=0A=
Verisign Class 3 Public Primary Certification Authority - G3=0A=
=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=
=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=
=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=0A=
-----BEGIN CERTIFICATE-----=0A=
MIIEGjCCAwICEQCbfgZJoz5iudXukEhxKe9XMA0GCSqGSIb3DQEBBQUAMIHKMQswCQYDVQQGE=
wJV=0A=
UzEXMBUGA1UEChMOVmVyaVNpZ24sIEluYy4xHzAdBgNVBAsTFlZlcmlTaWduIFRydXN0IE5ld=
Hdv=0A=
cmsxOjA4BgNVBAsTMShjKSAxOTk5IFZlcmlTaWduLCBJbmMuIC0gRm9yIGF1dGhvcml6ZWQgd=
XNl=0A=
IG9ubHkxRTBDBgNVBAMTPFZlcmlTaWduIENsYXNzIDMgUHVibGljIFByaW1hcnkgQ2VydGlma=
WNh=0A=
dGlvbiBBdXRob3JpdHkgLSBHMzAeFw05OTEwMDEwMDAwMDBaFw0zNjA3MTYyMzU5NTlaMIHKM=
Qsw=0A=
CQYDVQQGEwJVUzEXMBUGA1UEChMOVmVyaVNpZ24sIEluYy4xHzAdBgNVBAsTFlZlcmlTaWduI=
FRy=0A=
dXN0IE5ldHdvcmsxOjA4BgNVBAsTMShjKSAxOTk5IFZlcmlTaWduLCBJbmMuIC0gRm9yIGF1d=
Ghv=0A=
cml6ZWQgdXNlIG9ubHkxRTBDBgNVBAMTPFZlcmlTaWduIENsYXNzIDMgUHVibGljIFByaW1hc=
nkg=0A=
Q2VydGlmaWNhdGlvbiBBdXRob3JpdHkgLSBHMzCCASIwDQYJKoZIhvcNAQEBBQADggEPADCCA=
QoC=0A=
ggEBAMu6nFL8eB8aHm8bN3O9+MlrlBIwT/A2R/XQkQr1F8ilYcEWQE37imGQ5XYgwREGfassb=
qb1=0A=
EUGO+i2tKmFZpGcmTNDovFJbcCAEWNF6yaRpvIMXZK0Fi7zQWM6NjPXr8EJJC52XJ2cybuGuk=
xUc=0A=
cLwgTS8Y3pKI6GyFVxEa6X7jJhFUokWWVYPKMIno3Nij7SqAP395ZVc+FSBmCC+Vk7+qRy+oR=
pfw=0A=
EuL+wgorUeZ25rdGt+INpsyow0xZVYnm6FNcHOqd8GIWC6fJXwzw3sJ2zq/3avL6QaaiMxTJ5=
Xpj=0A=
055iN9WFZZ4O5lMkdBteHRJTW8cs54NJOxWuimi5V5cCAwEAATANBgkqhkiG9w0BAQUFAAOCA=
QEA=0A=
ERSWwauSCPc/L8my/uRan2Te2yFPhpk0djZX3dAVL8WtfxUfN2JzPtTnX84XA9s1+ivbrmAJX=
x5f=0A=
j267Cz3qWhMeDGBvtcC1IyIuBwvLqXTLR7sdwdela8wv0kL9Sd2nic9TutoAWii/gt/4uhMdU=
IaC=0A=
/Y4wjylGsB49Ndo4YhYYSq3mtlFs3q9i6wHQHiT+eo8SGhJouPtmmRQURVyu565pF4ErWjfJX=
ir0=0A=
xuKhXFSbplQAz/DxwceYMBo7Nhbbo27q/a2ywtrvAkcTisDxszGtTxzhT5yvDwyd93gN2PQ1V=
oDa=0A=
t20Xj50egWTh/sVFuq1ruQp6Tk9LhO5L8X3dEQ=3D=3D=0A=
-----END CERTIFICATE-----=0A=
=0A=
Verisign Class 4 Public Primary Certification Authority - G3=0A=
=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=
=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=
=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=0A=
-----BEGIN CERTIFICATE-----=0A=
MIIEGjCCAwICEQDsoKeLbnVqAc/EfMwvlF7XMA0GCSqGSIb3DQEBBQUAMIHKMQswCQYDVQQGE=
wJV=0A=
UzEXMBUGA1UEChMOVmVyaVNpZ24sIEluYy4xHzAdBgNVBAsTFlZlcmlTaWduIFRydXN0IE5ld=
Hdv=0A=
cmsxOjA4BgNVBAsTMShjKSAxOTk5IFZlcmlTaWduLCBJbmMuIC0gRm9yIGF1dGhvcml6ZWQgd=
XNl=0A=
IG9ubHkxRTBDBgNVBAMTPFZlcmlTaWduIENsYXNzIDQgUHVibGljIFByaW1hcnkgQ2VydGlma=
WNh=0A=
dGlvbiBBdXRob3JpdHkgLSBHMzAeFw05OTEwMDEwMDAwMDBaFw0zNjA3MTYyMzU5NTlaMIHKM=
Qsw=0A=
CQYDVQQGEwJVUzEXMBUGA1UEChMOVmVyaVNpZ24sIEluYy4xHzAdBgNVBAsTFlZlcmlTaWduI=
FRy=0A=
dXN0IE5ldHdvcmsxOjA4BgNVBAsTMShjKSAxOTk5IFZlcmlTaWduLCBJbmMuIC0gRm9yIGF1d=
Ghv=0A=
cml6ZWQgdXNlIG9ubHkxRTBDBgNVBAMTPFZlcmlTaWduIENsYXNzIDQgUHVibGljIFByaW1hc=
nkg=0A=
Q2VydGlmaWNhdGlvbiBBdXRob3JpdHkgLSBHMzCCASIwDQYJKoZIhvcNAQEBBQADggEPADCCA=
QoC=0A=
ggEBAK3LpRFpxlmr8Y+1GQ9Wzsy1HyDkniYlS+BzZYlZ3tCD5PUPtbut8XzoIfzk6AzufEUiG=
XaS=0A=
tBO3IFsJ+mGuqPKljYXCKtbeZjbSmwL0qJJgfJxptI8kHtCGUvYynEFYHiK9zUVilQhu0GbdU=
6LM=0A=
8BDcVHOLBKFGMzNcF0C5nk3T875Vg+ixiY5afJqWIpA7iCXy0lOIAgwLePLmNxdLMEYH5IBtp=
tiW=0A=
Lugs+BGzOA1mppvqySNb247i8xOOGlktqgLw7KSHZtzBP/XYufTsgsbSPZUd5cBPhMnZo0QoB=
mrX=0A=
Razwa2rvTl/4EYIeOGM0ZlDUPpNz+jDDZq3/ky2X7wMCAwEAATANBgkqhkiG9w0BAQUFAAOCA=
QEA=0A=
j/ola09b5KROJ1WrIhVZPMq1CtRK26vdoV9TxaBXOcLORyu+OshWv8LZJxA6sQU8wHcxuzrTB=
Xtt=0A=
mhwwjIDLk5Mqg6sFUYICABFna/OIYUdfA5PVWw3g8dShMjWFsjrbsIKr0csKvE+MW8VLADsfK=
oKm=0A=
fjaF3H48ZwC15DtS4KjrXRX5xm3wrR0OhbepmnMUWluPQSjA1egtTaRezarZ7c7c2NU8Qh0Xw=
RJd=0A=
RTjDOPP8hS6DRkiy1yBfkjaP53kPmF6Z6PDQpLv1U70qzlmwr25/bLvSHgCwIe34QWKCudiyx=
LtG=0A=
UPMxxY8BqHTr9Xgn2uf3ZkPznoM+IKrDNWCRzg=3D=3D=0A=
-----END CERTIFICATE-----=0A=
=0A=
Entrust.net Premium 2048 Secure Server CA=0A=
=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=
=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=0A=
-----BEGIN CERTIFICATE-----=0A=
MIIEKjCCAxKgAwIBAgIEOGPe+DANBgkqhkiG9w0BAQUFADCBtDEUMBIGA1UEChMLRW50cnVzd=
C5u=0A=
ZXQxQDA+BgNVBAsUN3d3dy5lbnRydXN0Lm5ldC9DUFNfMjA0OCBpbmNvcnAuIGJ5IHJlZi4gK=
Gxp=0A=
bWl0cyBsaWFiLikxJTAjBgNVBAsTHChjKSAxOTk5IEVudHJ1c3QubmV0IExpbWl0ZWQxMzAxB=
gNV=0A=
BAMTKkVudHJ1c3QubmV0IENlcnRpZmljYXRpb24gQXV0aG9yaXR5ICgyMDQ4KTAeFw05OTEyM=
jQx=0A=
NzUwNTFaFw0yOTA3MjQxNDE1MTJaMIG0MRQwEgYDVQQKEwtFbnRydXN0Lm5ldDFAMD4GA1UEC=
xQ3=0A=
d3d3LmVudHJ1c3QubmV0L0NQU18yMDQ4IGluY29ycC4gYnkgcmVmLiAobGltaXRzIGxpYWIuK=
TEl=0A=
MCMGA1UECxMcKGMpIDE5OTkgRW50cnVzdC5uZXQgTGltaXRlZDEzMDEGA1UEAxMqRW50cnVzd=
C5u=0A=
ZXQgQ2VydGlmaWNhdGlvbiBBdXRob3JpdHkgKDIwNDgpMIIBIjANBgkqhkiG9w0BAQEFAAOCA=
Q8A=0A=
MIIBCgKCAQEArU1LqRKGsuqjIAcVFmQqK0vRvwtKTY7tgHalZ7d4QMBzQshowNtTK91euHaYN=
ZOL=0A=
Gp18EzoOH1u3Hs/lJBQesYGpjX24zGtLA/ECDNyrpUAkAH90lKGdCCmziAv1h3edVc3kw37Xa=
mSr=0A=
hRSGlVuXMlBvPci6Zgzj/L24ScF2iUkZ/cCovYmjZy/Gn7xxGWC4LeksyZB2ZnuU4q941mVTX=
TzW=0A=
nLLPKQP5L6RQstRIzgUyVYr9smRMDuSYB3Xbf9+5CFVghTAp+XtIpGmG4zU/HoZdenoVve8Aj=
hUi=0A=
VBcAkCaTvA5JaJG/+EfTnZVCwQ5N328mz8MYIWJmQ3DW1cAH4QIDAQABo0IwQDAOBgNVHQ8BA=
f8E=0A=
BAMCAQYwDwYDVR0TAQH/BAUwAwEB/zAdBgNVHQ4EFgQUVeSB0RGAvtiJuQijMfmhJAkWuXAwD=
QYJ=0A=
KoZIhvcNAQEFBQADggEBADubj1abMOdTmXx6eadNl9cZlZD7Bh/KM3xGY4+WZiT6QBshJ8rmc=
nPy=0A=
T/4xmf3IDExoU8aAghOY+rat2l098c5u9hURlIIM7j+VrxGrD9cv3h8Dj1csHsm7mhpElesYT=
6Yf=0A=
zX1XEC+bBAlahLVu2B064dae0Wx5XnkcFMXj0EyTO2U87d89vqbllRrDtRnDvV5bu/8j72gZy=
xKT=0A=
J1wDLW8w0B62GqzeWvfRqqgnpv55gcR5mTNXuhKwqeBCbJPKVt7+bYQLCIt+jerXmCHG8+c8e=
S9e=0A=
nNFMFY3h7CI3zJpDC5fcgJCNs2ebb0gIFVbPv/ErfF6adulZkMV8gzURZVE=3D=0A=
-----END CERTIFICATE-----=0A=
=0A=
Baltimore CyberTrust Root=0A=
=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=0A=
-----BEGIN CERTIFICATE-----=0A=
MIIDdzCCAl+gAwIBAgIEAgAAuTANBgkqhkiG9w0BAQUFADBaMQswCQYDVQQGEwJJRTESMBAGA=
1UE=0A=
ChMJQmFsdGltb3JlMRMwEQYDVQQLEwpDeWJlclRydXN0MSIwIAYDVQQDExlCYWx0aW1vcmUgQ=
3li=0A=
ZXJUcnVzdCBSb290MB4XDTAwMDUxMjE4NDYwMFoXDTI1MDUxMjIzNTkwMFowWjELMAkGA1UEB=
hMC=0A=
SUUxEjAQBgNVBAoTCUJhbHRpbW9yZTETMBEGA1UECxMKQ3liZXJUcnVzdDEiMCAGA1UEAxMZQ=
mFs=0A=
dGltb3JlIEN5YmVyVHJ1c3QgUm9vdDCCASIwDQYJKoZIhvcNAQEBBQADggEPADCCAQoCggEBA=
KME=0A=
uyKrmD1X6CZymrV51Cni4eiVgLGw41uOKymaZN+hXe2wCQVt2yguzmKiYv60iNoS6zjrIZ3AQ=
SsB=0A=
UnuId9Mcj8e6uYi1agnnc+gRQKfRzMpijS3ljwumUNKoUMMo6vWrJYeKmpYcqWe4PwzV9/lSE=
y/C=0A=
G9VwcPCPwBLKBsua4dnKM3p31vjsufFoREJIE9LAwqSuXmD+tqYF/LTdB1kC1FkYmGP1pWPgk=
Ax9=0A=
XbIGevOF6uvUA65ehD5f/xXtabz5OTZydc93Uk3zyZAsuT3lySNTPx8kmCFcB5kpvcY67Oduh=
jpr=0A=
l3RjM71oGDHweI12v/yejl0qhqdNkNwnGjkCAwEAAaNFMEMwHQYDVR0OBBYEFOWdWTCCR1jMr=
PoI=0A=
VDaGezq1BE3wMBIGA1UdEwEB/wQIMAYBAf8CAQMwDgYDVR0PAQH/BAQDAgEGMA0GCSqGSIb3D=
QEB=0A=
BQUAA4IBAQCFDF2O5G9RaEIFoN27TyclhAO992T9Ldcw46QQF+vaKSm2eT929hkTI7gQCvlYp=
NRh=0A=
cL0EYWoSihfVCr3FvDB81ukMJY2GQE/szKN+OMY3EU/t3WgxjkzSswF07r51XgdIGn9w/xZch=
MB5=0A=
hbgF/X++ZRGjD8ACtPhSNzkE1akxehi/oCr0Epn3o0WC4zxe9Z2etciefC7IpJ5OCBRLbf1wb=
Wsa=0A=
Y71k5h+3zvDyny67G7fyUIhzksLi4xaNmjICq44Y3ekQEe5+NauQrz4wlHrQMz2nZQ/1/I6eY=
s9H=0A=
RCwBXbsdtTLSR9I4LtD+gdwyah617jzV/OeBHRnDJELqYzmp=0A=
-----END CERTIFICATE-----=0A=
=0A=
AddTrust Low-Value Services Root=0A=
=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=
=3D=3D=3D=3D=3D=3D=3D=0A=
-----BEGIN CERTIFICATE-----=0A=
MIIEGDCCAwCgAwIBAgIBATANBgkqhkiG9w0BAQUFADBlMQswCQYDVQQGEwJTRTEUMBIGA1UEC=
hML=0A=
QWRkVHJ1c3QgQUIxHTAbBgNVBAsTFEFkZFRydXN0IFRUUCBOZXR3b3JrMSEwHwYDVQQDExhBZ=
GRU=0A=
cnVzdCBDbGFzcyAxIENBIFJvb3QwHhcNMDAwNTMwMTAzODMxWhcNMjAwNTMwMTAzODMxWjBlM=
Qsw=0A=
CQYDVQQGEwJTRTEUMBIGA1UEChMLQWRkVHJ1c3QgQUIxHTAbBgNVBAsTFEFkZFRydXN0IFRUU=
CBO=0A=
ZXR3b3JrMSEwHwYDVQQDExhBZGRUcnVzdCBDbGFzcyAxIENBIFJvb3QwggEiMA0GCSqGSIb3D=
QEB=0A=
AQUAA4IBDwAwggEKAoIBAQCWltQhSWDia+hBBwzexODcEyPNwTXH+9ZOEQpnXvUGW2ulCDtbK=
RY6=0A=
54eyNAbFvAWlA3yCyykQruGIgb3WntP+LVbBFc7jJp0VLhD7Bo8wBN6ntGO0/7Gcrjyvd7ZWx=
bWr=0A=
oulpOj0OM3kyP3CCkplhbY0wCI9xP6ZIVxn4JdxLZlyldI+Yrsj5wAYi56xz36Uu+1LcsRVlI=
Po1=0A=
Zmne3yzxbrww2ywkEtvrNTVokMsAsJchPXQhI2U0K7t4WaPW4XY5mqRJjox0r26kmqPZm9I4X=
Jui=0A=
GMx1I4S+6+JNM3GOGvDC+Mcdoq0Dlyz4zyXG9rgkMbFjXZJ/Y/AlyVMuH79NAgMBAAGjgdIwg=
c8w=0A=
HQYDVR0OBBYEFJWxtPCUtr3H2tERCSG+wa9J/RB7MAsGA1UdDwQEAwIBBjAPBgNVHRMBAf8EB=
TAD=0A=
AQH/MIGPBgNVHSMEgYcwgYSAFJWxtPCUtr3H2tERCSG+wa9J/RB7oWmkZzBlMQswCQYDVQQGE=
wJT=0A=
RTEUMBIGA1UEChMLQWRkVHJ1c3QgQUIxHTAbBgNVBAsTFEFkZFRydXN0IFRUUCBOZXR3b3JrM=
SEw=0A=
HwYDVQQDExhBZGRUcnVzdCBDbGFzcyAxIENBIFJvb3SCAQEwDQYJKoZIhvcNAQEFBQADggEBA=
Cxt=0A=
ZBsfzQ3duQH6lmM0MkhHma6X7f1yFqZzR1r0693p9db7RcwpiURdv0Y5PejuvE1Uhh4dbOMXJ=
0Ph=0A=
iVYrqW9yTkkz43J8KiOavD7/KCrto/8cI7pDVwlnTUtiBi34/2ydYB7YHEt9tTEv2dB8Xfjea=
4MY=0A=
eDdXL+gzB2ffHsdrKpV2ro9Xo/D0UrSpUwjP4E/TelOL/bscVjby/rK25Xa71SJlpz/+0WatC=
7xr=0A=
mYbvP33zGDLKe8bjq2RGlfgmadlVg3sslgf/WSxEo8bl6ancoWOAWiFeIc9TVPC6b4nbqKqVz=
4vj=0A=
ccweGyBECMB6tkD9xOQ14R0WHNC8K47Wcdk=3D=0A=
-----END CERTIFICATE-----=0A=
=0A=
AddTrust External Root=0A=
=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=0A=
-----BEGIN CERTIFICATE-----=0A=
MIIENjCCAx6gAwIBAgIBATANBgkqhkiG9w0BAQUFADBvMQswCQYDVQQGEwJTRTEUMBIGA1UEC=
hML=0A=
QWRkVHJ1c3QgQUIxJjAkBgNVBAsTHUFkZFRydXN0IEV4dGVybmFsIFRUUCBOZXR3b3JrMSIwI=
AYD=0A=
VQQDExlBZGRUcnVzdCBFeHRlcm5hbCBDQSBSb290MB4XDTAwMDUzMDEwNDgzOFoXDTIwMDUzM=
DEw=0A=
NDgzOFowbzELMAkGA1UEBhMCU0UxFDASBgNVBAoTC0FkZFRydXN0IEFCMSYwJAYDVQQLEx1BZ=
GRU=0A=
cnVzdCBFeHRlcm5hbCBUVFAgTmV0d29yazEiMCAGA1UEAxMZQWRkVHJ1c3QgRXh0ZXJuYWwgQ=
0Eg=0A=
Um9vdDCCASIwDQYJKoZIhvcNAQEBBQADggEPADCCAQoCggEBALf3GjPm8gAELTngTlvtH7xsD=
821=0A=
+iO2zt6bETOXpClMfZOfvUq8k+0DGuOPz+VtUFrWlymUWoCwSXrbLpX9uMq/NzgtHj6RQa1wV=
sfw=0A=
Tz/oMp50ysiQVOnGXw94nZpAPA6sYapeFI+eh6FqUNzXmk6vBbOmcZSccbNQYArHE504B4YCq=
Omo=0A=
aSYYkKtMsE8jqzpPhNjfzp/haW+710LXa0Tkx63ubUFfclpxCDezeWWkWaCUN/cALw3CknLa0=
Dhy=0A=
2xSoRcRdKn23tNbE7qzNE0S3ySvdQwAl+mG5aWpYIxG3pzOPVnVZ9c0p10a3CitlttNCbxWyu=
Hv7=0A=
7+ldU9U0WicCAwEAAaOB3DCB2TAdBgNVHQ4EFgQUrb2YejS0Jvf6xCZU7wO94CTLVBowCwYDV=
R0P=0A=
BAQDAgEGMA8GA1UdEwEB/wQFMAMBAf8wgZkGA1UdIwSBkTCBjoAUrb2YejS0Jvf6xCZU7wO94=
CTL=0A=
VBqhc6RxMG8xCzAJBgNVBAYTAlNFMRQwEgYDVQQKEwtBZGRUcnVzdCBBQjEmMCQGA1UECxMdQ=
WRk=0A=
VHJ1c3QgRXh0ZXJuYWwgVFRQIE5ldHdvcmsxIjAgBgNVBAMTGUFkZFRydXN0IEV4dGVybmFsI=
ENB=0A=
IFJvb3SCAQEwDQYJKoZIhvcNAQEFBQADggEBALCb4IUlwtYj4g+WBpKdQZic2YR5gdkeWxQHI=
zZl=0A=
j7DYd7usQWxHYINRsPkyPef89iYTx4AWpb9a/IfPeHmJIZriTAcKhjW88t5RxNKWt9x+Tu5w/=
Rw5=0A=
6wwCURQtjr0W4MHfRnXnJK3s9EK0hZNwEGe6nQY1ShjTK3rMUUKhemPR5ruhxSvCNr4TDea9Y=
355=0A=
e6cJDUCrat2PisP29owaQgVR1EX1n6diIWgVIEM8med8vSTYqZEXc4g/VhsxOBi0cQ+azcgOn=
o4u=0A=
G+GMmIPLHzHxREzGBHNJdmAPx/i9F4BrLunMTA5amnkPIAou1Z5jJh5VkpTYghdae9C8x49Oh=
gQ=3D=0A=
-----END CERTIFICATE-----=0A=
=0A=
AddTrust Public Services Root=0A=
=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=
=3D=3D=3D=3D=0A=
-----BEGIN CERTIFICATE-----=0A=
MIIEFTCCAv2gAwIBAgIBATANBgkqhkiG9w0BAQUFADBkMQswCQYDVQQGEwJTRTEUMBIGA1UEC=
hML=0A=
QWRkVHJ1c3QgQUIxHTAbBgNVBAsTFEFkZFRydXN0IFRUUCBOZXR3b3JrMSAwHgYDVQQDExdBZ=
GRU=0A=
cnVzdCBQdWJsaWMgQ0EgUm9vdDAeFw0wMDA1MzAxMDQxNTBaFw0yMDA1MzAxMDQxNTBaMGQxC=
zAJ=0A=
BgNVBAYTAlNFMRQwEgYDVQQKEwtBZGRUcnVzdCBBQjEdMBsGA1UECxMUQWRkVHJ1c3QgVFRQI=
E5l=0A=
dHdvcmsxIDAeBgNVBAMTF0FkZFRydXN0IFB1YmxpYyBDQSBSb290MIIBIjANBgkqhkiG9w0BA=
QEF=0A=
AAOCAQ8AMIIBCgKCAQEA6Rowj4OIFMEg2Dybjxt+A3S72mnTRqX4jsIMEZBRpS9mVEBV6tsfS=
lbu=0A=
nyNu9DnLoblv8n75XYcmYZ4c+OLspoH4IcUkzBEMP9smcnrHAZcHF/nXGCwwfQ56HmIexkvA/=
X1i=0A=
d9NEHif2P0tEs7c42TkfYNVRknMDtABp4/MUTu7R3AnPdzRGULD4EfL+OHn3Bzn+UZKXC1sIX=
zSG=0A=
Aa2Il+tmzV7R/9x98oTaunet3IAIx6eH1lWfl2royBFkuucZKT8Rs3iQhCBSWxHveNCD9tVIk=
NAw=0A=
HM+A+WD+eeSI8t0A65RF62WUaUC6wNW0uLp9BBGo6zEFlpROWCGOn9Bg/QIDAQABo4HRMIHOM=
B0G=0A=
A1UdDgQWBBSBPjfYkrAfd59ctKtzquf2NGAv+jALBgNVHQ8EBAMCAQYwDwYDVR0TAQH/BAUwA=
wEB=0A=
/zCBjgYDVR0jBIGGMIGDgBSBPjfYkrAfd59ctKtzquf2NGAv+qFopGYwZDELMAkGA1UEBhMCU=
0Ux=0A=
FDASBgNVBAoTC0FkZFRydXN0IEFCMR0wGwYDVQQLExRBZGRUcnVzdCBUVFAgTmV0d29yazEgM=
B4G=0A=
A1UEAxMXQWRkVHJ1c3QgUHVibGljIENBIFJvb3SCAQEwDQYJKoZIhvcNAQEFBQADggEBAAP3F=
Ur4=0A=
JNojVhaTdt02KLmuG7jD8WS6IBh4lSknVwW8fCr0uVFV2ocC3g8WFzH4qnkuCRO7r7IgGRLlk=
/lL=0A=
+YPoRNWyQSW/iHVv/xD8SlTQX/D67zZzfRs2RcYhbbQVuE7PnFylPVoAjgbjPGsye/Kf8Lb93=
/Ao=0A=
GEjwxrzQvzSAlsJKsW2Ox5BF3i9nrEUEo3rcVZLJR2bYGozH7ZxOmuASu7VqTITh4SINhwBk/=
ox9=0A=
Yjllpu9CtoAlEmEBqCQTcAARJl/6NVDFSMwGR+gn2HCNX2TmoUQmXiLsks3/QppEIW1cxeMiH=
V9H=0A=
EufOX1362KqxMy3ZdvJOOjMMK7MtkAY=3D=0A=
-----END CERTIFICATE-----=0A=
=0A=
AddTrust Qualified Certificates Root=0A=
=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=
=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=0A=
-----BEGIN CERTIFICATE-----=0A=
MIIEHjCCAwagAwIBAgIBATANBgkqhkiG9w0BAQUFADBnMQswCQYDVQQGEwJTRTEUMBIGA1UEC=
hML=0A=
QWRkVHJ1c3QgQUIxHTAbBgNVBAsTFEFkZFRydXN0IFRUUCBOZXR3b3JrMSMwIQYDVQQDExpBZ=
GRU=0A=
cnVzdCBRdWFsaWZpZWQgQ0EgUm9vdDAeFw0wMDA1MzAxMDQ0NTBaFw0yMDA1MzAxMDQ0NTBaM=
Gcx=0A=
CzAJBgNVBAYTAlNFMRQwEgYDVQQKEwtBZGRUcnVzdCBBQjEdMBsGA1UECxMUQWRkVHJ1c3QgV=
FRQ=0A=
IE5ldHdvcmsxIzAhBgNVBAMTGkFkZFRydXN0IFF1YWxpZmllZCBDQSBSb290MIIBIjANBgkqh=
kiG=0A=
9w0BAQEFAAOCAQ8AMIIBCgKCAQEA5B6a/twJWoekn0e+EV+vhDTbYjx5eLfpMLXsDBwqxBb/4=
Oxx=0A=
64r1EW7tTw2R0hIYLUkVAcKkIhPHEWT/IhKauY5cLwjPcWqzZwFZ8V1G87B4pfYOQnrjfxvM0=
PC3=0A=
KP0q6p6zsLkEqv32x7SxuCqg+1jxGaBvcCV+PmlKfw8i2O+tCBGaKZnhqkRFmhJePp1tUvzno=
D1o=0A=
L/BLcHwTOK28FSXx1s6rosAx1i+f4P8UWfyEk9mHfExUE+uf0S0R+Bg6Ot4l2ffTQO2kBhLEO=
+GR=0A=
wVY18BTcZTYJbqukB8c10cIDMzZbdSZtQvESa0NvS3GU+jQd7RNuyoB/mC9suWXY6QIDAQABo=
4HU=0A=
MIHRMB0GA1UdDgQWBBQ5lYtii1zJ1IC6WA+XPxUIQ8yYpzALBgNVHQ8EBAMCAQYwDwYDVR0TA=
QH/=0A=
BAUwAwEB/zCBkQYDVR0jBIGJMIGGgBQ5lYtii1zJ1IC6WA+XPxUIQ8yYp6FrpGkwZzELMAkGA=
1UE=0A=
BhMCU0UxFDASBgNVBAoTC0FkZFRydXN0IEFCMR0wGwYDVQQLExRBZGRUcnVzdCBUVFAgTmV0d=
29y=0A=
azEjMCEGA1UEAxMaQWRkVHJ1c3QgUXVhbGlmaWVkIENBIFJvb3SCAQEwDQYJKoZIhvcNAQEFB=
QAD=0A=
ggEBABmrder4i2VhlRO6aQTvhsoToMeqT2QbPxj2qC0sVY8FtzDqQmodwCVRLae/DLPt7wh/b=
DxG=0A=
GuoYQ992zPlmhpwsaPXpF/gxsxjE1kh9I0xowX67ARRvxdlu3rsEQmr49lx95dr6h+sNNVJn0=
J6X=0A=
dgWTP5XHAeZpVTh/EGGZyeNfpso+gmNIquIISD6q8rKFYqa0p9m9N5xotS1WfbC3P6CxB9bpT=
9ze=0A=
RXEwMn8bLgn5v1Kh7sKAPgZcLlVAwRv1cEWw3F369nJad9Jjzc9YiQBCYz95OdBEsIJuQRno3=
eDB=0A=
iFrRHnGTHyQwdOUeqN48Jzd/g66ed8/wMLH/S5noxqE=3D=0A=
-----END CERTIFICATE-----=0A=
=0A=
Entrust Root Certification Authority=0A=
=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=
=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=0A=
-----BEGIN CERTIFICATE-----=0A=
MIIEkTCCA3mgAwIBAgIERWtQVDANBgkqhkiG9w0BAQUFADCBsDELMAkGA1UEBhMCVVMxFjAUB=
gNV=0A=
BAoTDUVudHJ1c3QsIEluYy4xOTA3BgNVBAsTMHd3dy5lbnRydXN0Lm5ldC9DUFMgaXMgaW5jb=
3Jw=0A=
b3JhdGVkIGJ5IHJlZmVyZW5jZTEfMB0GA1UECxMWKGMpIDIwMDYgRW50cnVzdCwgSW5jLjEtM=
CsG=0A=
A1UEAxMkRW50cnVzdCBSb290IENlcnRpZmljYXRpb24gQXV0aG9yaXR5MB4XDTA2MTEyNzIwM=
jM0=0A=
MloXDTI2MTEyNzIwNTM0MlowgbAxCzAJBgNVBAYTAlVTMRYwFAYDVQQKEw1FbnRydXN0LCBJb=
mMu=0A=
MTkwNwYDVQQLEzB3d3cuZW50cnVzdC5uZXQvQ1BTIGlzIGluY29ycG9yYXRlZCBieSByZWZlc=
mVu=0A=
Y2UxHzAdBgNVBAsTFihjKSAyMDA2IEVudHJ1c3QsIEluYy4xLTArBgNVBAMTJEVudHJ1c3QgU=
m9v=0A=
dCBDZXJ0aWZpY2F0aW9uIEF1dGhvcml0eTCCASIwDQYJKoZIhvcNAQEBBQADggEPADCCAQoCg=
gEB=0A=
ALaVtkNC+sZtKm9I35RMOVcF7sN5EUFoNu3s/poBj6E4KPz3EEZmLk0eGrEaTsbRwJWIsMn/M=
Ysz=0A=
A9u3g3s+IIRe7bJWKKf44LlAcTfFy0cOlypowCKVYhXbR9n10Cv/gkvJrT7eTNuQgFA/CYqEA=
Oww=0A=
Cj0Yzfv9KlmaI5UXLEWeH25DeW0MXJj+SKfFI0dcXv1u5x609mhF0YaDW6KKjbHjKYD+JXGIr=
b68=0A=
j6xSlkuqUY3kEzEZ6E5Nn9uss2rVvDlUccp6en+Q3X0dgNmBu1kmwhH+5pPi94DkZfs0Nw4pg=
HBN=0A=
rziGLp5/V6+eF67rHMsoIV+2HNjnogQi+dPa2MsCAwEAAaOBsDCBrTAOBgNVHQ8BAf8EBAMCA=
QYw=0A=
DwYDVR0TAQH/BAUwAwEB/zArBgNVHRAEJDAigA8yMDA2MTEyNzIwMjM0MlqBDzIwMjYxMTI3M=
jA1=0A=
MzQyWjAfBgNVHSMEGDAWgBRokORnpKZTgMeGZqTx90tD+4S9bTAdBgNVHQ4EFgQUaJDkZ6SmU=
4DH=0A=
hmak8fdLQ/uEvW0wHQYJKoZIhvZ9B0EABBAwDhsIVjcuMTo0LjADAgSQMA0GCSqGSIb3DQEBB=
QUA=0A=
A4IBAQCT1DCw1wMgKtD5Y+iRDAUgqV8ZyntyTtSx29CW+1RaGSwMCPeyvIWonX9tO1KzKtvn1=
ISM=0A=
Y/YPyyYBkVBs9F8U4pN0wBOeMDpQ47RgxRzwIkSNcUesyBrJ6ZuaAGAT/3B+XxFNSRuzFVJ7y=
VTa=0A=
v52Vr2ua2J7p8eRDjeIRRDq/r72DQnNSi6q7pynP9WQcCk3RvKqsnyrQ/39/2n3qse0wJcGE2=
jTS=0A=
W3iDVuycNsMm4hH2Z0kdkquM++v/eu6FSqdQgPCnXEqULl8FmTxSQeDNtGPPAUO6nIPcj2A78=
1q0=0A=
tHuu2guQOHXvgR1m0vdXcDazv/wor3ElhVsT/h5/WrQ8=0A=
-----END CERTIFICATE-----=0A=
=0A=
RSA Security 2048 v3=0A=
=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=0A=
-----BEGIN CERTIFICATE-----=0A=
MIIDYTCCAkmgAwIBAgIQCgEBAQAAAnwAAAAKAAAAAjANBgkqhkiG9w0BAQUFADA6MRkwFwYDV=
QQK=0A=
ExBSU0EgU2VjdXJpdHkgSW5jMR0wGwYDVQQLExRSU0EgU2VjdXJpdHkgMjA0OCBWMzAeFw0wM=
TAy=0A=
MjIyMDM5MjNaFw0yNjAyMjIyMDM5MjNaMDoxGTAXBgNVBAoTEFJTQSBTZWN1cml0eSBJbmMxH=
TAb=0A=
BgNVBAsTFFJTQSBTZWN1cml0eSAyMDQ4IFYzMIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBC=
gKC=0A=
AQEAt49VcdKA3XtpeafwGFAyPGJn9gqVB93mG/Oe2dJBVGutn3y+Gc37RqtBaB4Y6lXIL5F4i=
Sj7=0A=
Jylg/9+PjDvJSZu1pJTOAeo+tWN7fyb9Gd3AIb2E0S1PRsNO3Ng3OTsor8udGuorryGlwSMiu=
Lgb=0A=
WhOHV4PR8CDn6E8jQrAApX2J6elhc5SYcSa8LWrg903w8bYqODGBDSnhAMFRD0xS+ARaqn1y0=
7iH=0A=
KrtjEAMqs6FPDVpeRrc9DvV07Jmf+T0kgYim3WBU6JU2PcYJk5qjEoAAVZkZR73QpXzDuvsf9=
/UP=0A=
+Ky5tfQ3mBMY3oVbtwyCO4dvlTlYMNpuAWgXIszACwIDAQABo2MwYTAPBgNVHRMBAf8EBTADA=
QH/=0A=
MA4GA1UdDwEB/wQEAwIBBjAfBgNVHSMEGDAWgBQHw1EwpKrpRa41JPr/JCwz0LGdjDAdBgNVH=
Q4E=0A=
FgQUB8NRMKSq6UWuNST6/yQsM9CxnYwwDQYJKoZIhvcNAQEFBQADggEBAF8+hnZuuDU8TjYcH=
nmY=0A=
v/3VEhF5Ug7uMYm83X/50cYVIeiKAVQNOvtUudZj1LGqlk2iQk3UUx+LEN5/Zb5gEydxiKRz4=
4Rj=0A=
0aRV4VCT5hsOedBnvEbIvz8XDZXmxpBp3ue0L96VfdASPz0+f00/FGj1EVDVwfSQpQgdMWD/Y=
Iwj=0A=
VAqv/qFuxdF6Kmh4zx6CCiC0H63lhbJqaHVOrSU3lIW+vaHU6rcMSzyd6BIA8F+sDeGscGNz9=
395=0A=
nzIlQnQFgCi/vcEkllgVsRch6YlL2weIZ/QVrXA+L02FO8K32/6YaCOJ4XQP3vTFhGMpG8zLB=
8kA=0A=
pKnXwiJPZ9d37CAFYd4=3D=0A=
-----END CERTIFICATE-----=0A=
=0A=
GeoTrust Global CA=0A=
=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=0A=
-----BEGIN CERTIFICATE-----=0A=
MIIDVDCCAjygAwIBAgIDAjRWMA0GCSqGSIb3DQEBBQUAMEIxCzAJBgNVBAYTAlVTMRYwFAYDV=
QQK=0A=
Ew1HZW9UcnVzdCBJbmMuMRswGQYDVQQDExJHZW9UcnVzdCBHbG9iYWwgQ0EwHhcNMDIwNTIxM=
DQw=0A=
MDAwWhcNMjIwNTIxMDQwMDAwWjBCMQswCQYDVQQGEwJVUzEWMBQGA1UEChMNR2VvVHJ1c3QgS=
W5j=0A=
LjEbMBkGA1UEAxMSR2VvVHJ1c3QgR2xvYmFsIENBMIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AM=
IIB=0A=
CgKCAQEA2swYYzD99BcjGlZ+W988bDjkcbd4kdS8odhM+KhDtgPpTSEHCIjaWC9mOSm9BXiLn=
Tjo=0A=
BbdqfnGk5sRgprDvgOSJKA+eJdbtg/OtppHHmMlCGDUUna2YRpIuT8rxh0PBFpVXLVDviS2Ae=
let=0A=
8u5fa9IAjbkU+BQVNdnARqN7csiRv8lVK83Qlz6cJmTM386DGXHKTubU1XupGc1V3sjs0l44U=
+Vc=0A=
T4wt/lAjNvxm5suOpDkZALeVAjmRCw7+OC7RHQWa9k0+bw8HHa8sHo9gOeL6NlMTOdReJivbP=
agU=0A=
vTLrGAMoUgRx5aszPeE4uwc2hGKceeoWMPRfwCvocWvk+QIDAQABo1MwUTAPBgNVHRMBAf8EB=
TAD=0A=
AQH/MB0GA1UdDgQWBBTAephojYn7qwVkDBF9qn1luMrMTjAfBgNVHSMEGDAWgBTAephojYn7q=
wVk=0A=
DBF9qn1luMrMTjANBgkqhkiG9w0BAQUFAAOCAQEANeMpauUvXVSOKVCUn5kaFOSPeCpilKInZ=
57Q=0A=
zxpeR+nBsqTP3UEaBU6bS+5Kb1VSsyShNwrrZHYqLizz/Tt1kL/6cdjHPTfStQWVYrmm3ok9N=
ns4=0A=
d0iXrKYgjy6myQzCsplFAMfOEVEiIuCl6rYVSAlk6l5PdPcFPseKUgzbFbS9bZvlxrFUaKnja=
ZC2=0A=
mqUPuLk/IH2uSrW4nOQdtqvmlKXBx4Ot2/Unhw4EbNX/3aBd7YdStysVAq45pmp06drE57xNN=
B6p=0A=
XE0zX5IJL4hmXXeXxx12E6nV5fEWCRE11azbJHFwLJhWC9kXtNHjUStedejV0NxPNO3CBWaAo=
cvm=0A=
Mw=3D=3D=0A=
-----END CERTIFICATE-----=0A=
=0A=
GeoTrust Global CA 2=0A=
=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=0A=
-----BEGIN CERTIFICATE-----=0A=
MIIDZjCCAk6gAwIBAgIBATANBgkqhkiG9w0BAQUFADBEMQswCQYDVQQGEwJVUzEWMBQGA1UEC=
hMN=0A=
R2VvVHJ1c3QgSW5jLjEdMBsGA1UEAxMUR2VvVHJ1c3QgR2xvYmFsIENBIDIwHhcNMDQwMzA0M=
DUw=0A=
MDAwWhcNMTkwMzA0MDUwMDAwWjBEMQswCQYDVQQGEwJVUzEWMBQGA1UEChMNR2VvVHJ1c3QgS=
W5j=0A=
LjEdMBsGA1UEAxMUR2VvVHJ1c3QgR2xvYmFsIENBIDIwggEiMA0GCSqGSIb3DQEBAQUAA4IBD=
wAw=0A=
ggEKAoIBAQDvPE1APRDfO1MA4Wf+lGAVPoWI8YkNkMgoI5kF6CsgncbzYEbYwbLVjDHZ3CB5J=
IG/=0A=
NTL8Y2nbsSpr7iFY8gjpeMtvy/wWUsiRxP89c96xPqfCfWbB9X5SJBri1WeR0IIQ13hLTytCO=
b1k=0A=
LUCgsBDTOEhGiKEMuzozKmKY+wCdE1l/bztyqu6mD4b5BWHqZ38MN5aL5mkWRxHCJ1kDs6Zgw=
iFA=0A=
Vvqgx306E+PsV8ez1q6diYD3Aecs9pYrEw15LNnA5IZ7S4wMcoKK+xfNAGw6EzywhIdLFnops=
k/b=0A=
HdQL82Y3vdj2V7teJHq4PIu5+pIaGoSe2HSPqht/XvT+RSIhAgMBAAGjYzBhMA8GA1UdEwEB/=
wQF=0A=
MAMBAf8wHQYDVR0OBBYEFHE4NvICMVNHK266ZUapEBVYIAUJMB8GA1UdIwQYMBaAFHE4NvICM=
VNH=0A=
K266ZUapEBVYIAUJMA4GA1UdDwEB/wQEAwIBhjANBgkqhkiG9w0BAQUFAAOCAQEAA/e1K6tdE=
Px7=0A=
srJerJsOflN4WT5CBP51o62sgU7XAotexC3IUnbHLB/8gTKY0UvGkpMzNTEv/NgdRN3ggX+d6=
Yvh=0A=
ZJFiCzkIjKx0nVnZellSlxG5FntvRdOW2TF9AjYPnDtuzywNA0ZF66D0f0hExghAzN4bcLUpr=
bqL=0A=
OzRldRtxIR0sFAqwlpW41uryZfspuk/qkZN0abby/+Ea0AzRdoXLiiW9l14sbxWZJue2Kf8i7=
MkC=0A=
x1YAzUm5s2x7UwQa4qjJqhIFI8LO57sEAszAR6LkxCkvW0VXiVHuPOtSCP8HNR6fNWpHSlaY0=
VqF=0A=
H4z1Ir+rzoPz4iIprn2DQKi6bA=3D=3D=0A=
-----END CERTIFICATE-----=0A=
=0A=
GeoTrust Universal CA=0A=
=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=0A=
-----BEGIN CERTIFICATE-----=0A=
MIIFaDCCA1CgAwIBAgIBATANBgkqhkiG9w0BAQUFADBFMQswCQYDVQQGEwJVUzEWMBQGA1UEC=
hMN=0A=
R2VvVHJ1c3QgSW5jLjEeMBwGA1UEAxMVR2VvVHJ1c3QgVW5pdmVyc2FsIENBMB4XDTA0MDMwN=
DA1=0A=
MDAwMFoXDTI5MDMwNDA1MDAwMFowRTELMAkGA1UEBhMCVVMxFjAUBgNVBAoTDUdlb1RydXN0I=
Elu=0A=
Yy4xHjAcBgNVBAMTFUdlb1RydXN0IFVuaXZlcnNhbCBDQTCCAiIwDQYJKoZIhvcNAQEBBQADg=
gIP=0A=
ADCCAgoCggIBAKYVVaCjxuAfjJ0hUNfBvitbtaSeodlyWL0AG0y/YckUHUWCq8YdgNY96xCcO=
q9t=0A=
JPi8cQGeBvV8Xx7BDlXKg5pZMK4ZyzBIle0iN430SppyZj6tlcDgFgDgEB8rMQ7XlFTTQjOgN=
B0e=0A=
RXbdT8oYN+yFFXoZCPzVx5zw8qkuEKmS5j1YPakWaDwvdSEYfyh3peFhF7em6fgemdtzbvQKo=
iFs=0A=
7tqqhZJmr/Z6a4LauiIINQ/PQvE1+mrufislzDoR5G2vc7J2Ha3QsnhnGqQ5HFELZ1aD/ThdD=
c7d=0A=
8Lsrlh/eezJS/R27tQahsiFepdaVaH/wmZ7cRQg+59IJDTWU3YBOU5fXtQlEIGQWFwMCTFMNa=
N7V=0A=
qnJNk22CDtucvc+081xdVHppCZbW2xHBjXWotM85yM48vCR85mLK4b19p71XZQvk/iXttmkQ3=
Cga=0A=
Rr0BHdCXteGYO8A3ZNY9lO4L4fUorgtWv3GLIylBjobFS1J72HGrH4oVpjuDWtdYAVHGTEHZf=
9hB=0A=
Z3KiKN9gg6meyHv8U3NyWfWTehd2Ds735VzZC1U0oqpbtWpU5xPKV+yXbfReBi9Fi1jUIxaS5=
BZu=0A=
KGNZMN9QAZxjiRqf2xeUgnA3wySemkfWWspOqGmJch+RbNt+nhutxx9z3SxPGWX9f5NAEC7S8=
O08=0A=
ni4oPmkmM8V7AgMBAAGjYzBhMA8GA1UdEwEB/wQFMAMBAf8wHQYDVR0OBBYEFNq7LqqwDLiIJ=
lF0=0A=
XG0D08DYj3rWMB8GA1UdIwQYMBaAFNq7LqqwDLiIJlF0XG0D08DYj3rWMA4GA1UdDwEB/wQEA=
wIB=0A=
hjANBgkqhkiG9w0BAQUFAAOCAgEAMXjmx7XfuJRAyXHEqDXsRh3ChfMoWIawC/yOsjmPRFWrZ=
IRc=0A=
aanQmjg8+uUfNeVE44B5lGiku8SfPeE0zTBGi1QrlaXv9z+ZhP015s8xxtxqv6fXIwjhmF7DW=
gh2=0A=
qaavdy+3YL1ERmrvl/9zlcGO6JP7/TG37FcREUWbMPEaiDnBTzynANXH/KttgCJwpQzgXQQpA=
vvL=0A=
oJHRfNbDflDVnVi+QTjruXU8FdmbyUqDWcDaU/0zuzYYm4UPFd3uLax2k7nZAY1IEKj79TiG8=
dsK=0A=
xr2EoyNB3tZ3b4XUhRxQ4K5RirqNPnbiucon8l+f725ZDQbYKxek0nxru18UGkiPGkzns0ccj=
kxF=0A=
KyDuSN/n3QmOGKjaQI2SJhFTYXNd673nxE0pN2HrrDktZy4W1vUAg4WhzH92xH3kt0tm7wNFY=
Gm2=0A=
DFKWkoRepqO1pD4r2czYG0eq8kTaT/kD6PAUyz/zg97QwVTjt+gKN02LIFkDMBmhLMi9ER/fr=
slK=0A=
xfMnZmaGrGiR/9nmUxwPi1xpZQomyB40w11Re9epnAahNt3ViZS82eQtDF4JbAiXfKM9fJP/P=
6EU=0A=
p8+1Xevb2xzEdt+Iub1FBZUbrvxGakyvSOPOrg/SfuvmbJxPgWp6ZKy7PtXny3YuxadIwVyQD=
8vI=0A=
P/rmMuGNG2+k5o7Y+SlIis5z/iw=3D=0A=
-----END CERTIFICATE-----=0A=
=0A=
GeoTrust Universal CA 2=0A=
=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=0A=
-----BEGIN CERTIFICATE-----=0A=
MIIFbDCCA1SgAwIBAgIBATANBgkqhkiG9w0BAQUFADBHMQswCQYDVQQGEwJVUzEWMBQGA1UEC=
hMN=0A=
R2VvVHJ1c3QgSW5jLjEgMB4GA1UEAxMXR2VvVHJ1c3QgVW5pdmVyc2FsIENBIDIwHhcNMDQwM=
zA0=0A=
MDUwMDAwWhcNMjkwMzA0MDUwMDAwWjBHMQswCQYDVQQGEwJVUzEWMBQGA1UEChMNR2VvVHJ1c=
3Qg=0A=
SW5jLjEgMB4GA1UEAxMXR2VvVHJ1c3QgVW5pdmVyc2FsIENBIDIwggIiMA0GCSqGSIb3DQEBA=
QUA=0A=
A4ICDwAwggIKAoICAQCzVFLByT7y2dyxUxpZKeexw0Uo5dfR7cXFS6GqdHtXr0om/Nj1XqduG=
dt0=0A=
DE81WzILAePb63p3NeqqWuDW6KFXlPCQo3RWlEQwAx5cTiuFJnSCegx2oG9NzkEtoBUGFF+3Q=
s17=0A=
j1hhNNwqCPkuwwGmIkQcTAeC5lvO0Ep8BNMZcyfwqph/Lq9O64ceJHdqXbboW0W63MOhBW9Wj=
o8Q=0A=
JqVJwy7XQYci4E+GymC16qFjwAGXEHm9ADwSbSsVsaxLse4YuU6W3Nx2/zu+z18DwPw76L5GG=
//a=0A=
QMJS9/7jOvdqdzXQ2o3rXhhqMcceujwbKNZrVMaqW9eiLBsZzKIC9ptZvTdrhrVtgrrY6slWv=
Kk2=0A=
WP0+GfPtDCapkzj4T8FdIgbQl+rhrcZV4IErKIM6+vR7IVEAvlI4zs1meaj0gVbi0IMJR1FbU=
GrP=0A=
20gaXT73y/Zl92zxlfgCOzJWgjl6W70viRu/obTo/3+NjN8D8WBOWBFM66M/ECuDmgFz2ZRth=
AAn=0A=
ZqzwcEAJQpKtT5MNYQlRJNiS1QuUYbKHsu3/mjX/hVTK7URDrBs8FmtISgocQIgfksILAAX/8=
sgC=0A=
SqSqqcyZlpwvWOB94b67B9xfBHJcMTTD7F8t4D1kkCLm0ey4Lt1ZrtmhN79UNdxzMk+MBB4zs=
slG=0A=
8dhcyFVQyWi9qLo2CQIDAQABo2MwYTAPBgNVHRMBAf8EBTADAQH/MB0GA1UdDgQWBBR281Xh+=
qQ2=0A=
+/CfXGJx7Tz0RzgQKzAfBgNVHSMEGDAWgBR281Xh+qQ2+/CfXGJx7Tz0RzgQKzAOBgNVHQ8BA=
f8E=0A=
BAMCAYYwDQYJKoZIhvcNAQEFBQADggIBAGbBxiPz2eAubl/oz66wsCVNK/g7WJtAJDday6sWS=
f+z=0A=
dXkzoS9tcBc0kf5nfo/sm+VegqlVHy/c1FEHEv6sFj4sNcZj/NwQ6w2jqtB8zNHQL1EuxBRa3=
ugZ=0A=
4T7GzKQp5y6EqgYweHZUcyiYWTjgAA1i00J9IZ+uPTqM1fp3DRgrFg5fNuH8KrUwJM/gYwx7W=
Br+=0A=
mbpCErGR9Hxo4sjoryzqyX6uuyo9DRXcNJW2GHSoag/HtPQTxORb7QrSpJdMKu0vbBKJPfEnc=
Kpq=0A=
A1Ihn0CoZ1Dy81of398j9tx4TuaYT1U6U+Pv8vSfx3zYWK8pIpe44L2RLrB27FcRz+8pRPPph=
Xpg=0A=
Y+RdM4kX2TGq2tbzGDVyz4crL2MjhF2EjD9XoIj8mZEoJmmZ1I+XRL6O1UixpCgp8RW04eWe3=
fiP=0A=
pm8m1wk8OhwRDqZsN/etRIcsKMfYdIKz0G9KV7s1KSegi+ghp4dkNl3M2Basx7InQJJVOCiNU=
W7d=0A=
FGdTbHFcJoRNdVq2fmBWqU2t+5sel/MN2dKXVHfaPRK34B7vCAas+YWH6aLcr34YEoP9VhdBL=
tUp=0A=
gn2Z9DH2canPLAEnpQW5qrJITirvn5NSUZU8UnOOVkwXQMAJKOSLakhT2+zNVVXxxvjpoixMp=
tEm=0A=
X36vWkzaH6byHCx+rgIW0lbQL1dTR+iS=0A=
-----END CERTIFICATE-----=0A=
=0A=
Visa eCommerce Root=0A=
=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=0A=
-----BEGIN CERTIFICATE-----=0A=
MIIDojCCAoqgAwIBAgIQE4Y1TR0/BvLB+WUF1ZAcYjANBgkqhkiG9w0BAQUFADBrMQswCQYDV=
QQG=0A=
EwJVUzENMAsGA1UEChMEVklTQTEvMC0GA1UECxMmVmlzYSBJbnRlcm5hdGlvbmFsIFNlcnZpY=
2Ug=0A=
QXNzb2NpYXRpb24xHDAaBgNVBAMTE1Zpc2EgZUNvbW1lcmNlIFJvb3QwHhcNMDIwNjI2MDIxO=
DM2=0A=
WhcNMjIwNjI0MDAxNjEyWjBrMQswCQYDVQQGEwJVUzENMAsGA1UEChMEVklTQTEvMC0GA1UEC=
xMm=0A=
VmlzYSBJbnRlcm5hdGlvbmFsIFNlcnZpY2UgQXNzb2NpYXRpb24xHDAaBgNVBAMTE1Zpc2EgZ=
UNv=0A=
bW1lcmNlIFJvb3QwggEiMA0GCSqGSIb3DQEBAQUAA4IBDwAwggEKAoIBAQCvV95WHm6h2mCxl=
CfL=0A=
F9sHP4CFT8icttD0b0/Pmdjh28JIXDqsOTPHH2qLJj0rNfVIsZHBAk4ElpF7sDPwsRROEW+1Q=
K8b=0A=
RaVK7362rPKgH1g/EkZgPI2h4H3PVz4zHvtH8aoVlwdVZqW1LS7YgFmypw23RuwhY/81q6UCz=
yr0=0A=
TP579ZRdhE2o8mCP2w4lPJ9zcc+U30rq299yOIzzlr3xF7zSujtFWsan9sYXiwGd/BmoKoMWu=
DpI=0A=
/k4+oKsGGelT84ATB+0tvz8KPFUgOSwsAGl0lUq8ILKpeeUYiZGo3BxN77t+Nwtd/jmliFKMA=
Gzs=0A=
GHxBvfaLdXe6YJ2E5/4tAgMBAAGjQjBAMA8GA1UdEwEB/wQFMAMBAf8wDgYDVR0PAQH/BAQDA=
gEG=0A=
MB0GA1UdDgQWBBQVOIMPPyw/cDMezUb+B4wg4NfDtzANBgkqhkiG9w0BAQUFAAOCAQEAX/FBf=
Xxc=0A=
CLkr4NWSR/pnXKUTwwMhmytMiUbPWU3J/qVAtmPN3XEolWcRzCSs00Rsca4BIGsDoo8Ytyk6f=
eUW=0A=
YFN4PMCvFYP3j1IzJL1kk5fui/fbGKhtcbP3LBfQdCVp9/5rPJS+TUtBjE7ic9DjkCJzQ83z7=
+pz=0A=
zkWKsKZJ/0x9nXGIxHYdkFsd7v3M9+79YKWxehZx0RbQfBI8bGmX265fOZpwLwU8GUYEmSA20=
GBu=0A=
YQa7FkKMcPcw++DbZqMAAb3mLNqRX6BGi01qnD093QVG/na/oAo85ADmJ7f/hC3euiInlhBx6=
yLt=0A=
398znM/jra6O1I7mT1GvFpLgXPYHDw=3D=3D=0A=
-----END CERTIFICATE-----=0A=
=0A=
Certum Root CA=0A=
=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=0A=
-----BEGIN CERTIFICATE-----=0A=
MIIDDDCCAfSgAwIBAgIDAQAgMA0GCSqGSIb3DQEBBQUAMD4xCzAJBgNVBAYTAlBMMRswGQYDV=
QQK=0A=
ExJVbml6ZXRvIFNwLiB6IG8uby4xEjAQBgNVBAMTCUNlcnR1bSBDQTAeFw0wMjA2MTExMDQ2M=
zla=0A=
Fw0yNzA2MTExMDQ2MzlaMD4xCzAJBgNVBAYTAlBMMRswGQYDVQQKExJVbml6ZXRvIFNwLiB6I=
G8u=0A=
by4xEjAQBgNVBAMTCUNlcnR1bSBDQTCCASIwDQYJKoZIhvcNAQEBBQADggEPADCCAQoCggEBA=
M6x=0A=
wS7TT3zNJc4YPk/EjG+AanPIW1H4m9LcuwBcsaD8dQPugfCI7iNS6eYVM42sLQnFdvkrOYCJ5=
JdL=0A=
kKWoePhzQ3ukYbDYWMzhbGZ+nPMJXlVjhNWo7/OxLjBos8Q82KxujZlakE403Daaj4GIULdtl=
kIJ=0A=
89eVgw1BS7Bqa/j8D35in2fE7SZfECYPCE/wpFcozo+47UX2bu4lXapuOb7kky/ZR6By6/qmW=
6/K=0A=
Uz/iDsaWVhFu9+lmqSbYf5VT7QqFiLpPKaVCjF62/IUgAKpoC6EahQGcxEZjgoi2IrHu/qpGW=
X7P=0A=
NSzVttpd90gzFFS269lvzs2I1qsb2pY7HVkCAwEAAaMTMBEwDwYDVR0TAQH/BAUwAwEB/zANB=
gkq=0A=
hkiG9w0BAQUFAAOCAQEAuI3O7+cUus/usESSbLQ5PqKEbq24IXfS1HeCh+YgQYHu4vgRt2PRF=
ze+=0A=
GXYkHAQaTOs9qmdvLdTN/mUxcMUbpgIKumB7bVjCmkn+YzILa+M6wKyrO7Do0wlRjBCDxjTgx=
Svg=0A=
GrZgFCdsMneMvLJymM/NzD+5yCRCFNZX/OYmQ6kd5YCQzgNUKD73P9P4Te1qCjqTE5s7FCMTY=
5w/=0A=
0YcneeVMUeMBrYVdGjux1XMQpNPyvG5k9VpWkKjHDkx0Dy5xO/fIR/RpbxXyEV6DHpx8Uq79A=
toS=0A=
qFlnGNu8cN2bsWntgM6JQEhqDjXKKWYVIZQs6GAqm4VKQPNriiTsBhYscw=3D=3D=0A=
-----END CERTIFICATE-----=0A=
=0A=
Comodo AAA Services root=0A=
=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=0A=
-----BEGIN CERTIFICATE-----=0A=
MIIEMjCCAxqgAwIBAgIBATANBgkqhkiG9w0BAQUFADB7MQswCQYDVQQGEwJHQjEbMBkGA1UEC=
AwS=0A=
R3JlYXRlciBNYW5jaGVzdGVyMRAwDgYDVQQHDAdTYWxmb3JkMRowGAYDVQQKDBFDb21vZG8gQ=
0Eg=0A=
TGltaXRlZDEhMB8GA1UEAwwYQUFBIENlcnRpZmljYXRlIFNlcnZpY2VzMB4XDTA0MDEwMTAwM=
DAw=0A=
MFoXDTI4MTIzMTIzNTk1OVowezELMAkGA1UEBhMCR0IxGzAZBgNVBAgMEkdyZWF0ZXIgTWFuY=
2hl=0A=
c3RlcjEQMA4GA1UEBwwHU2FsZm9yZDEaMBgGA1UECgwRQ29tb2RvIENBIExpbWl0ZWQxITAfB=
gNV=0A=
BAMMGEFBQSBDZXJ0aWZpY2F0ZSBTZXJ2aWNlczCCASIwDQYJKoZIhvcNAQEBBQADggEPADCCA=
QoC=0A=
ggEBAL5AnfRu4ep2hxxNRUSOvkbIgwadwSr+GB+O5AL686tdUIoWMQuaBtDFcCLNSS1UY8y2b=
mhG=0A=
C1Pqy0wkwLxyTurxFa70VJoSCsN6sjNg4tqJVfMiWPPe3M/vg4aijJRPn2jymJBGhCfHdr/jz=
DUs=0A=
i14HZGWCwEiwqJH5YZ92IFCokcdmtet4YgNW8IoaE+oxox6gmf049vYnMlhvB/VruPsUK6+3q=
szW=0A=
Y19zjNoFmag4qMsXeDZRrOme9Hg6jc8P2ULimAyrL58OAd7vn5lJ8S3frHRNG5i1R8XlKdH5k=
BjH=0A=
Ypy+g8cmez6KJcfA3Z3mNWgQIJ2P2N7Sw4ScDV7oL8kCAwEAAaOBwDCBvTAdBgNVHQ4EFgQUo=
BEK=0A=
Iz6W8Qfs4q8p74Klf9AwpLQwDgYDVR0PAQH/BAQDAgEGMA8GA1UdEwEB/wQFMAMBAf8wewYDV=
R0f=0A=
BHQwcjA4oDagNIYyaHR0cDovL2NybC5jb21vZG9jYS5jb20vQUFBQ2VydGlmaWNhdGVTZXJ2a=
WNl=0A=
cy5jcmwwNqA0oDKGMGh0dHA6Ly9jcmwuY29tb2RvLm5ldC9BQUFDZXJ0aWZpY2F0ZVNlcnZpY=
2Vz=0A=
LmNybDANBgkqhkiG9w0BAQUFAAOCAQEACFb8AvCb6P+k+tZ7xkSAzk/ExfYAWMymtrwUSWgEd=
ujm=0A=
7l3sAg9g1o1QGE8mTgHj5rCl7r+8dFRBv/38ErjHT1r0iWAFf2C3BUrz9vHCv8S5dIa2LX1rz=
NLz=0A=
Rt0vxuBqw8M0Ayx9lt1awg6nCpnBBYurDC/zXDrPbDdVCYfeU0BsWO/8tqtlbgT2G9w84FoVx=
p7Z=0A=
8VlIMCFlA2zs6SFz7JsDoeA3raAVGI/6ugLOpyypEBMs1OUIJqsil2D4kF501KKaU73yqWjgo=
m7C=0A=
12yxow+ev+to51byrvLjKzg6CYG1a4XXvi3tPxq3smPi9WIsgtRqAEFQ8TmDn5XpNpaYbg=3D=
=3D=0A=
-----END CERTIFICATE-----=0A=
=0A=
Comodo Secure Services root=0A=
=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=
=3D=3D=0A=
-----BEGIN CERTIFICATE-----=0A=
MIIEPzCCAyegAwIBAgIBATANBgkqhkiG9w0BAQUFADB+MQswCQYDVQQGEwJHQjEbMBkGA1UEC=
AwS=0A=
R3JlYXRlciBNYW5jaGVzdGVyMRAwDgYDVQQHDAdTYWxmb3JkMRowGAYDVQQKDBFDb21vZG8gQ=
0Eg=0A=
TGltaXRlZDEkMCIGA1UEAwwbU2VjdXJlIENlcnRpZmljYXRlIFNlcnZpY2VzMB4XDTA0MDEwM=
TAw=0A=
MDAwMFoXDTI4MTIzMTIzNTk1OVowfjELMAkGA1UEBhMCR0IxGzAZBgNVBAgMEkdyZWF0ZXIgT=
WFu=0A=
Y2hlc3RlcjEQMA4GA1UEBwwHU2FsZm9yZDEaMBgGA1UECgwRQ29tb2RvIENBIExpbWl0ZWQxJ=
DAi=0A=
BgNVBAMMG1NlY3VyZSBDZXJ0aWZpY2F0ZSBTZXJ2aWNlczCCASIwDQYJKoZIhvcNAQEBBQADg=
gEP=0A=
ADCCAQoCggEBAMBxM4KK0HDrc4eCQNUd5MvJDkKQ+d40uaG6EfQlhfPMcm3ye5drswfxdySRX=
yWP=0A=
9nQ95IDC+DwN879A6vfIUtFyb+/Iq0G4bi4XKpVpDM3SHpR7LZQdqnXXs5jLrLxkU0C8j6ysN=
stc=0A=
rbvd4JQX7NFc0L/vpZXJkMWwrPsbQ996CF23uPJAGysnnlDOXmWCiIxe004MeuoIkbY2qitC+=
+rC=0A=
oznl2yY4rYsK7hljxxwk3wN42ubqwUcaCwtGCd0C/N7Lh1/XMGNooa7cMqG6vv5Eq2i2pRcV/=
b3V=0A=
p6ea5EQz6YiO/O1R65NxTq0B50SOqy3LqP4BSUjwwN3HaNiS/j0CAwEAAaOBxzCBxDAdBgNVH=
Q4E=0A=
FgQUPNiTiMLAggnMAZkGkyDpnnAJY08wDgYDVR0PAQH/BAQDAgEGMA8GA1UdEwEB/wQFMAMBA=
f8w=0A=
gYEGA1UdHwR6MHgwO6A5oDeGNWh0dHA6Ly9jcmwuY29tb2RvY2EuY29tL1NlY3VyZUNlcnRpZ=
mlj=0A=
YXRlU2VydmljZXMuY3JsMDmgN6A1hjNodHRwOi8vY3JsLmNvbW9kby5uZXQvU2VjdXJlQ2Vyd=
Glm=0A=
aWNhdGVTZXJ2aWNlcy5jcmwwDQYJKoZIhvcNAQEFBQADggEBAIcBbSMdflsXfcFhMs+P5/OKl=
Flm=0A=
4J4oqF7Tt/Q05qo5spcWxYJvMqTpjOev/e/C6LlLqqP05tqNZSH7uoDrJiiFGv45jN5bBAS0V=
Pmj=0A=
Z55B+glSzAVIqMk/IQQezkhr/IXownuvf7fM+F86/TXGDe+X3EyrEeFryzHRbPtIgKvcnDe4I=
RRL=0A=
DXE97IMzbtFuMhbsmMcWi1mmNKsFVy2T96oTy9IT4rcuO81rUBcJaD61JlfutuC23bkpgHl9j=
6Pw=0A=
pCikFcSF9CfUa7/lXORlAnZUtOM3ZiTTGWHIUhDlizeauan5Hb/qmZJhlv8BzaFfDbxxvA6sC=
x1H=0A=
RR3B7Hzs/Sk=3D=0A=
-----END CERTIFICATE-----=0A=
=0A=
Comodo Trusted Services root=0A=
=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=
=3D=3D=3D=0A=
-----BEGIN CERTIFICATE-----=0A=
MIIEQzCCAyugAwIBAgIBATANBgkqhkiG9w0BAQUFADB/MQswCQYDVQQGEwJHQjEbMBkGA1UEC=
AwS=0A=
R3JlYXRlciBNYW5jaGVzdGVyMRAwDgYDVQQHDAdTYWxmb3JkMRowGAYDVQQKDBFDb21vZG8gQ=
0Eg=0A=
TGltaXRlZDElMCMGA1UEAwwcVHJ1c3RlZCBDZXJ0aWZpY2F0ZSBTZXJ2aWNlczAeFw0wNDAxM=
DEw=0A=
MDAwMDBaFw0yODEyMzEyMzU5NTlaMH8xCzAJBgNVBAYTAkdCMRswGQYDVQQIDBJHcmVhdGVyI=
E1h=0A=
bmNoZXN0ZXIxEDAOBgNVBAcMB1NhbGZvcmQxGjAYBgNVBAoMEUNvbW9kbyBDQSBMaW1pdGVkM=
SUw=0A=
IwYDVQQDDBxUcnVzdGVkIENlcnRpZmljYXRlIFNlcnZpY2VzMIIBIjANBgkqhkiG9w0BAQEFA=
AOC=0A=
AQ8AMIIBCgKCAQEA33FvNlhTWvI2VFeAxHQIIO0Yfyod5jWaHiWsnOWWfnJSoBVC21ndZHoa0=
Lh7=0A=
3TkVvFVIxO06AOoxEbrycXQaZ7jPM8yoMa+j49d/vzMtTGo87IvDktJTdyR0nAducPy9C1t2u=
l/y=0A=
/9c3S0pgePfw+spwtOpZqqPOSC+pw7ILfhdyFgymBwwbOM/JYrc/oJOlh0Hyt3BAd9i+FHzjq=
MB6=0A=
juljatEPmsbS9Is6FARW1O24zG71++IsWL1/T2sr92AkWCTOJu80kTrV44HQsvAEAtdbtz6Sr=
GsS=0A=
ivnkBbA7kUlcsutT6vifR4buv5XAwAaf0lteERv0xwQ1KdJVXOTt6wIDAQABo4HJMIHGMB0GA=
1Ud=0A=
DgQWBBTFe1i97doladL3WRaoszLAeydb9DAOBgNVHQ8BAf8EBAMCAQYwDwYDVR0TAQH/BAUwA=
wEB=0A=
/zCBgwYDVR0fBHwwejA8oDqgOIY2aHR0cDovL2NybC5jb21vZG9jYS5jb20vVHJ1c3RlZENlc=
nRp=0A=
ZmljYXRlU2VydmljZXMuY3JsMDqgOKA2hjRodHRwOi8vY3JsLmNvbW9kby5uZXQvVHJ1c3RlZ=
ENl=0A=
cnRpZmljYXRlU2VydmljZXMuY3JsMA0GCSqGSIb3DQEBBQUAA4IBAQDIk4E7ibSvuIQSTI3S8=
Ntw=0A=
uleGFTQQuS9/HrCoiWChisJ3DFBKmwCL2Iv0QeLQg4pKHBQGsKNoBXAxMKdTmw7pSqBYaWcOr=
p32=0A=
pSxBvzwGa+RZzG0Q8ZZvH9/0BAKkn0U+yNj6NkZEUD+Cl5EfKNsYEYwq5GWDVxISjBc/lDb+X=
bDA=0A=
BHcTuPQV1T84zJQ6VdCsmPW6AF/ghhmBeC8owH7TzEIK9a5QoNE+xqFx7D+gIIxmOom0jtTYs=
U0l=0A=
R+4viMi14QVFwL4Ucd56/Y57fU0IlqUSc/AtyjcndBInTMu2l+nZrghtWjlA3QVHdWpaIbOjG=
M9O=0A=
9y5Xt5hwXsjEeLBi=0A=
-----END CERTIFICATE-----=0A=
=0A=
QuoVadis Root CA=0A=
=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=0A=
-----BEGIN CERTIFICATE-----=0A=
MIIF0DCCBLigAwIBAgIEOrZQizANBgkqhkiG9w0BAQUFADB/MQswCQYDVQQGEwJCTTEZMBcGA=
1UE=0A=
ChMQUXVvVmFkaXMgTGltaXRlZDElMCMGA1UECxMcUm9vdCBDZXJ0aWZpY2F0aW9uIEF1dGhvc=
ml0=0A=
eTEuMCwGA1UEAxMlUXVvVmFkaXMgUm9vdCBDZXJ0aWZpY2F0aW9uIEF1dGhvcml0eTAeFw0wM=
TAz=0A=
MTkxODMzMzNaFw0yMTAzMTcxODMzMzNaMH8xCzAJBgNVBAYTAkJNMRkwFwYDVQQKExBRdW9WY=
WRp=0A=
cyBMaW1pdGVkMSUwIwYDVQQLExxSb290IENlcnRpZmljYXRpb24gQXV0aG9yaXR5MS4wLAYDV=
QQD=0A=
EyVRdW9WYWRpcyBSb290IENlcnRpZmljYXRpb24gQXV0aG9yaXR5MIIBIjANBgkqhkiG9w0BA=
QEF=0A=
AAOCAQ8AMIIBCgKCAQEAv2G1lVO6V/z68mcLOhrfEYBklbTRvM16z/Ypli4kVEAkOPcahdxYT=
Muk=0A=
J0KX0J+DisPkBgNbAKVRHnAEdOLB1Dqr1607BxgFjv2DrOpm2RgbaIr1VxqYuvXtdj182d6Ua=
jtL=0A=
F8HVj71lODqV0D1VNk7feVcxKh7YWWVJWCCYfqtffp/p1k3sg3Spx2zY7ilKhSoGFPlU5tPaZ=
QeL=0A=
YzcS19Dsw3sgQUSj7cugF+FxZc4dZjH3dgEZyH0DWLaVSR2mEiboxgx24ONmy+pdpibu5cxfv=
Wen=0A=
AScOospUxbF6lR1xHkopigPcakXBpBlebzbNw6Kwt/5cOOJSvPhEQ+aQuwIDAQABo4ICUjCCA=
k4w=0A=
PQYIKwYBBQUHAQEEMTAvMC0GCCsGAQUFBzABhiFodHRwczovL29jc3AucXVvdmFkaXNvZmZza=
G9y=0A=
ZS5jb20wDwYDVR0TAQH/BAUwAwEB/zCCARoGA1UdIASCAREwggENMIIBCQYJKwYBBAG+WAABM=
IH7=0A=
MIHUBggrBgEFBQcCAjCBxxqBxFJlbGlhbmNlIG9uIHRoZSBRdW9WYWRpcyBSb290IENlcnRpZ=
mlj=0A=
YXRlIGJ5IGFueSBwYXJ0eSBhc3N1bWVzIGFjY2VwdGFuY2Ugb2YgdGhlIHRoZW4gYXBwbGljY=
WJs=0A=
ZSBzdGFuZGFyZCB0ZXJtcyBhbmQgY29uZGl0aW9ucyBvZiB1c2UsIGNlcnRpZmljYXRpb24gc=
HJh=0A=
Y3RpY2VzLCBhbmQgdGhlIFF1b1ZhZGlzIENlcnRpZmljYXRlIFBvbGljeS4wIgYIKwYBBQUHA=
gEW=0A=
Fmh0dHA6Ly93d3cucXVvdmFkaXMuYm0wHQYDVR0OBBYEFItLbe3TKbkGGew5Oanwl4Rqy+/fM=
IGu=0A=
BgNVHSMEgaYwgaOAFItLbe3TKbkGGew5Oanwl4Rqy+/foYGEpIGBMH8xCzAJBgNVBAYTAkJNM=
Rkw=0A=
FwYDVQQKExBRdW9WYWRpcyBMaW1pdGVkMSUwIwYDVQQLExxSb290IENlcnRpZmljYXRpb24gQ=
XV0=0A=
aG9yaXR5MS4wLAYDVQQDEyVRdW9WYWRpcyBSb290IENlcnRpZmljYXRpb24gQXV0aG9yaXR5g=
gQ6=0A=
tlCLMA4GA1UdDwEB/wQEAwIBBjANBgkqhkiG9w0BAQUFAAOCAQEAitQUtf70mpKnGdSkfnIYj=
9lo=0A=
fFIk3WdvOXrEql494liwTXCYhGHoG+NpGA7O+0dQoE7/8CQfvbLO9Sf87C9TqnN7Az10buYWn=
uul=0A=
LsS/VidQK2K6vkscPFVcQR0kvoIgR13VRH56FmjffU1RcHhXHTMe/QKZnAzNCgVPx7uOpHX6S=
m2x=0A=
gI4JVrmcGmD+XcHXetwReNDWXcG31a0ymQM6isxUJTkxgXsTIlG6Rmyhu576BGxJJnSP0nPrz=
DCi=0A=
5upZIof4l/UO/erMkqQWxFIY6iHOsfHmhIHluqmGKPJDWl0Snawe2ajlCmqnf6CHKc/yiU3U7=
MXi=0A=
5nrQNiOKSnQ2+Q=3D=3D=0A=
-----END CERTIFICATE-----=0A=
=0A=
QuoVadis Root CA 2=0A=
=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=0A=
-----BEGIN CERTIFICATE-----=0A=
MIIFtzCCA5+gAwIBAgICBQkwDQYJKoZIhvcNAQEFBQAwRTELMAkGA1UEBhMCQk0xGTAXBgNVB=
AoT=0A=
EFF1b1ZhZGlzIExpbWl0ZWQxGzAZBgNVBAMTElF1b1ZhZGlzIFJvb3QgQ0EgMjAeFw0wNjExM=
jQx=0A=
ODI3MDBaFw0zMTExMjQxODIzMzNaMEUxCzAJBgNVBAYTAkJNMRkwFwYDVQQKExBRdW9WYWRpc=
yBM=0A=
aW1pdGVkMRswGQYDVQQDExJRdW9WYWRpcyBSb290IENBIDIwggIiMA0GCSqGSIb3DQEBAQUAA=
4IC=0A=
DwAwggIKAoICAQCaGMpLlA0ALa8DKYrwD4HIrkwZhR0In6spRIXzL4GtMh6QRr+jhiYaHv5+H=
Bg6=0A=
XJxgFyo6dIMzMH1hVBHL7avg5tKifvVrbxi3Cgst/ek+7wrGsxDp3MJGF/hd/aTa/55JWpzmM=
+Yk=0A=
lvc/ulsrHHo1wtZn/qtmUIttKGAr79dgw8eTvI02kfN/+NsRE8Scd3bBrrcCaoF6qUWD4gXmu=
VbB=0A=
lDePSHFjIuwXZQeVikvfj8ZaCuWw419eaxGrDPmF60Tp+ARz8un+XJiM9XOva7R+zdRcAitMO=
eGy=0A=
lZUtQofX1bOQQ7dsE/He3fbE+Ik/0XX1ksOR1YqI0JDs3G3eicJlcZaLDQP9nL9bFqyS2+r+e=
Xyt=0A=
66/3FsvbzSUr5R/7mp/iUcw6UwxI5g69ybR2BlLmEROFcmMDBOAENisgGQLodKcftslWZvB1J=
dxn=0A=
wQ5hYIizPtGo/KPaHbDRsSNU30R2be1B2MGyIrZTHN81Hdyhdyox5C315eXbyOD/5YDXC2Og/=
zOh=0A=
D7osFRXql7PSorW+8oyWHhqPHWykYTe5hnMz15eWniN9gqRMgeKh0bpnX5UHoycR7hYQe7xFS=
kyy=0A=
BNKr79X9DFHOUGoIMfmR2gyPZFwDwzqLID9ujWc9Otb+fVuIyV77zGHcizN300QyNQliBJIWE=
Nie=0A=
J0f7OyHj+OsdWwIDAQABo4GwMIGtMA8GA1UdEwEB/wQFMAMBAf8wCwYDVR0PBAQDAgEGMB0GA=
1Ud=0A=
DgQWBBQahGK8SEwzJQTU7tD2A8QZRtGUazBuBgNVHSMEZzBlgBQahGK8SEwzJQTU7tD2A8QZR=
tGU=0A=
a6FJpEcwRTELMAkGA1UEBhMCQk0xGTAXBgNVBAoTEFF1b1ZhZGlzIExpbWl0ZWQxGzAZBgNVB=
AMT=0A=
ElF1b1ZhZGlzIFJvb3QgQ0EgMoICBQkwDQYJKoZIhvcNAQEFBQADggIBAD4KFk2fBluornFdL=
wUv=0A=
Z+YTRYPENvbzwCYMDbVHZF34tHLJRqUDGCdViXh9duqWNIAXINzng/iN/Ae42l9NLmeyhP3ZR=
Px3=0A=
UIHmfLTJDQtyU/h2BwdBR5YM++CCJpNVjP4iH2BlfF/nJrP3MpCYUNQ3cVX2kiF495V5+vgtJ=
odm=0A=
VjB3pjd4M1IQWK4/YY7yarHvGH5KWWPKjaJW1acvvFYfzznB4vsKqBUsfU16Y8Zsl0Q80m/DS=
hcK=0A=
+JDSV6IZUaUtl0HaB0+pUNqQjZRG4T7wlP0QADj1O+hA4bRuVhogzG9Yje0uRY/W6ZM/57Es3=
zrW=0A=
IozchLsib9D45MY56QSIPMO661V6bYCZJPVsAfv4l7CUW+v90m/xd2gNNWQjrLhVoQPRTUIZ3=
Ph1=0A=
WVaj+ahJefivDrkRoHy3au000LYmYjgahwz46P0u05B/B5EqHdZ+XIWDmbA4CD/pXvk1B+TJY=
m5X=0A=
f6dQlfe6yJvmjqIBxdZmv3lh8zwc4bmCXF2gw+nYSL0ZohEUGW6yhhtoPkg3Goi3XZZenMfvJ=
2II=0A=
4pEZXNLxId26F0KCl3GBUzGpn/Z9Yr9y4aOTHcyKJloJONDO1w2AFrR4pTqHTI2KpdVGl/IsE=
Lm8=0A=
VCLAAVBpQ570su9t+Oza8eOx79+Rj1QqCyXBJhnEUhAFZdWCEOrCMc0u=0A=
-----END CERTIFICATE-----=0A=
=0A=
QuoVadis Root CA 3=0A=
=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=0A=
-----BEGIN CERTIFICATE-----=0A=
MIIGnTCCBIWgAwIBAgICBcYwDQYJKoZIhvcNAQEFBQAwRTELMAkGA1UEBhMCQk0xGTAXBgNVB=
AoT=0A=
EFF1b1ZhZGlzIExpbWl0ZWQxGzAZBgNVBAMTElF1b1ZhZGlzIFJvb3QgQ0EgMzAeFw0wNjExM=
jQx=0A=
OTExMjNaFw0zMTExMjQxOTA2NDRaMEUxCzAJBgNVBAYTAkJNMRkwFwYDVQQKExBRdW9WYWRpc=
yBM=0A=
aW1pdGVkMRswGQYDVQQDExJRdW9WYWRpcyBSb290IENBIDMwggIiMA0GCSqGSIb3DQEBAQUAA=
4IC=0A=
DwAwggIKAoICAQDMV0IWVJzmmNPTTe7+7cefQzlKZbPoFog02w1ZkXTPkrgEQK0CSzGrvI2Ra=
Ngg=0A=
DhoB4hp7Thdd4oq3P5kazethq8Jlph+3t723j/z9cI8LoGe+AaJZz3HmDyl2/7FWeUUrH556V=
Oij=0A=
KTVopAFPD6QuN+8bv+OPEKhyq1hX51SGyMnzW9os2l2ObjyjPtr7guXd8lyyBTNvijbO0BNO/=
79K=0A=
DDRMpsMhvVAEVeuxu537RR5kFd5VAYwCdrXLoT9CabwvvWhDFlaJKjdhkf2mrk7AyxRllDdLk=
gbv=0A=
BNDInIjbC3uBr7E9KsRlOni27tyAsdLTmZw67mtaa7ONt9XOnMK+pUsvFrGeaDsGb659n/je7=
Mwp=0A=
p5ijJUMv7/FfJuGITfhebtfZFG4ZM2mnO4SJk8RTVROhUXhA+LjJou57ulJCg54U7QVSWllWp=
5f8=0A=
nT8KKdjcT5EOE7zelaTfi5m+rJsziO+1ga8bxiJTyPbH7pcUsMV8eFLI8M5ud2CEpukqdiDtW=
AEX=0A=
MJPpGovgc2PZapKUSU60rUqFxKMiMPwJ7Wgic6aIDFUhWMXhOp8q3crhkODZc6tsgLjoC2STo=
JyM=0A=
Gf+z0gzskSaHirOi4XCPLArlzW1oUevaPwV/izLmE1xr/l9A4iLItLRkT9a6fUg+qGkM17uGc=
clz=0A=
uD87nSVL2v9A6wIDAQABo4IBlTCCAZEwDwYDVR0TAQH/BAUwAwEB/zCB4QYDVR0gBIHZMIHWM=
IHT=0A=
BgkrBgEEAb5YAAMwgcUwgZMGCCsGAQUFBwICMIGGGoGDQW55IHVzZSBvZiB0aGlzIENlcnRpZ=
mlj=0A=
YXRlIGNvbnN0aXR1dGVzIGFjY2VwdGFuY2Ugb2YgdGhlIFF1b1ZhZGlzIFJvb3QgQ0EgMyBDZ=
XJ0=0A=
aWZpY2F0ZSBQb2xpY3kgLyBDZXJ0aWZpY2F0aW9uIFByYWN0aWNlIFN0YXRlbWVudC4wLQYIK=
wYB=0A=
BQUHAgEWIWh0dHA6Ly93d3cucXVvdmFkaXNnbG9iYWwuY29tL2NwczALBgNVHQ8EBAMCAQYwH=
QYD=0A=
VR0OBBYEFPLAE+CCQz777i9nMpY1XNu4ywLQMG4GA1UdIwRnMGWAFPLAE+CCQz777i9nMpY1X=
Nu4=0A=
ywLQoUmkRzBFMQswCQYDVQQGEwJCTTEZMBcGA1UEChMQUXVvVmFkaXMgTGltaXRlZDEbMBkGA=
1UE=0A=
AxMSUXVvVmFkaXMgUm9vdCBDQSAzggIFxjANBgkqhkiG9w0BAQUFAAOCAgEAT62gLEz6wPJv9=
2ZV=0A=
qyM07ucp2sNbtrCD2dDQ4iH782CnO11gUyeim/YIIirnv6By5ZwkajGxkHon24QRiSemd1o41=
7+s=0A=
hvzuXYO8BsbRd2sPbSQvS3pspweWyuOEn62Iix2rFo1bZhfZFvSLgNLd+LJ2w/w4E6oM3kJpK=
27z=0A=
POuAJ9v1pkQNn1pVWQvVDVJIxa6f8i+AxeoyUDUSly7B4f/xI4hROJ/yZlZ25w9Rl6VSDE1JU=
ZU2=0A=
Pb+iSwwQHYaZTKrzchGT5Or2m9qoXadNt54CrnMAyNojA+j56hl0YgCUyyIgvpSnWbWCar6Ze=
Xqp=0A=
8kokUvd0/bpO5qgdAm6xDYBEwa7TIzdfu4V8K5Iu6H6li92Z4b8nby1dqnuH/grdS/yO9Sbkb=
nBC=0A=
bjPsMZ57k8HkyWkaPcBrTiJt7qtYTcbQQcEr6k8Sh17rRdhs9ZgC06DYVYoGmRmioHfRMJ6sz=
HXu=0A=
g/WwYjnPbFfiTNKRCw51KBuav/0aQ/HKd/s7j2G4aSgWQgRecCocIdiP4b0jWy10QJLZYxkNc=
91p=0A=
vGJHvOB0K7Lrfb5BG7XARsWhIstfTsEokt4YutUqKLsRixeTmJlglFwjz1onl14LBQaTNx47a=
Tbr=0A=
qZ5hHY8y2o4M1nQ+ewkk2gF3R8Q7zTSMmfXK4SVhM7JZG+Ju1zdXtg2pEto=3D=0A=
-----END CERTIFICATE-----=0A=
=0A=
Security Communication Root CA=0A=
=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=
=3D=3D=3D=3D=3D=0A=
-----BEGIN CERTIFICATE-----=0A=
MIIDWjCCAkKgAwIBAgIBADANBgkqhkiG9w0BAQUFADBQMQswCQYDVQQGEwJKUDEYMBYGA1UEC=
hMP=0A=
U0VDT00gVHJ1c3QubmV0MScwJQYDVQQLEx5TZWN1cml0eSBDb21tdW5pY2F0aW9uIFJvb3RDQ=
TEw=0A=
HhcNMDMwOTMwMDQyMDQ5WhcNMjMwOTMwMDQyMDQ5WjBQMQswCQYDVQQGEwJKUDEYMBYGA1UEC=
hMP=0A=
U0VDT00gVHJ1c3QubmV0MScwJQYDVQQLEx5TZWN1cml0eSBDb21tdW5pY2F0aW9uIFJvb3RDQ=
TEw=0A=
ggEiMA0GCSqGSIb3DQEBAQUAA4IBDwAwggEKAoIBAQCzs/5/022x7xZ8V6UMbXaKL0u/ZPtM7=
orw=0A=
8yl89f/uKuDp6bpbZCKamm8sOiZpUQWZJtzVHGpxxpp9Hp3dfGzGjGdnSj74cbAZJ6kJDKaVv=
0uM=0A=
DPpVmDvY6CKhS3E4eayXkmmziX7qIWgGmBSWh9JhNrxtJ1aeV+7AwFb9Ms+k2Y7CI9eNqPPYJ=
ayX=0A=
5HA49LY6tJ07lyZDo6G8SVlyTCMwhwFY9k6+HGhWZq/NQV3Is00qVUarH9oe4kA92819uZKAn=
Dfd=0A=
DJZkndwi92SL32HeFZRSFaB9UslLqCHJxrHty8OVYNEP8Ktw+N/LTX7s1vqr2b1/VPKl6Xn62=
dZ2=0A=
JChzAgMBAAGjPzA9MB0GA1UdDgQWBBSgc0mZaNyFW2XjmygvV5+9M7wHSDALBgNVHQ8EBAMCA=
QYw=0A=
DwYDVR0TAQH/BAUwAwEB/zANBgkqhkiG9w0BAQUFAAOCAQEAaECpqLvkT115swW1F7NgE+vGk=
l3g=0A=
0dNq/vu+m22/xwVtWSDEHPC32oRYAmP6SBbvT6UL90qY8j+eG61Ha2POCEfrUj94nK9NrvjVT=
8+a=0A=
mCoQQTlSxN3Zmw7vkwGusi7KaEIkQmywszo+zenaSMQVy+n5Bw+SUEmK3TGXX8npN6o7WWWXl=
DLJ=0A=
s58+OmJYxUmtYg5xpTKqL8aJdkNAExNnPaJUJRDL8Try2frbSVa7pv6nQTXD4IhhyYjH3zYQI=
phZ=0A=
6rBK+1YWc26sTfcioU+tHXotRSflMMFe8toTyyVCUZVHA4xsIcx0Qu1T/zOLjw9XARYvz6buy=
XAi=0A=
FL39vmwLAw=3D=3D=0A=
-----END CERTIFICATE-----=0A=
=0A=
Sonera Class 2 Root CA=0A=
=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=0A=
-----BEGIN CERTIFICATE-----=0A=
MIIDIDCCAgigAwIBAgIBHTANBgkqhkiG9w0BAQUFADA5MQswCQYDVQQGEwJGSTEPMA0GA1UEC=
hMG=0A=
U29uZXJhMRkwFwYDVQQDExBTb25lcmEgQ2xhc3MyIENBMB4XDTAxMDQwNjA3Mjk0MFoXDTIxM=
DQw=0A=
NjA3Mjk0MFowOTELMAkGA1UEBhMCRkkxDzANBgNVBAoTBlNvbmVyYTEZMBcGA1UEAxMQU29uZ=
XJh=0A=
IENsYXNzMiBDQTCCASIwDQYJKoZIhvcNAQEBBQADggEPADCCAQoCggEBAJAXSjWdyvANlsdE+=
hY3=0A=
/Ei9vX+ALTU74W+oZ6m/AxxNjG8yR9VBaKQTBME1DJqEQ/xcHf+Js+gXGM2RX/uJ4+q/Tl18G=
ybT=0A=
dXnt5oTjV+WtKcT0OijnpXuENmmz/V52vaMtmdOQTiMofRhj8VQ7Jp12W5dCsv+u8E7s3TmVT=
oMG=0A=
f+dJQMjFAbJUWmYdPfz56TwKnoG4cPABi+QjVHzIrviQHgCWctRUz2EjvOr7nQKV0ba5cTppC=
D8P=0A=
tOFCx4j1P5iop7oc4HFx71hXgVB6XGt0Rg6DA5jDjqhu8nYybieDwnPz3BjotJPqdURrBGAgc=
VeH=0A=
nfO+oJAjPYok4doh28MCAwEAAaMzMDEwDwYDVR0TAQH/BAUwAwEB/zARBgNVHQ4ECgQISqCqW=
ITT=0A=
XjwwCwYDVR0PBAQDAgEGMA0GCSqGSIb3DQEBBQUAA4IBAQBazof5FnIVV0sd2ZvnoiYw7JNn3=
9Yt=0A=
0jSv9zilzqsWuasvfDXLrNAPtEwr/IDva4yRXzZ299uzGxnq9LIR/WFxRL8oszodv7ND6J+/3=
DEI=0A=
cbCdjdY0RzKQxmUk96BKfARzjzlvF4xytb1LyHr4e4PDKE6cCepnP7JnBBvDFNr450kkkdAda=
vph=0A=
Oe9r5yF1BgfYErQhIHBCcYHaPJo2vqZbDWpsmh+Re/n570K6Tk6ezAyNlNzZRZxe7EJQY670X=
cSx=0A=
EtzKO6gunRRaBXW37Ndj4ro1tgQIkejanZz2ZrUYrAqmVCY0M9IbwdR/GjqOC6oybtv8TyWf2=
TLH=0A=
llpwrN9M=0A=
-----END CERTIFICATE-----=0A=
=0A=
Staat der Nederlanden Root CA=0A=
=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=
=3D=3D=3D=3D=0A=
-----BEGIN CERTIFICATE-----=0A=
MIIDujCCAqKgAwIBAgIEAJiWijANBgkqhkiG9w0BAQUFADBVMQswCQYDVQQGEwJOTDEeMBwGA=
1UE=0A=
ChMVU3RhYXQgZGVyIE5lZGVybGFuZGVuMSYwJAYDVQQDEx1TdGFhdCBkZXIgTmVkZXJsYW5kZ=
W4g=0A=
Um9vdCBDQTAeFw0wMjEyMTcwOTIzNDlaFw0xNTEyMTYwOTE1MzhaMFUxCzAJBgNVBAYTAk5MM=
R4w=0A=
HAYDVQQKExVTdGFhdCBkZXIgTmVkZXJsYW5kZW4xJjAkBgNVBAMTHVN0YWF0IGRlciBOZWRlc=
mxh=0A=
bmRlbiBSb290IENBMIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAmNK1URF6gaYUm=
HFt=0A=
vsznExvWJw56s2oYHLZhWtVhCb/ekBPHZ+7d89rFDBKeNVU+LCeIQGv33N0iYfXCxw719tV2U=
02P=0A=
jLwYdjeFnejKScfST5gTCaI+Ioicf9byEGW07l8Y1Rfj+MX94p2i71MOhXeiD+EwR+4A5zN9R=
Gca=0A=
C1Hoi6CeUJhoNFIfLm0B8mBF8jHrqTFoKbt6QZ7GGX+UtFE5A3+y3qcym7RHjm+0Sq7lr7Hcs=
Bth=0A=
vJly3uSJt3omXdozSVtSnA71iq3DuD3oBmrC1SoLbHuEvVYFy4ZlkuxEK7COudxwC0barbxji=
Dn6=0A=
22r+I/q85Ej0ZytqERAhSQIDAQABo4GRMIGOMAwGA1UdEwQFMAMBAf8wTwYDVR0gBEgwRjBEB=
gRV=0A=
HSAAMDwwOgYIKwYBBQUHAgEWLmh0dHA6Ly93d3cucGtpb3ZlcmhlaWQubmwvcG9saWNpZXMvc=
m9v=0A=
dC1wb2xpY3kwDgYDVR0PAQH/BAQDAgEGMB0GA1UdDgQWBBSofeu8Y6R0E3QA7Jbg0zTBLL9s+=
DAN=0A=
BgkqhkiG9w0BAQUFAAOCAQEABYSHVXQ2YcG70dTGFagTtJ+k/rvuFbQvBgwp8qiSpGEN/KtcC=
FtR=0A=
EytNwiphyPgJWPwtArI5fZlmgb9uXJVFIGzmeafR2Bwp/MIgJ1HI8XxdNGdphREwxgDS1/PTf=
Lbw=0A=
MVcoEoJz6TMvplW0C5GUR5z6u3pCMuiufi3IvKwUv9kP2Vv8wfl6leF9fpb8cbDCTMjfRTTJz=
g3y=0A=
nGQI0DvDKcWy7ZAEwbEpkcUwb8GpcjPM/l0WFywRaed+/sWDCN+83CI6LiBpIzlWYGeQiy52O=
fsR=0A=
iJf2fL1LuCAWZwWN4jvBcj+UlTfHXbme2JOhF4//DGYVwSR8MnwDHTuhWEUykw=3D=3D=0A=
-----END CERTIFICATE-----=0A=
=0A=
UTN DATACorp SGC Root CA=0A=
=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=0A=
-----BEGIN CERTIFICATE-----=0A=
MIIEXjCCA0agAwIBAgIQRL4Mi1AAIbQR0ypoBqmtaTANBgkqhkiG9w0BAQUFADCBkzELMAkGA=
1UE=0A=
BhMCVVMxCzAJBgNVBAgTAlVUMRcwFQYDVQQHEw5TYWx0IExha2UgQ2l0eTEeMBwGA1UEChMVV=
Ghl=0A=
IFVTRVJUUlVTVCBOZXR3b3JrMSEwHwYDVQQLExhodHRwOi8vd3d3LnVzZXJ0cnVzdC5jb20xG=
zAZ=0A=
BgNVBAMTElVUTiAtIERBVEFDb3JwIFNHQzAeFw05OTA2MjQxODU3MjFaFw0xOTA2MjQxOTA2M=
zBa=0A=
MIGTMQswCQYDVQQGEwJVUzELMAkGA1UECBMCVVQxFzAVBgNVBAcTDlNhbHQgTGFrZSBDaXR5M=
R4w=0A=
HAYDVQQKExVUaGUgVVNFUlRSVVNUIE5ldHdvcmsxITAfBgNVBAsTGGh0dHA6Ly93d3cudXNlc=
nRy=0A=
dXN0LmNvbTEbMBkGA1UEAxMSVVROIC0gREFUQUNvcnAgU0dDMIIBIjANBgkqhkiG9w0BAQEFA=
AOC=0A=
AQ8AMIIBCgKCAQEA3+5YEKIrblXEjr8uRgnn4AgPLit6E5Qbvfa2gI5lBZMAHryv4g+OGQ0SR=
+ys=0A=
raP6LnD43m77VkIVni5c7yPeIbkFdicZD0/Ww5y0vpQZY/KmEQrrU0icvvIpOxboGqBMpsn0G=
Flo=0A=
wHDyUwDAXlCCpVZvNvlK4ESGoE1O1kduSUrLZ9emxAW5jh70/P/N5zbgnAVssjMiFdC04MwXw=
LLA=0A=
9P4yPykqlXvY8qdOD1R8oQ2AswkDwf9c3V6aPryuvEeKaq5xyh+xKrhfQgUL7EYw0XILyulWb=
fXv=0A=
33i+Ybqypa4ETLyorGkVl73v67SMvzX41MPRKA5cOp9wGDMgd8SirwIDAQABo4GrMIGoMAsGA=
1Ud=0A=
DwQEAwIBxjAPBgNVHRMBAf8EBTADAQH/MB0GA1UdDgQWBBRTMtGzz3/64PGgXYVOktKeRR20T=
zA9=0A=
BgNVHR8ENjA0MDKgMKAuhixodHRwOi8vY3JsLnVzZXJ0cnVzdC5jb20vVVROLURBVEFDb3JwU=
0dD=0A=
LmNybDAqBgNVHSUEIzAhBggrBgEFBQcDAQYKKwYBBAGCNwoDAwYJYIZIAYb4QgQBMA0GCSqGS=
Ib3=0A=
DQEBBQUAA4IBAQAnNZcAiosovcYzMB4p/OL31ZjUQLtgyr+rFywJNn9Q+kHcrpY6CiM+iVnJo=
wft=0A=
Gzet/Hy+UUla3joKVAgWRcKZsYfNjGjgaQPpxE6YsjuMFrMOoAyYUJuTqXAJyCyjj98C5OBxO=
vG0=0A=
I3KgqgHf35g+FFCgMSa9KOlaMCZ1+XtgHI3zzVAmbQQnmt/VDUVHKWss5nbZqSl9Mt3JNjy9r=
jXx=0A=
EZ4du5A/EkdOjtd+D2JzHVImOBwYSf0wdJrE5SIv2MCN7ZF6TACPcn9d2t0bi0Vr591pl6jFV=
kwP=0A=
DPafepE39peC4N1xaf92P2BNPM/3mfnGV/TJVTl4uix5yaaIK/QI=0A=
-----END CERTIFICATE-----=0A=
=0A=
UTN USERFirst Hardware Root CA=0A=
=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=
=3D=3D=3D=3D=3D=0A=
-----BEGIN CERTIFICATE-----=0A=
MIIEdDCCA1ygAwIBAgIQRL4Mi1AAJLQR0zYq/mUK/TANBgkqhkiG9w0BAQUFADCBlzELMAkGA=
1UE=0A=
BhMCVVMxCzAJBgNVBAgTAlVUMRcwFQYDVQQHEw5TYWx0IExha2UgQ2l0eTEeMBwGA1UEChMVV=
Ghl=0A=
IFVTRVJUUlVTVCBOZXR3b3JrMSEwHwYDVQQLExhodHRwOi8vd3d3LnVzZXJ0cnVzdC5jb20xH=
zAd=0A=
BgNVBAMTFlVUTi1VU0VSRmlyc3QtSGFyZHdhcmUwHhcNOTkwNzA5MTgxMDQyWhcNMTkwNzA5M=
Tgx=0A=
OTIyWjCBlzELMAkGA1UEBhMCVVMxCzAJBgNVBAgTAlVUMRcwFQYDVQQHEw5TYWx0IExha2UgQ=
2l0=0A=
eTEeMBwGA1UEChMVVGhlIFVTRVJUUlVTVCBOZXR3b3JrMSEwHwYDVQQLExhodHRwOi8vd3d3L=
nVz=0A=
ZXJ0cnVzdC5jb20xHzAdBgNVBAMTFlVUTi1VU0VSRmlyc3QtSGFyZHdhcmUwggEiMA0GCSqGS=
Ib3=0A=
DQEBAQUAA4IBDwAwggEKAoIBAQCx98M4P7Sof885glFn0G2f0v9Y8+efK+wNiVSZuTiZFvfgI=
XlI=0A=
wrthdBKWHTxqctU8EGc6Oe0rE81m65UJM6Rsl7HoxuzBdXmcRl6Nq9Bq/bkqVRcQVLMZ8Jr28=
bFd=0A=
tqdt++BxF2uiiPsA3/4aMXcMmgF6sTLjKwEHOG7DpV4jvEWbe1DByTCP2+UretNb+zNAHqDVm=
Be8=0A=
i4fDidNdoI6yqqr2jmmIBsX6iSHzCJ1pLgkzmykNRg+MzEk0sGlRvfkGzWitZky8PqxhvQqID=
sjf=0A=
Pe58BEydCl5rkdbux+0ojatNh4lz0G6k0B4WixThdkQDf2Os5M1JnMWS9KsyoUhbAgMBAAGjg=
bkw=0A=
gbYwCwYDVR0PBAQDAgHGMA8GA1UdEwEB/wQFMAMBAf8wHQYDVR0OBBYEFKFyXyYbKJhDlV0HN=
9WF=0A=
lp1L0sNFMEQGA1UdHwQ9MDswOaA3oDWGM2h0dHA6Ly9jcmwudXNlcnRydXN0LmNvbS9VVE4tV=
VNF=0A=
UkZpcnN0LUhhcmR3YXJlLmNybDAxBgNVHSUEKjAoBggrBgEFBQcDAQYIKwYBBQUHAwUGCCsGA=
QUF=0A=
BwMGBggrBgEFBQcDBzANBgkqhkiG9w0BAQUFAAOCAQEARxkP3nTGmZev/K0oXnWO6y1n7k57K=
9cM=0A=
//bey1WiCuFMVGWTYGufEpytXoMs61quwOQt9ABjHbjAbPLPSbtNk28GpgoiskliCE7/yMgUs=
ogW=0A=
XecB5BKV5UU0s4tpvc+0hY91UZ59Ojg6FEgSxvunOxqNDYJAB+gECJChicsZUN/KHAG8HQQZe=
xB2=0A=
lzvukJDKxA4fFm517zP4029bHpbj4HR3dHuKom4t3XbWOTCC8KucUvIqx69JXn7HaOWCgchqJ=
/kn=0A=
iCrVWFCVH/A7HFe7fRQ5YiuayZSSKqMiDP+JJn1fIytH1xUdqWqeUQ0qUZ6B+dQ7XnASfxAyn=
B67=0A=
nfhmqA=3D=3D=0A=
-----END CERTIFICATE-----=0A=
=0A=
Camerfirma Chambers of Commerce Root=0A=
=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=
=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=0A=
-----BEGIN CERTIFICATE-----=0A=
MIIEvTCCA6WgAwIBAgIBADANBgkqhkiG9w0BAQUFADB/MQswCQYDVQQGEwJFVTEnMCUGA1UEC=
hMe=0A=
QUMgQ2FtZXJmaXJtYSBTQSBDSUYgQTgyNzQzMjg3MSMwIQYDVQQLExpodHRwOi8vd3d3LmNoY=
W1i=0A=
ZXJzaWduLm9yZzEiMCAGA1UEAxMZQ2hhbWJlcnMgb2YgQ29tbWVyY2UgUm9vdDAeFw0wMzA5M=
zAx=0A=
NjEzNDNaFw0zNzA5MzAxNjEzNDRaMH8xCzAJBgNVBAYTAkVVMScwJQYDVQQKEx5BQyBDYW1lc=
mZp=0A=
cm1hIFNBIENJRiBBODI3NDMyODcxIzAhBgNVBAsTGmh0dHA6Ly93d3cuY2hhbWJlcnNpZ24ub=
3Jn=0A=
MSIwIAYDVQQDExlDaGFtYmVycyBvZiBDb21tZXJjZSBSb290MIIBIDANBgkqhkiG9w0BAQEFA=
AOC=0A=
AQ0AMIIBCAKCAQEAtzZV5aVdGDDg2olUkfzIx1L4L1DZ77F1c2VHfRtbunXF/KGIJPov7coIS=
jlU=0A=
xFF6tdpg6jg8gbLL8bvZkSM/SAFwdakFKq0fcfPJVD0dBmpAPrMMhe5cG3nCYsS4No41XQEMI=
wRH=0A=
NaqbYE6gZj3LJgqcQKH0XZi/caulAGgq7YN6D6IUtdQis4CwPAxaUWktWBiP7Zme8a7ileb2R=
6jW=0A=
DA+wWFjbw2Y3npuRVDM30pQcakjJyfKl2qUMI/cjDpwyVV5xnIQFUZot/eZOKjRa3spAN2cMV=
CFV=0A=
d9oKDMyXroDclDZK9D7ONhMeU+SsTjoF7Nuucpw4i9A5O4kKPnf+dQIBA6OCAUQwggFAMBIGA=
1Ud=0A=
EwEB/wQIMAYBAf8CAQwwPAYDVR0fBDUwMzAxoC+gLYYraHR0cDovL2NybC5jaGFtYmVyc2lnb=
i5v=0A=
cmcvY2hhbWJlcnNyb290LmNybDAdBgNVHQ4EFgQU45T1sU3p26EpW1eLTXYGduHRooowDgYDV=
R0P=0A=
AQH/BAQDAgEGMBEGCWCGSAGG+EIBAQQEAwIABzAnBgNVHREEIDAegRxjaGFtYmVyc3Jvb3RAY=
2hh=0A=
bWJlcnNpZ24ub3JnMCcGA1UdEgQgMB6BHGNoYW1iZXJzcm9vdEBjaGFtYmVyc2lnbi5vcmcwW=
AYD=0A=
VR0gBFEwTzBNBgsrBgEEAYGHLgoDATA+MDwGCCsGAQUFBwIBFjBodHRwOi8vY3BzLmNoYW1iZ=
XJz=0A=
aWduLm9yZy9jcHMvY2hhbWJlcnNyb290Lmh0bWwwDQYJKoZIhvcNAQEFBQADggEBAAxBl8Iah=
sAi=0A=
fJ/7kPMa0QOx7xP5IV8EnNrJpY0nbJaHkb5BkAFyk+cefV/2icZdp0AJPaxJRUXcLo0waLIJu=
vvD=0A=
L8y6C98/d3tGfToSJI6WjzwFCm/SlCgdbQzALogi1djPHRPH8EjX1wWnz8dHnjs8NMiAT9QUu=
/wN=0A=
UPf6s+xCX6ndbcj0dc97wXImsQEcXCz9ek60AcUFV7nnPKoF2YjpB0ZBzu9Bga5Y34OirsrXd=
x/n=0A=
ADydb47kMgkdTXg0eDQ8lJsm7U9xxhl6vSAiSFr+S30Dt+dYvsYyTnQeaN2oaFuzPu5ifdmA6=
Ap1=0A=
erfutGWaIZDgqtCYvDi1czyL+Nw=3D=0A=
-----END CERTIFICATE-----=0A=
=0A=
Camerfirma Global Chambersign Root=0A=
=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=
=3D=3D=3D=3D=3D=3D=3D=3D=3D=0A=
-----BEGIN CERTIFICATE-----=0A=
MIIExTCCA62gAwIBAgIBADANBgkqhkiG9w0BAQUFADB9MQswCQYDVQQGEwJFVTEnMCUGA1UEC=
hMe=0A=
QUMgQ2FtZXJmaXJtYSBTQSBDSUYgQTgyNzQzMjg3MSMwIQYDVQQLExpodHRwOi8vd3d3LmNoY=
W1i=0A=
ZXJzaWduLm9yZzEgMB4GA1UEAxMXR2xvYmFsIENoYW1iZXJzaWduIFJvb3QwHhcNMDMwOTMwM=
TYx=0A=
NDE4WhcNMzcwOTMwMTYxNDE4WjB9MQswCQYDVQQGEwJFVTEnMCUGA1UEChMeQUMgQ2FtZXJma=
XJt=0A=
YSBTQSBDSUYgQTgyNzQzMjg3MSMwIQYDVQQLExpodHRwOi8vd3d3LmNoYW1iZXJzaWduLm9yZ=
zEg=0A=
MB4GA1UEAxMXR2xvYmFsIENoYW1iZXJzaWduIFJvb3QwggEgMA0GCSqGSIb3DQEBAQUAA4IBD=
QAw=0A=
ggEIAoIBAQCicKLQn0KuWxfH2H3PFIP8T8mhtxOviteePgQKkotgVvq0Mi+ITaFgCPS3CU6gS=
S9J=0A=
1tPfnZdan5QEcOw/Wdm3zGaLmFIoCQLfxS+EjXqXd7/sQJ0lcqu1PzKY+7e3/HKE5TWH+VX6o=
x8O=0A=
by4o3Wmg2UIQxvi1RMLQQ3/bvOSiPGpVeAp3qdjqGTK3L/5cPxvusZjsyq16aUXjlg9V9ubtd=
epl=0A=
6DJWk0aJqCWKZQbua795B9Dxt6/tLE2Su8CoX6dnfQTyFQhwrJLWfQTSM/tMtgsL+xrJxI0Dq=
X5c=0A=
8lCrEqWhz0hQpe/SyBoT+rB/sYIcd2oPX9wLlY/vQ37mRQklAgEDo4IBUDCCAUwwEgYDVR0TA=
QH/=0A=
BAgwBgEB/wIBDDA/BgNVHR8EODA2MDSgMqAwhi5odHRwOi8vY3JsLmNoYW1iZXJzaWduLm9yZ=
y9j=0A=
aGFtYmVyc2lnbnJvb3QuY3JsMB0GA1UdDgQWBBRDnDafsJ4wTcbOX60Qq+UDpfqpFDAOBgNVH=
Q8B=0A=
Af8EBAMCAQYwEQYJYIZIAYb4QgEBBAQDAgAHMCoGA1UdEQQjMCGBH2NoYW1iZXJzaWducm9vd=
EBj=0A=
aGFtYmVyc2lnbi5vcmcwKgYDVR0SBCMwIYEfY2hhbWJlcnNpZ25yb290QGNoYW1iZXJzaWduL=
m9y=0A=
ZzBbBgNVHSAEVDBSMFAGCysGAQQBgYcuCgEBMEEwPwYIKwYBBQUHAgEWM2h0dHA6Ly9jcHMuY=
2hh=0A=
bWJlcnNpZ24ub3JnL2Nwcy9jaGFtYmVyc2lnbnJvb3QuaHRtbDANBgkqhkiG9w0BAQUFAAOCA=
QEA=0A=
PDtwkfkEVCeR4e3t/mh/YV3lQWVPMvEYBZRqHN4fcNs+ezICNLUMbKGKfKX0j//U2K0X1S0E0=
T9Y=0A=
gOKBWYi+wONGkyT+kL0mojAt6JcmVzWJdJYY9hXiryQZVgICsroPFOrGimbBhkVVi76SvpykB=
MdJ=0A=
PJ7oKXqJ1/6v/2j1pReQvayZzKWGVwlnRtvWFsJG8eSpUPWP0ZIV018+xgBJOm5YstHRJw0ly=
DL4=0A=
IBHNfTIzSJRUTN3cecQwn+uOuFW114hcxWokPbLTBQNRxgfvzBRydD1ucs4YKIxKoHflCStFR=
Ees=0A=
t2d/AYoFWpO+ocH/+OcOZ6RHSXZddZAa9SaP8A=3D=3D=0A=
-----END CERTIFICATE-----=0A=
=0A=
NetLock Notary (Class A) Root=0A=
=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=
=3D=3D=3D=3D=0A=
-----BEGIN CERTIFICATE-----=0A=
MIIGfTCCBWWgAwIBAgICAQMwDQYJKoZIhvcNAQEEBQAwga8xCzAJBgNVBAYTAkhVMRAwDgYDV=
QQI=0A=
EwdIdW5nYXJ5MREwDwYDVQQHEwhCdWRhcGVzdDEnMCUGA1UEChMeTmV0TG9jayBIYWxvemF0Y=
ml6=0A=
dG9uc2FnaSBLZnQuMRowGAYDVQQLExFUYW51c2l0dmFueWtpYWRvazE2MDQGA1UEAxMtTmV0T=
G9j=0A=
ayBLb3pqZWd5em9pIChDbGFzcyBBKSBUYW51c2l0dmFueWtpYWRvMB4XDTk5MDIyNDIzMTQ0N=
1oX=0A=
DTE5MDIxOTIzMTQ0N1owga8xCzAJBgNVBAYTAkhVMRAwDgYDVQQIEwdIdW5nYXJ5MREwDwYDV=
QQH=0A=
EwhCdWRhcGVzdDEnMCUGA1UEChMeTmV0TG9jayBIYWxvemF0Yml6dG9uc2FnaSBLZnQuMRowG=
AYD=0A=
VQQLExFUYW51c2l0dmFueWtpYWRvazE2MDQGA1UEAxMtTmV0TG9jayBLb3pqZWd5em9pIChDb=
GFz=0A=
cyBBKSBUYW51c2l0dmFueWtpYWRvMIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAv=
HSM=0A=
D7tM9DceqQWC2ObhbHDqeLVu0ThEDaiDzl3S1tWBxdRL51uUcCbbO51qTGL3cfNk1mE7Petzo=
zfZ=0A=
z+qMkjvN9wfcZnSX9EUi3fRc4L9t875lM+QVOr/bmJBVOMTtplVjC7B4BPTjbsE/jvxReB+Sn=
oPC=0A=
/tmwqcm8WgD/qaiYdPv2LD4VOQ22BFWoDpggQrOxJa1+mm9dU7GrDPzr4PN6s6iz/0b2Y6LYO=
ph7=0A=
tqyF/7AlT3Rj5xMHpQqPBffAZG9+pyeAlt7ULoZgx2srXnN7F+eRP2QM2EsiNCubMvJIH5+hC=
oR6=0A=
4sKtlz2O1cH5VqNQ6ca0+pii7pXmKgOM3wIDAQABo4ICnzCCApswDgYDVR0PAQH/BAQDAgAGM=
BIG=0A=
A1UdEwEB/wQIMAYBAf8CAQQwEQYJYIZIAYb4QgEBBAQDAgAHMIICYAYJYIZIAYb4QgENBIICU=
RaC=0A=
Ak1GSUdZRUxFTSEgRXplbiB0YW51c2l0dmFueSBhIE5ldExvY2sgS2Z0LiBBbHRhbGFub3MgU=
3pv=0A=
bGdhbHRhdGFzaSBGZWx0ZXRlbGVpYmVuIGxlaXJ0IGVsamFyYXNvayBhbGFwamFuIGtlc3p1b=
HQu=0A=
IEEgaGl0ZWxlc2l0ZXMgZm9seWFtYXRhdCBhIE5ldExvY2sgS2Z0LiB0ZXJtZWtmZWxlbG9zc=
2Vn=0A=
LWJpenRvc2l0YXNhIHZlZGkuIEEgZGlnaXRhbGlzIGFsYWlyYXMgZWxmb2dhZGFzYW5hayBmZ=
Wx0=0A=
ZXRlbGUgYXogZWxvaXJ0IGVsbGVub3J6ZXNpIGVsamFyYXMgbWVndGV0ZWxlLiBBeiBlbGphc=
mFz=0A=
IGxlaXJhc2EgbWVndGFsYWxoYXRvIGEgTmV0TG9jayBLZnQuIEludGVybmV0IGhvbmxhcGphb=
iBh=0A=
IGh0dHBzOi8vd3d3Lm5ldGxvY2submV0L2RvY3MgY2ltZW4gdmFneSBrZXJoZXRvIGF6IGVsb=
GVu=0A=
b3J6ZXNAbmV0bG9jay5uZXQgZS1tYWlsIGNpbWVuLiBJTVBPUlRBTlQhIFRoZSBpc3N1YW5jZ=
SBh=0A=
bmQgdGhlIHVzZSBvZiB0aGlzIGNlcnRpZmljYXRlIGlzIHN1YmplY3QgdG8gdGhlIE5ldExvY=
2sg=0A=
Q1BTIGF2YWlsYWJsZSBhdCBodHRwczovL3d3dy5uZXRsb2NrLm5ldC9kb2NzIG9yIGJ5IGUtb=
WFp=0A=
bCBhdCBjcHNAbmV0bG9jay5uZXQuMA0GCSqGSIb3DQEBBAUAA4IBAQBIJEb3ulZv+sgoA0BO5=
TE5=0A=
ayZrU3/b39/zcT0mwBQOxmd7I6gMc90Bu8bKbjc5VdXHjFYgDigKDtIqpLBJUsY4B/6+CgmM0=
ZjP=0A=
ytoUMaFP0jn8DxEsQ8Pdq5PHVT5HfBgaANzze9jyf1JsIPQLX2lS9O74silg6+NJMSEN1rUQQ=
eJB=0A=
CWziGppWS3cC9qCbmieH6FUpccKQn0V4GuEVZD3QDtigdp+uxdAu6tYPVuxkf1qbFFgBJ34TU=
Mdr=0A=
KuZoPL9coAob4Q566eKAw+np9v1sEZ7Q5SgnK1QyQhSCdeZK8CtmdWOMovsEPoMOmzbwGOQmI=
MOM=0A=
8CgHrTwXZoi1/baI=0A=
-----END CERTIFICATE-----=0A=
=0A=
XRamp Global CA Root=0A=
=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=0A=
-----BEGIN CERTIFICATE-----=0A=
MIIEMDCCAxigAwIBAgIQUJRs7Bjq1ZxN1ZfvdY+grTANBgkqhkiG9w0BAQUFADCBgjELMAkGA=
1UE=0A=
BhMCVVMxHjAcBgNVBAsTFXd3dy54cmFtcHNlY3VyaXR5LmNvbTEkMCIGA1UEChMbWFJhbXAgU=
2Vj=0A=
dXJpdHkgU2VydmljZXMgSW5jMS0wKwYDVQQDEyRYUmFtcCBHbG9iYWwgQ2VydGlmaWNhdGlvb=
iBB=0A=
dXRob3JpdHkwHhcNMDQxMTAxMTcxNDA0WhcNMzUwMTAxMDUzNzE5WjCBgjELMAkGA1UEBhMCV=
VMx=0A=
HjAcBgNVBAsTFXd3dy54cmFtcHNlY3VyaXR5LmNvbTEkMCIGA1UEChMbWFJhbXAgU2VjdXJpd=
Hkg=0A=
U2VydmljZXMgSW5jMS0wKwYDVQQDEyRYUmFtcCBHbG9iYWwgQ2VydGlmaWNhdGlvbiBBdXRob=
3Jp=0A=
dHkwggEiMA0GCSqGSIb3DQEBAQUAA4IBDwAwggEKAoIBAQCYJB69FbS638eMpSe2OAtp87ZOq=
Cwu=0A=
IR1cRN8hXX4jdP5efrRKt6atH67gBhbim1vZZ3RrXYCPKZ2GG9mcDZhtdhAoWORlsH9KmHmf4=
MMx=0A=
foArtYzAQDsRhtDLooY2YKTVMIJt2W7QDxIEM5dfT2Fa8OT5kavnHTu86M/0ay00fOJIYRyO8=
2FE=0A=
zG+gSqmUsE3a56k0enI4qEHMPJQRfevIpoy3hsvKMzvZPTeL+3o+hiznc9cKV6xkmxnr9A8EC=
Iqs=0A=
AxcZZPRaJSKNNCyy9mgdEm3Tih4U2sSPpuIjhdV6Db1q4Ons7Be7QhtnqiXtRYMh/MHJfNViP=
vry=0A=
xS3T/dRlAgMBAAGjgZ8wgZwwEwYJKwYBBAGCNxQCBAYeBABDAEEwCwYDVR0PBAQDAgGGMA8GA=
1Ud=0A=
EwEB/wQFMAMBAf8wHQYDVR0OBBYEFMZPoj0GY4QJnM5i5ASsjVy16bYbMDYGA1UdHwQvMC0wK=
6Ap=0A=
oCeGJWh0dHA6Ly9jcmwueHJhbXBzZWN1cml0eS5jb20vWEdDQS5jcmwwEAYJKwYBBAGCNxUBB=
AMC=0A=
AQEwDQYJKoZIhvcNAQEFBQADggEBAJEVOQMBG2f7Shz5CmBbodpNl2L5JFMn14JkTpAuw0kbK=
5rc=0A=
/Kh4ZzXxHfARvbdI4xD2Dd8/0sm2qlWkSLoC295ZLhVbO50WfUfXN+pfTXYSNrsf16GBBEYgo=
yxt=0A=
qZ4Bfj8pzgCT3/3JknOJiWSe5yvkHJEs0rnOfc5vMZnT5r7SHpDwCRR5XCOrTdLaIR9NmXmd4=
c8n=0A=
nxCbHIgNsIpkQTG4DmyQJKSbXHGPurt+HBvbaoAPIbzp26a3QPSyi6mx5O+aGtA9aZnuqCij4=
Tyz=0A=
8LIRnM98QObd50N9otg6tamN8jSZxNQQ4Qb9CYQQO+7ETPTsJ3xCwnR8gooJybQDJbw=3D=0A=
-----END CERTIFICATE-----=0A=
=0A=
Go Daddy Class 2 CA=0A=
=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=0A=
-----BEGIN CERTIFICATE-----=0A=
MIIEADCCAuigAwIBAgIBADANBgkqhkiG9w0BAQUFADBjMQswCQYDVQQGEwJVUzEhMB8GA1UEC=
hMY=0A=
VGhlIEdvIERhZGR5IEdyb3VwLCBJbmMuMTEwLwYDVQQLEyhHbyBEYWRkeSBDbGFzcyAyIENlc=
nRp=0A=
ZmljYXRpb24gQXV0aG9yaXR5MB4XDTA0MDYyOTE3MDYyMFoXDTM0MDYyOTE3MDYyMFowYzELM=
AkG=0A=
A1UEBhMCVVMxITAfBgNVBAoTGFRoZSBHbyBEYWRkeSBHcm91cCwgSW5jLjExMC8GA1UECxMoR=
28g=0A=
RGFkZHkgQ2xhc3MgMiBDZXJ0aWZpY2F0aW9uIEF1dGhvcml0eTCCASAwDQYJKoZIhvcNAQEBB=
QAD=0A=
ggENADCCAQgCggEBAN6d1+pXGEmhW+vXX0iG6r7d/+TvZxz0ZWizV3GgXne77ZtJ6XCAPVYYY=
whv=0A=
2vLM0D9/AlQiVBDYsoHUwHU9S3/Hd8M+eKsaA7Ugay9qK7HFiH7Eux6wwdhFJ2+qN1j3hybX2=
C32=0A=
qRe3H3I2TqYXP2WYktsqbl2i/ojgC95/5Y0V4evLOtXiEqITLdiOr18SPaAIBQi2XKVlOARFm=
R6j=0A=
YGB0xUGlcmIbYsUfb18aQr4CUWWoriMYavx4A6lNf4DD+qta/KFApMoZFv6yyO9ecw3ud72a9=
nmY=0A=
vLEHZ6IVDd2gWMZEewo+YihfukEHU1jPEX44dMX4/7VpkI+EdOqXG68CAQOjgcAwgb0wHQYDV=
R0O=0A=
BBYEFNLEsNKR1EwRcbNhyz2h/t2oatTjMIGNBgNVHSMEgYUwgYKAFNLEsNKR1EwRcbNhyz2h/=
t2o=0A=
atTjoWekZTBjMQswCQYDVQQGEwJVUzEhMB8GA1UEChMYVGhlIEdvIERhZGR5IEdyb3VwLCBJb=
mMu=0A=
MTEwLwYDVQQLEyhHbyBEYWRkeSBDbGFzcyAyIENlcnRpZmljYXRpb24gQXV0aG9yaXR5ggEAM=
AwG=0A=
A1UdEwQFMAMBAf8wDQYJKoZIhvcNAQEFBQADggEBADJL87LKPpH8EsahB4yOd6AzBhRckB4Y9=
wim=0A=
PQoZ+YeAEW5p5JYXMP80kWNyOO7MHAGjHZQopDH2esRU1/blMVgDoszOYtuURXO1v0XJJLXVg=
gKt=0A=
I3lpjbi2Tc7PTMozI+gciKqdi0FuFskg5YmezTvacPd+mSYgFFQlq25zheabIZ0KbIIOqPjCD=
PoQ=0A=
HmyW74cNxA9hi63ugyuV+I6ShHI56yDqg+2DzZduCLzrTia2cyvk0/ZM/iZx4mERdEr/VxqHD=
3VI=0A=
Ls9RaRegAhJhldXRQLIQTO7ErBBDpqWeCtWVYpoNz4iCxTIM5CufReYNnyicsbkqWletNw+vH=
X/b=0A=
vZ8=3D=0A=
-----END CERTIFICATE-----=0A=
=0A=
Starfield Class 2 CA=0A=
=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=0A=
-----BEGIN CERTIFICATE-----=0A=
MIIEDzCCAvegAwIBAgIBADANBgkqhkiG9w0BAQUFADBoMQswCQYDVQQGEwJVUzElMCMGA1UEC=
hMc=0A=
U3RhcmZpZWxkIFRlY2hub2xvZ2llcywgSW5jLjEyMDAGA1UECxMpU3RhcmZpZWxkIENsYXNzI=
DIg=0A=
Q2VydGlmaWNhdGlvbiBBdXRob3JpdHkwHhcNMDQwNjI5MTczOTE2WhcNMzQwNjI5MTczOTE2W=
jBo=0A=
MQswCQYDVQQGEwJVUzElMCMGA1UEChMcU3RhcmZpZWxkIFRlY2hub2xvZ2llcywgSW5jLjEyM=
DAG=0A=
A1UECxMpU3RhcmZpZWxkIENsYXNzIDIgQ2VydGlmaWNhdGlvbiBBdXRob3JpdHkwggEgMA0GC=
SqG=0A=
SIb3DQEBAQUAA4IBDQAwggEIAoIBAQC3Msj+6XGmBIWtDBFk385N78gDGIc/oav7PKaf8MOh2=
tTY=0A=
bitTkPskpD6E8J7oX+zlJ0T1KKY/e97gKvDIr1MvnsoFAZMej2YcOadN+lq2cwQlZut3f+dZx=
kqZ=0A=
JRRU6ybH838Z1TBwj6+wRir/resp7defqgSHo9T5iaU0X9tDkYI22WY8sbi5gv2cOj4QyDvvB=
mVm=0A=
epsZGD3/cVE8MC5fvj13c7JdBmzDI1aaK4UmkhynArPkPw2vCHmCuDY96pzTNbO8acr1zJ3o/=
WSN=0A=
F4Azbl5KXZnJHoe0nRrA1W4TNSNe35tfPe/W93bC6j67eA0cQmdrBNj41tpvi/JEoAGrAgEDo=
4HF=0A=
MIHCMB0GA1UdDgQWBBS/X7fRzt0fhvRbVazc1xDCDqmI5zCBkgYDVR0jBIGKMIGHgBS/X7fRz=
t0f=0A=
hvRbVazc1xDCDqmI56FspGowaDELMAkGA1UEBhMCVVMxJTAjBgNVBAoTHFN0YXJmaWVsZCBUZ=
WNo=0A=
bm9sb2dpZXMsIEluYy4xMjAwBgNVBAsTKVN0YXJmaWVsZCBDbGFzcyAyIENlcnRpZmljYXRpb=
24g=0A=
QXV0aG9yaXR5ggEAMAwGA1UdEwQFMAMBAf8wDQYJKoZIhvcNAQEFBQADggEBAAWdP4id0ckaV=
aGs=0A=
afPzWdqbAYcaT1epoXkJKtv3L7IezMdeatiDh6GX70k1PncGQVhiv45YuApnP+yz3SFmH8lU+=
nLM=0A=
PUxA2IGvd56Deruix/U0F47ZEUD0/CwqTRV/p2JdLiXTAAsgGh1o+Re49L2L7ShZ3U0WixeDy=
LJl=0A=
xy16paq8U4Zt3VekyvggQQto8PT7dL5WXXp59fkdheMtlb71cZBDzI0fmgAKhynpVSJYACPq4=
xJD=0A=
KVtHCN2MQWplBqjlIapBtJUhlbl90TSrE9atvNziPTnNvT51cKEYWQPJIrSPnNVeKtelttQKb=
fi3=0A=
QBFGmh95DmK/D5fs4C8fF5Q=3D=0A=
-----END CERTIFICATE-----=0A=
=0A=
StartCom Certification Authority=0A=
=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=
=3D=3D=3D=3D=3D=3D=3D=0A=
-----BEGIN CERTIFICATE-----=0A=
MIIHyTCCBbGgAwIBAgIBATANBgkqhkiG9w0BAQUFADB9MQswCQYDVQQGEwJJTDEWMBQGA1UEC=
hMN=0A=
U3RhcnRDb20gTHRkLjErMCkGA1UECxMiU2VjdXJlIERpZ2l0YWwgQ2VydGlmaWNhdGUgU2lnb=
mlu=0A=
ZzEpMCcGA1UEAxMgU3RhcnRDb20gQ2VydGlmaWNhdGlvbiBBdXRob3JpdHkwHhcNMDYwOTE3M=
Tk0=0A=
NjM2WhcNMzYwOTE3MTk0NjM2WjB9MQswCQYDVQQGEwJJTDEWMBQGA1UEChMNU3RhcnRDb20gT=
HRk=0A=
LjErMCkGA1UECxMiU2VjdXJlIERpZ2l0YWwgQ2VydGlmaWNhdGUgU2lnbmluZzEpMCcGA1UEA=
xMg=0A=
U3RhcnRDb20gQ2VydGlmaWNhdGlvbiBBdXRob3JpdHkwggIiMA0GCSqGSIb3DQEBAQUAA4ICD=
wAw=0A=
ggIKAoICAQDBiNsJvGxGfHiflXu1M5DycmLWwTYgIiRezul38kMKogZkpMyONvg45iPwbm2xP=
N1y=0A=
o4UcodM9tDMr0y+v/uqwQVlntsQGfQqedIXWeUyAN3rfOQVSWff0G0ZDpNKFhdLDcfN1YjS6L=
Ip/=0A=
Ho/u7TTQEceWzVI9ujPW3U3eCztKS5/CJi/6tRYccjV3yjxd5srhJosaNnZcAdt0FCX+7bWgi=
A/d=0A=
eMotHweXMAEtcnn6RtYTKqi5pquDSR3l8u/d5AGOGAqPY1MWhWKpDhk6zLVmpsJrdAfkK+F2P=
rRt=0A=
2PZE4XNiHzvEvqBTViVsUQn3qqvKv3b9bZvzndu/PWa8DFaqr5hIlTpL36dYUNk4dalb6kMMA=
v+Z=0A=
6+hsTXBbKWWc3apdzK8BMewM69KN6Oqce+Zu9ydmDBpI125C4z/eIT574Q1w+2OqqGwaVLRcJ=
XrJ=0A=
osmLFqa7LH4XXgVNWG4SHQHuEhANxjJ/GP/89PrNbpHoNkm+Gkhpi8KWTRoSsmkXwQqQ1vp5I=
ki/=0A=
untp+HDH+no32NgN0nZPV/+Qt+OR0t3vwmC3Zzrd/qqc8NSLf3Iizsafl7b4r4qgEKjZ+xjGt=
rVc=0A=
UjyJthkqcwEKDwOzEmDyei+B26Nu/yYwl/WL3YlXtq09s68rxbd2AvCl1iuahhQqcvbjM4xdC=
UsT=0A=
37uMdBNSSwIDAQABo4ICUjCCAk4wDAYDVR0TBAUwAwEB/zALBgNVHQ8EBAMCAa4wHQYDVR0OB=
BYE=0A=
FE4L7xqkQFulF2mHMMo0aEPQQa7yMGQGA1UdHwRdMFswLKAqoCiGJmh0dHA6Ly9jZXJ0LnN0Y=
XJ0=0A=
Y29tLm9yZy9zZnNjYS1jcmwuY3JsMCugKaAnhiVodHRwOi8vY3JsLnN0YXJ0Y29tLm9yZy9zZ=
nNj=0A=
YS1jcmwuY3JsMIIBXQYDVR0gBIIBVDCCAVAwggFMBgsrBgEEAYG1NwEBATCCATswLwYIKwYBB=
QUH=0A=
AgEWI2h0dHA6Ly9jZXJ0LnN0YXJ0Y29tLm9yZy9wb2xpY3kucGRmMDUGCCsGAQUFBwIBFilod=
HRw=0A=
Oi8vY2VydC5zdGFydGNvbS5vcmcvaW50ZXJtZWRpYXRlLnBkZjCB0AYIKwYBBQUHAgIwgcMwJ=
xYg=0A=
U3RhcnQgQ29tbWVyY2lhbCAoU3RhcnRDb20pIEx0ZC4wAwIBARqBl0xpbWl0ZWQgTGlhYmlsa=
XR5=0A=
LCByZWFkIHRoZSBzZWN0aW9uICpMZWdhbCBMaW1pdGF0aW9ucyogb2YgdGhlIFN0YXJ0Q29tI=
ENl=0A=
cnRpZmljYXRpb24gQXV0aG9yaXR5IFBvbGljeSBhdmFpbGFibGUgYXQgaHR0cDovL2NlcnQuc=
3Rh=0A=
cnRjb20ub3JnL3BvbGljeS5wZGYwEQYJYIZIAYb4QgEBBAQDAgAHMDgGCWCGSAGG+EIBDQQrF=
ilT=0A=
dGFydENvbSBGcmVlIFNTTCBDZXJ0aWZpY2F0aW9uIEF1dGhvcml0eTANBgkqhkiG9w0BAQUFA=
AOC=0A=
AgEAFmyZ9GYMNPXQhV59CuzaEE44HF7fpiUFS5Eyweg78T3dRAlbB0mKKctmArexmvclmAk8j=
hvh=0A=
3TaHK0u7aNM5Zj2gJsfyOZEdUauCe37Vzlrk4gNXcGmXCPleWKYK34wGmkUWFjgKXlf2Ysd6A=
gXm=0A=
vB618p70qSmD+LIU424oh0TDkBreOKk8rENNZEXO3SipXPJzewT4F+irsfMuXGRuczE6Eri8s=
xHk=0A=
fY+BUZo7jYn0TZNmezwD7dOaHZrzZVD1oNB1ny+v8OqCQ5j4aZyJecRDjkZy42Q2Eq/3JR44i=
ZB3=0A=
fsNrarnDy0RLrHiQi+fHLB5LEUTINFInzQpdn4XBidUaePKVEFMy3YCEZnXZtWgo+2EuvoSoO=
MCZ=0A=
EoalHmdkrQYuL6lwhceWD3yJZfWOQ1QOq92lgDmUYMA0yZZwLKMS9R9Ie70cfmu3nZD0Ijuu+=
Pwq=0A=
yvqCUqDvr0tVk+vBtfAii6w0TiYiBKGHLHVKt+V9E9e4DGTANtLJL4YSjCMJwRuCO3NJo2pXh=
5Tl=0A=
1njFmUNj403gdy3hZZlyaQQaRwnmDwFWJPsfvw55qVguucQJAX6Vum0ABj6y6koQOdjQK/W/7=
HW/=0A=
lwLFCRsI3FU34oH7N4RDYiDK51ZLZer+bMEkkyShNOsF/5oirpt9P/FlUQqmMGqz9IgcgA38c=
oro=0A=
g14=3D=0A=
-----END CERTIFICATE-----=0A=
=0A=
Taiwan GRCA=0A=
=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=0A=
-----BEGIN CERTIFICATE-----=0A=
MIIFcjCCA1qgAwIBAgIQH51ZWtcvwgZEpYAIaeNe9jANBgkqhkiG9w0BAQUFADA/MQswCQYDV=
QQG=0A=
EwJUVzEwMC4GA1UECgwnR292ZXJubWVudCBSb290IENlcnRpZmljYXRpb24gQXV0aG9yaXR5M=
B4X=0A=
DTAyMTIwNTEzMjMzM1oXDTMyMTIwNTEzMjMzM1owPzELMAkGA1UEBhMCVFcxMDAuBgNVBAoMJ=
0dv=0A=
dmVybm1lbnQgUm9vdCBDZXJ0aWZpY2F0aW9uIEF1dGhvcml0eTCCAiIwDQYJKoZIhvcNAQEBB=
QAD=0A=
ggIPADCCAgoCggIBAJoluOzMonWoe/fOW1mKydGGEghU7Jzy50b2iPN86aXfTEc2pBsBHH8eV=
4qN=0A=
w8XRIePaJD9IK/ufLqGU5ywck9G/GwGHU5nOp/UKIXZ3/6m3xnOUT0b3EEk3+qhZSV1qgQdW8=
or5=0A=
BtD3cCJNtLdBuTK4sfCxw5w/cP1T3YGq2GN49thTbqGsaoQkclSGxtKyyhwOeYHWtXBiCAEuT=
k8O=0A=
1RGvqa/lmr/czIdtJuTJV6L7lvnM4T9TjGxMfptTCAtsF/tnyMKtsc2AtJfcdgEWFelq16The=
EfO=0A=
htX7MfP6Mb40qij7cEwdScevLJ1tZqa2jWR+tSBqnTuBto9AAGdLiYa4zGX+FVPpBMHWXx1E1=
wov=0A=
J5pGfaENda1UhhXcSTvxls4Pm6Dso3pdvtUqdULle96ltqqvKKyskKw4t9VoNSZ63Pc78/1Fm=
9G7=0A=
Q3hub/FCVGqY8A2tl+lSXunVanLeavcbYBT0peS2cWeqH+riTcFCQP5nRhc4L0c/cZyu5SHKY=
S1t=0A=
B6iEfC3uUSXxY5Ce/eFXiGvviiNtsea9P63RPZYLhY3Naye7twWb7LuRqQoHEgKXTiCQ8P8NH=
uJB=0A=
O9NAOueNXdpm5AKwB1KYXA6OM5zCppX7VRluTI6uSw+9wThNXo+EHWbNxWCWtFJaBYmOlXqYw=
ZE8=0A=
lSOyDvR5tMl8wUohAgMBAAGjajBoMB0GA1UdDgQWBBTMzO/MKWCkO7GStjz6MmKPrCUVOzAMB=
gNV=0A=
HRMEBTADAQH/MDkGBGcqBwAEMTAvMC0CAQAwCQYFKw4DAhoFADAHBgVnKgMAAAQUA5vwIhP/l=
Sg2=0A=
09yewDL7MTqKUWUwDQYJKoZIhvcNAQEFBQADggIBAECASvomyc5eMN1PhnR2WPWus4MzeKR6d=
BcZ=0A=
TulStbngCnRiqmjKeKBMmo4sIy7VahIkv9Ro04rQ2JyftB8M3jh+Vzj8jeJPXgyfqzvS/3WXy=
6Tj=0A=
Zwj/5cAWtUgBfen5Cv8b5Wppv3ghqMKnI6mGq3ZW6A4M9hPdKmaKZEk9GhiHkASfQlK3T8v+R=
0F2=0A=
Ne//AHY2RTKbxkaFXeIksB7jSJaYV0eUVXoPQbFEJPPB/hprv4j9wabak2BegUqZIJxIZhm1A=
HlU=0A=
D7gsL0u8qV1bYH+Mh6XgUmMqvtg7hUAV/h62ZT/FS9p+tXo1KaMuephgIqP0fSdOLeq0dDzpD=
6Qz=0A=
DxARvBMB1uUO07+1EqLhRSPAzAhuYbeJq4PjJB7mXQfnHyA+z2fI56wwbSdLaG5LKlwCCDTb+=
Hbk=0A=
Z6MmnD+iMsJKxYEYMRBWqoTvLQr/uB930r+lWKBi5NdLkXWNiYCYfm3LU05er/ayl4WXudpVB=
rkk=0A=
7tfGOB5jGxI7leFYrPLfhNVfmS8NVVvmONsuP3LpSIXLuykTjx44VbnzssQwmSNOXfJIoRIM3=
BKQ=0A=
CZBUkQM8R+XVyWXgt0t97EfTsws+rZ7QdAAO671RrcDeLMDDav7v3Aun+kbfYNucpllQdSNpc=
5Oy=0A=
+fwC00fmcc4QAu4njIT/rEUNE1yDMuAlpYYsfPQS=0A=
-----END CERTIFICATE-----=0A=
=0A=
Swisscom Root CA 1=0A=
=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=0A=
-----BEGIN CERTIFICATE-----=0A=
MIIF2TCCA8GgAwIBAgIQXAuFXAvnWUHfV8w/f52oNjANBgkqhkiG9w0BAQUFADBkMQswCQYDV=
QQG=0A=
EwJjaDERMA8GA1UEChMIU3dpc3Njb20xJTAjBgNVBAsTHERpZ2l0YWwgQ2VydGlmaWNhdGUgU=
2Vy=0A=
dmljZXMxGzAZBgNVBAMTElN3aXNzY29tIFJvb3QgQ0EgMTAeFw0wNTA4MTgxMjA2MjBaFw0yN=
TA4=0A=
MTgyMjA2MjBaMGQxCzAJBgNVBAYTAmNoMREwDwYDVQQKEwhTd2lzc2NvbTElMCMGA1UECxMcR=
Gln=0A=
aXRhbCBDZXJ0aWZpY2F0ZSBTZXJ2aWNlczEbMBkGA1UEAxMSU3dpc3Njb20gUm9vdCBDQSAxM=
IIC=0A=
IjANBgkqhkiG9w0BAQEFAAOCAg8AMIICCgKCAgEA0LmwqAzZuz8h+BvVM5OAFmUgdbI9m2BtR=
siM=0A=
MW8Xw/qabFbtPMWRV8PNq5ZJkCoZSx6jbVfd8StiKHVFXqrWW/oLJdihFvkcxC7mlSpnzNApb=
jyF=0A=
NDhhSbEAn9Y6cV9Nbc5fuankiX9qUvrKm/LcqfmdmUc/TilftKaNXXsLmREDA/7n29uj/x2lz=
ZAe=0A=
AR81sH8A25Bvxn570e56eqeqDFdvpG3FEzuwpdntMhy0XmeLVNxzh+XTF3xmUHJd1BpYwdnP2=
IkC=0A=
b6dJtDZd0KTeByy2dbcokdaXvij1mB7qWybJvbCXc9qukSbraMH5ORXWZ0sKbU/Lz7DkQnGMU=
3nn=0A=
7uHbHaBuHYwadzVcFh4rUx80i9Fs/PJnB3r1re3WmquhsUvhzDdf/X/NTa64H5xD+SpYVUNFv=
JbN=0A=
cA78yeNmuk6NO4HLFWR7uZToXTNShXEuT46iBhFRyePLoW4xCGQMwtI89Tbo19AOeCMgkckkK=
mUp=0A=
WyL3Ic6DXqTz3kvTaI9GdVyDCW4pa8RwjPWd1yAv/0bSKzjCL3UcPX7ape8eYIVpQtPM+GP+H=
kM5=0A=
haa2Y0EQs3MevNP6yn0WR+Kn1dCjigoIlmJWbjTb2QK5MHXjBNLnj8KwEUAKrNVxAmKLMb7dx=
iNY=0A=
MUJDLXT5xp6mig/p/r+D5kNXJLrvRjSq1xIBOO0CAwEAAaOBhjCBgzAOBgNVHQ8BAf8EBAMCA=
YYw=0A=
HQYDVR0hBBYwFDASBgdghXQBUwABBgdghXQBUwABMBIGA1UdEwEB/wQIMAYBAf8CAQcwHwYDV=
R0j=0A=
BBgwFoAUAyUv3m+CATpcLNwroWm1Z9SM0/0wHQYDVR0OBBYEFAMlL95vggE6XCzcK6FptWfUj=
NP9=0A=
MA0GCSqGSIb3DQEBBQUAA4ICAQA1EMvspgQNDQ/NwNurqPKIlwzfky9NfEBWMXrrpA9gzXrzv=
sMn=0A=
jgM+pN0S734edAY8PzHyHHuRMSG08NBsl9Tpl7IkVh5WwzW9iAUPWxAaZOHHgjD5Mq2eUCzne=
AXQ=0A=
MbFamIp1TpBcahQq4FJHgmDmHtqBsfsUC1rxn9KVuj7QG9YVHaO+htXbD8BJZLsuUBlL0iT43=
R4H=0A=
VtA4oJVwIHaM190e3p9xxCPvgxNcoyQVTSlAPGrEqdi3pkSlDfTgnXceQHAm/NrZNuR55LU/v=
Jtl=0A=
vrsRls/bxig5OgjOR1tTWsWZ/l2p3e9M1MalrQLmjAcSHm8D0W+go/MpvRLHUKKwf4ipmXeas=
cCl=0A=
OS5cfGniLLDqN2qk4Vrh9VDlg++luyqI54zb/W1elxmofmZ1a3Hqv7HHb6D0jqTsNFFbjCYDc=
KF3=0A=
1QESVwA12yPeDooomf2xEG9L/zgtYE4snOtnta1J7ksfrK/7DZBaZmBwXarNeNQk7shBoJMBk=
pxq=0A=
nvy5JMWzFYJ+vq6VK+uxwNrjAWALXmmshFZhvnEX/h0TD/7Gh0Xp/jKgGg0TpJRVcaUWi7rKi=
bCy=0A=
x/yP2FS1k2Kdzs9Z+z0YzirLNRWCXf9UIltxUvu3yf5gmwBBZPCqKuy2QkPOiWaByIufOVQDJ=
dMW=0A=
NY6E0F/6MBr1mmz0DlP5OlvRHA=3D=3D=0A=
-----END CERTIFICATE-----=0A=
=0A=
DigiCert Assured ID Root CA=0A=
=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=
=3D=3D=0A=
-----BEGIN CERTIFICATE-----=0A=
MIIDtzCCAp+gAwIBAgIQDOfg5RfYRv6P5WD8G/AwOTANBgkqhkiG9w0BAQUFADBlMQswCQYDV=
QQG=0A=
EwJVUzEVMBMGA1UEChMMRGlnaUNlcnQgSW5jMRkwFwYDVQQLExB3d3cuZGlnaWNlcnQuY29tM=
SQw=0A=
IgYDVQQDExtEaWdpQ2VydCBBc3N1cmVkIElEIFJvb3QgQ0EwHhcNMDYxMTEwMDAwMDAwWhcNM=
zEx=0A=
MTEwMDAwMDAwWjBlMQswCQYDVQQGEwJVUzEVMBMGA1UEChMMRGlnaUNlcnQgSW5jMRkwFwYDV=
QQL=0A=
ExB3d3cuZGlnaWNlcnQuY29tMSQwIgYDVQQDExtEaWdpQ2VydCBBc3N1cmVkIElEIFJvb3QgQ=
0Ew=0A=
ggEiMA0GCSqGSIb3DQEBAQUAA4IBDwAwggEKAoIBAQCtDhXO5EOAXLGH87dg+XESpa7cJpSIq=
vTO=0A=
9SA5KFhgDPiA2qkVlTJhPLWxKISKityfCgyDF3qPkKyK53lTXDGEKvYPmDI2dsze3Tyoou9q+=
yHy=0A=
UmHfnyDXH+Kx2f4YZNISW1/5WBg1vEfNoTb5a3/UsDg+wRvDjDPZ2C8Y/igPs6eD1sNuRMBhN=
ZYW=0A=
/lmci3Zt1/GiSw0r/wty2p5g0I6QNcZ4VYcgoc/lbQrISXwxmDNsIumH0DJaoroTghHtORedm=
Tpy=0A=
oeb6pNnVFzF1roV9Iq4/AUaG9ih5yLHa5FcXxH4cDrC0kqZWs72yl+2qp/C3xag/lRbQ/6GW6=
whf=0A=
GHdPAgMBAAGjYzBhMA4GA1UdDwEB/wQEAwIBhjAPBgNVHRMBAf8EBTADAQH/MB0GA1UdDgQWB=
BRF=0A=
66Kv9JLLgjEtUYunpyGd823IDzAfBgNVHSMEGDAWgBRF66Kv9JLLgjEtUYunpyGd823IDzANB=
gkq=0A=
hkiG9w0BAQUFAAOCAQEAog683+Lt8ONyc3pklL/3cmbYMuRCdWKuh+vy1dneVrOfzM4UKLkNl=
2Bc=0A=
EkxY5NM9g0lFWJc1aRqoR+pWxnmrEthngYTffwk8lOa4JiwgvT2zKIn3X/8i4peEH+ll74fg3=
8Fn=0A=
SbNd67IJKusm7Xi+fT8r87cmNW1fiQG2SVufAQWbqz0lwcy2f8Lxb4bG+mRo64EtlOtCt/qMH=
t1i=0A=
8b5QZ7dsvfPxH2sMNgcWfzd8qVttevESRmCD1ycEvkvOl77DZypoEd+A5wwzZr8TDRRu838fY=
xAe=0A=
+o0bJW1sj6W3YQGx0qMmoRBxna3iw/nDmVG3KwcIzi7mULKn+gpFL6Lw8g=3D=3D=0A=
-----END CERTIFICATE-----=0A=
=0A=
DigiCert Global Root CA=0A=
=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=0A=
-----BEGIN CERTIFICATE-----=0A=
MIIDrzCCApegAwIBAgIQCDvgVpBCRrGhdWrJWZHHSjANBgkqhkiG9w0BAQUFADBhMQswCQYDV=
QQG=0A=
EwJVUzEVMBMGA1UEChMMRGlnaUNlcnQgSW5jMRkwFwYDVQQLExB3d3cuZGlnaWNlcnQuY29tM=
SAw=0A=
HgYDVQQDExdEaWdpQ2VydCBHbG9iYWwgUm9vdCBDQTAeFw0wNjExMTAwMDAwMDBaFw0zMTExM=
TAw=0A=
MDAwMDBaMGExCzAJBgNVBAYTAlVTMRUwEwYDVQQKEwxEaWdpQ2VydCBJbmMxGTAXBgNVBAsTE=
Hd3=0A=
dy5kaWdpY2VydC5jb20xIDAeBgNVBAMTF0RpZ2lDZXJ0IEdsb2JhbCBSb290IENBMIIBIjANB=
gkq=0A=
hkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEA4jvhEXLeqKTTo1eqUKKPC3eQyaKl7hLOllsBCSDMA=
ZOn=0A=
TjC3U/dDxGkAV53ijSLdhwZAAIEJzs4bg7/fzTtxRuLWZscFs3YnFo97nh6Vfe63SKMI2tave=
gw5=0A=
BmV/Sl0fvBf4q77uKNd0f3p4mVmFaG5cIzJLv07A6Fpt43C/dxC//AH2hdmoRBBYMql1GNXRo=
r5H=0A=
4idq9Joz+EkIYIvUX7Q6hL+hqkpMfT7PT19sdl6gSzeRntwi5m3OFBqOasv+zbMUZBfHWymeM=
r/y=0A=
7vrTC0LUq7dBMtoM1O/4gdW7jVg/tRvoSSiicNoxBN33shbyTApOB6jtSj1etX+jkMOvJwIDA=
QAB=0A=
o2MwYTAOBgNVHQ8BAf8EBAMCAYYwDwYDVR0TAQH/BAUwAwEB/zAdBgNVHQ4EFgQUA95QNVbRT=
Ltm=0A=
8KPiGxvDl7I90VUwHwYDVR0jBBgwFoAUA95QNVbRTLtm8KPiGxvDl7I90VUwDQYJKoZIhvcNA=
QEF=0A=
BQADggEBAMucN6pIExIK+t1EnE9SsPTfrgT1eXkIoyQY/EsrhMAtudXH/vTBH1jLuG2cenTnm=
Cmr=0A=
EbXjcKChzUyImZOMkXDiqw8cvpOp/2PV5Adg06O/nVsJ8dWO41P0jmP6P6fbtGbfYmbW0W5Bj=
fIt=0A=
tep3Sp+dWOIrWcBAI+0tKIJFPnlUkiaY4IBIqDfv8NZ5YBberOgOzW6sRBc4L0na4UU+Krk2U=
886=0A=
UAb3LujEV0lsYSEY1QSteDwsOoBrp+uvFRTp2InBuThs4pFsiv9kuXclVzDAGySj4dzp30d8t=
bQk=0A=
CAUw7C29C79Fv1C5qfPrmAESrciIxpg0X40KPMbp1ZWVbd4=3D=0A=
-----END CERTIFICATE-----=0A=
=0A=
DigiCert High Assurance EV Root CA=0A=
=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=
=3D=3D=3D=3D=3D=3D=3D=3D=3D=0A=
-----BEGIN CERTIFICATE-----=0A=
MIIDxTCCAq2gAwIBAgIQAqxcJmoLQJuPC3nyrkYldzANBgkqhkiG9w0BAQUFADBsMQswCQYDV=
QQG=0A=
EwJVUzEVMBMGA1UEChMMRGlnaUNlcnQgSW5jMRkwFwYDVQQLExB3d3cuZGlnaWNlcnQuY29tM=
Ssw=0A=
KQYDVQQDEyJEaWdpQ2VydCBIaWdoIEFzc3VyYW5jZSBFViBSb290IENBMB4XDTA2MTExMDAwM=
DAw=0A=
MFoXDTMxMTExMDAwMDAwMFowbDELMAkGA1UEBhMCVVMxFTATBgNVBAoTDERpZ2lDZXJ0IEluY=
zEZ=0A=
MBcGA1UECxMQd3d3LmRpZ2ljZXJ0LmNvbTErMCkGA1UEAxMiRGlnaUNlcnQgSGlnaCBBc3N1c=
mFu=0A=
Y2UgRVYgUm9vdCBDQTCCASIwDQYJKoZIhvcNAQEBBQADggEPADCCAQoCggEBAMbM5XPm+9S75=
S0t=0A=
Mqbf5YE/yc0lSbZxKsPVlDRnogocsF9ppkCxxLeyj9CYpKlBWTrT3JTWPNt0OKRKzE0lgvdKp=
VMS=0A=
OO7zSW1xkX5jtqumX8OkhPhPYlG++MXs2ziS4wblCJEMxChBVfvLWokVfnHoNb9Ncgk9vjo4U=
Ft3=0A=
MRuNs8ckRZqnrG0AFFoEt7oT61EKmEFBIk5lYYeBQVCmeVyJ3hlKV9Uu5l0cUyx+mM0aBhaka=
HPQ=0A=
NAQTXKFx01p8VdteZOE3hzBWBOURtCmAEvF5OYiiAhF8J2a3iLd48soKqDirCmTCv2ZdlYTBo=
SUe=0A=
h10aUAsgEsxBu24LUTi4S8sCAwEAAaNjMGEwDgYDVR0PAQH/BAQDAgGGMA8GA1UdEwEB/wQFM=
AMB=0A=
Af8wHQYDVR0OBBYEFLE+w2kD+L9HAdSYJhoIAu9jZCvDMB8GA1UdIwQYMBaAFLE+w2kD+L9HA=
dSY=0A=
JhoIAu9jZCvDMA0GCSqGSIb3DQEBBQUAA4IBAQAcGgaX3NecnzyIZgYIVyHbIUf4Kmeqvxgyd=
kAQ=0A=
V8GK83rZEWWONfqe/EW1ntlMMUu4kehDLI6zeM7b41N5cdblIZQB2lWHmiRk9opmzN6cN82oN=
LFp=0A=
myPInngiK3BD41VHMWEZ71jFhS9OMPagMRYjyOfiZRYzy78aG6A9+MpeizGLYAiJLQwGXFK3x=
PkK=0A=
mNEVX58Svnw2Yzi9RKR/5CYrCsSXaQ3pjOLAEFe4yHYSkVXySGnYvCoCWw9E1CAx2/S6cCZdk=
GCe=0A=
vEsXCS+0yx5DaMkHJ8HSXPfqIbloEpw8nL+e/IBcm2PN7EeqJSdnoDfzAIJ9VNep+OkuE6N36=
B9K=0A=
-----END CERTIFICATE-----=0A=
=0A=
Certplus Class 2 Primary CA=0A=
=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=
=3D=3D=0A=
-----BEGIN CERTIFICATE-----=0A=
MIIDkjCCAnqgAwIBAgIRAIW9S/PY2uNp9pTXX8OlRCMwDQYJKoZIhvcNAQEFBQAwPTELMAkGA=
1UE=0A=
BhMCRlIxETAPBgNVBAoTCENlcnRwbHVzMRswGQYDVQQDExJDbGFzcyAyIFByaW1hcnkgQ0EwH=
hcN=0A=
OTkwNzA3MTcwNTAwWhcNMTkwNzA2MjM1OTU5WjA9MQswCQYDVQQGEwJGUjERMA8GA1UEChMIQ=
2Vy=0A=
dHBsdXMxGzAZBgNVBAMTEkNsYXNzIDIgUHJpbWFyeSBDQTCCASIwDQYJKoZIhvcNAQEBBQADg=
gEP=0A=
ADCCAQoCggEBANxQltAS+DXSCHh6tlJw/W/uz7kRy1134ezpfgSN1sxvc0NXYKwzCkTsA18cg=
CSR=0A=
5aiRVhKC9+Ar9NuuYS6JEI1rbLqzAr3VNsVINyPi8Fo3UjMXEuLRYE2+L0ER4/YXJQyLkcAbm=
XuZ=0A=
Vg2v7tK8R1fjeUl7NIknJITesezpWE7+Tt9avkGtrAjFGA7v0lPubNCdEgETjdyAYveVqUSIS=
nFO=0A=
YFWe2yMZeVYHDD9jC1yw4r5+FfyUM1hBOHTE4Y+L3yasH7WLO7dDWWuwJKZtkIvEcupdM5i3y=
95e=0A=
e++U8Rs+yskhwcWYAqqi9lt3m/V+llU0HGdpwPFC40es/CgcZlUCAwEAAaOBjDCBiTAPBgNVH=
RME=0A=
CDAGAQH/AgEKMAsGA1UdDwQEAwIBBjAdBgNVHQ4EFgQU43Mt38sOKAze3bOkynm4jrvoMIkwE=
QYJ=0A=
YIZIAYb4QgEBBAQDAgEGMDcGA1UdHwQwMC4wLKAqoCiGJmh0dHA6Ly93d3cuY2VydHBsdXMuY=
29t=0A=
L0NSTC9jbGFzczIuY3JsMA0GCSqGSIb3DQEBBQUAA4IBAQCnVM+IRBnL39R/AN9WM2K191EBk=
OvD=0A=
P9GIROkkXe/nFL0gt5o8AP5tn9uQ3Nf0YtaLcF3n5QRIqWh8yfFC82x/xXp8HVGIutIKPidd3=
i1R=0A=
TtMTZGnkLuPT55sJmabglZvOGtd/vjzOUrMRFcEPF80Du5wlFbqidon8BvEY0JNLDnyCt6X09=
l/+=0A=
7UCmnYR0ObncHoUW2ikbhiMAybuJfm6AiB4vFLQDJKgybwOaRywwvlbGp0ICcBvqQNi6BQNwB=
6SW=0A=
//1IMwrh3KWBkJtN3X3n57LNXMhqlfil9o3EXXgIvnsG1knPGTZQIy4I5p4FTUcY1Rbpsda2E=
NW7=0A=
l7+ijrRU=0A=
-----END CERTIFICATE-----=0A=
=0A=
DST Root CA X3=0A=
=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=0A=
-----BEGIN CERTIFICATE-----=0A=
MIIDSjCCAjKgAwIBAgIQRK+wgNajJ7qJMDmGLvhAazANBgkqhkiG9w0BAQUFADA/MSQwIgYDV=
QQK=0A=
ExtEaWdpdGFsIFNpZ25hdHVyZSBUcnVzdCBDby4xFzAVBgNVBAMTDkRTVCBSb290IENBIFgzM=
B4X=0A=
DTAwMDkzMDIxMTIxOVoXDTIxMDkzMDE0MDExNVowPzEkMCIGA1UEChMbRGlnaXRhbCBTaWduY=
XR1=0A=
cmUgVHJ1c3QgQ28uMRcwFQYDVQQDEw5EU1QgUm9vdCBDQSBYMzCCASIwDQYJKoZIhvcNAQEBB=
QAD=0A=
ggEPADCCAQoCggEBAN+v6ZdQCINXtMxiZfaQguzH0yxrMMpb7NnDfcdAwRgUi+DoM3ZJKuM/I=
UmT=0A=
rE4Orz5Iy2Xu/NMhD2XSKtkyj4zl93ewEnu1lcCJo6m67XMuegwGMoOifooUMM0RoOEqOLl5C=
jH9=0A=
UL2AZd+3UWODyOKIYepLYYHsUmu5ouJLGiifSKOeDNoJjj4XLh7dIN9bxiqKqy69cK3FCxolk=
HRy=0A=
xXtqqzTWMIn/5WgTe1QLyNau7Fqckh49ZLOMxt+/yUFw7BZy1SbsOFU5Q9D8/RhcQPGX69Wam=
40d=0A=
utolucbY38EVAjqr2m7xPi71XAicPNaDaeQQmxkqtilX4+U9m5/wAl0CAwEAAaNCMEAwDwYDV=
R0T=0A=
AQH/BAUwAwEB/zAOBgNVHQ8BAf8EBAMCAQYwHQYDVR0OBBYEFMSnsaR7LHH62+FLkHX/xBVgh=
YkQ=0A=
MA0GCSqGSIb3DQEBBQUAA4IBAQCjGiybFwBcqR7uKGY3Or+Dxz9LwwmglSBd49lZRNI+DT69i=
kug=0A=
dB/OEIKcdBodfpga3csTS7MgROSR6cz8faXbauX+5v3gTt23ADq1cEmv8uXrAvHRAosZy5Q6X=
kjE=0A=
GB5YGV8eAlrwDPGxrancWYaLbumR9YbK+rlmM6pZW87ipxZzR8srzJmwN0jP41ZL9c8PDHIyh=
8bw=0A=
RLtTcm1D9SZImlJnt1ir/md2cXjbDaJWFBM5JDGFoqgCWjBH4d1QB7wCCZAA62RjYJsWvIjJE=
ubS=0A=
fZGL+T0yjWW06XyxV3bqxbYoOb8VZRzI9neWagqNdwvYkQsEjgfbKbYK7p2CNTUQ=0A=
-----END CERTIFICATE-----=0A=
=0A=
DST ACES CA X6=0A=
=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=0A=
-----BEGIN CERTIFICATE-----=0A=
MIIECTCCAvGgAwIBAgIQDV6ZCtadt3js2AdWO4YV2TANBgkqhkiG9w0BAQUFADBbMQswCQYDV=
QQG=0A=
EwJVUzEgMB4GA1UEChMXRGlnaXRhbCBTaWduYXR1cmUgVHJ1c3QxETAPBgNVBAsTCERTVCBBQ=
0VT=0A=
MRcwFQYDVQQDEw5EU1QgQUNFUyBDQSBYNjAeFw0wMzExMjAyMTE5NThaFw0xNzExMjAyMTE5N=
Tha=0A=
MFsxCzAJBgNVBAYTAlVTMSAwHgYDVQQKExdEaWdpdGFsIFNpZ25hdHVyZSBUcnVzdDERMA8GA=
1UE=0A=
CxMIRFNUIEFDRVMxFzAVBgNVBAMTDkRTVCBBQ0VTIENBIFg2MIIBIjANBgkqhkiG9w0BAQEFA=
AOC=0A=
AQ8AMIIBCgKCAQEAuT31LMmU3HWKlV1j6IR3dma5WZFcRt2SPp/5DgO0PWGSvSMmtWPuktKe1=
jzI=0A=
DZBfZIGxqAgNTNj50wUoUrQBJcWVHAx+PhCEdc/BGZFjz+iokYi5Q1K7gLFViYsx+tC3dr5BP=
TCa=0A=
pCIlF3PoHuLTrCq9Wzgh1SpL11V94zpVvddtawJXa+ZHfAjIgrrep4c9oW24MFbCswKBXy314=
pow=0A=
GCi4ZtPLAZZv6opFVdbgnf9nKxcCpk4aahELfrd755jWjHZvwTvbUJN+5dCOHze4vbrGn2zpf=
DPy=0A=
MjwmR/onJALJfh1biEITajV8fTXpLmaRcpPVMibEdPVTo7NdmvYJywIDAQABo4HIMIHFMA8GA=
1Ud=0A=
EwEB/wQFMAMBAf8wDgYDVR0PAQH/BAQDAgHGMB8GA1UdEQQYMBaBFHBraS1vcHNAdHJ1c3Rkc=
3Qu=0A=
Y29tMGIGA1UdIARbMFkwVwYKYIZIAWUDAgEBATBJMEcGCCsGAQUFBwIBFjtodHRwOi8vd3d3L=
nRy=0A=
dXN0ZHN0LmNvbS9jZXJ0aWZpY2F0ZXMvcG9saWN5L0FDRVMtaW5kZXguaHRtbDAdBgNVHQ4EF=
gQU=0A=
CXIGThhDD+XWzMNqizF7eI+og7gwDQYJKoZIhvcNAQEFBQADggEBAKPYjtay284F5zLNAdMEA=
+V2=0A=
5FYrnJmQ6AgwbN99Pe7lv7UkQIRJ4dEorsTCOlMwiPH1d25Ryvr/ma8kXxug/fKshMrfqfBfB=
C6t=0A=
Fr8hlxCBPeP/h40y3JTlR4peahPJlJU90u7INJXQgNStMgiAVDzgvVJT11J8smk/f3rPanTK+=
gQq=0A=
nExaBqXpIK1FZg9p8d2/6eMyi/rgwYZNcjwu2JN4Cir42NInPRmJX1p7ijvMDNpRrscL9yuwN=
wXs=0A=
vFcj4jjSm2jzVhKIT0J8uDHEtdvkyCE06UgRNe76x5JXxZ805Mf29w4LTJxoeHtxMcfrHuBnQ=
fO3=0A=
oKfN5XozNmr6mis=3D=0A=
-----END CERTIFICATE-----=0A=
=0A=
TURKTRUST Certificate Services Provider Root 2=0A=
=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=
=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=0A=
-----BEGIN CERTIFICATE-----=0A=
MIIEPDCCAySgAwIBAgIBATANBgkqhkiG9w0BAQUFADCBvjE/MD0GA1UEAww2VMOcUktUUlVTV=
CBF=0A=
bGVrdHJvbmlrIFNlcnRpZmlrYSBIaXptZXQgU2HEn2xhecSxY8Sxc8SxMQswCQYDVQQGEwJUU=
jEP=0A=
MA0GA1UEBwwGQW5rYXJhMV0wWwYDVQQKDFRUw5xSS1RSVVNUIEJpbGdpIMSwbGV0acWfaW0gd=
mUg=0A=
QmlsacWfaW0gR8O8dmVubGnEn2kgSGl6bWV0bGVyaSBBLsWeLiAoYykgS2FzxLFtIDIwMDUwH=
hcN=0A=
MDUxMTA3MTAwNzU3WhcNMTUwOTE2MTAwNzU3WjCBvjE/MD0GA1UEAww2VMOcUktUUlVTVCBFb=
GVr=0A=
dHJvbmlrIFNlcnRpZmlrYSBIaXptZXQgU2HEn2xhecSxY8Sxc8SxMQswCQYDVQQGEwJUUjEPM=
A0G=0A=
A1UEBwwGQW5rYXJhMV0wWwYDVQQKDFRUw5xSS1RSVVNUIEJpbGdpIMSwbGV0acWfaW0gdmUgQ=
mls=0A=
acWfaW0gR8O8dmVubGnEn2kgSGl6bWV0bGVyaSBBLsWeLiAoYykgS2FzxLFtIDIwMDUwggEiM=
A0G=0A=
CSqGSIb3DQEBAQUAA4IBDwAwggEKAoIBAQCpNn7DkUNMwxmYCMjHWHtPFoylzkkBH3MOrHUTp=
vqe=0A=
LCDe2JAOCtFp0if7qnefJ1Il4std2NiDUBd9irWCPwSOtNXwSadktx4uXyCcUHVPr+G1QRT0m=
JKI=0A=
x+XlZEdhR3n9wFHxwZnn3M5q+6+1ATDcRhzviuyV79z/rxAc653YsKpqhRgNF8k+v/Gb0AmJQ=
v2g=0A=
QrSdiVFVKc8bcLyEVK3BEx+Y9C52YItdP5qtygy/p1Zbj3e41Z55SZI/4PGXJHpsmxcPbe9Tm=
JEr=0A=
5A++WXkHeLuXlfSfadRYhwqp48y2WBmfJiGxxFmNskF1wK1pzpwACPI2/z7woQ8arBT9pmAPA=
gMB=0A=
AAGjQzBBMB0GA1UdDgQWBBTZN7NOBf3Zz58SFq62iS/rJTqIHDAPBgNVHQ8BAf8EBQMDBwYAM=
A8G=0A=
A1UdEwEB/wQFMAMBAf8wDQYJKoZIhvcNAQEFBQADggEBAHJglrfJ3NgpXiOFX7KzLXb7iNcX/=
ntt=0A=
Rbj2hWyfIvwqECLsqrkw9qtY1jkQMZkpAL2JZkH7dN6RwRgLn7Vhy506vvWolKMiVW4XSf/SK=
fE4=0A=
Jl3vpao6+XF75tpYHdN0wgH6PmlYX63LaL4ULptswLbcoCb6dxriJNoaN+BnrdFzgw2lGh1uE=
pJ+=0A=
hGIAF728JRhX8tepb1mIvDS3LoV4nZbcFMMsilKbloxSZj2GFotHuFEJjOp9zYhys2AzsfAKR=
O8P=0A=
9Qk3iCQOLGsgOqL6EfJANZxEaGM7rDNvY7wsu/LSy3Z9fYjYHcgFHW68lKlmjHdxx/qR+i9Rn=
uk5=0A=
UrbnBEI=3D=0A=
-----END CERTIFICATE-----=0A=
=0A=
SwissSign Gold CA - G2=0A=
=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=0A=
-----BEGIN CERTIFICATE-----=0A=
MIIFujCCA6KgAwIBAgIJALtAHEP1Xk+wMA0GCSqGSIb3DQEBBQUAMEUxCzAJBgNVBAYTAkNIM=
RUw=0A=
EwYDVQQKEwxTd2lzc1NpZ24gQUcxHzAdBgNVBAMTFlN3aXNzU2lnbiBHb2xkIENBIC0gRzIwH=
hcN=0A=
MDYxMDI1MDgzMDM1WhcNMzYxMDI1MDgzMDM1WjBFMQswCQYDVQQGEwJDSDEVMBMGA1UEChMMU=
3dp=0A=
c3NTaWduIEFHMR8wHQYDVQQDExZTd2lzc1NpZ24gR29sZCBDQSAtIEcyMIICIjANBgkqhkiG9=
w0B=0A=
AQEFAAOCAg8AMIICCgKCAgEAr+TufoskDhJuqVAtFkQ7kpJcyrhdhJJCEyq8ZVeCQD5XJM1Qi=
yUq=0A=
t2/876LQwB8CJEoTlo8jE+YoWACjR8cGp4QjK7u9lit/VcyLwVcfDmJlD909Vopz2q5+bbqBH=
H5C=0A=
jCA12UNNhPqE21Is8w4ndwtrvxEvcnifLtg+5hg3Wipy+dpikJKVyh+c6bM8K8vzARO/Ws/Bt=
Qpg=0A=
vd21mWRTuKCWs2/iJneRjOBiEAKfNA+k1ZIzUd6+jbqEemA8atufK+ze3gE/bk3lUIbLtK/tR=
EDF=0A=
ylqM2tIrfKjuvqblCqoOpd8FUrdVxyJdMmqXl2MT28nbeTZ7hTpKxVKJ+STnnXepgv9VHKVxa=
SvR=0A=
AiTysybUa9oEVeXBCsdtMDeQKuSeFDNeFhdVxVu1yzSJkvGdJo+hB9TGsnhQ2wwMC3wLjEHXu=
end=0A=
jIj3o02yMszYF9rNt85mndT9Xv+9lz4pded+p2JYryU0pUHHPbwNUMoDAw8IWh+Vc3hiv69yF=
GkO=0A=
peUDDniOJihC8AcLYiAQZzlG+qkDzAQ4embvIIO1jEpWjpEA/I5cgt6IoMPiaG59je883WX0X=
axR=0A=
7ySArqpWl2/5rX3aYT+YdzylkbYcjCbaZaIJbcHiVOO5ykxMgI93e2CaHt+28kgeDrpOVG2Y4=
OGi=0A=
GqJ3UM/EY5LsRxmd6+ZrzsECAwEAAaOBrDCBqTAOBgNVHQ8BAf8EBAMCAQYwDwYDVR0TAQH/B=
AUw=0A=
AwEB/zAdBgNVHQ4EFgQUWyV7lqRlUX64OfPAeGZe6Drn8O4wHwYDVR0jBBgwFoAUWyV7lqRlU=
X64=0A=
OfPAeGZe6Drn8O4wRgYDVR0gBD8wPTA7BglghXQBWQECAQEwLjAsBggrBgEFBQcCARYgaHR0c=
Dov=0A=
L3JlcG9zaXRvcnkuc3dpc3NzaWduLmNvbS8wDQYJKoZIhvcNAQEFBQADggIBACe645R88a7A3=
hfm=0A=
5djV9VSwg/S7zV4Fe0+fdWavPOhWfvxyeDgD2StiGwC5+OlgzczOUYrHUDFu4Up+GC9pWbY9Z=
IEr=0A=
44OE5iKHjn3g7gKZYbge9LgriBIWhMIxkziWMaa5O1M/wySTVltpkuzFwbs4AOPsF6m43Md8A=
YOf=0A=
Mke6UiI0HTJ6CVanfCU2qT1L2sCCbwq7EsiHSycR+R4tx5M/nttfJmtS2S6K8RTGRI0Vqbe/v=
d6m=0A=
Gu6uLftIdxf+u+yvGPUqUfA5hJeVbG4bwyvEdGB5JbAKJ9/fXtI5z0V9QkvfsywexcZdylU6o=
Jxp=0A=
mo/a77KwPJ+HbBIrZXAVUjEaJM9vMSNQH4xPjyPDdEFjHFWoFN0+4FFQz/EbMFYOkrCChdiDy=
yJk=0A=
vC24JdVUorgG6q2SpCSgwYa1ShNqR88uC1aVVMvOmttqtKay20EIhid392qgQmwLOM7XdVAyk=
sLf=0A=
KzAiSNDVQTglXaTpXZ/GlHXQRf0wl0OPkKsKx4ZzYEppLd6leNcG2mqeSz53OiATIgHQv2ieY=
2Br=0A=
NU0LbbqhPcCT4H8js1WtciVORvnSFu+wZMEBnunKoGqYDs/YYPIvSbjkQuE4NRb0yG5P94FW6=
Lqj=0A=
viOvrv1vA+ACOzB2+httQc8Bsem4yWb02ybzOqR08kkkW8mw0FfB+j564ZfJ=0A=
-----END CERTIFICATE-----=0A=
=0A=
SwissSign Silver CA - G2=0A=
=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=0A=
-----BEGIN CERTIFICATE-----=0A=
MIIFvTCCA6WgAwIBAgIITxvUL1S7L0swDQYJKoZIhvcNAQEFBQAwRzELMAkGA1UEBhMCQ0gxF=
TAT=0A=
BgNVBAoTDFN3aXNzU2lnbiBBRzEhMB8GA1UEAxMYU3dpc3NTaWduIFNpbHZlciBDQSAtIEcyM=
B4X=0A=
DTA2MTAyNTA4MzI0NloXDTM2MTAyNTA4MzI0NlowRzELMAkGA1UEBhMCQ0gxFTATBgNVBAoTD=
FN3=0A=
aXNzU2lnbiBBRzEhMB8GA1UEAxMYU3dpc3NTaWduIFNpbHZlciBDQSAtIEcyMIICIjANBgkqh=
kiG=0A=
9w0BAQEFAAOCAg8AMIICCgKCAgEAxPGHf9N4Mfc4yfjDmUO8x/e8N+dOcbpLj6VzHVxumK4DV=
644=0A=
N0MvFz0fyM5oEMF4rhkDKxD6LHmD9ui5aLlV8gREpzn5/ASLHvGiTSf5YXu6t+WiE7brYT7Qb=
NHm=0A=
+/pe7R20nqA1W6GSy/BJkv6FCgU+5tkL4k+73JU3/JHpMjUi0R86TieFnbAVlDLaYQ1HTWBCr=
pJH=0A=
6INaUFjpiou5XaHc3ZlKHzZnu0jkg7Y360g6rw9njxcH6ATK72oxh9TAtvmUcXtnZLi2kUpCe=
2Uu=0A=
MGoM9ZDulebyzYLs2aFK7PayS+VFheZteJMELpyCbTapxDFkH4aDCyr0NQp4yVXPQbBH6TCfm=
b5h=0A=
qAaEuSh6XzjZG6k4sIN/c8HDO0gqgg8hm7jMqDXDhBuDsz6+pJVpATqJAHgE2cn0mRmrVn5bi=
4Y5=0A=
FZGkECwJMoBgs5PAKrYYC51+jUnyEEp/+dVGLxmSo5mnJqy7jDzmDrxHB9xzUfFwZC8I+bRHH=
TBs=0A=
ROopN4WSaGa8gzj+ezku01DwH/teYLappvonQfGbGHLy9YR0SslnxFSuSGTfjNFusB3hB48IH=
pmc=0A=
celM2KX3RxIfdNFRnobzwqIjQAtz20um53MGjMGg6cFZrEb65i/4z3GcRm25xBWNOHkDRUjvx=
F3X=0A=
CO6HOSKGsg0PWEP3calILv3q1h8CAwEAAaOBrDCBqTAOBgNVHQ8BAf8EBAMCAQYwDwYDVR0TA=
QH/=0A=
BAUwAwEB/zAdBgNVHQ4EFgQUF6DNweRBtjpbO8tFnb0cwpj6hlgwHwYDVR0jBBgwFoAUF6DNw=
eRB=0A=
tjpbO8tFnb0cwpj6hlgwRgYDVR0gBD8wPTA7BglghXQBWQEDAQEwLjAsBggrBgEFBQcCARYga=
HR0=0A=
cDovL3JlcG9zaXRvcnkuc3dpc3NzaWduLmNvbS8wDQYJKoZIhvcNAQEFBQADggIBAHPGgeAn0=
i0P=0A=
4JUw4ppBf1AsX19iYamGamkYDHRJ1l2E6kFSGG9YrVBWIGrGvShpWJHckRE1qTodvBqlYJ7YH=
39F=0A=
kWnZfrt4csEGDyrOj4VwYaygzQu4OSlWhDJOhrs9xCrZ1x9y7v5RoSJBsXECYxqCsGKrXlcSH=
9/L=0A=
3XWgwF15kIwb4FDm3jH+mHtwX6WQ2K34ArZv02DdQEsixT2tOnqfGhpHkXkzuoLcMmkDlm4fS=
/Bx=0A=
/uNncqCxv1yL5PqZIseEuRuNI5c/7SXgz2W79WEE790eslpBIlqhn10s6FvJbakMDHiqYMZWj=
wFa=0A=
DGi8aRl5xB9+lwW/xekkUV7U1UtT7dkjWjYDZaPBA61BMPNGG4WQr2W11bHkFlt4dR2Xem1Zq=
SqP=0A=
e97Dh4kQmUlzeMg9vVE1dCrV8X5pGyq7O70luJpaPXJhkGaH7gzWTdQRdAtq/gsD/KNVV4n+S=
suu=0A=
WxcFyPKNIzFTONItaj+CuY0IavdeQXRuwxF+B6wpYJE/OMpXEA29MC/HpeZBoNquBYeaoKRlb=
EwJ=0A=
DIm6uNO5wJOKMPqN5ZprFQFOZ6raYlY+hAhm0sQ2fac+EPyI4NSA5QC9qvNOBqN6avlicuMJT=
+ub=0A=
DgEj8Z+7fNzcbBGXJbLytGMU0gYqZ4yD9c7qB9iaah7s5Aq7KkzrCWA5zspi2C5u=0A=
-----END CERTIFICATE-----=0A=
=0A=
GeoTrust Primary Certification Authority=0A=
=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=
=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=0A=
-----BEGIN CERTIFICATE-----=0A=
MIIDfDCCAmSgAwIBAgIQGKy1av1pthU6Y2yv2vrEoTANBgkqhkiG9w0BAQUFADBYMQswCQYDV=
QQG=0A=
EwJVUzEWMBQGA1UEChMNR2VvVHJ1c3QgSW5jLjExMC8GA1UEAxMoR2VvVHJ1c3QgUHJpbWFye=
SBD=0A=
ZXJ0aWZpY2F0aW9uIEF1dGhvcml0eTAeFw0wNjExMjcwMDAwMDBaFw0zNjA3MTYyMzU5NTlaM=
Fgx=0A=
CzAJBgNVBAYTAlVTMRYwFAYDVQQKEw1HZW9UcnVzdCBJbmMuMTEwLwYDVQQDEyhHZW9UcnVzd=
CBQ=0A=
cmltYXJ5IENlcnRpZmljYXRpb24gQXV0aG9yaXR5MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AM=
IIB=0A=
CgKCAQEAvrgVe//UfH1nrYNke8hCUy3f9oQIIGHWAVlqnEQRr+92/ZV+zmEwu3qDXwK9AWbK7=
hWN=0A=
b6EwnL2hhZ6UOvNWiAAxz9juapYC2e0DjPt1befquFUWBRaa9OBesYjAZIVcFU2Ix7e64HXpr=
QU9=0A=
nceJSOC7KMgD4TCTZF5SwFlwIjVXiIrxlQqD17wxcwE07e9GceBrAqg1cmuXm2bgyxx5X9gaB=
Gge=0A=
RwLmnWDiNpcB3841kt++Z8dtd1k7j53WkBWUvEI0EME5+bEnPn7WinXFsq+W06Lem+SYvn3h6=
YGt=0A=
tm/81w7a4DSwDRp35+MImO9Y+pyEtzavwt+s0vQQBnBxNQIDAQABo0IwQDAPBgNVHRMBAf8EB=
TAD=0A=
AQH/MA4GA1UdDwEB/wQEAwIBBjAdBgNVHQ4EFgQULNVQQZcVi/CPNmFbSvtr2ZnJM5IwDQYJK=
oZI=0A=
hvcNAQEFBQADggEBAFpwfyzdtzRP9YZRqSa+S7iq8XEN3GHHoOo0Hnp3DwQ16CePbJC/kRYkR=
j5K=0A=
Ts4rFtULUh38H2eiAkUxT87z+gOneZ1TatnaYzr4gNfTmeGl4b7UVXGYNTq+k+qurUKykG/g/=
CFN=0A=
NWMziUnWm07Kx+dOCQD32sfvmWKZd7aVIl6KoKv0uHiYyjgZmclynnjNS6yvGaBzEi38wkG6g=
ZHa=0A=
Floxt/m0cYASSJlyc1pZU8FjUjPtp8nSOQJw+uCxQmYpqptR7TBUIhRf2asdweSU8Pj1K/fqy=
nhG=0A=
1riR/aYNKxoUAT6A8EKglQdebc3MS6RFjasS6LPeWuWgfOgPIh1a6Vk=3D=0A=
-----END CERTIFICATE-----=0A=
=0A=
thawte Primary Root CA=0A=
=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=0A=
-----BEGIN CERTIFICATE-----=0A=
MIIEIDCCAwigAwIBAgIQNE7VVyDV7exJ9C/ON9srbTANBgkqhkiG9w0BAQUFADCBqTELMAkGA=
1UE=0A=
BhMCVVMxFTATBgNVBAoTDHRoYXd0ZSwgSW5jLjEoMCYGA1UECxMfQ2VydGlmaWNhdGlvbiBTZ=
XJ2=0A=
aWNlcyBEaXZpc2lvbjE4MDYGA1UECxMvKGMpIDIwMDYgdGhhd3RlLCBJbmMuIC0gRm9yIGF1d=
Ghv=0A=
cml6ZWQgdXNlIG9ubHkxHzAdBgNVBAMTFnRoYXd0ZSBQcmltYXJ5IFJvb3QgQ0EwHhcNMDYxM=
TE3=0A=
MDAwMDAwWhcNMzYwNzE2MjM1OTU5WjCBqTELMAkGA1UEBhMCVVMxFTATBgNVBAoTDHRoYXd0Z=
Swg=0A=
SW5jLjEoMCYGA1UECxMfQ2VydGlmaWNhdGlvbiBTZXJ2aWNlcyBEaXZpc2lvbjE4MDYGA1UEC=
xMv=0A=
KGMpIDIwMDYgdGhhd3RlLCBJbmMuIC0gRm9yIGF1dGhvcml6ZWQgdXNlIG9ubHkxHzAdBgNVB=
AMT=0A=
FnRoYXd0ZSBQcmltYXJ5IFJvb3QgQ0EwggEiMA0GCSqGSIb3DQEBAQUAA4IBDwAwggEKAoIBA=
QCs=0A=
oPD7gFnUnMekz52hWXMJEEUMDSxuaPFsW0hoSVk3/AszGcJ3f8wQLZU0HObrTQmnHNK4yZc2A=
reJ=0A=
1CRfBsDMRJSUjQJib+ta3RGNKJpchJAQeg29dGYvajig4tVUROsdB58Hum/u6f1OCyn1PoSgA=
fGc=0A=
q/gcfomk6KHYcWUNo1F77rzSImANuVud37r8UVsLr5iy6S7pBOhih94ryNdOwUxkHt3Ph1i6S=
k/K=0A=
aAcdHJ1KxtUvkcx8cXIcxcBn6zL9yZJclNqFwJu/U30rCfSMnZEfl2pSy94JNqR32HuHUETVP=
m4p=0A=
afs5SSYeCaWAe0At6+gnhcn+Yf1+5nyXHdWdAgMBAAGjQjBAMA8GA1UdEwEB/wQFMAMBAf8wD=
gYD=0A=
VR0PAQH/BAQDAgEGMB0GA1UdDgQWBBR7W0XPr87Lev0xkhpqtvNG61dIUDANBgkqhkiG9w0BA=
QUF=0A=
AAOCAQEAeRHAS7ORtvzw6WfUDW5FvlXok9LOAz/t2iWwHVfLHjp2oEzsUHboZHIMpKnxuIvW1=
oeE=0A=
uzLlQRHAd9mzYJ3rG9XRbkREqaYB7FViHXe4XI5ISXycO1cRrK1zN44veFyQaEfZYGDm/Ac9I=
iAX=0A=
xPcW6cTYcvnIc3zfFi8VqT79aie2oetaupgf1eNNZAqdE8hhuvU5HIe6uL17In/2/qxAeeWsE=
G89=0A=
jxt5dovEN7MhGITlNgDrYyCZuen+MwS7QcjBAvlEYyCegc5C09Y/LHbTY5xZ3Y+m4Q6gLkH3L=
pVH=0A=
z7z9M/P2C2F+fpErgUfCJzDupxBdN49cOSvkBPB7jVaMaA=3D=3D=0A=
-----END CERTIFICATE-----=0A=
=0A=
VeriSign Class 3 Public Primary Certification Authority - G5=0A=
=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=
=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=
=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=0A=
-----BEGIN CERTIFICATE-----=0A=
MIIE0zCCA7ugAwIBAgIQGNrRniZ96LtKIVjNzGs7SjANBgkqhkiG9w0BAQUFADCByjELMAkGA=
1UE=0A=
BhMCVVMxFzAVBgNVBAoTDlZlcmlTaWduLCBJbmMuMR8wHQYDVQQLExZWZXJpU2lnbiBUcnVzd=
CBO=0A=
ZXR3b3JrMTowOAYDVQQLEzEoYykgMjAwNiBWZXJpU2lnbiwgSW5jLiAtIEZvciBhdXRob3Jpe=
mVk=0A=
IHVzZSBvbmx5MUUwQwYDVQQDEzxWZXJpU2lnbiBDbGFzcyAzIFB1YmxpYyBQcmltYXJ5IENlc=
nRp=0A=
ZmljYXRpb24gQXV0aG9yaXR5IC0gRzUwHhcNMDYxMTA4MDAwMDAwWhcNMzYwNzE2MjM1OTU5W=
jCB=0A=
yjELMAkGA1UEBhMCVVMxFzAVBgNVBAoTDlZlcmlTaWduLCBJbmMuMR8wHQYDVQQLExZWZXJpU=
2ln=0A=
biBUcnVzdCBOZXR3b3JrMTowOAYDVQQLEzEoYykgMjAwNiBWZXJpU2lnbiwgSW5jLiAtIEZvc=
iBh=0A=
dXRob3JpemVkIHVzZSBvbmx5MUUwQwYDVQQDEzxWZXJpU2lnbiBDbGFzcyAzIFB1YmxpYyBQc=
mlt=0A=
YXJ5IENlcnRpZmljYXRpb24gQXV0aG9yaXR5IC0gRzUwggEiMA0GCSqGSIb3DQEBAQUAA4IBD=
wAw=0A=
ggEKAoIBAQCvJAgIKXo1nmAMqudLO07cfLw8RRy7K+D+KQL5VwijZIUVJ/XxrcgxiV0i6Cqqp=
kKz=0A=
j/i5Vbext0uz/o9+B1fs70PbZmIVYc9gDaTY3vjgw2IIPVQT60nKWVSFJuUrjxuf6/WhkcIzS=
dhD=0A=
Y2pSS9KP6HBRTdGJaXvHcPaz3BJ023tdS1bTlr8Vd6Gw9KIl8q8ckmcY5fQGBO+QueQA5N06t=
Rn/=0A=
Arr0PO7gi+s3i+z016zy9vA9r911kTMZHRxAy3QkGSGT2RT+rCpSx4/VBEnkjWNHiDxpg8v+R=
70r=0A=
fk/Fla4OndTRQ8Bnc+MUCH7lP59zuDMKz10/NIeWiu5T6CUVAgMBAAGjgbIwga8wDwYDVR0TA=
QH/=0A=
BAUwAwEB/zAOBgNVHQ8BAf8EBAMCAQYwbQYIKwYBBQUHAQwEYTBfoV2gWzBZMFcwVRYJaW1hZ=
2Uv=0A=
Z2lmMCEwHzAHBgUrDgMCGgQUj+XTGoasjY5rw8+AatRIGCx7GS4wJRYjaHR0cDovL2xvZ28ud=
mVy=0A=
aXNpZ24uY29tL3ZzbG9nby5naWYwHQYDVR0OBBYEFH/TZafC3ey78DAJ80M5+gKvMzEzMA0GC=
SqG=0A=
SIb3DQEBBQUAA4IBAQCTJEowX2LP2BqYLz3q3JktvXf2pXkiOOzEp6B4Eq1iDkVwZMXnl2Ytm=
Al+=0A=
X6/WzChl8gGqCBpH3vn5fJJaCGkgDdk+bW48DW7Y5gaRQBi5+MHt39tBquCWIMnNZBU4gcmU7=
qKE=0A=
KQsTb47bDN0lAtukixlE0kF6BWlKWE9gyn6CagsCqiUXObXbf+eEZSqVir2G3l6BFoMtEMze/=
aiC=0A=
Km0oHw0LxOXnGiYZ4fQRbxC1lfznQgUy286dUV4otp6F01vvpX1FQHKOtw5rDgb7MzVIcbidJ=
4vE=0A=
ZV8NhnacRHr2lVz2XTIIM6RUthg/aFzyQkqFOFSDX9HoLPKsEdao7WNq=0A=
-----END CERTIFICATE-----=0A=
=0A=
SecureTrust CA=0A=
=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=0A=
-----BEGIN CERTIFICATE-----=0A=
MIIDuDCCAqCgAwIBAgIQDPCOXAgWpa1Cf/DrJxhZ0DANBgkqhkiG9w0BAQUFADBIMQswCQYDV=
QQG=0A=
EwJVUzEgMB4GA1UEChMXU2VjdXJlVHJ1c3QgQ29ycG9yYXRpb24xFzAVBgNVBAMTDlNlY3VyZ=
VRy=0A=
dXN0IENBMB4XDTA2MTEwNzE5MzExOFoXDTI5MTIzMTE5NDA1NVowSDELMAkGA1UEBhMCVVMxI=
DAe=0A=
BgNVBAoTF1NlY3VyZVRydXN0IENvcnBvcmF0aW9uMRcwFQYDVQQDEw5TZWN1cmVUcnVzdCBDQ=
TCC=0A=
ASIwDQYJKoZIhvcNAQEBBQADggEPADCCAQoCggEBAKukgeWVzfX2FI7CT8rU4niVWJxB4Q2ZQ=
CQX=0A=
OZEzZum+4YOvYlyJ0fwkW2Gz4BERQRwdbvC4u/jep4G6pkjGnx29vo6pQT64lO0pGtSO0gMdA=
+9t=0A=
DWccV9cGrcrI9f4Or2YlSASWC12juhbDCE/RRvgUXPLIXgGZbf2IzIaowW8xQmxSPmjL8xk03=
7uH=0A=
GFaAJsTQ3MBv396gwpEWoGQRS0S8Hvbn+mPeZqx2pHGj7DaUaHp3pLHnDi+BeuK1cobvomuL8=
A/b=0A=
01k/unK8RCSc43Oz969XL0Imnal0ugBS8kvNU3xHCzaFDmapCJcWNFfBZveA4+1wVMeT4C4oF=
VmH=0A=
ursCAwEAAaOBnTCBmjATBgkrBgEEAYI3FAIEBh4EAEMAQTALBgNVHQ8EBAMCAYYwDwYDVR0TA=
QH/=0A=
BAUwAwEB/zAdBgNVHQ4EFgQUQjK2FvoE/f5dS3rD/fdMQB1aQ68wNAYDVR0fBC0wKzApoCegJ=
YYj=0A=
aHR0cDovL2NybC5zZWN1cmV0cnVzdC5jb20vU1RDQS5jcmwwEAYJKwYBBAGCNxUBBAMCAQAwD=
QYJ=0A=
KoZIhvcNAQEFBQADggEBADDtT0rhWDpSclu1pqNlGKa7UTt36Z3q059c4EVlew3KW+JwULKUB=
RSu=0A=
SceNQQcSc5R+DCMh/bwQf2AQWnL1mA6s7Ll/3XpvXdMc9P+IBWlCqQVxyLesJugutIxq/3Hcu=
LHf=0A=
mbx8IVQr5Fiiu1cprp6poxkmD5kuCLDv/WnPmRoJjeOnnyvJNjR7JLN4TJUXpAYmHrZkUjZfY=
GfZ=0A=
nMUFdAvnZyPSCPyI6a6Lf+Ew9Dd+/cYy2i2eRDAwbO4H3tI0/NL/QPZL9GZGBlSm8jIKYyYwa=
5vR=0A=
3ItHuuG51WLQoqD0ZwV4KWMabwTW+MZMo5qxN7SN5ShLHZ4swrhovO0C7jE=3D=0A=
-----END CERTIFICATE-----=0A=
=0A=
Secure Global CA=0A=
=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=0A=
-----BEGIN CERTIFICATE-----=0A=
MIIDvDCCAqSgAwIBAgIQB1YipOjUiolN9BPI8PjqpTANBgkqhkiG9w0BAQUFADBKMQswCQYDV=
QQG=0A=
EwJVUzEgMB4GA1UEChMXU2VjdXJlVHJ1c3QgQ29ycG9yYXRpb24xGTAXBgNVBAMTEFNlY3VyZ=
SBH=0A=
bG9iYWwgQ0EwHhcNMDYxMTA3MTk0MjI4WhcNMjkxMjMxMTk1MjA2WjBKMQswCQYDVQQGEwJVU=
zEg=0A=
MB4GA1UEChMXU2VjdXJlVHJ1c3QgQ29ycG9yYXRpb24xGTAXBgNVBAMTEFNlY3VyZSBHbG9iY=
Wwg=0A=
Q0EwggEiMA0GCSqGSIb3DQEBAQUAA4IBDwAwggEKAoIBAQCvNS7YrGxVaQZx5RNoJLNP2MwhR=
/jx=0A=
YDiJiQPpvepeRlMJ3Fz1Wuj3RSoC6zFh1ykzTM7HfAo3fg+6MpjhHZevj8fcyTiW89sa/FHta=
MbQ=0A=
bqR8JNGuQsiWUGMu4P51/pinX0kuleM5M2SOHqRfkNJnPLLZ/kG5VacJjnIFHovdRIWCQtBJw=
B1g=0A=
8NEXLJXr9qXBkqPFwqcIYA1gBBCWeZ4WNOaptvolRTnIHmX5k/Wq8VLcmZg9pYYaDDUz+kulB=
AYV=0A=
HDGA76oYa8J719rO+TMg1fW9ajMtgQT7sFzUnKPiXB3jqUJ1XnvUd+85VLrJChgbEplJL4hL/=
VBi=0A=
0XPnj3pDAgMBAAGjgZ0wgZowEwYJKwYBBAGCNxQCBAYeBABDAEEwCwYDVR0PBAQDAgGGMA8GA=
1Ud=0A=
EwEB/wQFMAMBAf8wHQYDVR0OBBYEFK9EBMJBfkiD2045AuzshHrmzsmkMDQGA1UdHwQtMCswK=
aAn=0A=
oCWGI2h0dHA6Ly9jcmwuc2VjdXJldHJ1c3QuY29tL1NHQ0EuY3JsMBAGCSsGAQQBgjcVAQQDA=
gEA=0A=
MA0GCSqGSIb3DQEBBQUAA4IBAQBjGghAfaReUw132HquHw0LURYD7xh8yOOvaliTFGCRsoTci=
E6+=0A=
OYo68+aCiV0BN7OrJKQVDpI1WkpEXk5X+nXOH0jOZvQ8QCaSmGwb7iRGDBezUqXbpZGRzzfTb=
+cn=0A=
CDpOGR86p1hcF895P4vkp9MmI50mD1hp/Ed+stCNi5O/KU9DaXR2Z0vPB4zmAve14bRDtUstF=
J/5=0A=
3CYNv6ZHdAbYiNE6KTCEztI5gGIbqMdXSbxqVVFnFUq+NQfk1XWYN3kwFNspnWzFacxHVaIw9=
8xc=0A=
f8LDmBxrThaA63p4ZUWiABqvDA1VZDRIuJK58bRQKfJPIx/abKwfROHdI3hRW8cW=0A=
-----END CERTIFICATE-----=0A=
=0A=
COMODO Certification Authority=0A=
=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=
=3D=3D=3D=3D=3D=0A=
-----BEGIN CERTIFICATE-----=0A=
MIIEHTCCAwWgAwIBAgIQToEtioJl4AsC7j41AkblPTANBgkqhkiG9w0BAQUFADCBgTELMAkGA=
1UE=0A=
BhMCR0IxGzAZBgNVBAgTEkdyZWF0ZXIgTWFuY2hlc3RlcjEQMA4GA1UEBxMHU2FsZm9yZDEaM=
BgG=0A=
A1UEChMRQ09NT0RPIENBIExpbWl0ZWQxJzAlBgNVBAMTHkNPTU9ETyBDZXJ0aWZpY2F0aW9uI=
EF1=0A=
dGhvcml0eTAeFw0wNjEyMDEwMDAwMDBaFw0yOTEyMzEyMzU5NTlaMIGBMQswCQYDVQQGEwJHQ=
jEb=0A=
MBkGA1UECBMSR3JlYXRlciBNYW5jaGVzdGVyMRAwDgYDVQQHEwdTYWxmb3JkMRowGAYDVQQKE=
xFD=0A=
T01PRE8gQ0EgTGltaXRlZDEnMCUGA1UEAxMeQ09NT0RPIENlcnRpZmljYXRpb24gQXV0aG9ya=
XR5=0A=
MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEA0ECLi3LjkRv3UcEbVASY06m/weaKX=
TuH=0A=
+7uIzg3jLz8GlvCiKVCZrts7oVewdFFxze1CkU1B/qnI2GqGd0S7WWaXUF601CxwRM/aN5VCa=
Tww=0A=
xHGzUvAhTaHYujl8HJ6jJJ3ygxaYqhZ8Q5sVW7euNJH+1GImGEaaP+vB+fGQV+useg2L23Iwa=
mbV=0A=
4EajcNxo2f8ESIl33rXp+2dtQem8Ob0y2WIC8bGoPW43nOIv4tOiJovGuFVDiOEjPqXSJDlqR=
6sA=0A=
1KGzqSX+DT+nHbrTUcELpNqsOO9VUCQFZUaTNE8tja3G1CEZ0o7KBWFxB3NH5YoZEr0ETc5On=
KVI=0A=
rLsm9wIDAQABo4GOMIGLMB0GA1UdDgQWBBQLWOWLxkwVN6RAqTCpIb5HNlpW/zAOBgNVHQ8BA=
f8E=0A=
BAMCAQYwDwYDVR0TAQH/BAUwAwEB/zBJBgNVHR8EQjBAMD6gPKA6hjhodHRwOi8vY3JsLmNvb=
W9k=0A=
b2NhLmNvbS9DT01PRE9DZXJ0aWZpY2F0aW9uQXV0aG9yaXR5LmNybDANBgkqhkiG9w0BAQUFA=
AOC=0A=
AQEAPpiem/Yb6dc5t3iuHXIYSdOH5EOC6z/JqvWote9VfCFSZfnVDeFs9D6Mk3ORLgLETgdxb=
8CP=0A=
OGEIqB6BCsAvIC9Bi5HcSEW88cbeunZrM8gALTFGTO3nnc+IlP8zwFboJIYmuNg4ON8qa90Sz=
Mc/=0A=
RxdMosIGlgnW2/4/PEZB31jiVg88O8EckzXZOFKs7sjsLjBOlDW0JB9LeGna8gI4zJVSk/BwJ=
Vmc=0A=
IGfE7vmLV2H0knZ9P4SNVbfo5azV8fUZVqZa+5Acr5Pr5RzUZ5ddBA6+C4OmF4O5MBKgxTMVB=
bkN=0A=
+8cFduPYSo38NBejxiEovjBFMR7HeL5YYTisO+IBZQ=3D=3D=0A=
-----END CERTIFICATE-----=0A=
=0A=
Network Solutions Certificate Authority=0A=
=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=
=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=0A=
-----BEGIN CERTIFICATE-----=0A=
MIID5jCCAs6gAwIBAgIQV8szb8JcFuZHFhfjkDFo4DANBgkqhkiG9w0BAQUFADBiMQswCQYDV=
QQG=0A=
EwJVUzEhMB8GA1UEChMYTmV0d29yayBTb2x1dGlvbnMgTC5MLkMuMTAwLgYDVQQDEydOZXR3b=
3Jr=0A=
IFNvbHV0aW9ucyBDZXJ0aWZpY2F0ZSBBdXRob3JpdHkwHhcNMDYxMjAxMDAwMDAwWhcNMjkxM=
jMx=0A=
MjM1OTU5WjBiMQswCQYDVQQGEwJVUzEhMB8GA1UEChMYTmV0d29yayBTb2x1dGlvbnMgTC5ML=
kMu=0A=
MTAwLgYDVQQDEydOZXR3b3JrIFNvbHV0aW9ucyBDZXJ0aWZpY2F0ZSBBdXRob3JpdHkwggEiM=
A0G=0A=
CSqGSIb3DQEBAQUAA4IBDwAwggEKAoIBAQDkvH6SMG3G2I4rC7xGzuAnlt7e+foS0zwzc7MEL=
7xx=0A=
jOWftiJgPl9dzgn/ggwbmlFQGiaJ3dVhXRncEg8tCqJDXRfQNJIg6nPPOCwGJgl6cvf6UDL4w=
pPT=0A=
aaIjzkGxzOTVHzbRijr4jGPiFFlp7Q3Tf2vouAPlT2rlmGNpSAW+Lv8ztumXWWn4Zxmuk2GWR=
BXT=0A=
crA/vGp97Eh/jcOrqnErU2lBUzS1sLnFBgrEsEX1QV1uiUV7PTsmjHTC5dLRfbIR1PtYMiKag=
Mnc=0A=
/Qzpf14Dl847ABSHJ3A4qY5usyd2mFHgBeMhqxrVhSI8KbWaFsWAqPS7azCPL0YCorEMIuDTA=
gMB=0A=
AAGjgZcwgZQwHQYDVR0OBBYEFCEwyfsA106Y2oeqKtCnLrFAMadMMA4GA1UdDwEB/wQEAwIBB=
jAP=0A=
BgNVHRMBAf8EBTADAQH/MFIGA1UdHwRLMEkwR6BFoEOGQWh0dHA6Ly9jcmwubmV0c29sc3NsL=
mNv=0A=
bS9OZXR3b3JrU29sdXRpb25zQ2VydGlmaWNhdGVBdXRob3JpdHkuY3JsMA0GCSqGSIb3DQEBB=
QUA=0A=
A4IBAQC7rkvnt1frf6ott3NHhWrB5KUd5Oc86fRZZXe1eltajSU24HqXLjjAV2CDmAaDn7l2e=
m5Q=0A=
4LqILPxFzBiwmZVRDuwduIj/h1AcgsLj4DKAv6ALR8jDMe+ZZzKATxcheQxpXN5eNK4CtSbqU=
N9/=0A=
GGUsyfJj4akH/nxxH2szJGoeBfcFaMBqEssuXmHLrijTfsK0ZpEmXzwuJF/LWA/rKOyvEZbz3=
Htv=0A=
wKeI8lN3s2Berq4o2jUsbzRF0ybh3uxbTydrFny9RAQYgrOJeRcQcT16ohZO9QHNpGxlaKFJd=
lxD=0A=
ydi8NmdspZS11My5vWo1ViHe2MPr+8ukYEywVaCge1ey=0A=
-----END CERTIFICATE-----=0A=
=0A=
WellsSecure Public Root Certificate Authority=0A=
=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=
=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=0A=
-----BEGIN CERTIFICATE-----=0A=
MIIEvTCCA6WgAwIBAgIBATANBgkqhkiG9w0BAQUFADCBhTELMAkGA1UEBhMCVVMxIDAeBgNVB=
AoM=0A=
F1dlbGxzIEZhcmdvIFdlbGxzU2VjdXJlMRwwGgYDVQQLDBNXZWxscyBGYXJnbyBCYW5rIE5BM=
TYw=0A=
NAYDVQQDDC1XZWxsc1NlY3VyZSBQdWJsaWMgUm9vdCBDZXJ0aWZpY2F0ZSBBdXRob3JpdHkwH=
hcN=0A=
MDcxMjEzMTcwNzU0WhcNMjIxMjE0MDAwNzU0WjCBhTELMAkGA1UEBhMCVVMxIDAeBgNVBAoMF=
1dl=0A=
bGxzIEZhcmdvIFdlbGxzU2VjdXJlMRwwGgYDVQQLDBNXZWxscyBGYXJnbyBCYW5rIE5BMTYwN=
AYD=0A=
VQQDDC1XZWxsc1NlY3VyZSBQdWJsaWMgUm9vdCBDZXJ0aWZpY2F0ZSBBdXRob3JpdHkwggEiM=
A0G=0A=
CSqGSIb3DQEBAQUAA4IBDwAwggEKAoIBAQDub7S9eeKPCCGeOARBJe+rWxxTkqxtnt3CxC5Fl=
AM1=0A=
iGd0V+PfjLindo8796jE2yljDpFoNoqXjopxaAkH5OjUDk/41itMpBb570OYj7OeUt9tkTmPO=
L13=0A=
i0Nj67eT/DBMHAGTthP796EfvyXhdDcsHqRePGj4S78NuR4uNuip5Kf4D8uCdXw1LSLWwr8L8=
7T8=0A=
bJVhHlfXBIEyg1J55oNjz7fLY4sR4r1e6/aN7ZVyKLSsEmLpSjPmgzKuBXWVvYSV2ypcm44uD=
LiB=0A=
K0HmOFafSZtsdvqKXfcBeYF8wYNABf5x/Qw/zE5gCQ5lRxAvAcAFP4/4s0HvWkJ+We/SlwxlA=
gMB=0A=
AAGjggE0MIIBMDAPBgNVHRMBAf8EBTADAQH/MDkGA1UdHwQyMDAwLqAsoCqGKGh0dHA6Ly9jc=
mwu=0A=
cGtpLndlbGxzZmFyZ28uY29tL3dzcHJjYS5jcmwwDgYDVR0PAQH/BAQDAgHGMB0GA1UdDgQWB=
BQm=0A=
lRkQ2eihl5H/3BnZtQQ+0nMKajCBsgYDVR0jBIGqMIGngBQmlRkQ2eihl5H/3BnZtQQ+0nMKa=
qGB=0A=
i6SBiDCBhTELMAkGA1UEBhMCVVMxIDAeBgNVBAoMF1dlbGxzIEZhcmdvIFdlbGxzU2VjdXJlM=
Rww=0A=
GgYDVQQLDBNXZWxscyBGYXJnbyBCYW5rIE5BMTYwNAYDVQQDDC1XZWxsc1NlY3VyZSBQdWJsa=
WMg=0A=
Um9vdCBDZXJ0aWZpY2F0ZSBBdXRob3JpdHmCAQEwDQYJKoZIhvcNAQEFBQADggEBALkVsUSRz=
CPI=0A=
K0134/iaeycNzXK7mQDKfGYZUMbVmO2rvwNa5U3lHshPcZeG1eMd/ZDJPHV3V3p9+N701NX3l=
eZ0=0A=
bh08rnyd2wIDBSxxSyU+B+NemvVmFymIGjifz6pBA4SXa5M4esowRBskRDPQ5NHcKDj0E0M1N=
Slj=0A=
qHyita04pO2t/caaH/+Xc/77szWnk4bGdpEA5qxRFsQnMlzbc9qlk1eOPm01JghZ1edE13YgY=
+es=0A=
E2fDbbFwRnzVlhE9iW9dqKHrjQrawx0zbKPqZxmamX9LPYNRKh3KL4YMon4QLSvUFpULB6ouF=
JJJ=0A=
tylv2G0xffX8oRAHh84vWdw+WNs=3D=0A=
-----END CERTIFICATE-----=0A=
=0A=
COMODO ECC Certification Authority=0A=
=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=
=3D=3D=3D=3D=3D=3D=3D=3D=3D=0A=
-----BEGIN CERTIFICATE-----=0A=
MIICiTCCAg+gAwIBAgIQH0evqmIAcFBUTAGem2OZKjAKBggqhkjOPQQDAzCBhTELMAkGA1UEB=
hMC=0A=
R0IxGzAZBgNVBAgTEkdyZWF0ZXIgTWFuY2hlc3RlcjEQMA4GA1UEBxMHU2FsZm9yZDEaMBgGA=
1UE=0A=
ChMRQ09NT0RPIENBIExpbWl0ZWQxKzApBgNVBAMTIkNPTU9ETyBFQ0MgQ2VydGlmaWNhdGlvb=
iBB=0A=
dXRob3JpdHkwHhcNMDgwMzA2MDAwMDAwWhcNMzgwMTE4MjM1OTU5WjCBhTELMAkGA1UEBhMCR=
0Ix=0A=
GzAZBgNVBAgTEkdyZWF0ZXIgTWFuY2hlc3RlcjEQMA4GA1UEBxMHU2FsZm9yZDEaMBgGA1UEC=
hMR=0A=
Q09NT0RPIENBIExpbWl0ZWQxKzApBgNVBAMTIkNPTU9ETyBFQ0MgQ2VydGlmaWNhdGlvbiBBd=
XRo=0A=
b3JpdHkwdjAQBgcqhkjOPQIBBgUrgQQAIgNiAAQDR3svdcmCFYX7deSRFtSrYpn1PlILBs5BA=
H+X=0A=
4QokPB0BBO490o0JlwzgdeT6+3eKKvUDYEs2ixYjFq0JcfRK9ChQtP6IHG4/bC8vCVlbpVsLM=
5ni=0A=
wz2J+Wos77LTBumjQjBAMB0GA1UdDgQWBBR1cacZSBm8nZ3qQUfflMRId5nTeTAOBgNVHQ8BA=
f8E=0A=
BAMCAQYwDwYDVR0TAQH/BAUwAwEB/zAKBggqhkjOPQQDAwNoADBlAjEA7wNbeqy3eApyt4jf/=
7VG=0A=
FAkK+qDmfQjGGoe9GKhzvSbKYAydzpmfz1wPMOG+FDHqAjAU9JM8SaczepBGR7NjfRObTrdvG=
DeA=0A=
U/7dIOA1mjbRxwG55tzd8/8dLDoWV9mSOdY=3D=0A=
-----END CERTIFICATE-----=0A=
=0A=
IGC/A=0A=
=3D=3D=3D=3D=3D=0A=
-----BEGIN CERTIFICATE-----=0A=
MIIEAjCCAuqgAwIBAgIFORFFEJQwDQYJKoZIhvcNAQEFBQAwgYUxCzAJBgNVBAYTAkZSMQ8wD=
QYD=0A=
VQQIEwZGcmFuY2UxDjAMBgNVBAcTBVBhcmlzMRAwDgYDVQQKEwdQTS9TR0ROMQ4wDAYDVQQLE=
wVE=0A=
Q1NTSTEOMAwGA1UEAxMFSUdDL0ExIzAhBgkqhkiG9w0BCQEWFGlnY2FAc2dkbi5wbS5nb3V2L=
mZy=0A=
MB4XDTAyMTIxMzE0MjkyM1oXDTIwMTAxNzE0MjkyMlowgYUxCzAJBgNVBAYTAkZSMQ8wDQYDV=
QQI=0A=
EwZGcmFuY2UxDjAMBgNVBAcTBVBhcmlzMRAwDgYDVQQKEwdQTS9TR0ROMQ4wDAYDVQQLEwVEQ=
1NT=0A=
STEOMAwGA1UEAxMFSUdDL0ExIzAhBgkqhkiG9w0BCQEWFGlnY2FAc2dkbi5wbS5nb3V2LmZyM=
IIB=0A=
IjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAsh/R0GLFMzvABIaIs9z4iPf930Pfeo2aS=
Vz2=0A=
TqrMHLmh6yeJ8kbpO0px1R2OLc/mratjUMdUC24SyZA2xtgv2pGqaMVy/hcKshd+ebUyiHDKc=
MCW=0A=
So7kVc0dJ5S/znIq7Fz5cyD+vfcuiWe4u0dzEvfRNWk68gq5rv9GQkaiv6GFGvm/5P9Jhfejc=
IYy=0A=
HF2fYPepraX/z9E0+X1bF8bc1g4oa8Ld8fUzaJ1O/Id8NhLWo4DoQw1VYZTqZDdH6nfK0LJYB=
cNd=0A=
frGoRpAxVs5wKpayMLh35nnAvSk7/ZR3TL0gzUEl4C7HG7vupARB0l2tEmqKm0f7yd1GQOGdP=
DPQ=0A=
tQIDAQABo3cwdTAPBgNVHRMBAf8EBTADAQH/MAsGA1UdDwQEAwIBRjAVBgNVHSAEDjAMMAoGC=
CqB=0A=
egF5AQEBMB0GA1UdDgQWBBSjBS8YYFDCiQrdKyFP/45OqDAxNjAfBgNVHSMEGDAWgBSjBS8YY=
FDC=0A=
iQrdKyFP/45OqDAxNjANBgkqhkiG9w0BAQUFAAOCAQEABdwm2Pp3FURo/C9mOnTgXeQp/wYHE=
4RK=0A=
q89toB9RlPhJy3Q2FLwV3duJL92PoF189RLrn544pEfMs5bZvpwlqwN+Mw+VgQ39FuCIvjfwb=
F3Q=0A=
MZsyK10XZZOYYLxuj7GoPB7ZHPOpJkL5ZB3C55L29B5aqhlSXa/oovdgoPaN8In1buAKBQGVy=
Ysg=0A=
Crpa/JosPL3Dt8ldeCUFP1YUmwza+zpI/pdpXsoQhvdOlgQITeywvl3cO45Pwf2aNjSaTFR+F=
wNI=0A=
lQgRHAdvhQh+XU3Endv7rs6y0bO4g2wdsrN58dhwmX7wEwLOXt1R0982gaEbeC9xs/FZTEYYK=
KuF=0A=
0mBWWg=3D=3D=0A=
-----END CERTIFICATE-----=0A=
=0A=
Security Communication EV RootCA1=0A=
=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=
=3D=3D=3D=3D=3D=3D=3D=3D=0A=
-----BEGIN CERTIFICATE-----=0A=
MIIDfTCCAmWgAwIBAgIBADANBgkqhkiG9w0BAQUFADBgMQswCQYDVQQGEwJKUDElMCMGA1UEC=
hMc=0A=
U0VDT00gVHJ1c3QgU3lzdGVtcyBDTy4sTFRELjEqMCgGA1UECxMhU2VjdXJpdHkgQ29tbXVua=
WNh=0A=
dGlvbiBFViBSb290Q0ExMB4XDTA3MDYwNjAyMTIzMloXDTM3MDYwNjAyMTIzMlowYDELMAkGA=
1UE=0A=
BhMCSlAxJTAjBgNVBAoTHFNFQ09NIFRydXN0IFN5c3RlbXMgQ08uLExURC4xKjAoBgNVBAsTI=
VNl=0A=
Y3VyaXR5IENvbW11bmljYXRpb24gRVYgUm9vdENBMTCCASIwDQYJKoZIhvcNAQEBBQADggEPA=
DCC=0A=
AQoCggEBALx/7FebJOD+nLpCeamIivqA4PUHKUPqjgo0No0c+qe1OXj/l3X3L+SqawSERMqm4=
miO=0A=
/VVQYg+kcQ7OBzgtQoVQrTyWb4vVog7P3kmJPdZkLjjlHmy1V4qe70gOzXppFodEtZDkBp2uo=
QSX=0A=
WHnvIEqCa4wiv+wfD+mEce3xDuS4GBPMVjZd0ZoeUWs5bmB2iDQL87PRsJ3KYeJkHcFGB7hj3=
R4z=0A=
ZbOOCVVSPbW9/wfrrWFVGCypaZhKqkDFMxRldAD5kd6vA0jFQFTcD4SQaCDFkpbcLuUCRarAX=
1T4=0A=
bepJz11sS6/vmsJWXMY1VkJqMF/Cq/biPT+zyRGPMUzXn0kCAwEAAaNCMEAwHQYDVR0OBBYEF=
DVK=0A=
9U2vP9eCOKyrcWUXdYydVZPmMA4GA1UdDwEB/wQEAwIBBjAPBgNVHRMBAf8EBTADAQH/MA0GC=
SqG=0A=
SIb3DQEBBQUAA4IBAQCoh+ns+EBnXcPBZsdAS5f8hxOQWsTvoMpfi7ent/HWtWS3irO4G8za+=
6xm=0A=
iEHO6Pzk2x6Ipu0nUBsCMCRGef4Eh3CXQHPRwMFXGZpppSeZq51ihPZRwSzJIxXYKLerJRO1R=
uGG=0A=
Av8mjMSIkh1W/hln8lXkgKNrnKt34VFxDSDbEJrbvXZ5B3eZKK2aXtqxT0QsNY6llsf9g/BYx=
nnW=0A=
mHyojf6GPgcWkuF75x3sM3Z+Qi5KhfmRiWiEA4Glm5q+4zfFVKtWOxgtQaQM+ELbmaDgcm+7X=
eEW=0A=
T1MKZPlO9L9OVL14bIjqv5wTJMJwaaJ/D8g8rQjJsJhAoyrniIPtd490=0A=
-----END CERTIFICATE-----=0A=
=0A=
OISTE WISeKey Global Root GA CA=0A=
=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=
=3D=3D=3D=3D=3D=3D=0A=
-----BEGIN CERTIFICATE-----=0A=
MIID8TCCAtmgAwIBAgIQQT1yx/RrH4FDffHSKFTfmjANBgkqhkiG9w0BAQUFADCBijELMAkGA=
1UE=0A=
BhMCQ0gxEDAOBgNVBAoTB1dJU2VLZXkxGzAZBgNVBAsTEkNvcHlyaWdodCAoYykgMjAwNTEiM=
CAG=0A=
A1UECxMZT0lTVEUgRm91bmRhdGlvbiBFbmRvcnNlZDEoMCYGA1UEAxMfT0lTVEUgV0lTZUtle=
SBH=0A=
bG9iYWwgUm9vdCBHQSBDQTAeFw0wNTEyMTExNjAzNDRaFw0zNzEyMTExNjA5NTFaMIGKMQswC=
QYD=0A=
VQQGEwJDSDEQMA4GA1UEChMHV0lTZUtleTEbMBkGA1UECxMSQ29weXJpZ2h0IChjKSAyMDA1M=
SIw=0A=
IAYDVQQLExlPSVNURSBGb3VuZGF0aW9uIEVuZG9yc2VkMSgwJgYDVQQDEx9PSVNURSBXSVNlS=
2V5=0A=
IEdsb2JhbCBSb290IEdBIENBMIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAy0+zA=
Js9=0A=
Nt350UlqaxBJH+zYK7LG+DKBKUOVTJoZIyEVRd7jyBxRVVuuk+g3/ytr6dTqvirdqFEr12bDY=
Vxg=0A=
Asj1znJ7O7jyTmUIms2kahnBAbtzptf2w93NvKSLtZlhuAGio9RN1AU9ka34tAhxZK9w8Rxrf=
vbD=0A=
d50kc3vkDIzh2TbhmYsFmQvtRTEJysIA2/dyoJaqlYfQjse2YXMNdmaM3Bu0Y6Kff5MTMPGhJ=
9vZ=0A=
/yxViJGg4E8HsChWjBgbl0SOid3gF27nKu+POQoxhILYQBRJLnpB5Kf+42TMwVlxSywhp1t94=
B3R=0A=
LoGbw9ho972WG6xwsRYUC9tguSYBBQIDAQABo1EwTzALBgNVHQ8EBAMCAYYwDwYDVR0TAQH/B=
AUw=0A=
AwEB/zAdBgNVHQ4EFgQUswN+rja8sHnR3JQmthG+IbJphpQwEAYJKwYBBAGCNxUBBAMCAQAwD=
QYJ=0A=
KoZIhvcNAQEFBQADggEBAEuh/wuHbrP5wUOxSPMowB0uyQlB+pQAHKSkq0lPjz0e701vvbyk9=
vIm=0A=
MMkQyh2I+3QZH4VFvbBsUfk2ftv1TDI6QU9bR8/oCy22xBmddMVHxjtqD6wU2zz0c5ypBd8A3=
HR4=0A=
+vg1YFkCExh8vPtNsCBtQ7tgMHpnM1zFmdH4LTlSc/uMqpclXHLZCB6rTjzjgTGfA6b7wP4pi=
FXa=0A=
hNVQA7bihKOmNqoROgHhGEvWRGizPflTdISzRpFGlgC3gCy24eMQ4tui5yiPAZZiFj4A4xylN=
oEY=0A=
okxSdsARo27mHbrjWr42U8U+dY+GaSlYU7Wcu2+fXMUY7N0v4ZjJ/L7fCg0=3D=0A=
-----END CERTIFICATE-----=0A=
=0A=
Microsec e-Szigno Root CA=0A=
=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=0A=
-----BEGIN CERTIFICATE-----=0A=
MIIHqDCCBpCgAwIBAgIRAMy4579OKRr9otxmpRwsDxEwDQYJKoZIhvcNAQEFBQAwcjELMAkGA=
1UE=0A=
BhMCSFUxETAPBgNVBAcTCEJ1ZGFwZXN0MRYwFAYDVQQKEw1NaWNyb3NlYyBMdGQuMRQwEgYDV=
QQL=0A=
EwtlLVN6aWdubyBDQTEiMCAGA1UEAxMZTWljcm9zZWMgZS1Temlnbm8gUm9vdCBDQTAeFw0wN=
TA0=0A=
MDYxMjI4NDRaFw0xNzA0MDYxMjI4NDRaMHIxCzAJBgNVBAYTAkhVMREwDwYDVQQHEwhCdWRhc=
GVz=0A=
dDEWMBQGA1UEChMNTWljcm9zZWMgTHRkLjEUMBIGA1UECxMLZS1Temlnbm8gQ0ExIjAgBgNVB=
AMT=0A=
GU1pY3Jvc2VjIGUtU3ppZ25vIFJvb3QgQ0EwggEiMA0GCSqGSIb3DQEBAQUAA4IBDwAwggEKA=
oIB=0A=
AQDtyADVgXvNOABHzNuEwSFpLHSQDCHZU4ftPkNEU6+r+ICbPHiN1I2uuO/TEdyB5s87lozWb=
xXG=0A=
d36hL+BfkrYn13aaHUM86tnsL+4582pnS4uCzyL4ZVX+LMsvfUh6PXX5qqAnu3jCBspRwn5mS=
6/N=0A=
oqdNAoI/gqyFxuEPkEeZlApxcpMqyabAvjxWTHOSJ/FrtfX9/DAFYJLG65Z+AZHCabEeHXtTR=
bjc=0A=
QR/Ji3HWVBTji1R4P770Yjtb9aPs1ZJ04nQw7wHb4dSrmZsqa/i9phyGI0Jf7Enemotb9HI6Q=
MVJ=0A=
PqW+jqpx62z69Rrkav17fVVA71hu5tnVvCSrwe+3AgMBAAGjggQ3MIIEMzBnBggrBgEFBQcBA=
QRb=0A=
MFkwKAYIKwYBBQUHMAGGHGh0dHBzOi8vcmNhLmUtc3ppZ25vLmh1L29jc3AwLQYIKwYBBQUHM=
AKG=0A=
IWh0dHA6Ly93d3cuZS1zemlnbm8uaHUvUm9vdENBLmNydDAPBgNVHRMBAf8EBTADAQH/MIIBc=
wYD=0A=
VR0gBIIBajCCAWYwggFiBgwrBgEEAYGoGAIBAQEwggFQMCgGCCsGAQUFBwIBFhxodHRwOi8vd=
3d3=0A=
LmUtc3ppZ25vLmh1L1NaU1ovMIIBIgYIKwYBBQUHAgIwggEUHoIBEABBACAAdABhAG4A+gBzA=
O0A=0A=
dAB2AOEAbgB5ACAA6QByAHQAZQBsAG0AZQB6AOkAcwDpAGgAZQB6ACAA6QBzACAAZQBsAGYAb=
wBn=0A=
AGEAZADhAHMA4QBoAG8AegAgAGEAIABTAHoAbwBsAGcA4QBsAHQAYQB0APMAIABTAHoAbwBsA=
GcA=0A=
4QBsAHQAYQB0AOEAcwBpACAAUwB6AGEAYgDhAGwAeQB6AGEAdABhACAAcwB6AGUAcgBpAG4Ad=
AAg=0A=
AGsAZQBsAGwAIABlAGwAagDhAHIAbgBpADoAIABoAHQAdABwADoALwAvAHcAdwB3AC4AZQAtA=
HMA=0A=
egBpAGcAbgBvAC4AaAB1AC8AUwBaAFMAWgAvMIHIBgNVHR8EgcAwgb0wgbqggbeggbSGIWh0d=
HA6=0A=
Ly93d3cuZS1zemlnbm8uaHUvUm9vdENBLmNybIaBjmxkYXA6Ly9sZGFwLmUtc3ppZ25vLmh1L=
0NO=0A=
PU1pY3Jvc2VjJTIwZS1Temlnbm8lMjBSb290JTIwQ0EsT1U9ZS1Temlnbm8lMjBDQSxPPU1pY=
3Jv=0A=
c2VjJTIwTHRkLixMPUJ1ZGFwZXN0LEM9SFU/Y2VydGlmaWNhdGVSZXZvY2F0aW9uTGlzdDtia=
W5h=0A=
cnkwDgYDVR0PAQH/BAQDAgEGMIGWBgNVHREEgY4wgYuBEGluZm9AZS1zemlnbm8uaHWkdzB1M=
SMw=0A=
IQYDVQQDDBpNaWNyb3NlYyBlLVN6aWduw7MgUm9vdCBDQTEWMBQGA1UECwwNZS1TemlnbsOzI=
EhT=0A=
WjEWMBQGA1UEChMNTWljcm9zZWMgS2Z0LjERMA8GA1UEBxMIQnVkYXBlc3QxCzAJBgNVBAYTA=
khV=0A=
MIGsBgNVHSMEgaQwgaGAFMegSXUWYYTbMUuE0vE3QJDvTtz3oXakdDByMQswCQYDVQQGEwJIV=
TER=0A=
MA8GA1UEBxMIQnVkYXBlc3QxFjAUBgNVBAoTDU1pY3Jvc2VjIEx0ZC4xFDASBgNVBAsTC2UtU=
3pp=0A=
Z25vIENBMSIwIAYDVQQDExlNaWNyb3NlYyBlLVN6aWdubyBSb290IENBghEAzLjnv04pGv2i3=
Gal=0A=
HCwPETAdBgNVHQ4EFgQUx6BJdRZhhNsxS4TS8TdAkO9O3PcwDQYJKoZIhvcNAQEFBQADggEBA=
NMT=0A=
nGZjWS7KXHAM/IO8VbH0jgdsZifOwTsgqRy7RlRw7lrMoHfqaEQn6/Ip3Xep1fvj1KcExJW4C=
+FE=0A=
aGAHQzAxQmHl7tnlJNUb3+FKG6qfx1/4ehHqE5MAyopYse7tDk2016g2JnzgOsHVV4Lxdbb9i=
V/a=0A=
86g4nzUGCM4ilb7N1fy+W955a9x6qWVmvrElWl/tftOsRm1M9DKHtCAE4Gx4sHfRhUZLphK3d=
ehK=0A=
yVZs15KrnfVJONJPU+NVkBHbmJbGSfI+9J8b4PeI3CVimUTYc78/MPMMNz7UwiiAc7EBt51al=
hQB=0A=
S6kRnSlqLtBdgcDPsiBDxwPgN05dCtxZICU=3D=0A=
-----END CERTIFICATE-----=0A=
=0A=
Certigna=0A=
=3D=3D=3D=3D=3D=3D=3D=3D=0A=
-----BEGIN CERTIFICATE-----=0A=
MIIDqDCCApCgAwIBAgIJAP7c4wEPyUj/MA0GCSqGSIb3DQEBBQUAMDQxCzAJBgNVBAYTAkZSM=
RIw=0A=
EAYDVQQKDAlEaGlteW90aXMxETAPBgNVBAMMCENlcnRpZ25hMB4XDTA3MDYyOTE1MTMwNVoXD=
TI3=0A=
MDYyOTE1MTMwNVowNDELMAkGA1UEBhMCRlIxEjAQBgNVBAoMCURoaW15b3RpczERMA8GA1UEA=
wwI=0A=
Q2VydGlnbmEwggEiMA0GCSqGSIb3DQEBAQUAA4IBDwAwggEKAoIBAQDIaPHJ1tazNHUmgh7st=
L7q=0A=
XOEm7RFHYeGifBZ4QCHkYJ5ayGPhxLGWkv8YbWkj4Sti993iNi+RB7lIzw7sebYs5zRLcAglo=
zyH=0A=
GxnygQcPOJAZ0xH+hrTy0V4eHpbNgGzOOzGTtvKg0KmVEn2lmsxryIRWijOp5yIVUxbwzBfsV=
1/p=0A=
ogqYCd7jX5xv3EjjhQsVWqa6n6xI4wmy9/Qy3l40vhx4XUJbzg4ij02Q130yGLMLLGq/jj8UE=
Ykg=0A=
DncUtT2UCIf3JR7VsmAA7G8qKCVuKj4YYxclPz5EIBb2JsglrgVKtOdjLPOMFlN+XPsRGgjBR=
mKf=0A=
Irjxwo1p3Po6WAbfAgMBAAGjgbwwgbkwDwYDVR0TAQH/BAUwAwEB/zAdBgNVHQ4EFgQUGu3+Q=
TmQ=0A=
tCRZvgHyUtVF9lo53BEwZAYDVR0jBF0wW4AUGu3+QTmQtCRZvgHyUtVF9lo53BGhOKQ2MDQxC=
zAJ=0A=
BgNVBAYTAkZSMRIwEAYDVQQKDAlEaGlteW90aXMxETAPBgNVBAMMCENlcnRpZ25hggkA/tzjA=
Q/J=0A=
SP8wDgYDVR0PAQH/BAQDAgEGMBEGCWCGSAGG+EIBAQQEAwIABzANBgkqhkiG9w0BAQUFAAOCA=
QEA=0A=
hQMeknH2Qq/ho2Ge6/PAD/Kl1NqV5ta+aDY9fm4fTIrv0Q8hbV6lUmPOEvjvKtpv6zf+EwLHy=
zs+=0A=
ImvaYS5/1HI93TDhHkxAGYwP15zRgzB7mFncfca5DClMoTOi62c6ZYTTluLtdkVwj7Ur3vkj1=
klu=0A=
PBS1xp81HlDQwY9qcEQCYsuuHWhBp6pX6FOqB9IG9tUUBguRA3UsbHK1YZWaDYu5Def131TN3=
ubY=0A=
1gkIl2PlwS6wt0QmwCbAr1UwnjvVNioZBPRcHv/PLLf/0P2HQBHVESO7SMAhqaQoLf0V+LBOK=
/Qw=0A=
WyH8EZE0vkHve52Xdf+XlcCWWC/qu0bXu+TZLg=3D=3D=0A=
-----END CERTIFICATE-----=0A=
=0A=
Deutsche Telekom Root CA 2=0A=
=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=
=3D=0A=
-----BEGIN CERTIFICATE-----=0A=
MIIDnzCCAoegAwIBAgIBJjANBgkqhkiG9w0BAQUFADBxMQswCQYDVQQGEwJERTEcMBoGA1UEC=
hMT=0A=
RGV1dHNjaGUgVGVsZWtvbSBBRzEfMB0GA1UECxMWVC1UZWxlU2VjIFRydXN0IENlbnRlcjEjM=
CEG=0A=
A1UEAxMaRGV1dHNjaGUgVGVsZWtvbSBSb290IENBIDIwHhcNOTkwNzA5MTIxMTAwWhcNMTkwN=
zA5=0A=
MjM1OTAwWjBxMQswCQYDVQQGEwJERTEcMBoGA1UEChMTRGV1dHNjaGUgVGVsZWtvbSBBRzEfM=
B0G=0A=
A1UECxMWVC1UZWxlU2VjIFRydXN0IENlbnRlcjEjMCEGA1UEAxMaRGV1dHNjaGUgVGVsZWtvb=
SBS=0A=
b290IENBIDIwggEiMA0GCSqGSIb3DQEBAQUAA4IBDwAwggEKAoIBAQCrC6M14IspFLEUha88E=
OQ5=0A=
bzVdSq7d6mGNlUn0b2SjGmBmpKlAIoTZ1KXleJMOaAGtuU1cOs7TuKhCQN/Po7qCWWqSG6wcm=
toI=0A=
KyUn+WkjR/Hg6yx6m/UTAtB+NHzCnjwAWav12gz1MjwrrFDa1sPeg5TKqAyZMg4ISFZbavva4=
VhY=0A=
AUlfckE8FQYBjl2tqriTtM2e66foai1SNNs671x1Udrb8zH57nGYMsRUFUQM+ZtV7a3fGAigo=
4aK=0A=
Se5TBY8ZTNXeWHmb0mocQqvF1afPaA+W5OFhmHZhyJF81j4A4pFQh+GdCuatl9Idxjp9y7zaA=
zTV=0A=
jlsB9WoHtxa2bkp/AgMBAAGjQjBAMB0GA1UdDgQWBBQxw3kbuvVT1xfgiXotF2wKsyudMzAPB=
gNV=0A=
HRMECDAGAQH/AgEFMA4GA1UdDwEB/wQEAwIBBjANBgkqhkiG9w0BAQUFAAOCAQEAlGRZrTlk5=
ynr=0A=
E/5aw4sTV8gEJPB0d8Bg42f76Ymmg7+Wgnxu1MM9756AbrsptJh6sTtU6zkXR34ajgv8HzFZM=
QSy=0A=
zhfzLMdiNlXiItiJVbSYSKpk+tYcNthEeFpaIzpXl/V6ME+un2pMSyuOoAPjPuCp1NJ70rOo4=
nI8=0A=
rZ7/gFnkm0W09juwzTkZmDLl6iFhkOQxIY40sfcvNUqFENrnijchvllj4PKFiDFT1FQUhXB59=
C4G=0A=
dyd1Lx+4ivn+xbrYNuSD7Odlt79jWvNGr4GUN9RBjNYj1h7P9WgbRGOiWrqnNVmh5XAFmw4jV=
5mU=0A=
Cm26OWMohpLzGITY+9HPBVZkVw=3D=3D=0A=
-----END CERTIFICATE-----=0A=
=0A=
Cybertrust Global Root=0A=
=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=0A=
-----BEGIN CERTIFICATE-----=0A=
MIIDoTCCAomgAwIBAgILBAAAAAABD4WqLUgwDQYJKoZIhvcNAQEFBQAwOzEYMBYGA1UEChMPQ=
3li=0A=
ZXJ0cnVzdCwgSW5jMR8wHQYDVQQDExZDeWJlcnRydXN0IEdsb2JhbCBSb290MB4XDTA2MTIxN=
TA4=0A=
MDAwMFoXDTIxMTIxNTA4MDAwMFowOzEYMBYGA1UEChMPQ3liZXJ0cnVzdCwgSW5jMR8wHQYDV=
QQD=0A=
ExZDeWJlcnRydXN0IEdsb2JhbCBSb290MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCA=
QEA=0A=
+Mi8vRRQZhP/8NN57CPytxrHjoXxEnOmGaoQ25yiZXRadz5RfVb23CO21O1fWLE3TdVJDm71a=
ofW=0A=
0ozSJ8bi/zafmGWgE07GKmSb1ZASzxQG9Dvj1Ci+6A74q05IlG2OlTEQXO2iLb3VOm2yHLtgw=
EZL=0A=
AfVJrn5GitB0jaEMAs7u/OePuGtm839EAL9mJRQr3RAwHQeWP032a7iPt3sMpTjr3kfb1V05/=
Iin=0A=
89cqdPHoWqI7n1C6poxFNcJQZZXcY4Lv3b93TZxiyWNzFtApD0mpSPCzqrdsxacwOUBdrsTiX=
SZT=0A=
8M4cIwhhqJQZugRiQOwfOHB3EgZxpzAYXSUnpQIDAQABo4GlMIGiMA4GA1UdDwEB/wQEAwIBB=
jAP=0A=
BgNVHRMBAf8EBTADAQH/MB0GA1UdDgQWBBS2CHsNesysIEyGVjJez6tuhS1wVzA/BgNVHR8EO=
DA2=0A=
MDSgMqAwhi5odHRwOi8vd3d3Mi5wdWJsaWMtdHJ1c3QuY29tL2NybC9jdC9jdHJvb3QuY3JsM=
B8G=0A=
A1UdIwQYMBaAFLYIew16zKwgTIZWMl7Pq26FLXBXMA0GCSqGSIb3DQEBBQUAA4IBAQBW7wojo=
FRO=0A=
lZfJ+InaRcHUowAl9B8Tq7ejhVhpwjCt2BWKLePJzYFa+HMjWqd8BfP9IjsO0QbE2zZMcwSO5=
bAi=0A=
5MXzLqXZI+O4Tkogp24CJJ8iYGd7ix1yCcUxXOl5n4BHPa2hCwcUPUf/A2kaDAtE52Mlp3+yy=
bh2=0A=
hO0j9n0Hq0V+09+zv+mKts2oomcrUtW3ZfA5TGOgkXmTUg9U3YO7n9GPp1Nzw8v/MOx8BLjYR=
B+T=0A=
X3EJIrduPuocA06dGiBh+4E37F78CkWr1+cXVdCg6mCbpvbjjFspwgZgFJ0tl0ypkxWdYcQBX=
0jW=0A=
WL1WMRJOEcgh4LMRkWXbtKaIOM5V=0A=
-----END CERTIFICATE-----=0A=
=0A=
ePKI Root Certification Authority=0A=
=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=
=3D=3D=3D=3D=3D=3D=3D=3D=0A=
-----BEGIN CERTIFICATE-----=0A=
MIIFsDCCA5igAwIBAgIQFci9ZUdcr7iXAF7kBtK8nTANBgkqhkiG9w0BAQUFADBeMQswCQYDV=
QQG=0A=
EwJUVzEjMCEGA1UECgwaQ2h1bmdod2EgVGVsZWNvbSBDby4sIEx0ZC4xKjAoBgNVBAsMIWVQS=
0kg=0A=
Um9vdCBDZXJ0aWZpY2F0aW9uIEF1dGhvcml0eTAeFw0wNDEyMjAwMjMxMjdaFw0zNDEyMjAwM=
jMx=0A=
MjdaMF4xCzAJBgNVBAYTAlRXMSMwIQYDVQQKDBpDaHVuZ2h3YSBUZWxlY29tIENvLiwgTHRkL=
jEq=0A=
MCgGA1UECwwhZVBLSSBSb290IENlcnRpZmljYXRpb24gQXV0aG9yaXR5MIICIjANBgkqhkiG9=
w0B=0A=
AQEFAAOCAg8AMIICCgKCAgEA4SUP7o3biDN1Z82tH306Tm2d0y8U82N0ywEhajfqhFAHSyZbC=
UNs=0A=
IZ5qyNUD9WBpj8zwIuQf5/dqIjG3LBXy4P4AakP/h2XGtRrBp0xtInAhijHyl3SJCRImHJ7K2=
RKi=0A=
lTza6We/CKBk49ZCt0Xvl/T29de1ShUCWH2YWEtgvM3XDZoTM1PRYfl61dd4s5oz9wCGzh1Nl=
Div=0A=
qOx4UXCKXBCDUSH3ET00hl7lSM2XgYI1TBnsZfZrxQWh7kcT1rMhJ5QQCtkkO7q+RBNGMD+XP=
NjX=0A=
12ruOzjjK9SXDrkb5wdJfzcq+Xd4z1TtW0ado4AOkUPB1ltfFLqfpo0kR0BZv3I4sjZsN/+Z0=
V0O=0A=
WQqraffAsgRFelQArr5T9rXn4fg8ozHSqf4hUmTFpmfwdQcGlBSBVcYn5AGPF8Fqcde+S/uUW=
H1+=0A=
ETOxQvdibBjWzwloPn9s9h6PYq2lY9sJpx8iQkEeb5mKPtf5P0B6ebClAZLSnT0IFaUQAS2zM=
nao=0A=
lQ2zepr7BxB4EW/hj8e6DyUadCrlHJhBmd8hh+iVBmoKs2pHdmX2Os+PYhcZewoozRrSgx4hx=
yy/=0A=
vv9haLdnG7t4TY3OZ+XkwY63I2binZB1NJipNiuKmpS5nezMirH4JYlcWrYvjB9teSSnUmjDh=
DXi=0A=
Zo1jDiVN1Rmy5nk3pyKdVDECAwEAAaNqMGgwHQYDVR0OBBYEFB4M97Zn8uGSJglFwFU5Lnc/Q=
kqi=0A=
MAwGA1UdEwQFMAMBAf8wOQYEZyoHAAQxMC8wLQIBADAJBgUrDgMCGgUAMAcGBWcqAwAABBRFs=
MLH=0A=
ClZ87lt4DJX5GFPBphzYEDANBgkqhkiG9w0BAQUFAAOCAgEACbODU1kBPpVJufGBuvl2ICO1J=
2B0=0A=
1GqZNF5sAFPZn/KmsSQHRGoqxqWOeBLoR9lYGxMqXnmbnwoqZ6YlPwZpVnPDimZI+ymBV3QGy=
pzq=0A=
KOg4ZyYr8dW1P2WT+DZdjo2NQCCHGervJ8A9tDkPJXtoUHRVnAxZfVo9QZQlUgjgRywVMRnVv=
wdV=0A=
xrsStZf0X4OFunHB2WyBEXYKCrC/gpf36j36+uwtqSiUO1bd0lEursC9CBWMd1I0ltabrNMdj=
mEP=0A=
NXubrjlpC2JgQCA2j6/7Nu4tCEoduL+bXPjqpRugc6bY+G7gMwRfaKonh+3ZwZCc7b3jajWvY=
9+r=0A=
GNm65ulK6lCKD2GTHuItGeIwlDWSXQ62B68ZgI9HkFFLLk3dheLSClIKF5r8GrBQAuUBo2M3I=
UxE=0A=
xJtRmREOc5wGj1QupyheRDmHVi03vYVElOEMSyycw5KFNGHLD7ibSkNS/jQ6fbjpKdx2qcgw+=
BRx=0A=
gMYeNkh0IkFch4LoGHGLQYlE535YW6i4jRPpp2zDR+2zGp1iro2C6pSe3VkQw63d4k3jMdXH7=
Ojy=0A=
sP6SHhYKGvzZ8/gntsm+HbRsZJB/9OTEW9c3rkIO3aQab3yIVMUWbuF6aC74Or8NpDyJO3inT=
mOD=0A=
BCEIZ43ygknQW/2xzQ+DhNQ+IIX3Sj0rnP0qCglN6oH4EZw=3D=0A=
-----END CERTIFICATE-----=0A=
=0A=
T\xc3\x9c\x42\xC4\xB0TAK UEKAE K\xC3\xB6k Sertifika Hizmet =
Sa\xC4\x9Flay\xc4\xb1\x63\xc4\xb1s\xc4\xb1 - S\xC3\xBCr\xC3\xBCm 3=0A=
=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=
=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=
=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=
=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=
=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=0A=
-----BEGIN CERTIFICATE-----=0A=
MIIFFzCCA/+gAwIBAgIBETANBgkqhkiG9w0BAQUFADCCASsxCzAJBgNVBAYTAlRSMRgwFgYDV=
QQH=0A=
DA9HZWJ6ZSAtIEtvY2FlbGkxRzBFBgNVBAoMPlTDvHJraXllIEJpbGltc2VsIHZlIFRla25vb=
G9q=0A=
aWsgQXJhxZ90xLFybWEgS3VydW11IC0gVMOcQsSwVEFLMUgwRgYDVQQLDD9VbHVzYWwgRWxla=
3Ry=0A=
b25payB2ZSBLcmlwdG9sb2ppIEFyYcWfdMSxcm1hIEVuc3RpdMO8c8O8IC0gVUVLQUUxIzAhB=
gNV=0A=
BAsMGkthbXUgU2VydGlmaWthc3lvbiBNZXJrZXppMUowSAYDVQQDDEFUw5xCxLBUQUsgVUVLQ=
UUg=0A=
S8O2ayBTZXJ0aWZpa2EgSGl6bWV0IFNhxJ9sYXnEsWPEsXPEsSAtIFPDvHLDvG0gMzAeFw0wN=
zA4=0A=
MjQxMTM3MDdaFw0xNzA4MjExMTM3MDdaMIIBKzELMAkGA1UEBhMCVFIxGDAWBgNVBAcMD0dlY=
npl=0A=
IC0gS29jYWVsaTFHMEUGA1UECgw+VMO8cmtpeWUgQmlsaW1zZWwgdmUgVGVrbm9sb2ppayBBc=
mHF=0A=
n3TEsXJtYSBLdXJ1bXUgLSBUw5xCxLBUQUsxSDBGBgNVBAsMP1VsdXNhbCBFbGVrdHJvbmlrI=
HZl=0A=
IEtyaXB0b2xvamkgQXJhxZ90xLFybWEgRW5zdGl0w7xzw7wgLSBVRUtBRTEjMCEGA1UECwwaS=
2Ft=0A=
dSBTZXJ0aWZpa2FzeW9uIE1lcmtlemkxSjBIBgNVBAMMQVTDnELEsFRBSyBVRUtBRSBLw7ZrI=
FNl=0A=
cnRpZmlrYSBIaXptZXQgU2HEn2xhecSxY8Sxc8SxIC0gU8O8csO8bSAzMIIBIjANBgkqhkiG9=
w0B=0A=
AQEFAAOCAQ8AMIIBCgKCAQEAim1L/xCIOsP2fpTo6iBkcK4hgb46ezzb8R1Sf1n68yJMlaCQv=
EhO=0A=
Eav7t7WNeoMojCZG2E6VQIdhn8WebYGHV2yKO7Rm6sxA/OOqbLLLAdsyv9Lrhc+hDVXDWzhXc=
Lh1=0A=
xnnRFDDtG1hba+818qEhTsXOfJlfbLm4IpNQp81McGq+agV/E5wrHur+R84EpW+sky58K5+ee=
ROR=0A=
6Oqeyjh1jmKwlZMq5d/pXpduIF9fhHpEORlAHLpVK/swsoHvhOPc7Jg4OQOFCKlUAwUp8MmPi=
+oL=0A=
hmUZEdPpCSPeaJMDyTYcIW7OjGbxmTDY17PDHfiBLqi9ggtm/oLL4eAagsNAgQIDAQABo0IwQ=
DAd=0A=
BgNVHQ4EFgQUvYiHyY/2pAoLquvF/pEjnatKijIwDgYDVR0PAQH/BAQDAgEGMA8GA1UdEwEB/=
wQF=0A=
MAMBAf8wDQYJKoZIhvcNAQEFBQADggEBAB18+kmPNOm3JpIWmgV050vQbTlswyb2zrgxvMTfv=
Cr4=0A=
N5EY3ATIZJkrGG2AA1nJrvhY0D7twyOfaTyGOBye79oneNGEN3GKPEs5z35FBtYt2IpNeBLWr=
cLT=0A=
y9LQQfMmNkqblWwM7uXRQydmwYj3erMgbOqwaSvHIOgMA8RBBZniP+Rr+KCGgceExh/VS4ESs=
hYh=0A=
LBOhgLJeDEoTniDYYkCrkOpkSi+sDQESeUWoL4cZaMjihccwsnX5OD+ywJO0a+IDRM5noN+J1=
q2M=0A=
dqMTw5RhK2vZbMEHCiIHhWyFJEapvj+LeISCfiQMnf2BN+MlqO02TpUsyZyQ2uypQjyttgI=3D=0A=
-----END CERTIFICATE-----=0A=
=0A=
Buypass Class 2 CA 1=0A=
=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=0A=
-----BEGIN CERTIFICATE-----=0A=
MIIDUzCCAjugAwIBAgIBATANBgkqhkiG9w0BAQUFADBLMQswCQYDVQQGEwJOTzEdMBsGA1UEC=
gwU=0A=
QnV5cGFzcyBBUy05ODMxNjMzMjcxHTAbBgNVBAMMFEJ1eXBhc3MgQ2xhc3MgMiBDQSAxMB4XD=
TA2=0A=
MTAxMzEwMjUwOVoXDTE2MTAxMzEwMjUwOVowSzELMAkGA1UEBhMCTk8xHTAbBgNVBAoMFEJ1e=
XBh=0A=
c3MgQVMtOTgzMTYzMzI3MR0wGwYDVQQDDBRCdXlwYXNzIENsYXNzIDIgQ0EgMTCCASIwDQYJK=
oZI=0A=
hvcNAQEBBQADggEPADCCAQoCggEBAIs8B0XY9t/mx8q6jUPFR42wWsE425KEHK8T1A9vNkYgx=
C7M=0A=
cXA0ojTTNy7Y3Tp3L8DrKehc0rWpkTSHIln+zNvnma+WwajHQN2lFYxuyHyXA8vmIPLXl18xo=
S83=0A=
0r7uvqmtqEyeIWZDO6i88wmjONVZJMHCR3axiFyCO7srpgTXjAePzdVBHfCuuCkslFJgNJQ72=
uA4=0A=
0Z0zPhX0kzLFANq1KWYOOngPIVJfAuWSeyXTkh4vFZ2B5J2O6O+JzhRMVB0cgRJNcKi+EAUXf=
h/R=0A=
uFdV7c27UsKwHnjCTTZoy1YmwVLBvXb3WNVyfh9EdrsAiR0WnVE1703CVu9r4Iw7DekCAwEAA=
aNC=0A=
MEAwDwYDVR0TAQH/BAUwAwEB/zAdBgNVHQ4EFgQUP42aWYv8e3uco684sDntkHGA1sgwDgYDV=
R0P=0A=
AQH/BAQDAgEGMA0GCSqGSIb3DQEBBQUAA4IBAQAVGn4TirnoB6NLJzKyQJHyIdFkhb5jatLPg=
cIV=0A=
1Xp+DCmsNx4cfHZSldq1fyOhKXdlyTKdqC5Wq2B2zha0jX94wNWZUYN/Xtm+DKhQ7SLHrQVMd=
vvt=0A=
7h5HZPb3J31cKA9FxVxiXqaakZG3Uxcu3K1gnZZkOb1naLKuBctN518fV4bVIJwo+28TOPX2E=
ZL2=0A=
fZleHwzoq0QkKXJAPTZSr4xYkHPB7GEseaHsh7U/2k3ZIQAw3pDaDtMaSKk+hQsUi4y8QZ5q9=
w5w=0A=
wDX3OaJdZtB7WZ+oRxKaJyOkLY4ng5IgodcVf/EuGO70SH8vf/GhGLWhC5SgYiAynB321O+/T=
Iho=0A=
-----END CERTIFICATE-----=0A=
=0A=
EBG Elektronik Sertifika Hizmet =
Sa\xC4\x9Flay\xc4\xb1\x63\xc4\xb1s\xc4\xb1=0A=
=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=
=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=
=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=0A=
-----BEGIN CERTIFICATE-----=0A=
MIIF5zCCA8+gAwIBAgIITK9zQhyOdAIwDQYJKoZIhvcNAQEFBQAwgYAxODA2BgNVBAMML0VCR=
yBF=0A=
bGVrdHJvbmlrIFNlcnRpZmlrYSBIaXptZXQgU2HEn2xhecSxY8Sxc8SxMTcwNQYDVQQKDC5FQ=
kcg=0A=
QmlsacWfaW0gVGVrbm9sb2ppbGVyaSB2ZSBIaXptZXRsZXJpIEEuxZ4uMQswCQYDVQQGEwJUU=
jAe=0A=
Fw0wNjA4MTcwMDIxMDlaFw0xNjA4MTQwMDMxMDlaMIGAMTgwNgYDVQQDDC9FQkcgRWxla3Ryb=
25p=0A=
ayBTZXJ0aWZpa2EgSGl6bWV0IFNhxJ9sYXnEsWPEsXPEsTE3MDUGA1UECgwuRUJHIEJpbGnFn=
2lt=0A=
IFRla25vbG9qaWxlcmkgdmUgSGl6bWV0bGVyaSBBLsWeLjELMAkGA1UEBhMCVFIwggIiMA0GC=
SqG=0A=
SIb3DQEBAQUAA4ICDwAwggIKAoICAQDuoIRh0DpqZhAy2DE4f6en5f2h4fuXd7hxlugTlkaDT=
7by=0A=
X3JWbhNgpQGR4lvFzVcfd2NR/y8927k/qqk153nQ9dAktiHq6yOU/im/+4mRDGSaBUorzAzu8=
T2b=0A=
gmmkTPiab+ci2hC6X5L8GCcKqKpE+i4stPtGmggDg3KriORqcsnlZR9uKg+ds+g75AxuetpX/=
dfr=0A=
eYteIAbTdgtsApWjluTLdlHRKJ2hGvxEok3MenaoDT2/F08iiFD9rrbskFBKW5+VQarKD7JK/=
oCZ=0A=
TqNGFav4c0JqwmZ2sQomFd2TkuzbqV9UIlKRcF0T6kjsbgNs2d1s/OsNA/+mgxKb8amTD8UmT=
DGy=0A=
Y5lhcucqZJnSuOl14nypqZoaqsNW2xCaPINStnuWt6yHd6i58mcLlEOzrz5z+kI2sSXFCjEmN=
1Zn=0A=
uqMLfdb3ic1nobc6HmZP9qBVFCVMLDMNpkGMvQQxahByCp0OLna9XvNRiYuoP1Vzv9s6xiQFl=
pJI=0A=
qkuNKgPlV5EQ9GooFW5Hd4RcUXSfGenmHmMWOeMRFeNYGkS9y8RsZteEBt8w9DeiQyJ50hBs3=
7vm=0A=
ExH8nYQKE3vwO9D8owrXieqWfo1IhR5kX9tUoqzVegJ5a9KK8GfaZXINFHDk6Y54jzJ0fFfy1=
tb0=0A=
Nokb+Clsi7n2l9GkLqq+CxnCRelwXQIDAJ3Zo2MwYTAPBgNVHRMBAf8EBTADAQH/MA4GA1UdD=
wEB=0A=
/wQEAwIBBjAdBgNVHQ4EFgQU587GT/wWZ5b6SqMHwQSny2re2kcwHwYDVR0jBBgwFoAU587GT=
/wW=0A=
Z5b6SqMHwQSny2re2kcwDQYJKoZIhvcNAQEFBQADggIBAJuYml2+8ygjdsZs93/mQJ7ANtyVD=
R2t=0A=
FcU22NU57/IeIl6zgrRdu0waypIN30ckHrMk2pGI6YNw3ZPX6bqz3xZaPt7gyPvT/Wwp+BVGo=
Ggm=0A=
zJNSroIBk5DKd8pNSe/iWtkqvTDOTLKBtjDOWU/aWR1qeqRFsIImgYZ29fUQALjuswnoT4cCB=
64k=0A=
XPBfrAowzIpAoHMEwfuJJPaaHFy3PApnNgUIMbOv2AFoKuB4j3TeuFGkjGwgPaL7s9QJ/XvCg=
KqT=0A=
bCmYIai7FvOpEl90tYeY8pUm3zTvilORiF0alKM/fCL414i6poyWqD1SNGKfAB5UVUJnxk1Gj=
7sU=0A=
RT0KlhaOEKGXmdXTMIXM3rRyt7yKPBgpaP3ccQfuJDlq+u2lrDgv+R4QDgZxGhBM/nV+/x5XO=
ULK=0A=
1+EVoVZVWRvRo68R2E7DpSvvkL/A7IITW43WciyTTo9qKd+FPNMN4KIYEsxVL0e3p5sC/kH2i=
Ext=0A=
2qkBR4NkJ2IQgtYSe14DHzSpyZH+r11thie3I6p1GMog57AP14kOpmciY/SDQSsGS7tY1dHXt=
7kQ=0A=
Y9iJSrSq3RZj9W6+YKH47ejWkE8axsWgKdOnIaj1Wjz3x0miIZpKlVIglnKaZsv30oZDfCK+l=
vm9=0A=
AahH3eU7QPl1K5srRmSGjR70j/sHd9DqSaIcjVIUpgqT=0A=
-----END CERTIFICATE-----=0A=
=0A=
certSIGN ROOT CA=0A=
=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=0A=
-----BEGIN CERTIFICATE-----=0A=
MIIDODCCAiCgAwIBAgIGIAYFFnACMA0GCSqGSIb3DQEBBQUAMDsxCzAJBgNVBAYTAlJPMREwD=
wYD=0A=
VQQKEwhjZXJ0U0lHTjEZMBcGA1UECxMQY2VydFNJR04gUk9PVCBDQTAeFw0wNjA3MDQxNzIwM=
DRa=0A=
Fw0zMTA3MDQxNzIwMDRaMDsxCzAJBgNVBAYTAlJPMREwDwYDVQQKEwhjZXJ0U0lHTjEZMBcGA=
1UE=0A=
CxMQY2VydFNJR04gUk9PVCBDQTCCASIwDQYJKoZIhvcNAQEBBQADggEPADCCAQoCggEBALczu=
X7I=0A=
JUqOtdu0KBuqV5Do0SLTZLrTk+jUrIZhQGpgV2hUhE28alQCBf/fm5oqrl0Hj0rDKH/v+yv6e=
fHH=0A=
rfAQUySQi2bJqIirr1qjAOm+ukbuW3N7LBeCgV5iLKECZbO9xSsAfsT8AzNXDe3i+s5dRdY4z=
TW2=0A=
ssHQnIFKquSyAVwdj1+ZxLGt24gh65AIgoDzMKND5pCCrlUoSe1b16kQOA7+j0xbm0bqQfWwC=
HTD=0A=
0IgztnzXdN/chNFDDnU5oSVAKOp4yw4sLjmdjItuFhwvJoIQ4uNllAoEwF73XVv4EOLQunpL+=
943=0A=
AAAaWyjj0pxzPjKHmKHJUS/X3qwzs08CAwEAAaNCMEAwDwYDVR0TAQH/BAUwAwEB/zAOBgNVH=
Q8B=0A=
Af8EBAMCAcYwHQYDVR0OBBYEFOCMm9slSbPxfIbWskKHC9BroNnkMA0GCSqGSIb3DQEBBQUAA=
4IB=0A=
AQA+0hyJLjX8+HXd5n9liPRyTMks1zJO890ZeUe9jjtbkw9QSSQTaxQGcu8J06Gh40CEyecYM=
nQ8=0A=
SG4Pn0vU9x7Tk4ZkVJdjclDVVc/6IJMCopvDI5NOFlV2oHB5bc0hH88vLbwZ44gx+FkagQnIl=
6Z0=0A=
x2DEW8xXjrJ1/RsCCdtZb3KTafcxQdaIOL+Hsr0Wefmq5L6IJd1hJyMctTEHBDa0GpC9oHRxU=
Ilt=0A=
vBTjD4au8as+x6AJzKNI0eDbZOeStc+vckNwi/nDhDwTqn6Sm1dTk/pwwpEOMfmbZ13pljheX=
7Nz=0A=
TogVZ96edhBiIL5VaZVDADlN9u6wWk5JRFRYX0KD=0A=
-----END CERTIFICATE-----=0A=
=0A=
CNNIC ROOT=0A=
=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=0A=
-----BEGIN CERTIFICATE-----=0A=
MIIDVTCCAj2gAwIBAgIESTMAATANBgkqhkiG9w0BAQUFADAyMQswCQYDVQQGEwJDTjEOMAwGA=
1UE=0A=
ChMFQ05OSUMxEzARBgNVBAMTCkNOTklDIFJPT1QwHhcNMDcwNDE2MDcwOTE0WhcNMjcwNDE2M=
Dcw=0A=
OTE0WjAyMQswCQYDVQQGEwJDTjEOMAwGA1UEChMFQ05OSUMxEzARBgNVBAMTCkNOTklDIFJPT=
1Qw=0A=
ggEiMA0GCSqGSIb3DQEBAQUAA4IBDwAwggEKAoIBAQDTNfc/c3et6FtzF8LRb+1VvG7q6KR5s=
mzD=0A=
o+/hn7E7SIX1mlwhIhAsxYLO2uOabjfhhyzcuQxauohV3/2q2x8x6gHx3zkBwRP9SFIhxFXf2=
tiz=0A=
VHa6dLG3fdfA6PZZxU3Iva0fFNrfWEQlMhkqx35+jq44sDB7R3IJMfAw28Mbdim7aXZOV/kbZ=
KKT=0A=
VrdvmW7bCgScEeOAH8tjlBAKqeFkgjH5jCftppkA9nCTGPihNIaj3XrCGHn2emU1z5DrvTOTn=
1Or=0A=
czvmmzQgLx3vqR1jGqCA2wMv+SYahtKNu6m+UjqHZ0gNv7Sg2Ca+I19zN38m5pIEo3/PIKe38=
zrK=0A=
y5nLAgMBAAGjczBxMBEGCWCGSAGG+EIBAQQEAwIABzAfBgNVHSMEGDAWgBRl8jGtKvf33VKWC=
scC=0A=
wQ7vptU7ETAPBgNVHRMBAf8EBTADAQH/MAsGA1UdDwQEAwIB/jAdBgNVHQ4EFgQUZfIxrSr39=
91S=0A=
lgrHAsEO76bVOxEwDQYJKoZIhvcNAQEFBQADggEBAEs17szkrr/Dbq2flTtLP1se31cpolnKO=
OK5=0A=
Gv+e5m4y3R6u6jW39ZORTtpC4cMXYFDy0VwmuYK36m3knITnA3kXr5g9lNvHugDnuL8BV8F3R=
TIM=0A=
O/G0HAiw/VGgod2aHRM2mm23xzy54cXZF/qD1T0VoDy7HgviyJA/qIYM/PmLXoXLT1tLYhFHx=
UV8=0A=
BS9BsZ4QaRuZluBVeftOhpm4lNqGOGqTo+fLbuXf6iFViZx9fX+Y9QCJ7uOEwFyWtcVG6kbgh=
VW2=0A=
G8kS1sHNzYDzAgE8yGnLRUhj2JTQ7IUOO04RZfSCjKY9ri4ilAnIXOo8gV0WKgOXFlUJ24pBg=
p5m=0A=
mxE=3D=0A=
-----END CERTIFICATE-----=0A=
=0A=
ApplicationCA - Japanese Government=0A=
=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=
=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=0A=
-----BEGIN CERTIFICATE-----=0A=
MIIDoDCCAoigAwIBAgIBMTANBgkqhkiG9w0BAQUFADBDMQswCQYDVQQGEwJKUDEcMBoGA1UEC=
hMT=0A=
SmFwYW5lc2UgR292ZXJubWVudDEWMBQGA1UECxMNQXBwbGljYXRpb25DQTAeFw0wNzEyMTIxN=
TAw=0A=
MDBaFw0xNzEyMTIxNTAwMDBaMEMxCzAJBgNVBAYTAkpQMRwwGgYDVQQKExNKYXBhbmVzZSBHb=
3Zl=0A=
cm5tZW50MRYwFAYDVQQLEw1BcHBsaWNhdGlvbkNBMIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AM=
IIB=0A=
CgKCAQEAp23gdE6Hj6UG3mii24aZS2QNcfAKBZuOquHMLtJqO8F6tJdhjYq+xpqcBrSGUeQ3D=
nR4=0A=
fl+Kf5Sk10cI/VBaVuRorChzoHvpfxiSQE8tnfWuREhzNgaeZCw7NCPbXCbkcXmP1G55IrmTw=
crN=0A=
wVbtiGrXoDkhBFcsovW8R0FPXjQilbUfKW1eSvNNcr5BViCH/OlQR9cwFO5cjFW6WY2H/CPek=
9AE=0A=
jP3vbb3QesmlOmpyM8ZKDQUXKi17safY1vC+9D/qDihtQWEjdnjDuGWk81quzMKq2edY3rZ+n=
YVu=0A=
nyoKb58DKTCXKB28t89UKU5RMfkntigm/qJj5kEW8DOYRwIDAQABo4GeMIGbMB0GA1UdDgQWB=
BRU=0A=
WssmP3HMlEYNllPqa0jQk/5CdTAOBgNVHQ8BAf8EBAMCAQYwWQYDVR0RBFIwUKROMEwxCzAJB=
gNV=0A=
BAYTAkpQMRgwFgYDVQQKDA/ml6XmnKzlm73mlL/lupwxIzAhBgNVBAsMGuOCouODl+ODquOCs=
eOD=0A=
vOOCt+ODp+ODs0NBMA8GA1UdEwEB/wQFMAMBAf8wDQYJKoZIhvcNAQEFBQADggEBADlqRHZ3O=
Drs=0A=
o2dGD/mLBqj7apAxzn7s2tGJfHrrLgy9mTLnsCTWw//1sogJhyzjVOGjprIIC8CFqMjSnHH2H=
Z9g=0A=
/DgzE+Ge3Atf2hZQKXsvcJEPmbo0NI2VdMV+eKlmXb3KIXdCEKxmJj3ekav9FfBv7WxfEPjzF=
vYD=0A=
io+nEhEMy/0/ecGc/WLuo89UDNErXxc+4z6/wCs+CZv+iKZ+tJIX/COUgb1up8WMwusRRdv4Q=
cmW=0A=
dupwX3kSa+SjB1oF7ydJzyGfikwJcGapJsErEU4z0g781mzSDjJkaP+tBXhfAx2o45CsJOAPQ=
KdL=0A=
rosot4LKGAfmt1t06SAZf7IbiVQ=3D=0A=
-----END CERTIFICATE-----=0A=
=0A=
GeoTrust Primary Certification Authority - G3=0A=
=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=
=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=0A=
-----BEGIN CERTIFICATE-----=0A=
MIID/jCCAuagAwIBAgIQFaxulBmyeUtB9iepwxgPHzANBgkqhkiG9w0BAQsFADCBmDELMAkGA=
1UE=0A=
BhMCVVMxFjAUBgNVBAoTDUdlb1RydXN0IEluYy4xOTA3BgNVBAsTMChjKSAyMDA4IEdlb1Ryd=
XN0=0A=
IEluYy4gLSBGb3IgYXV0aG9yaXplZCB1c2Ugb25seTE2MDQGA1UEAxMtR2VvVHJ1c3QgUHJpb=
WFy=0A=
eSBDZXJ0aWZpY2F0aW9uIEF1dGhvcml0eSAtIEczMB4XDTA4MDQwMjAwMDAwMFoXDTM3MTIwM=
TIz=0A=
NTk1OVowgZgxCzAJBgNVBAYTAlVTMRYwFAYDVQQKEw1HZW9UcnVzdCBJbmMuMTkwNwYDVQQLE=
zAo=0A=
YykgMjAwOCBHZW9UcnVzdCBJbmMuIC0gRm9yIGF1dGhvcml6ZWQgdXNlIG9ubHkxNjA0BgNVB=
AMT=0A=
LUdlb1RydXN0IFByaW1hcnkgQ2VydGlmaWNhdGlvbiBBdXRob3JpdHkgLSBHMzCCASIwDQYJK=
oZI=0A=
hvcNAQEBBQADggEPADCCAQoCggEBANziXmJYHTNXOTIz+uvLh4yn1ErdBojqZI4xmKU4kB6Yz=
y5j=0A=
K/BGvESyiaHAKAxJcCGVn2TAppMSAmUmhsalifD614SgcK9PGpc/BkTVyetyEH3kMSj7HGHmK=
AdE=0A=
c5IiaacDiGydY8hS2pgn5whMcD60yRLBxWeDXTPzAxHsatBT4tG6NmCUgLthY2xbF37fQJQeq=
w3C=0A=
IShwiP/WJmxsYAQlTlV+fe+/lEjetx3dcI0FX4ilm/LC7urRQEFtYjgdVgbFA0dRIBn8exALD=
mKu=0A=
dlW/X3e+PkkBUz2YJQN2JFodtNuJ6nnltrM7P7pMKEF/BqxqjsHQ9gUdfeZChuOl1UcCAwEAA=
aNC=0A=
MEAwDwYDVR0TAQH/BAUwAwEB/zAOBgNVHQ8BAf8EBAMCAQYwHQYDVR0OBBYEFMR5yo6hTgMdH=
Nxr=0A=
2zFblD4/MH8tMA0GCSqGSIb3DQEBCwUAA4IBAQAtxRPPVoB7eni9n64smefv2t+UXglpp+dua=
Iy9=0A=
cr5HqQ6XErhK8WTTOd8lNNTBzU6B8A8ExCSzNJbGpqow32hhc9f5joWJ7w5elShKKiePEI4uf=
IbE=0A=
Ap7aDHdlDkQNkv39sxY2+hENHYwOB4lqKVb3cvTdFZx3NWZXqxNT2I7BQMXXExZacse3aQHEe=
rGD=0A=
AWh9jUGhlBjBJVz88P6DAod8DQ3PLghcSkANPuyBYeYk28rgDi0Hsj5W3I31QYUHSJsMC8tJP=
33s=0A=
t/3LjWeJGqvtux6jAAgIFyqCXDFdRootD4abdNlF+9RAsXqqaC2Gspki4cErx5z481+oghLrG=
REt=0A=
-----END CERTIFICATE-----=0A=
=0A=
thawte Primary Root CA - G2=0A=
=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=
=3D=3D=0A=
-----BEGIN CERTIFICATE-----=0A=
MIICiDCCAg2gAwIBAgIQNfwmXNmET8k9Jj1Xm67XVjAKBggqhkjOPQQDAzCBhDELMAkGA1UEB=
hMC=0A=
VVMxFTATBgNVBAoTDHRoYXd0ZSwgSW5jLjE4MDYGA1UECxMvKGMpIDIwMDcgdGhhd3RlLCBJb=
mMu=0A=
IC0gRm9yIGF1dGhvcml6ZWQgdXNlIG9ubHkxJDAiBgNVBAMTG3RoYXd0ZSBQcmltYXJ5IFJvb=
3Qg=0A=
Q0EgLSBHMjAeFw0wNzExMDUwMDAwMDBaFw0zODAxMTgyMzU5NTlaMIGEMQswCQYDVQQGEwJVU=
zEV=0A=
MBMGA1UEChMMdGhhd3RlLCBJbmMuMTgwNgYDVQQLEy8oYykgMjAwNyB0aGF3dGUsIEluYy4gL=
SBG=0A=
b3IgYXV0aG9yaXplZCB1c2Ugb25seTEkMCIGA1UEAxMbdGhhd3RlIFByaW1hcnkgUm9vdCBDQ=
SAt=0A=
IEcyMHYwEAYHKoZIzj0CAQYFK4EEACIDYgAEotWcgnuVnfFSeIf+iha/BebfowJPDQfGAFG6D=
AJS=0A=
LSKkQjnE/o/qycG+1E3/n3qe4rF8mq2nhglzh9HnmuN6papu+7qzcMBniKI11KOasf2twu8x+=
qi5=0A=
8/sIxpHR+ymVo0IwQDAPBgNVHRMBAf8EBTADAQH/MA4GA1UdDwEB/wQEAwIBBjAdBgNVHQ4EF=
gQU=0A=
mtgAMADna3+FGO6Lts6KDPgR4bswCgYIKoZIzj0EAwMDaQAwZgIxAN344FdHW6fmCsO99YCKl=
zUN=0A=
G4k8VIZ3KMqh9HneteY4sPBlcIx/AlTCv//YoT7ZzwIxAMSNlPzcU9LcnXgWHxUzI1NS41oxX=
Z3K=0A=
rr0TKUQNJ1uo52icEvdYPy5yAlejj6EULg=3D=3D=0A=
-----END CERTIFICATE-----=0A=
=0A=
thawte Primary Root CA - G3=0A=
=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=
=3D=3D=0A=
-----BEGIN CERTIFICATE-----=0A=
MIIEKjCCAxKgAwIBAgIQYAGXt0an6rS0mtZLL/eQ+zANBgkqhkiG9w0BAQsFADCBrjELMAkGA=
1UE=0A=
BhMCVVMxFTATBgNVBAoTDHRoYXd0ZSwgSW5jLjEoMCYGA1UECxMfQ2VydGlmaWNhdGlvbiBTZ=
XJ2=0A=
aWNlcyBEaXZpc2lvbjE4MDYGA1UECxMvKGMpIDIwMDggdGhhd3RlLCBJbmMuIC0gRm9yIGF1d=
Ghv=0A=
cml6ZWQgdXNlIG9ubHkxJDAiBgNVBAMTG3RoYXd0ZSBQcmltYXJ5IFJvb3QgQ0EgLSBHMzAeF=
w0w=0A=
ODA0MDIwMDAwMDBaFw0zNzEyMDEyMzU5NTlaMIGuMQswCQYDVQQGEwJVUzEVMBMGA1UEChMMd=
Ghh=0A=
d3RlLCBJbmMuMSgwJgYDVQQLEx9DZXJ0aWZpY2F0aW9uIFNlcnZpY2VzIERpdmlzaW9uMTgwN=
gYD=0A=
VQQLEy8oYykgMjAwOCB0aGF3dGUsIEluYy4gLSBGb3IgYXV0aG9yaXplZCB1c2Ugb25seTEkM=
CIG=0A=
A1UEAxMbdGhhd3RlIFByaW1hcnkgUm9vdCBDQSAtIEczMIIBIjANBgkqhkiG9w0BAQEFAAOCA=
Q8A=0A=
MIIBCgKCAQEAsr8nLPvb2FvdeHsbnndmgcs+vHyu86YnmjSjaDFxODNi5PNxZnmxqWWjpYvVj=
2At=0A=
P0LMqmsywCPLLEHd5N/8YZzic7IilRFDGF/Eth9XbAoFWCLINkw6fKXRz4aviKdEAhN0cXMKQ=
lkC=0A=
+BsUa0Lfb1+6a4KinVvnSr0eAXLbS3ToO39/fR8EtCab4LRarEc9VbjXsCZSKAExQGbY2SS99=
irY=0A=
7CFJXJv2eul/VTV+lmuNk5Mny5K76qxAwJ/C+IDPXfRa3M50hqY+bAtTyr2SzhkGcuYMXDhpx=
wTW=0A=
vGzOW/b3aJzcJRVIiKHpqfiYnODz1TEoYRFsZ5aNOZnLwkUkOQIDAQABo0IwQDAPBgNVHRMBA=
f8E=0A=
BTADAQH/MA4GA1UdDwEB/wQEAwIBBjAdBgNVHQ4EFgQUrWyqlGCc7eT/+j4KdCtjA/e2Wb8wD=
QYJ=0A=
KoZIhvcNAQELBQADggEBABpA2JVlrAmSicY59BDlqQ5mU1143vokkbvnRFHfxhY0Cu9qRFHqK=
weK=0A=
A3rD6z8KLFIWoCtDuSWQP3CpMyVtRRooOyfPqsMpQhvfO0zAMzRbQYi/aytlryjvsvXDqmbOe=
1bu=0A=
t8jLZ8HJnBoYuMTDSQPxYA5QzUbF83d597YV4Djbxy8ooAw/dyZ02SUS2jHaGh7cKUGRIjxpp=
7sC=0A=
8rZcJwOJ9Abqm+RyguOhCcHpABnTPtRwa7pxpqpYrvS76Wy274fMm7v/OeZWYdMKp8RcTGB7B=
Xcm=0A=
er/YB1IsYvdwY9k5vG8cwnncdimvzsUsZAReiDZuMdRAGmI0Nj81Aa6sY6A=3D=0A=
-----END CERTIFICATE-----=0A=
=0A=
GeoTrust Primary Certification Authority - G2=0A=
=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=
=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=0A=
-----BEGIN CERTIFICATE-----=0A=
MIICrjCCAjWgAwIBAgIQPLL0SAoA4v7rJDteYD7DazAKBggqhkjOPQQDAzCBmDELMAkGA1UEB=
hMC=0A=
VVMxFjAUBgNVBAoTDUdlb1RydXN0IEluYy4xOTA3BgNVBAsTMChjKSAyMDA3IEdlb1RydXN0I=
Elu=0A=
Yy4gLSBGb3IgYXV0aG9yaXplZCB1c2Ugb25seTE2MDQGA1UEAxMtR2VvVHJ1c3QgUHJpbWFye=
SBD=0A=
ZXJ0aWZpY2F0aW9uIEF1dGhvcml0eSAtIEcyMB4XDTA3MTEwNTAwMDAwMFoXDTM4MDExODIzN=
Tk1=0A=
OVowgZgxCzAJBgNVBAYTAlVTMRYwFAYDVQQKEw1HZW9UcnVzdCBJbmMuMTkwNwYDVQQLEzAoY=
ykg=0A=
MjAwNyBHZW9UcnVzdCBJbmMuIC0gRm9yIGF1dGhvcml6ZWQgdXNlIG9ubHkxNjA0BgNVBAMTL=
Udl=0A=
b1RydXN0IFByaW1hcnkgQ2VydGlmaWNhdGlvbiBBdXRob3JpdHkgLSBHMjB2MBAGByqGSM49A=
gEG=0A=
BSuBBAAiA2IABBWx6P0DFUPlrOuHNxFi79KDNlJ9RVcLSo17VDs6bl8VAsBQps8lL33KSLjHU=
GMc=0A=
KiEIfJo22Av+0SbFWDEwKCXzXV2juLaltJLtbCyf691DiaI8S0iRHVDsJt/WYC69IaNCMEAwD=
wYD=0A=
VR0TAQH/BAUwAwEB/zAOBgNVHQ8BAf8EBAMCAQYwHQYDVR0OBBYEFBVfNVdRVfslsq0DafwBo=
/q+=0A=
EVXVMAoGCCqGSM49BAMDA2cAMGQCMGSWWaboCd6LuvpaiIjwH5HTRqjySkwCY/tsXzjbLkGTq=
Q7m=0A=
ndwxHLKgpxgceeHHNgIwOlavmnRs9vuD4DPTCF+hnMJbn0bWtsuRBmOiBuczrD6ogRLQy7rQk=
gu2=0A=
npaqBA+K=0A=
-----END CERTIFICATE-----=0A=
=0A=
VeriSign Universal Root Certification Authority=0A=
=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=
=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=0A=
-----BEGIN CERTIFICATE-----=0A=
MIIEuTCCA6GgAwIBAgIQQBrEZCGzEyEDDrvkEhrFHTANBgkqhkiG9w0BAQsFADCBvTELMAkGA=
1UE=0A=
BhMCVVMxFzAVBgNVBAoTDlZlcmlTaWduLCBJbmMuMR8wHQYDVQQLExZWZXJpU2lnbiBUcnVzd=
CBO=0A=
ZXR3b3JrMTowOAYDVQQLEzEoYykgMjAwOCBWZXJpU2lnbiwgSW5jLiAtIEZvciBhdXRob3Jpe=
mVk=0A=
IHVzZSBvbmx5MTgwNgYDVQQDEy9WZXJpU2lnbiBVbml2ZXJzYWwgUm9vdCBDZXJ0aWZpY2F0a=
W9u=0A=
IEF1dGhvcml0eTAeFw0wODA0MDIwMDAwMDBaFw0zNzEyMDEyMzU5NTlaMIG9MQswCQYDVQQGE=
wJV=0A=
UzEXMBUGA1UEChMOVmVyaVNpZ24sIEluYy4xHzAdBgNVBAsTFlZlcmlTaWduIFRydXN0IE5ld=
Hdv=0A=
cmsxOjA4BgNVBAsTMShjKSAyMDA4IFZlcmlTaWduLCBJbmMuIC0gRm9yIGF1dGhvcml6ZWQgd=
XNl=0A=
IG9ubHkxODA2BgNVBAMTL1ZlcmlTaWduIFVuaXZlcnNhbCBSb290IENlcnRpZmljYXRpb24gQ=
XV0=0A=
aG9yaXR5MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAx2E3XrEBNNti1xWb/1haj=
CMj=0A=
1mCOkdeQmIN65lgZOIzF9uVkhbSicfvtvbnazU0AtMgtc6XHaXGVHzk8skQHnOgO+k1KxCHfK=
WGP=0A=
MiJhgsWHH26MfF8WIFFE0XBPV+rjHOPMee5Y2A7Cs0WTwCznmhcrewA3ekEzeOEz4vMQGn+HL=
L72=0A=
9fdC4uW/h2KJXwBL38Xd5HVEMkE6HnFuacsLdUYI0crSK5XQz/u5QGtkjFdN/BMReYTtXlT2N=
J8I=0A=
AfMQJQYXStrxHXpma5hgZqTZ79IugvHw7wnqRMkVauIDbjPTrJ9VAMf2CGqUuV/c4DPxhGD5W=
ycR=0A=
tPwW8rtWaoAljQIDAQABo4GyMIGvMA8GA1UdEwEB/wQFMAMBAf8wDgYDVR0PAQH/BAQDAgEGM=
G0G=0A=
CCsGAQUFBwEMBGEwX6FdoFswWTBXMFUWCWltYWdlL2dpZjAhMB8wBwYFKw4DAhoEFI/l0xqGr=
I2O=0A=
a8PPgGrUSBgsexkuMCUWI2h0dHA6Ly9sb2dvLnZlcmlzaWduLmNvbS92c2xvZ28uZ2lmMB0GA=
1Ud=0A=
DgQWBBS2d/ppSEefUxLVwuoHMnYH0ZcHGTANBgkqhkiG9w0BAQsFAAOCAQEASvj4sAPmLGd75=
JR3=0A=
Y8xuTPl9Dg3cyLk1uXBPY/ok+myDjEedO2Pzmvl2MpWRsXe8rJq+seQxIcaBlVZaDrHC1LGmW=
azx=0A=
Y8u4TB1ZkErvkBYoH1quEPuBUDgMbMzxPcP1Y+Oz4yHJJDnp/RVmRvQbEdBNc6N9Rvk97ahfY=
tTx=0A=
P/jgdFcrGJ2BtMQo2pSXpXDrrB2+BxHw1dvd5Yzw1TKwg+ZX4o+/vqGqvz0dtdQ46tewXDpPa=
j+P=0A=
wGZsY6rp2aQW9IHRlRQOfc2VNNnSj3BzgXucfr2YYdhFh5iQxeuGMMY1v/D/w1WIg0vvBZIGc=
fK4=0A=
mJO37M2CYfE45k+XmCpajQ=3D=3D=0A=
-----END CERTIFICATE-----=0A=
=0A=
VeriSign Class 3 Public Primary Certification Authority - G4=0A=
=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=
=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=
=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=0A=
-----BEGIN CERTIFICATE-----=0A=
MIIDhDCCAwqgAwIBAgIQL4D+I4wOIg9IZxIokYesszAKBggqhkjOPQQDAzCByjELMAkGA1UEB=
hMC=0A=
VVMxFzAVBgNVBAoTDlZlcmlTaWduLCBJbmMuMR8wHQYDVQQLExZWZXJpU2lnbiBUcnVzdCBOZ=
XR3=0A=
b3JrMTowOAYDVQQLEzEoYykgMjAwNyBWZXJpU2lnbiwgSW5jLiAtIEZvciBhdXRob3JpemVkI=
HVz=0A=
ZSBvbmx5MUUwQwYDVQQDEzxWZXJpU2lnbiBDbGFzcyAzIFB1YmxpYyBQcmltYXJ5IENlcnRpZ=
mlj=0A=
YXRpb24gQXV0aG9yaXR5IC0gRzQwHhcNMDcxMTA1MDAwMDAwWhcNMzgwMTE4MjM1OTU5WjCBy=
jEL=0A=
MAkGA1UEBhMCVVMxFzAVBgNVBAoTDlZlcmlTaWduLCBJbmMuMR8wHQYDVQQLExZWZXJpU2lnb=
iBU=0A=
cnVzdCBOZXR3b3JrMTowOAYDVQQLEzEoYykgMjAwNyBWZXJpU2lnbiwgSW5jLiAtIEZvciBhd=
XRo=0A=
b3JpemVkIHVzZSBvbmx5MUUwQwYDVQQDEzxWZXJpU2lnbiBDbGFzcyAzIFB1YmxpYyBQcmltY=
XJ5=0A=
IENlcnRpZmljYXRpb24gQXV0aG9yaXR5IC0gRzQwdjAQBgcqhkjOPQIBBgUrgQQAIgNiAASnV=
np8=0A=
Utpkmw4tXNherJI9/gHmGUo9FANL+mAnINmDiWn6VMaaGF5VKmTeBvaNSjutEDxlPZCIBIngM=
GGz=0A=
rl0Bp3vefLK+ymVhAIau2o970ImtTR1ZmkGxvEeA3J5iw/mjgbIwga8wDwYDVR0TAQH/BAUwA=
wEB=0A=
/zAOBgNVHQ8BAf8EBAMCAQYwbQYIKwYBBQUHAQwEYTBfoV2gWzBZMFcwVRYJaW1hZ2UvZ2lmM=
CEw=0A=
HzAHBgUrDgMCGgQUj+XTGoasjY5rw8+AatRIGCx7GS4wJRYjaHR0cDovL2xvZ28udmVyaXNpZ=
24u=0A=
Y29tL3ZzbG9nby5naWYwHQYDVR0OBBYEFLMWkf3upm7ktS5Jj4d4gYDs5bG1MAoGCCqGSM49B=
AMD=0A=
A2gAMGUCMGYhDBgmYFo4e1ZC4Kf8NoRRkSAsdk1DPcQdhCPQrNZ8NQbOzWm9kA3bbEhCHQ6qQ=
gIx=0A=
AJw9SDkjOVgaFRJZap7v1VmyHVIsmXHNxynfGyphe3HR3vPA5Q06Sqotp9iGKt0uEA=3D=3D=0A=
-----END CERTIFICATE-----=0A=
=0A=
NetLock Arany (Class Gold) F=C5=91tan=C3=BAs=C3&shy;tv=C3=A1ny=0A=
=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=
=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=0A=
-----BEGIN CERTIFICATE-----=0A=
MIIEFTCCAv2gAwIBAgIGSUEs5AAQMA0GCSqGSIb3DQEBCwUAMIGnMQswCQYDVQQGEwJIVTERM=
A8G=0A=
A1UEBwwIQnVkYXBlc3QxFTATBgNVBAoMDE5ldExvY2sgS2Z0LjE3MDUGA1UECwwuVGFuw7pzw=
610=0A=
dsOhbnlraWFkw7NrIChDZXJ0aWZpY2F0aW9uIFNlcnZpY2VzKTE1MDMGA1UEAwwsTmV0TG9ja=
yBB=0A=
cmFueSAoQ2xhc3MgR29sZCkgRsWRdGFuw7pzw610dsOhbnkwHhcNMDgxMjExMTUwODIxWhcNM=
jgx=0A=
MjA2MTUwODIxWjCBpzELMAkGA1UEBhMCSFUxETAPBgNVBAcMCEJ1ZGFwZXN0MRUwEwYDVQQKD=
AxO=0A=
ZXRMb2NrIEtmdC4xNzA1BgNVBAsMLlRhbsO6c8OtdHbDoW55a2lhZMOzayAoQ2VydGlmaWNhd=
Glv=0A=
biBTZXJ2aWNlcykxNTAzBgNVBAMMLE5ldExvY2sgQXJhbnkgKENsYXNzIEdvbGQpIEbFkXRhb=
sO6=0A=
c8OtdHbDoW55MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAxCRec75LbRTDofTjl=
5Bu=0A=
0jBFHjzuZ9lk4BqKf8owyoPjIMHj9DrTlF8afFttvzBPhCf2nx9JvMaZCpDyD/V/Q4Q3Y1GLe=
qVw=0A=
/HpYzY6b7cNGbIRwXdrzAZAj/E4wqX7hJ2Pn7WQ8oLjJM2P+FpD/sLj916jAwJRDC7bVWaaeV=
tAk=0A=
H3B5r9s5VA1lddkVQZQBr17s9o3x/61k/iCa11zr/qYfCGSji3ZVrR47KGAuhyXoqq8fxmRGI=
Ldw=0A=
fzzeSNuWU7c5d+Qa4scWhHaXWy+7GRWF+GmF9ZmnqfI0p6m2pgP8b4Y9VHx2BJtr+UBdADTHL=
pl1=0A=
neWIA6pN+APSQnbAGwIDAKiLo0UwQzASBgNVHRMBAf8ECDAGAQH/AgEEMA4GA1UdDwEB/wQEA=
wIB=0A=
BjAdBgNVHQ4EFgQUzPpnk/C2uNClwB7zU/2MU9+D15YwDQYJKoZIhvcNAQELBQADggEBAKt/7=
hwW=0A=
qZw8UQCgwBEIBaeZ5m8BiFRhbvG5GK1Krf6BQCOUL/t1fC8oS2IkgYIL9WHxHG64YTjrgfpio=
Tta=0A=
YtOUZcTh5m2C+C8lcLIhJsFyUR+MLMOEkMNaj7rP9KdlpeuY0fsFskZ1FSNqb4VjMIDw1Z4fK=
RzC=0A=
bLBQWV2QWzuoDTDPv31/zvGdg73JRm4gpvlhUbohL3u+pRVjodSVh/GeufOJ8z2FuLjbvrW5K=
fna=0A=
NwUASZQDhETnv0Mxz3WLJdH0pmT1kvarBes96aULNmLazAZfNou2XjG4Kvte9nHfRCaexOYNk=
bQu=0A=
dZWAUWpLMKawYqGT8ZvYzsRjdT9ZR7E=3D=0A=
-----END CERTIFICATE-----=0A=
=0A=
Staat der Nederlanden Root CA - G2=0A=
=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=
=3D=3D=3D=3D=3D=3D=3D=3D=3D=0A=
-----BEGIN CERTIFICATE-----=0A=
MIIFyjCCA7KgAwIBAgIEAJiWjDANBgkqhkiG9w0BAQsFADBaMQswCQYDVQQGEwJOTDEeMBwGA=
1UE=0A=
CgwVU3RhYXQgZGVyIE5lZGVybGFuZGVuMSswKQYDVQQDDCJTdGFhdCBkZXIgTmVkZXJsYW5kZ=
W4g=0A=
Um9vdCBDQSAtIEcyMB4XDTA4MDMyNjExMTgxN1oXDTIwMDMyNTExMDMxMFowWjELMAkGA1UEB=
hMC=0A=
TkwxHjAcBgNVBAoMFVN0YWF0IGRlciBOZWRlcmxhbmRlbjErMCkGA1UEAwwiU3RhYXQgZGVyI=
E5l=0A=
ZGVybGFuZGVuIFJvb3QgQ0EgLSBHMjCCAiIwDQYJKoZIhvcNAQEBBQADggIPADCCAgoCggIBA=
MVZ=0A=
5291qj5LnLW4rJ4L5PnZyqtdj7U5EILXr1HgO+EASGrP2uEGQxGZqhQlEq0i6ABtQ8SpuOUfi=
Utn=0A=
vWFI7/3S4GCI5bkYYCjDdyutsDeqN95kWSpGV+RLufg3fNU254DBtvPUZ5uW6M7XxgpT0GtJl=
vOj=0A=
CwV3SPcl5XCsMBQgJeN/dVrlSPhOewMHBPqCYYdu8DvEpMfQ9XQ+pV0aCPKbJdL2rAQmPlU6Y=
iil=0A=
e7Iwr/g3wtG61jj99O9JMDeZJiFIhQGp5Rbn3JBV3w/oOM2ZNyFPXfUib2rFEhZgF1XyZWamp=
zCR=0A=
OME4HYYEhLoaJXhena/MUGDWE4dS7WMfbWV9whUYdMrhfmQpjHLYFhN9C0lK8SgbIHRrxT3ds=
KpI=0A=
CT0ugpTNGmXZK4iambwYfp/ufWZ8Pr2UuIHOzZgweMFvZ9C+X+Bo7d7iscksWXiSqt8rYGPy5=
V65=0A=
48r6f1CGPqI0GAwJaCgRHOThuVw+R7oyPxjMW4T182t0xHJ04eOLoEq9jWYv6q012iDTiIJh8=
BIi=0A=
trzQ1aTsr1SIJSQ8p22xcik/Plemf1WvbibG/ufMQFxRRIEKeN5KzlW/HdXZt1bv8Hb/C3m1r=
737=0A=
qWmRRpdogBQ2HbN/uymYNqUg+oJgYjOk7Na6B6duxc8UpufWkjTYgfX8HV2qXB72o007uPc5A=
gMB=0A=
AAGjgZcwgZQwDwYDVR0TAQH/BAUwAwEB/zBSBgNVHSAESzBJMEcGBFUdIAAwPzA9BggrBgEFB=
QcC=0A=
ARYxaHR0cDovL3d3dy5wa2lvdmVyaGVpZC5ubC9wb2xpY2llcy9yb290LXBvbGljeS1HMjAOB=
gNV=0A=
HQ8BAf8EBAMCAQYwHQYDVR0OBBYEFJFoMocVHYnitfGsNig0jQt8YojrMA0GCSqGSIb3DQEBC=
wUA=0A=
A4ICAQCoQUpnKpKBglBu4dfYszk78wIVCVBR7y29JHuIhjv5tLySCZa59sCrI2AGeYwRTlHSe=
YAz=0A=
+51IvuxBQ4EffkdAHOV6CMqqi3WtFMTC6GY8ggen5ieCWxjmD27ZUD6KQhgpxrRW/FYQoAUXv=
Qwj=0A=
f/ST7ZwaUb7dRUG/kSS0H4zpX897IZmflZ85OkYcbPnNe5yQzSipx6lVu6xiNGI1E0sUOlWDu=
YaN=0A=
kqbG9AclVMwWVxJKgnjIFNkXgiYtXSAfea7+1HAWFpWD2DU5/1JddRwWxRNVz0fMdWVSSt7ws=
Kfk=0A=
CpYL+63C4iWEst3kvX5ZbJvw8NjnyvLplzh+ib7M+zkXYT9y2zqR2GUBGR2tUKRXCnxLvJxxc=
ypF=0A=
URmFzI79R6d0lR2o0a9OF7FpJsKqeFdbxU2n5Z4FF5TKsl+gSRiNNOkmbEgeqmiSBeGCc1qb3=
Adb=0A=
CG19ndeNIdn8FCCqwkXfP+cAslHkwvgFuXkajDTznlvkN1trSt8sV4pAWja63XVECDdCcAz+3=
F4h=0A=
oKOKwJCcaNpQ5kUQR3i2TtJlycM33+FCY7BXN0Ute4qcvwXqZVUz9zkQxSgqIXobisQk+T8Vy=
JoV=0A=
IPVVYpbtbZNQvOSqeK3Zywplh6ZmwcSBo3c6WB4L7oOLnR7SUqTMHW+wmG2UMbX4cQrcufx9M=
mDm=0A=
66+KAQ=3D=3D=0A=
-----END CERTIFICATE-----=0A=
=0A=
CA Disig=0A=
=3D=3D=3D=3D=3D=3D=3D=3D=0A=
-----BEGIN CERTIFICATE-----=0A=
MIIEDzCCAvegAwIBAgIBATANBgkqhkiG9w0BAQUFADBKMQswCQYDVQQGEwJTSzETMBEGA1UEB=
xMK=0A=
QnJhdGlzbGF2YTETMBEGA1UEChMKRGlzaWcgYS5zLjERMA8GA1UEAxMIQ0EgRGlzaWcwHhcNM=
DYw=0A=
MzIyMDEzOTM0WhcNMTYwMzIyMDEzOTM0WjBKMQswCQYDVQQGEwJTSzETMBEGA1UEBxMKQnJhd=
Glz=0A=
bGF2YTETMBEGA1UEChMKRGlzaWcgYS5zLjERMA8GA1UEAxMIQ0EgRGlzaWcwggEiMA0GCSqGS=
Ib3=0A=
DQEBAQUAA4IBDwAwggEKAoIBAQCS9jHBfYj9mQGp2HvycXXxMcbzdWb6UShGhJd4NLxs/LxFW=
Ygm=0A=
GErENx+hSkS943EE9UQX4j/8SFhvXJ56CbpRNyIjZkMhsDxkovhqFQ4/61HhVKndBpnXmjxUi=
zkD=0A=
Pw/Fzsbrg3ICqB9x8y34dQjbYkzo+s7552oftms1grrijxaSfQUMbEYDXcDtab86wYqg6I7Zu=
UUo=0A=
hwjstMoVvoLdtUSLLa2GDGhibYVW8qwUYzrG0ZmsNHhWS8+2rT+MitcE5eN4TPWGqvWP+j1sc=
aMt=0A=
ymfraHtuM6kMgiioTGohQBUgDCZbg8KpFhXAJIJdKxatymP2dACw30PEEGBWZ2NFAgMBAAGjg=
f8w=0A=
gfwwDwYDVR0TAQH/BAUwAwEB/zAdBgNVHQ4EFgQUjbJJaJ1yCCW5wCf1UJNWSEZx+Y8wDgYDV=
R0P=0A=
AQH/BAQDAgEGMDYGA1UdEQQvMC2BE2Nhb3BlcmF0b3JAZGlzaWcuc2uGFmh0dHA6Ly93d3cuZ=
Glz=0A=
aWcuc2svY2EwZgYDVR0fBF8wXTAtoCugKYYnaHR0cDovL3d3dy5kaXNpZy5zay9jYS9jcmwvY=
2Ff=0A=
ZGlzaWcuY3JsMCygKqAohiZodHRwOi8vY2EuZGlzaWcuc2svY2EvY3JsL2NhX2Rpc2lnLmNyb=
DAa=0A=
BgNVHSAEEzARMA8GDSuBHpGT5goAAAABAQEwDQYJKoZIhvcNAQEFBQADggEBAF00dGFMrzvY/=
59t=0A=
WDYcPQuBDRIrRhCA/ec8J9B6yKm2fnQwM6M6int0wHl5QpNt/7EpFIKrIYwvF/k/Ji/1Wcbvg=
Aa3=0A=
mkkp7M5+cTxqEEHA9tOasnxakZzArFvITV734VP/Q3f8nktnbNfzg9Gg4H8l37iYC5oyOGwwo=
PP/=0A=
CBUz91BKez6jPiCp3C9WgArtQVCwyfTssuMmRAAOb54GvCKWU3BlxFAKRmukLyeBEicTXxChd=
s6K=0A=
ezfqwzlhA5WYOudsiCUI/HloDYd9Yvi0X/vF2Ey9WLw/Q1vUHgFNPGO+I++MzVpQuGhU+QqZM=
xEA=0A=
4Z7CRneC9VkGjCFMhwnN5ag=3D=0A=
-----END CERTIFICATE-----=0A=
=0A=
Juur-SK=0A=
=3D=3D=3D=3D=3D=3D=3D=0A=
-----BEGIN CERTIFICATE-----=0A=
MIIE5jCCA86gAwIBAgIEO45L/DANBgkqhkiG9w0BAQUFADBdMRgwFgYJKoZIhvcNAQkBFglwa=
2lA=0A=
c2suZWUxCzAJBgNVBAYTAkVFMSIwIAYDVQQKExlBUyBTZXJ0aWZpdHNlZXJpbWlza2Vza3VzM=
RAw=0A=
DgYDVQQDEwdKdXVyLVNLMB4XDTAxMDgzMDE0MjMwMVoXDTE2MDgyNjE0MjMwMVowXTEYMBYGC=
SqG=0A=
SIb3DQEJARYJcGtpQHNrLmVlMQswCQYDVQQGEwJFRTEiMCAGA1UEChMZQVMgU2VydGlmaXRzZ=
WVy=0A=
aW1pc2tlc2t1czEQMA4GA1UEAxMHSnV1ci1TSzCCASIwDQYJKoZIhvcNAQEBBQADggEPADCCA=
QoC=0A=
ggEBAIFxNj4zB9bjMI0TfncyRsvPGbJgMUaXhvSYRqTCZUXP00B841oiqBB4M8yIsdOBSvZiF=
3tf=0A=
TQou0M+LI+5PAk676w7KvRhj6IAcjeEcjT3g/1tf6mTll+g/mX8MCgkzABpTpyHhOEvWgxutr=
2TC=0A=
+Rx6jGZITWYfGAriPrsfB2WThbkasLnE+w0R9vXW+RvHLCu3GFH+4Hv2qEivbDtPL+/40UceJ=
lfw=0A=
UR0zlv/vWT3aTdEVNMfqPxZIe5EcgEMPPbgFPtGzlc3Yyg/CQ2fbt5PgIoIuvvVoKIO5wTtpe=
yDa=0A=
Tpxt4brNj3pssAki14sL2xzVWiZbDcDq5WDQn/413z8CAwEAAaOCAawwggGoMA8GA1UdEwEB/=
wQF=0A=
MAMBAf8wggEWBgNVHSAEggENMIIBCTCCAQUGCisGAQQBzh8BAQEwgfYwgdAGCCsGAQUFBwICM=
IHD=0A=
HoHAAFMAZQBlACAAcwBlAHIAdABpAGYAaQBrAGEAYQB0ACAAbwBuACAAdgDkAGwAagBhAHMAd=
ABh=0A=
AHQAdQBkACAAQQBTAC0AaQBzACAAUwBlAHIAdABpAGYAaQB0AHMAZQBlAHIAaQBtAGkAcwBrA=
GUA=0A=
cwBrAHUAcwAgAGEAbABhAG0ALQBTAEsAIABzAGUAcgB0AGkAZgBpAGsAYQBhAHQAaQBkAGUAI=
ABr=0A=
AGkAbgBuAGkAdABhAG0AaQBzAGUAawBzMCEGCCsGAQUFBwIBFhVodHRwOi8vd3d3LnNrLmVlL=
2Nw=0A=
cy8wKwYDVR0fBCQwIjAgoB6gHIYaaHR0cDovL3d3dy5zay5lZS9qdXVyL2NybC8wHQYDVR0OB=
BYE=0A=
FASqekej5ImvGs8KQKcYP2/v6X2+MB8GA1UdIwQYMBaAFASqekej5ImvGs8KQKcYP2/v6X2+M=
A4G=0A=
A1UdDwEB/wQEAwIB5jANBgkqhkiG9w0BAQUFAAOCAQEAe8EYlFOiCfP+JmeaUOTDBS8rNXiRT=
Hyo=0A=
ERF5TElZrMj3hWVcRrs7EKACr81Ptcw2Kuxd/u+gkcm2k298gFTsxwhwDY77guwqYHhpNjbRx=
ZyL=0A=
abVAyJRld/JXIWY7zoVAtjNjGr95HvxcHdMdkxuLDF2FvZkwMhgJkVLpfKG6/2SSmuz+Ne6ML=
678=0A=
IIbsSt4beDI3poHSna9aEhbKmVv8b20OxaAehsmR0FyYgl9jDIpaq9iVpszLita/ZEuOyoqys=
Okh=0A=
Mp6qqIWYNIE5ITuoOlIyPfZrN4YGWhWY3PARZv40ILcD9EEQfTmEeZZyY7aWAuVrua0ZTbvGR=
Ns2=0A=
yyqcjg=3D=3D=0A=
-----END CERTIFICATE-----=0A=
=0A=
Hongkong Post Root CA 1=0A=
=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=0A=
-----BEGIN CERTIFICATE-----=0A=
MIIDMDCCAhigAwIBAgICA+gwDQYJKoZIhvcNAQEFBQAwRzELMAkGA1UEBhMCSEsxFjAUBgNVB=
AoT=0A=
DUhvbmdrb25nIFBvc3QxIDAeBgNVBAMTF0hvbmdrb25nIFBvc3QgUm9vdCBDQSAxMB4XDTAzM=
DUx=0A=
NTA1MTMxNFoXDTIzMDUxNTA0NTIyOVowRzELMAkGA1UEBhMCSEsxFjAUBgNVBAoTDUhvbmdrb=
25n=0A=
IFBvc3QxIDAeBgNVBAMTF0hvbmdrb25nIFBvc3QgUm9vdCBDQSAxMIIBIjANBgkqhkiG9w0BA=
QEF=0A=
AAOCAQ8AMIIBCgKCAQEArP84tulmAknjorThkPlAj3n54r15/gK97iSSHSL22oVyaf7XPwnU3=
ZG1=0A=
ApzQjVrhVcNQhrkpJsLj2aDxaQMoIIBFIi1WpztUlVYiWR8o3x8gPW2iNr4joLFutbEnPzlTC=
eqr=0A=
auh0ssJlXI6/fMN4hM2eFvz1Lk8gKgifd/PFHsSaUmYeSF7jEAaPIpjhZY4bXSNmO7ilMlHIh=
qqh=0A=
qZ5/dpTCpmy3QfDVyAY45tQM4vM7TG1QjMSDJ8EThFk9nnV0ttgCXjqQesBCNnLsak3c78QA3=
xMY=0A=
V18meMjWCnl3v/evt3a5pQuEF10Q6m/hq5URX208o1xNg1vysxmKgIsLhwIDAQABoyYwJDASB=
gNV=0A=
HRMBAf8ECDAGAQH/AgEDMA4GA1UdDwEB/wQEAwIBxjANBgkqhkiG9w0BAQUFAAOCAQEADkbVP=
K7i=0A=
h9legYsCmEEIjEy82tvuJxuC52pF7BaLT4Wg87JwvVqWuspube5Gi27nKi6Wsxkz67SfqLI37=
pio=0A=
l7Yutmcn1KZJ/RyTZXaeQi/cImyaT/JaFTmxcdcrUehtHJjA2Sr0oYJ71clBoiMBdDhViw+5L=
mei=0A=
IAQ32pwL0xch4I+XeTRvhEgCIDMb5jREn5Fw9IBehEPCKdJsEhTkYY2sEJCehFC78JZvRZ+K8=
8ps=0A=
T/oROhUVRsPNH4NbLUES7VBnQRM9IauUiqpOfMGx+6fWtScvl6tu4B3i0RwsH0Ti/L6RoZz71=
ilT=0A=
c4afU9hDDl3WY4JxHYB0yvbiAmvZWg=3D=3D=0A=
-----END CERTIFICATE-----=0A=
=0A=
SecureSign RootCA11=0A=
=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=0A=
-----BEGIN CERTIFICATE-----=0A=
MIIDbTCCAlWgAwIBAgIBATANBgkqhkiG9w0BAQUFADBYMQswCQYDVQQGEwJKUDErMCkGA1UEC=
hMi=0A=
SmFwYW4gQ2VydGlmaWNhdGlvbiBTZXJ2aWNlcywgSW5jLjEcMBoGA1UEAxMTU2VjdXJlU2lnb=
iBS=0A=
b290Q0ExMTAeFw0wOTA0MDgwNDU2NDdaFw0yOTA0MDgwNDU2NDdaMFgxCzAJBgNVBAYTAkpQM=
Ssw=0A=
KQYDVQQKEyJKYXBhbiBDZXJ0aWZpY2F0aW9uIFNlcnZpY2VzLCBJbmMuMRwwGgYDVQQDExNTZ=
WN1=0A=
cmVTaWduIFJvb3RDQTExMIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEA/XeqpRyQB=
TvL=0A=
TJszi1oURaTnkBbR31fSIRCkF/3frNYfp+TbfPfs37gD2pRY/V1yfIw/XwFndBWW4wI8h9uuy=
wGO=0A=
wvNmxoVF9ALGOrVisq/6nL+k5tSAMJjzDbaTj6nU2DbysPyKyiyhFTOVMdrAG/LuYpmGYz+/3=
ZMq=0A=
g6h2uRMft85OQoWPIucuGvKVCbIFtUROd6EgvanyTgp9UK31BQ1FT0Zx/Sg+U/sE2C3XZR1KG=
/rP=0A=
O7AxmjVuyIsG0wCR8pQIZUyxNAYAeoni8McDWc/V1uinMrPmmECGxc0nEovMe863ETxiYAcjP=
itA=0A=
bpSACW22s293bzUIUPsCh8U+iQIDAQABo0IwQDAdBgNVHQ4EFgQUW/hNT7KlhtQ60vFjmqC+C=
fZX=0A=
t94wDgYDVR0PAQH/BAQDAgEGMA8GA1UdEwEB/wQFMAMBAf8wDQYJKoZIhvcNAQEFBQADggEBA=
KCh=0A=
OBZmLqdWHyGcBvod7bkixTgm2E5P7KN/ed5GIaGHd48HCJqypMWvDzKYC3xmKbabfSVSSUOrT=
C4r=0A=
bnpwrxYO4wJs+0LmGJ1F2FXI6Dvd5+H0LgscNFxsWEr7jIhQX5Ucv+2rIrVls4W6ng+4reV6G=
4pQ=0A=
Oh29Dbx7VFALuUKvVaAYga1lme++5Jy/xIWrQbJUb9wlze144o4MjQlJ3WN7WmmWAiGovVJZ6=
X01=0A=
y8hSyn+B/tlr0/cR7SXf+Of5pPpyl4RTDaXQMhhRdlkUbA/r7F+AjHVDg8OFmP9Mni0N5HeDk=
061=0A=
lgeLKBObjBmNQSdJQO7e5iNEOdyhIta6A/I=3D=0A=
-----END CERTIFICATE-----=0A=
=0A=
ACEDICOM Root=0A=
=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=0A=
-----BEGIN CERTIFICATE-----=0A=
MIIFtTCCA52gAwIBAgIIYY3HhjsBggUwDQYJKoZIhvcNAQEFBQAwRDEWMBQGA1UEAwwNQUNFR=
ElD=0A=
T00gUm9vdDEMMAoGA1UECwwDUEtJMQ8wDQYDVQQKDAZFRElDT00xCzAJBgNVBAYTAkVTMB4XD=
TA4=0A=
MDQxODE2MjQyMloXDTI4MDQxMzE2MjQyMlowRDEWMBQGA1UEAwwNQUNFRElDT00gUm9vdDEMM=
AoG=0A=
A1UECwwDUEtJMQ8wDQYDVQQKDAZFRElDT00xCzAJBgNVBAYTAkVTMIICIjANBgkqhkiG9w0BA=
QEF=0A=
AAOCAg8AMIICCgKCAgEA/5KV4WgGdrQsyFhIyv2AVClVYyT/kGWbEHV7w2rbYgIB8hiGtXxaO=
LHk=0A=
WLn709gtn70yN78sFW2+tfQh0hOR2QetAQXW8713zl9CgQr5auODAKgrLlUTY4HKRxx7XBZXe=
huD=0A=
YAQ6PmXDzQHe3qTWDLqO3tkE7hdWIpuPY/1NFgu3e3eM+SW10W2ZEi5PGrjm6gSSrj0RuVFCP=
Yew=0A=
MYWveVqc/udOXpJPQ/yrOq2lEiZmueIM15jO1FillUAKt0SdE3QrwqXrIhWYENiLxQSfHY9g5=
QYb=0A=
m8+5eaA9oiM/Qj9r+hwDezCNzmzAv+YbX79nuIQZ1RXve8uQNjFiybwCq0Zfm/4aaJQ0PZCOr=
fbk=0A=
HQl/Sog4P75n/TSW9R28MHTLOO7VbKvU/PQAtwBbhTIWdjPp2KOZnQUAqhbm84F9b32qhm2tF=
XTT=0A=
xKJxqvQUfecyuB+81fFOvW8XAjnXDpVCOscAPukmYxHqC9FK/xidstd7LzrZlvvoHpKuE1XI2=
Sf2=0A=
3EgbsCTBheN3nZqk8wwRHQ3ItBTutYJXCb8gWH8vIiPYcMt5bMlL8qkqyPyHK9caUPgn6C9D4=
zq9=0A=
2Fdx/c6mUlv53U3t5fZvie27k5x2IXXwkkwp9y+cAS7+UEaeZAwUswdbxcJzbPEHXEUkFDWug=
/Fq=0A=
TYl6+rPYLWbwNof1K1MCAwEAAaOBqjCBpzAPBgNVHRMBAf8EBTADAQH/MB8GA1UdIwQYMBaAF=
Kaz=0A=
4SsrSbbXc6GqlPUB53NlTKxQMA4GA1UdDwEB/wQEAwIBhjAdBgNVHQ4EFgQUprPhKytJttdzo=
aqU=0A=
9QHnc2VMrFAwRAYDVR0gBD0wOzA5BgRVHSAAMDEwLwYIKwYBBQUHAgEWI2h0dHA6Ly9hY2Vka=
WNv=0A=
bS5lZGljb21ncm91cC5jb20vZG9jMA0GCSqGSIb3DQEBBQUAA4ICAQDOLAtSUWImfQwng4/F9=
tqg=0A=
aHtPkl7qpHMyEVNEskTLnewPeUKzEKbHDZ3Ltvo/Onzqv4hTGzz3gvoFNTPhNahXwOf9jU8/k=
zJP=0A=
eGYDdwdY6ZXIfj7QeQCM8htRM5u8lOk6e25SLTKeI6RF+7YuE7CLGLHdztUdp0J/Vb77W7tH1=
Pwk=0A=
zQSulgUV1qzOMPPKC8W64iLgpq0i5ALudBF/TP94HTXa5gI06xgSYXcGCRZj6hitoocf8seAC=
Ql1=0A=
ThCojz2GuHURwCRiipZ7SkXp7FnFvmuD5uHorLUwHv4FB4D54SMNUI8FmP8sX+g7tq3PgbUhh=
8oI=0A=
KiMnMCArz+2UW6yyetLHKKGKC5tNSixthT8Jcjxn4tncB7rrZXtaAWPWkFtPF2Y9fwsZo5NjE=
FIq=0A=
nxQWWOLcpfShFosOkYuByptZ+thrkQdlVV9SH686+5DdaaVbnG0OLLb6zqylfDJKZ0DcMDQj3=
dcE=0A=
I2bw/FWAp/tmGYI1Z2JwOV5vx+qQQEQIHriy1tvuWacNGHk0vFQYXlPKNFHtRQrmjseCNj6nO=
GOp=0A=
MCwXEGCSn1WHElkQwg9naRHMTh5+Spqtr0CodaxWkHS4oJyleW/c6RrIaQXpuvoDs3zk4E7Cz=
p3o=0A=
tkYNbn5XOmeUwssfnHdKZ05phkOTOPu220+DkdRgfks+KzgHVZhepA=3D=3D=0A=
-----END CERTIFICATE-----=0A=
=0A=
Microsec e-Szigno Root CA 2009=0A=
=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=
=3D=3D=3D=3D=3D=0A=
-----BEGIN CERTIFICATE-----=0A=
MIIECjCCAvKgAwIBAgIJAMJ+QwRORz8ZMA0GCSqGSIb3DQEBCwUAMIGCMQswCQYDVQQGEwJIV=
TER=0A=
MA8GA1UEBwwIQnVkYXBlc3QxFjAUBgNVBAoMDU1pY3Jvc2VjIEx0ZC4xJzAlBgNVBAMMHk1pY=
3Jv=0A=
c2VjIGUtU3ppZ25vIFJvb3QgQ0EgMjAwOTEfMB0GCSqGSIb3DQEJARYQaW5mb0BlLXN6aWdub=
y5o=0A=
dTAeFw0wOTA2MTYxMTMwMThaFw0yOTEyMzAxMTMwMThaMIGCMQswCQYDVQQGEwJIVTERMA8GA=
1UE=0A=
BwwIQnVkYXBlc3QxFjAUBgNVBAoMDU1pY3Jvc2VjIEx0ZC4xJzAlBgNVBAMMHk1pY3Jvc2VjI=
GUt=0A=
U3ppZ25vIFJvb3QgQ0EgMjAwOTEfMB0GCSqGSIb3DQEJARYQaW5mb0BlLXN6aWduby5odTCCA=
SIw=0A=
DQYJKoZIhvcNAQEBBQADggEPADCCAQoCggEBAOn4j/NjrdqG2KfgQvvPkd6mJviZpWNwrZuuy=
jNA=0A=
fW2WbqEORO7hE52UQlKavXWFdCyoDh2Tthi3jCyoz/tccbna7P7ofo/kLx2yqHWH2Leh5TvPm=
UpG=0A=
0IMZfcChEhyVbUr02MelTTMuhTlAdX4UfIASmFDHQWe4oIBhVKZsTh/gnQ4H6cm6M+f+wFUoL=
AKA=0A=
pxn1ntxVUwOXewdI/5n7N4okxFnMUBBjjqqpGrCEGob5X7uxUG6k0QrM1XF+H6cbfPVTbiJfy=
yvm=0A=
1HxdrtbCxkzlBQHZ7Vf8wSN5/PrIJIOV87VqUQHQd9bpEqH5GoP7ghu5sJf0dgYzQ0mg/wu1+=
rUC=0A=
AwEAAaOBgDB+MA8GA1UdEwEB/wQFMAMBAf8wDgYDVR0PAQH/BAQDAgEGMB0GA1UdDgQWBBTLD=
8bf=0A=
QkPMPcu1SCOhGnqmKrs0aDAfBgNVHSMEGDAWgBTLD8bfQkPMPcu1SCOhGnqmKrs0aDAbBgNVH=
REE=0A=
FDASgRBpbmZvQGUtc3ppZ25vLmh1MA0GCSqGSIb3DQEBCwUAA4IBAQDJ0Q5eLtXMs3w+y/w9/=
w0o=0A=
lZMEyL/azXm4Q5DwpL7v8u8hmLzU1F0G9u5C7DBsoKqpyvGvivo/C3NqPuouQH4frlRheesuC=
DfX=0A=
I/OMn74dseGkddug4lQUsbocKaQY9hK6ohQU4zE1yED/t+AFdlfBHFny+L/k7SViXITwfn4fs=
775=0A=
tyERzAMBVnCnEJIeGzSBHq2cGsMEPO0CYdYeBvNfOofyK/FFh+U9rNHHV4S9a67c2Pm2G2JwC=
z02=0A=
yULyMtd6YebS2z3PyKnJm9zbWETXbzivf3jTo60adbocwTZ8jx5tHMN1Rq41Bab2XD0h7lbwy=
YIi=0A=
LXpUq3DDfSJlgnCW=0A=
-----END CERTIFICATE-----=0A=
=0A=
GlobalSign Root CA - R3=0A=
=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=0A=
-----BEGIN CERTIFICATE-----=0A=
MIIDXzCCAkegAwIBAgILBAAAAAABIVhTCKIwDQYJKoZIhvcNAQELBQAwTDEgMB4GA1UECxMXR=
2xv=0A=
YmFsU2lnbiBSb290IENBIC0gUjMxEzARBgNVBAoTCkdsb2JhbFNpZ24xEzARBgNVBAMTCkdsb=
2Jh=0A=
bFNpZ24wHhcNMDkwMzE4MTAwMDAwWhcNMjkwMzE4MTAwMDAwWjBMMSAwHgYDVQQLExdHbG9iY=
WxT=0A=
aWduIFJvb3QgQ0EgLSBSMzETMBEGA1UEChMKR2xvYmFsU2lnbjETMBEGA1UEAxMKR2xvYmFsU=
2ln=0A=
bjCCASIwDQYJKoZIhvcNAQEBBQADggEPADCCAQoCggEBAMwldpB5BngiFvXAg7aEyiie/QV2E=
cWt=0A=
iHL8RgJDx7KKnQRfJMsuS+FggkbhUqsMgUdwbN1k0ev1LKMPgj0MK66X17YUhhB5uzsTgHeMC=
OFJ=0A=
0mpiLx9e+pZo34knlTifBtc+ycsmWQ1z3rDI6SYOgxXG71uL0gRgykmmKPZpO/bLyCiR5Z2KY=
Vc3=0A=
rHQU3HTgOu5yLy6c+9C7v/U9AOEGM+iCK65TpjoWc4zdQQ4gOsC0p6Hpsk+QLjJg6VfLuQSSa=
Gjl=0A=
OCZgdbKfd/+RFO+uIEn8rUAVSNECMWEZXriX7613t2Saer9fwRPvm2L7DWzgVGkWqQPabumDk=
3F2=0A=
xmmFghcCAwEAAaNCMEAwDgYDVR0PAQH/BAQDAgEGMA8GA1UdEwEB/wQFMAMBAf8wHQYDVR0OB=
BYE=0A=
FI/wS3+oLkUkrk1Q+mOai97i3Ru8MA0GCSqGSIb3DQEBCwUAA4IBAQBLQNvAUKr+yAzv95ZUR=
Um7=0A=
lgAJQayzE4aGKAczymvmdLm6AC2upArT9fHxD4q/c2dKg8dEe3jgr25sbwMpjjM5RcOO5LlXb=
Kr8=0A=
EpbsU8Yt5CRsuZRj+9xTaGdWPoO4zzUhw8lo/s7awlOqzJCK6fBdRoyV3XpYKBovHd7NADdBj=
+1E=0A=
bddTKJd+82cEHhXXipa0095MJ6RMG3NzdvQXmcIfeg7jLQitChws/zyrVQ4PkX4268NXSb7hL=
i18=0A=
YIvDQVETI53O9zJrlAGomecsMx86OyXShkDOOyyGeMlhLxS67ttVb9+E7gUJTb0o2HLO02JQZ=
R7r=0A=
kpeDMdmztcpHWD9f=0A=
-----END CERTIFICATE-----=0A=
=0A=
Autoridad de Certificacion Firmaprofesional CIF A62634068=0A=
=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=
=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=
=3D=3D=3D=3D=3D=3D=3D=0A=
-----BEGIN CERTIFICATE-----=0A=
MIIGFDCCA/ygAwIBAgIIU+w77vuySF8wDQYJKoZIhvcNAQEFBQAwUTELMAkGA1UEBhMCRVMxQ=
jBA=0A=
BgNVBAMMOUF1dG9yaWRhZCBkZSBDZXJ0aWZpY2FjaW9uIEZpcm1hcHJvZmVzaW9uYWwgQ0lGI=
EE2=0A=
MjYzNDA2ODAeFw0wOTA1MjAwODM4MTVaFw0zMDEyMzEwODM4MTVaMFExCzAJBgNVBAYTAkVTM=
UIw=0A=
QAYDVQQDDDlBdXRvcmlkYWQgZGUgQ2VydGlmaWNhY2lvbiBGaXJtYXByb2Zlc2lvbmFsIENJR=
iBB=0A=
NjI2MzQwNjgwggIiMA0GCSqGSIb3DQEBAQUAA4ICDwAwggIKAoICAQDKlmuO6vj78aI14H9M2=
uDD=0A=
Utd9thDIAl6zQyrET2qyyhxdKJp4ERppWVevtSBC5IsP5t9bpgOSL/UR5GLXMnE42QQMcas9U=
X4P=0A=
B99jBVzpv5RvwSmCwLTaUbDBPLutN0pcyvFLNg4kq7/DhHf9qFD0sefGL9ItWY16Ck6WaVICq=
jaY=0A=
7Pz6FIMMNx/Jkjd/14Et5cS54D40/mf0PmbR0/RAz15iNA9wBj4gGFrO93IbJWyTdBSTo3OxD=
qqH=0A=
ECNZXyAFGUftaI6SEspd/NYrspI8IM/hX68gvqB2f3bl7BqGYTM+53u0P6APjqK5am+5hyZvQ=
WyI=0A=
plD9amML9ZMWGxmPsu2bm8mQ9QEM3xk9Dz44I8kvjwzRAv4bVdZO0I08r0+k8/6vKtMFnXkIo=
ctX=0A=
MbScyJCyZ/QYFpM6/EfY0XiWMR+6KwxfXZmtY4laJCB22N/9q06mIqqdXuYnin1oKaPnirjaE=
bsX=0A=
LZmdEyRG98Xi2J+Of8ePdG1asuhy9azuJBCtLxTa/y2aRnFHvkLfuwHb9H/TKI8xWVvTyQKmt=
FLK=0A=
bpf7Q8UIJm+K9Lv9nyiqDdVF8xM6HdjAeI9BZzwelGSuewvF6NkBiDkal4ZkQdU7hwxu+g/Gv=
UgU=0A=
vzlN1J5Bto+WHWOWk9mVBngxaJ43BjuAiUVhOSPHG0SjFeUc+JIwuwIDAQABo4HvMIHsMBIGA=
1Ud=0A=
EwEB/wQIMAYBAf8CAQEwDgYDVR0PAQH/BAQDAgEGMB0GA1UdDgQWBBRlzeurNR4APn7VdMAct=
HNH=0A=
DhpkLzCBpgYDVR0gBIGeMIGbMIGYBgRVHSAAMIGPMC8GCCsGAQUFBwIBFiNodHRwOi8vd3d3L=
mZp=0A=
cm1hcHJvZmVzaW9uYWwuY29tL2NwczBcBggrBgEFBQcCAjBQHk4AUABhAHMAZQBvACAAZABlA=
CAA=0A=
bABhACAAQgBvAG4AYQBuAG8AdgBhACAANAA3ACAAQgBhAHIAYwBlAGwAbwBuAGEAIAAwADgAM=
AAx=0A=
ADcwDQYJKoZIhvcNAQEFBQADggIBABd9oPm03cXF661LJLWhAqvdpYhKsg9VSytXjDvlMd3+x=
DLx=0A=
51tkljYyGOylMnfX40S2wBEqgLk9am58m9Ot/MPWo+ZkKXzR4Tgegiv/J2Wv+xYVxC5xhOW1/=
/qk=0A=
R71kMrv2JYSiJ0L1ILDCExARzRAVukKQKtJE4ZYm6zFIEv0q2skGz3QeqUvVhyj5eTSSPi5E6=
PaP=0A=
T481PyWzOdxjKpBrIF/EUhJOlywqrJ2X3kjyo2bbwtKDlaZmp54lD+kLM5FlClrD2VQS3a/DT=
g4f=0A=
Jl4N3LON7NWBcN7STyQF82xO9UxJZo3R/9ILJUFI/lGExkKvgATP0H5kSeTy36LssUzAKh3nt=
LFl=0A=
osS88Zj0qnAHY7S42jtM+kAiMFsRpvAFDsYCA0irhpuF3dvd6qJ2gHN99ZwExEWN57kci57q1=
3XR=0A=
crHedUTnQn3iV2t93Jm8PYMo6oCTjcVMZcFwgbg4/EMxsvYDNEeyrPsiBsse3RdHHF9mudMao=
toR=0A=
saS8I8nkvof/uZS2+F0gStRf571oe2XyFR7SOqkt6dhrJKyXWERHrVkY8SFlcN7ONGCoQPHzP=
KTD=0A=
KCOM/iczQ0CgFzzr6juwcqajuUpLXhZI9LK8yIySxZ2frHI2vDSANGupi5LAuBft7HZT9SQBj=
LMi=0A=
6Et8Vcad+qMUu2WFbm5PEn4KPJ2V=0A=
-----END CERTIFICATE-----=0A=
=0A=
Izenpe.com=0A=
=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=0A=
-----BEGIN CERTIFICATE-----=0A=
MIIF8TCCA9mgAwIBAgIQALC3WhZIX7/hy/WL1xnmfTANBgkqhkiG9w0BAQsFADA4MQswCQYDV=
QQG=0A=
EwJFUzEUMBIGA1UECgwLSVpFTlBFIFMuQS4xEzARBgNVBAMMCkl6ZW5wZS5jb20wHhcNMDcxM=
jEz=0A=
MTMwODI4WhcNMzcxMjEzMDgyNzI1WjA4MQswCQYDVQQGEwJFUzEUMBIGA1UECgwLSVpFTlBFI=
FMu=0A=
QS4xEzARBgNVBAMMCkl6ZW5wZS5jb20wggIiMA0GCSqGSIb3DQEBAQUAA4ICDwAwggIKAoICA=
QDJ=0A=
03rKDx6sp4boFmVqscIbRTJxldn+EFvMr+eleQGPicPK8lVx93e+d5TzcqQsRNiekpsUOqHnJ=
JAK=0A=
ClaOxdgmlOHZSOEtPtoKct2jmRXagaKH9HtuJneJWK3W6wyyQXpzbm3benhB6QiIEn6HLmYRY=
2xU=0A=
+zydcsC8Lv/Ct90NduM61/e0aL6i9eOBbsFGb12N4E3GVFWJGjMxCrFXuaOKmMPsOzTFlUFpf=
nXC=0A=
PCDFYbpRR6AgkJOhkEvzTnyFRVSa0QUmQbC1TR0zvsQDyCV8wXDbO/QJLVQnSKwv4cSsPsjLk=
kxT=0A=
OTcj7NMB+eAJRE1NZMDhDVqHIrytG6P+JrUV86f8hBnp7KGItERphIPzidF0BqnMC9bC3ieFU=
CbK=0A=
F7jJeodWLBoBHmy+E60QrLUk9TiRodZL2vG70t5HtfG8gfZZa88ZU+mNFctKy6lvROUbQc/hh=
qfK=0A=
0GqfvEyNBjNaooXlkDWgYlwWTvDjovoDGrQscbNYLN57C9saD+veIR8GdwYDsMnvmfzAuU8Lh=
ij+=0A=
0rnq49qlw0dpEuDb8PYZi+17cNcC1u2HGCgsBCRMd+RIihrGO5rUD8r6ddIBQFqNeb+Lz0vPq=
hbB=0A=
leStTIo+F5HUsWLlguWABKQDfo2/2n+iD5dPDNMN+9fR5XJ+HMh3/1uaD7euBUbl8agW7EekF=
wID=0A=
AQABo4H2MIHzMIGwBgNVHREEgagwgaWBD2luZm9AaXplbnBlLmNvbaSBkTCBjjFHMEUGA1UEC=
gw+=0A=
SVpFTlBFIFMuQS4gLSBDSUYgQTAxMzM3MjYwLVJNZXJjLlZpdG9yaWEtR2FzdGVpeiBUMTA1N=
SBG=0A=
NjIgUzgxQzBBBgNVBAkMOkF2ZGEgZGVsIE1lZGl0ZXJyYW5lbyBFdG9yYmlkZWEgMTQgLSAwM=
TAx=0A=
MCBWaXRvcmlhLUdhc3RlaXowDwYDVR0TAQH/BAUwAwEB/zAOBgNVHQ8BAf8EBAMCAQYwHQYDV=
R0O=0A=
BBYEFB0cZQ6o8iV7tJHP5LGx5r1VdGwFMA0GCSqGSIb3DQEBCwUAA4ICAQB4pgwWSp9MiDrAy=
w6l=0A=
Fn2fuUhfGI8NYjb2zRlrrKvV9pF9rnHzP7MOeIWblaQnIUdCSnxIOvVFfLMMjlF4rJUT3sb9f=
bga=0A=
kEyrkgPH7UIBzg/YsfqikuFgba56awmqxinuaElnMIAkejEWOVt+8Rwu3WwJrfIxwYJOubv5v=
r8q=0A=
hT/AQKM6WfxZSzwoJNu0FXWuDYi6LnPAvViH5ULy617uHjAimcs30cQhbIHsvm0m5hzkQiCeR=
7Cs=0A=
g1lwLDXWrzY0tM07+DKo7+N4ifuNRSzanLh+QBxh5z6ikixL8s36mLYp//Pye6kfLqCTVyveh=
QP5=0A=
aTfLnnhqBbTFMXiJ7HqnheG5ezzevh55hM6fcA5ZwjUukCox2eRFekGkLhObNA5me0mrZJfQR=
sN5=0A=
nXJQY6aYWwa9SG3YOYNw6DXwBdGqvOPbyALqfP2C2sJbUjWumDqtujWTI6cfSN01RpiyEGjkp=
THC=0A=
ClguGYEQyVB1/OpaFs4R1+7vUIgtYf8/QnMFlEPVjjxOAToZpR9GTnfQXeWBIiGH/pR9hNiTr=
dZo=0A=
Q0iy2+tzJOeRf1SktoA+naM8THLCV8Sg1Mw4J87VBp6iSNnpn86CcDaTmjvfliHjWbcM2pE38=
P1Z=0A=
WrOZyGlsQyYBNWNgVYkDOnXYukrZVP/u3oDYLdE41V4tC5h9Pmzb/CaIxw=3D=3D=0A=
-----END CERTIFICATE-----=0A=
=0A=
Chambers of Commerce Root - 2008=0A=
=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=
=3D=3D=3D=3D=3D=3D=3D=0A=
-----BEGIN CERTIFICATE-----=0A=
MIIHTzCCBTegAwIBAgIJAKPaQn6ksa7aMA0GCSqGSIb3DQEBBQUAMIGuMQswCQYDVQQGEwJFV=
TFD=0A=
MEEGA1UEBxM6TWFkcmlkIChzZWUgY3VycmVudCBhZGRyZXNzIGF0IHd3dy5jYW1lcmZpcm1hL=
mNv=0A=
bS9hZGRyZXNzKTESMBAGA1UEBRMJQTgyNzQzMjg3MRswGQYDVQQKExJBQyBDYW1lcmZpcm1hI=
FMu=0A=
QS4xKTAnBgNVBAMTIENoYW1iZXJzIG9mIENvbW1lcmNlIFJvb3QgLSAyMDA4MB4XDTA4MDgwM=
TEy=0A=
Mjk1MFoXDTM4MDczMTEyMjk1MFowga4xCzAJBgNVBAYTAkVVMUMwQQYDVQQHEzpNYWRyaWQgK=
HNl=0A=
ZSBjdXJyZW50IGFkZHJlc3MgYXQgd3d3LmNhbWVyZmlybWEuY29tL2FkZHJlc3MpMRIwEAYDV=
QQF=0A=
EwlBODI3NDMyODcxGzAZBgNVBAoTEkFDIENhbWVyZmlybWEgUy5BLjEpMCcGA1UEAxMgQ2hhb=
WJl=0A=
cnMgb2YgQ29tbWVyY2UgUm9vdCAtIDIwMDgwggIiMA0GCSqGSIb3DQEBAQUAA4ICDwAwggIKA=
oIC=0A=
AQCvAMtwNyuAWko6bHiUfaN/Gh/2NdW928sNRHI+JrKQUrpjOyhYb6WzbZSm891kDFX29ufyI=
iKA=0A=
XuFixrYp4YFs8r/lfTJqVKAyGVn+H4vXPWCGhSRv4xGzdz4gljUha7MI2XAuZPeEklPWDrCQi=
orj=0A=
h40G072QDuKZoRuGDtqaCrsLYVAGUvGef3bsyw/QHg3PmTA9HMRFEFis1tPo1+XqxQEHd9ZR5=
gN/=0A=
ikilTWh1uem8nk4ZcfUyS5xtYBkL+8ydddy/Js2Pk3g5eXNeJQ7KXOt3EgfLZEFHcpOrUMPrC=
XZk=0A=
NNI5t3YRCQ12RcSprj1qr7V9ZS+UWBDsXHyvfuK2GNnQm05aSd+pZgvMPMZ4fKecHePOjlO+B=
d5g=0A=
D2vlGts/4+EhySnB8esHnFIbAURRPHsl18TlUlRdJQfKFiC4reRB7noI/plvg6aRArBsNlVq5=
331=0A=
lubKgdaX8ZSD6e2wsWsSaR6s+12pxZjptFtYer49okQ6Y1nUCyXeG0+95QGezdIp1Z8XGQpvv=
wyQ=0A=
0wlf2eOKNcx5Wk0ZN5K3xMGtr/R5JJqyAQuxr1yW84Ay+1w9mPGgP0revq+ULtlVmhduYJ1jb=
Lhj=0A=
ya6BXBg14JC7vjxPNyK5fuvPnnchpj04gftI2jE9K+OJ9dC1vX7gUMQSibMjmhAxhduub+84M=
xh2=0A=
EQIDAQABo4IBbDCCAWgwEgYDVR0TAQH/BAgwBgEB/wIBDDAdBgNVHQ4EFgQU+SSsD7K1+HnA+=
mCI=0A=
G8TZTQKeFxkwgeMGA1UdIwSB2zCB2IAU+SSsD7K1+HnA+mCIG8TZTQKeFxmhgbSkgbEwga4xC=
zAJ=0A=
BgNVBAYTAkVVMUMwQQYDVQQHEzpNYWRyaWQgKHNlZSBjdXJyZW50IGFkZHJlc3MgYXQgd3d3L=
mNh=0A=
bWVyZmlybWEuY29tL2FkZHJlc3MpMRIwEAYDVQQFEwlBODI3NDMyODcxGzAZBgNVBAoTEkFDI=
ENh=0A=
bWVyZmlybWEgUy5BLjEpMCcGA1UEAxMgQ2hhbWJlcnMgb2YgQ29tbWVyY2UgUm9vdCAtIDIwM=
DiC=0A=
CQCj2kJ+pLGu2jAOBgNVHQ8BAf8EBAMCAQYwPQYDVR0gBDYwNDAyBgRVHSAAMCowKAYIKwYBB=
QUH=0A=
AgEWHGh0dHA6Ly9wb2xpY3kuY2FtZXJmaXJtYS5jb20wDQYJKoZIhvcNAQEFBQADggIBAJASr=
yI1=0A=
wqM58C7e6bXpeHxIvj99RZJe6dqxGfwWPJ+0W2aeaufDuV2I6A+tzyMP3iU6XsxPpcG1Lawk0=
lgH=0A=
3qLPaYRgM+gQDROpI9CF5Y57pp49chNyM/WqfcZjHwj0/gF/JM8rLFQJ3uIrbZLGOU8W6jx+e=
kbU=0A=
RWpGqOt1glanq6B8aBMz9p0w8G8nOSQjKpD9kCk18pPfNKXG9/jvjA9iSnyu0/VU+I22mlaHF=
oI6=0A=
M6taIgj3grrqLuBHmrS1RaMFO9ncLkVAO+rcf+g769HsJtg1pDDFOqxXnrN2pSB7+R5KBWIBp=
ih1=0A=
YJeSDW4+TTdDDZIVnBgizVGZoCkaPF+KMjNbMMeJL0eYD6MDxvbxrN8y8NmBGuScvfaAFPDRL=
LmF=0A=
9dijscilIeUcE5fuDr3fKanvNFNb0+RqE4QGtjICxFKuItLcsiFCGtpA8CnJ7AoMXOLQusxI0=
zcK=0A=
zBIKinmwPQN/aUv0NCB9szTqjktk9T79syNnFQ0EuPAtwQlRPLJsFfClI9eDdOTlLsn+mCdCx=
qvG=0A=
nrDQWzilm1DefhiYtUU79nm06PcaewaD+9CL2rvHvRirCG88gGtAPxkZumWK5r7VXNM21+9AU=
iRg=0A=
OGcEMeyP84LG3rlV8zsxkVrctQgVrXYlCg17LofiDKYGvCYQbTed7N14jHyAxfDZd0jQ=0A=
-----END CERTIFICATE-----=0A=
=0A=
Global Chambersign Root - 2008=0A=
=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=
=3D=3D=3D=3D=3D=0A=
-----BEGIN CERTIFICATE-----=0A=
MIIHSTCCBTGgAwIBAgIJAMnN0+nVfSPOMA0GCSqGSIb3DQEBBQUAMIGsMQswCQYDVQQGEwJFV=
TFD=0A=
MEEGA1UEBxM6TWFkcmlkIChzZWUgY3VycmVudCBhZGRyZXNzIGF0IHd3dy5jYW1lcmZpcm1hL=
mNv=0A=
bS9hZGRyZXNzKTESMBAGA1UEBRMJQTgyNzQzMjg3MRswGQYDVQQKExJBQyBDYW1lcmZpcm1hI=
FMu=0A=
QS4xJzAlBgNVBAMTHkdsb2JhbCBDaGFtYmVyc2lnbiBSb290IC0gMjAwODAeFw0wODA4MDExM=
jMx=0A=
NDBaFw0zODA3MzExMjMxNDBaMIGsMQswCQYDVQQGEwJFVTFDMEEGA1UEBxM6TWFkcmlkIChzZ=
WUg=0A=
Y3VycmVudCBhZGRyZXNzIGF0IHd3dy5jYW1lcmZpcm1hLmNvbS9hZGRyZXNzKTESMBAGA1UEB=
RMJ=0A=
QTgyNzQzMjg3MRswGQYDVQQKExJBQyBDYW1lcmZpcm1hIFMuQS4xJzAlBgNVBAMTHkdsb2Jhb=
CBD=0A=
aGFtYmVyc2lnbiBSb290IC0gMjAwODCCAiIwDQYJKoZIhvcNAQEBBQADggIPADCCAgoCggIBA=
MDf=0A=
VtPkOpt2RbQT2//BthmLN0EYlVJH6xedKYiONWwGMi5HYvNJBL99RDaxccy9Wglz1dmFRP+RV=
yXf=0A=
XjaOcNFccUMd2drvXNL7G706tcuto8xEpw2uIRU/uXpbknXYpBI4iRmKt4DS4jJvVpyR1ogQC=
7N0=0A=
ZJJ0YPP2zxhPYLIj0Mc7zmFLmY/CDNBAspjcDahOo7kKrmCgrUVSY7pmvWjg+b4aqIG7HkF4d=
dPB=0A=
/gBVsIdU6CeQNR1MM62X/JcumIS/LMmjv9GYERTtY/jKmIhYF5ntRQOXfjyGHoiMvvKRhI9lN=
NgA=0A=
TH23MRdaKXoKGCQwoze1eqkBfSbW+Q6OWfH9GzO1KTsXO0G2Id3UwD2ln58fQ1DJu7xsepeY7=
s2M=0A=
H/ucUa6LcL0nn3HAa6x9kGbo1106DbDVwo3VyJ2dwW3Q0L9R5OP4wzg2rtandeavhENdk5IMa=
gfe=0A=
Ox2YItaswTXbo6Al/3K1dh3ebeksZixShNBFks4c5eUzHdwHU1SjqoI7mjcv3N2gZOnm3b2u/=
GSF=0A=
HTynyQbehP9r6GsaPMWis0L7iwk+XwhSx2LE1AVxv8Rk5Pihg+g+EpuoHtQ2TS9x9o0o9oOpE=
9Jh=0A=
wZG7SMA0j0GMS0zbaRL/UJScIINZc+18ofLx/d33SdNDWKBWY8o9PeU1VlnpDsogzCtLkykPA=
gMB=0A=
AAGjggFqMIIBZjASBgNVHRMBAf8ECDAGAQH/AgEMMB0GA1UdDgQWBBS5CcqcHtvTbDprru1U8=
VuT=0A=
BjUuXjCB4QYDVR0jBIHZMIHWgBS5CcqcHtvTbDprru1U8VuTBjUuXqGBsqSBrzCBrDELMAkGA=
1UE=0A=
BhMCRVUxQzBBBgNVBAcTOk1hZHJpZCAoc2VlIGN1cnJlbnQgYWRkcmVzcyBhdCB3d3cuY2FtZ=
XJm=0A=
aXJtYS5jb20vYWRkcmVzcykxEjAQBgNVBAUTCUE4Mjc0MzI4NzEbMBkGA1UEChMSQUMgQ2FtZ=
XJm=0A=
aXJtYSBTLkEuMScwJQYDVQQDEx5HbG9iYWwgQ2hhbWJlcnNpZ24gUm9vdCAtIDIwMDiCCQDJz=
dPp=0A=
1X0jzjAOBgNVHQ8BAf8EBAMCAQYwPQYDVR0gBDYwNDAyBgRVHSAAMCowKAYIKwYBBQUHAgEWH=
Gh0=0A=
dHA6Ly9wb2xpY3kuY2FtZXJmaXJtYS5jb20wDQYJKoZIhvcNAQEFBQADggIBAICIf3DekijZB=
ZRG=0A=
/5BXqfEv3xoNa/p8DhxJJHkn2EaqbylZUohwEurdPfWbU1Rv4WCiqAm57OtZfMY18dwY6fFn5=
a+6=0A=
ReAJ3spED8IXDneRRXozX1+WLGiLwUePmJs9wOzL9dWCkoQ10b42OFZyMVtHLaoXpGNR6woBr=
X/s=0A=
dZ7LoR/xfxKxueRkf2fWIyr0uDldmOghp+G9PUIadJpwr2hsUF1Jz//7Dl3mLEfXgTpZALVza=
2Mg=0A=
9jFFCDkO9HB+QHBaP9BrQql0PSgvAm11cpUJjUhjxsYjV5KTXjXBjfkK9yydYhz2rXzdpjEet=
rHH=0A=
foUm+qRqtdpjMNHvkzeyZi99Bffnt0uYlDXA2TopwZ2yUDMdSqlapskD7+3056huirRXhOukP=
9Du=0A=
qqqHW2Pok+JrqNS4cnhrG+055F3Lm6qH1U9OAP7Zap88MQ8oAgF9mOinsKJknnn4SPIVqczmy=
ETr=0A=
P3iZ8ntxPjzxmKfFGBI/5rsoM0LpRQp8bfKGeS/Fghl9CYl8slR2iK7ewfPM4W7bMdaTrpmg7=
yVq=0A=
c5iJWzouE4gev8CSlDQb4ye3ix5vQv/n6TebUB0tovkC7stYWDpxvGjjqsGvHCgfotwjZT+B6=
q6Z=0A=
09gwzxMNTxXJhLynSC34MCN32EZLeW32jO06f2ARePTpm67VVMB0gNELQp/B=0A=
-----END CERTIFICATE-----=0A=
=0A=
Go Daddy Root Certificate Authority - G2=0A=
=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=
=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=0A=
-----BEGIN CERTIFICATE-----=0A=
MIIDxTCCAq2gAwIBAgIBADANBgkqhkiG9w0BAQsFADCBgzELMAkGA1UEBhMCVVMxEDAOBgNVB=
AgT=0A=
B0FyaXpvbmExEzARBgNVBAcTClNjb3R0c2RhbGUxGjAYBgNVBAoTEUdvRGFkZHkuY29tLCBJb=
mMu=0A=
MTEwLwYDVQQDEyhHbyBEYWRkeSBSb290IENlcnRpZmljYXRlIEF1dGhvcml0eSAtIEcyMB4XD=
TA5=0A=
MDkwMTAwMDAwMFoXDTM3MTIzMTIzNTk1OVowgYMxCzAJBgNVBAYTAlVTMRAwDgYDVQQIEwdBc=
ml6=0A=
b25hMRMwEQYDVQQHEwpTY290dHNkYWxlMRowGAYDVQQKExFHb0RhZGR5LmNvbSwgSW5jLjExM=
C8G=0A=
A1UEAxMoR28gRGFkZHkgUm9vdCBDZXJ0aWZpY2F0ZSBBdXRob3JpdHkgLSBHMjCCASIwDQYJK=
oZI=0A=
hvcNAQEBBQADggEPADCCAQoCggEBAL9xYgjx+lk09xvJGKP3gElY6SKDE6bFIEMBO4Tx5oVJn=
yfq=0A=
9oQbTqC023CYxzIBsQU+B07u9PpPL1kwIuerGVZr4oAH/PMWdYA5UXvl+TW2dE6pjYIT5LY/q=
QOD=0A=
+qK+ihVqf94Lw7YZFAXK6sOoBJQ7RnwyDfMAZiLIjWltNowRGLfTshxgtDj6AozO091GB94KP=
utd=0A=
fMh8+7ArU6SSYmlRJQVhGkSBjCypQ5Yj36w6gZoOKcUcqeldHraenjAKOc7xiID7S13MMuyFY=
kMl=0A=
NAJWJwGRtDtwKj9useiciAF9n9T521NtYJ2/LOdYq7hfRvzOxBsDPAnrSTFcaUaz4EcCAwEAA=
aNC=0A=
MEAwDwYDVR0TAQH/BAUwAwEB/zAOBgNVHQ8BAf8EBAMCAQYwHQYDVR0OBBYEFDqahQcQZyi27=
/a9=0A=
BUFuIMGU2g/eMA0GCSqGSIb3DQEBCwUAA4IBAQCZ21151fmXWWcDYfF+OwYxdS2hII5PZYe09=
6ac=0A=
vNjpL9DbWu7PdIxztDhC2gV7+AJ1uP2lsdeu9tfeE8tTEH6KRtGX+rcuKxGrkLAngPnon1rpN=
5+r=0A=
5N9ss4UXnT3ZJE95kTXWXwTrgIOrmgIttRD02JDHBHNA7XIloKmf7J6raBKZV8aPEjoJpL1E/=
QYV=0A=
N8Gb5DKj7Tjo2GTzLH4U/ALqn83/B2gX2yKQOC16jdFU8WnjXzPKej17CuPKf1855eJ1usV2G=
DPO=0A=
LPAvTK33sefOT6jEm0pUBsV/fdUID+Ic/n4XuKxe9tQWskMJDE32p2u0mYRlynqI4uJEvlz36=
hz1=0A=
-----END CERTIFICATE-----=0A=
=0A=
Starfield Root Certificate Authority - G2=0A=
=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=
=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=0A=
-----BEGIN CERTIFICATE-----=0A=
MIID3TCCAsWgAwIBAgIBADANBgkqhkiG9w0BAQsFADCBjzELMAkGA1UEBhMCVVMxEDAOBgNVB=
AgT=0A=
B0FyaXpvbmExEzARBgNVBAcTClNjb3R0c2RhbGUxJTAjBgNVBAoTHFN0YXJmaWVsZCBUZWNob=
m9s=0A=
b2dpZXMsIEluYy4xMjAwBgNVBAMTKVN0YXJmaWVsZCBSb290IENlcnRpZmljYXRlIEF1dGhvc=
ml0=0A=
eSAtIEcyMB4XDTA5MDkwMTAwMDAwMFoXDTM3MTIzMTIzNTk1OVowgY8xCzAJBgNVBAYTAlVTM=
RAw=0A=
DgYDVQQIEwdBcml6b25hMRMwEQYDVQQHEwpTY290dHNkYWxlMSUwIwYDVQQKExxTdGFyZmllb=
GQg=0A=
VGVjaG5vbG9naWVzLCBJbmMuMTIwMAYDVQQDEylTdGFyZmllbGQgUm9vdCBDZXJ0aWZpY2F0Z=
SBB=0A=
dXRob3JpdHkgLSBHMjCCASIwDQYJKoZIhvcNAQEBBQADggEPADCCAQoCggEBAL3twQP89o/8A=
rFv=0A=
W59I2Z154qK3A2FWGMNHttfKPTUuiUP3oWmb3ooa/RMgnLRJdzIpVv257IzdIvpy3Cdhl+72W=
oTs=0A=
bhm5iSzchFvVdPtrX8WJpRBSiUZV9Lh1HOZ/5FSuS/hVclcCGfgXcVnrHigHdMWdSL5stPSks=
PNk=0A=
N3mSwOxGXn/hbVNMYq/NHwtjuzqd+/x5AJhhdM8mgkBj87JyahkNmcrUDnXMN/uLicFZ8WJ/X=
7Nf=0A=
ZTD4p7dNdloedl40wOiWVpmKs/B/pM293DIxfJHP4F8R+GuqSVzRmZTRouNjWwl2tVZi4Ut0H=
ZbU=0A=
JtQIBFnQmA4O5t78w+wfkPECAwEAAaNCMEAwDwYDVR0TAQH/BAUwAwEB/zAOBgNVHQ8BAf8EB=
AMC=0A=
AQYwHQYDVR0OBBYEFHwMMh+n2TB/xH1oo2Kooc6rB1snMA0GCSqGSIb3DQEBCwUAA4IBAQARW=
fol=0A=
TwNvlJk7mh+ChTnUdgWUXuEok21iXQnCoKjUsHU48TRqneSfioYmUeYs0cYtbpUgSpIB7LiKZ=
3sx=0A=
4mcujJUDJi5DnUox9g61DLu34jd/IroAow57UvtruzvE03lRTs2Q9GcHGcg8RnoNAX3FWOdt5=
oUw=0A=
F5okxBDgBPfg8n/Uqgr/Qh037ZTlZFkSIHc40zI+OIF1lnP6aI+xy84fxez6nH7PfrHxBy22/=
L/K=0A=
pL/QlwVKvOoYKAKQvVR4CSFx09F9HdkWsKlhPdAKACL8x3vLCWRFCztAgfd9fDL1mMpYjn0q7=
pBZ=0A=
c2T5NnReJaH1ZgUufzkVqSr7UIuOhWn0=0A=
-----END CERTIFICATE-----=0A=
=0A=
Starfield Services Root Certificate Authority - G2=0A=
=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=
=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=0A=
-----BEGIN CERTIFICATE-----=0A=
MIID7zCCAtegAwIBAgIBADANBgkqhkiG9w0BAQsFADCBmDELMAkGA1UEBhMCVVMxEDAOBgNVB=
AgT=0A=
B0FyaXpvbmExEzARBgNVBAcTClNjb3R0c2RhbGUxJTAjBgNVBAoTHFN0YXJmaWVsZCBUZWNob=
m9s=0A=
b2dpZXMsIEluYy4xOzA5BgNVBAMTMlN0YXJmaWVsZCBTZXJ2aWNlcyBSb290IENlcnRpZmljY=
XRl=0A=
IEF1dGhvcml0eSAtIEcyMB4XDTA5MDkwMTAwMDAwMFoXDTM3MTIzMTIzNTk1OVowgZgxCzAJB=
gNV=0A=
BAYTAlVTMRAwDgYDVQQIEwdBcml6b25hMRMwEQYDVQQHEwpTY290dHNkYWxlMSUwIwYDVQQKE=
xxT=0A=
dGFyZmllbGQgVGVjaG5vbG9naWVzLCBJbmMuMTswOQYDVQQDEzJTdGFyZmllbGQgU2VydmljZ=
XMg=0A=
Um9vdCBDZXJ0aWZpY2F0ZSBBdXRob3JpdHkgLSBHMjCCASIwDQYJKoZIhvcNAQEBBQADggEPA=
DCC=0A=
AQoCggEBANUMOsQq+U7i9b4Zl1+OiFOxHz/Lz58gE20pOsgPfTz3a3Y4Y9k2YKibXlwAgLIvW=
X/2=0A=
h/klQ4bnaRtSmpDhcePYLQ1Ob/bISdm28xpWriu2dBTrz/sm4xq6HZYuajtYlIlHVv8loJNwU=
4Pa=0A=
hHQUw2eeBGg6345AWh1KTs9DkTvnVtYAcMtS7nt9rjrnvDH5RfbCYM8TWQIrgMw0R9+53pBlb=
QLP=0A=
LJGmpufehRhJfGZOozptqbXuNC66DQO4M99H67FrjSXZm86B0UVGMpZwh94CDklDhbZsc7tk6=
mFB=0A=
rMnUVN+HL8cisibMn1lUaJ/8viovxFUcdUBgF4UCVTmLfwUCAwEAAaNCMEAwDwYDVR0TAQH/B=
AUw=0A=
AwEB/zAOBgNVHQ8BAf8EBAMCAQYwHQYDVR0OBBYEFJxfAN+qAdcwKziIorhtSpzyEZGDMA0GC=
SqG=0A=
SIb3DQEBCwUAA4IBAQBLNqaEd2ndOxmfZyMIbw5hyf2E3F/YNoHN2BtBLZ9g3ccaaNnRbobhi=
CPP=0A=
E95Dz+I0swSdHynVv/heyNXBve6SbzJ08pGCL72CQnqtKrcgfU28elUSwhXqvfdqlS5sdJ/PH=
LTy=0A=
xQGjhdByPq1zqwubdQxtRbeOlKyWN7Wg0I8VRw7j6IPdj/3vQQF3zCepYoUz8jcI73HPdwbey=
Bkd=0A=
iEDPfUYd/x7H4c7/I9vG+o1VTqkC50cRRj70/b17KSa7qWFiNyi2LSr2EIZkyXCn0q23KXB56=
jza=0A=
YyWf/Wi3MOxw+3WKt21gZ7IeyLnp2KhvAotnDU0mV3HaIPzBSlCNsSi6=0A=
-----END CERTIFICATE-----=0A=
=0A=
AffirmTrust Commercial=0A=
=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=0A=
-----BEGIN CERTIFICATE-----=0A=
MIIDTDCCAjSgAwIBAgIId3cGJyapsXwwDQYJKoZIhvcNAQELBQAwRDELMAkGA1UEBhMCVVMxF=
DAS=0A=
BgNVBAoMC0FmZmlybVRydXN0MR8wHQYDVQQDDBZBZmZpcm1UcnVzdCBDb21tZXJjaWFsMB4XD=
TEw=0A=
MDEyOTE0MDYwNloXDTMwMTIzMTE0MDYwNlowRDELMAkGA1UEBhMCVVMxFDASBgNVBAoMC0FmZ=
mly=0A=
bVRydXN0MR8wHQYDVQQDDBZBZmZpcm1UcnVzdCBDb21tZXJjaWFsMIIBIjANBgkqhkiG9w0BA=
QEF=0A=
AAOCAQ8AMIIBCgKCAQEA9htPZwcroRX1BiLLHwGy43NFBkRJLLtJJRTWzsO3qyxPxkEylFf6E=
qdb=0A=
DuKPHx6GGaeqtS25Xw2Kwq+FNXkyLbscYjfysVtKPcrNcV/pQr6U6Mje+SJIZMblq8Yrba0F8=
PrV=0A=
C8+a5fBQpIs7R6UjW3p6+DM/uO+Zl+MgwdYoic+U+7lF7eNAFxHUdPALMeIrJmqbTFeurCA+u=
kV6=0A=
BfO9m2kVrn1OIGPENXY6BwLJN/3HR+7o8XYdcxXyl6S1yHp52UKqK39c/s4mT6NmgTWvRLpUH=
hww=0A=
MmWd5jyTXlBOeuM61G7MGvv50jeuJCqrVwMiKA1JdX+3KNp1v47j3A55MQIDAQABo0IwQDAdB=
gNV=0A=
HQ4EFgQUnZPGU4teyq8/nx4P5ZmVvCT2lI8wDwYDVR0TAQH/BAUwAwEB/zAOBgNVHQ8BAf8EB=
AMC=0A=
AQYwDQYJKoZIhvcNAQELBQADggEBAFis9AQOzcAN/wr91LoWXym9e2iZWEnStB03TX8nfUYGX=
UPG=0A=
hi4+c7ImfU+TqbbEKpqrIZcUsd6M06uJFdhrJNTxFq7YpFzUf1GO7RgBsZNjvbz4YYCanrHOQ=
nDi=0A=
qX0GJX0nof5v7LMeJNrjS1UaADs1tDvZ110w/YETifLCBivtZ8SOyUOyXGsViQK8YvxO8rUzq=
rJv=0A=
0wqiUOP2O+guRMLbZjipM1ZI8W0bM40NjD9gN53Tym1+NH4Nn3J2ixufcv1SNUFFApYvHLKac=
0kh=0A=
sUlHRUe072o0EclNmsxZt9YCnlpOZbWUrhvfKbAW8b8Angc6F2S1BLUjIZkKlTuXfO8=3D=0A=
-----END CERTIFICATE-----=0A=
=0A=
AffirmTrust Networking=0A=
=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=0A=
-----BEGIN CERTIFICATE-----=0A=
MIIDTDCCAjSgAwIBAgIIfE8EORzUmS0wDQYJKoZIhvcNAQEFBQAwRDELMAkGA1UEBhMCVVMxF=
DAS=0A=
BgNVBAoMC0FmZmlybVRydXN0MR8wHQYDVQQDDBZBZmZpcm1UcnVzdCBOZXR3b3JraW5nMB4XD=
TEw=0A=
MDEyOTE0MDgyNFoXDTMwMTIzMTE0MDgyNFowRDELMAkGA1UEBhMCVVMxFDASBgNVBAoMC0FmZ=
mly=0A=
bVRydXN0MR8wHQYDVQQDDBZBZmZpcm1UcnVzdCBOZXR3b3JraW5nMIIBIjANBgkqhkiG9w0BA=
QEF=0A=
AAOCAQ8AMIIBCgKCAQEAtITMMxcua5Rsa2FSoOujz3mUTOWUgJnLVWREZY9nZOIG41w3SfYvm=
4SE=0A=
Hi3yYJ0wTsyEheIszx6e/jarM3c1RNg1lho9Nuh6DtjVR6FqaYvZ/Ls6rnla1fTWcbuakCNrm=
reI=0A=
dIcMHl+5ni36q1Mr3Lt2PpNMCAiMHqIjHNRqrSK6mQEubWXLviRmVSRLQESxG9fhwoXA3hA/P=
e24=0A=
/PHxI1Pcv2WXb9n5QHGNfb2V1M6+oF4nI979ptAmDgAp6zxG8D1gvz9Q0twmQVGeFDdCBKNwV=
6gb=0A=
h+0t+nvujArjqWaJGctB+d1ENmHP4ndGyH329JKBNv3bNPFyfvMMFr20FQIDAQABo0IwQDAdB=
gNV=0A=
HQ4EFgQUBx/S55zawm6iQLSwelAQUHTEyL0wDwYDVR0TAQH/BAUwAwEB/zAOBgNVHQ8BAf8EB=
AMC=0A=
AQYwDQYJKoZIhvcNAQEFBQADggEBAIlXshZ6qML91tmbmzTCnLQyFE2npN/svqe++EPbkTfOt=
DIu=0A=
UFUaNU52Q3Eg75N3ThVwLofDwR1t3Mu1J9QsVtFSUzpE0nPIxBsFZVpikpzuQY0x2+c06lkh1=
QF6=0A=
12S4ZDnNye2v7UsDSKegmQGA3GWjNq5lWUhPgkvIZfFXHeVZLgo/bNjR9eUJtGxUAArgFU2Hd=
W23=0A=
WJZa3W3SAKD0m0i+wzekujbgfIeFlxoVot4uolu9rxj5kFDNcFn4J2dHy8egBzp90SxdbBk6Z=
rV9=0A=
/ZFvgrG+CJPbFEfxojfHRZ48x3evZKiT3/Zpg4Jg8klCNO1aAFSFHBY2kgxc+qatv9s=3D=0A=
-----END CERTIFICATE-----=0A=
=0A=
AffirmTrust Premium=0A=
=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=0A=
-----BEGIN CERTIFICATE-----=0A=
MIIFRjCCAy6gAwIBAgIIbYwURrGmCu4wDQYJKoZIhvcNAQEMBQAwQTELMAkGA1UEBhMCVVMxF=
DAS=0A=
BgNVBAoMC0FmZmlybVRydXN0MRwwGgYDVQQDDBNBZmZpcm1UcnVzdCBQcmVtaXVtMB4XDTEwM=
DEy=0A=
OTE0MTAzNloXDTQwMTIzMTE0MTAzNlowQTELMAkGA1UEBhMCVVMxFDASBgNVBAoMC0FmZmlyb=
VRy=0A=
dXN0MRwwGgYDVQQDDBNBZmZpcm1UcnVzdCBQcmVtaXVtMIICIjANBgkqhkiG9w0BAQEFAAOCA=
g8A=0A=
MIICCgKCAgEAxBLfqV/+Qd3d9Z+K4/as4Tx4mrzY8H96oDMq3I0gW64tb+eT2TZwamjPjlGjh=
Vtn=0A=
BKAQJG9dKILBl1fYSCkTtuG+kU3fhQxTGJoeJKJPj/CihQvL9Cl/0qRY7iZNyaqoe5rZ+jjeR=
FcV=0A=
5fiMyNlI4g0WJx0eyIOFJbe6qlVBzAMiSy2RjYvmia9mx+n/K+k8rNrSs8PhaJyJ+HoAVt70V=
ZVs=0A=
+7pk3WKL3wt3MutizCaam7uqYoNMtAZ6MMgpv+0GTZe5HMQxK9VfvFMSF5yZVylmd2EhMQcuJ=
Umd=0A=
GPLu8ytxjLW6OQdJd/zvLpKQBY0tL3d770O/Nbua2Plzpyzy0FfuKE4mX4+QaAkvuPjcBukum=
j5R=0A=
p9EixAqnOEhss/n/fauGV+O61oV4d7pD6kh/9ti+I20ev9E2bFhc8e6kGVQa9QPSdubhjL08s=
9NI=0A=
S+LI+H+SqHZGnEJlPqQewQcDWkYtuJfzt9WyVSHvutxMAJf7FJUnM7/oQ0dG0giZFmA7mn7S5=
u04=0A=
6uwBHjxIVkkJx0w3AJ6IDsBz4W9m6XJHMD4Q5QsDyZpCAGzFlH5hxIrff4IaC1nEWTJ3s7xga=
VY5=0A=
/bQGeyzWZDbZvUjthB9+pSKPKrhC9IK31FOQeE4tGv2Bb0TXOwF0lkLgAOIua+rF7nKsu7/+6=
qqo=0A=
+Nz2snmKtmcCAwEAAaNCMEAwHQYDVR0OBBYEFJ3AZ6YMItkm9UWrpmVSESfYRaxjMA8GA1UdE=
wEB=0A=
/wQFMAMBAf8wDgYDVR0PAQH/BAQDAgEGMA0GCSqGSIb3DQEBDAUAA4ICAQCzV00QYk465Kzqu=
Byv=0A=
MiPIs0laUZx2KI15qldGF9X1Uva3ROgIRL8YhNILgM3FEv0AVQVhh0HctSSePMTYyPtwni94l=
oMg=0A=
Nt58D2kTiKV1NpgIpsbfrM7jWNa3Pt668+s0QNiigfV4Py/VpfzZotReBA4Xrf5B8OWycvpEg=
jNC=0A=
6C1Y91aMYj+6QrCcDFx+LmUmXFNPALJ4fqENmS2NuB2OosSw/WDQMKSOyARiqcTtNd56l+0OO=
F6S=0A=
L5Nwpamcb6d9Ex1+xghIsV5n61EIJenmJWtSKZGc0jlzCFfemQa0W50QBuHCAKi4HEoCChTQw=
UHK=0A=
+4w1IX2COPKpVJEZNZOUbWo6xbLQu4mGk+ibyQ86p3q4ofB4Rvr8Ny/lioTz3/4E2aFooC8k4=
gmV=0A=
BtWVyuEklut89pMFu+1z6S3RdTnX5yTb2E5fQ4+e0BQ5v1VwSJlXMbSc7kqYA5YwH2AG7hsj/=
oFg=0A=
IxpHYoWlzBk0gG+zrBrjn/B7SK3VAdlntqlyk+otZrWyuOQ9PLLvTIzq6we/qzWaVYa8GKa1q=
F60=0A=
g2xraUDTn9zxw2lrueFtCfTxqlB2Cnp9ehehVZZCmTEJ3WARjQUwfuaORtGdFNrHF+QFlozEJ=
LUb=0A=
zxQHskD4o55BhrwE0GuWyCqANP2/7waj3VjFhT0+j/6eKeC2uAloGRwYQw=3D=3D=0A=
-----END CERTIFICATE-----=0A=
=0A=
AffirmTrust Premium ECC=0A=
=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=0A=
-----BEGIN CERTIFICATE-----=0A=
MIIB/jCCAYWgAwIBAgIIdJclisc/elQwCgYIKoZIzj0EAwMwRTELMAkGA1UEBhMCVVMxFDASB=
gNV=0A=
BAoMC0FmZmlybVRydXN0MSAwHgYDVQQDDBdBZmZpcm1UcnVzdCBQcmVtaXVtIEVDQzAeFw0xM=
DAx=0A=
MjkxNDIwMjRaFw00MDEyMzExNDIwMjRaMEUxCzAJBgNVBAYTAlVTMRQwEgYDVQQKDAtBZmZpc=
m1U=0A=
cnVzdDEgMB4GA1UEAwwXQWZmaXJtVHJ1c3QgUHJlbWl1bSBFQ0MwdjAQBgcqhkjOPQIBBgUrg=
QQA=0A=
IgNiAAQNMF4bFZ0D0KF5Nbc6PJJ6yhUczWLznCZcBz3lVPqj1swS6vQUX+iOGasvLkjmrBhDe=
KzQ=0A=
N8O9ss0s5kfiGuZjuD0uL3jET9v0D6RoTFVya5UdThhClXjMNzyR4ptlKymjQjBAMB0GA1UdD=
gQW=0A=
BBSaryl6wBE1NSZRMADDav5A1a7WPDAPBgNVHRMBAf8EBTADAQH/MA4GA1UdDwEB/wQEAwIBB=
jAK=0A=
BggqhkjOPQQDAwNnADBkAjAXCfOHiFBar8jAQr9HX/VsaobgxCd05DhT1wV/GzTjxi+zygk8N=
53X=0A=
57hG8f2h4nECMEJZh0PUUd+60wkyWs6Iflc9nF9Ca/UHLbXwgpP5WW+uZPpY5Yse42O+tYHNb=
wKM=0A=
eQ=3D=3D=0A=
-----END CERTIFICATE-----=0A=
=0A=
Certum Trusted Network CA=0A=
=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=0A=
-----BEGIN CERTIFICATE-----=0A=
MIIDuzCCAqOgAwIBAgIDBETAMA0GCSqGSIb3DQEBBQUAMH4xCzAJBgNVBAYTAlBMMSIwIAYDV=
QQK=0A=
ExlVbml6ZXRvIFRlY2hub2xvZ2llcyBTLkEuMScwJQYDVQQLEx5DZXJ0dW0gQ2VydGlmaWNhd=
Glv=0A=
biBBdXRob3JpdHkxIjAgBgNVBAMTGUNlcnR1bSBUcnVzdGVkIE5ldHdvcmsgQ0EwHhcNMDgxM=
DIy=0A=
MTIwNzM3WhcNMjkxMjMxMTIwNzM3WjB+MQswCQYDVQQGEwJQTDEiMCAGA1UEChMZVW5pemV0b=
yBU=0A=
ZWNobm9sb2dpZXMgUy5BLjEnMCUGA1UECxMeQ2VydHVtIENlcnRpZmljYXRpb24gQXV0aG9ya=
XR5=0A=
MSIwIAYDVQQDExlDZXJ0dW0gVHJ1c3RlZCBOZXR3b3JrIENBMIIBIjANBgkqhkiG9w0BAQEFA=
AOC=0A=
AQ8AMIIBCgKCAQEA4/t9o3K6wvDJFIf1awFO4W5AB7ptJ11/91sts1rHUV+rpDKmYYe2bg+G0=
jAC=0A=
l/jXaVehGDldamR5xgFZrDwxSjh80gTSSyjoIF87B6LMTXPb865Px1bVWqeWifrzq2jUI4ZZJ=
88J=0A=
J7ysbnKDHDBy3+Ci6dLhdHUZvSqeexVUBBvXQzmtVSjF4hq79MDkrjhJM8x2hZ85RdKknvISj=
FH4=0A=
fOQtf/WsX+sWn7Et0brMkUJ3TCXJkDhv2/DM+44el1k+1WBO5gUo7Ul5E0u6SNsv+XLTOcr+H=
9g0=0A=
cvW0QM8xAcPs3hEtF10fuFDRXhmnad4HMyjKUJX5p1TLVIZQRan5SQIDAQABo0IwQDAPBgNVH=
RMB=0A=
Af8EBTADAQH/MB0GA1UdDgQWBBQIds3LB/8k9sXN7buQvOKEN0Z19zAOBgNVHQ8BAf8EBAMCA=
QYw=0A=
DQYJKoZIhvcNAQEFBQADggEBAKaorSLOAT2mo/9i0Eidi15ysHhE49wcrwn9I0j6vSrEuVUEt=
RCj=0A=
jSfeC4Jj0O7eDDd5QVsisrCaQVymcODU0HfLI9MA4GxWL+FpDQ3Zqr8hgVDZBqWo/5U30Kr+4=
rP1=0A=
mS1FhIrlQgnXdAIv94nYmem8J9RHjboNRhx3zxSkHLmkMcScKHQDNP8zGSal6Q10tz6XxnboJ=
5aj=0A=
Zt3hrvJBW8qYVoNzcOSGGtIxQbovvi0TWnZvTuhOgQ4/WwMioBK+ZlgRSssDxLQqKi2WF+A5V=
LxI=0A=
03YnnZotBqbJ7DnSq9ufmgsnAjUpsUCV5/nonFWIGUbWtzT1fs45mtk48VH3Tyw=3D=0A=
-----END CERTIFICATE-----=0A=
=0A=
Certinomis - Autorit=C3=A9 Racine=0A=
=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=
=3D=3D=3D=3D=0A=
-----BEGIN CERTIFICATE-----=0A=
MIIFnDCCA4SgAwIBAgIBATANBgkqhkiG9w0BAQUFADBjMQswCQYDVQQGEwJGUjETMBEGA1UEC=
hMK=0A=
Q2VydGlub21pczEXMBUGA1UECxMOMDAwMiA0MzM5OTg5MDMxJjAkBgNVBAMMHUNlcnRpbm9ta=
XMg=0A=
LSBBdXRvcml0w6kgUmFjaW5lMB4XDTA4MDkxNzA4Mjg1OVoXDTI4MDkxNzA4Mjg1OVowYzELM=
AkG=0A=
A1UEBhMCRlIxEzARBgNVBAoTCkNlcnRpbm9taXMxFzAVBgNVBAsTDjAwMDIgNDMzOTk4OTAzM=
SYw=0A=
JAYDVQQDDB1DZXJ0aW5vbWlzIC0gQXV0b3JpdMOpIFJhY2luZTCCAiIwDQYJKoZIhvcNAQEBB=
QAD=0A=
ggIPADCCAgoCggIBAJ2Fn4bT46/HsmtuM+Cet0I0VZ35gb5j2CN2DpdUzZlMGvE5x4jYF1AMn=
mHa=0A=
wE5V3udauHpOd4cN5bjr+p5eex7Ezyh0x5P1FMYiKAT5kcOrJ3NqDi5N8y4oH3DfVS9O7cdxb=
wly=0A=
Lu3VMpfQ8Vh30WC8Tl7bmoT2R2FFK/ZQpn9qcSdIhDWerP5pqZ56XjUl+rSnSTV3lqc2W+HN3=
yNw=0A=
2F1MpQiD8aYkOBOo7C+ooWfHpi2GR+6K/OybDnT0K0kCe5B1jPyZOQE51kqJ5Z52qz6WKDgmi=
92N=0A=
jMD2AR5vpTESOH2VwnHu7XSu5DaiQ3XV8QCb4uTXzEIDS3h65X27uK4uIJPT5GHfceF2Z5c/t=
t9q=0A=
c1pkIuVC28+BA5PY9OMQ4HL2AHCs8MF6DwV/zzRpRbWT5BnbUhYjBYkOjUjkJW+zeL9i9Qf6l=
STC=0A=
lrLooyPCXQP8w9PlfMl1I9f09bze5N/NgL+RiH2nE7Q5uiy6vdFrzPOlKO1Enn1So2+WLhl+H=
PNb=0A=
xxaOu2B9d2ZHVIIAEWBsMsGoOBvrbpgT1u449fCfDu/+MYHB0iSVL1N6aaLwD4ZFjliCK0wi1=
F6g=0A=
530mJ0jfJUaNSih8hp75mxpZuWW/Bd22Ql095gBIgl4g9xGC3srYn+Y3RyYe63j3YcNBZFgCQ=
fna=0A=
4NH4+ej9Uji29YnfAgMBAAGjWzBZMA8GA1UdEwEB/wQFMAMBAf8wDgYDVR0PAQH/BAQDAgEGM=
B0G=0A=
A1UdDgQWBBQNjLZh2kS40RR9w759XkjwzspqsDAXBgNVHSAEEDAOMAwGCiqBegFWAgIAAQEwD=
QYJ=0A=
KoZIhvcNAQEFBQADggIBACQ+YAZ+He86PtvqrxyaLAEL9MW12Ukx9F1BjYkMTv9sov3/4gbIO=
Z/x=0A=
WqndIlgVqIrTseYyCYIDbNc/CMf4uboAbbnW/FIyXaR/pDGUu7ZMOH8oMDX/nyNTt7buFHAAQ=
Cva=0A=
R6s0fl6nVjBhK4tDrP22iCj1a7Y+YEq6QpA0Z43q619FVDsXrIvkxmUP7tCMXWY5zjKn2BCXw=
H40=0A=
nJ+U8/aGH88bc62UeYdocMMzpXDn2NU4lG9jeeu/Cg4I58UvD0KgKxRA/yHgBcUn4YQRE7rWh=
h1B=0A=
CxMjidPJC+iKunqjo3M3NYB9Ergzd0A4wPpeMNLytqOx1qKVl4GbUu1pTP+A5FPbVFsDbVRfs=
bjv=0A=
JL1vnxHDx2TCDyhihWZeGnuyt++uNckZM6i4J9szVb9o4XVIRFb7zdNIu0eJOqxp9YDG5ERQL=
1TE=0A=
qkPFMTFYvZbF6nVsmnWxTfj3l/+WFvKXTej28xH5On2KOG4Ey+HTRRWqpdEdnV1j6CTmNhTih=
60b=0A=
WfVEm/vXd3wfAXBioSAaosUaKPQhA+4u2cGA6rnZgtZbdsLLO7XSAPCjDuGtbkD326C00EauF=
ddE=0A=
wk01+dIL8hf2rGbVJLJP0RyZwG71fet0BLj5TXcJ17TPBzAJ8bgAVtkXFhYKK4bfjwEZGuW7g=
mP/=0A=
vgt2Fl43N+bYdJeimUV5=0A=
-----END CERTIFICATE-----=0A=
=0A=
Root CA Generalitat Valenciana=0A=
=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=
=3D=3D=3D=3D=3D=0A=
-----BEGIN CERTIFICATE-----=0A=
MIIGizCCBXOgAwIBAgIEO0XlaDANBgkqhkiG9w0BAQUFADBoMQswCQYDVQQGEwJFUzEfMB0GA=
1UE=0A=
ChMWR2VuZXJhbGl0YXQgVmFsZW5jaWFuYTEPMA0GA1UECxMGUEtJR1ZBMScwJQYDVQQDEx5Sb=
290=0A=
IENBIEdlbmVyYWxpdGF0IFZhbGVuY2lhbmEwHhcNMDEwNzA2MTYyMjQ3WhcNMjEwNzAxMTUyM=
jQ3=0A=
WjBoMQswCQYDVQQGEwJFUzEfMB0GA1UEChMWR2VuZXJhbGl0YXQgVmFsZW5jaWFuYTEPMA0GA=
1UE=0A=
CxMGUEtJR1ZBMScwJQYDVQQDEx5Sb290IENBIEdlbmVyYWxpdGF0IFZhbGVuY2lhbmEwggEiM=
A0G=0A=
CSqGSIb3DQEBAQUAA4IBDwAwggEKAoIBAQDGKqtXETcvIorKA3Qdyu0togu8M1JAJke+WmmmO=
3I2=0A=
F0zo37i7L3bhQEZ0ZQKQUgi0/6iMweDHiVYQOTPvaLRfX9ptI6GJXiKjSgbwJ/BXufjpTjJ3C=
j9B=0A=
ZPPrZe52/lSqfR0grvPXdMIKX/UIKFIIzFVd0g/bmoGlu6GzwZTNVOAydTGRGmKy3nXiz0+J2=
ZGQ=0A=
D0EbtFpKd71ng+CT516nDOeB0/RSrFOyA8dEJvt55cs0YFAQexvba9dHq198aMpunUEDEO5rm=
Xte=0A=
JajCq+TA81yc477OMUxkHl6AovWDfgzWyoxVjr7gvkkHD6MkQXpYHYTqWBLI4bft75PelAgxA=
gMB=0A=
AAGjggM7MIIDNzAyBggrBgEFBQcBAQQmMCQwIgYIKwYBBQUHMAGGFmh0dHA6Ly9vY3NwLnBra=
S5n=0A=
dmEuZXMwEgYDVR0TAQH/BAgwBgEB/wIBAjCCAjQGA1UdIASCAiswggInMIICIwYKKwYBBAG/V=
QIB=0A=
ADCCAhMwggHoBggrBgEFBQcCAjCCAdoeggHWAEEAdQB0AG8AcgBpAGQAYQBkACAAZABlACAAQ=
wBl=0A=
AHIAdABpAGYAaQBjAGEAYwBpAPMAbgAgAFIAYQDtAHoAIABkAGUAIABsAGEAIABHAGUAbgBlA=
HIA=0A=
YQBsAGkAdABhAHQAIABWAGEAbABlAG4AYwBpAGEAbgBhAC4ADQAKAEwAYQAgAEQAZQBjAGwAY=
QBy=0A=
AGEAYwBpAPMAbgAgAGQAZQAgAFAAcgDhAGMAdABpAGMAYQBzACAAZABlACAAQwBlAHIAdABpA=
GYA=0A=
aQBjAGEAYwBpAPMAbgAgAHEAdQBlACAAcgBpAGcAZQAgAGUAbAAgAGYAdQBuAGMAaQBvAG4AY=
QBt=0A=
AGkAZQBuAHQAbwAgAGQAZQAgAGwAYQAgAHAAcgBlAHMAZQBuAHQAZQAgAEEAdQB0AG8AcgBpA=
GQA=0A=
YQBkACAAZABlACAAQwBlAHIAdABpAGYAaQBjAGEAYwBpAPMAbgAgAHMAZQAgAGUAbgBjAHUAZ=
QBu=0A=
AHQAcgBhACAAZQBuACAAbABhACAAZABpAHIAZQBjAGMAaQDzAG4AIAB3AGUAYgAgAGgAdAB0A=
HAA=0A=
OgAvAC8AdwB3AHcALgBwAGsAaQAuAGcAdgBhAC4AZQBzAC8AYwBwAHMwJQYIKwYBBQUHAgEWG=
Wh0=0A=
dHA6Ly93d3cucGtpLmd2YS5lcy9jcHMwHQYDVR0OBBYEFHs100DSHHgZZu90ECjcPk+yeAT8M=
IGV=0A=
BgNVHSMEgY0wgYqAFHs100DSHHgZZu90ECjcPk+yeAT8oWykajBoMQswCQYDVQQGEwJFUzEfM=
B0G=0A=
A1UEChMWR2VuZXJhbGl0YXQgVmFsZW5jaWFuYTEPMA0GA1UECxMGUEtJR1ZBMScwJQYDVQQDE=
x5S=0A=
b290IENBIEdlbmVyYWxpdGF0IFZhbGVuY2lhbmGCBDtF5WgwDQYJKoZIhvcNAQEFBQADggEBA=
CRh=0A=
TvW1yEICKrNcda3FbcrnlD+laJWIwVTAEGmiEi8YPyVQqHxK6sYJ2fR1xkDar1CdPaUWu20xx=
sdz=0A=
Ckj+IHLtb8zog2EWRpABlUt9jppSCS/2bxzkoXHPjCpaF3ODR00PNvsETUlR4hTJZGH71BTg9=
J63=0A=
NI8KJr2XXPR5OkowGcytT6CYirQxlyric21+eLj4iIlPsSKRZEv1UN4D2+XFducTZnV+ZfsBn=
5OH=0A=
iJ35Rld8TWCvmHMTI6QgkYH60GFmuH3Rr9ZvHmw96RH9qfmCIoaZM3Fa6hlXPZHNqcCjbgcTp=
snt=0A=
+GijnsNacgmHKNHEc8RzGF9QdRYxn7fofMM=3D=0A=
-----END CERTIFICATE-----=0A=
=0A=
A-Trust-nQual-03=0A=
=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=0A=
-----BEGIN CERTIFICATE-----=0A=
MIIDzzCCAregAwIBAgIDAWweMA0GCSqGSIb3DQEBBQUAMIGNMQswCQYDVQQGEwJBVDFIMEYGA=
1UE=0A=
Cgw/QS1UcnVzdCBHZXMuIGYuIFNpY2hlcmhlaXRzc3lzdGVtZSBpbSBlbGVrdHIuIERhdGVud=
mVy=0A=
a2VociBHbWJIMRkwFwYDVQQLDBBBLVRydXN0LW5RdWFsLTAzMRkwFwYDVQQDDBBBLVRydXN0L=
W5R=0A=
dWFsLTAzMB4XDTA1MDgxNzIyMDAwMFoXDTE1MDgxNzIyMDAwMFowgY0xCzAJBgNVBAYTAkFUM=
Ugw=0A=
RgYDVQQKDD9BLVRydXN0IEdlcy4gZi4gU2ljaGVyaGVpdHNzeXN0ZW1lIGltIGVsZWt0ci4gR=
GF0=0A=
ZW52ZXJrZWhyIEdtYkgxGTAXBgNVBAsMEEEtVHJ1c3QtblF1YWwtMDMxGTAXBgNVBAMMEEEtV=
HJ1=0A=
c3QtblF1YWwtMDMwggEiMA0GCSqGSIb3DQEBAQUAA4IBDwAwggEKAoIBAQCtPWFuA/OQO8BBC=
4SA=0A=
zewqo51ru27CQoT3URThoKgtUaNR8t4j8DRE/5TrzAUjlUC5B3ilJfYKvUWG6Nm9wASOhURh7=
3+n=0A=
yfrBJcyFLGM/BWBzSQXgYHiVEEvc+RFZznF/QJuKqiTfC0Li21a8StKlDJu3Qz7dg9MmEALP6=
iPE=0A=
SU7l0+m0iKsMrmKS1GWH2WrX9IWf5DMiJaXlyDO6w8dB3F/GaswADm0yqLaHNgBid5seHzTLk=
Dx4=0A=
iHQF63n1k3Flyp3HaxgtPVxO59X4PzF9j4fsCiIvI+n+u33J4PTs63zEsMMtYrWacdaxaujs2=
e3V=0A=
cuy+VwHOBVWf3tFgiBCzAgMBAAGjNjA0MA8GA1UdEwEB/wQFMAMBAf8wEQYDVR0OBAoECERql=
WdV=0A=
eRFPMA4GA1UdDwEB/wQEAwIBBjANBgkqhkiG9w0BAQUFAAOCAQEAVdRU0VlIXLOThaq/Yy/kg=
M40=0A=
ozRiPvbY7meIMQQDbwvUB/tOdQ/TLtPAF8fGKOwGDREkDg6lXb+MshOWcdzUzg4NCmgybLlBM=
Rmr=0A=
sQd7TZjTXLDR8KdCoLXEjq/+8T/0709GAHbrAvv5ndJAlseIOrifEXnzgGWovR/TeIGgUUw3t=
KZd=0A=
JXDRZslo+S4RFGjxVJgIrCaSD96JntT6s3kr0qN51OyLrIdTaEJMUVF0HhsnLuP1Hyl0Te2v9=
+GS=0A=
mYHovjrHF1D2t8b8m7CKa9aIA5GPBnc6hQLdmNVDeD/GMBWsm2vLV7eJUYs66MmEDNuxUCAKG=
kq6=0A=
ahq97BvIxYSazQ=3D=3D=0A=
-----END CERTIFICATE-----=0A=
=0A=
TWCA Root Certification Authority=0A=
=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=
=3D=3D=3D=3D=3D=3D=3D=3D=0A=
-----BEGIN CERTIFICATE-----=0A=
MIIDezCCAmOgAwIBAgIBATANBgkqhkiG9w0BAQUFADBfMQswCQYDVQQGEwJUVzESMBAGA1UEC=
gwJ=0A=
VEFJV0FOLUNBMRAwDgYDVQQLDAdSb290IENBMSowKAYDVQQDDCFUV0NBIFJvb3QgQ2VydGlma=
WNh=0A=
dGlvbiBBdXRob3JpdHkwHhcNMDgwODI4MDcyNDMzWhcNMzAxMjMxMTU1OTU5WjBfMQswCQYDV=
QQG=0A=
EwJUVzESMBAGA1UECgwJVEFJV0FOLUNBMRAwDgYDVQQLDAdSb290IENBMSowKAYDVQQDDCFUV=
0NB=0A=
IFJvb3QgQ2VydGlmaWNhdGlvbiBBdXRob3JpdHkwggEiMA0GCSqGSIb3DQEBAQUAA4IBDwAwg=
gEK=0A=
AoIBAQCwfnK4pAOU5qfeCTiRShFAh6d8WWQUe7UREN3+v9XAu1bihSX0NXIP+FPQQeFEAcK0H=
MMx=0A=
QhZHhTMidrIKbw/lJVBPhYa+v5guEGcevhEFhgWQxFnQfHgQsIBct+HHK3XLfJ+utdGdIzdjp=
9xC=0A=
oi2SBBtQwXu4PhvJVgSLL1KbralW6cH/ralYhzC2gfeXRfwZVzsrb+RH9JlF/h3x+JejiB03H=
FyP=0A=
4HYlmlD4oFT/RJB2I9IyxsOrBr/8+7/zrX2SYgJbKdM1o5OaQ2RgXbL6Mv87BK9NQGr5x+PvI=
/1r=0A=
y+UPizgN7gr8/g+YnzAx3WxSZfmLgb4i4RxYA7qRG4kHAgMBAAGjQjBAMA4GA1UdDwEB/wQEA=
wIB=0A=
BjAPBgNVHRMBAf8EBTADAQH/MB0GA1UdDgQWBBRqOFsmjd6LWvJPelSDGRjjCDWmujANBgkqh=
kiG=0A=
9w0BAQUFAAOCAQEAPNV3PdrfibqHDAhUaiBQkr6wQT25JmSDCi/oQMCXKCeCMErJk/9q56YAf=
4lC=0A=
mtYR5VPOL8zy2gXE/uJQxDqGfczafhAJO5I1KlOy/usrBdlsXebQ79NqZp4VKIV66IIArB6nC=
WlW=0A=
QtNoURi+VJq/REG6Sb4gumlc7rh3zc5sH62Dlhh9DrUUOYTxKOkto557HnpyWoOzeW/vtPzQC=
qVY=0A=
T0bf+215WfKEIlKuD8z7fDvnaspHYcN6+NOSBB+4IIThNlQWx0DeO4pz3N/GCUzf7Nr/1FNCo=
cny=0A=
Yh0igzyXxfkZYiesZSLX0zzG5Y6yU8xJzrww/nsOM5D77dIUkR8Hrw=3D=3D=0A=
-----END CERTIFICATE-----=0A=
=0A=
Security Communication RootCA2=0A=
=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=
=3D=3D=3D=3D=3D=0A=
-----BEGIN CERTIFICATE-----=0A=
MIIDdzCCAl+gAwIBAgIBADANBgkqhkiG9w0BAQsFADBdMQswCQYDVQQGEwJKUDElMCMGA1UEC=
hMc=0A=
U0VDT00gVHJ1c3QgU3lzdGVtcyBDTy4sTFRELjEnMCUGA1UECxMeU2VjdXJpdHkgQ29tbXVua=
WNh=0A=
dGlvbiBSb290Q0EyMB4XDTA5MDUyOTA1MDAzOVoXDTI5MDUyOTA1MDAzOVowXTELMAkGA1UEB=
hMC=0A=
SlAxJTAjBgNVBAoTHFNFQ09NIFRydXN0IFN5c3RlbXMgQ08uLExURC4xJzAlBgNVBAsTHlNlY=
3Vy=0A=
aXR5IENvbW11bmljYXRpb24gUm9vdENBMjCCASIwDQYJKoZIhvcNAQEBBQADggEPADCCAQoCg=
gEB=0A=
ANAVOVKxUrO6xVmCxF1SrjpDZYBLx/KWvNs2l9amZIyoXvDjChz335c9S672XewhtUGrzbl+d=
p++=0A=
+T42NKA7wfYxEUV0kz1XgMX5iZnK5atq1LXaQZAQwdbWQonCv/Q4EpVMVAX3NuRFg3sUZdbcD=
E3R=0A=
3n4MqzvEFb46VqZab3ZpUql6ucjrappdUtAtCms1FgkQhNBqyjoGADdH5H5XTz+L62e4iKrFv=
lNV=0A=
spHEfbmwhRkGeC7bYRr6hfVKkaHnFtWOojnflLhwHyg/i/xAXmODPIMqGplrz95Zajv8bxbXH=
/1K=0A=
EOtOghY6rCcMU/Gt1SSwawNQwS08Ft1ENCcadfsCAwEAAaNCMEAwHQYDVR0OBBYEFAqFqXdlB=
Zh8=0A=
QIH4D5csOPEK7DzPMA4GA1UdDwEB/wQEAwIBBjAPBgNVHRMBAf8EBTADAQH/MA0GCSqGSIb3D=
QEB=0A=
CwUAA4IBAQBMOqNErLlFsceTfsgLCkLfZOoc7llsCLqJX2rKSpWeeo8HxdpFcoJxDjrSzG+nt=
KEj=0A=
u/Ykn8sX/oymzsLS28yN/HH8AynBbF0zX2S2ZTuJbxh2ePXcokgfGT+Ok+vx+hfuzU7jBBJV1=
uXk=0A=
3fs+BXziHV7Gp7yXT2g69ekuCkO2r1dcYmh8t/2jioSgrGK+KwmHNPBqAbubKVY8/gA3zyNs8=
U6q=0A=
tnRGEmyR7jTV7JqR50S+kDFy1UkC9gLl9B/rfNmWVan/7Ir5mUf/NVoCqgTLiluHcSmRvaS0e=
g29=0A=
mvVXIwAHIRc/SjnRBUkLp7Y3gaVdjKozXoEofKd9J+sAro03=0A=
-----END CERTIFICATE-----=0A=
=0A=
EC-ACC=0A=
=3D=3D=3D=3D=3D=3D=0A=
-----BEGIN CERTIFICATE-----=0A=
MIIFVjCCBD6gAwIBAgIQ7is969Qh3hSoYqwE893EATANBgkqhkiG9w0BAQUFADCB8zELMAkGA=
1UE=0A=
BhMCRVMxOzA5BgNVBAoTMkFnZW5jaWEgQ2F0YWxhbmEgZGUgQ2VydGlmaWNhY2lvIChOSUYgU=
S0w=0A=
ODAxMTc2LUkpMSgwJgYDVQQLEx9TZXJ2ZWlzIFB1YmxpY3MgZGUgQ2VydGlmaWNhY2lvMTUwM=
wYD=0A=
VQQLEyxWZWdldSBodHRwczovL3d3dy5jYXRjZXJ0Lm5ldC92ZXJhcnJlbCAoYykwMzE1MDMGA=
1UE=0A=
CxMsSmVyYXJxdWlhIEVudGl0YXRzIGRlIENlcnRpZmljYWNpbyBDYXRhbGFuZXMxDzANBgNVB=
AMT=0A=
BkVDLUFDQzAeFw0wMzAxMDcyMzAwMDBaFw0zMTAxMDcyMjU5NTlaMIHzMQswCQYDVQQGEwJFU=
zE7=0A=
MDkGA1UEChMyQWdlbmNpYSBDYXRhbGFuYSBkZSBDZXJ0aWZpY2FjaW8gKE5JRiBRLTA4MDExN=
zYt=0A=
SSkxKDAmBgNVBAsTH1NlcnZlaXMgUHVibGljcyBkZSBDZXJ0aWZpY2FjaW8xNTAzBgNVBAsTL=
FZl=0A=
Z2V1IGh0dHBzOi8vd3d3LmNhdGNlcnQubmV0L3ZlcmFycmVsIChjKTAzMTUwMwYDVQQLEyxKZ=
XJh=0A=
cnF1aWEgRW50aXRhdHMgZGUgQ2VydGlmaWNhY2lvIENhdGFsYW5lczEPMA0GA1UEAxMGRUMtQ=
UND=0A=
MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAsyLHT+KXQpWIR4NA9h0X84NzJB5R8=
5iK=0A=
w5K4/0CQBXCHYMkAqbWUZRkiFRfCQ2xmRJoNBD45b6VLeqpjt4pEndljkYRm4CgPukLjbo73F=
CeT=0A=
ae6RDqNfDrHrZqJyTxIThmV6PttPB/SnCWDaOkKZx7J/sxaVHMf5NLWUhdWZXqBIoH7nF2W4o=
nW4=0A=
HvPlQn2v7fOKSGRdghST2MDk/7NQcvJ29rNdQlB50JQ+awwAvthrDk4q7D7SzIKiGGUzE3eem=
l0a=0A=
E9jD2z3Il3rucO2n5nzbcc8tlGLfbdb1OL4/pYUKGbio2Al1QnDE6u/LDsg0qBIimAy4E5S2S=
+zw=0A=
0JDnJwIDAQABo4HjMIHgMB0GA1UdEQQWMBSBEmVjX2FjY0BjYXRjZXJ0Lm5ldDAPBgNVHRMBA=
f8E=0A=
BTADAQH/MA4GA1UdDwEB/wQEAwIBBjAdBgNVHQ4EFgQUoMOLRKo3pUW/l4Ba0fF4opvpXY0wf=
wYD=0A=
VR0gBHgwdjB0BgsrBgEEAfV4AQMBCjBlMCwGCCsGAQUFBwIBFiBodHRwczovL3d3dy5jYXRjZ=
XJ0=0A=
Lm5ldC92ZXJhcnJlbDA1BggrBgEFBQcCAjApGidWZWdldSBodHRwczovL3d3dy5jYXRjZXJ0L=
m5l=0A=
dC92ZXJhcnJlbCAwDQYJKoZIhvcNAQEFBQADggEBAKBIW4IB9k1IuDlVNZyAelOZ1Vr/sXE7z=
DkJ=0A=
lF7W2u++AVtd0x7Y/X1PzaBB4DSTv8vihpw3kpBWHNzrKQXlxJ7HNd+KDM3FIUPpqojlNcAZQ=
mNa=0A=
Al6kSBg6hW/cnbw/nZzBh7h6YQjpdwt/cKt63dmXLGQehb+8dJahw3oS7AwaboMMPOhyRp/7S=
NVe=0A=
l+axofjk70YllJyJ22k4vuxcDlbHZVHlUIiIv0LVKz3l+bqeLrPK9HOSAgu+TGbrIP65y7WZf=
+a2=0A=
E/rKS03Z7lNGBjvGTq2TWoF+bCpLagVFjPIhpDGQh2xlnJ2lYJU6Un/10asIbvPuW/mIPX64b=
24D=0A=
5EI=3D=0A=
-----END CERTIFICATE-----=0A=
=0A=
Hellenic Academic and Research Institutions RootCA 2011=0A=
=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=
=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=
=3D=3D=3D=3D=3D=0A=
-----BEGIN CERTIFICATE-----=0A=
MIIEMTCCAxmgAwIBAgIBADANBgkqhkiG9w0BAQUFADCBlTELMAkGA1UEBhMCR1IxRDBCBgNVB=
AoT=0A=
O0hlbGxlbmljIEFjYWRlbWljIGFuZCBSZXNlYXJjaCBJbnN0aXR1dGlvbnMgQ2VydC4gQXV0a=
G9y=0A=
aXR5MUAwPgYDVQQDEzdIZWxsZW5pYyBBY2FkZW1pYyBhbmQgUmVzZWFyY2ggSW5zdGl0dXRpb=
25z=0A=
IFJvb3RDQSAyMDExMB4XDTExMTIwNjEzNDk1MloXDTMxMTIwMTEzNDk1MlowgZUxCzAJBgNVB=
AYT=0A=
AkdSMUQwQgYDVQQKEztIZWxsZW5pYyBBY2FkZW1pYyBhbmQgUmVzZWFyY2ggSW5zdGl0dXRpb=
25z=0A=
IENlcnQuIEF1dGhvcml0eTFAMD4GA1UEAxM3SGVsbGVuaWMgQWNhZGVtaWMgYW5kIFJlc2Vhc=
mNo=0A=
IEluc3RpdHV0aW9ucyBSb290Q0EgMjAxMTCCASIwDQYJKoZIhvcNAQEBBQADggEPADCCAQoCg=
gEB=0A=
AKlTAOMupvaO+mDYLZU++CwqVE7NuYRhlFhPjz2L5EPzdYmNUeTDN9KKiE15HrcS3UN4SoqS5=
tdI=0A=
1Q+kOilENbgH9mgdVc04UfCMJDGFr4PJfel3r+0ae50X+bOdOFAPplp5kYCvN66m0zH7tSYJn=
Txa=0A=
71HFK9+WXesyHgLacEnsbgzImjeN9/E2YEsmLIKe0HjzDQ9jpFEw4fkrJxIH2Oq9GGKYsFk3f=
b7u=0A=
8yBRQlqD75O6aRXxYp2fmTmCobd0LovUxQt7L/DICto9eQqakxylKHJzkUOap9FNhYS5qXSPF=
EDH=0A=
3N6sQWRstBmbAmNtJGSPRLIl6s5ddAxjMlyNh+UCAwEAAaOBiTCBhjAPBgNVHRMBAf8EBTADA=
QH/=0A=
MAsGA1UdDwQEAwIBBjAdBgNVHQ4EFgQUppFC/RNhSiOeCKQp5dgTBCPuQSUwRwYDVR0eBEAwP=
qA8=0A=
MAWCAy5ncjAFggMuZXUwBoIELmVkdTAGggQub3JnMAWBAy5ncjAFgQMuZXUwBoEELmVkdTAGg=
QQu=0A=
b3JnMA0GCSqGSIb3DQEBBQUAA4IBAQAf73lB4XtuP7KMhjdCSk4cNx6NZrokgclPEg8hwAOXh=
iVt=0A=
XdMiKahsog2p6z0GW5k6x8zDmjR/qw7IThzh+uTczQ2+vyT+bOdrwg3IBp5OjWEopmr95fZi6=
hg8=0A=
TqBTnbI6nOulnJEWtk2C4AwFSKls9cz4y51JtPACpf1wA+2KIaWuE4ZJwzNzvoc7dIsXRSZMF=
pGD=0A=
/md9zU1jZ/rzAxKWeAaNsWftjj++n08C9bMJL/NMh98qy5V8AcysNnq/onN694/BtZqhFLKPM=
58N=0A=
7yLcZnuEvUUXBj08yrl3NI/K6s8/MT7jiOOASSXIl7WdmplNsDz4SgCbZN2fOUvRJ9e4=0A=
-----END CERTIFICATE-----=0A=
=0A=
Actalis Authentication Root CA=0A=
=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=
=3D=3D=3D=3D=3D=0A=
-----BEGIN CERTIFICATE-----=0A=
MIIFuzCCA6OgAwIBAgIIVwoRl0LE48wwDQYJKoZIhvcNAQELBQAwazELMAkGA1UEBhMCSVQxD=
jAM=0A=
BgNVBAcMBU1pbGFuMSMwIQYDVQQKDBpBY3RhbGlzIFMucC5BLi8wMzM1ODUyMDk2NzEnMCUGA=
1UE=0A=
AwweQWN0YWxpcyBBdXRoZW50aWNhdGlvbiBSb290IENBMB4XDTExMDkyMjExMjIwMloXDTMwM=
Dky=0A=
MjExMjIwMlowazELMAkGA1UEBhMCSVQxDjAMBgNVBAcMBU1pbGFuMSMwIQYDVQQKDBpBY3Rhb=
Glz=0A=
IFMucC5BLi8wMzM1ODUyMDk2NzEnMCUGA1UEAwweQWN0YWxpcyBBdXRoZW50aWNhdGlvbiBSb=
290=0A=
IENBMIICIjANBgkqhkiG9w0BAQEFAAOCAg8AMIICCgKCAgEAp8bEpSmkLO/lGMWwUKNvUTufC=
lrJ=0A=
wkg4CsIcoBh/kbWHuUA/3R1oHwiD1S0eiKD4j1aPbZkCkpAW1V8IbInX4ay8IMKx4INRimlNA=
JZa=0A=
by/ARH6jDuSRzVju3PvHHkVH3Se5CAGfpiEd9UEtL0z9KK3giq0itFZljoZUj5NDKd45RnijM=
CO6=0A=
zfB9E1fAXdKDa0hMxKufgFpbOr3JpyI/gCczWw63igxdBzcIy2zSekciRDXFzMwujt0q7bd9Z=
g1f=0A=
YVEiVRvjRuPjPdA1YprbrxTIW6HMiRvhMCb8oJsfgadHHwTrozmSBp+Z07/T6k9QnBn+loceP=
GX2=0A=
oxgkg4YQ51Q+qDp2JE+BIcXjDwL4k5RHILv+1A7TaLndxHqEguNTVHnd25zS8gebLra8Pu2Fb=
e8l=0A=
EfKXGkJh90qX6IuxEAf6ZYGyojnP9zz/GPvG8VqLWeICrHuS0E4UT1lF9gxeKF+w6D9Fz8+vm=
2/7=0A=
hNN3WpVvrJSEnu68wEqPSpP4RCHiMUVhUE4Q2OM1fEwZtN4Fv6MGn8i1zeQf1xcGDXqVdFUNa=
Br8=0A=
EBtiZJ1t4JWgw5QHVw0U5r0F+7if5t+L4sbnfpb2U8WANFAoWPASUHEXMLrmeGO89LKtmyuy/=
uE5=0A=
jF66CyCU3nuDuP/jVo23Eek7jPKxwV2dpAtMK9myGPW1n0sCAwEAAaNjMGEwHQYDVR0OBBYEF=
FLY=0A=
iDrIn3hm7YnzezhwlMkCAjbQMA8GA1UdEwEB/wQFMAMBAf8wHwYDVR0jBBgwFoAUUtiIOsife=
Gbt=0A=
ifN7OHCUyQICNtAwDgYDVR0PAQH/BAQDAgEGMA0GCSqGSIb3DQEBCwUAA4ICAQALe3KHwGCmS=
UyI=0A=
WOYdiPcUZEim2FgKDk8TNd81HdTtBjHIgT5q1d07GjLukD0R0i70jsNjLiNmsGe+b7bAEzlgq=
qI0=0A=
JZN1Ut6nna0Oh4lScWoWPBkdg/iaKWW+9D+a2fDzWochcYBNy+A4mz+7+uAwTc+G02UQGRjRl=
wKx=0A=
K3JCaKygvU5a2hi/a5iB0P2avl4VSM0RFbnAKVy06Ij3Pjaut2L9HmLecHgQHEhb2rykOLpn7=
VU+=0A=
Xlff1ANATIGk0k9jpwlCCRT8AKnCgHNPLsBA2RF7SOp6AsDT6ygBJlh0wcBzIm2Tlf05fbsq4=
/aC=0A=
4yyXX04fkZT6/iyj2HYauE2yOE+b+h1IYHkm4vP9qdCa6HCPSXrW5b0KDtst842/6+OkfcvHl=
XHo=0A=
2qN8xcL4dJIEG4aspCJTQLas/kx2z/uUMsA1n3Y/buWQbqCmJqK4LL7RK4X9p2jIugErsWx0H=
bhz=0A=
lefut8cl8ABMALJ+tguLHPPAUJ4lueAI3jZm/zel0btUZCzJJ7VLkn5l/9Mt4blOvH+kQSGQQ=
Xem=0A=
OR/qnuOf0GZvBeyqdn6/axag67XH/JJULysRJyU3eExRarDzzFhdFPFqSBX/wge2sY0PjlxQR=
rM9=0A=
vwGYT7JZVEc+NHt4bVaTLnPqZih4zR0Uv6CPLy64Lo7yFIrM6bV8+2ydDKXhlg=3D=3D=0A=
-----END CERTIFICATE-----=0A=
=0A=
Trustis FPS Root CA=0A=
=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=0A=
-----BEGIN CERTIFICATE-----=0A=
MIIDZzCCAk+gAwIBAgIQGx+ttiD5JNM2a/fH8YygWTANBgkqhkiG9w0BAQUFADBFMQswCQYDV=
QQG=0A=
EwJHQjEYMBYGA1UEChMPVHJ1c3RpcyBMaW1pdGVkMRwwGgYDVQQLExNUcnVzdGlzIEZQUyBSb=
290=0A=
IENBMB4XDTAzMTIyMzEyMTQwNloXDTI0MDEyMTExMzY1NFowRTELMAkGA1UEBhMCR0IxGDAWB=
gNV=0A=
BAoTD1RydXN0aXMgTGltaXRlZDEcMBoGA1UECxMTVHJ1c3RpcyBGUFMgUm9vdCBDQTCCASIwD=
QYJ=0A=
KoZIhvcNAQEBBQADggEPADCCAQoCggEBAMVQe547NdDfxIzNjpvto8A2mfRC6qc+gIMPpqdZh=
8mQ=0A=
RUN+AOqGeSoDvT03mYlmt+WKVoaTnGhLaASMk5MCPjDSNzoiYYkchU59j9WvezX2fihHiTHcD=
nlk=0A=
H5nSW7r+f2C/revnPDgpai/lkQtV/+xvWNUtyd5MZnGPDNcE2gfmHhjjvSkCqPoc4Vu5g6hBS=
Lwa=0A=
cY3nYuUtsuvffM/bq1rKMfFMIvMFE/eC+XN5DL7XSxzA0RU8k0Fk0ea+IxciAIleH2ulrG6nS=
4zt=0A=
o3Lmr2NNL4XSFDWaLk6M6jKYKIahkQlBOrTh4/L68MkKokHdqeMDx4gVOxzUGpTXn2RZEm0CA=
wEA=0A=
AaNTMFEwDwYDVR0TAQH/BAUwAwEB/zAfBgNVHSMEGDAWgBS6+nEleYtXQSUhhgtx67JkDoshZ=
zAd=0A=
BgNVHQ4EFgQUuvpxJXmLV0ElIYYLceuyZA6LIWcwDQYJKoZIhvcNAQEFBQADggEBAH5Y//01G=
X2c=0A=
GE+esCu8jowU/yyg2kdbw++BLa8F6nRIW/M+TgfHbcWzk88iNVy2P3UnXwmWzaD+vkAMXBJV+=
JOC=0A=
yinpXj9WV4s4NvdFGkwozZ5BuO1WTISkQMi4sKUraXAEasP41BIy+Q7DsdwyhEQsb8tGD+pmQ=
Q9P=0A=
8Vilpg0ND2HepZ5dfWWhPBfnqFVO76DH7cZEf1T1o+CP8HxVIo8ptoGj4W1OLBuAZ+ytIJ8MY=
mHV=0A=
l/9D7S3B2l0pKoU/rGXuhg8FjZBf3+6f9L/uHfuY5H+QK4R4EA5sSVPvFVtlRkpdr7r7OnIdz=
fYl=0A=
iB6XzCGcKQENZetX2fNXlrtIzYE=3D=0A=
-----END CERTIFICATE-----=0A=
=0A=
StartCom Certification Authority=0A=
=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=
=3D=3D=3D=3D=3D=3D=3D=0A=
-----BEGIN CERTIFICATE-----=0A=
MIIHhzCCBW+gAwIBAgIBLTANBgkqhkiG9w0BAQsFADB9MQswCQYDVQQGEwJJTDEWMBQGA1UEC=
hMN=0A=
U3RhcnRDb20gTHRkLjErMCkGA1UECxMiU2VjdXJlIERpZ2l0YWwgQ2VydGlmaWNhdGUgU2lnb=
mlu=0A=
ZzEpMCcGA1UEAxMgU3RhcnRDb20gQ2VydGlmaWNhdGlvbiBBdXRob3JpdHkwHhcNMDYwOTE3M=
Tk0=0A=
NjM3WhcNMzYwOTE3MTk0NjM2WjB9MQswCQYDVQQGEwJJTDEWMBQGA1UEChMNU3RhcnRDb20gT=
HRk=0A=
LjErMCkGA1UECxMiU2VjdXJlIERpZ2l0YWwgQ2VydGlmaWNhdGUgU2lnbmluZzEpMCcGA1UEA=
xMg=0A=
U3RhcnRDb20gQ2VydGlmaWNhdGlvbiBBdXRob3JpdHkwggIiMA0GCSqGSIb3DQEBAQUAA4ICD=
wAw=0A=
ggIKAoICAQDBiNsJvGxGfHiflXu1M5DycmLWwTYgIiRezul38kMKogZkpMyONvg45iPwbm2xP=
N1y=0A=
o4UcodM9tDMr0y+v/uqwQVlntsQGfQqedIXWeUyAN3rfOQVSWff0G0ZDpNKFhdLDcfN1YjS6L=
Ip/=0A=
Ho/u7TTQEceWzVI9ujPW3U3eCztKS5/CJi/6tRYccjV3yjxd5srhJosaNnZcAdt0FCX+7bWgi=
A/d=0A=
eMotHweXMAEtcnn6RtYTKqi5pquDSR3l8u/d5AGOGAqPY1MWhWKpDhk6zLVmpsJrdAfkK+F2P=
rRt=0A=
2PZE4XNiHzvEvqBTViVsUQn3qqvKv3b9bZvzndu/PWa8DFaqr5hIlTpL36dYUNk4dalb6kMMA=
v+Z=0A=
6+hsTXBbKWWc3apdzK8BMewM69KN6Oqce+Zu9ydmDBpI125C4z/eIT574Q1w+2OqqGwaVLRcJ=
XrJ=0A=
osmLFqa7LH4XXgVNWG4SHQHuEhANxjJ/GP/89PrNbpHoNkm+Gkhpi8KWTRoSsmkXwQqQ1vp5I=
ki/=0A=
untp+HDH+no32NgN0nZPV/+Qt+OR0t3vwmC3Zzrd/qqc8NSLf3Iizsafl7b4r4qgEKjZ+xjGt=
rVc=0A=
UjyJthkqcwEKDwOzEmDyei+B26Nu/yYwl/WL3YlXtq09s68rxbd2AvCl1iuahhQqcvbjM4xdC=
UsT=0A=
37uMdBNSSwIDAQABo4ICEDCCAgwwDwYDVR0TAQH/BAUwAwEB/zAOBgNVHQ8BAf8EBAMCAQYwH=
QYD=0A=
VR0OBBYEFE4L7xqkQFulF2mHMMo0aEPQQa7yMB8GA1UdIwQYMBaAFE4L7xqkQFulF2mHMMo0a=
EPQ=0A=
Qa7yMIIBWgYDVR0gBIIBUTCCAU0wggFJBgsrBgEEAYG1NwEBATCCATgwLgYIKwYBBQUHAgEWI=
mh0=0A=
dHA6Ly93d3cuc3RhcnRzc2wuY29tL3BvbGljeS5wZGYwNAYIKwYBBQUHAgEWKGh0dHA6Ly93d=
3cu=0A=
c3RhcnRzc2wuY29tL2ludGVybWVkaWF0ZS5wZGYwgc8GCCsGAQUFBwICMIHCMCcWIFN0YXJ0I=
ENv=0A=
bW1lcmNpYWwgKFN0YXJ0Q29tKSBMdGQuMAMCAQEagZZMaW1pdGVkIExpYWJpbGl0eSwgcmVhZ=
CB0=0A=
aGUgc2VjdGlvbiAqTGVnYWwgTGltaXRhdGlvbnMqIG9mIHRoZSBTdGFydENvbSBDZXJ0aWZpY=
2F0=0A=
aW9uIEF1dGhvcml0eSBQb2xpY3kgYXZhaWxhYmxlIGF0IGh0dHA6Ly93d3cuc3RhcnRzc2wuY=
29t=0A=
L3BvbGljeS5wZGYwEQYJYIZIAYb4QgEBBAQDAgAHMDgGCWCGSAGG+EIBDQQrFilTdGFydENvb=
SBG=0A=
cmVlIFNTTCBDZXJ0aWZpY2F0aW9uIEF1dGhvcml0eTANBgkqhkiG9w0BAQsFAAOCAgEAjo/n3=
JR5=0A=
fPGFf59Jb2vKXfuM/gTFwWLRfUKKvFO3lANmMD+x5wqnUCBVJX92ehQN6wQOQOY+2IirByeDq=
XWm=0A=
N3PH/UvSTa0XQMhGvjt/UfzDtgUx3M2FIk5xt/JxXrAaxrqTi3iSSoX4eA+D/i+tLPfkpLst0=
OcN=0A=
Org+zvZ49q5HJMqjNTbOx8aHmNrs++myziebiMMEofYLWWivydsQD032ZGNcpRJvkrKTlMeIF=
w6T=0A=
tn5ii5B/q06f/ON1FE8qMt9bDeD1e5MNq6HPh+GlBEXoPBKlCcWw0bdT82AUuoVpaiF8H3VhF=
yAX=0A=
e2w7QSlc4axa0c2Mm+tgHRns9+Ww2vl5GKVFP0lDV9LdJNUso/2RjSe15esUBppMeyG7Oq0wB=
hjA=0A=
2MFrLH9ZXF2RsXAiV+uKa0hK1Q8p7MZAwC+ITGgBF3f0JBlPvfrhsiAhS90a2Cl9qrjeVOwhV=
YBs=0A=
HvUwyKMQ5bLmKhQxw4UtjJixhlpPiVktucf3HMiKf8CdBUrmQk9io20ppB+Fq9vlgcitKj1MX=
VuE=0A=
JnHEhV5xJMqlG2zYYdMa4FTbzrqpMrUi9nNBCV24F10OD5mQ1kfabwo6YigUZ4LZ8dCAWZvLM=
dib=0A=
D4x3TrVoivJs9iQOLWxwxXPR3hTQcY+203sC9uO41Alua551hDnmfyWl8kgAwKQB2j8=3D=0A=
-----END CERTIFICATE-----=0A=
=0A=
StartCom Certification Authority G2=0A=
=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=
=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=0A=
-----BEGIN CERTIFICATE-----=0A=
MIIFYzCCA0ugAwIBAgIBOzANBgkqhkiG9w0BAQsFADBTMQswCQYDVQQGEwJJTDEWMBQGA1UEC=
hMN=0A=
U3RhcnRDb20gTHRkLjEsMCoGA1UEAxMjU3RhcnRDb20gQ2VydGlmaWNhdGlvbiBBdXRob3Jpd=
Hkg=0A=
RzIwHhcNMTAwMTAxMDEwMDAxWhcNMzkxMjMxMjM1OTAxWjBTMQswCQYDVQQGEwJJTDEWMBQGA=
1UE=0A=
ChMNU3RhcnRDb20gTHRkLjEsMCoGA1UEAxMjU3RhcnRDb20gQ2VydGlmaWNhdGlvbiBBdXRob=
3Jp=0A=
dHkgRzIwggIiMA0GCSqGSIb3DQEBAQUAA4ICDwAwggIKAoICAQC2iTZbB7cgNr2Cu+EWIAOVe=
q8O=0A=
o1XJJZlKxdBWQYeQTSFgpBSHO839sj60ZwNq7eEPS8CRhXBF4EKe3ikj1AENoBB5uNsDvfOpL=
9HG=0A=
4A/LnooUCri99lZi8cVytjIl2bLzvWXFDSxu1ZJvGIsAQRSCb0AgJnooD/Uefyf3lLE3PbfHk=
ffi=0A=
Aez9lInhzG7TNtYKGXmu1zSCZf98Qru23QumNK9LYP5/Q0kGi4xDuFby2X8hQxfqp0iVAXV16=
iul=0A=
Q5XqFYSdCI0mblWbq9zSOdIxHWDirMxWRST1HFSr7obdljKF+ExP6JV2tgXdNiNnvP8V4so75=
qbs=0A=
O+wmETRIjfaAKxojAuuKHDp2KntWFhxyKrOq42ClAJ8Em+JvHhRYW6Vsi1g8w7pOOlz34ZYrP=
u8H=0A=
vKTlXcxNnw3h3Kq74W4a7I/htkxNeXJdFzULHdfBR9qWJODQcqhaX2YtENwvKhOuJv4KHBnM0=
D4L=0A=
nMgJLvlblnpHnOl68wVQdJVznjAJ85eCXuaPOQgeWeU1FEIT/wCc976qUM/iUUjXuG+v+E5+M=
5iS=0A=
FGI6dWPPe/regjupuznixL0sAA7IF6wT700ljtizkC+p2il9Ha90OrInwMEePnWjFqmveiJdn=
xMa=0A=
z6eg6+OGCtP95paV1yPIN93EfKo2rJgaErHgTuixO/XWb/Ew1wIDAQABo0IwQDAPBgNVHRMBA=
f8E=0A=
BTADAQH/MA4GA1UdDwEB/wQEAwIBBjAdBgNVHQ4EFgQUS8W0QGutHLOlHGVuRjaJhwUMDrYwD=
QYJ=0A=
KoZIhvcNAQELBQADggIBAHNXPyzVlTJ+N9uWkusZXn5T50HsEbZH77Xe7XRcxfGOSeD8bpkTz=
Z+K=0A=
2s06Ctg6Wgk/XzTQLwPSZh0avZyQN8gMjgdalEVGKua+etqhqaRpEpKwfTbURIfXUfEpY9Z1z=
Rbk=0A=
J4kd+MIySP3bmdCPX1R0zKxnNBFi2QwKN4fRoxdIjtIXHfbX/dtl6/2o1PXWT6RbdejF0mCy2=
wl+=0A=
JYt7ulKSnj7oxXehPOBKc2thz4bcQ///If4jXSRK9dNtD2IEBVeC2m6kMyV5Sy5UGYvMLD0w6=
dEG=0A=
/+gyRr61M3Z3qAFdlsHB1b6uJcDJHgoJIIihDsnzb02CVAAgp9KP5DlUFy6NHrgbuxu9mk47E=
DTc=0A=
nIhT76IxW1hPkWLIwpqazRVdOKnWvvgTtZ8SafJQYqz7Fzf07rh1Z2AQ+4NQ+US1dZxAF7L+/=
Xld=0A=
blhYXzD8AK6vM8EOTmy6p6ahfzLbOOCxchcKK5HsamMm7YnUeMx0HgX4a/6ManY5Ka5lIxKVC=
CIc=0A=
l85bBu4M4ru8H0ST9tg4RQUh7eStqxK2A6RCLi3ECToDZ2mEmuFZkIoohdVddLHRDiBYmxOls=
GOm=0A=
7XtH/UVVMKTumtTm4ofvmMkyghEpIrwACjFeLQ/Ajulrso8uBtjRkcfGEvRM/TAXw8HaOFvjq=
erm=0A=
obp573PYtlNXLfbQ4ddI=0A=
-----END CERTIFICATE-----=0A=
=0A=
Buypass Class 2 Root CA=0A=
=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=0A=
-----BEGIN CERTIFICATE-----=0A=
MIIFWTCCA0GgAwIBAgIBAjANBgkqhkiG9w0BAQsFADBOMQswCQYDVQQGEwJOTzEdMBsGA1UEC=
gwU=0A=
QnV5cGFzcyBBUy05ODMxNjMzMjcxIDAeBgNVBAMMF0J1eXBhc3MgQ2xhc3MgMiBSb290IENBM=
B4X=0A=
DTEwMTAyNjA4MzgwM1oXDTQwMTAyNjA4MzgwM1owTjELMAkGA1UEBhMCTk8xHTAbBgNVBAoMF=
EJ1=0A=
eXBhc3MgQVMtOTgzMTYzMzI3MSAwHgYDVQQDDBdCdXlwYXNzIENsYXNzIDIgUm9vdCBDQTCCA=
iIw=0A=
DQYJKoZIhvcNAQEBBQADggIPADCCAgoCggIBANfHXvfBB9R3+0Mh9PT1aeTuMgHbo4Yf5FkNu=
ud1=0A=
g1Lr6hxhFUi7HQfKjK6w3Jad6sNgkoaCKHOcVgb/S2TwDCo3SbXlzwx87vFKu3MwZfPVL4O2f=
uPn=0A=
9Z6rYPnT8Z2SdIrkHJasW4DptfQxh6NR/Md+oW+OU3fUl8FVM5I+GC911K2GScuVr1QGbNgGE=
41b=0A=
/+EmGVnAJLqBcXmQRFBoJJRfuLMR8SlBYaNByyM21cHxMlAQTn/0hpPshNOOvEu/XAFOBz3cF=
IqU=0A=
CqTqc/sLUegTBxj6DvEr0VQVfTzh97QZQmdiXnfgolXsttlpF9U6r0TtSsWe5HonfOV116rLJ=
eff=0A=
awrbD02TTqigzXsu8lkBarcNuAeBfos4GzjmCleZPe4h6KP1DBbdi+w0jpwqHAAVF41og9Jwn=
xgI=0A=
zRFo1clrUs3ERo/ctfPYV3Me6ZQ5BL/T3jjetFPsaRyifsSP5BtwrfKi+fv3FmRmaZ9JUaLiF=
Rhn=0A=
Bkp/1Wy1TbMz4GHrXb7pmA8y1x1LPC5aAVKRCfLf6o3YBkBjqhHk/sM3nhRSP/TizPJhk9H9Z=
2vX=0A=
Uq6/aKtAQ6BXNVN48FP4YUIHZMbXb5tMOA1jrGKvNouicwoN9SG9dKpN6nIDSdvHXx1iY8f93=
ZHs=0A=
M+71bbRuMGjeyNYmsHVee7QHIJihdjK4TWxPAgMBAAGjQjBAMA8GA1UdEwEB/wQFMAMBAf8wH=
QYD=0A=
VR0OBBYEFMmAd+BikoL1RpzzuvdMw964o605MA4GA1UdDwEB/wQEAwIBBjANBgkqhkiG9w0BA=
QsF=0A=
AAOCAgEAU18h9bqwOlI5LJKwbADJ784g7wbylp7ppHR/ehb8t/W2+xUbP6umwHJdELFx7rxP4=
62s=0A=
A20ucS6vxOOto70MEae0/0qyexAQH6dXQbLArvQsWdZHEIjzIVEpMMpghq9Gqx3tOluwlN5E4=
0EI=0A=
osHsHdb9T7bWR9AUC8rmyrV7d35BH16Dx7aMOZawP5aBQW9gkOLo+fsicdl9sz1Gv7SEr5AcD=
48S=0A=
aq/v7h56rgJKihcrdv6sVIkkLE8/trKnToyokZf7KcZ7XC25y2a2t6hbElGFtQl+Ynhw/qlqY=
LYd=0A=
DnkM/crqJIByw5c/8nerQyIKx+u2DISCLIBrQYoIwOula9+ZEsuK1V6ADJHgJgg2SMX6OBE1/=
yWD=0A=
LfJ6v9r9jv6ly0UsH8SIU653DtmadsWOLB2jutXsMq7Aqqz30XpN69QH4kj3Io6wpJ9qzo6ys=
mD0=0A=
oyLQI+uUWnpp3Q+/QFesa1lQ2aOZ4W7+jQF5JyMV3pKdewlNWudLSDBaGOYKbeaP4NK75t98b=
iGC=0A=
wWg5TbSYWGZizEqQXsP6JwSxeRV0mcy+rSDeJmAc61ZRpqPq5KM/p/9h3PFaTWwyI0PurKju7=
koS=0A=
CTxdccK+efrCh2gdC/1cacwG0Jp9VJkqyTkaGa9LKkPzY11aWOIv4x3kqdbQCtCev9eBCfHJx=
yYN=0A=
rJgWVqA=3D=0A=
-----END CERTIFICATE-----=0A=
=0A=
Buypass Class 3 Root CA=0A=
=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=0A=
-----BEGIN CERTIFICATE-----=0A=
MIIFWTCCA0GgAwIBAgIBAjANBgkqhkiG9w0BAQsFADBOMQswCQYDVQQGEwJOTzEdMBsGA1UEC=
gwU=0A=
QnV5cGFzcyBBUy05ODMxNjMzMjcxIDAeBgNVBAMMF0J1eXBhc3MgQ2xhc3MgMyBSb290IENBM=
B4X=0A=
DTEwMTAyNjA4Mjg1OFoXDTQwMTAyNjA4Mjg1OFowTjELMAkGA1UEBhMCTk8xHTAbBgNVBAoMF=
EJ1=0A=
eXBhc3MgQVMtOTgzMTYzMzI3MSAwHgYDVQQDDBdCdXlwYXNzIENsYXNzIDMgUm9vdCBDQTCCA=
iIw=0A=
DQYJKoZIhvcNAQEBBQADggIPADCCAgoCggIBAKXaCpUWUOOV8l6ddjEGMnqb8RB2uACatVI2z=
SRH=0A=
sJ8YZLya9vrVediQYkwiL944PdbgqOkcLNt4EemOaFEVcsfzM4fkoF0LXOBXByow9c3EN3coT=
RiR=0A=
5r/VUv1xLXA+58bEiuPwKAv0dpihi4dVsjoT/Lc+JzeOIuOoTyrvYLs9tznDDgFHmV0ST9tD+=
leh=0A=
7fmdvhFHJlsTmKtdFoqwNxxXnUX/iJY2v7vKB3tvh2PX0DJq1l1sDPGzbjniazEuOQAnFN44w=
OwZ=0A=
ZoYS6J1yFhNkUsepNxz9gjDthBgd9K5c/3ATAOux9TN6S9ZV+AWNS2mw9bMoNlwUxFFzTWsL8=
TQH=0A=
2xc519woe2v1n/MuwU8XKhDzzMro6/1rqy6any2CbgTUUgGTLT2G/H783+9CHaZr77kgxve9o=
KeV=0A=
/afmiSTYzIw0bOIjL9kSGiG5VZFvC5F5GQytQIgLcOJ60g7YaEi7ghM5EFjp2CoHxhLbWNvSO=
1UQ=0A=
RwUVZ2J+GGOmRj8JDlQyXr8NYnon74Do29lLBlo3WiXQCBJ31G8JUJc9yB3D34xFMFbG02SrZ=
vPA=0A=
Xpacw8Tvw3xrizp5f7NJzz3iiZ+gMEuFuZyUJHmPfWupRWgPK9Dx2hzLabjKSWJtyNBjYt1gD=
1iq=0A=
j6G8BaVmos8bdrKEZLFMOVLAMLrwjEsCsLa3AgMBAAGjQjBAMA8GA1UdEwEB/wQFMAMBAf8wH=
QYD=0A=
VR0OBBYEFEe4zf/lb+74suwvTg75JbCOPGvDMA4GA1UdDwEB/wQEAwIBBjANBgkqhkiG9w0BA=
QsF=0A=
AAOCAgEAACAjQTUEkMJAYmDv4jVM1z+s4jSQuKFvdvoWFqRINyzpkMLyPPgKn9iB5btb2iUsp=
KdV=0A=
cSQy9sgL8rxq+JOssgfCX5/bzMiKqr5qb+FJEMwx14C7u8jYog5kV+qi9cKpMRXSIGrs/CIBK=
M+G=0A=
uIAeqcwRpTzyFrNHnfzSgCHEy9BHcEGhyoMZCCxt8l13nIoUE9Q2HJLw5QY33KbmkJs4j1xrG=
0aG=0A=
Q0JfPgEHU1RdZX33inOhmlRaHylDFCfChQ+1iHsaO5S3HWCntZznKWlXWpuTekMwGwPXYshAp=
qr8=0A=
ZORK15FTAaggiG6cX0S5y2CBNOxv033aSF/rtJC8LakcC6wc1aJoIIAE1vyxjy+7SjENSoYc6=
+I2=0A=
KSb12tjE8nVhz36udmNKekBlk4f4HoCMhuWG1o8O/FMsYOgWYRqiPkN7zTlgVGr18okmAWiDS=
KIz=0A=
6MkEkbIRNBE+6tBDGR8Dk5AM/1E9V/RBbuHLoL7ryWPNbczk+DaqaJ3tvV2XcEQNtg413OEMX=
bug=0A=
UZTLfhbrES+jkkXITHHZvMmZUldGL1DPvTVp9D0VzgalLA8+9oG6lLvDu79leNKGef9JOxqDD=
PDe=0A=
eOzI8k1MGt6CKfjBWtrt7uYnXuhF0J0cUahoq0Tj0Itq4/g7u9xN12TyUb7mqqta6THuBrxzv=
xNi=0A=
Cp/HuZc=3D=0A=
-----END CERTIFICATE-----=0A=
=0A=
T-TeleSec GlobalRoot Class 3=0A=
=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=
=3D=3D=3D=0A=
-----BEGIN CERTIFICATE-----=0A=
MIIDwzCCAqugAwIBAgIBATANBgkqhkiG9w0BAQsFADCBgjELMAkGA1UEBhMCREUxKzApBgNVB=
AoM=0A=
IlQtU3lzdGVtcyBFbnRlcnByaXNlIFNlcnZpY2VzIEdtYkgxHzAdBgNVBAsMFlQtU3lzdGVtc=
yBU=0A=
cnVzdCBDZW50ZXIxJTAjBgNVBAMMHFQtVGVsZVNlYyBHbG9iYWxSb290IENsYXNzIDMwHhcNM=
Dgx=0A=
MDAxMTAyOTU2WhcNMzMxMDAxMjM1OTU5WjCBgjELMAkGA1UEBhMCREUxKzApBgNVBAoMIlQtU=
3lz=0A=
dGVtcyBFbnRlcnByaXNlIFNlcnZpY2VzIEdtYkgxHzAdBgNVBAsMFlQtU3lzdGVtcyBUcnVzd=
CBD=0A=
ZW50ZXIxJTAjBgNVBAMMHFQtVGVsZVNlYyBHbG9iYWxSb290IENsYXNzIDMwggEiMA0GCSqGS=
Ib3=0A=
DQEBAQUAA4IBDwAwggEKAoIBAQC9dZPwYiJvJK7genasfb3ZJNW4t/zN8ELg63iIVl6bmlQdT=
QyK=0A=
9tPPcPRStdiTBONGhnFBSivwKixVA9ZIw+A5OO3yXDw/RLyTPWGrTs0NvvAgJ1gORH8EGoel1=
5YU=0A=
NpDQSXuhdfsaa3Ox+M6pCSzyU9XDFES4hqX2iys52qMzVNn6chr3IhUciJFrf2blw2qAsCTz3=
4ZF=0A=
iP0Zf3WHHx+xGwpzJFu5ZeAsVMhg02YXP+HMVDNzkQI6pn97djmiH5a2OK61yJN0HZ65tOVgn=
S9W=0A=
0eDrXltMEnAMbEQgqxHY9Bn20pxSN+f6tsIxO0rUFJmtxxr1XV/6B7h8DR/Wgx6zAgMBAAGjQ=
jBA=0A=
MA8GA1UdEwEB/wQFMAMBAf8wDgYDVR0PAQH/BAQDAgEGMB0GA1UdDgQWBBS1A/d2O2GCahKqG=
FPr=0A=
AyGUv/7OyjANBgkqhkiG9w0BAQsFAAOCAQEAVj3vlNW92nOyWL6ukK2YJ5f+AbGwUgC4TeQbI=
XQb=0A=
fsDuXmkqJa9c1h3a0nnJ85cp4IaH3gRZD/FZ1GSFS5mvJQQeyUapl96Cshtwn5z2r3Ex3XsFp=
SzT=0A=
ucpH9sry9uetuUg/vBa3wW306gmv7PO15wWeph6KU1HWk4HMdJP2udqmJQV0eVp+QD6CSyYRM=
G7h=0A=
P0HHRwA11fXT91Q+gT3aSWqas+8QPebrb9HIIkfLzM8BMZLZGOMivgkeGj5asuRrDFR6fUNOu=
Iml=0A=
e9eiPZaGzPImNC1qkp2aGtAw4l1OBLBfiyB+d8E9lYLRRpo7PHi4b6HQDWSieB4pTpPDpFQUW=
w=3D=3D=0A=
-----END CERTIFICATE-----=0A=
=0A=
EE Certification Centre Root CA=0A=
=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=
=3D=3D=3D=3D=3D=3D=0A=
-----BEGIN CERTIFICATE-----=0A=
MIIEAzCCAuugAwIBAgIQVID5oHPtPwBMyonY43HmSjANBgkqhkiG9w0BAQUFADB1MQswCQYDV=
QQG=0A=
EwJFRTEiMCAGA1UECgwZQVMgU2VydGlmaXRzZWVyaW1pc2tlc2t1czEoMCYGA1UEAwwfRUUgQ=
2Vy=0A=
dGlmaWNhdGlvbiBDZW50cmUgUm9vdCBDQTEYMBYGCSqGSIb3DQEJARYJcGtpQHNrLmVlMCIYD=
zIw=0A=
MTAxMDMwMTAxMDMwWhgPMjAzMDEyMTcyMzU5NTlaMHUxCzAJBgNVBAYTAkVFMSIwIAYDVQQKD=
BlB=0A=
UyBTZXJ0aWZpdHNlZXJpbWlza2Vza3VzMSgwJgYDVQQDDB9FRSBDZXJ0aWZpY2F0aW9uIENlb=
nRy=0A=
ZSBSb290IENBMRgwFgYJKoZIhvcNAQkBFglwa2lAc2suZWUwggEiMA0GCSqGSIb3DQEBAQUAA=
4IB=0A=
DwAwggEKAoIBAQDIIMDs4MVLqwd4lfNE7vsLDP90jmG7sWLqI9iroWUyeuuOF0+W2Ap7kaJjb=
MeM=0A=
TC55v6kF/GlclY1i+blw7cNRfdCT5mzrMEvhvH2/UpvObntl8jixwKIy72KyaOBhU8E2lf/sl=
Lo2=0A=
rpwcpzIP5Xy0xm90/XsY6KxX7QYgSzIwWFv9zajmofxwvI6Sc9uXp3whrj3B9UiHbCe9nyV0g=
VWw=0A=
93X2PaRka9ZP585ArQ/dMtO8ihJTmMmJ+xAdTX7Nfh9WDSFwhfYggx/2uh8Ej+p3iDXE/+pOo=
YtN=0A=
P2MbRMNE1CV2yreN1x5KZmTNXMWcg+HCCIia7E6j8T4cLNlsHaFLAgMBAAGjgYowgYcwDwYDV=
R0T=0A=
AQH/BAUwAwEB/zAOBgNVHQ8BAf8EBAMCAQYwHQYDVR0OBBYEFBLyWj7qVhy/zQas8fElyalL1=
BSZ=0A=
MEUGA1UdJQQ+MDwGCCsGAQUFBwMCBggrBgEFBQcDAQYIKwYBBQUHAwMGCCsGAQUFBwMEBggrB=
gEF=0A=
BQcDCAYIKwYBBQUHAwkwDQYJKoZIhvcNAQEFBQADggEBAHv25MANqhlHt01Xo/6tu7Fq1Q+e2=
+Rj=0A=
xY6hUFaTlrg4wCQiZrxTFGGVv9DHKpY5P30osxBAIWrEr7BSdxjhlthWXePdNl4dp1BUoMUq5=
KqM=0A=
lIpPnTX/dqQGE5Gion0ARD9V04I8GtVbvFZMIi5GQ4okQC3zErg7cBqklrkar4dBGmoYDQZPx=
z5u=0A=
uSlNDUmJEYcyW+ZLBMjkXOZ0c5RdFpgTlf7727FE5TpwrDdr5rMzcijJs1eg9gIWiAYLtqZLI=
CjU=0A=
3j2LrTcFU3T+bsy8QxdxXvnFzBqpYe73dgzzcvRyrc9yAjYHR8/vGVCJYMzpJJUPwssd8m92k=
MfM=0A=
dcGWxZ0=3D=0A=
-----END CERTIFICATE-----=0A=
=0A=
TURKTRUST Certificate Services Provider Root 2007=0A=
=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=
=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=0A=
-----BEGIN CERTIFICATE-----=0A=
MIIEPTCCAyWgAwIBAgIBATANBgkqhkiG9w0BAQUFADCBvzE/MD0GA1UEAww2VMOcUktUUlVTV=
CBF=0A=
bGVrdHJvbmlrIFNlcnRpZmlrYSBIaXptZXQgU2HEn2xhecSxY8Sxc8SxMQswCQYDVQQGEwJUU=
jEP=0A=
MA0GA1UEBwwGQW5rYXJhMV4wXAYDVQQKDFVUw5xSS1RSVVNUIEJpbGdpIMSwbGV0acWfaW0gd=
mUg=0A=
QmlsacWfaW0gR8O8dmVubGnEn2kgSGl6bWV0bGVyaSBBLsWeLiAoYykgQXJhbMSxayAyMDA3M=
B4X=0A=
DTA3MTIyNTE4MzcxOVoXDTE3MTIyMjE4MzcxOVowgb8xPzA9BgNVBAMMNlTDnFJLVFJVU1QgR=
Wxl=0A=
a3Ryb25payBTZXJ0aWZpa2EgSGl6bWV0IFNhxJ9sYXnEsWPEsXPEsTELMAkGA1UEBhMCVFIxD=
zAN=0A=
BgNVBAcMBkFua2FyYTFeMFwGA1UECgxVVMOcUktUUlVTVCBCaWxnaSDEsGxldGnFn2ltIHZlI=
EJp=0A=
bGnFn2ltIEfDvHZlbmxpxJ9pIEhpem1ldGxlcmkgQS7Fni4gKGMpIEFyYWzEsWsgMjAwNzCCA=
SIw=0A=
DQYJKoZIhvcNAQEBBQADggEPADCCAQoCggEBAKu3PgqMyKVYFeaK7yc9SrToJdPNM8Ig3Bnui=
D9N=0A=
YvDdE3ePYakqtdTyuTFYKTsvP2qcb3N2Je40IIDu6rfwxArNK4aUyeNgsURSsloptJGXg9i3p=
hQv=0A=
KUmi8wUG+7RP2qFsmmaf8EMJyupyj+sA1zU511YXRxcw9L6/P8JorzZAwan0qafoEGsIiveGH=
tya=0A=
KhUG9qPw9ODHFNRRf8+0222vR5YXm3dx2KdxnSQM9pQ/hTEST7ruToK4uT6PIzdezKKqdfcYb=
wnT=0A=
rqdUKDT74eA7YH2gvnmJhsifLfkKS8RQouf9eRbHegsYz85M733WB2+Y8a+xwXrXgTW4qhe04=
MsC=0A=
AwEAAaNCMEAwHQYDVR0OBBYEFCnFkKslrxHkYb+j/4hhkeYO/pyBMA4GA1UdDwEB/wQEAwIBB=
jAP=0A=
BgNVHRMBAf8EBTADAQH/MA0GCSqGSIb3DQEBBQUAA4IBAQAQDdr4Ouwo0RSVgrESLFF6QSU2T=
J/s=0A=
Px+EnWVUXKgWAkD6bho3hO9ynYYKVZ1WKKxmLNA6VpM0ByWtCLCPyA8JWcqdmBzlVPi5RX9ql=
2+I=0A=
aE1KBiY3iAIOtsbWcpnOa3faYjGkVh+uX4132l32iPwa2Z61gfAyuOOI0JzzaqC5mxRZNTZPz=
/OO=0A=
Xl0XrRWV2N2y1RVuAE6zS89mlOTgzbUF2mNXi+WzqtvALhyQRNsaXRik7r4EW5nVcV9VZWRi1=
aKb=0A=
BFmGyGJ353yCRWo9F7/snXUMrqNvWtMvmDb08PUZqxFdyKbjKlhqQgnDvZImZjINXQhVdP+Mm=
NAK=0A=
poRq0Tl9=0A=
-----END CERTIFICATE-----=0A=
=0A=
D-TRUST Root Class 3 CA 2 2009=0A=
=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=
=3D=3D=3D=3D=3D=0A=
-----BEGIN CERTIFICATE-----=0A=
MIIEMzCCAxugAwIBAgIDCYPzMA0GCSqGSIb3DQEBCwUAME0xCzAJBgNVBAYTAkRFMRUwEwYDV=
QQK=0A=
DAxELVRydXN0IEdtYkgxJzAlBgNVBAMMHkQtVFJVU1QgUm9vdCBDbGFzcyAzIENBIDIgMjAwO=
TAe=0A=
Fw0wOTExMDUwODM1NThaFw0yOTExMDUwODM1NThaME0xCzAJBgNVBAYTAkRFMRUwEwYDVQQKD=
AxE=0A=
LVRydXN0IEdtYkgxJzAlBgNVBAMMHkQtVFJVU1QgUm9vdCBDbGFzcyAzIENBIDIgMjAwOTCCA=
SIw=0A=
DQYJKoZIhvcNAQEBBQADggEPADCCAQoCggEBANOySs96R+91myP6Oi/WUEWJNTrGa9v+2wBoq=
OAD=0A=
ER03UAifTUpolDWzU9GUY6cgVq/eUXjsKj3zSEhQPgrfRlWLJ23DEE0NkVJD2IfgXU42tSHKX=
zlA=0A=
BF9bfsyjxiupQB7ZNoTWSPOSHjRGICTBpFGOShrvUD9pXRl/RcPHAY9RySPocq60vFYJfxLLH=
LGv=0A=
KZAKyVXMD9O0Gu1HNVpK7ZxzBCHQqr0ME7UAyiZsxGsMlFqVlNpQmvH/pStmMaTJOKDfHR+4C=
S7z=0A=
p+hnUquVH+BGPtikw8paxTGA6Eian5Rp/hnd2HN8gcqW3o7tszIFZYQ05ub9VxC1X3a/L7AQD=
cUC=0A=
AwEAAaOCARowggEWMA8GA1UdEwEB/wQFMAMBAf8wHQYDVR0OBBYEFP3aFMSfMN4hvR5COfyrY=
yNJ=0A=
4PGEMA4GA1UdDwEB/wQEAwIBBjCB0wYDVR0fBIHLMIHIMIGAoH6gfIZ6bGRhcDovL2RpcmVjd=
G9y=0A=
eS5kLXRydXN0Lm5ldC9DTj1ELVRSVVNUJTIwUm9vdCUyMENsYXNzJTIwMyUyMENBJTIwMiUyM=
DIw=0A=
MDksTz1ELVRydXN0JTIwR21iSCxDPURFP2NlcnRpZmljYXRlcmV2b2NhdGlvbmxpc3QwQ6BBo=
D+G=0A=
PWh0dHA6Ly93d3cuZC10cnVzdC5uZXQvY3JsL2QtdHJ1c3Rfcm9vdF9jbGFzc18zX2NhXzJfM=
jAw=0A=
OS5jcmwwDQYJKoZIhvcNAQELBQADggEBAH+X2zDI36ScfSF6gHDOFBJpiBSVYEQBrLLpME+bU=
MJm=0A=
2H6NMLVwMeniacfzcNsgFYbQDfC+rAF1hM5+n02/t2A7nPPKHeJeaNijnZflQGDSNiH+0LS4F=
9p0=0A=
o3/U37CYAqxva2ssJSRyoWXuJVrl5jLn8t+rSfrzkGkj2wTZ51xY/GXUl77M/C4KzCUqNQT4Y=
JEV=0A=
dT1B/yMfGchs64JTBKbkTCJNjYy6zltz7GRUUG3RnFX7acM2w4y8PIWmawomDeCTmGCufsYkl=
4ph=0A=
X5GOZpIJhzbNi5stPvZR1FDUWSi9g/LMKHtThm3YJohw1+qRzT65ysCQblrGXnRl11z+o+I=3D=0A=
-----END CERTIFICATE-----=0A=
=0A=
D-TRUST Root Class 3 CA 2 EV 2009=0A=
=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=
=3D=3D=3D=3D=3D=3D=3D=3D=0A=
-----BEGIN CERTIFICATE-----=0A=
MIIEQzCCAyugAwIBAgIDCYP0MA0GCSqGSIb3DQEBCwUAMFAxCzAJBgNVBAYTAkRFMRUwEwYDV=
QQK=0A=
DAxELVRydXN0IEdtYkgxKjAoBgNVBAMMIUQtVFJVU1QgUm9vdCBDbGFzcyAzIENBIDIgRVYgM=
jAw=0A=
OTAeFw0wOTExMDUwODUwNDZaFw0yOTExMDUwODUwNDZaMFAxCzAJBgNVBAYTAkRFMRUwEwYDV=
QQK=0A=
DAxELVRydXN0IEdtYkgxKjAoBgNVBAMMIUQtVFJVU1QgUm9vdCBDbGFzcyAzIENBIDIgRVYgM=
jAw=0A=
OTCCASIwDQYJKoZIhvcNAQEBBQADggEPADCCAQoCggEBAJnxhDRwui+3MKCOvXwEz75ivJn9g=
pfS=0A=
egpnljgJ9hBOlSJzmY3aFS3nBfwZcyK3jpgAvDw9rKFs+9Z5JUut8Mxk2og+KbgPCdM03TP1Y=
tHh=0A=
zRnp7hhPTFiu4h7WDFsVWtg6uMQYZB7jM7K1iXdODL/ZlGsTl28So/6ZqQTMFexgaDbtCHu39=
b+T=0A=
7WYxg4zGcTSHThfqr4uRjRxWQa4iN1438h3Z0S0NL2lRp75mpoo6Kr3HGrHhFPC+Oh25z1uxa=
v60=0A=
sUYgovseO3Dvk5h9jHOW8sXvhXCtKSb8HgQ+HKDYD8tSg2J87otTlZCpV6LqYQXY+U3EJ/pur=
e35=0A=
11H3a6UCAwEAAaOCASQwggEgMA8GA1UdEwEB/wQFMAMBAf8wHQYDVR0OBBYEFNOUikxiEyoZL=
syv=0A=
cop9NteaHNxnMA4GA1UdDwEB/wQEAwIBBjCB3QYDVR0fBIHVMIHSMIGHoIGEoIGBhn9sZGFwO=
i8v=0A=
ZGlyZWN0b3J5LmQtdHJ1c3QubmV0L0NOPUQtVFJVU1QlMjBSb290JTIwQ2xhc3MlMjAzJTIwQ=
0El=0A=
MjAyJTIwRVYlMjAyMDA5LE89RC1UcnVzdCUyMEdtYkgsQz1ERT9jZXJ0aWZpY2F0ZXJldm9jY=
XRp=0A=
b25saXN0MEagRKBChkBodHRwOi8vd3d3LmQtdHJ1c3QubmV0L2NybC9kLXRydXN0X3Jvb3RfY=
2xh=0A=
c3NfM19jYV8yX2V2XzIwMDkuY3JsMA0GCSqGSIb3DQEBCwUAA4IBAQA07XtaPKSUiO8aEXUHL=
7P+=0A=
PPoeUSbrh/Yp3uDx1MYkCenBz1UbtDDZzhr+BlGmFaQt77JLvyAoJUnRpjZ3NOhk31KxEcdze=
s05=0A=
nsKtjHEh8lprr988TlWvsoRlFIm5d8sqMb7Po23Pb0iUMkZv53GMoKaEGTcH8gNFCSuGdXzfX=
2lX=0A=
ANtu2KZyIktQ1HWYVt+3GP9DQ1CuekR78HlR10M9p9OB0/DJT7naxpeG0ILD5EJt/rDiZE4OJ=
udA=0A=
NCa1CInXCGNjOCd1HjPqbqjdn5lPdE2BiYBL3ZqXKVwvvoFBuYz/6n1gBp7N1z3TLqMVvKjmJ=
uVv=0A=
w9y4AyHqnxbxLFS1=0A=
-----END CERTIFICATE-----=0A=
=0A=
PSCProcert=0A=
=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=0A=
-----BEGIN CERTIFICATE-----=0A=
MIIJhjCCB26gAwIBAgIBCzANBgkqhkiG9w0BAQsFADCCAR4xPjA8BgNVBAMTNUF1dG9yaWRhZ=
CBk=0A=
ZSBDZXJ0aWZpY2FjaW9uIFJhaXogZGVsIEVzdGFkbyBWZW5lem9sYW5vMQswCQYDVQQGEwJWR=
TEQ=0A=
MA4GA1UEBxMHQ2FyYWNhczEZMBcGA1UECBMQRGlzdHJpdG8gQ2FwaXRhbDE2MDQGA1UEChMtU=
2lz=0A=
dGVtYSBOYWNpb25hbCBkZSBDZXJ0aWZpY2FjaW9uIEVsZWN0cm9uaWNhMUMwQQYDVQQLEzpTd=
XBl=0A=
cmludGVuZGVuY2lhIGRlIFNlcnZpY2lvcyBkZSBDZXJ0aWZpY2FjaW9uIEVsZWN0cm9uaWNhM=
SUw=0A=
IwYJKoZIhvcNAQkBFhZhY3JhaXpAc3VzY2VydGUuZ29iLnZlMB4XDTEwMTIyODE2NTEwMFoXD=
TIw=0A=
MTIyNTIzNTk1OVowgdExJjAkBgkqhkiG9w0BCQEWF2NvbnRhY3RvQHByb2NlcnQubmV0LnZlM=
Q8w=0A=
DQYDVQQHEwZDaGFjYW8xEDAOBgNVBAgTB01pcmFuZGExKjAoBgNVBAsTIVByb3ZlZWRvciBkZ=
SBD=0A=
ZXJ0aWZpY2Fkb3MgUFJPQ0VSVDE2MDQGA1UEChMtU2lzdGVtYSBOYWNpb25hbCBkZSBDZXJ0a=
WZp=0A=
Y2FjaW9uIEVsZWN0cm9uaWNhMQswCQYDVQQGEwJWRTETMBEGA1UEAxMKUFNDUHJvY2VydDCCA=
iIw=0A=
DQYJKoZIhvcNAQEBBQADggIPADCCAgoCggIBANW39KOUM6FGqVVhSQ2oh3NekS1wwQYalNo97=
BVC=0A=
wfWMrmoX8Yqt/ICV6oNEolt6Vc5Pp6XVurgfoCfAUFM+jbnADrgV3NZs+J74BCXfgI8Qhd19L=
3uA=0A=
3VcAZCP4bsm+lU/hdezgfl6VzbHvvnpC2Mks0+saGiKLt38GieU89RLAu9MLmV+QfI4tL3czk=
koh=0A=
RqipCKzx9hEC2ZUWno0vluYC3XXCFCpa1sl9JcLB/KpnheLsvtF8PPqv1W7/U0HU9TI4seJfx=
PmO=0A=
EO8GqQKJ/+MMbpfg353bIdD0PghpbNjU5Db4g7ayNo+c7zo3Fn2/omnXO1ty0K+qP1xmk6wKI=
mG2=0A=
0qCZyFSTXai20b1dCl53lKItwIKOvMoDKjSuc/HUtQy9vmebVOvh+qBa7Dh+PsHMosdEMXXqP=
+UH=0A=
0quhJZb25uSgXTcYOWEAM11G1ADEtMo88aKjPvM6/2kwLkDd9p+cJsmWN63nOaK/6mnbVSKVU=
yqU=0A=
td+tFjiBdWbjxywbk5yqjKPK2Ww8F22c3HxT4CAnQzb5EuE8XL1mv6JpIzi4mWCZDlZTOpx+F=
Iyw=0A=
Bm/xhnaQr/2v/pDGj59/i5IjnOcVdo/Vi5QTcmn7K2FjiO/mpF7moxdqWEfLcU8UC17IAggmo=
svp=0A=
r2uKGcfLFFb14dq12fy/czja+eevbqQ34gcnAgMBAAGjggMXMIIDEzASBgNVHRMBAf8ECDAGA=
QH/=0A=
AgEBMDcGA1UdEgQwMC6CD3N1c2NlcnRlLmdvYi52ZaAbBgVghl4CAqASDBBSSUYtRy0yMDAwN=
DAz=0A=
Ni0wMB0GA1UdDgQWBBRBDxk4qpl/Qguk1yeYVKIXTC1RVDCCAVAGA1UdIwSCAUcwggFDgBStu=
yId=0A=
xuDSAaj9dlBSk+2YwU2u06GCASakggEiMIIBHjE+MDwGA1UEAxM1QXV0b3JpZGFkIGRlIENlc=
nRp=0A=
ZmljYWNpb24gUmFpeiBkZWwgRXN0YWRvIFZlbmV6b2xhbm8xCzAJBgNVBAYTAlZFMRAwDgYDV=
QQH=0A=
EwdDYXJhY2FzMRkwFwYDVQQIExBEaXN0cml0byBDYXBpdGFsMTYwNAYDVQQKEy1TaXN0ZW1hI=
E5h=0A=
Y2lvbmFsIGRlIENlcnRpZmljYWNpb24gRWxlY3Ryb25pY2ExQzBBBgNVBAsTOlN1cGVyaW50Z=
W5k=0A=
ZW5jaWEgZGUgU2VydmljaW9zIGRlIENlcnRpZmljYWNpb24gRWxlY3Ryb25pY2ExJTAjBgkqh=
kiG=0A=
9w0BCQEWFmFjcmFpekBzdXNjZXJ0ZS5nb2IudmWCAQowDgYDVR0PAQH/BAQDAgEGME0GA1UdE=
QRG=0A=
MESCDnByb2NlcnQubmV0LnZloBUGBWCGXgIBoAwMClBTQy0wMDAwMDKgGwYFYIZeAgKgEgwQU=
klG=0A=
LUotMzE2MzUzNzMtNzB2BgNVHR8EbzBtMEagRKBChkBodHRwOi8vd3d3LnN1c2NlcnRlLmdvY=
i52=0A=
ZS9sY3IvQ0VSVElGSUNBRE8tUkFJWi1TSEEzODRDUkxERVIuY3JsMCOgIaAfhh1sZGFwOi8vY=
WNy=0A=
YWl6LnN1c2NlcnRlLmdvYi52ZTA3BggrBgEFBQcBAQQrMCkwJwYIKwYBBQUHMAGGG2h0dHA6L=
y9v=0A=
Y3NwLnN1c2NlcnRlLmdvYi52ZTBBBgNVHSAEOjA4MDYGBmCGXgMBAjAsMCoGCCsGAQUFBwIBF=
h5o=0A=
dHRwOi8vd3d3LnN1c2NlcnRlLmdvYi52ZS9kcGMwDQYJKoZIhvcNAQELBQADggIBACtZ6yKZu=
4Sq=0A=
T96QxtGGcSOeSwORR3C7wJJg7ODU523G0+1ng3dS1fLld6c2suNUvtm7CpsR72H0xpkzmfWvA=
DmN=0A=
g7+mvTV+LFwxNG9s2/NkAZiqlCxB3RWGymspThbASfzXg0gTB1GEMVKIu4YXx2sviiCtxQuPc=
D4q=0A=
uxtxj7mkoP3YldmvWb8lK5jpY5MvYB7Eqvh39YtsL+1+LrVPQA3uvFd359m21D+VJzog1eWuq=
2w1=0A=
n8GhHVnchIHuTQfiSLaeS5UtQbHh6N5+LwUeaO6/u5BlOsju6rEYNxxik6SgMexxbJHmpHmJW=
hSn=0A=
FFAFTKQAVzAswbVhltw+HoSvOULP5dAssSS830DD7X9jSr3hTxJkhpXzsOfIt+FTvZLm8wyWu=
evo=0A=
5pLtp4EJFAv8lXrPj9Y0TzYS3F7RNHXGRoAvlQSMx4bEqCaJqD8Zm4G7UaRKhqsLEQ+xrmNTb=
Sjq=0A=
3TNWOByyrYDT13K9mmyZY+gAu0F2BbdbmRiKw7gSXFbPVgx96OLP7bx0R/vu0xdOIk9W/1DzL=
uY5=0A=
poLWccret9W6aAjtmcz9opLLabid+Qqkpj5PkygqYWwHJgD/ll9ohri4zspV4KuxPX+Y1zMOW=
j3Y=0A=
eMLEYC/HYvBhkdI4sPaeVdtAgAUSM84dkpvRabP/v/GSCmE1P93+hvS84Bpxs2Km=0A=
-----END CERTIFICATE-----=0A=
=0A=
China Internet Network Information Center EV Certificates Root=0A=
=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=
=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=
=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=0A=
-----BEGIN CERTIFICATE-----=0A=
MIID9zCCAt+gAwIBAgIESJ8AATANBgkqhkiG9w0BAQUFADCBijELMAkGA1UEBhMCQ04xMjAwB=
gNV=0A=
BAoMKUNoaW5hIEludGVybmV0IE5ldHdvcmsgSW5mb3JtYXRpb24gQ2VudGVyMUcwRQYDVQQDD=
D5D=0A=
aGluYSBJbnRlcm5ldCBOZXR3b3JrIEluZm9ybWF0aW9uIENlbnRlciBFViBDZXJ0aWZpY2F0Z=
XMg=0A=
Um9vdDAeFw0xMDA4MzEwNzExMjVaFw0zMDA4MzEwNzExMjVaMIGKMQswCQYDVQQGEwJDTjEyM=
DAG=0A=
A1UECgwpQ2hpbmEgSW50ZXJuZXQgTmV0d29yayBJbmZvcm1hdGlvbiBDZW50ZXIxRzBFBgNVB=
AMM=0A=
PkNoaW5hIEludGVybmV0IE5ldHdvcmsgSW5mb3JtYXRpb24gQ2VudGVyIEVWIENlcnRpZmljY=
XRl=0A=
cyBSb290MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAm35z7r07eKpkQ0H1UN+U8=
i6y=0A=
jUqORlTSIRLIOTJCBumD1Z9S7eVnAztUwYyZmczpwA//DdmEEbK40ctb3B75aDFk4Zv6dOtou=
SCV=0A=
98YPjUesWgbdYavi7NifFy2cyjw1l1VxzUOFsUcW9SxTgHbP0wBkvUCZ3czY28Sf1hNfQYOL+=
Q2H=0A=
klY0bBoQCxfVWhyXWIQ8hBouXJE0bhlffxdpxWXvayHG1VA6v2G5BY3vbzQ6sm8UY78WO5upK=
v23=0A=
KzhmBsUs4qpnHkWnjQRmQvaPK++IIGmPMowUc9orhpFjIpryp9vOiYurXccUwVswah+xt54ug=
QEC=0A=
7c+WXmPbqOY4twIDAQABo2MwYTAfBgNVHSMEGDAWgBR8cks5x8DbYqVPm6oYNJKiyoOCWTAPB=
gNV=0A=
HRMBAf8EBTADAQH/MA4GA1UdDwEB/wQEAwIBBjAdBgNVHQ4EFgQUfHJLOcfA22KlT5uqGDSSo=
sqD=0A=
glkwDQYJKoZIhvcNAQEFBQADggEBACrDx0M3j92tpLIM7twUbY8opJhJywyA6vPtI2Z1fcXTI=
Wd5=0A=
0XPFtQO3WKwMVC/GVhMPMdoG52U7HW8228gd+f2ABsqjPWYWqJ1MFn3AlUa1UeTiH9fqBk1jj=
ZaM=0A=
7+czV0I664zBechNdn3e9rG3geCg+aF4RhcaVpjwTj2rHO3sOdwHSPdj/gauwqRcalsyiMXHM=
4Ws=0A=
ZkJHwlgkmeHlPuV1LI5D1l08eB6olYIpUNHRFrrvwb562bTYzB5MRuF3sTGrvSrIzo9uoV1/A=
3U0=0A=
5K2JRVRevq4opbs/eHnrc7MKDf2+yfdWrPa37S+bISnHOLaVxATywy39FCqQmbkHzJ8=3D=0A=
-----END CERTIFICATE-----=0A=
=0A=
Swisscom Root CA 2=0A=
=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=0A=
-----BEGIN CERTIFICATE-----=0A=
MIIF2TCCA8GgAwIBAgIQHp4o6Ejy5e/DfEoeWhhntjANBgkqhkiG9w0BAQsFADBkMQswCQYDV=
QQG=0A=
EwJjaDERMA8GA1UEChMIU3dpc3Njb20xJTAjBgNVBAsTHERpZ2l0YWwgQ2VydGlmaWNhdGUgU=
2Vy=0A=
dmljZXMxGzAZBgNVBAMTElN3aXNzY29tIFJvb3QgQ0EgMjAeFw0xMTA2MjQwODM4MTRaFw0zM=
TA2=0A=
MjUwNzM4MTRaMGQxCzAJBgNVBAYTAmNoMREwDwYDVQQKEwhTd2lzc2NvbTElMCMGA1UECxMcR=
Gln=0A=
aXRhbCBDZXJ0aWZpY2F0ZSBTZXJ2aWNlczEbMBkGA1UEAxMSU3dpc3Njb20gUm9vdCBDQSAyM=
IIC=0A=
IjANBgkqhkiG9w0BAQEFAAOCAg8AMIICCgKCAgEAlUJOhJ1R5tMJ6HJaI2nbeHCOFvErjw0Dz=
pPM=0A=
LgAIe6szjPTpQOYXTKueuEcUMncy3SgM3hhLX3af+Dk7/E6J2HzFZ++r0rk0X2s682Q2zsKwz=
xNo=0A=
ysjL67XiPS4h3+os1OD5cJZM/2pYmLcX5BtS5X4HAB1f2uY+lQS3aYg5oUFgJWFLlTloYhyxC=
wWJ=0A=
wDaCFCE/rtuh/bxvHGCGtlOUSbkrRsVPACu/obvLP+DHVxxX6NZp+MEkUp2IVd3Chy50I9AU/=
SpH=0A=
Wrumnf2U5NGKpV+GY3aFy6//SSj8gO1MedK75MDvAe5QQQg1I3ArqRa0jG6F6bYRzzHdUyYb3=
y1a=0A=
SgJA/MTAtukxGggo5WDDH8SQjhBiYEQN7Aq+VRhxLKX0srwVYv8c474d2h5Xszx+zYIdkeNL6=
yxS=0A=
NLCK/RJOlrDrcH+eOfdmQrGrrFLadkBXeyq96G4DsguAhYidDMfCd7Camlf0uPoTXGiTOmekl=
9Ab=0A=
mbeGMktg2M7v0Ax/lZ9vh0+Hio5fCHyqW/xavqGRn1V9TrALacywlKinh/LTSlDcX3KwFnUey=
7QY=0A=
Ypqwpzmqm59m2I2mbJYV4+by+PGDYmy7Velhk6M99bFXi08jsJvllGov34zflVEpYKELKeRcV=
Vi3=0A=
qPyZ7iVNTA6z00yPhOgpD/0QVAKFyPnlw4vP5w8CAwEAAaOBhjCBgzAOBgNVHQ8BAf8EBAMCA=
YYw=0A=
HQYDVR0hBBYwFDASBgdghXQBUwIBBgdghXQBUwIBMBIGA1UdEwEB/wQIMAYBAf8CAQcwHQYDV=
R0O=0A=
BBYEFE0mICKJS9PVpAqhb97iEoHF8TwuMB8GA1UdIwQYMBaAFE0mICKJS9PVpAqhb97iEoHF8=
Twu=0A=
MA0GCSqGSIb3DQEBCwUAA4ICAQAyCrKkG8t9voJXiblqf/P0wS4RfbgZPnm3qKhyN2abGu2sE=
zsO=0A=
v2LwnN+ee6FTSA5BesogpxcbtnjsQJHzQq0Qw1zv/2BZf82Fo4s9SBwlAjxnffUy6S8w5X2le=
jjQ=0A=
82YqZh6NM4OKb3xuqFp1mrjX2lhIREeoTPpMSQpKwhI3qEAMw8jh0FcNlzKVxzqfl9NX+Ave5=
XLz=0A=
o9v/tdhZsnPdTSpxsrpJ9csc1fV5yJmz/MFMdOO0vSk3FQQoHt5FRnDsr7p4DooqzgB53MBfG=
Wcs=0A=
a0vvaGgLQ+OswWIJ76bdZWGgr4RVSJFSHMYlkSrQwSIjYVmvRRGFHQEkNI/Ps/8XciATwoCqI=
Sxx=0A=
OQ7Qj1zB09GOInJGTB2Wrk9xseEFKZZZ9LuedT3PDTcNYtsmjGOpI99nBjx8Oto0QuFmtEYE3=
saW=0A=
mA9LSHokMnWRn6z3aOkquVVlzl1h0ydw2Df+n7mvoC5Wt6NlUe07qxS/TFED6F+KBZvuim6c7=
79o=0A=
+sjaC+NCydAXFJy3SuCvkychVSa1ZC+N8f+mQAWFBVzKBxlcCxMoTFh/wqXvRdpg065lYZ1Tg=
3TC=0A=
rvJcwhbtkj6EPnNgiLx29CzP0H1907he0ZESEOnN3col49XtmS++dYFLJPlFRpTJKSFTnCZFq=
hMX=0A=
5OfNeOI5wSsSnqaeG8XmDtkx2Q=3D=3D=0A=
-----END CERTIFICATE-----=0A=
=0A=
Swisscom Root EV CA 2=0A=
=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=0A=
-----BEGIN CERTIFICATE-----=0A=
MIIF4DCCA8igAwIBAgIRAPL6ZOJ0Y9ON/RAdBB92ylgwDQYJKoZIhvcNAQELBQAwZzELMAkGA=
1UE=0A=
BhMCY2gxETAPBgNVBAoTCFN3aXNzY29tMSUwIwYDVQQLExxEaWdpdGFsIENlcnRpZmljYXRlI=
FNl=0A=
cnZpY2VzMR4wHAYDVQQDExVTd2lzc2NvbSBSb290IEVWIENBIDIwHhcNMTEwNjI0MDk0NTA4W=
hcN=0A=
MzEwNjI1MDg0NTA4WjBnMQswCQYDVQQGEwJjaDERMA8GA1UEChMIU3dpc3Njb20xJTAjBgNVB=
AsT=0A=
HERpZ2l0YWwgQ2VydGlmaWNhdGUgU2VydmljZXMxHjAcBgNVBAMTFVN3aXNzY29tIFJvb3QgR=
VYg=0A=
Q0EgMjCCAiIwDQYJKoZIhvcNAQEBBQADggIPADCCAgoCggIBAMT3HS9X6lds93BdY7BxUglgR=
Cgz=0A=
o3pOCvrY6myLURYaVa5UJsTMRQdBTxB5f3HSek4/OE6zAMaVylvNwSqD1ycfMQ4jFrclyxy0u=
YAy=0A=
Xhqdk/HoPGAsp15XGVhRXrwsVgu42O+LgrQ8uMIkqBPHoCE2G3pXKSinLr9xJZDzRINpUKTk4=
Rti=0A=
GZQJo/PDvO/0vezbE53PnUgJUmfANykRHvvSEaeFGHR55E+FFOtSN+KxRdjMDUN/rhPSays/p=
8Li=0A=
qG12W0OfvrSdsyaGOx9/5fLoZigWJdBLlzin5M8J0TbDC77aO0RYjb7xnglrPvMyxyuHxuxen=
PaH=0A=
Za0zKcQvidm5y8kDnftslFGXEBuGCxobP/YCfnvUxVFkKJ3106yDgYjTdLRZncHrYTNaRdHLO=
dAG=0A=
alNgHa/2+2m8atwBz735j9m9W8E6X47aD0upm50qKGsaCnw8qyIL5XctcfaCNYGu+HuB5ur+r=
PQa=0A=
m3Rc6I8k9l2dRsQs0h4rIWqDJ2dVSqTjyDKXZpBy2uPUZC5f46Fq9mDU5zXNysRojddxyNMkM=
3Ox=0A=
bPlq4SjbX8Y96L5V5jcb7STZDxmPX2MYWFCBUWVv8p9+agTnNCRxunZLWB4ZvRVgRaoMEkABn=
RDi=0A=
xzgHcgplwLa7JSnaFp6LNYth7eVxV4O1PHGf40+/fh6Bn0GXAgMBAAGjgYYwgYMwDgYDVR0PA=
QH/=0A=
BAQDAgGGMB0GA1UdIQQWMBQwEgYHYIV0AVMCAgYHYIV0AVMCAjASBgNVHRMBAf8ECDAGAQH/A=
gED=0A=
MB0GA1UdDgQWBBRF2aWBbj2ITY1x0kbBbkUe88SAnTAfBgNVHSMEGDAWgBRF2aWBbj2ITY1x0=
kbB=0A=
bkUe88SAnTANBgkqhkiG9w0BAQsFAAOCAgEAlDpzBp9SSzBc1P6xXCX5145v9Ydkn+0UjrgEj=
ihL=0A=
j6p7jjm02Vj2e6E1CqGdivdj5eu9OYLU43otb98TPLr+flaYC/NUn81ETm484T4VvwYmneTwk=
LbU=0A=
wp4wLh/vx3rEUMfqe9pQy3omywC0Wqu1kx+AiYQElY2NfwmTv9SoqORjbdlk5LgpWgi/UOGED=
1V7=0A=
XwgiG/W9mR4U9s70WBCCswo9GcG/W6uqmdjyMb3lOGbcWAXH7WMaLgqXfIeTK7KK4/HsGOV1t=
imH=0A=
59yLGn602MnTihdsfSlEvoqq9X46Lmgxk7lq2prg2+kupYTNHAq4Sgj5nPFhJpiTt3tm7JFe3=
VE/=0A=
23MPrQRYCd0EApUKPtN236YQHoA96M2kZNEzx5LH4k5E4wnJTsJdhw4Snr8PyQUQ3nqjsTzyP=
6Wq=0A=
J3mtMX0f/fwZacXduT98zca0wjAefm6S139hdlqP65VNvBFuIXxZN5nQBrz5Bm0yFqXZaajh3=
DyA=0A=
HmBR3NdUIR7KYndP+tiPsys6DXhyyWhBWkdKwqPrGtcKqzwyVcgKEZzfdNbwQBUdyLmPtTbFr=
/gi=0A=
uMod89a2GQ+fYWVq6nTIfI/DT11lgh/ZDYnadXL77/FHZxOzyNEZiCcmmpl5fx7kLD977vHeT=
YuW=0A=
l8PVP3wbI+2ksx0WckNLIOFZfsLorSa/ovc=3D=0A=
-----END CERTIFICATE-----=0A=
=0A=
CA Disig Root R1=0A=
=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=0A=
-----BEGIN CERTIFICATE-----=0A=
MIIFaTCCA1GgAwIBAgIJAMMDmu5QkG4oMA0GCSqGSIb3DQEBBQUAMFIxCzAJBgNVBAYTAlNLM=
RMw=0A=
EQYDVQQHEwpCcmF0aXNsYXZhMRMwEQYDVQQKEwpEaXNpZyBhLnMuMRkwFwYDVQQDExBDQSBEa=
XNp=0A=
ZyBSb290IFIxMB4XDTEyMDcxOTA5MDY1NloXDTQyMDcxOTA5MDY1NlowUjELMAkGA1UEBhMCU=
0sx=0A=
EzARBgNVBAcTCkJyYXRpc2xhdmExEzARBgNVBAoTCkRpc2lnIGEucy4xGTAXBgNVBAMTEENBI=
ERp=0A=
c2lnIFJvb3QgUjEwggIiMA0GCSqGSIb3DQEBAQUAA4ICDwAwggIKAoICAQCqw3j33Jijp1ped=
xiy=0A=
3QRkD2P9m5YJgNXoqqXinCaUOuiZc4yd39ffg/N4T0Dhf9Kn0uXKE5Pn7cZ3Xza1lK/oOI7bm=
+V8=0A=
u8yN63Vz4STN5qctGS7Y1oprFOsIYgrY3LMATcMjfF9DCCMyEtztDK3AfQ+lekLZWnDZv6fXA=
Rz2=0A=
m6uOt0qGeKAeVjGu74IKgEH3G8muqzIm1Cxr7X1r5OJeIgpFy4QxTaz+29FHuvlglzmxZcfe+=
5nk=0A=
CiKxLU3lSCZpq+Kq8/v8kiky6bM+TR8noc2OuRf7JT7JbvN32g0S9l3HuzYQ1VTW8+DiR0jm3=
hTa=0A=
YVKvJrT1cU/J19IG32PK/yHoWQbgCNWEFVP3Q+V8xaCJmGtzxmjOZd69fwX3se72V6FglcXM6=
pM6=0A=
vpmumwKjrckWtc7dXpl4fho5frLABaTAgqWjR56M6ly2vGfb5ipN0gTco65F97yLnByn1tUD3=
AjL=0A=
LhbKXEAz6GfDLuemROoRRRw1ZS0eRWEkG4IupZ0zXWX4Qfkuy5Q/H6MMMSRE7cderVC6xkGbr=
PAX=0A=
ZcD4XW9boAo0PO7X6oifmPmvTiT6l7Jkdtqr9O3jw2Dv1fkCyC2fg69naQanMVXVz0tv/wQFx=
1is=0A=
XxYb5dKj6zHbHzMVTdDypVP1y+E9Tmgt2BLdqvLmTZtJ5cUoobqwWsagtQIDAQABo0IwQDAPB=
gNV=0A=
HRMBAf8EBTADAQH/MA4GA1UdDwEB/wQEAwIBBjAdBgNVHQ4EFgQUiQq0OJMa5qvum5EY+fU8P=
jXQ=0A=
04IwDQYJKoZIhvcNAQEFBQADggIBADKL9p1Kyb4U5YysOMo6CdQbzoaz3evUuii+Eq5FLAR0r=
BNR=0A=
xVgYZk2C2tXck8An4b58n1KeElb21Zyp9HWc+jcSjxyT7Ff+Bw+r1RL3D65hXlaASfX8MPWbT=
x9B=0A=
LxyE04nH4toCdu0Jz2zBuByDHBb6lM19oMgY0sidbvW9adRtPTXoHqJPYNcHKfyyo6SdbhWSV=
hlM=0A=
CrDpfNIZTUJG7L399ldb3Zh+pE3McgODWF3vkzpBemOqfDqo9ayk0d2iLbYq/J8BjuIQscTK5=
Gfb=0A=
VSUZP/3oNn6z4eGBrxEWi1CXYBmCAMBrTXO40RMHPuq2MU/wQppt4hF05ZSsjYSVPCGvxdpHy=
N85=0A=
YmLLW1AL14FABZyb7bq2ix4Eb5YgOe2kfSnbSM6C3NQCjR0EMVrHS/BsYVLXtFHCgWzN4funo=
dKS=0A=
ds+xDzdYpPJScWc/DIh4gInByLUfkmO+p3qKViwaqKactV2zY9ATIKHrkWzQjX2v3wvkF7mGn=
jix=0A=
lAxYjOBVqjtjbZqJYLhkKpLGN/R+Q0O3c+gB53+XD9fyexn9GtePyfqFa3qdnom2piiZk4hA9=
z7N=0A=
UaPK6u95RyG1/jLix8NRb76AdPCkwzryT+lf3xkK8jsTQ6wxpLPn6/wY1gGp8yqPNg7rtLG8t=
0zJ=0A=
a7+h89n07eLw4+1knj0vllJPgFOL=0A=
-----END CERTIFICATE-----=0A=
=0A=
CA Disig Root R2=0A=
=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=0A=
-----BEGIN CERTIFICATE-----=0A=
MIIFaTCCA1GgAwIBAgIJAJK4iNuwisFjMA0GCSqGSIb3DQEBCwUAMFIxCzAJBgNVBAYTAlNLM=
RMw=0A=
EQYDVQQHEwpCcmF0aXNsYXZhMRMwEQYDVQQKEwpEaXNpZyBhLnMuMRkwFwYDVQQDExBDQSBEa=
XNp=0A=
ZyBSb290IFIyMB4XDTEyMDcxOTA5MTUzMFoXDTQyMDcxOTA5MTUzMFowUjELMAkGA1UEBhMCU=
0sx=0A=
EzARBgNVBAcTCkJyYXRpc2xhdmExEzARBgNVBAoTCkRpc2lnIGEucy4xGTAXBgNVBAMTEENBI=
ERp=0A=
c2lnIFJvb3QgUjIwggIiMA0GCSqGSIb3DQEBAQUAA4ICDwAwggIKAoICAQCio8QACdaFXS1tF=
PbC=0A=
w3OeNcJxVX6B+6tGUODBfEl45qt5WDza/3wcn9iXAng+a0EE6UG9vgMsRfYvZNSrXaNHPWSb6=
Wia=0A=
xswbP7q+sos0Ai6YVRn8jG+qX9pMzk0DIaPY0jSTVpbLTAwAFjxfGs3Ix2ymrdMxp7zo5eFm1=
tL7=0A=
A7RBZckQrg4FY8aAamkw/dLukO8NJ9+flXP04SXabBbeQTg06ov80egEFGEtQX6sx3dOy1FU+=
16S=0A=
GBsEWmjGycT6txOgmLcRK7fWV8x8nhfRyyX+hk4kLlYMeE2eARKmK6cBZW58Yh2EhN/qwGu1p=
SqV=0A=
g8NTEQxzHQuyRpDRQjrOQG6Vrf/GlK1ul4SOfW+eioANSW1z4nuSHsPzwfPrLgVv2RvPN3YEy=
LRa=0A=
5Beny912H9AZdugsBbPWnDTYltxhh5EF5EQIM8HauQhl1K6yNg3ruji6DOWbnuuNZt2Zz9aJQ=
fYE=0A=
koopKW1rOhzndX0CcQ7zwOe9yxndnWCywmZgtrEE7snmhrmaZkCo5xHtgUUDi/ZnWejBBhG93=
c+A=0A=
Ak9lQHhcR1DIm+YfgXvkRKhbhZri3lrVx/k6RGZL5DJUfORsnLMOPReisjQS1n6yqEm70XooQ=
L6i=0A=
Fh/f5DcfEXP7kAplQ6INfPgGAVUzfbANuPT1rqVCV3w2EYx7XsQDnYx5nQIDAQABo0IwQDAPB=
gNV=0A=
HRMBAf8EBTADAQH/MA4GA1UdDwEB/wQEAwIBBjAdBgNVHQ4EFgQUtZn4r7CU9eMg1gqtzk5Wp=
C5u=0A=
Qu0wDQYJKoZIhvcNAQELBQADggIBACYGXnDnZTPIgm7ZnBc6G3pmsgH2eDtpXi/q/075KMOYK=
mFM=0A=
tCQSin1tERT3nLXK5ryeJ45MGcipvXrA1zYObYVybqjGom32+nNjf7xueQgcnYqfGopTpti72=
TVV=0A=
sRHFqQOzVju5hJMiXn7B9hJSi+osZ7z+Nkz1uM/Rs0mSO9MpDpkblvdhuDvEK7Z4bLQjb/D90=
7Je=0A=
dR+Zlais9trhxTF7+9FGs9K8Z7RiVLoJ92Owk6Ka+elSLotgEqv89WBW7xBci8QaQtyDW2QOy=
7W8=0A=
1k/BfDxujRNt+3vrMNDcTa/F1balTFtxyegxvug4BkihGuLq0t4SOVga/4AOgnXmt8kHbA7v/=
zjx=0A=
mHHEt38OFdAlab0inSvtBfZGR6ztwPDUO+Ls7pZbkBNOHlY667DvlruWIxG68kOGdGSVyCh13=
x01=0A=
utI3gzhTODY7z2zp+WsO0PsE6E9312UBeIYMej4hYvF/Y3EMyZ9E26gnonW+boE+18DrG5gPc=
Fw0=0A=
sorMwIUY6256s/daoQe/qUKS82Ail+QUoQebTnbAjn39pCXHR+3/H3OszMOl6W8KjptlwlCFt=
aOg=0A=
UxLMVYdh84GuEEZhvUQhuMI9dM9+JDX6HAcOmz0iyu8xL4ysEr3vQCj8KWefshNPZiTEUxnpH=
ikV=0A=
7+ZtsH8tZ/3zbBt1RqPlShfppNcL=0A=
-----END CERTIFICATE-----=0A=
=0A=
ACCVRAIZ1=0A=
=3D=3D=3D=3D=3D=3D=3D=3D=3D=0A=
-----BEGIN CERTIFICATE-----=0A=
MIIH0zCCBbugAwIBAgIIXsO3pkN/pOAwDQYJKoZIhvcNAQEFBQAwQjESMBAGA1UEAwwJQUNDV=
lJB=0A=
SVoxMRAwDgYDVQQLDAdQS0lBQ0NWMQ0wCwYDVQQKDARBQ0NWMQswCQYDVQQGEwJFUzAeFw0xM=
TA1=0A=
MDUwOTM3MzdaFw0zMDEyMzEwOTM3MzdaMEIxEjAQBgNVBAMMCUFDQ1ZSQUlaMTEQMA4GA1UEC=
wwH=0A=
UEtJQUNDVjENMAsGA1UECgwEQUNDVjELMAkGA1UEBhMCRVMwggIiMA0GCSqGSIb3DQEBAQUAA=
4IC=0A=
DwAwggIKAoICAQCbqau/YUqXry+XZpp0X9DZlv3P4uRm7x8fRzPCRKPfmt4ftVTdFXxpNRFvu=
8gM=0A=
jmoYHtiP2Ra8EEg2XPBjs5BaXCQ316PWywlxufEBcoSwfdtNgM3802/J+Nq2DoLSRYWoG2ioP=
ej0=0A=
RGy9ocLLA76MPhMAhN9KSMDjIgro6TenGEyxCQ0jVn8ETdkXhBilyNpAlHPrzg5XPAOBOp0Ko=
VdD=0A=
aaxXbXmQeOW1tDvYvEyNKKGno6e6Ak4l0Squ7a4DIrhrIA8wKFSVf+DuzgpmndFALW4ir50aw=
QUZ=0A=
0m/A8p/4e7MCQvtQqR0tkw8jq8bBD5L/0KIV9VMJcRz/RROE5iZe+OCIHAr8Fraocwa48GOEA=
qDG=0A=
WuzndN9wrqODJerWx5eHk6fGioozl2A3ED6XPm4pFdahD9GILBKfb6qkxkLrQaLjlUPTAYVtj=
rs7=0A=
8yM2x/474KElB0iryYl0/wiPgL/AlmXz7uxLaL2diMMxs0Dx6M/2OLuc5NF/1OVYm3z61PMOm=
3WR=0A=
5LpSLhl+0fXNWhn8ugb2+1KoS5kE3fj5tItQo05iifCHJPqDQsGH+tUtKSpacXpkatcnYGMN2=
85J=0A=
9Y0fkIkyF/hzQ7jSWpOGYdbhdQrqeWZ2iE9x6wQl1gpaepPluUsXQA+xtrn13k/c4LOsOxFwY=
IRK=0A=
Q26ZIMApcQrAZQIDAQABo4ICyzCCAscwfQYIKwYBBQUHAQEEcTBvMEwGCCsGAQUFBzAChkBod=
HRw=0A=
Oi8vd3d3LmFjY3YuZXMvZmlsZWFkbWluL0FyY2hpdm9zL2NlcnRpZmljYWRvcy9yYWl6YWNjd=
jEu=0A=
Y3J0MB8GCCsGAQUFBzABhhNodHRwOi8vb2NzcC5hY2N2LmVzMB0GA1UdDgQWBBTSh7Tj3zcnk=
1X2=0A=
VuqB5TbMjB4/vTAPBgNVHRMBAf8EBTADAQH/MB8GA1UdIwQYMBaAFNKHtOPfNyeTVfZW6oHlN=
syM=0A=
Hj+9MIIBcwYDVR0gBIIBajCCAWYwggFiBgRVHSAAMIIBWDCCASIGCCsGAQUFBwICMIIBFB6CA=
RAA=0A=
QQB1AHQAbwByAGkAZABhAGQAIABkAGUAIABDAGUAcgB0AGkAZgBpAGMAYQBjAGkA8wBuACAAU=
gBh=0A=
AO0AegAgAGQAZQAgAGwAYQAgAEEAQwBDAFYAIAAoAEEAZwBlAG4AYwBpAGEAIABkAGUAIABUA=
GUA=0A=
YwBuAG8AbABvAGcA7QBhACAAeQAgAEMAZQByAHQAaQBmAGkAYwBhAGMAaQDzAG4AIABFAGwAZ=
QBj=0A=
AHQAcgDzAG4AaQBjAGEALAAgAEMASQBGACAAUQA0ADYAMAAxADEANQA2AEUAKQAuACAAQwBQA=
FMA=0A=
IABlAG4AIABoAHQAdABwADoALwAvAHcAdwB3AC4AYQBjAGMAdgAuAGUAczAwBggrBgEFBQcCA=
RYk=0A=
aHR0cDovL3d3dy5hY2N2LmVzL2xlZ2lzbGFjaW9uX2MuaHRtMFUGA1UdHwROMEwwSqBIoEaGR=
Gh0=0A=
dHA6Ly93d3cuYWNjdi5lcy9maWxlYWRtaW4vQXJjaGl2b3MvY2VydGlmaWNhZG9zL3JhaXphY=
2N2=0A=
MV9kZXIuY3JsMA4GA1UdDwEB/wQEAwIBBjAXBgNVHREEEDAOgQxhY2N2QGFjY3YuZXMwDQYJK=
oZI=0A=
hvcNAQEFBQADggIBAJcxAp/n/UNnSEQU5CmH7UwoZtCPNdpNYbdKl02125DgBS4OxnnQ8pdpD=
70E=0A=
R9m+27Up2pvZrqmZ1dM8MJP1jaGo/AaNRPTKFpV8M9xii6g3+CfYCS0b78gUJyCpZET/LtZ1q=
mxN=0A=
YEAZSUNUY9rizLpm5U9EelvZaoErQNV/+QEnWCzI7UiRfD+mAM/EKXMRNt6GGT6d7hmKG9Ww7=
Y49=0A=
nCrADdg9ZuM8Db3VlFzi4qc1GwQA9j9ajepDvV+JHanBsMyZ4k0ACtrJJ1vnE5Bc5PUzolVt3=
OAJ=0A=
TS+xJlsndQAJxGJ3KQhfnlmstn6tn1QwIgPBHnFk/vk4CpYY3QIUrCPLBhwepH2NDd4nQeit2=
hW3=0A=
sCPdK6jT2iWH7ehVRE2I9DZ+hJp4rPcOVkkO1jMl1oRQQmwgEh0q1b688nCBpHBgvgW1m54ER=
L5h=0A=
I6zppSSMEYCUWqKiuUnSwdzRp+0xESyeGabu4VXhwOrPDYTkF7eifKXeVSUG7szAh1xA2syVP=
1Xg=0A=
Nce4hL60Xc16gwFy7ofmXx2utYXGJt/mwZrpHgJHnyqobalbz+xFd3+YJ5oyXSrjhO7FmGYvl=
iAd=0A=
3djDJ9ew+f7Zfc3Qn48LFFhRny+Lwzgt3uiP1o2HpPVWQxaZLPSkVrQ0uGE3ycJYgBugl6H8W=
Y3p=0A=
EfbRD0tVNEYqi4Y7=0A=
-----END CERTIFICATE-----=0A=
=0A=
TWCA Global Root CA=0A=
=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=0A=
-----BEGIN CERTIFICATE-----=0A=
MIIFQTCCAymgAwIBAgICDL4wDQYJKoZIhvcNAQELBQAwUTELMAkGA1UEBhMCVFcxEjAQBgNVB=
AoT=0A=
CVRBSVdBTi1DQTEQMA4GA1UECxMHUm9vdCBDQTEcMBoGA1UEAxMTVFdDQSBHbG9iYWwgUm9vd=
CBD=0A=
QTAeFw0xMjA2MjcwNjI4MzNaFw0zMDEyMzExNTU5NTlaMFExCzAJBgNVBAYTAlRXMRIwEAYDV=
QQK=0A=
EwlUQUlXQU4tQ0ExEDAOBgNVBAsTB1Jvb3QgQ0ExHDAaBgNVBAMTE1RXQ0EgR2xvYmFsIFJvb=
3Qg=0A=
Q0EwggIiMA0GCSqGSIb3DQEBAQUAA4ICDwAwggIKAoICAQCwBdvI64zEbooh745NnHEKH1Jw7=
W2C=0A=
nJfF10xORUnLQEK1EjRsGcJ0pDFfhQKX7EMzClPSnIyOt7h52yvVavKOZsTuKwEHktSz0ALfU=
PZV=0A=
r2YOy+BHYC8rMjk1Ujoog/h7FsYYuGLWRyWRzvAZEk2tY/XTP3VfKfChMBwqoJimFb3u/Rk28=
OKR=0A=
Q4/6ytYQJ0lM793B8YVwm8rqqFpD/G2Gb3PpN0Wp8DbHzIh1HrtsBv+baz4X7GGqcXzGHaL3S=
ekV=0A=
tTzWoWH1EfcFbx39Eb7QMAfCKbAJTibc46KokWofwpFFiFzlmLhxpRUZyXx1EcxwdE8tmx2RR=
P1W=0A=
KKD+u4ZqyPpcC1jcxkt2yKsi2XMPpfRaAok/T54igu6idFMqPVMnaR1sjjIsZAAmY2E2TqNGt=
z99=0A=
sy2sbZCilaLOz9qC5wc0GZbpuCGqKX6mOL6OKUohZnkfs8O1CWfe1tQHRvMq2uYiN2DLgbYPo=
A/p=0A=
yJV/v1WRBXrPPRXAb94JlAGD1zQbzECl8LibZ9WYkTunhHiVJqRaCPgrdLQABDzfuBSO6N+pj=
Wxn=0A=
kjMdwLfS7JLIvgm/LCkFbwJrnu+8vyq8W8BQj0FwcYeyTbcEqYSjMq+u7msXi7Kx/mzhkIyIq=
JdI=0A=
zshNy/MGz19qCkKxHh53L46g5pIOBvwFItIm4TFRfTLcDwIDAQABoyMwITAOBgNVHQ8BAf8EB=
AMC=0A=
AQYwDwYDVR0TAQH/BAUwAwEB/zANBgkqhkiG9w0BAQsFAAOCAgEAXzSBdu+WHdXltdkCY4QWw=
a6g=0A=
cFGn90xHNcgL1yg9iXHZqjNB6hQbbCEAwGxCGX6faVsgQt+i0trEfJdLjbDorMjupWkEmQqSp=
qsn=0A=
LhpNgb+E1HAerUf+/UqdM+DyucRFCCEK2mlpc3INvjT+lIutwx4116KD7+U4x6WFH6vPNOw/K=
P4M=0A=
8VeGTslV9xzU2KV9Bnpv1d8Q34FOIWWxtuEXeZVFBs5fzNxGiWNoRI2T9GRwoD2dKAXDOXC4Y=
nsg=0A=
/eTb6QihuJ49CcdP+yz4k3ZB3lLg4VfSnQO8d57+nile98FRYB/e2guyLXW3Q0iT5/Z5xoRdg=
Flg=0A=
lPx4mI88k1HtQJAH32RjJMtOcQWh15QaiDLxInQirqWm2BJpTGCjAu4r7NRjkgtevi92a6O2J=
ryP=0A=
A9gK8kxkRr05YuWW6zRjESjMlfGt7+/cgFhI6Uu46mWs6fyAtbXIRfmswZ/ZuepiiI7E8UuDE=
q3m=0A=
i4TWnsLrgxifarsbJGAzcMzs9zLzXNl5fe+epP7JI8Mk7hWSsT2RTyaGvWZzJBPqpK5jwa19h=
AM8=0A=
EHiGG3njxPPyBJUgriOCxLM6AGK/5jYk4Ve6xx6QddVfP5VhK8E7zeWzaGHQRiapIVJpLesux=
+t3=0A=
zqY6tQMzT3bR51xUAV3LePTJDL/PEo4XLSNolOer/qmyKwbQBM0=3D=0A=
-----END CERTIFICATE-----=0A=
=0A=
TeliaSonera Root CA v1=0A=
=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=0A=
-----BEGIN CERTIFICATE-----=0A=
MIIFODCCAyCgAwIBAgIRAJW+FqD3LkbxezmCcvqLzZYwDQYJKoZIhvcNAQEFBQAwNzEUMBIGA=
1UE=0A=
CgwLVGVsaWFTb25lcmExHzAdBgNVBAMMFlRlbGlhU29uZXJhIFJvb3QgQ0EgdjEwHhcNMDcxM=
DE4=0A=
MTIwMDUwWhcNMzIxMDE4MTIwMDUwWjA3MRQwEgYDVQQKDAtUZWxpYVNvbmVyYTEfMB0GA1UEA=
wwW=0A=
VGVsaWFTb25lcmEgUm9vdCBDQSB2MTCCAiIwDQYJKoZIhvcNAQEBBQADggIPADCCAgoCggIBA=
MK+=0A=
6yfwIaPzaSZVfp3FVRaRXP3vIb9TgHot0pGMYzHw7CTww6XScnwQbfQ3t+XmfHnqjLWCi65It=
qwA=0A=
3GV17CpNX8GH9SBlK4GoRz6JI5UwFpB/6FcHSOcZrr9FZ7E3GwYq/t75rH2D+1665I+XZ75Lj=
o1k=0A=
B1c4VWk0Nj0TSO9P4tNmHqTPGrdeNjPUtAa9GAH9d4RQAEX1jF3oI7x+/jXh7VB7qTCNGdMJj=
mhn=0A=
Xb88lxhTuylixcpecsHHltTbLaC0H2kD7OriUPEMPPCs81Mt8Bz17Ww5OXOAFshSsCPN4D7c3=
TxH=0A=
oLs1iuKYaIu+5b9y7tL6pe0S7fyYGKkmdtwoSxAgHNN/Fnct7W+A90m7UwW7XWjH1Mh1Fj+JW=
ov3=0A=
F0fUTPHSiXk+TT2YqGHeOh7S+F4D4MHJHIzTjU3TlTazN19jY5szFPAtJmtTfImMMsJu7D0hA=
DnJ=0A=
oWjiUIMusDor8zagrC/kb2HCUQk5PotTubtn2txTuXZZNp1D5SDgPTJghSJRt8czu90VL6R4p=
gd7=0A=
gUY2BIbdeTXHlSw7sKMXNeVzH7RcWe/a6hBle3rQf5+ztCo3O3CLm1u5K7fsslESl1MpWtTwE=
hDc=0A=
TwK7EpIvYtQ/aUN8Ddb8WHUBiJ1YFkveupD/RwGJBmr2X7KQarMCpgKIv7NHfirZ1fpoeDVNA=
gMB=0A=
AAGjPzA9MA8GA1UdEwEB/wQFMAMBAf8wCwYDVR0PBAQDAgEGMB0GA1UdDgQWBBTwj1k4ALP1j=
5qW=0A=
DNXr+nuqF+gTEjANBgkqhkiG9w0BAQUFAAOCAgEAvuRcYk4k9AwI//DTDGjkk0kiP0Qnb7tt3=
oNm=0A=
zqjMDfz1mgbldxSR651Be5kqhOX//CHBXfDkH1e3damhXwIm/9fH907eT/j3HEbAek9ALCI18=
Bmx=0A=
0GtnLLCo4MBANzX2hFxc469CeP6nyQ1Q6g2EdvZR74NTxnr/DlZJLo961gzmJ1TjTQpgcmLNk=
QfW=0A=
pb/ImWvtxBnmq0wROMVvMeJuScg/doAmAyYp4Db29iBT4xdwNBedY2gea+zDTYa4EzAvXUYNR=
0PV=0A=
G6pZDrlcjQZIrXSHX8f8MVRBE+LHIQ6e4B4N4cB7Q4WQxYpYxmUKeFfyxiMPAdkgS94P+5KFd=
Spc=0A=
c41teyWRyu5FrgZLAMzTsVlQ2jqIOylDRl6XK1TOU2+NSueW+r9xDkKLfP0ooNBIytrEgUy7o=
nOT=0A=
JsjrDNYmiLbAJM+7vVvrdX3pCI6GMyx5dwlppYn8s3CQh3aP0yK7Qs69cwsgJirQmz1wHiRsz=
Yd2=0A=
qReWt88NkvuOGKmYSdGe/mBEciG5Ge3C9THxOUiIkCR1VBatzvT4aRRkOfujuLpwQMcnHL/EV=
lP6=0A=
Y2XQ8xwOFvVrhlhNGNTkDY6lnVuR3HYkUD/GKvvZt5y11ubQ2egZixVxSK236thZiNSQvxaz2=
ems=0A=
WWFUyBy6ysHK4bkgTI86k4mloMy/0/Z1pHWWbVY=3D=0A=
-----END CERTIFICATE-----=0A=
=0A=
E-Tugra Certification Authority=0A=
=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=
=3D=3D=3D=3D=3D=3D=0A=
-----BEGIN CERTIFICATE-----=0A=
MIIGSzCCBDOgAwIBAgIIamg+nFGby1MwDQYJKoZIhvcNAQELBQAwgbIxCzAJBgNVBAYTAlRSM=
Q8w=0A=
DQYDVQQHDAZBbmthcmExQDA+BgNVBAoMN0UtVHXEn3JhIEVCRyBCaWxpxZ9pbSBUZWtub2xva=
mls=0A=
ZXJpIHZlIEhpem1ldGxlcmkgQS7Fni4xJjAkBgNVBAsMHUUtVHVncmEgU2VydGlmaWthc3lvb=
iBN=0A=
ZXJrZXppMSgwJgYDVQQDDB9FLVR1Z3JhIENlcnRpZmljYXRpb24gQXV0aG9yaXR5MB4XDTEzM=
DMw=0A=
NTEyMDk0OFoXDTIzMDMwMzEyMDk0OFowgbIxCzAJBgNVBAYTAlRSMQ8wDQYDVQQHDAZBbmthc=
mEx=0A=
QDA+BgNVBAoMN0UtVHXEn3JhIEVCRyBCaWxpxZ9pbSBUZWtub2xvamlsZXJpIHZlIEhpem1ld=
Gxl=0A=
cmkgQS7Fni4xJjAkBgNVBAsMHUUtVHVncmEgU2VydGlmaWthc3lvbiBNZXJrZXppMSgwJgYDV=
QQD=0A=
DB9FLVR1Z3JhIENlcnRpZmljYXRpb24gQXV0aG9yaXR5MIICIjANBgkqhkiG9w0BAQEFAAOCA=
g8A=0A=
MIICCgKCAgEA4vU/kwVRHoViVF56C/UYB4Oufq9899SKa6VjQzm5S/fDxmSJPZQuVIBSOTkHS=
0vd=0A=
hQd2h8y/L5VMzH2nPbxHD5hw+IyFHnSOkm0bQNGZDbt1bsipa5rAhDGvykPL6ys06I+XawGb1=
Q5K=0A=
CKpbknSFQ9OArqGIW66z6l7LFpp3RMih9lRozt6Plyu6W0ACDGQXwLWTzeHxE2bODHnv0ZEoq=
1+g=0A=
ElIwcxmOj+GMB6LDu0rw6h8VqO4lzKRG+Bsi77MOQ7osJLjFLFzUHPhdZL3Dk14opz8n8Y4e0=
ypQ=0A=
BaNV2cvnOVPAmJ6MVGKLJrD3fY185MaeZkJVgkfnsliNZvcHfC425lAcP9tDJMW/hkd5s3kc9=
1r0=0A=
E+xs+D/iWR+V7kI+ua2oMoVJl0b+SzGPWsutdEcf6ZG33ygEIqDUD13ieU/qbIWGvaimzuT6w=
+Gz=0A=
rt48Ue7LE3wBf4QOXVGUnhMMti6lTPk5cDZvlsouDERVxcr6XQKj39ZkjFqzAQqptQpHF//vk=
UAq=0A=
jqFGOjGY5RH8zLtJVor8udBhmm9lbObDyz51Sf6Pp+KJxWfXnUYTTjF2OySznhFlhqt/7x3U+=
Lzn=0A=
rFpct1pHXFXOVbQicVtbC/DP3KBhZOqp12gKY6fgDT+gr9Oq0n7vUaDmUStVkhUXU8u3Zg5mT=
Pj5=0A=
dUyQ5xJwx0UCAwEAAaNjMGEwHQYDVR0OBBYEFC7j27JJ0JxUeVz6Jyr+zE7S6E5UMA8GA1UdE=
wEB=0A=
/wQFMAMBAf8wHwYDVR0jBBgwFoAULuPbsknQnFR5XPonKv7MTtLoTlQwDgYDVR0PAQH/BAQDA=
gEG=0A=
MA0GCSqGSIb3DQEBCwUAA4ICAQAFNzr0TbdF4kV1JI+2d1LoHNgQk2Xz8lkGpD4eKexd0dCrf=
OAK=0A=
kEh47U6YA5n+KGCRHTAduGN8qOY1tfrTYXbm1gdLymmasoR6d5NFFxWfJNCYExL/u6Au/U5Mh=
/jO=0A=
XKqYGwXgAEZKgoClM4so3O0409/lPun++1ndYYRP0lSWE2ETPo+Aab6TR7U1Q9Jauz1c77NCR=
807=0A=
VRMGsAnb/WP2OogKmW9+4c4bU2pEZiNRCHu8W1Ki/QY3OEBhj0qWuJA3+GbHeJAAFS6LrVE1U=
weo=0A=
a2iu+U48BybNCAVwzDk/dr2l02cmAYamU9JgO3xDf1WKvJUawSg5TB9D0pH0clmKuVb8P7Sd2=
nCc=0A=
dlqMQ1DujjByTd//SffGqWfZbawCEeI6FiWnWAjLb1NBnEg4R2gz0dfHj9R0IdTDBZB6/86Wi=
LEV=0A=
KV0jq9BgoRJP3vQXzTLlyb/IQ639Lo7xr+L0mPoSHyDYwKcMhcWQ9DstliaxLL5Mq+ux0orJ2=
3gT=0A=
Dx4JnW2PAJ8C2sH6H3p6CcRK5ogql5+Ji/03X186zjhZhkuvcQu02PJwT58yE+Owp1fl2tpDy=
4Q0=0A=
8ijE6m30Ku/Ba3ba+367hTzSU8JNvnHhRdH9I2cNE3X7z2VnIp2usAnRCf8dNL/+I5c30jn6P=
Q0G=0A=
C7TbO6Orb1wdtn7os4I07QZcJA=3D=3D=0A=
-----END CERTIFICATE-----=0A=
=0A=
T-TeleSec GlobalRoot Class 2=0A=
=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=
=3D=3D=3D=0A=
-----BEGIN CERTIFICATE-----=0A=
MIIDwzCCAqugAwIBAgIBATANBgkqhkiG9w0BAQsFADCBgjELMAkGA1UEBhMCREUxKzApBgNVB=
AoM=0A=
IlQtU3lzdGVtcyBFbnRlcnByaXNlIFNlcnZpY2VzIEdtYkgxHzAdBgNVBAsMFlQtU3lzdGVtc=
yBU=0A=
cnVzdCBDZW50ZXIxJTAjBgNVBAMMHFQtVGVsZVNlYyBHbG9iYWxSb290IENsYXNzIDIwHhcNM=
Dgx=0A=
MDAxMTA0MDE0WhcNMzMxMDAxMjM1OTU5WjCBgjELMAkGA1UEBhMCREUxKzApBgNVBAoMIlQtU=
3lz=0A=
dGVtcyBFbnRlcnByaXNlIFNlcnZpY2VzIEdtYkgxHzAdBgNVBAsMFlQtU3lzdGVtcyBUcnVzd=
CBD=0A=
ZW50ZXIxJTAjBgNVBAMMHFQtVGVsZVNlYyBHbG9iYWxSb290IENsYXNzIDIwggEiMA0GCSqGS=
Ib3=0A=
DQEBAQUAA4IBDwAwggEKAoIBAQCqX9obX+hzkeXaXPSi5kfl82hVYAUdAqSzm1nzHoqvNK38D=
cLZ=0A=
SBnuaY/JIPwhqgcZ7bBcrGXHX+0CfHt8LRvWurmAwhiCFoT6ZrAIxlQjgeTNuUk/9k9uN0goO=
A/F=0A=
vudocP05l03Sx5iRUKrERLMjfTlH6VJi1hKTXrcxlkIF+3anHqP1wvzpesVsqXFP6st4vGCvx=
970=0A=
2cu+fjOlbpSD8DT6IavqjnKgP6TeMFvvhk1qlVtDRKgQFRzlAVfFmPHmBiiRqiDFt1MmUUOyC=
xGV=0A=
WOHAD3bZwI18gfNycJ5v/hqO2V81xrJvNHy+SE/iWjnX2J14np+GPgNeGYtEotXHAgMBAAGjQ=
jBA=0A=
MA8GA1UdEwEB/wQFMAMBAf8wDgYDVR0PAQH/BAQDAgEGMB0GA1UdDgQWBBS/WSA2AHmgoCJrj=
NXy=0A=
YdK4LMuCSjANBgkqhkiG9w0BAQsFAAOCAQEAMQOiYQsfdOhyNsZt+U2e+iKo4YFWz827n+qrk=
Rk4=0A=
r6p8FU3ztqONpfSO9kSpp+ghla0+AGIWiPACuvxhI+YzmzB6azZie60EI4RYZeLbK4rnJVM3Y=
lNf=0A=
vNoBYimipidx5joifsFvHZVwIEoHNN/q/xWA5brXethbdXwFeilHfkCoMRN3zUA7tFFHei4R4=
0cR=0A=
3p1m0IvVVGb6g1XqfMIpiRvpb7PO4gWEyS8+eIVibslfwXhjdFjASBgMmTnrpMwatXlajRWc2=
BQN=0A=
9noHV8cigwUtPJslJj0Ys6lDfMjIq2SPDqO/nBudMNva0Bkuqjzx+zOAduTNrRlPBSeOE6Fuw=
g=3D=3D=0A=
-----END CERTIFICATE-----=0A=
=0A=
Atos TrustedRoot 2011=0A=
=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=0A=
-----BEGIN CERTIFICATE-----=0A=
MIIDdzCCAl+gAwIBAgIIXDPLYixfszIwDQYJKoZIhvcNAQELBQAwPDEeMBwGA1UEAwwVQXRvc=
yBU=0A=
cnVzdGVkUm9vdCAyMDExMQ0wCwYDVQQKDARBdG9zMQswCQYDVQQGEwJERTAeFw0xMTA3MDcxN=
DU4=0A=
MzBaFw0zMDEyMzEyMzU5NTlaMDwxHjAcBgNVBAMMFUF0b3MgVHJ1c3RlZFJvb3QgMjAxMTENM=
AsG=0A=
A1UECgwEQXRvczELMAkGA1UEBhMCREUwggEiMA0GCSqGSIb3DQEBAQUAA4IBDwAwggEKAoIBA=
QCV=0A=
hTuXbyo7LjvPpvMpNb7PGKw+qtn4TaA+Gke5vJrf8v7MPkfoepbCJI419KkM/IL9bcFyYie96=
mvr=0A=
54rMVD6QUM+A1JX76LWC1BTFtqlVJVfbsVD2sGBkWXppzwO3bw2+yj5vdHLqqjAqc2K+SZFhy=
BH+=0A=
DgMq92og3AIVDV4VavzjgsG1xZ1kCWyjWZgHJ8cblithdHFsQ/H3NYkQ4J7sVaE3IqKHBAUsR=
320=0A=
HLliKWYoyrfhk/WklAOZuXCFteZI6o1Q/NnezG8HDt0Lcp2AMBYHlT8oDv3FdU9T1nSatCQuj=
gKR=0A=
z3bFmx5VdJx4IbHwLfELn8LVlhgf8FQieowHAgMBAAGjfTB7MB0GA1UdDgQWBBSnpQaxLKYJY=
O7R=0A=
l+lwrrw7GWzbITAPBgNVHRMBAf8EBTADAQH/MB8GA1UdIwQYMBaAFKelBrEspglg7tGX6XCuv=
DsZ=0A=
bNshMBgGA1UdIAQRMA8wDQYLKwYBBAGwLQMEAQEwDgYDVR0PAQH/BAQDAgGGMA0GCSqGSIb3D=
QEB=0A=
CwUAA4IBAQAmdzTblEiGKkGdLD4GkGDEjKwLVLgfuXvTBznk+j57sj1O7Z8jvZfza1zv7v1Ap=
t+h=0A=
k6EKhqzvINB5Ab149xnYJDE0BAGmuhWawyfc2E8PzBhj/5kPDpFrdRbhIfzYJsdHt6bPWHJxf=
rrh=0A=
TZVHO8mvbaG0weyJ9rQPOLXiZNwlz6bb65pcmaHFCN795trV1lpFDMS3wrUU77QR/w4VtfX12=
8a9=0A=
61qn8FYiqTxlVMYVqL2Gns2Dlmh6cYGJ4Qvh6hEbaAjMaZ7snkGeRDImeuKHCnE96+RapNLbx=
c3G=0A=
3mB/ufNPRJLvKrcYPqcZ2Qt9sTdBQrC6YB3y/gkRsPCHe6ed=0A=
-----END CERTIFICATE-----=0A=
=0A=
QuoVadis Root CA 1 G3=0A=
=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=0A=
-----BEGIN CERTIFICATE-----=0A=
MIIFYDCCA0igAwIBAgIUeFhfLq0sGUvjNwc1NBMotZbUZZMwDQYJKoZIhvcNAQELBQAwSDELM=
AkG=0A=
A1UEBhMCQk0xGTAXBgNVBAoTEFF1b1ZhZGlzIExpbWl0ZWQxHjAcBgNVBAMTFVF1b1ZhZGlzI=
FJv=0A=
b3QgQ0EgMSBHMzAeFw0xMjAxMTIxNzI3NDRaFw00MjAxMTIxNzI3NDRaMEgxCzAJBgNVBAYTA=
kJN=0A=
MRkwFwYDVQQKExBRdW9WYWRpcyBMaW1pdGVkMR4wHAYDVQQDExVRdW9WYWRpcyBSb290IENBI=
DEg=0A=
RzMwggIiMA0GCSqGSIb3DQEBAQUAA4ICDwAwggIKAoICAQCgvlAQjunybEC0BJyFuTHK3C3kE=
akE=0A=
PBtVwedYMB0ktMPvhd6MLOHBPd+C5k+tR4ds7FtJwUrVu4/sh6x/gpqG7D0DmVIB0jWerNrwU=
8lm=0A=
PNSsAgHaJNM7qAJGr6Qc4/hzWHa39g6QDbXwz8z6+cZM5cOGMAqNF34168Xfuw6cwI2H44g4h=
Wf6=0A=
Pser4BOcBRiYz5P1sZK0/CPTz9XEJ0ngnjybCKOLXSoh4Pw5qlPafX7PGglTvF0FBM+hSo+Ld=
oIN=0A=
ofjSxxR3W5A2B4GbPgb6Ul5jxaYA/qXpUhtStZI5cgMJYr2wYBZupt0lwgNm3fME0UDiTouG9=
G/l=0A=
g6AnhF4EwfWQvTA9xO+oabw4m6SkltFi2mnAAZauy8RRNOoMqv8hjlmPSlzkYZqn0ukqeI1RP=
ToV=0A=
7qJZjqlc3sX5kCLliEVx3ZGZbHqfPT2YfF72vhZooF6uCyP8Wg+qInYtyaEQHeTTRCOQiJ/GK=
ubX=0A=
9ZqzWB4vMIkIG1SitZgj7Ah3HJVdYdHLiZxfokqRmu8hqkkWCKi9YSgxyXSthfbZxbGL0eUQM=
k1f=0A=
iyA6PEkfM4VZDdvLCXVDaXP7a3F98N/ETH3Goy7IlXnLc6KOTk0k+17kBL5yG6YnLUlamXrXX=
Akg=0A=
t3+UuU/xDRxeiEIbEbfnkduebPRq34wGmAOtzCjvpUfzUwIDAQABo0IwQDAPBgNVHRMBAf8EB=
TAD=0A=
AQH/MA4GA1UdDwEB/wQEAwIBBjAdBgNVHQ4EFgQUo5fW816iEOGrRZ88F2Q87gFwnMwwDQYJK=
oZI=0A=
hvcNAQELBQADggIBABj6W3X8PnrHX3fHyt/PX8MSxEBd1DKquGrX1RUVRpgjpeaQWxiZTOOtQ=
qOC=0A=
MTaIzen7xASWSIsBx40Bz1szBpZGZnQdT+3Btrm0DWHMY37XLneMlhwqI2hrhVd2cDMT/uFPp=
iN3=0A=
GPoajOi9ZcnPP/TJF9zrx7zABC4tRi9pZsMbj/7sPtPKlL92CiUNqXsCHKnQO18LwIE6PWThv=
6ct=0A=
Tr1NxNgpxiIY0MWscgKCP6o6ojoilzHdCGPDdRS5YCgtW2jgFqlmgiNR9etT2DGbe+m3nUvri=
BbP=0A=
+V04ikkwj+3x6xn0dxoxGE1nVGwvb2X52z3sIexe9PSLymBlVNFxZPT5pqOBMzYzcfCkeF9Or=
YMh=0A=
3jRJjehZrJ3ydlo28hP0r+AJx2EqbPfgna67hkooby7utHnNkDPDs3b69fBsnQGQ+p6Q9pxyz=
0fa=0A=
wx/kNSBT8lTR32GDpgLiJTjehTItXnOQUl1CxM49S+H5GYQd1aJQzEH7QRTDvdbJWqNjZgKAv=
QU6=0A=
O0ec7AAmTPWIUb+oI38YB7AL7YsmoWTTYUrrXJ/es69nA7Mf3W1daWhpq1467HxpvMc7hU6eF=
bm0=0A=
FU/DlXpY18ls6Wy58yljXrQs8C097Vpl4KlbQMJImYFtnh8GKjwStIsPm6Ik8KaN1nrgS7Zkl=
mOV=0A=
hMJKzRwuJIczYOXD=0A=
-----END CERTIFICATE-----=0A=
=0A=
QuoVadis Root CA 2 G3=0A=
=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=0A=
-----BEGIN CERTIFICATE-----=0A=
MIIFYDCCA0igAwIBAgIURFc0JFuBiZs18s64KztbpybwdSgwDQYJKoZIhvcNAQELBQAwSDELM=
AkG=0A=
A1UEBhMCQk0xGTAXBgNVBAoTEFF1b1ZhZGlzIExpbWl0ZWQxHjAcBgNVBAMTFVF1b1ZhZGlzI=
FJv=0A=
b3QgQ0EgMiBHMzAeFw0xMjAxMTIxODU5MzJaFw00MjAxMTIxODU5MzJaMEgxCzAJBgNVBAYTA=
kJN=0A=
MRkwFwYDVQQKExBRdW9WYWRpcyBMaW1pdGVkMR4wHAYDVQQDExVRdW9WYWRpcyBSb290IENBI=
DIg=0A=
RzMwggIiMA0GCSqGSIb3DQEBAQUAA4ICDwAwggIKAoICAQChriWyARjcV4g/Ruv5r+LrI3Him=
tFh=0A=
ZiFfqq8nUeVuGxbULX1QsFN3vXg6YOJkApt8hpvWGo6t/x8Vf9WVHhLL5hSEBMHfNrMWn4rjy=
duY=0A=
NM7YMxcoRvynyfDStNVNCXJJ+fKH46nafaF9a7I6JaltUkSs+L5u+9ymc5GQYaYDFCDy54eji=
K2t=0A=
oIz/pgslUiXnFgHVy7g1gQyjO/Dh4fxaXc6AcW34Sas+O7q414AB+6XrW7PFXmAqMaCvN+ggO=
p+o=0A=
MiwMzAkd056OXbxMmO7FGmh77FOm6RQ1o9/NgJ8MSPsc9PG/Srj61YxxSscfrf5BmrODXfKEV=
u+l=0A=
V0POKa2Mq1W/xPtbAd0jIaFYAI7D0GoT7RPjEiuA3GfmlbLNHiJuKvhB1PLKFAeNilUSxmn1u=
IZo=0A=
L1NesNKqIcGY5jDjZ1XHm26sGahVpkUG0CM62+tlXSoREfA7T8pt9DTEceT/AFr2XK4jYIVz8=
eQQ=0A=
sSWu1ZK7E8EM4DnatDlXtas1qnIhO4M15zHfeiFuuDIIfR0ykRVKYnLP43ehvNURG3YBZwjgQ=
QvD=0A=
6xVu+KQZ2aKrr+InUlYrAoosFCT5v0ICvybIxo/gbjh9Uy3l7ZizlWNof/k19N+IxWA1ksB8a=
Rxh=0A=
lRbQ694Lrz4EEEVlWFA4r0jyWbYW8jwNkALGcC4BrTwV1wIDAQABo0IwQDAPBgNVHRMBAf8EB=
TAD=0A=
AQH/MA4GA1UdDwEB/wQEAwIBBjAdBgNVHQ4EFgQU7edvdlq/YOxJW8ald7tyFnGbxD0wDQYJK=
oZI=0A=
hvcNAQELBQADggIBAJHfgD9DCX5xwvfrs4iP4VGyvD11+ShdyLyZm3tdquXK4Qr36LLTn91nM=
X66=0A=
AarHakE7kNQIXLJgapDwyM4DYvmL7ftuKtwGTTwpD4kWilhMSA/ohGHqPHKmd+RCroijQ1h5f=
q7K=0A=
pVMNqT1wvSAZYaRsOPxDMuHBR//47PERIjKWnML2W2mWeyAMQ0GaW/ZZGYjeVYg3UQt4XAoeo=
0L9=0A=
x52ID8DyeAIkVJOviYeIyUqAHerQbj5hLja7NQ4nlv1mNDthcnPxFlxHBlRJAHpYErAK74X9s=
bgz=0A=
dWqTHBLmYF5vHX/JHyPLhGGfHoJE+V+tYlUkmlKY7VHnoX6XOuYvHxHaU4AshZ6rNRDbIl9qx=
V6X=0A=
U/IyAgkwo1jwDQHVcsaxfGl7w/U2Rcxhbl5MlMVerugOXou/983g7aEOGzPuVBj+D77vfoRrQ=
+Nw=0A=
mNtddbINWQeFFSM51vHfqSYP1kjHs6Yi9TM3WpVHn3u6GBVv/9YUZINJ0gpnIdsPNWNgKCLjs=
ZWD=0A=
zYWm3S8P52dSbrsvhXz1SnPnxT7AvSESBT/8twNJAlvIJebiVDj1eYeMHVOyToV7BjjHLPj4s=
HKN=0A=
JeV3UvQDHEimUF+IIDBu8oJDqz2XhOdT+yHBTw8imoa4WSr2Rz0ZiC3oheGe7IUIarFsNMkd7=
Egr=0A=
O3jtZsSOeWmD3n+M=0A=
-----END CERTIFICATE-----=0A=
=0A=
QuoVadis Root CA 3 G3=0A=
=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=0A=
-----BEGIN CERTIFICATE-----=0A=
MIIFYDCCA0igAwIBAgIULvWbAiin23r/1aOp7r0DoM8Sah0wDQYJKoZIhvcNAQELBQAwSDELM=
AkG=0A=
A1UEBhMCQk0xGTAXBgNVBAoTEFF1b1ZhZGlzIExpbWl0ZWQxHjAcBgNVBAMTFVF1b1ZhZGlzI=
FJv=0A=
b3QgQ0EgMyBHMzAeFw0xMjAxMTIyMDI2MzJaFw00MjAxMTIyMDI2MzJaMEgxCzAJBgNVBAYTA=
kJN=0A=
MRkwFwYDVQQKExBRdW9WYWRpcyBMaW1pdGVkMR4wHAYDVQQDExVRdW9WYWRpcyBSb290IENBI=
DMg=0A=
RzMwggIiMA0GCSqGSIb3DQEBAQUAA4ICDwAwggIKAoICAQCzyw4QZ47qFJenMioKVjZ/aEzHs=
286=0A=
IxSR/xl/pcqs7rN2nXrpixurazHb+gtTTK/FpRp5PIpM/6zfJd5O2YIyC0TeytuMrKNuFoM7p=
mRL=0A=
Mon7FhY4futD4tN0SsJiCnMK3UmzV9KwCoWdcTzeo8vAMvMBOSBDGzXRU7Ox7sWTaYI+FrUoR=
qHe=0A=
6okJ7UO4BUaKhvVZR74bbwEhELn9qdIoyhA5CcoTNs+cra1AdHkrAj80//ogaX3T7mH1urPnM=
NA3=0A=
I4ZyYUUpSFlob3emLoG+B01vr87ERRORFHAGjx+f+IdpsQ7vw4kZ6+ocYfx6bIrc1gMLnia6E=
t3U=0A=
VDmrJqMz6nWB2i3ND0/kA9HvFZcba5DFApCTZgIhsUfei5pKgLlVj7WiL8DWM2fafsSntARE6=
0f7=0A=
5li59wzweyuxwHApw0BiLTtIadwjPEjrewl5qW3aqDCYz4ByA4imW0aucnl8CAMhZa634Ryls=
Sqi=0A=
Md5mBPfAdOhx3v89WcyWJhKLhZVXGqtrdQtEPREoPHtht+KPZ0/l7DxMYIBpVzgeAVuNVejH3=
8DM=0A=
dyM0SXV89pgR6y3e7UEuFAUCf+D+IOs15xGsIs5XPd7JMG0QA4XN8f+MFrXBsj6IbGB/kE+V9=
/Yt=0A=
rQE5BwT6dYB9v0lQ7e/JxHwc64B+27bQ3RP+ydOc17KXqQIDAQABo0IwQDAPBgNVHRMBAf8EB=
TAD=0A=
AQH/MA4GA1UdDwEB/wQEAwIBBjAdBgNVHQ4EFgQUxhfQvKjqAkPyGwaZXSuQILnXnOQwDQYJK=
oZI=0A=
hvcNAQELBQADggIBADRh2Va1EodVTd2jNTFGu6QHcrxfYWLopfsLN7E8trP6KZ1/AvWkyaiTt=
3px=0A=
KGmPc+FSkNrVvjrlt3ZqVoAh313m6Tqe5T72omnHKgqwGEfcIHB9UqM+WXzBusnIFUBhynLWc=
KzS=0A=
t/Ac5IYp8M7vaGPQtSCKFWGafoaYtMnCdvvMujAWzKNhxnQT5WvvoxXqA/4Ti2Tk08HS6IT7S=
dEQ=0A=
TXlm66r99I0xHnAUrdzeZxNMgRVhvLfZkXdxGYFgu/BYpbWcC/ePIlUnwEsBbTuZDdQdm2NnL=
9Du=0A=
DcpmvJRPpq3t/O5jrFc/ZSXPsoaP0Aj/uHYUbt7lJ+yreLVTubY/6CD50qi+YUbKh4yE8/nxo=
Gib=0A=
Ih6BJpsQBJFxwAYf3KDTuVan45gtf4Od34wrnDKOMpTwATwiKp9Dwi7DmDkHOHv8XgBCH/MyJ=
nmD=0A=
hPbl8MFREsALHgQjDFSlTC9JxUrRtm5gDWv8a4uFJGS3iQ6rJUdbPM9+Sb3H6QrG2vd+DhcI0=
0iX=0A=
0HGS8A85PjRqHH3Y8iKuu2n0M7SmSFXRDw4m6Oy2Cy2nhTXN/VnIn9HNPlopNLk9hM6xZdRZk=
ZFW=0A=
dSHBd575euFgndOtBBj0fOtek49TSiIp+EgrPk2GrFt/ywaZWWDYWGWVjUTR939+J399roD1B=
0y2=0A=
PpxxVJkES/1Y+Zj0=0A=
-----END CERTIFICATE-----=0A=
=0A=
DigiCert Assured ID Root G2=0A=
=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=
=3D=3D=0A=
-----BEGIN CERTIFICATE-----=0A=
MIIDljCCAn6gAwIBAgIQC5McOtY5Z+pnI7/Dr5r0SzANBgkqhkiG9w0BAQsFADBlMQswCQYDV=
QQG=0A=
EwJVUzEVMBMGA1UEChMMRGlnaUNlcnQgSW5jMRkwFwYDVQQLExB3d3cuZGlnaWNlcnQuY29tM=
SQw=0A=
IgYDVQQDExtEaWdpQ2VydCBBc3N1cmVkIElEIFJvb3QgRzIwHhcNMTMwODAxMTIwMDAwWhcNM=
zgw=0A=
MTE1MTIwMDAwWjBlMQswCQYDVQQGEwJVUzEVMBMGA1UEChMMRGlnaUNlcnQgSW5jMRkwFwYDV=
QQL=0A=
ExB3d3cuZGlnaWNlcnQuY29tMSQwIgYDVQQDExtEaWdpQ2VydCBBc3N1cmVkIElEIFJvb3QgR=
zIw=0A=
ggEiMA0GCSqGSIb3DQEBAQUAA4IBDwAwggEKAoIBAQDZ5ygvUj82ckmIkzTz+GoeMVSAn61UQ=
bVH=0A=
35ao1K+ALbkKz3X9iaV9JPrjIgwrvJUXCzO/GU1BBpAAvQxNEP4HteccbiJVMWWXvdMX0h5i8=
9vq=0A=
bFCMP4QMls+3ywPgym2hFEwbid3tALBSfK+RbLE4E9HpEgjAALAcKxHad3A2m67OeYfcgnDmC=
XRw=0A=
VWmvo2ifv922ebPynXApVfSr/5Vh88lAbx3RvpO704gqu52/clpWcTs/1PPRCv4o76Pu2ZmvA=
9OP=0A=
YLfykqGxvYmJHzDNw6YuYjOuFgJ3RFrngQo8p0Quebg/BLxcoIfhG69Rjs3sLPr4/m3wOnyqi=
+Rn=0A=
lTGNAgMBAAGjQjBAMA8GA1UdEwEB/wQFMAMBAf8wDgYDVR0PAQH/BAQDAgGGMB0GA1UdDgQWB=
BTO=0A=
w0q5mVXyuNtgv6l+vVa1lzan1jANBgkqhkiG9w0BAQsFAAOCAQEAyqVVjOPIQW5pJ6d1Ee88h=
jZv=0A=
0p3GeDgdaZaikmkuOGybfQTUiaWxMTeKySHMq2zNixya1r9I0jJmwYrA8y8678Dj1JGG0VDjA=
9tz=0A=
d29KOVPt3ibHtX2vK0LRdWLjSisCx1BL4GnilmwORGYQRI+tBev4eaymG+g3NJ1TyWGqolKvS=
nAW=0A=
hsI6yLETcDbYz+70CjTVW0z9B5yiutkBclzzTcHdDrEcDcRjvq30FPuJ7KJBDkzMyFdA0G4Dq=
s0M=0A=
jomZmWzwPDCvON9vvKO+KSAnq3T/EyJ43pdSVR6DtVQgA+6uwE9W3jfMw3+qBCe703e4YtsXf=
Jwo=0A=
IhNzbM8m9Yop5w=3D=3D=0A=
-----END CERTIFICATE-----=0A=
=0A=
DigiCert Assured ID Root G3=0A=
=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=
=3D=3D=0A=
-----BEGIN CERTIFICATE-----=0A=
MIICRjCCAc2gAwIBAgIQC6Fa+h3foLVJRK/NJKBs7DAKBggqhkjOPQQDAzBlMQswCQYDVQQGE=
wJV=0A=
UzEVMBMGA1UEChMMRGlnaUNlcnQgSW5jMRkwFwYDVQQLExB3d3cuZGlnaWNlcnQuY29tMSQwI=
gYD=0A=
VQQDExtEaWdpQ2VydCBBc3N1cmVkIElEIFJvb3QgRzMwHhcNMTMwODAxMTIwMDAwWhcNMzgwM=
TE1=0A=
MTIwMDAwWjBlMQswCQYDVQQGEwJVUzEVMBMGA1UEChMMRGlnaUNlcnQgSW5jMRkwFwYDVQQLE=
xB3=0A=
d3cuZGlnaWNlcnQuY29tMSQwIgYDVQQDExtEaWdpQ2VydCBBc3N1cmVkIElEIFJvb3QgRzMwd=
jAQ=0A=
BgcqhkjOPQIBBgUrgQQAIgNiAAQZ57ysRGXtzbg/WPuNsVepRC0FFfLvC/8QdJ+1YlJfZn4f5=
dwb=0A=
RXkLzMZTCp2NXQLZqVneAlr2lSoOjThKiknGvMYDOAdfVdp+CW7if17QRSAPWXYQ1qAk8C3eN=
vJs=0A=
KTmjQjBAMA8GA1UdEwEB/wQFMAMBAf8wDgYDVR0PAQH/BAQDAgGGMB0GA1UdDgQWBBTL0L2p4=
ZgF=0A=
UaFNN6KDec6NHSrkhDAKBggqhkjOPQQDAwNnADBkAjAlpIFFAmsSS3V0T8gj43DydXLefInwz=
5Fy=0A=
YZ5eEJJZVrmDxxDnOOlYJjZ91eQ0hjkCMHw2U/Aw5WJjOpnitqM7mzT6HtoQknFekROn3aRuk=
swy=0A=
1vUhZscv6pZjamVFkpUBtA=3D=3D=0A=
-----END CERTIFICATE-----=0A=
=0A=
DigiCert Global Root G2=0A=
=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=0A=
-----BEGIN CERTIFICATE-----=0A=
MIIDjjCCAnagAwIBAgIQAzrx5qcRqaC7KGSxHQn65TANBgkqhkiG9w0BAQsFADBhMQswCQYDV=
QQG=0A=
EwJVUzEVMBMGA1UEChMMRGlnaUNlcnQgSW5jMRkwFwYDVQQLExB3d3cuZGlnaWNlcnQuY29tM=
SAw=0A=
HgYDVQQDExdEaWdpQ2VydCBHbG9iYWwgUm9vdCBHMjAeFw0xMzA4MDExMjAwMDBaFw0zODAxM=
TUx=0A=
MjAwMDBaMGExCzAJBgNVBAYTAlVTMRUwEwYDVQQKEwxEaWdpQ2VydCBJbmMxGTAXBgNVBAsTE=
Hd3=0A=
dy5kaWdpY2VydC5jb20xIDAeBgNVBAMTF0RpZ2lDZXJ0IEdsb2JhbCBSb290IEcyMIIBIjANB=
gkq=0A=
hkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAuzfNNNx7a8myaJCtSnX/RrohCgiN9RlUyfuI2/Ou8=
jqJ=0A=
kTx65qsGGmvPrC3oXgkkRLpimn7Wo6h+4FR1IAWsULecYxpsMNzaHxmx1x7e/dfgy5SDN67sH=
0NO=0A=
3Xss0r0upS/kqbitOtSZpLYl6ZtrAGCSYP9PIUkY92eQq2EGnI/yuum06ZIya7XzV+hdG82MH=
auV=0A=
BJVJ8zUtluNJbd134/tJS7SsVQepj5WztCO7TG1F8PapspUwtP1MVYwnSlcUfIKdzXOS0xZKB=
gyM=0A=
UNGPHgm+F6HmIcr9g+UQvIOlCsRnKPZzFBQ9RnbDhxSJITRNrw9FDKZJobq7nMWxM4MphQIDA=
QAB=0A=
o0IwQDAPBgNVHRMBAf8EBTADAQH/MA4GA1UdDwEB/wQEAwIBhjAdBgNVHQ4EFgQUTiJUIBiV5=
uNu=0A=
5g/6+rkS7QYXjzkwDQYJKoZIhvcNAQELBQADggEBAGBnKJRvDkhj6zHd6mcY1Yl9PMWLSn/pv=
tsr=0A=
F9+wX3N3KjITOYFnQoQj8kVnNeyIv/iPsGEMNKSuIEyExtv4NeF22d+mQrvHRAiGfzZ0JFrab=
A0U=0A=
WTW98kndth/Jsw1HKj2ZL7tcu7XUIOGZX1NGFdtom/DzMNU+MeKNhJ7jitralj41E6Vf8PlwU=
HBH=0A=
QRFXGU7Aj64GxJUTFy8bJZ918rGOmaFvE7FBcf6IKshPECBV1/MUReXgRPTqh5Uykw7+U0b6L=
J3/=0A=
iyK5S9kJRaTepLiaWN0bfVKfjllDiIGknibVb63dDcY3fe0Dkhvld1927jyNxF1WW6LZZm6zN=
Tfl=0A=
MrY=3D=0A=
-----END CERTIFICATE-----=0A=
=0A=
DigiCert Global Root G3=0A=
=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=0A=
-----BEGIN CERTIFICATE-----=0A=
MIICPzCCAcWgAwIBAgIQBVVWvPJepDU1w6QP1atFcjAKBggqhkjOPQQDAzBhMQswCQYDVQQGE=
wJV=0A=
UzEVMBMGA1UEChMMRGlnaUNlcnQgSW5jMRkwFwYDVQQLExB3d3cuZGlnaWNlcnQuY29tMSAwH=
gYD=0A=
VQQDExdEaWdpQ2VydCBHbG9iYWwgUm9vdCBHMzAeFw0xMzA4MDExMjAwMDBaFw0zODAxMTUxM=
jAw=0A=
MDBaMGExCzAJBgNVBAYTAlVTMRUwEwYDVQQKEwxEaWdpQ2VydCBJbmMxGTAXBgNVBAsTEHd3d=
y5k=0A=
aWdpY2VydC5jb20xIDAeBgNVBAMTF0RpZ2lDZXJ0IEdsb2JhbCBSb290IEczMHYwEAYHKoZIz=
j0C=0A=
AQYFK4EEACIDYgAE3afZu4q4C/sLfyHS8L6+c/MzXRq8NOrexpu80JX28MzQC7phW1FGfp4tn=
+6O=0A=
YwwX7Adw9c+ELkCDnOg/QW07rdOkFFk2eJ0DQ+4QE2xy3q6Ip6FrtUPOZ9wj/wMco+I+o0IwQ=
DAP=0A=
BgNVHRMBAf8EBTADAQH/MA4GA1UdDwEB/wQEAwIBhjAdBgNVHQ4EFgQUs9tIpPmhxdiuNkHME=
WNp=0A=
Yim8S8YwCgYIKoZIzj0EAwMDaAAwZQIxAK288mw/EkrRLTnDCgmXc/SINoyIJ7vmiI1Qhadj+=
Z4y=0A=
3maTD/HMsQmP3Wyr+mt/oAIwOWZbwmSNuJ5Q3KjVSaLtx9zRSX8XAbjIho9OjIgrqJqpisXRA=
L34=0A=
VOKa5Vt8sycX=0A=
-----END CERTIFICATE-----=0A=
=0A=
DigiCert Trusted Root G4=0A=
=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=0A=
-----BEGIN CERTIFICATE-----=0A=
MIIFkDCCA3igAwIBAgIQBZsbV56OITLiOQe9p3d1XDANBgkqhkiG9w0BAQwFADBiMQswCQYDV=
QQG=0A=
EwJVUzEVMBMGA1UEChMMRGlnaUNlcnQgSW5jMRkwFwYDVQQLExB3d3cuZGlnaWNlcnQuY29tM=
SEw=0A=
HwYDVQQDExhEaWdpQ2VydCBUcnVzdGVkIFJvb3QgRzQwHhcNMTMwODAxMTIwMDAwWhcNMzgwM=
TE1=0A=
MTIwMDAwWjBiMQswCQYDVQQGEwJVUzEVMBMGA1UEChMMRGlnaUNlcnQgSW5jMRkwFwYDVQQLE=
xB3=0A=
d3cuZGlnaWNlcnQuY29tMSEwHwYDVQQDExhEaWdpQ2VydCBUcnVzdGVkIFJvb3QgRzQwggIiM=
A0G=0A=
CSqGSIb3DQEBAQUAA4ICDwAwggIKAoICAQC/5pBzaN675F1KPDAiMGkz7MKnJS7JIT3yithZw=
uEp=0A=
pz1Yq3aaza57G4QNxDAf8xukOBbrVsaXbR2rsnnyyhHS5F/WBTxSD1Ifxp4VpX6+n6lXFllVc=
q9o=0A=
k3DCsrp1mWpzMpTREEQQLt+C8weE5nQ7bXHiLQwb7iDVySAdYyktzuxeTsiT+CFhmzTrBcZe7=
Fsa=0A=
vOvJz82sNEBfsXpm7nfISKhmV1efVFiODCu3T6cw2Vbuyntd463JT17lNecxy9qTXtyOj4Dat=
pGY=0A=
QJB5w3jHtrHEtWoYOAMQjdjUN6QuBX2I9YI+EJFwq1WCQTLX2wRzKm6RAXwhTNS8rhsDdV14Z=
tk6=0A=
MUSaM0C/CNdaSaTC5qmgZ92kJ7yhTzm1EVgX9yRcRo9k98FpiHaYdj1ZXUJ2h4mXaXpI8OCiE=
htm=0A=
mnTK3kse5w5jrubU75KSOp493ADkRSWJtppEGSt+wJS00mFt6zPZxd9LBADMfRyVw4/3IbKyE=
be7=0A=
f/LVjHAsQWCqsWMYRJUadmJ+9oCw++hkpjPRiQfhvbfmQ6QYuKZ3AeEPlAwhHbJUKSWJbOUOU=
lFH=0A=
dL4mrLZBdd56rF+NP8m800ERElvlEFDrMcXKchYiCd98THU/Y+whX8QgUWtvsauGi0/C1kVfn=
SD8=0A=
oR7FwI+isX4KJpn15GkvmB0t9dmpsh3lGwIDAQABo0IwQDAPBgNVHRMBAf8EBTADAQH/MA4GA=
1Ud=0A=
DwEB/wQEAwIBhjAdBgNVHQ4EFgQU7NfjgtJxXWRM3y5nP+e6mK4cD08wDQYJKoZIhvcNAQEMB=
QAD=0A=
ggIBALth2X2pbL4XxJEbw6GiAI3jZGgPVs93rnD5/ZpKmbnJeFwMDF/k5hQpVgs2SV1EY+Ctn=
JYY=0A=
ZhsjDT156W1r1lT40jzBQ0CuHVD1UvyQO7uYmWlrx8GnqGikJ9yd+SeuMIW59mdNOj6PWTkiU=
0Tr=0A=
yF0Dyu1Qen1iIQqAyHNm0aAFYF/opbSnr6j3bTWcfFqK1qI4mfN4i/RN0iAL3gTujJtHgXINw=
BQy=0A=
7zBZLq7gcfJW5GqXb5JQbZaNaHqasjYUegbyJLkJEVDXCLG4iXqEI2FCKeWjzaIgQdfRnGTZ6=
iah=0A=
ixTXTBmyUEFxPT9NcCOGDErcgdLMMpSEDQgJlxxPwO5rIHQw0uA5NBCFIRUBCOhVMt5xSdkoF=
1BN=0A=
5r5N0XWs0Mr7QbhDparTwwVETyw2m+L64kW4I1NsBm9nVX9GtUw/bihaeSbSpKhil9Ie4u1Ki=
7wb=0A=
/UdKDd9nZn6yW0HQO+T0O/QEY+nvwlQAUaCKKsnOeMzV6ocEGLPOr0mIr/OSmbaz5mEP0oUA5=
1Aa=0A=
5BuVnRmhuZyxm7EAHu/QD09CbMkKvO5D+jpxpchNJqU1/YldvIViHTLSoCtU7ZpXwdv6EM8Zt=
4tK=0A=
G48BtieVU+i2iW1bvGjUI+iLUaJW+fCmgKDWHrO8Dw9TdSmq6hN35N6MgSGtBxBHEa2HPQfRd=
bzP=0A=
82Z+=0A=
-----END CERTIFICATE-----=0A=
=0A=
WoSign=0A=
=3D=3D=3D=3D=3D=3D=0A=
-----BEGIN CERTIFICATE-----=0A=
MIIFdjCCA16gAwIBAgIQXmjWEXGUY1BWAGjzPsnFkTANBgkqhkiG9w0BAQUFADBVMQswCQYDV=
QQG=0A=
EwJDTjEaMBgGA1UEChMRV29TaWduIENBIExpbWl0ZWQxKjAoBgNVBAMTIUNlcnRpZmljYXRpb=
24g=0A=
QXV0aG9yaXR5IG9mIFdvU2lnbjAeFw0wOTA4MDgwMTAwMDFaFw0zOTA4MDgwMTAwMDFaMFUxC=
zAJ=0A=
BgNVBAYTAkNOMRowGAYDVQQKExFXb1NpZ24gQ0EgTGltaXRlZDEqMCgGA1UEAxMhQ2VydGlma=
WNh=0A=
dGlvbiBBdXRob3JpdHkgb2YgV29TaWduMIICIjANBgkqhkiG9w0BAQEFAAOCAg8AMIICCgKCA=
gEA=0A=
vcqNrLiRFVaXe2tcesLea9mhsMMQI/qnobLMMfo+2aYpbxY94Gv4uEBf2zmoAHqLoE1UfcIie=
PyO=0A=
CbiohdfMlZdLdNiefvAA5A6JrkkoRBoQmTIPJYhTpA2zDxIIFgsDcSccf+Hb0v1naMQFXQoOX=
XDX=0A=
2JegvFNBmpGN9J42Znp+VsGQX+axaCA2pIwkLCxHC1l2ZjC1vt7tj/id07sBMOby8w7gLJKA8=
4X5=0A=
KIq0VC6a7fd2/BVoFutKbOsuEo/Uz/4Mx1wdC34FMr5esAkqQtXJTpCzWQ27en7N1QhatH/YH=
GkR=0A=
+ScPewavVIMYe+HdVHpRaG53/Ma/UkpmRqGyZxq7o093oL5d//xWC0Nyd5DKnvnyOfUNqfTq1=
+ez=0A=
EC8wQjchzDBwyYaYD8xYTYO7feUapTeNtqwylwA6Y3EkHp43xP901DfA4v6IRmAR3Qg/UDaru=
Hqk=0A=
lWJqbrDKaiFaafPz+x1wOZXzp26mgYmhiMU7ccqjUu6Du/2gd/Tkb+dC221KmYo0SLwX3OSAC=
CK2=0A=
8jHAPwQ+658geda4BmRkAjHXqc1S+4RFaQkAKtxVi8QGRkvASh0JWzko/amrzgD5LkhLJuYwT=
KVY=0A=
yrREgk/nkR4zw7CT/xH8gdLKH3Ep3XZPkiWvHYG3Dy+MwwbMLyejSuQOmbp8HkUff6oZRZb9/=
D0C=0A=
AwEAAaNCMEAwDgYDVR0PAQH/BAQDAgEGMA8GA1UdEwEB/wQFMAMBAf8wHQYDVR0OBBYEFOFmz=
w7R=0A=
8bNLtwYgFP6HEtX2/vs+MA0GCSqGSIb3DQEBBQUAA4ICAQCoy3JAsnbBfnv8rWTjMnvMPLZdR=
tP1=0A=
LOJwXcgu2AZ9mNELIaCJWSQBnfmvCX0KI4I01fx8cpm5o9dU9OpScA7F9dY74ToJMuYhOZO9s=
xXq=0A=
T2r09Ys/L3yNWC7F4TmgPsc9SnOeQHrAK2GpZ8nzJLmzbVUsWh2eJXLOC62qx1ViC777Y7NhR=
COj=0A=
y+EaDveaBk3e1CNOIZZbOVtXHS9dCF4Jef98l7VNg64N1uajeeAz0JmWAjCnPv/So0M/BVoG6=
kQC=0A=
2nz4SNAzqfkHx5Xh9T71XXG68pWpdIhhWeO/yloTunK0jF02h+mmxTwTv97QRCbut+wucPrXn=
bes=0A=
5cVAWubXbHssw1abR80LzvobtCHXt2a49CUwi1wNuepnsvRtrtWhnk/Yn+knArAdBtaP4/tIE=
p9/=0A=
EaEQPkxROpaw0RPxx9gmrjrKkcRpnd8BKWRRb2jaFOwIQZeQjdCygPLPwj2/kWjFgGcexGATV=
dVh=0A=
mVd8upUPYUk6ynW8yQqTP2cOEvIo4jEbwFcW3wh8GcF+Dx+FHgo2fFt+J7x6v+Db9NpSvd4MV=
HAx=0A=
kUOVyLzwPt0JfjBkUO1/AaQzZ01oT74V77D2AhGiGxMlOtzCWfHjXEa7ZywCRuoeSKbmW9m1v=
FGi=0A=
kpbbqsY3Iqb+zCB0oy2pLmvLwIIRIbWTee5Ehr7XHuQe+w=3D=3D=0A=
-----END CERTIFICATE-----=0A=
=0A=
WoSign China=0A=
=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=0A=
-----BEGIN CERTIFICATE-----=0A=
MIIFWDCCA0CgAwIBAgIQUHBrzdgT/BtOOzNy0hFIjTANBgkqhkiG9w0BAQsFADBGMQswCQYDV=
QQG=0A=
EwJDTjEaMBgGA1UEChMRV29TaWduIENBIExpbWl0ZWQxGzAZBgNVBAMMEkNBIOayg+mAmuagu=
eiv=0A=
geS5pjAeFw0wOTA4MDgwMTAwMDFaFw0zOTA4MDgwMTAwMDFaMEYxCzAJBgNVBAYTAkNOMRowG=
AYD=0A=
VQQKExFXb1NpZ24gQ0EgTGltaXRlZDEbMBkGA1UEAwwSQ0Eg5rKD6YCa5qC56K+B5LmmMIICI=
jAN=0A=
BgkqhkiG9w0BAQEFAAOCAg8AMIICCgKCAgEA0EkhHiX8h8EqwqzbdoYGTufQdDTc7WU1/FDWi=
D+k=0A=
8H/rD195L4mx/bxjWDeTmzj4t1up+thxx7S8gJeNbEvxUNUqKaqoGXqW5pWOdO2XCld19AXbb=
Qs5=0A=
uQF/qvbW2mzmBeCkTVL829B0txGMe41P/4eDrv8FAxNXUDf+jJZSEExfv5RxadmWPgxDT74ww=
J85=0A=
dE8GRV2j1lY5aAfMh09Qd5Nx2UQIsYo06Yms25tO4dnkUkWMLhQfkWsZHWgpLFbE4h4TV2TwY=
eO5=0A=
Ed+w4VegG63XX9Gv2ystP9Bojg/qnw+LNVgbExz03jWhCl3W6t8Sb8D7aQdGctyB9gQjF+BNd=
eFy=0A=
b7Ao65vh4YOhn0pdr8yb+gIgthhid5E7o9Vlrdx8kHccREGkSovrlXLp9glk3Kgtn3R46MGiC=
WOc=0A=
76DbT52VqyBPt7D3h1ymoOQ3OMdc4zUPLK2jgKLsLl3Az+2LBcLmc272idX10kaO6m1jGx6Ky=
X2m=0A=
+Jzr5dVjhU1zZmkR/sgO9MHHZklTfuQZa/HpelmjbX7FF+Ynxu8b22/8DU0GAbQOXDBGVWCvO=
GU6=0A=
yke6rCzMRh+yRpY/8+0mBe53oWprfi1tWFxK1I5nuPHa1UaKJ/kR8slC/k7e3x9cxKSGhxYzo=
acX=0A=
GKUN5AXlK8IrC6KVkLn9YDxOiT7nnO4fuwECAwEAAaNCMEAwDgYDVR0PAQH/BAQDAgEGMA8GA=
1Ud=0A=
EwEB/wQFMAMBAf8wHQYDVR0OBBYEFOBNv9ybQV0T6GTwp+kVpOGBwboxMA0GCSqGSIb3DQEBC=
wUA=0A=
A4ICAQBqinA4WbbaixjIvirTthnVZil6Xc1bL3McJk6jfW+rtylNpumlEYOnOXOvEESS5iVdT=
2H6=0A=
yAa+Tkvv/vMx/sZ8cApBWNromUuWyXi8mHwCKe0JgOYKOoICKuLJL8hWGSbueBwj/feTZU7n8=
5iY=0A=
r83d2Z5AiDEoOqsuC7CsDCT6eiaY8xJhEPRdF/d+4niXVOKM6Cm6jBAyvd0zaziGfjk9DgNyp=
115=0A=
j0WKWa5bIW4xRtVZjc8VX90xJc/bYNaBRHIpAlf2ltTW/+op2znFuCyKGo3Oy+dCMYYFaA6eF=
N0A=0A=
kLppRQjbbpCBhqcqBT/mhDn4t/lXX0ykeVoQDF7Va/81XwVRHmyjdanPUIPTfPRm94KNPQx96=
N97=0A=
qA4bLJyuQHCH2u2nFoJavjVsIE4iYdm8UXrNemHcSxH5/mc0zy4EZmFcV5cjjPOGG0jfKq+nw=
f/Y=0A=
jj4Du9gqsPoUJbJRa4ZDhS4HIxaAjUz7tGM7zMN07RujHv41D198HRaG9Q7DlfEvr10lO1Hm1=
3ZB=0A=
ONFLAzkopR6RctR9q5czxNM+4Gm2KHmgCY0c0f9BckgG/Jou5yD5m6Leie2uPAmvylezkolwQ=
OQv=0A=
T8Jwg0DXJCxr5wkf09XHwQj02w47HAcLQxGEIYbpgNR12KvxAmLBsX5VYc8T1yaw15zLKYs4S=
gsO=0A=
kI26oQ=3D=3D=0A=
-----END CERTIFICATE-----=0A=
=0A=
COMODO RSA Certification Authority=0A=
=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=
=3D=3D=3D=3D=3D=3D=3D=3D=3D=0A=
-----BEGIN CERTIFICATE-----=0A=
MIIF2DCCA8CgAwIBAgIQTKr5yttjb+Af907YWwOGnTANBgkqhkiG9w0BAQwFADCBhTELMAkGA=
1UE=0A=
BhMCR0IxGzAZBgNVBAgTEkdyZWF0ZXIgTWFuY2hlc3RlcjEQMA4GA1UEBxMHU2FsZm9yZDEaM=
BgG=0A=
A1UEChMRQ09NT0RPIENBIExpbWl0ZWQxKzApBgNVBAMTIkNPTU9ETyBSU0EgQ2VydGlmaWNhd=
Glv=0A=
biBBdXRob3JpdHkwHhcNMTAwMTE5MDAwMDAwWhcNMzgwMTE4MjM1OTU5WjCBhTELMAkGA1UEB=
hMC=0A=
R0IxGzAZBgNVBAgTEkdyZWF0ZXIgTWFuY2hlc3RlcjEQMA4GA1UEBxMHU2FsZm9yZDEaMBgGA=
1UE=0A=
ChMRQ09NT0RPIENBIExpbWl0ZWQxKzApBgNVBAMTIkNPTU9ETyBSU0EgQ2VydGlmaWNhdGlvb=
iBB=0A=
dXRob3JpdHkwggIiMA0GCSqGSIb3DQEBAQUAA4ICDwAwggIKAoICAQCR6FSS0gpWsawNJN3Fz=
0Rn=0A=
dJkrN6N9I3AAcbxT38T6KhKPS38QVr2fcHK3YX/JSw8Xpz3jsARh7v8Rl8f0hj4K+j5c+ZPmN=
HrZ=0A=
FGvnnLOFoIJ6dq9xkNfs/Q36nGz637CC9BR++b7Epi9Pf5l/tfxnQ3K9DADWietrLNPtj5gcF=
Kt+=0A=
5eNu/Nio5JIk2kNrYrhV/erBvGy2i/MOjZrkm2xpmfh4SDBF1a3hDTxFYPwyllEnvGfDyi62a=
+pG=0A=
x8cgoLEfZd5ICLqkTqnyg0Y3hOvozIFIQ2dOciqbXL1MGyiKXCJ7tKuY2e7gUYPDCUZObT6Z+=
pUX=0A=
2nwzV0E8jVHtC7ZcryxjGt9XyD+86V3Em69FmeKjWiS0uqlWPc9vqv9JWL7wqP/0uK3pN/u6u=
PQL=0A=
OvnoQ0IeidiEyxPx2bvhiWC4jChWrBQdnArncevPDt09qZahSL0896+1DSJMwBGB7FY79tOi4=
lu3=0A=
sgQiUpWAk2nojkxl8ZEDLXB0AuqLZxUpaVICu9ffUGpVRr+goyhhf3DQw6KqLCGqR84onAZFd=
r+C=0A=
GCe01a60y1Dma/RMhnEw6abfFobg2P9A3fvQQoh/ozM6LlweQRGBY84YcWsr7KaKtzFcOmpH4=
MN5=0A=
WdYgGq/yapiqcrxXStJLnbsQ/LBMQeXtHT1eKJ2czL+zUdqnR+WEUwIDAQABo0IwQDAdBgNVH=
Q4E=0A=
FgQUu69+Aj36pvE8hI6t7jiY7NkyMtQwDgYDVR0PAQH/BAQDAgEGMA8GA1UdEwEB/wQFMAMBA=
f8w=0A=
DQYJKoZIhvcNAQEMBQADggIBAArx1UaEt65Ru2yyTUEUAJNMnMvlwFTPoCWOAvn9sKIN9SCYP=
BMt=0A=
rFaisNZ+EZLpLrqeLppysb0ZRGxhNaKatBYSaVqM4dc+pBroLwP0rmEdEBsqpIt6xf4FpuHA1=
sj+=0A=
nq6PK7o9mfjYcwlYRm6mnPTXJ9OV2jeDchzTc+CiR5kDOF3VSXkAKRzH7JsgHAckaVd4sjn8O=
oSg=0A=
tZx8jb8uk2IntznaFxiuvTwJaP+EmzzV1gsD41eeFPfR60/IvYcjt7ZJQ3mFXLrrkguhxuhoq=
EwW=0A=
sRqZCuhTLJK7oQkYdQxlqHvLI7cawiiFwxv/0Cti76R7CZGYZ4wUAc1oBmpjIXUDgIiKboHGh=
fKp=0A=
pC3n9KUkEEeDys30jXlYsQab5xoq2Z0B15R97QNKyvDb6KkBPvVWmckejkk9u+UJueBPSZI9F=
oJA=0A=
zMxZxuY67RIuaTxslbH9qh17f4a+Hg4yRvv7E491f0yLS0Zj/gA0QHDBw7mh3aZw4gSzQbzpg=
JHq=0A=
ZJx64SIDqZxubw5lT2yHh17zbqD5daWbQOhTsiedSrnAdyGN/4fy3ryM7xfft0kL0fJuMAsaD=
k52=0A=
7RH89elWsn2/x20Kk4yl0MC2Hb46TpSi125sC8KKfPog88Tk5c0NqMuRkrF8hey1FGlmDoLnz=
c7I=0A=
LaZRfyHBNVOFBkpdn627G190=0A=
-----END CERTIFICATE-----=0A=
=0A=
USERTrust RSA Certification Authority=0A=
=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=
=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=0A=
-----BEGIN CERTIFICATE-----=0A=
MIIF3jCCA8agAwIBAgIQAf1tMPyjylGoG7xkDjUDLTANBgkqhkiG9w0BAQwFADCBiDELMAkGA=
1UE=0A=
BhMCVVMxEzARBgNVBAgTCk5ldyBKZXJzZXkxFDASBgNVBAcTC0plcnNleSBDaXR5MR4wHAYDV=
QQK=0A=
ExVUaGUgVVNFUlRSVVNUIE5ldHdvcmsxLjAsBgNVBAMTJVVTRVJUcnVzdCBSU0EgQ2VydGlma=
WNh=0A=
dGlvbiBBdXRob3JpdHkwHhcNMTAwMjAxMDAwMDAwWhcNMzgwMTE4MjM1OTU5WjCBiDELMAkGA=
1UE=0A=
BhMCVVMxEzARBgNVBAgTCk5ldyBKZXJzZXkxFDASBgNVBAcTC0plcnNleSBDaXR5MR4wHAYDV=
QQK=0A=
ExVUaGUgVVNFUlRSVVNUIE5ldHdvcmsxLjAsBgNVBAMTJVVTRVJUcnVzdCBSU0EgQ2VydGlma=
WNh=0A=
dGlvbiBBdXRob3JpdHkwggIiMA0GCSqGSIb3DQEBAQUAA4ICDwAwggIKAoICAQCAEmUXNg7D2=
wiz=0A=
0KxXDXbtzSfTTK1Qg2HiqiBNCS1kCdzOiZ/MPans9s/B3PHTsdZ7NygRK0faOca8Ohm0X6a9f=
Z2j=0A=
Y0K2dvKpOyuR+OJv0OwWIJAJPuLodMkYtJHUYmTbf6MG8YgYapAiPLz+E/CHFHv25B+O1ORRx=
hFn=0A=
RghRy4YUVD+8M/5+bJz/Fp0YvVGONaanZshyZ9shZrHUm3gDwFA66Mzw3LyeTP6vBZY1H1dat=
//O=0A=
+T23LLb2VN3I5xI6Ta5MirdcmrS3ID3KfyI0rn47aGYBROcBTkZTmzNg95S+UzeQc0PzMsNT7=
9uq=0A=
/nROacdrjGCT3sTHDN/hMq7MkztReJVni+49Vv4M0GkPGw/zJSZrM233bkf6c0Plfg6lZrEpf=
DKE=0A=
Y1WJxA3Bk1QwGROs0303p+tdOmw1XNtB1xLaqUkL39iAigmTYo61Zs8liM2EuLE/pDkP2QKe6=
xJM=0A=
lXzzawWpXhaDzLhn4ugTncxbgtNMs+1b/97lc6wjOy0AvzVVdAlJ2ElYGn+SNuZRkg7zJn0cT=
Re8=0A=
yexDJtC/QV9AqURE9JnnV4eeUB9XVKg+/XRjL7FQZQnmWEIuQxpMtPAlR1n6BB6T1CZGSlCBs=
t6+=0A=
eLf8ZxXhyVeEHg9j1uliutZfVS7qXMYoCAQlObgOK6nyTJccBz8NUvXt7y+CDwIDAQABo0IwQ=
DAd=0A=
BgNVHQ4EFgQUU3m/WqorSs9UgOHYm8Cd8rIDZsswDgYDVR0PAQH/BAQDAgEGMA8GA1UdEwEB/=
wQF=0A=
MAMBAf8wDQYJKoZIhvcNAQEMBQADggIBAFzUfA3P9wF9QZllDHPFUp/L+M+ZBn8b2kMVn54CV=
VeW=0A=
FPFSPCeHlCjtHzoBN6J2/FNQwISbxmtOuowhT6KOVWKR82kV2LyI48SqC/3vqOlLVSoGIG1Ve=
CkZ=0A=
7l8wXEskEVX/JJpuXior7gtNn3/3ATiUFJVDBwn7YKnuHKsSjKCaXqeYalltiz8I+8jRRa8YF=
WSQ=0A=
Eg9zKC7F4iRO/Fjs8PRF/iKz6y+O0tlFYQXBl2+odnKPi4w2r78NBc5xjeambx9spnFixdjQg=
3IM=0A=
8WcRiQycE0xyNN+81XHfqnHd4blsjDwSXWXavVcStkNr/+XeTWYRUc+ZruwXtuhxkYzeSf7dN=
XGi=0A=
FSeUHM9h4ya7b6NnJSFd5t0dCy5oGzuCr+yDZ4XUmFF0sbmZgIn/f3gZXHlKYC6SQK5MNyosy=
cdi=0A=
yA5d9zZbyuAlJQG03RoHnHcAP9Dc1ew91Pq7P8yF1m9/qS3fuQL39ZeatTXaw2ewh0qpKJ4jj=
v9c=0A=
J2vhsE/zB+4ALtRZh8tSQZXq9EfX7mRBVXyNWQKV3WKdwrnuWih0hKWbt5DHDAff9Yk2dDLWK=
MGw=0A=
sAvgnEzDHNb842m1R0aBL6KCq9NjRHDEjf8tM7qtj3u1cIiuPhnPQCjY/MiQu12ZIvVS5ljFH=
4gx=0A=
Q+6IHdfGjjxDah2nGN59PRbxYvnKkKj9=0A=
-----END CERTIFICATE-----=0A=
=0A=
USERTrust ECC Certification Authority=0A=
=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=
=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=0A=
-----BEGIN CERTIFICATE-----=0A=
MIICjzCCAhWgAwIBAgIQXIuZxVqUxdJxVt7NiYDMJjAKBggqhkjOPQQDAzCBiDELMAkGA1UEB=
hMC=0A=
VVMxEzARBgNVBAgTCk5ldyBKZXJzZXkxFDASBgNVBAcTC0plcnNleSBDaXR5MR4wHAYDVQQKE=
xVU=0A=
aGUgVVNFUlRSVVNUIE5ldHdvcmsxLjAsBgNVBAMTJVVTRVJUcnVzdCBFQ0MgQ2VydGlmaWNhd=
Glv=0A=
biBBdXRob3JpdHkwHhcNMTAwMjAxMDAwMDAwWhcNMzgwMTE4MjM1OTU5WjCBiDELMAkGA1UEB=
hMC=0A=
VVMxEzARBgNVBAgTCk5ldyBKZXJzZXkxFDASBgNVBAcTC0plcnNleSBDaXR5MR4wHAYDVQQKE=
xVU=0A=
aGUgVVNFUlRSVVNUIE5ldHdvcmsxLjAsBgNVBAMTJVVTRVJUcnVzdCBFQ0MgQ2VydGlmaWNhd=
Glv=0A=
biBBdXRob3JpdHkwdjAQBgcqhkjOPQIBBgUrgQQAIgNiAAQarFRaqfloI+d61SRvU8Za2Eurx=
tW2=0A=
0eZzca7dnNYMYf3boIkDuAUU7FfO7l0/4iGzzvfUinngo4N+LZfQYcTxmdwlkWOrfzCjtHDix=
6Ez=0A=
nPO/LlxTsV+zfTJ/ijTjeXmjQjBAMB0GA1UdDgQWBBQ64QmG1M8ZwpZ2dEl23OA1xmNjmjAOB=
gNV=0A=
HQ8BAf8EBAMCAQYwDwYDVR0TAQH/BAUwAwEB/zAKBggqhkjOPQQDAwNoADBlAjA2Z6EWCNzkl=
wBB=0A=
HU6+4WMBzzuqQhFkoJ2UOQIReVx7Hfpkue4WQrO/isIJxOzksU0CMQDpKmFHjFJKS04YcPbWR=
NZu=0A=
9YO6bVi9JNlWSOrvxKJGgYhqOkbRqZtNyWHa0V1Xahg=3D=0A=
-----END CERTIFICATE-----=0A=
=0A=
GlobalSign ECC Root CA - R4=0A=
=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=
=3D=3D=0A=
-----BEGIN CERTIFICATE-----=0A=
MIIB4TCCAYegAwIBAgIRKjikHJYKBN5CsiilC+g0mAIwCgYIKoZIzj0EAwIwUDEkMCIGA1UEC=
xMb=0A=
R2xvYmFsU2lnbiBFQ0MgUm9vdCBDQSAtIFI0MRMwEQYDVQQKEwpHbG9iYWxTaWduMRMwEQYDV=
QQD=0A=
EwpHbG9iYWxTaWduMB4XDTEyMTExMzAwMDAwMFoXDTM4MDExOTAzMTQwN1owUDEkMCIGA1UEC=
xMb=0A=
R2xvYmFsU2lnbiBFQ0MgUm9vdCBDQSAtIFI0MRMwEQYDVQQKEwpHbG9iYWxTaWduMRMwEQYDV=
QQD=0A=
EwpHbG9iYWxTaWduMFkwEwYHKoZIzj0CAQYIKoZIzj0DAQcDQgAEuMZ5049sJQ6fLjkZHAOkr=
prl=0A=
OQcJFspjsbmG+IpXwVfOQvpzofdlQv8ewQCybnMO/8ch5RikqtlxP6jUuc6MHaNCMEAwDgYDV=
R0P=0A=
AQH/BAQDAgEGMA8GA1UdEwEB/wQFMAMBAf8wHQYDVR0OBBYEFFSwe61FuOJAf/sKbvu+M8k8o=
4TV=0A=
MAoGCCqGSM49BAMCA0gAMEUCIQDckqGgE6bPA7DmxCGXkPoUVy0D7O48027KqGx2vKLeuwIgJ=
6iF=0A=
JzWbVsaj8kfSt24bAgAXqmemFZHe+pTsewv4n4Q=3D=0A=
-----END CERTIFICATE-----=0A=
=0A=
GlobalSign ECC Root CA - R5=0A=
=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=
=3D=3D=0A=
-----BEGIN CERTIFICATE-----=0A=
MIICHjCCAaSgAwIBAgIRYFlJ4CYuu1X5CneKcflK2GwwCgYIKoZIzj0EAwMwUDEkMCIGA1UEC=
xMb=0A=
R2xvYmFsU2lnbiBFQ0MgUm9vdCBDQSAtIFI1MRMwEQYDVQQKEwpHbG9iYWxTaWduMRMwEQYDV=
QQD=0A=
EwpHbG9iYWxTaWduMB4XDTEyMTExMzAwMDAwMFoXDTM4MDExOTAzMTQwN1owUDEkMCIGA1UEC=
xMb=0A=
R2xvYmFsU2lnbiBFQ0MgUm9vdCBDQSAtIFI1MRMwEQYDVQQKEwpHbG9iYWxTaWduMRMwEQYDV=
QQD=0A=
EwpHbG9iYWxTaWduMHYwEAYHKoZIzj0CAQYFK4EEACIDYgAER0UOlvt9Xb/pOdEh+J8LttV7H=
pI6=0A=
SFkc8GIxLcB6KP4ap1yztsyX50XUWPrRd21DosCHZTQKH3rd6zwzocWdTaRvQZU4f8kehOvRn=
kmS=0A=
h5SHDDqFSmafnVmTTZdhBoZKo0IwQDAOBgNVHQ8BAf8EBAMCAQYwDwYDVR0TAQH/BAUwAwEB/=
zAd=0A=
BgNVHQ4EFgQUPeYpSJvqB8ohREom3m7e0oPQn1kwCgYIKoZIzj0EAwMDaAAwZQIxAOVpEslu2=
8Yx=0A=
uglB4Zf4+/2a4n0Sye18ZNPLBSWLVtmg515dTguDnFt2KaAJJiFqYgIwcdK1j1zqO+F4CYWod=
ZI7=0A=
yFz9SO8NdCKoCOJuxUnOxwy8p2Fp8fc74SrL+SvzZpA3=0A=
-----END CERTIFICATE-----=0A=
=0A=
Staat der Nederlanden Root CA - G3=0A=
=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=
=3D=3D=3D=3D=3D=3D=3D=3D=3D=0A=
-----BEGIN CERTIFICATE-----=0A=
MIIFdDCCA1ygAwIBAgIEAJiiOTANBgkqhkiG9w0BAQsFADBaMQswCQYDVQQGEwJOTDEeMBwGA=
1UE=0A=
CgwVU3RhYXQgZGVyIE5lZGVybGFuZGVuMSswKQYDVQQDDCJTdGFhdCBkZXIgTmVkZXJsYW5kZ=
W4g=0A=
Um9vdCBDQSAtIEczMB4XDTEzMTExNDExMjg0MloXDTI4MTExMzIzMDAwMFowWjELMAkGA1UEB=
hMC=0A=
TkwxHjAcBgNVBAoMFVN0YWF0IGRlciBOZWRlcmxhbmRlbjErMCkGA1UEAwwiU3RhYXQgZGVyI=
E5l=0A=
ZGVybGFuZGVuIFJvb3QgQ0EgLSBHMzCCAiIwDQYJKoZIhvcNAQEBBQADggIPADCCAgoCggIBA=
L4y=0A=
olQPcPssXFnrbMSkUeiFKrPMSjTysF/zDsccPVMeiAho2G89rcKezIJnByeHaHE6n3WWIkYFs=
O2t=0A=
x1ueKt6c/DrGlaf1F2cY5y9JCAxcz+bMNO14+1Cx3Gsy8KL+tjzk7FqXxz8ecAgwoNzFs21v0=
IJy=0A=
EavSgWhZghe3eJJg+szeP4TrjTgzkApyI/o1zCZxMdFyKJLZWyNtZrVtB0LrpjPOktvA9mxje=
M3K=0A=
Tj215VKb8b475lRgsGYeCasH/lSJEULR9yS6YHgamPfJEf0WwTUaVHXvQ9Plrk7O53vDxk5hU=
Uur=0A=
mkVLoR9BvUhTFXFkC4az5S6+zqQbwSmEorXLCCN2QyIkHxcE1G6cxvx/K2Ya7Irl1s9N9WMJt=
xU5=0A=
1nus6+N86U78dULI7ViVDAZCopz35HCz33JvWjdAidiFpNfxC95DGdRKWCyMijmev4SH8RY7N=
gzp=0A=
07TKbBlBUgmhHbBqv4LvcFEhMtwFdozL92TkA1CvjJFnq8Xy7ljY3r735zHPbMk7ccHViLVlv=
MDo=0A=
FxcHErVc0qsgk7TmgoNwNsXNo42ti+yjwUOH5kPiNL6VizXtBznaqB16nzaeErAMZRKQFWDZJ=
kBE=0A=
41ZgpRDUajz9QdwOWke275dhdU/Z/seyHdTtXUmzqWrLZoQT1Vyg3N9udwbRcXXIV2+vD3dbA=
gMB=0A=
AAGjQjBAMA8GA1UdEwEB/wQFMAMBAf8wDgYDVR0PAQH/BAQDAgEGMB0GA1UdDgQWBBRUrfrHk=
leu=0A=
yjWcLhL75LpdINyUVzANBgkqhkiG9w0BAQsFAAOCAgEAMJmdBTLIXg47mAE6iqTnB/d6+Oea3=
1BD=0A=
U5cqPco8R5gu4RV78ZLzYdqQJRZlwJ9UXQ4DO1t3ApyEtg2YXzTdO2PCwyiBwpwpLiniyMMB8=
jPq=0A=
KqrMCQj3ZWfGzd/TtiunvczRDnBfuCPRy5FOCvTIeuXZYzbB1N/8Ipf3YF3qKS9Ysr1YvY2WT=
xB1=0A=
v0h7PVGHoTx0IsL8B3+A3MSs/mrBcDCw6Y5p4ixpgZQJut3+TcCDjJRYwEYgr5wfAvg1VUkvR=
tTA=0A=
8KCWAg8zxXHzniN9lLf9OtMJgwYh/WA9rjLA0u6NpvDntIJ8CsxwyXmA+P5M9zWEGYox+wrZ1=
3+b=0A=
8KKaa8MFSu1BYBQw0aoRQm7TIwIEC8Zl3d1Sd9qBa7Ko+gE4uZbqKmxnl4mUnrzhVNXkanjvS=
r0r=0A=
mj1AfsbAddJu+2gw7OyLnflJNZoaLNmzlTnVHpL3prllL+U9bTpITAjc5CgSKL59NVzq4BZ+E=
xtq=0A=
1z7XnvwtdbLBFNUjA9tbbws+eC8N3jONFrdI54OagQ97wUNNVQQXOEpR1VmiiXTTn74eS9fGb=
beI=0A=
JG9gkaSChVtWQbzQRKtqE77RLFi3EjNYsjdj3BP1lB0/QFH1T/U67cjF68IeHRaVesd+QnGTb=
ksV=0A=
tzDfqu1XhUisHWrdOWnk4Xl4vs4Fv6EM94B7IWcnMFk=3D=0A=
-----END CERTIFICATE-----=0A=
=0A=
Staat der Nederlanden EV Root CA=0A=
=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=
=3D=3D=3D=3D=3D=3D=3D=0A=
-----BEGIN CERTIFICATE-----=0A=
MIIFcDCCA1igAwIBAgIEAJiWjTANBgkqhkiG9w0BAQsFADBYMQswCQYDVQQGEwJOTDEeMBwGA=
1UE=0A=
CgwVU3RhYXQgZGVyIE5lZGVybGFuZGVuMSkwJwYDVQQDDCBTdGFhdCBkZXIgTmVkZXJsYW5kZ=
W4g=0A=
RVYgUm9vdCBDQTAeFw0xMDEyMDgxMTE5MjlaFw0yMjEyMDgxMTEwMjhaMFgxCzAJBgNVBAYTA=
k5M=0A=
MR4wHAYDVQQKDBVTdGFhdCBkZXIgTmVkZXJsYW5kZW4xKTAnBgNVBAMMIFN0YWF0IGRlciBOZ=
WRl=0A=
cmxhbmRlbiBFViBSb290IENBMIICIjANBgkqhkiG9w0BAQEFAAOCAg8AMIICCgKCAgEA48d+i=
fkk=0A=
SzrSM4M1LGns3Amk41GoJSt5uAg94JG6hIXGhaTK5skuU6TJJB79VWZxXSzFYGgEt9nCUiY4i=
KTW=0A=
O0Cmws0/zZiTs1QUWJZV1VD+hq2kY39ch/aO5ieSZxeSAgMs3NZmdO3dZ//BYY1jTw+bbRcwJ=
u+r=0A=
0h8QoPnFfxZpgQNH7R5ojXKhTbImxrpsX23Wr9GxE46prfNeaXUmGD5BKyF/7otdBwadQ8QpC=
iv8=0A=
Kj6GyzyDOvnJDdrFmeK8eEEzduG/L13lpJhQDBXd4Pqcfzho0LKmeqfRMb1+ilgnQ7O6M5HTp=
5gV=0A=
XJrm0w912fxBmJc+qiXbj5IusHsMX/FjqTf5m3VpTCgmJdrV8hJwRVXj33NeN/UhbJCONVrJ0=
yPr=0A=
08C+eKxCKFhmpUZtcALXEPlLVPxdhkqHz3/KRawRWrUgUY0viEeXOcDPusBCAUCZSCELa6fS/=
ZbV=0A=
0b5GnUngC6agIk440ME8MLxwjyx1zNDFjFE7PZQIZCZhfbnDZY8UnCHQqv0XcgOPvZuM5l5Tn=
rmd=0A=
74K74bzickFbIZTTRTeU0d8JOV3nI6qaHcptqAqGhYqCvkIH1vI4gnPah1vlPNOePqc7nvQDs=
/nx=0A=
fRN0Av+7oeX6AHkcpmZBiFxgV6YuCcS6/ZrPpx9Aw7vMWgpVSzs4dlG4Y4uElBbmVvMCAwEAA=
aNC=0A=
MEAwDwYDVR0TAQH/BAUwAwEB/zAOBgNVHQ8BAf8EBAMCAQYwHQYDVR0OBBYEFP6rAJCYniT8q=
cwa=0A=
ivsnuL8wbqg7MA0GCSqGSIb3DQEBCwUAA4ICAQDPdyxuVr5Os7aEAJSrR8kN0nbHhp8dB9O2t=
LsI=0A=
eK9p0gtJ3jPFrK3CiAJ9Brc1AsFgyb/E6JTe1NOpEyVa/m6irn0F3H3zbPB+po3u2dfOWBfoq=
Smu=0A=
c0iH55vKbimhZF8ZE/euBhD/UcabTVUlT5OZEAFTdfETzsemQUHSv4ilf0X8rLiltTMMgsT7B=
/Zq=0A=
5SWEXwbKwYY5EdtYzXc7LMJMD16a4/CrPmEbUCTCwPTxGfARKbalGAKb12NMcIxHowNDXLldR=
qAN=0A=
b/9Zjr7dn3LDWyvfjFvO5QxGbJKyCqNMVEIYFRIYvdr8unRu/8G2oGTYqV9Vrp9canaW2HNnh=
/tN=0A=
f1zuacpzEPuKqf2evTY4SUmH9A4U8OmHuD+nT3pajnnUk+S7aFKErGzp85hwVXIy+TSrK0m1z=
SBi=0A=
5Dp6Z2Orltxtrpfs/J92VoguZs9btsmksNcFuuEnL5O7Jiqik7Ab846+HUCjuTaPPoIaGl6I6=
lD4=0A=
WeKDRikL40Rc4ZW2aZCaFG+XroHPaO+Zmr615+F/+PoTRxZMzG0IQOeLeG9QgkRQP2YGiqtDh=
FZK=0A=
DyAthg710tvSeopLzaXoTvFeJiUBWSOgftL2fiFX1ye8FVdMpEbB4IMeDExNH08GGeL5qPQ6g=
qGy=0A=
eUN51q1veieQA6TqJIc/2b3Z6fJfUEkc7uzXLg=3D=3D=0A=
-----END CERTIFICATE-----=0A=
=0A=
IdenTrust Commercial Root CA 1=0A=
=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=
=3D=3D=3D=3D=3D=0A=
-----BEGIN CERTIFICATE-----=0A=
MIIFYDCCA0igAwIBAgIQCgFCgAAAAUUjyES1AAAAAjANBgkqhkiG9w0BAQsFADBKMQswCQYDV=
QQG=0A=
EwJVUzESMBAGA1UEChMJSWRlblRydXN0MScwJQYDVQQDEx5JZGVuVHJ1c3QgQ29tbWVyY2lhb=
CBS=0A=
b290IENBIDEwHhcNMTQwMTE2MTgxMjIzWhcNMzQwMTE2MTgxMjIzWjBKMQswCQYDVQQGEwJVU=
zES=0A=
MBAGA1UEChMJSWRlblRydXN0MScwJQYDVQQDEx5JZGVuVHJ1c3QgQ29tbWVyY2lhbCBSb290I=
ENB=0A=
IDEwggIiMA0GCSqGSIb3DQEBAQUAA4ICDwAwggIKAoICAQCnUBneP5k91DNG8W9RYYKyqU+PZ=
4ld=0A=
hNlT3Qwo2dfw/66VQ3KZ+bVdfIrBQuExUHTRgQ18zZshq0PirK1ehm7zCYofWjK9ouuU+ehcC=
uz/=0A=
mNKvcbO0U59Oh++SvL3sTzIwiEsXXlfEU8L2ApeN2WIrvyQfYo3fw7gpS0l4PJNgiCL8mdo2y=
MKi=0A=
1CxUAGc1bnO/AljwpN3lsKImesrgNqUZFvX9t++uP0D1bVoE/c40yiTcdCMbXTMTEl3EASX2M=
N0C=0A=
XZ/g1Ue9tOsbobtJSdifWwLziuQkkORiT0/Br4sOdBeo0XKIanoBScy0RnnGF7HamB4HWfp1I=
YVl=0A=
3ZBWzvurpWCdxJ35UrCLvYf5jysjCiN2O/cz4ckA82n5S6LgTrx+kzmEB/dEcH7+B1rlsazRG=
Mzy=0A=
NeVJSQjKVsk9+w8YfYs7wRPCTY/JTw436R+hDmrfYi7LNQZReSzIJTj0+kuniVyc0uMNOYZKd=
HzV=0A=
WYfCP04MXFL0PfdSgvHqo6z9STQaKPNBiDoT7uje/5kdX7rL6B7yuVBgwDHTc+XvvqDtMwt0v=
iAg=0A=
xGds8AgDelWAf0ZOlqf0Hj7h9tgJ4TNkK2PXMl6f+cB7D3hvl7yTmvmcEpB4eoCHFddydJxVd=
Hix=0A=
uuFucAS6T6C6aMN7/zHwcz09lCqxC0EOoP5NiGVreTO01wIDAQABo0IwQDAOBgNVHQ8BAf8EB=
AMC=0A=
AQYwDwYDVR0TAQH/BAUwAwEB/zAdBgNVHQ4EFgQU7UQZwNPwBovupHu+QucmVMiONnYwDQYJK=
oZI=0A=
hvcNAQELBQADggIBAA2ukDL2pkt8RHYZYR4nKM1eVO8lvOMIkPkp165oCOGUAFjvLi5+U1KMt=
lwH=0A=
6oi6mYtQlNeCgN9hCQCTrQ0U5s7B8jeUeLBfnLOic7iPBZM4zY0+sLj7wM+x8uwtLRvM7Kqas=
6pg=0A=
ghstO8OEPVeKlh6cdbjTMM1gCIOQ045U8U1mwF10A0Cj7oV+wh93nAbowacYXVKV7cndJZ5t+=
qnt=0A=
ozo00Fl72u1Q8zW/7esUTTHHYPTa8Yec4kjixsU3+wYQ+nVZZjFHKdp2mhzpgq7vmrlR94gjm=
mmV=0A=
YjzlVYA211QC//G5Xc7UI2/YRYRKW2XviQzdFKcgyxilJbQN+QHwotL0AMh0jqEqSI5l2xPE4=
iUX=0A=
feu+h1sXIFRRk0pTAwvsXcoz7WL9RccvW9xYoIA55vrX/hMUpu09lEpCdNTDd1lzzY9GvlU47=
/ro=0A=
kTLql1gEIt44w8y8bckzOmoKaT+gyOpyj4xjhiO9bTyWnpXgSUyqorkqG5w2gXjtw+hG4iZZR=
HUe=0A=
2XWJUc0QhJ1hYMtd+ZciTY6Y5uN/9lu7rs3KSoFrXgvzUeF0K+l+J6fZmUlO+KWA2yUPHGNii=
skz=0A=
Z2s8EIPGrd6ozRaOjfAHN3Gf8qv8QfXBi+wAN10J5U6A7/qxXDgGpRtK4dw4LTzcqx+QGtVKn=
O7R=0A=
cGzM7vRX+Bi6hG6H=0A=
-----END CERTIFICATE-----=0A=
=0A=
IdenTrust Public Sector Root CA 1=0A=
=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=
=3D=3D=3D=3D=3D=3D=3D=3D=0A=
-----BEGIN CERTIFICATE-----=0A=
MIIFZjCCA06gAwIBAgIQCgFCgAAAAUUjz0Z8AAAAAjANBgkqhkiG9w0BAQsFADBNMQswCQYDV=
QQG=0A=
EwJVUzESMBAGA1UEChMJSWRlblRydXN0MSowKAYDVQQDEyFJZGVuVHJ1c3QgUHVibGljIFNlY=
3Rv=0A=
ciBSb290IENBIDEwHhcNMTQwMTE2MTc1MzMyWhcNMzQwMTE2MTc1MzMyWjBNMQswCQYDVQQGE=
wJV=0A=
UzESMBAGA1UEChMJSWRlblRydXN0MSowKAYDVQQDEyFJZGVuVHJ1c3QgUHVibGljIFNlY3Rvc=
iBS=0A=
b290IENBIDEwggIiMA0GCSqGSIb3DQEBAQUAA4ICDwAwggIKAoICAQC2IpT8pEiv6EdrCvsnd=
uTy=0A=
P4o7ekosMSqMjbCpwzFrqHd2hCa2rIFCDQjrVVi7evi8ZX3yoG2LqEfpYnYeEe4IFNGyRBb06=
tD6=0A=
Hi9e28tzQa68ALBKK0CyrOE7S8ItneShm+waOh7wCLPQ5CQ1B5+ctMlSbdsHyo+1W/CD80/HL=
aXI=0A=
rcuVIKQxKFdYWuSNG5qrng0M8gozOSI5Cpcu81N3uURF/YTLNiCBWS2ab21ISGHKTN9T0a9Sv=
ESf=0A=
qy9rg3LvdYDaBjMbXcjaY8ZNzaxmMc3R3j6HEDbhuaR672BQssvKplbgN6+rNBM5Jeg5ZuSYe=
qoS=0A=
mJxZZoY+rfGwyj4GD3vwEUs3oERte8uojHH01bWRNszwFcYr3lEXsZdMUD2xlVl8BX0tIdUAv=
wFn=0A=
ol57plzy9yLxkA2T26pEUWbMfXYD62qoKjgZl3YNa4ph+bz27nb9cCvdKTz4Ch5bQhyLVi9VG=
xyh=0A=
LrXHFub4qjySjmm2AcG1hp2JDws4lFTo6tyePSW8Uybt1as5qsVATFSrsrTZ2fjXctscvG29Z=
V/v=0A=
iDUqZi/u9rNl8DONfJhBaUYPQxxp+pu10GFqzcpL2UyQRqsVWaFHVCkugyhfHMKiq3IXAAaOR=
eyL=0A=
4jM9f9oZRORicsPfIsbyVtTdX5Vy7W1f90gDW/3FKqD2cyOEEBsB5wIDAQABo0IwQDAOBgNVH=
Q8B=0A=
Af8EBAMCAQYwDwYDVR0TAQH/BAUwAwEB/zAdBgNVHQ4EFgQU43HgntinQtnbcZFrlJPrw6PRF=
KMw=0A=
DQYJKoZIhvcNAQELBQADggIBAEf63QqwEZE4rU1d9+UOl1QZgkiHVIyqZJnYWv6IAcVYpZmxI=
1Qj=0A=
t2odIFflAWJBF9MJ23XLblSQdf4an4EKwt3X9wnQW3IV5B4Jaj0z8yGa5hV+rVHVDRDtfULAj=
+7A=0A=
mgjVQdZcDiFpboBhDhXAuM/FSRJSzL46zNQuOAXeNf0fb7iAaJg9TaDKQGXSc3z1i9kKlT/YP=
yNt=0A=
GtEqJBnZhbMX73huqVjRI9PHE+1yJX9dsXNw0H8GlwmEKYBhHfpe/3OsoOOJuBxxFcbeMX8S3=
OFt=0A=
m6/n6J91eEyrRjuazr8FGF1NFTwWmhlQBJqymm9li1JfPFgEKCXAZmExfrngdbkaqIHWchezx=
QMx=0A=
NRF4eKLg6TCMf4DfWN88uieW4oA0beOY02QnrEh+KHdcxiVhJfiFDGX6xDIvpZgF5PgLZxYWx=
oK4=0A=
Mhn5+bl53B/N66+rDt0b20XkeucC4pVd/GnwU2lhlXV5C15V5jgclKlZM57IcXR5f1GJtshqu=
DDI=0A=
ajjDbp7hNxbqBWJMWxJH7ae0s1hWx0nzfxJoCTFx8G34Tkf71oXuxVhAGaQdp/lLQzfcaFpPz=
+vC=0A=
ZHTetBXZ9FRUGi8c15dxVJCO2SCdUyt/q4/i6jC8UDfv8Ue1fXwsBOxonbRJRBD0ckscZOf85=
muQ=0A=
3Wl9af0AVqW3rLatt8o+Ae+c=0A=
-----END CERTIFICATE-----=0A=
=0A=
Entrust Root Certification Authority - G2=0A=
=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=
=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=0A=
-----BEGIN CERTIFICATE-----=0A=
MIIEPjCCAyagAwIBAgIESlOMKDANBgkqhkiG9w0BAQsFADCBvjELMAkGA1UEBhMCVVMxFjAUB=
gNV=0A=
BAoTDUVudHJ1c3QsIEluYy4xKDAmBgNVBAsTH1NlZSB3d3cuZW50cnVzdC5uZXQvbGVnYWwtd=
GVy=0A=
bXMxOTA3BgNVBAsTMChjKSAyMDA5IEVudHJ1c3QsIEluYy4gLSBmb3IgYXV0aG9yaXplZCB1c=
2Ug=0A=
b25seTEyMDAGA1UEAxMpRW50cnVzdCBSb290IENlcnRpZmljYXRpb24gQXV0aG9yaXR5IC0gR=
zIw=0A=
HhcNMDkwNzA3MTcyNTU0WhcNMzAxMjA3MTc1NTU0WjCBvjELMAkGA1UEBhMCVVMxFjAUBgNVB=
AoT=0A=
DUVudHJ1c3QsIEluYy4xKDAmBgNVBAsTH1NlZSB3d3cuZW50cnVzdC5uZXQvbGVnYWwtdGVyb=
XMx=0A=
OTA3BgNVBAsTMChjKSAyMDA5IEVudHJ1c3QsIEluYy4gLSBmb3IgYXV0aG9yaXplZCB1c2Ugb=
25s=0A=
eTEyMDAGA1UEAxMpRW50cnVzdCBSb290IENlcnRpZmljYXRpb24gQXV0aG9yaXR5IC0gRzIwg=
gEi=0A=
MA0GCSqGSIb3DQEBAQUAA4IBDwAwggEKAoIBAQC6hLZy254Ma+KZ6TABp3bqMriVQRrJ2mFOW=
HLP=0A=
/vaCeb9zYQYKpSfYs1/TRU4cctZOMvJyig/3gxnQaoCAAEUesMfnmr8SVycco2gvCoe9amsOX=
mXz=0A=
HHfV1IWNcCG0szLni6LVhjkCsbjSR87kyUnEO6fe+1R9V77w6G7CebI6C1XiUJgWMhNcL3hWw=
cKU=0A=
s/Ja5CeanyTXxuzQmyWC48zCxEXFjJd6BmsqEZ+pCm5IO2/b1BEZQvePB7/1U1+cPvQXLOZpr=
E4y=0A=
TGJ36rfo5bs0vBmLrpxR57d+tVOxMyLlbc9wPBr64ptntoP0jaWvYkxN4FisZDQSA/i2jZRjJ=
KRx=0A=
AgMBAAGjQjBAMA4GA1UdDwEB/wQEAwIBBjAPBgNVHRMBAf8EBTADAQH/MB0GA1UdDgQWBBRqc=
iZ6=0A=
0B7vfec7aVHUbI2fkBJmqzANBgkqhkiG9w0BAQsFAAOCAQEAeZ8dlsa2eT8ijYfThwMEYGprm=
i5Z=0A=
iXMRrEPR9RP/jTkrwPK9T3CMqS/qF8QLVJ7UG5aYMzyorWKiAHarWWluBh1+xLlEjZivEtRh2=
woZ=0A=
Rkfz6/djwUAFQKXSt/S1mja/qYh2iARVBCuch38aNzx+LaUa2NSJXsq9rD1s2G2v1fN2D807i=
Dgi=0A=
nWyTmsQ9v4IbZT+mD12q/OWyFcq1rca8PdCE6OoGcrBNOTJ4vz4RnAuknZoh8/CbCzB428Hch=
0P+=0A=
vGOaysXCHMnHjf87ElgI5rY97HosTvuDls4MPGmHVHOkc8KT/1EQrBVUAdj8BbGJoX90g5pJ1=
9xO=0A=
e4pIb4tF9g=3D=3D=0A=
-----END CERTIFICATE-----=0A=
=0A=
Entrust Root Certification Authority - EC1=0A=
=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=
=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=0A=
-----BEGIN CERTIFICATE-----=0A=
MIIC+TCCAoCgAwIBAgINAKaLeSkAAAAAUNCR+TAKBggqhkjOPQQDAzCBvzELMAkGA1UEBhMCV=
VMx=0A=
FjAUBgNVBAoTDUVudHJ1c3QsIEluYy4xKDAmBgNVBAsTH1NlZSB3d3cuZW50cnVzdC5uZXQvb=
GVn=0A=
YWwtdGVybXMxOTA3BgNVBAsTMChjKSAyMDEyIEVudHJ1c3QsIEluYy4gLSBmb3IgYXV0aG9ya=
Xpl=0A=
ZCB1c2Ugb25seTEzMDEGA1UEAxMqRW50cnVzdCBSb290IENlcnRpZmljYXRpb24gQXV0aG9ya=
XR5=0A=
IC0gRUMxMB4XDTEyMTIxODE1MjUzNloXDTM3MTIxODE1NTUzNlowgb8xCzAJBgNVBAYTAlVTM=
RYw=0A=
FAYDVQQKEw1FbnRydXN0LCBJbmMuMSgwJgYDVQQLEx9TZWUgd3d3LmVudHJ1c3QubmV0L2xlZ=
2Fs=0A=
LXRlcm1zMTkwNwYDVQQLEzAoYykgMjAxMiBFbnRydXN0LCBJbmMuIC0gZm9yIGF1dGhvcml6Z=
WQg=0A=
dXNlIG9ubHkxMzAxBgNVBAMTKkVudHJ1c3QgUm9vdCBDZXJ0aWZpY2F0aW9uIEF1dGhvcml0e=
SAt=0A=
IEVDMTB2MBAGByqGSM49AgEGBSuBBAAiA2IABIQTydC6bUF74mzQ61VfZgIaJPRbiWlH47jCf=
fHy=0A=
AsWfoPZb1YsGGYZPUxBtByQnoaD41UcZYUx9ypMn6nQM72+WCf5j7HBdNq1nd67JnXxVRDqiY=
1Ef=0A=
9eNi1KlHBz7MIKNCMEAwDgYDVR0PAQH/BAQDAgEGMA8GA1UdEwEB/wQFMAMBAf8wHQYDVR0OB=
BYE=0A=
FLdj5xrdjekIplWDpOBqUEFlEUJJMAoGCCqGSM49BAMDA2cAMGQCMGF52OVCR98crlOZF7ZvH=
H3h=0A=
vxGU0QOIdeSNiaSKd0bebWHvAvX7td/M/k7//qnmpwIwW5nXhTcGtXsI/esni0qU+eH6p44mC=
Oh8=0A=
kmhtc9hvJqwhAriZtyZBWyVgrtBIGu4G=0A=
-----END CERTIFICATE-----=0A=
=0A=
CFCA EV ROOT=0A=
=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=0A=
-----BEGIN CERTIFICATE-----=0A=
MIIFjTCCA3WgAwIBAgIEGErM1jANBgkqhkiG9w0BAQsFADBWMQswCQYDVQQGEwJDTjEwMC4GA=
1UE=0A=
CgwnQ2hpbmEgRmluYW5jaWFsIENlcnRpZmljYXRpb24gQXV0aG9yaXR5MRUwEwYDVQQDDAxDR=
kNB=0A=
IEVWIFJPT1QwHhcNMTIwODA4MDMwNzAxWhcNMjkxMjMxMDMwNzAxWjBWMQswCQYDVQQGEwJDT=
jEw=0A=
MC4GA1UECgwnQ2hpbmEgRmluYW5jaWFsIENlcnRpZmljYXRpb24gQXV0aG9yaXR5MRUwEwYDV=
QQD=0A=
DAxDRkNBIEVWIFJPT1QwggIiMA0GCSqGSIb3DQEBAQUAA4ICDwAwggIKAoICAQDXXWvNED8fB=
VnV=0A=
BU03sQ7smCuOFR36k0sXgiFxEFLXUWRwFsJVaU2OFW2fvwwbwuCjZ9YMrM8irq93VCpLTIpTU=
nrD=0A=
7i7es3ElweldPe6hL6P3KjzJIx1qqx2hp/Hz7KDVRM8Vz3IvHWOX6Jn5/ZOkVIBMUtRSqy5J3=
5DN=0A=
uF++P96hyk0g1CXohClTt7GIH//62pCfCqktQT+x8Rgp7hZZLDRJGqgG16iI0gNyejLi6mhNb=
iyW=0A=
ZXvKWfry4t3uMCz7zEasxGPrb382KzRzEpR/38wmnvFyXVBlWY9ps4deMm/DGIq1lY+wejfeW=
kU7=0A=
xzbh72fROdOXW3NiGUgthxwG+3SYIElz8AXSG7Ggo7cbcNOIabla1jj0Ytwli3i/+Oh+uFzJl=
U9f=0A=
py25IGvPa931DfSCt/SyZi4QKPaXWnuWFo8BGS1sbn85WAZkgwGDg8NNkt0yxoekN+kWzqota=
K8K=0A=
gWU6cMGbrU1tVMoqLUuFG7OA5nBFDWteNfB/O7ic5ARwiRIlk9oKmSJgamNgTnYGmE69g60dW=
Iol=0A=
hdLHZR4tjsbftsbhf4oEIRUpdPA+nJCdDC7xij5aqgwJHsfVPKPtl8MeNPo4+QgO48BdK4PRV=
mrJ=0A=
tqhUUy54Mmc9gn900PvhtgVguXDbjgv5E1hvcWAQUhC5wUEJ73IfZzF4/5YFjQIDAQABo2MwY=
TAf=0A=
BgNVHSMEGDAWgBTj/i39KNALtbq2osS/BqoFjJP7LzAPBgNVHRMBAf8EBTADAQH/MA4GA1UdD=
wEB=0A=
/wQEAwIBBjAdBgNVHQ4EFgQU4/4t/SjQC7W6tqLEvwaqBYyT+y8wDQYJKoZIhvcNAQELBQADg=
gIB=0A=
ACXGumvrh8vegjmWPfBEp2uEcwPenStPuiB/vHiyz5ewG5zz13ku9Ui20vsXiObTej/tUxPQ4=
i9q=0A=
ecsAIyjmHjdXNYmEwnZPNDatZ8POQQaIxffu2Bq41gt/UP+TqhdLjOztUmCypAbqTuv0axn96=
/Ua=0A=
4CUqmtzHQTb3yHQFhDmVOdYLO6Qn+gjYXB74BGBSESgoA//vU2YApUo0FmZ8/Qmkrp5nGm9BC=
2sG=0A=
E5uPhnEFtC+NiWYzKXZUmhH4J/qyP5Hgzg0b8zAarb8iXRvTvyUFTeGSGn+ZnzxEk8rUQElsg=
IfX=0A=
BDrDMlI1Dlb4pd19xIsNER9Tyx6yF7Zod1rg1MvIB671Oi6ON7fQAUtDKXeMOZePglr4UeWJo=
Bjn=0A=
aH9dCi77o0cOPaYjesYBx4/IXr9tgFa+iiS6M+qf4TIRnvHST4D2G0CvOJ4RUHlzEhLN5mydL=
Ihy=0A=
PDCBBpEi6lmt2hkuIsKNuYyH4Ga8cyNfIWRjgEj1oDwYPZTISEEdQLpe/v5WOaHIz16eGWRGE=
NoX=0A=
kbcFgKyLmZJ956LYBws2J+dIeWCKw9cTXPhyQN9Ky8+ZAAoACxGV2lZFA4gKn2fQ1XmxqI1Ab=
Q3C=0A=
ekD6819kR5LLU7m7Wc5P/dAVUwHY3+vZ5nbv0CO7O6l5s9UCKc2Jo5YPSjXnTkLAdc0Hz+Ys6=
3su=0A=
-----END CERTIFICATE-----=0A=
=0A=
T=C3=9CRKTRUST Elektronik Sertifika Hizmet =
Sa=C4=9Flay=C4=B1c=C4=B1s=C4=B1 H5=0A=
=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=
=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=
=3D=3D=3D=3D=3D=3D=3D=0A=
-----BEGIN CERTIFICATE-----=0A=
MIIEJzCCAw+gAwIBAgIHAI4X/iQggTANBgkqhkiG9w0BAQsFADCBsTELMAkGA1UEBhMCVFIxD=
zAN=0A=
BgNVBAcMBkFua2FyYTFNMEsGA1UECgxEVMOcUktUUlVTVCBCaWxnaSDEsGxldGnFn2ltIHZlI=
EJp=0A=
bGnFn2ltIEfDvHZlbmxpxJ9pIEhpem1ldGxlcmkgQS7Fni4xQjBABgNVBAMMOVTDnFJLVFJVU=
1Qg=0A=
RWxla3Ryb25payBTZXJ0aWZpa2EgSGl6bWV0IFNhxJ9sYXnEsWPEsXPEsSBINTAeFw0xMzA0M=
zAw=0A=
ODA3MDFaFw0yMzA0MjgwODA3MDFaMIGxMQswCQYDVQQGEwJUUjEPMA0GA1UEBwwGQW5rYXJhM=
U0w=0A=
SwYDVQQKDERUw5xSS1RSVVNUIEJpbGdpIMSwbGV0acWfaW0gdmUgQmlsacWfaW0gR8O8dmVub=
GnE=0A=
n2kgSGl6bWV0bGVyaSBBLsWeLjFCMEAGA1UEAww5VMOcUktUUlVTVCBFbGVrdHJvbmlrIFNlc=
nRp=0A=
ZmlrYSBIaXptZXQgU2HEn2xhecSxY8Sxc8SxIEg1MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AM=
IIB=0A=
CgKCAQEApCUZ4WWe60ghUEoI5RHwWrom/4NZzkQqL/7hzmAD/I0Dpe3/a6i6zDQGn1k19uwsu=
537=0A=
jVJp45wnEFPzpALFp/kRGml1bsMdi9GYjZOHp3GXDSHHmflS0yxjXVW86B8BSLlg/kJK9siAr=
s1m=0A=
ep5Fimh34khon6La8eHBEJ/rPCmBp+EyCNSgBbGM+42WAA4+Jd9ThiI7/PS98wl+d+yG6w8z5=
UNP=0A=
9FR1bSmZLmZaQ9/LXMrI5Tjxfjs1nQ/0xVqhzPMggCTTV+wVunUlm+hkS7M0hO8EuPbJbKoCP=
rZV=0A=
4jI3X/xml1/N1p7HIL9Nxqw/dV8c7TKcfGkAaZHjIxhT6QIDAQABo0IwQDAdBgNVHQ4EFgQUV=
pkH=0A=
HtOsDGlktAxQR95DLL4gwPswDgYDVR0PAQH/BAQDAgEGMA8GA1UdEwEB/wQFMAMBAf8wDQYJK=
oZI=0A=
hvcNAQELBQADggEBAJ5FdnsXSDLyOIspve6WSk6BGLFRRyDN0GSxDsnZAdkJzsiZ3GglE9Rc8=
qPo=0A=
BP5yCccLqh0lVX6Wmle3usURehnmp349hQ71+S4pL+f5bFgWV1Al9j4uPqrtd3GqqpmWRgquj=
uwq=0A=
URawXs3qZwQcWDD1YIq9pr1N5Za0/EKJAWv2cMhQOQwt1WbZyNKzMrcbGW3LM/nfpeYVhDfww=
vJl=0A=
lpKQd/Ct9JDpEXjXk4nAPQu6KfTomZ1yju2dL+6SfaHx/126M2CFYv4HAqGEVka+lgqaE9chT=
Ld8=0A=
B59OTj+RdPsnnRHM3eaxynFNExc5JsUpISuTKWqW+qtB4Uu2NQvAmxU=3D=0A=
-----END CERTIFICATE-----=0A=
=0A=
T=C3=9CRKTRUST Elektronik Sertifika Hizmet =
Sa=C4=9Flay=C4=B1c=C4=B1s=C4=B1 H6=0A=
=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=
=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=
=3D=3D=3D=3D=3D=3D=3D=0A=
-----BEGIN CERTIFICATE-----=0A=
MIIEJjCCAw6gAwIBAgIGfaHyZeyKMA0GCSqGSIb3DQEBCwUAMIGxMQswCQYDVQQGEwJUUjEPM=
A0G=0A=
A1UEBwwGQW5rYXJhMU0wSwYDVQQKDERUw5xSS1RSVVNUIEJpbGdpIMSwbGV0acWfaW0gdmUgQ=
mls=0A=
acWfaW0gR8O8dmVubGnEn2kgSGl6bWV0bGVyaSBBLsWeLjFCMEAGA1UEAww5VMOcUktUUlVTV=
CBF=0A=
bGVrdHJvbmlrIFNlcnRpZmlrYSBIaXptZXQgU2HEn2xhecSxY8Sxc8SxIEg2MB4XDTEzMTIxO=
DA5=0A=
MDQxMFoXDTIzMTIxNjA5MDQxMFowgbExCzAJBgNVBAYTAlRSMQ8wDQYDVQQHDAZBbmthcmExT=
TBL=0A=
BgNVBAoMRFTDnFJLVFJVU1QgQmlsZ2kgxLBsZXRpxZ9pbSB2ZSBCaWxpxZ9pbSBHw7x2ZW5sa=
cSf=0A=
aSBIaXptZXRsZXJpIEEuxZ4uMUIwQAYDVQQDDDlUw5xSS1RSVVNUIEVsZWt0cm9uaWsgU2Vyd=
Glm=0A=
aWthIEhpem1ldCBTYcSfbGF5xLFjxLFzxLEgSDYwggEiMA0GCSqGSIb3DQEBAQUAA4IBDwAwg=
gEK=0A=
AoIBAQCdsGjW6L0UlqMACprx9MfMkU1xeHe59yEmFXNRFpQJRwXiM/VomjX/3EsvMsew7eKC5=
W/a=0A=
2uqsxgbPJQ1BgfbBOCK9+bGlprMBvD9QFyv26WZV1DOzXPhDIHiTVRZwGTLmiddk671IUP320=
EED=0A=
wnS3/faAz1vFq6TWlRKb55cTMgPp1KtDWxbtMyJkKbbSk60vbNg9tvYdDjTu0n2pVQ8g9P0pu=
5Fb=0A=
HH3GQjhtQiht1AH7zYiXSX6484P4tZgvsycLSF5W506jM7NE1qXyGJTtHB6plVxiSvgNZ1Gpr=
yHV=0A=
+DKdeboaX+UEVU0TRv/yz3THGmNtwx8XEsMeED5gCLMxAgMBAAGjQjBAMB0GA1UdDgQWBBTdV=
RcT=0A=
9qzoSCHK77Wv0QAy7Z6MtTAOBgNVHQ8BAf8EBAMCAQYwDwYDVR0TAQH/BAUwAwEB/zANBgkqh=
kiG=0A=
9w0BAQsFAAOCAQEAb1gNl0OqFlQ+v6nfkkU/hQu7VtMMUszIv3ZnXuaqs6fvuay0EBQNdH49b=
a3R=0A=
fdCaqaXKGDsCQC4qnFAUi/5XfldcEQlLNkVS9z2sFP1E34uXI9TDwe7UU5X+LEr+DXCqu4svL=
csy=0A=
o4LyVN/Y8t3XSHLuSqMplsNEzm61kod2pLv0kmzOLBQJZo6NrRa1xxsJYTvjIKIDgI6tflEAT=
seW=0A=
hvtDmHd9KMeP2Cpu54Rvl0EpABZeTeIT6lnAY2c6RPuY/ATTMHKm9ocJV612ph1jmv3XZch4g=
yt1=0A=
O6VbuA1df74jrlZVlFjvH4GMKrLN5ptjnhi85WsGtAuYSyher4hYyw=3D=3D=0A=
-----END CERTIFICATE-----=0A=
=0A=
Certinomis - Root CA=0A=
=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=3D=0A=
-----BEGIN CERTIFICATE-----=0A=
MIIFkjCCA3qgAwIBAgIBATANBgkqhkiG9w0BAQsFADBaMQswCQYDVQQGEwJGUjETMBEGA1UEC=
hMK=0A=
Q2VydGlub21pczEXMBUGA1UECxMOMDAwMiA0MzM5OTg5MDMxHTAbBgNVBAMTFENlcnRpbm9ta=
XMg=0A=
LSBSb290IENBMB4XDTEzMTAyMTA5MTcxOFoXDTMzMTAyMTA5MTcxOFowWjELMAkGA1UEBhMCR=
lIx=0A=
EzARBgNVBAoTCkNlcnRpbm9taXMxFzAVBgNVBAsTDjAwMDIgNDMzOTk4OTAzMR0wGwYDVQQDE=
xRD=0A=
ZXJ0aW5vbWlzIC0gUm9vdCBDQTCCAiIwDQYJKoZIhvcNAQEBBQADggIPADCCAgoCggIBANTMC=
Qos=0A=
P5L2fxSeC5yaah1AMGT9qt8OHgZbn1CF6s2Nq0Nn3rD6foCWnoR4kkjW4znuzuRZWJflLieY6=
pOo=0A=
d5tK8O90gC3rMB+12ceAnGInkYjwSond3IjmFPnVAy//ldu9n+ws+hQVWZUKxkd8aRi5pwP5y=
nap=0A=
z8dvtF4F/u7BUrJ1Mofs7SlmO/NKFoL21prbcpjp3vDFTKWrteoB4owuZH9kb/2jJZOLyKIOS=
Y00=0A=
8B/sWEUuNKqEUL3nskoTuLAPrjhdsKkb5nPJWqHZZkCqqU2mNAKthH6yI8H7KsZn9DS2sJVqM=
09x=0A=
RLWtwHkziOC/7aOgFLScCbAK42C++PhmiM1b8XcF4LVzbsF9Ri6OSyemzTUK/eVNfaoqoynHW=
mgE=0A=
6OXWk6RiwsXm9E/G+Z8ajYJJGYrKWUM66A0ywfRMEwNvbqY/kXPLynNvEiCL7sCCeN5LLsJJw=
x3t=0A=
FvYk9CcbXFcx3FXuqB5vbKziRcxXV4p1VxngtViZSTYxPDMBbRZKzbgqg4SGm/lg0h9tkQPTY=
KbV=0A=
PZrdd5A9NaSfD171UkRpucC63M9933zZxKyGIjK8e2uR73r4F2iw4lNVYC2vPsKD2NkJK/DAZ=
NuH=0A=
i5HMkesE/Xa0lZrmFAYb1TQdvtj/dBxThZngWVJKYe2InmtJiUZ+IFrZ50rlau7SZRFDAgMBA=
AGj=0A=
YzBhMA4GA1UdDwEB/wQEAwIBBjAPBgNVHRMBAf8EBTADAQH/MB0GA1UdDgQWBBTvkUz1pcMw6=
C8I=0A=
6tNxIqSSaHh02TAfBgNVHSMEGDAWgBTvkUz1pcMw6C8I6tNxIqSSaHh02TANBgkqhkiG9w0BA=
QsF=0A=
AAOCAgEAfj1U2iJdGlg+O1QnurrMyOMaauo++RLrVl89UM7g6kgmJs95Vn6RHJk/0KGRHCwPT=
5iV=0A=
WVO90CLYiF2cN/z7ZMF4jIuaYAnq1fohX9B0ZedQxb8uuQsLrbWwF6YSjNRieOpWauwK0kDDP=
AUw=0A=
Pk2Ut59KA9N9J0u2/kTO+hkzGm2kQtHdzMjI1xZSg081lLMSVX3l4kLr5JyTCcBMWwerx20Ro=
FAX=0A=
lCOotQqSD7J6wWAsOMwaplv/8gzjqh8c3LigkyfeY+N/IZ865Z764BNqdeuWXGKRlI5nU7aJ+=
BIJ=0A=
y29SWwNyhlCVCNSNh4YVH5Uk2KRvms6knZtt0rJ2BobGVgjF6wnaNsIbW0G+YSrjcOa4pvi2W=
sS9=0A=
Iff/ql+hbHY5ZtbqTFXhADObE5hjyW/QASAJN1LnDE8+zbz1X5YnpyACleAu6AdBBR8Vbtaw5=
Bng=0A=
DwKTACdyxYvRVB9dSsNAl35VpnzBMwQUAR1JIGkLGZOdblgi90AMRgwjY/M50n92Uaf0yKHxD=
HYi=0A=
I0ZSKS3io0EHVmmY0gUJvGnHWmHNj4FgFU2A3ZDifcRQ8ow7bkrHxuaAKzyBvBGAFhAn1/DNP=
3nM=0A=
cyrDflOR1m749fPH0FFNjkulW+YZFzvWgQncItzujrnEj1PhZ7szuIgVRs/taTX/dQ1G885x4=
cVr=0A=
hkIGuUE=3D=0A=
-----END CERTIFICATE-----=0A=
CACERT;=0A=
    }=0A=
=0A=
}=0A=
</PRE></BODY></HTML>
