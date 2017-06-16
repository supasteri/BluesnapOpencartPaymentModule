# Introduction
This project represents the official OpenCart-compatible project for the BlueSnap Payment Gateway. 

# Before installing ...
Before you can install and configure this payment module with your OpenCart web store, you will need to configure your BlueSnap merchant account as follows:
1. Engage with a BlueSnap representative to obtain access to their sandbox environment. This will give you access to a dashboard where your API credentials may be configured. You can set up a Sandbox/Testing account at https://sandbox.bluesnap.com/jsp/new_developer_sandbox.jsp. Note that you will need a valid Merchant account for this process. 
2. Install and configure your plugin to initially perform test transactions against the sandbox environment using test credit card numbers provided by BlueSnap.  
3. Once you have set up and tested your installation against the BlueSnap sandbox environment, the final step will involve performing the production configuration. You will be required to gain access to the production BlueSnap account

## Bluesnap Configuration
To transact with the BlueSnap payment gateway (sandbox or production), you will need to log into the BlueSnap sandbox (https://sandbox.bluesnap.com) or production account pages (https://cp.bluesnap.com/jsp/developer_login.jsp) 

![Bluesnap Sandbox Login Screen](https://raw.githubusercontent.com/supasteri/Opencart-Bluesnap-Payment-Module/master/image-assets/Bluesnap%20-%20Sandbox%20Environment%20-%20Login.png "Bluesnap Sandbox Login Screen")

Once logged in, click on the "Settings -> API Settings" menu option:

![Bluesnap API Settings](https://raw.githubusercontent.com/supasteri/Opencart-Bluesnap-Payment-Module/master/image-assets/Bluesnap%20-%20Sandbox%20Environment%20-%20Post%20Login%20-%20API%20Settings.png
 "Bluesnap API Settings")

You will now need to configure your API Credentials as well as your Authorised IPs, as follows: 

![Bluesnap API Credentials and Authorized IPs](https://raw.githubusercontent.com/supasteri/Opencart-Bluesnap-Payment-Module/master/image-assets/Bluesnap%20-%20Sandbox%20Environment%20-%20Post%20Login%20-%20API%20Settings%20-%20Configuration.png
 "Bluesnap API Credentials and Authorized IPs")

1. Configure your API key. 
2. Configure your whitelisted API. 

# Installation
The installation of the module is relatively simple and follows the standard OpenCart model for payment modules. 
1. Download the contents of the folder that matches your version of OpenCart. For example, if you are using OpenCart 2.1.0.2, you would download the contents of the "oc_2000 - oc_2102" folder. 
2. Copy the contents downloaded in the previous step to the webroot of your OpenCart installation
3. Log onto your administration console, and navigate to "Extensions" -> "Payments", and click the "Install" (Plus) button.

![Payment Module Installation Process - OpenCart 2.1.0.2](https://github.com/supasteri/Opencart-Bluesnap-Payment-Module/raw/master/image-assets/Admin%20-%20Extensions%20-%20Payments%20-%20before-installation.png "Installation process")
Once installed you will can move on and configure the extension.


# Payment Module Configuration 
Once installed, you will be able to configure the BlueSnap payment module. In order to configure the plugin, log into you administration console, and navigate to "Extensions" -> "Payments", and click the "Edit" (pen) button.
![Payment Module Installation Process - OpenCart 2.1.0.2](https://raw.githubusercontent.com/supasteri/Opencart-Bluesnap-Payment-Module/master/image-assets/Admin%20-%20Extensions%20-%20Payments%20-%20post-installation%20-%20before-configuration.png
 "Plugin Configuration")
 
 
Before you can configure the plugin, you will need to ensure that you sign up for a sandbox account with your BlueSnap account manager. 

# Links
* BlueSnap website - https://www.bluesnap.com
* Official plugin extension site - https://www.opencart.com/index.php?route=marketplace/extension/info&extension_id=30987 
* Sandbox developer account - https://sandbox.bluesnap.com/
* Sandbox test credit card numbers - https://support.bluesnap.com/docs/test-credit-card-numbers

# Background and Features
## About
BlueSnap offers a merchant account and payment gateway solution all-in- one. We process your credit and debit card transactions with secure and frictionless checkout for your global shoppers. Our OpenCart payment gateway brings an embedded checkout form directly into your checkout page so that shoppers never leave your store. And the extension is free!

## Features
* Supports OpenCart version 1.x and 2.x
* Accept all Major Credit Cards / Debit Cards – Visa®, MasterCard®, American Express®, Discover®, Diner’s Club, JCB
* Provides a merchant account and payment gateway all-in- one
* Seamless checkout experience, customers stay on your site
* Multi-currency support for global sales which leads to increased conversions
* Best-in- class fraud prevention built in
* Digital and physical goods merchants

## Seamless Checkout Experience
The embedded, secure form looks and feels like it is part of your web page for a frictionless payment experience.

## Global Coverage and Multi-Currency
Our extension serves merchants and shoppers in 180 countries. It is designed to give your shoppers an easy payment experience. 13% of shoppers will abandon their cart if their price is presented in a foreign currency. Avoid checkout abandonment by displaying in 100+ local currencies that shoppers trust. Merchants can decide what currencies they would like to support.

## Safe & Secure
With our OpenCart extension, you will never have to store credit card data. This greatly reduces your PCI compliance scope. In addition, we have the industry’s leading fraud protection from Kount built into our platform, so you can rest easy knowing that we’ve got your back.

## Digital and Physical Goods
BlueSnap processes payments for thousands of merchants selling both digital and physical goods worldwide.

