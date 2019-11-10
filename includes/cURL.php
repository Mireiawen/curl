<?php
declare(strict_types = 1);

namespace Mireiawen\cURL;

/**
 * Simple cURL wrapper class for easier cURL use in object oriented way
 *
 * @package Mireiawen\cURL
 */
class cURL
{
	/**
	 * The cURL handle
	 *
	 * @var resource|null
	 */
	protected $handle = NULL;
	
	/**
	 * Constructs the class and initializes cURL
	 *
	 * @param string|null $url
	 *    The URL to initialize
	 *
	 * @throws \Exception
	 *    If cURL extension is missing
	 */
	public function __construct(?string $url = NULL)
	{
		// Check for extension
		if (!\extension_loaded('curl'))
		{
			throw new \Exception(\_('cURL extension is required!'));
		}
		
		// Initialize the handle
		$this->Init($url);
	}
	
	/**
	 * Close connection and destroy the class
	 */
	public function __destruct()
	{
		$this->Close();
	}
	
	/**
	 * Returns the current cURL handle
	 *
	 * @return resource
	 *    The cURL handle
	 *
	 * @throws \Exception
	 *    If handle is not initialized
	 */
	public function GetHandle()
	{
		if ($this->handle === NULL)
		{
			$this->ThrowError();
		}
		
		return $this->handle;
	}
	
	/**
	 * Close the current handle if it is open
	 */
	public function Close() : void
	{
		if ($this->handle !== NULL)
		{
			\curl_close($this->handle);
		}
		
		$this->handle = NULL;
	}
	
	/**
	 * URL encodes the given string
	 *
	 * @param string $string
	 *    String to encode
	 *
	 * @return  string
	 *    The encoded string
	 *
	 * @throws \Exception
	 *    If unable to escape the string
	 */
	public function Escape(string $string) : string
	{
		if ($this->handle === NULL)
		{
			$this->ThrowError();
		}
		
		$result = \curl_escape($this->handle, $string);
		if ($result === FALSE)
		{
			$this->ThrowError();
		}
		return $result;
	}
	
	/**
	 * Execute the cURL session
	 *
	 * @return string
	 *    Returns empty string on success, unless the CURLOPT_RETURNTRANSFER
	 *    option is set, which will cause the method to return the result instead.
	 *
	 * @throws \Exception
	 *    If the execution fails
	 */
	public function Execute() : string
	{
		if ($this->handle === NULL)
		{
			$this->ThrowError();
		}
		
		$result = \curl_exec($this->handle);
		if ($result === FALSE)
		{
			$this->ThrowError();
		}
		
		if (\is_string($result))
		{
			return $result;
		}
		
		return '';
	}
	
	/**
	 * Get the specific information regarding the transfer
	 *
	 * @param int $opt
	 *    cURL constant defining which information to get
	 *
	 * @return mixed
	 *    The return value
	 *
	 * @throws \Exception
	 *    On failure
	 */
	public function GetInformation(int $opt)
	{
		if ($this->handle === NULL)
		{
			$this->ThrowError();
		}
		
		$result = \curl_getinfo($this->handle, $opt);
		if ($result === FALSE)
		{
			$this->ThrowError();
		}
		
		return $result;
	}
	
	/**
	 * Get the information regarding the transfer
	 *
	 * @return array
	 *    An associative array of the transfer information
	 *
	 * @throws \Exception
	 *    On failure
	 */
	public function GetInformationArray() : array
	{
		if ($this->handle === NULL)
		{
			$this->ThrowError();
		}
		
		$result = \curl_getinfo($this->handle);
		if ($result === FALSE)
		{
			$this->ThrowError();
		}
		
		return $result;
	}
	
	/**
	 * Initialize the cURL session
	 *
	 * @param string $url
	 *    If provided, the CURLOPT_URL option will be set to this value
	 *
	 * @throws \Exception
	 *    On failure
	 */
	public function Init(?string $url = NULL) : void
	{
		if ($this->handle !== NULL)
		{
			throw new \Exception(\_('cURL error: already initialized'));
		}
		
		$this->handle = \curl_init($url);
		if ($this->handle === FALSE)
		{
			$this->handle = NULL;
			throw new \Exception(\_('cURL error: Initialization failed'));
		}
	}
	
	/**
	 * This re-initializes all options set to the default values
	 *
	 * @throws \Exception
	 *    If the handle is not initialized
	 */
	public function Reset() : void
	{
		if ($this->handle === NULL)
		{
			$this->ThrowError();
		}
		
		\curl_reset($this->handle);
	}
	
	/**
	 * Set multiple options for a cURL transfer
	 *
	 * @param array $options
	 *    An array specifying which options to set and their values
	 *
	 * @throws \Exception
	 *    On failure
	 */
	public function SetOptions(array $options) : void
	{
		if ($this->handle === NULL)
		{
			$this->ThrowError();
		}
		
		$result = \curl_setopt_array($this->handle, $options);
		if ($result === FALSE)
		{
			$this->ThrowError();
		}
	}
	
	/**
	 * Set an option for a cURL transfer
	 *
	 * @param int $option
	 *    The cURL option to set
	 *
	 * @param mixed $value
	 *    The value to be set on option
	 *
	 * @throws \Exception
	 *    On failure
	 */
	public function SetOption(int $option, $value) : void
	{
		if ($this->handle === NULL)
		{
			$this->ThrowError();
		}
		
		$result = \curl_setopt($this->handle, $option, $value);
		if ($result === FALSE)
		{
			$this->ThrowError();
		}
	}
	
	/**
	 * Return string describing the given error code
	 *
	 * @param int $code
	 *    The cURL error code
	 *
	 * @return string
	 *    Error description or NULL for invalid error code
	 */
	public static function ErrorToString(int $code) : string
	{
		return \curl_strerror($code);
	}
	
	/**
	 * Decodes the given URL encoded string
	 *
	 * @param string $string
	 *    The URL encoded string to be decoded
	 *
	 * @return  string
	 *    Decoded string
	 * @throws \Exception
	 *    If unable to decode the string
	 */
	public function Unescape($string) : string
	{
		if ($this->handle === NULL)
		{
			$this->ThrowError();
		}
		
		$result = \curl_unescape($this->handle, $string);
		if ($result === FALSE)
		{
			$this->ThrowError();
		}
		
		return $result;
	}
	
	/**
	 * Check the cURL handle, and throw the error message
	 * depending on handle availability
	 *
	 * @throws \Exception always
	 */
	protected function ThrowError() : void
	{
		if ($this->handle === NULL)
		{
			throw new \Exception(_('cURL error: Not initialized'));
		}
		
		throw new \Exception(\sprintf(\_('cURL error %s: %s'),
				\curl_errno($this->handle),
				\curl_error($this->handle)
			)
		);
	}
}
