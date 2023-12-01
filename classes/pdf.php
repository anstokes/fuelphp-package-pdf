<?php

/**
 * PDF Package
 *
 * This PDF Package for FuelPHP takes any PHP5 PDF generating class as a driver
 * and acts as a wrapper for that class, thus integrating PDF generation into
 * FuelPHP without reinventing the wheel.
 */

namespace Pdf;

use Fuel\Core\Arr;
use Fuel\Core\Config;
use Fuel\Core\Inflector;

class Pdf
{
    // Library path
    protected $libraryPath = '';

    // Driver Class
    protected $driverClass = '';

    // Driver Instance
    protected $driverInstance = '';

    // Silently fail, if method not found
    protected $silentFail = false;

    /**
     * Construct
     *
     * Called when the class is initialised
     *
     * @access  protected
     * @return  Pdf
     */
    protected function __construct($driver = null, $options = [])
    {
        // Load Config
        Config::load('pdf', true);

        // Default Driver
        if ($driver == null) {
            $driver = Config::get('pdf.default_driver');
        }

        // Set the library path
        $this->libraryPath = PKGPATH . 'pdf' . DS . 'lib' . DS;

        // Get driver configuration
        $drivers = Config::get('pdf.drivers');
        $driverConfiguration = Arr::get($drivers, $driver);
        if (! is_array($driverConfiguration)) {
            throw new \Exception(sprintf('Driver \'%s\' doesn\'t exist.', $driver));
        }

        // Include files
        if ($includes = Arr::get($driverConfiguration, 'includes')) {
            foreach ($includes as $include) {
                include_once($this->includeFile($include));
            }
        }

        // Set the driver class
        $this->driverClass = Arr::get($driverConfiguration, 'class');

        // Create new reflection of driver class
        $reflect = new \ReflectionClass($this->driverClass);

        // Load custom TCPDF fonts
        if (is_subclass_of($this->driverClass, 'TCPDF')) {
            // TCPDF options arguments
            $instance = $reflect->newInstanceArgs($options);

            // Load fonts
            $this->driverInstance = $instance;
            $this->loadFonts();    
        } else if (is_subclass_of($this->driverClass, 'fpdf')) {
            // Parameters
            $parameters = ['orientiation', 'unit', 'size'];
            $arguments = [];
            foreach($parameters as $parameter) {
                $arguments = isset($options[$parameter]) ? $options[$parameter] : null;
            }
            $instance = $reflect->newInstanceArgs($options);
        } else {
            $instance = $reflect->newInstance($options);
        }

        $this->driverInstance = $instance;
    }

    public function class()
    {
        return get_class($this->driverInstance);
    }

    /**
     * Create new instance of class
     *
     * @access  public
     * @param   string $driver
     * @return  Pdf
     */
    public static function forge($driver = null, $options = [])
    {
        // If drivers require default options to avoid errors, set them here
        switch ($driver) {
            default:
                break;
        }

        return new Pdf($driver, $options);
    }

    /**
     * Get Include File
     *
     * Gets the path of the include file and
     * makes it safe for Windows users.
     *
     * @access  protected
     * @param   string  file location (relative to lib path)
     * @return  string  real file location
     */
    protected function includeFile($file)
    {
        // $file = sprintf('%s%s', $this->libraryPath, str_replace('/', DS, $file));
        $file = sprintf('%s', str_replace('/', DS, $file));

        if (! file_exists($file)) {
            throw new \Exception(sprintf('File \'%s\' doesn\'t exist.', $file));
        }

        return $file;
    }

    protected function loadFonts()
    {
        // Define font directory (no '..')
        $directories = explode(DS, __DIR__);
        array_pop($directories);
        $fontDirectory = implode(DS, $directories) . DS . 'fonts' . DS;

        // Read font files from directory
        $loadedFonts = [];
        if ($fonts = glob($fontDirectory . '*.php')) {
            foreach ($fonts as $fontPath) {
                $fontName = substr(basename($fontPath), 0, -4);
                // Check if subset of existing font family
                if (preg_match('/(.*)([bi])$/', $fontName, $matches) && Arr::get($loadedFonts, $matches[1])) {
                    // Bold / Italic version of existing font
                    $this->driverInstance->AddFont($matches[1], strtoupper($matches[2]), $fontPath);
                } else {
                    // Base font
                    $this->driverInstance->AddFont($fontName, '', $fontPath);
                    $loadedFonts[$fontName] = true;
                }
            }
        }
    }

    /**
     * Call
     *
     * Magic method to catch all calls
     *
     * @access  public
     * @param   string  method
     * @param   array   arguments
     * @return  mixed
     */
    public function __call($method, $arguments)
    {
        // Get cameled method
        $camelizedMethod = Inflector::camelize($method);

        // Check the method exists
        if (method_exists($this->driverInstance, $method)) {
            // Provided method name exists
            $return = call_user_func_array([$this->driverInstance, $method], $arguments);
            return ($return !== false) ? $return : $this;
        } else if (method_exists($this->driverInstance, $camelizedMethod)) {
            // Camelized method name exists
            $return = call_user_func_array([$this->driverInstance, $camelizedMethod], $arguments);
            return ($return !== false) ? $return : $this;
        }

        if (!$this->silentFail) {
            throw new \Exception(sprintf('Call to undefined method %s::%s()', get_called_class(), $method));
        }
    }
}
