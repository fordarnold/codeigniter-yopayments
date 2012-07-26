# Yo-Payments CodeIgniter Library

#### by [Arnold Mwumva Ford](https://twitter.com/fordarnold)

### What is Yo! Payments ?
[Yo! Payments](http://payments.yo.co.ug/) is a revolutionary mobile payments gateway service. Yo! Payments enables businesses to receive payments from their customers via mobile money, as well as make mobile money payments to any mobile money account holder. Yo! Payments offers a rich API which enables seamless integration with websites, IVR services, SMS services and any other medium through which businesses interact with their customers. 

Yo! Payments also offers an “internal transfer” service which enables account holders to 
cheaply transfer funds amongst each other. 
Yo! Payments essentially opens the door for all types of businesses to benefit from the 
highly successful mobile money transfer phenomenon.

### Setup:
* Just drop the files in their appropriate folders (ie "libraries/Yo.php" goes in your "libraries" folder) 
* Add your API Username and Password in the "config/yo.php" file.
* Load the library in your controller  >>  $this->load->library('yo');
* Start making requests to the Yo! Payments API

### Requests:
#### Deposit Funds
Here, you pull funds FROM a phone's Mobile Money account and deposit them INTO your Yo! Payments business account.
Example:

	$this->yo->deposit('3000', '256772123456');
	
deducts UGX.3000 from the number 256772123456

#### Withdraw Funds
Here, you remove funds FROM your Yo! Payments business account and ADD them to a phone's Mobile Money account.
Example:

	$this->yo->withdraw('3000', '256772123456');

adds UGX.3000 to the number 256772123456

### NB:
* Read the Yo! Payments API documentation for more information at http://payments.yo.co.ug
* You can submit pull requests to this library and I'll be happy to include your changes

-Arnold Mwumva Ford
 ford@meridiansoftech.net / fordarnold@gmail.com
 @fordarnold 
