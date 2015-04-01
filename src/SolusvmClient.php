<?php namespace kofj\SolusVM;

class SolusvmClient
{

    // The key value of SolusVM API.
    private $key;
    // The hash value of SolusVM API.
    private $hash;
    // The host addres of SolusVM API.
    private $host;
    // Timeout of send query to the master.
    private $timeout = 20;

    /**
     * Init the class.
     *
     * @access private
     * @param string
     * @param string
     * @param string
     */
    public function __construct($key, $hash, $host)
    {
        $this->key = $key;
        $this->hash = $hash;
        $this->host = $host;
    }

    /**
     * Execute the action.
     *
     * @access private
     * @param string
     * @return string
     */
    private function execute($action)
    {
        // Check action string.
        if (!in_array($action, array('reboot', 'shutdown', 'boot', 'status'))) {
            throw new Exception("Error action for API.Only thus action allowed: reboot, shutdown, boot, status.", 1);
        }

        // Setting post fileds.
        $postfields["key"] = $this->key;
        $postfields["hash"] = $this->hash;
        $postfields["action"] = $action;

        // Send the query to the SolusVM master.
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->host . '/command.php');
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT, $this->timeout);
        curl_setopt($ch, CURLOPT_FRESH_CONNECT, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postfields);
        $data = curl_exec($ch);

        // error handling
        if ($data === false) {
            throw new Exception("API Query error: " . curl_error($ch));
        }

        // cleanup
        curl_close($ch);
        return $data;
    }

    /**
     * Reboot
     *
     * @access public
     */
    public function reboot()
    {
        $this->execute('reboot');
    }

    /**
     * Boot
     *
     * @access public
     */
    public function boot()
    {
        $this->execute('boot');
    }

    /**
     * shutdown
     *
     * @access public
     */
    public function shutdown()
    {
        $this->execute('shutdown');
    }

    /**
     * status
     *
     * @access public
     */
    public function status()
    {
        return $this->execute('status');
    }
}
