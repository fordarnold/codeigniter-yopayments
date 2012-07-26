<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
	/**
    * Name:  Yo Payments
    *
    * Author: Arnold Mwumva Ford
    *         ford@meridiansoftech.net / fordarnold@gmail.com
    *         @fordarnold
    *
    * Location: Kampala, Uganda
    *
    * Created:  24/07/2012
    *
    * Description:  Library to work with Yo! Payments API.
    *
    *
    */


	class Yo
	{
		protected $_ci;
		protected $mode;
		protected $api_username;
		protected $api_password;
        protected $sandbox_url;

		function __construct()
		{
			// initialize the CI super-object
			$this->_ci =& get_instance();

			// load config
			$this->_ci->load->config('yo', TRUE);

			// get settings from config
			$this->mode        = $this->_ci->config->item('mode', 'yo');
			$this->api_username = $this->_ci->config->item('api_username', 'yo');
			$this->api_password  = $this->_ci->config->item('api_password', 'yo');

            // Get appropriate API endpoint
            if ($this->mode == 'sandbox')
                $this->api_endpoint = "https://41.220.12.206/services/yopaymentsdev/task.php";
            else
                $this->api_endpoint = "https://paymentsapi1.yo.co.ug/ybs/task.php";
		}

        /**
         * Deposit funds into Yo! Payments account from a phone's Mobile Money account
         * 
         * 
         * @param float $amount The amount of money to deposit
         * @param string $phone Phone number to pull Mobile Money from <br> [Format]: 256772123456
         * @param string $narrative A description of the transaction 
         * @param string $ref_text The text to be returned to the user's phone after the transaction is complete
         * 
         * @return xml The XML Request String to be sent to the Yo! Payments Server
         *
         * ------------------------------------------------------------------------------------------------------------------
         *
         */
        public function deposit($amount, $phone, $narrative, $ref_text="")
        {
            $xml_request='<?xml version="1.0" encoding="UTF-8"?>';
            $xml_request=$xml_request.'<AutoCreate>';
            $xml_request=$xml_request.'<Request>';
            $xml_request=$xml_request.'<APIUsername>'.$this->api_username.'</APIUsername>';
            $xml_request=$xml_request.'<APIPassword>'.$this->api_password.'</APIPassword>';
            $xml_request=$xml_request.'<Method>acdepositfunds</Method>';
            $xml_request=$xml_request.'<NonBlocking>FALSE</NonBlocking>';
            $xml_request=$xml_request.'<Amount>'.$amount.'</Amount>';
            $xml_request=$xml_request.'<Account>'.$phone.'</Account>';
            $xml_request=$xml_request.'<Narrative>'.$narrative.'</Narrative>';
            $xml_request=$xml_request.'<ProviderReferenceText>'.$ref_text.'</ProviderReferenceText>';
            $xml_request=$xml_request.'</Request>';
            $xml_request=$xml_request.'</AutoCreate>';
            
            return $this->send_xml_request($xml_request);
        }

        /**
         * Withdraw funds from Yo! Payments account and add to a phone's Mobile Money account
         * 
         * 
         * @param float $amount The amount of money to withdraw
         * @param string $phone Phone number to add Mobile Money to <br> [Format]: 256772123456
         * @param string $narrative A description of the transaction 
         * @param string $ref_text The text to be returned to the user's phone after the transaction is complete
         * 
         * @return xml The XML Request String to be sent to the Yo! Payments Server
         *
         * ------------------------------------------------------------------------------------------------------------------
         *
         */
        public function withdraw($amount, $phone, $narrative="", $ref_text="")
        {
            $xml_request='<?xml version="1.0" encoding="UTF-8"?>';
            $xml_request=$xml_request.'<AutoCreate>';
            $xml_request=$xml_request.'<Request>';
            $xml_request=$xml_request.'<APIUsername>'.$this->api_username.'</APIUsername>';
            $xml_request=$xml_request.'<APIPassword>'.$this->api_password.'</APIPassword>';
            $xml_request=$xml_request.'<Method>acdepositfunds</Method>';
            $xml_request=$xml_request.'<NonBlocking>FALSE</NonBlocking>';
            $xml_request=$xml_request.'<Amount>'.$amount.'</Amount>';
            $xml_request=$xml_request.'<Account>'.$phone.'</Account>';
            $xml_request=$xml_request.'<Narrative>'.$narrative.'</Narrative>';
            $xml_request=$xml_request.'<ProviderReferenceText>'.$ref_text.'</ProviderReferenceText>';
            $xml_request=$xml_request.'</Request>';
            $xml_request=$xml_request.'</AutoCreate>';
            
            return $this->send_xml_request($xml_request);

        }

        /**
         * Implement cURL request to Yo! Servers
         *
         * ------------------------------------------------------------------------------------------------------------------
         */

        public function send_xml_request($xml_request)
        {
            $ch = curl_init(); //initiate the curl session 

            curl_setopt($ch, CURLOPT_URL, $this->api_endpoint); //set to API endpoint
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
            //curl_setopt($ch, CURLOPT_FOLLOWLOCATION,1);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); // tell curl to return data in a variable 
            curl_setopt($ch, CURLOPT_HEADER, false); 
            curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: text/xml", "Content-length: ".strlen($xml_request))); 
            curl_setopt($ch, CURLOPT_POST, true); 
            curl_setopt($ch, CURLOPT_POSTFIELDS, $xml_request); // post the xml 
            curl_setopt($ch, CURLOPT_TIMEOUT, (int)30); // set timeout in seconds 

            $xml_response = curl_exec($ch);

            if($xml_response === FALSE)
                show_error('There was an error connecting to Yo! Payments. Please try again.');

            curl_close ($ch); 
            
            // Get Status
            $status_code = $this->get_status($xml_response);
            return $status_code;
        }

        /**
         * Get the Status Message for the Request
         *
         * ------------------------------------------------------------------------------------------------------------------
         */

        public function get_status($xml_response)
        {
            $xml = simplexml_load_string($xml_response);
            if(!$xml)
            {
                return 'false';
            }
            else
            {
                return $xml->Response->StatusCode;
            }
        }

        /**
         * Log any Errors encountered
         *
         * ------------------------------------------------------------------------------------------------------------------
         */

        public function log_errors($xml_response)
        {
            // TODO: log errors to file / database
        }

	}

/* End of file Yo.php */