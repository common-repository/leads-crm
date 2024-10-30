<?php

function zleadscrm_sort_zleadscrm_menu() {
	global $submenu;

	$zleadscrm_menu = $submenu['zleadscrm_bookmarks'];
	$zleadscrm_menu = array_unique( $zleadscrm_menu, SORT_REGULAR );
	foreach ( $zleadscrm_menu as $menu ) {
		if ( $menu[0] == 'Analytics' ) {
			$aux_analytics = $menu;
			unset( $zleadscrm_menu[ array_search( $menu, $zleadscrm_menu ) ] );
		}
		if ( $menu[0] == 'Add-ons' ) {
			$aux_addons = $menu;
			unset( $zleadscrm_menu[ array_search( $menu, $zleadscrm_menu ) ] );
		}
		if ( $menu[0] == 'Settings' ) {
			$aux_settings = $menu;
			unset( $zleadscrm_menu[ array_search( $menu, $zleadscrm_menu ) ] );
		}
		if ( $menu[0] == 'Debug Mode' ) {
			$aux_debug_mode = $menu;
			unset( $zleadscrm_menu[ array_search( $menu, $zleadscrm_menu ) ] );
		}

		if ( $menu[0] == 'Custom Fields' ) {
			$aux_custom_fields = $menu;
			unset( $zleadscrm_menu[ array_search( $menu, $zleadscrm_menu ) ] );
		}

	}

	if ( isset( $aux_analytics ) ) {
		array_push( $zleadscrm_menu, $aux_analytics );
	}

	if ( isset( $aux_custom_fields ) ) {
		array_push( $zleadscrm_menu, $aux_custom_fields );
	}

	if ( isset( $aux_addons ) ) {

		array_push( $zleadscrm_menu, $aux_addons );
	}
	if ( isset( $aux_settings ) ) {
		array_push( $zleadscrm_menu, $aux_settings );
	}
	if ( isset( $aux_debug_mode ) ) {
		array_push( $zleadscrm_menu, $aux_debug_mode );
	}

	$submenu['zleadscrm_bookmarks'] = $zleadscrm_menu;
}

add_action( 'wp_php_error_message', 'zleadscrm_error_logs', 10, 2 );

function zleadscrm_error_logs( $message, $error ) {
	if ( strchr( $error['file'], 'zleadscrm' ) != false ) {
		$pluginlog = ZLEADSCRM_BASIC_PLUGIN_DIR . 'debug.log';
		$message   = '<p>-------------------------- <br>' . PHP_EOL .
		             'Leads Plugin encountered an error. Please send the logs to the support team.<br>' . PHP_EOL .
		             '--------------------------<br>' . PHP_EOL .
		             'Timestamp: ' . date( "F j, Y, g:i a" ) . '<br>' . PHP_EOL .
		             'Error: ' . $error['message'] . '<br>' . PHP_EOL .
		             '--------------------------' . '<br></p>' . PHP_EOL;
		error_log( $message, 3, $pluginlog );
	}
}

function zleadscrm_allow_view_page() {
	$current_user  = get_current_user_id();
	$allowed_users = zleadscrm_get_all_eligible_users();


	return in_array( $current_user, $allowed_users );
}

function zleadscrm_get_lead_statuses() {
	return array(
		'attempted_to_contact'  => 'Attempted To Contact',
		'contact_in_future'     => 'Contact in Future',
		'contacted'             => 'Contacted',
		'junk_lead'             => 'Junk Lead',
		'lost_lead'             => 'Lost Lead',
		'not_contacted'         => 'Not Contacted',
		'pre_qualified'         => 'Pre Qualified',
		'needs_to_be_contacted' => 'Needs to be Contacted',
		'converted_to_customer' => 'Converted to Customer',
	);
}

function zleadscrm_get_lead_sources() {
	return array(
		'prospect_search'   => 'Prospect Search',
		'advertisement'     => 'Advertisement',
		'cold_call'         => 'Cold Call',
		'employee_referral' => 'Employee Referral',
		'external_referral' => 'External Referral',
		'online_store'      => 'Online Store',
		'partner'           => 'Partner',
		'public_relations'  => 'Public Relations',
		'sales_mail_alias'  => 'Sales Mail Alias',
		'trade_show'        => 'Trade Show',
		'chat'              => 'Chat',
		'website_pipeline'  => 'Website Pipeline'
	);
}

function zleadscrm_get_lead_payment_methods() {
	return array(
		'n/a'                  => 'N/A',
		'cash_on_delivery'     => 'Cash On Delivery',
		'direct_bank_transfer' => 'Direct Bank Transfer',
		'credit_terms_net_15'  => 'Credit Terms Net 15',
		'credit_terms_net_30'  => 'Credit Terms Net 30',
		'credit_terms_net_45'  => 'Credit Terms Net 45',
		'credit_terms_net_60'  => 'Credit Terms Net 60',
		'credit_terms_net_90'  => 'Credit Terms Net 90'
	);
}

function zleadscrm_get_lead_customer_statuses() {
	return array(
		'prospect' => 'Prospect',
		'lead'     => 'Lead',
		'customer' => 'Customer',
		'pipeline' => 'Pipeline',
		'flagged'  => 'Flagged',
		'closed'   => 'Closed'
	);
}

function zleadscrm_get_countries() {
	return array(
		'AF' => __( 'Afghanistan', 'woocommerce' ),
		'AX' => __( '&#197;land Islands', 'woocommerce' ),
		'AL' => __( 'Albania', 'woocommerce' ),
		'DZ' => __( 'Algeria', 'woocommerce' ),
		'AS' => __( 'American Samoa', 'woocommerce' ),
		'AD' => __( 'Andorra', 'woocommerce' ),
		'AO' => __( 'Angola', 'woocommerce' ),
		'AI' => __( 'Anguilla', 'woocommerce' ),
		'AQ' => __( 'Antarctica', 'woocommerce' ),
		'AG' => __( 'Antigua and Barbuda', 'woocommerce' ),
		'AR' => __( 'Argentina', 'woocommerce' ),
		'AM' => __( 'Armenia', 'woocommerce' ),
		'AW' => __( 'Aruba', 'woocommerce' ),
		'AU' => __( 'Australia', 'woocommerce' ),
		'AT' => __( 'Austria', 'woocommerce' ),
		'AZ' => __( 'Azerbaijan', 'woocommerce' ),
		'BS' => __( 'Bahamas', 'woocommerce' ),
		'BH' => __( 'Bahrain', 'woocommerce' ),
		'BD' => __( 'Bangladesh', 'woocommerce' ),
		'BB' => __( 'Barbados', 'woocommerce' ),
		'BY' => __( 'Belarus', 'woocommerce' ),
		'BE' => __( 'Belgium', 'woocommerce' ),
		'PW' => __( 'Belau', 'woocommerce' ),
		'BZ' => __( 'Belize', 'woocommerce' ),
		'BJ' => __( 'Benin', 'woocommerce' ),
		'BM' => __( 'Bermuda', 'woocommerce' ),
		'BT' => __( 'Bhutan', 'woocommerce' ),
		'BO' => __( 'Bolivia', 'woocommerce' ),
		'BQ' => __( 'Bonaire, Saint Eustatius and Saba', 'woocommerce' ),
		'BA' => __( 'Bosnia and Herzegovina', 'woocommerce' ),
		'BW' => __( 'Botswana', 'woocommerce' ),
		'BV' => __( 'Bouvet Island', 'woocommerce' ),
		'BR' => __( 'Brazil', 'woocommerce' ),
		'IO' => __( 'British Indian Ocean Territory', 'woocommerce' ),
		'BN' => __( 'Brunei', 'woocommerce' ),
		'BG' => __( 'Bulgaria', 'woocommerce' ),
		'BF' => __( 'Burkina Faso', 'woocommerce' ),
		'BI' => __( 'Burundi', 'woocommerce' ),
		'KH' => __( 'Cambodia', 'woocommerce' ),
		'CM' => __( 'Cameroon', 'woocommerce' ),
		'CA' => __( 'Canada', 'woocommerce' ),
		'CV' => __( 'Cape Verde', 'woocommerce' ),
		'KY' => __( 'Cayman Islands', 'woocommerce' ),
		'CF' => __( 'Central African Republic', 'woocommerce' ),
		'TD' => __( 'Chad', 'woocommerce' ),
		'CL' => __( 'Chile', 'woocommerce' ),
		'CN' => __( 'China', 'woocommerce' ),
		'CX' => __( 'Christmas Island', 'woocommerce' ),
		'CC' => __( 'Cocos (Keeling) Islands', 'woocommerce' ),
		'CO' => __( 'Colombia', 'woocommerce' ),
		'KM' => __( 'Comoros', 'woocommerce' ),
		'CG' => __( 'Congo (Brazzaville)', 'woocommerce' ),
		'CD' => __( 'Congo (Kinshasa)', 'woocommerce' ),
		'CK' => __( 'Cook Islands', 'woocommerce' ),
		'CR' => __( 'Costa Rica', 'woocommerce' ),
		'HR' => __( 'Croatia', 'woocommerce' ),
		'CU' => __( 'Cuba', 'woocommerce' ),
		'CW' => __( 'Cura&ccedil;ao', 'woocommerce' ),
		'CY' => __( 'Cyprus', 'woocommerce' ),
		'CZ' => __( 'Czech Republic', 'woocommerce' ),
		'DK' => __( 'Denmark', 'woocommerce' ),
		'DJ' => __( 'Djibouti', 'woocommerce' ),
		'DM' => __( 'Dominica', 'woocommerce' ),
		'DO' => __( 'Dominican Republic', 'woocommerce' ),
		'EC' => __( 'Ecuador', 'woocommerce' ),
		'EG' => __( 'Egypt', 'woocommerce' ),
		'SV' => __( 'El Salvador', 'woocommerce' ),
		'GQ' => __( 'Equatorial Guinea', 'woocommerce' ),
		'ER' => __( 'Eritrea', 'woocommerce' ),
		'EE' => __( 'Estonia', 'woocommerce' ),
		'ET' => __( 'Ethiopia', 'woocommerce' ),
		'FK' => __( 'Falkland Islands', 'woocommerce' ),
		'FO' => __( 'Faroe Islands', 'woocommerce' ),
		'FJ' => __( 'Fiji', 'woocommerce' ),
		'FI' => __( 'Finland', 'woocommerce' ),
		'FR' => __( 'France', 'woocommerce' ),
		'GF' => __( 'French Guiana', 'woocommerce' ),
		'PF' => __( 'French Polynesia', 'woocommerce' ),
		'TF' => __( 'French Southern Territories', 'woocommerce' ),
		'GA' => __( 'Gabon', 'woocommerce' ),
		'GM' => __( 'Gambia', 'woocommerce' ),
		'GE' => __( 'Georgia', 'woocommerce' ),
		'DE' => __( 'Germany', 'woocommerce' ),
		'GH' => __( 'Ghana', 'woocommerce' ),
		'GI' => __( 'Gibraltar', 'woocommerce' ),
		'GR' => __( 'Greece', 'woocommerce' ),
		'GL' => __( 'Greenland', 'woocommerce' ),
		'GD' => __( 'Grenada', 'woocommerce' ),
		'GP' => __( 'Guadeloupe', 'woocommerce' ),
		'GU' => __( 'Guam', 'woocommerce' ),
		'GT' => __( 'Guatemala', 'woocommerce' ),
		'GG' => __( 'Guernsey', 'woocommerce' ),
		'GN' => __( 'Guinea', 'woocommerce' ),
		'GW' => __( 'Guinea-Bissau', 'woocommerce' ),
		'GY' => __( 'Guyana', 'woocommerce' ),
		'HT' => __( 'Haiti', 'woocommerce' ),
		'HM' => __( 'Heard Island and McDonald Islands', 'woocommerce' ),
		'HN' => __( 'Honduras', 'woocommerce' ),
		'HK' => __( 'Hong Kong', 'woocommerce' ),
		'HU' => __( 'Hungary', 'woocommerce' ),
		'IS' => __( 'Iceland', 'woocommerce' ),
		'IN' => __( 'India', 'woocommerce' ),
		'ID' => __( 'Indonesia', 'woocommerce' ),
		'IR' => __( 'Iran', 'woocommerce' ),
		'IQ' => __( 'Iraq', 'woocommerce' ),
		'IE' => __( 'Ireland', 'woocommerce' ),
		'IM' => __( 'Isle of Man', 'woocommerce' ),
		'IL' => __( 'Israel', 'woocommerce' ),
		'IT' => __( 'Italy', 'woocommerce' ),
		'CI' => __( 'Ivory Coast', 'woocommerce' ),
		'JM' => __( 'Jamaica', 'woocommerce' ),
		'JP' => __( 'Japan', 'woocommerce' ),
		'JE' => __( 'Jersey', 'woocommerce' ),
		'JO' => __( 'Jordan', 'woocommerce' ),
		'KZ' => __( 'Kazakhstan', 'woocommerce' ),
		'KE' => __( 'Kenya', 'woocommerce' ),
		'KI' => __( 'Kiribati', 'woocommerce' ),
		'KW' => __( 'Kuwait', 'woocommerce' ),
		'KG' => __( 'Kyrgyzstan', 'woocommerce' ),
		'LA' => __( 'Laos', 'woocommerce' ),
		'LV' => __( 'Latvia', 'woocommerce' ),
		'LB' => __( 'Lebanon', 'woocommerce' ),
		'LS' => __( 'Lesotho', 'woocommerce' ),
		'LR' => __( 'Liberia', 'woocommerce' ),
		'LY' => __( 'Libya', 'woocommerce' ),
		'LI' => __( 'Liechtenstein', 'woocommerce' ),
		'LT' => __( 'Lithuania', 'woocommerce' ),
		'LU' => __( 'Luxembourg', 'woocommerce' ),
		'MO' => __( 'Macao S.A.R., China', 'woocommerce' ),
		'MK' => __( 'North Macedonia', 'woocommerce' ),
		'MG' => __( 'Madagascar', 'woocommerce' ),
		'MW' => __( 'Malawi', 'woocommerce' ),
		'MY' => __( 'Malaysia', 'woocommerce' ),
		'MV' => __( 'Maldives', 'woocommerce' ),
		'ML' => __( 'Mali', 'woocommerce' ),
		'MT' => __( 'Malta', 'woocommerce' ),
		'MH' => __( 'Marshall Islands', 'woocommerce' ),
		'MQ' => __( 'Martinique', 'woocommerce' ),
		'MR' => __( 'Mauritania', 'woocommerce' ),
		'MU' => __( 'Mauritius', 'woocommerce' ),
		'YT' => __( 'Mayotte', 'woocommerce' ),
		'MX' => __( 'Mexico', 'woocommerce' ),
		'FM' => __( 'Micronesia', 'woocommerce' ),
		'MD' => __( 'Moldova', 'woocommerce' ),
		'MC' => __( 'Monaco', 'woocommerce' ),
		'MN' => __( 'Mongolia', 'woocommerce' ),
		'ME' => __( 'Montenegro', 'woocommerce' ),
		'MS' => __( 'Montserrat', 'woocommerce' ),
		'MA' => __( 'Morocco', 'woocommerce' ),
		'MZ' => __( 'Mozambique', 'woocommerce' ),
		'MM' => __( 'Myanmar', 'woocommerce' ),
		'NA' => __( 'Namibia', 'woocommerce' ),
		'NR' => __( 'Nauru', 'woocommerce' ),
		'NP' => __( 'Nepal', 'woocommerce' ),
		'NL' => __( 'Netherlands', 'woocommerce' ),
		'NC' => __( 'New Caledonia', 'woocommerce' ),
		'NZ' => __( 'New Zealand', 'woocommerce' ),
		'NI' => __( 'Nicaragua', 'woocommerce' ),
		'NE' => __( 'Niger', 'woocommerce' ),
		'NG' => __( 'Nigeria', 'woocommerce' ),
		'NU' => __( 'Niue', 'woocommerce' ),
		'NF' => __( 'Norfolk Island', 'woocommerce' ),
		'MP' => __( 'Northern Mariana Islands', 'woocommerce' ),
		'KP' => __( 'North Korea', 'woocommerce' ),
		'NO' => __( 'Norway', 'woocommerce' ),
		'OM' => __( 'Oman', 'woocommerce' ),
		'PK' => __( 'Pakistan', 'woocommerce' ),
		'PS' => __( 'Palestinian Territory', 'woocommerce' ),
		'PA' => __( 'Panama', 'woocommerce' ),
		'PG' => __( 'Papua New Guinea', 'woocommerce' ),
		'PY' => __( 'Paraguay', 'woocommerce' ),
		'PE' => __( 'Peru', 'woocommerce' ),
		'PH' => __( 'Philippines', 'woocommerce' ),
		'PN' => __( 'Pitcairn', 'woocommerce' ),
		'PL' => __( 'Poland', 'woocommerce' ),
		'PT' => __( 'Portugal', 'woocommerce' ),
		'PR' => __( 'Puerto Rico', 'woocommerce' ),
		'QA' => __( 'Qatar', 'woocommerce' ),
		'RE' => __( 'Reunion', 'woocommerce' ),
		'RO' => __( 'Romania', 'woocommerce' ),
		'RU' => __( 'Russia', 'woocommerce' ),
		'RW' => __( 'Rwanda', 'woocommerce' ),
		'BL' => __( 'Saint Barth&eacute;lemy', 'woocommerce' ),
		'SH' => __( 'Saint Helena', 'woocommerce' ),
		'KN' => __( 'Saint Kitts and Nevis', 'woocommerce' ),
		'LC' => __( 'Saint Lucia', 'woocommerce' ),
		'MF' => __( 'Saint Martin (French part)', 'woocommerce' ),
		'SX' => __( 'Saint Martin (Dutch part)', 'woocommerce' ),
		'PM' => __( 'Saint Pierre and Miquelon', 'woocommerce' ),
		'VC' => __( 'Saint Vincent and the Grenadines', 'woocommerce' ),
		'SM' => __( 'San Marino', 'woocommerce' ),
		'ST' => __( 'S&atilde;o Tom&eacute; and Pr&iacute;ncipe', 'woocommerce' ),
		'SA' => __( 'Saudi Arabia', 'woocommerce' ),
		'SN' => __( 'Senegal', 'woocommerce' ),
		'RS' => __( 'Serbia', 'woocommerce' ),
		'SC' => __( 'Seychelles', 'woocommerce' ),
		'SL' => __( 'Sierra Leone', 'woocommerce' ),
		'SG' => __( 'Singapore', 'woocommerce' ),
		'SK' => __( 'Slovakia', 'woocommerce' ),
		'SI' => __( 'Slovenia', 'woocommerce' ),
		'SB' => __( 'Solomon Islands', 'woocommerce' ),
		'SO' => __( 'Somalia', 'woocommerce' ),
		'ZA' => __( 'South Africa', 'woocommerce' ),
		'GS' => __( 'South Georgia/Sandwich Islands', 'woocommerce' ),
		'KR' => __( 'South Korea', 'woocommerce' ),
		'SS' => __( 'South Sudan', 'woocommerce' ),
		'ES' => __( 'Spain', 'woocommerce' ),
		'LK' => __( 'Sri Lanka', 'woocommerce' ),
		'SD' => __( 'Sudan', 'woocommerce' ),
		'SR' => __( 'Suriname', 'woocommerce' ),
		'SJ' => __( 'Svalbard and Jan Mayen', 'woocommerce' ),
		'SZ' => __( 'Swaziland', 'woocommerce' ),
		'SE' => __( 'Sweden', 'woocommerce' ),
		'CH' => __( 'Switzerland', 'woocommerce' ),
		'SY' => __( 'Syria', 'woocommerce' ),
		'TW' => __( 'Taiwan', 'woocommerce' ),
		'TJ' => __( 'Tajikistan', 'woocommerce' ),
		'TZ' => __( 'Tanzania', 'woocommerce' ),
		'TH' => __( 'Thailand', 'woocommerce' ),
		'TL' => __( 'Timor-Leste', 'woocommerce' ),
		'TG' => __( 'Togo', 'woocommerce' ),
		'TK' => __( 'Tokelau', 'woocommerce' ),
		'TO' => __( 'Tonga', 'woocommerce' ),
		'TT' => __( 'Trinidad and Tobago', 'woocommerce' ),
		'TN' => __( 'Tunisia', 'woocommerce' ),
		'TR' => __( 'Turkey', 'woocommerce' ),
		'TM' => __( 'Turkmenistan', 'woocommerce' ),
		'TC' => __( 'Turks and Caicos Islands', 'woocommerce' ),
		'TV' => __( 'Tuvalu', 'woocommerce' ),
		'UG' => __( 'Uganda', 'woocommerce' ),
		'UA' => __( 'Ukraine', 'woocommerce' ),
		'AE' => __( 'United Arab Emirates', 'woocommerce' ),
		'GB' => __( 'United Kingdom (UK)', 'woocommerce' ),
		'US' => __( 'United States (US)', 'woocommerce' ),
		'UM' => __( 'United States (US) Minor Outlying Islands', 'woocommerce' ),
		'UY' => __( 'Uruguay', 'woocommerce' ),
		'UZ' => __( 'Uzbekistan', 'woocommerce' ),
		'VU' => __( 'Vanuatu', 'woocommerce' ),
		'VA' => __( 'Vatican', 'woocommerce' ),
		'VE' => __( 'Venezuela', 'woocommerce' ),
		'VN' => __( 'Vietnam', 'woocommerce' ),
		'VG' => __( 'Virgin Islands (British)', 'woocommerce' ),
		'VI' => __( 'Virgin Islands (US)', 'woocommerce' ),
		'WF' => __( 'Wallis and Futuna', 'woocommerce' ),
		'EH' => __( 'Western Sahara', 'woocommerce' ),
		'WS' => __( 'Samoa', 'woocommerce' ),
		'YE' => __( 'Yemen', 'woocommerce' ),
		'ZM' => __( 'Zambia', 'woocommerce' ),
		'ZW' => __( 'Zimbabwe', 'woocommerce' ),
	);
}

function zleadscrm_get_currencies() {
	return array(
		'AED' => __( 'United Arab Emirates dirham', 'woocommerce' ),
		'AFN' => __( 'Afghan afghani', 'woocommerce' ),
		'ALL' => __( 'Albanian lek', 'woocommerce' ),
		'AMD' => __( 'Armenian dram', 'woocommerce' ),
		'ANG' => __( 'Netherlands Antillean guilder', 'woocommerce' ),
		'AOA' => __( 'Angolan kwanza', 'woocommerce' ),
		'ARS' => __( 'Argentine peso', 'woocommerce' ),
		'AUD' => __( 'Australian dollar', 'woocommerce' ),
		'AWG' => __( 'Aruban florin', 'woocommerce' ),
		'AZN' => __( 'Azerbaijani manat', 'woocommerce' ),
		'BAM' => __( 'Bosnia and Herzegovina convertible mark', 'woocommerce' ),
		'BBD' => __( 'Barbadian dollar', 'woocommerce' ),
		'BDT' => __( 'Bangladeshi taka', 'woocommerce' ),
		'BGN' => __( 'Bulgarian lev', 'woocommerce' ),
		'BHD' => __( 'Bahraini dinar', 'woocommerce' ),
		'BIF' => __( 'Burundian franc', 'woocommerce' ),
		'BMD' => __( 'Bermudian dollar', 'woocommerce' ),
		'BND' => __( 'Brunei dollar', 'woocommerce' ),
		'BOB' => __( 'Bolivian boliviano', 'woocommerce' ),
		'BRL' => __( 'Brazilian real', 'woocommerce' ),
		'BSD' => __( 'Bahamian dollar', 'woocommerce' ),
		'BTC' => __( 'Bitcoin', 'woocommerce' ),
		'BTN' => __( 'Bhutanese ngultrum', 'woocommerce' ),
		'BWP' => __( 'Botswana pula', 'woocommerce' ),
		'BYR' => __( 'Belarusian ruble (old)', 'woocommerce' ),
		'BYN' => __( 'Belarusian ruble', 'woocommerce' ),
		'BZD' => __( 'Belize dollar', 'woocommerce' ),
		'CAD' => __( 'Canadian dollar', 'woocommerce' ),
		'CDF' => __( 'Congolese franc', 'woocommerce' ),
		'CHF' => __( 'Swiss franc', 'woocommerce' ),
		'CLP' => __( 'Chilean peso', 'woocommerce' ),
		'CNY' => __( 'Chinese yuan', 'woocommerce' ),
		'COP' => __( 'Colombian peso', 'woocommerce' ),
		'CRC' => __( 'Costa Rican col&oacute;n', 'woocommerce' ),
		'CUC' => __( 'Cuban convertible peso', 'woocommerce' ),
		'CUP' => __( 'Cuban peso', 'woocommerce' ),
		'CVE' => __( 'Cape Verdean escudo', 'woocommerce' ),
		'CZK' => __( 'Czech koruna', 'woocommerce' ),
		'DJF' => __( 'Djiboutian franc', 'woocommerce' ),
		'DKK' => __( 'Danish krone', 'woocommerce' ),
		'DOP' => __( 'Dominican peso', 'woocommerce' ),
		'DZD' => __( 'Algerian dinar', 'woocommerce' ),
		'EGP' => __( 'Egyptian pound', 'woocommerce' ),
		'ERN' => __( 'Eritrean nakfa', 'woocommerce' ),
		'ETB' => __( 'Ethiopian birr', 'woocommerce' ),
		'EUR' => __( 'Euro', 'woocommerce' ),
		'FJD' => __( 'Fijian dollar', 'woocommerce' ),
		'FKP' => __( 'Falkland Islands pound', 'woocommerce' ),
		'GBP' => __( 'Pound sterling', 'woocommerce' ),
		'GEL' => __( 'Georgian lari', 'woocommerce' ),
		'GGP' => __( 'Guernsey pound', 'woocommerce' ),
		'GHS' => __( 'Ghana cedi', 'woocommerce' ),
		'GIP' => __( 'Gibraltar pound', 'woocommerce' ),
		'GMD' => __( 'Gambian dalasi', 'woocommerce' ),
		'GNF' => __( 'Guinean franc', 'woocommerce' ),
		'GTQ' => __( 'Guatemalan quetzal', 'woocommerce' ),
		'GYD' => __( 'Guyanese dollar', 'woocommerce' ),
		'HKD' => __( 'Hong Kong dollar', 'woocommerce' ),
		'HNL' => __( 'Honduran lempira', 'woocommerce' ),
		'HRK' => __( 'Croatian kuna', 'woocommerce' ),
		'HTG' => __( 'Haitian gourde', 'woocommerce' ),
		'HUF' => __( 'Hungarian forint', 'woocommerce' ),
		'IDR' => __( 'Indonesian rupiah', 'woocommerce' ),
		'ILS' => __( 'Israeli new shekel', 'woocommerce' ),
		'IMP' => __( 'Manx pound', 'woocommerce' ),
		'INR' => __( 'Indian rupee', 'woocommerce' ),
		'IQD' => __( 'Iraqi dinar', 'woocommerce' ),
		'IRR' => __( 'Iranian rial', 'woocommerce' ),
		'IRT' => __( 'Iranian toman', 'woocommerce' ),
		'ISK' => __( 'Icelandic kr&oacute;na', 'woocommerce' ),
		'JEP' => __( 'Jersey pound', 'woocommerce' ),
		'JMD' => __( 'Jamaican dollar', 'woocommerce' ),
		'JOD' => __( 'Jordanian dinar', 'woocommerce' ),
		'JPY' => __( 'Japanese yen', 'woocommerce' ),
		'KES' => __( 'Kenyan shilling', 'woocommerce' ),
		'KGS' => __( 'Kyrgyzstani som', 'woocommerce' ),
		'KHR' => __( 'Cambodian riel', 'woocommerce' ),
		'KMF' => __( 'Comorian franc', 'woocommerce' ),
		'KPW' => __( 'North Korean won', 'woocommerce' ),
		'KRW' => __( 'South Korean won', 'woocommerce' ),
		'KWD' => __( 'Kuwaiti dinar', 'woocommerce' ),
		'KYD' => __( 'Cayman Islands dollar', 'woocommerce' ),
		'KZT' => __( 'Kazakhstani tenge', 'woocommerce' ),
		'LAK' => __( 'Lao kip', 'woocommerce' ),
		'LBP' => __( 'Lebanese pound', 'woocommerce' ),
		'LKR' => __( 'Sri Lankan rupee', 'woocommerce' ),
		'LRD' => __( 'Liberian dollar', 'woocommerce' ),
		'LSL' => __( 'Lesotho loti', 'woocommerce' ),
		'LYD' => __( 'Libyan dinar', 'woocommerce' ),
		'MAD' => __( 'Moroccan dirham', 'woocommerce' ),
		'MDL' => __( 'Moldovan leu', 'woocommerce' ),
		'MGA' => __( 'Malagasy ariary', 'woocommerce' ),
		'MKD' => __( 'Macedonian denar', 'woocommerce' ),
		'MMK' => __( 'Burmese kyat', 'woocommerce' ),
		'MNT' => __( 'Mongolian t&ouml;gr&ouml;g', 'woocommerce' ),
		'MOP' => __( 'Macanese pataca', 'woocommerce' ),
		'MRO' => __( 'Mauritanian ouguiya', 'woocommerce' ),
		'MUR' => __( 'Mauritian rupee', 'woocommerce' ),
		'MVR' => __( 'Maldivian rufiyaa', 'woocommerce' ),
		'MWK' => __( 'Malawian kwacha', 'woocommerce' ),
		'MXN' => __( 'Mexican peso', 'woocommerce' ),
		'MYR' => __( 'Malaysian ringgit', 'woocommerce' ),
		'MZN' => __( 'Mozambican metical', 'woocommerce' ),
		'NAD' => __( 'Namibian dollar', 'woocommerce' ),
		'NGN' => __( 'Nigerian naira', 'woocommerce' ),
		'NIO' => __( 'Nicaraguan c&oacute;rdoba', 'woocommerce' ),
		'NOK' => __( 'Norwegian krone', 'woocommerce' ),
		'NPR' => __( 'Nepalese rupee', 'woocommerce' ),
		'NZD' => __( 'New Zealand dollar', 'woocommerce' ),
		'OMR' => __( 'Omani rial', 'woocommerce' ),
		'PAB' => __( 'Panamanian balboa', 'woocommerce' ),
		'PEN' => __( 'Sol', 'woocommerce' ),
		'PGK' => __( 'Papua New Guinean kina', 'woocommerce' ),
		'PHP' => __( 'Philippine peso', 'woocommerce' ),
		'PKR' => __( 'Pakistani rupee', 'woocommerce' ),
		'PLN' => __( 'Polish z&#x142;oty', 'woocommerce' ),
		'PRB' => __( 'Transnistrian ruble', 'woocommerce' ),
		'PYG' => __( 'Paraguayan guaran&iacute;', 'woocommerce' ),
		'QAR' => __( 'Qatari riyal', 'woocommerce' ),
		'RON' => __( 'Romanian leu', 'woocommerce' ),
		'RSD' => __( 'Serbian dinar', 'woocommerce' ),
		'RUB' => __( 'Russian ruble', 'woocommerce' ),
		'RWF' => __( 'Rwandan franc', 'woocommerce' ),
		'SAR' => __( 'Saudi riyal', 'woocommerce' ),
		'SBD' => __( 'Solomon Islands dollar', 'woocommerce' ),
		'SCR' => __( 'Seychellois rupee', 'woocommerce' ),
		'SDG' => __( 'Sudanese pound', 'woocommerce' ),
		'SEK' => __( 'Swedish krona', 'woocommerce' ),
		'SGD' => __( 'Singapore dollar', 'woocommerce' ),
		'SHP' => __( 'Saint Helena pound', 'woocommerce' ),
		'SLL' => __( 'Sierra Leonean leone', 'woocommerce' ),
		'SOS' => __( 'Somali shilling', 'woocommerce' ),
		'SRD' => __( 'Surinamese dollar', 'woocommerce' ),
		'SSP' => __( 'South Sudanese pound', 'woocommerce' ),
		'STD' => __( 'S&atilde;o Tom&eacute; and Pr&iacute;ncipe dobra', 'woocommerce' ),
		'SYP' => __( 'Syrian pound', 'woocommerce' ),
		'SZL' => __( 'Swazi lilangeni', 'woocommerce' ),
		'THB' => __( 'Thai baht', 'woocommerce' ),
		'TJS' => __( 'Tajikistani somoni', 'woocommerce' ),
		'TMT' => __( 'Turkmenistan manat', 'woocommerce' ),
		'TND' => __( 'Tunisian dinar', 'woocommerce' ),
		'TOP' => __( 'Tongan pa&#x2bb;anga', 'woocommerce' ),
		'TRY' => __( 'Turkish lira', 'woocommerce' ),
		'TTD' => __( 'Trinidad and Tobago dollar', 'woocommerce' ),
		'TWD' => __( 'New Taiwan dollar', 'woocommerce' ),
		'TZS' => __( 'Tanzanian shilling', 'woocommerce' ),
		'UAH' => __( 'Ukrainian hryvnia', 'woocommerce' ),
		'UGX' => __( 'Ugandan shilling', 'woocommerce' ),
		'USD' => __( 'United States (US) dollar', 'woocommerce' ),
		'UYU' => __( 'Uruguayan peso', 'woocommerce' ),
		'UZS' => __( 'Uzbekistani som', 'woocommerce' ),
		'VEF' => __( 'Venezuelan bol&iacute;var', 'woocommerce' ),
		'VES' => __( 'Bol&iacute;var soberano', 'woocommerce' ),
		'VND' => __( 'Vietnamese &#x111;&#x1ed3;ng', 'woocommerce' ),
		'VUV' => __( 'Vanuatu vatu', 'woocommerce' ),
		'WST' => __( 'Samoan t&#x101;l&#x101;', 'woocommerce' ),
		'XAF' => __( 'Central African CFA franc', 'woocommerce' ),
		'XCD' => __( 'East Caribbean dollar', 'woocommerce' ),
		'XOF' => __( 'West African CFA franc', 'woocommerce' ),
		'XPF' => __( 'CFP franc', 'woocommerce' ),
		'YER' => __( 'Yemeni rial', 'woocommerce' ),
		'ZAR' => __( 'South African rand', 'woocommerce' ),
		'ZMW' => __( 'Zambian kwacha', 'woocommerce' ),
	);
}

function zleadscrm_get_currency_symbol( $curr ) {
	$symb = array(
		'AED' => '&#x62f;.&#x625;',
		'AFN' => '&#x60b;',
		'ALL' => 'L',
		'AMD' => 'AMD',
		'ANG' => '&fnof;',
		'AOA' => 'Kz',
		'ARS' => '&#36;',
		'AUD' => '&#36;',
		'AWG' => 'Afl.',
		'AZN' => 'AZN',
		'BAM' => 'KM',
		'BBD' => '&#36;',
		'BDT' => '&#2547;&nbsp;',
		'BGN' => '&#1083;&#1074;.',
		'BHD' => '.&#x62f;.&#x628;',
		'BIF' => 'Fr',
		'BMD' => '&#36;',
		'BND' => '&#36;',
		'BOB' => 'Bs.',
		'BRL' => '&#82;&#36;',
		'BSD' => '&#36;',
		'BTC' => '&#3647;',
		'BTN' => 'Nu.',
		'BWP' => 'P',
		'BYR' => 'Br',
		'BYN' => 'Br',
		'BZD' => '&#36;',
		'CAD' => '&#36;',
		'CDF' => 'Fr',
		'CHF' => '&#67;&#72;&#70;',
		'CLP' => '&#36;',
		'CNY' => '&yen;',
		'COP' => '&#36;',
		'CRC' => '&#x20a1;',
		'CUC' => '&#36;',
		'CUP' => '&#36;',
		'CVE' => '&#36;',
		'CZK' => '&#75;&#269;',
		'DJF' => 'Fr',
		'DKK' => 'DKK',
		'DOP' => 'RD&#36;',
		'DZD' => '&#x62f;.&#x62c;',
		'EGP' => 'EGP',
		'ERN' => 'Nfk',
		'ETB' => 'Br',
		'EUR' => '&euro;',
		'FJD' => '&#36;',
		'FKP' => '&pound;',
		'GBP' => '&pound;',
		'GEL' => '&#x20be;',
		'GGP' => '&pound;',
		'GHS' => '&#x20b5;',
		'GIP' => '&pound;',
		'GMD' => 'D',
		'GNF' => 'Fr',
		'GTQ' => 'Q',
		'GYD' => '&#36;',
		'HKD' => '&#36;',
		'HNL' => 'L',
		'HRK' => 'kn',
		'HTG' => 'G',
		'HUF' => '&#70;&#116;',
		'IDR' => 'Rp',
		'ILS' => '&#8362;',
		'IMP' => '&pound;',
		'INR' => '&#8377;',
		'IQD' => '&#x639;.&#x62f;',
		'IRR' => '&#xfdfc;',
		'IRT' => '&#x062A;&#x0648;&#x0645;&#x0627;&#x0646;',
		'ISK' => 'kr.',
		'JEP' => '&pound;',
		'JMD' => '&#36;',
		'JOD' => '&#x62f;.&#x627;',
		'JPY' => '&yen;',
		'KES' => 'KSh',
		'KGS' => '&#x441;&#x43e;&#x43c;',
		'KHR' => '&#x17db;',
		'KMF' => 'Fr',
		'KPW' => '&#x20a9;',
		'KRW' => '&#8361;',
		'KWD' => '&#x62f;.&#x643;',
		'KYD' => '&#36;',
		'KZT' => 'KZT',
		'LAK' => '&#8365;',
		'LBP' => '&#x644;.&#x644;',
		'LKR' => '&#xdbb;&#xdd4;',
		'LRD' => '&#36;',
		'LSL' => 'L',
		'LYD' => '&#x644;.&#x62f;',
		'MAD' => '&#x62f;.&#x645;.',
		'MDL' => 'MDL',
		'MGA' => 'Ar',
		'MKD' => '&#x434;&#x435;&#x43d;',
		'MMK' => 'Ks',
		'MNT' => '&#x20ae;',
		'MOP' => 'P',
		'MRO' => 'UM',
		'MUR' => '&#x20a8;',
		'MVR' => '.&#x783;',
		'MWK' => 'MK',
		'MXN' => '&#36;',
		'MYR' => '&#82;&#77;',
		'MZN' => 'MT',
		'NAD' => '&#36;',
		'NGN' => '&#8358;',
		'NIO' => 'C&#36;',
		'NOK' => '&#107;&#114;',
		'NPR' => '&#8360;',
		'NZD' => '&#36;',
		'OMR' => '&#x631;.&#x639;.',
		'PAB' => 'B/.',
		'PEN' => 'S/',
		'PGK' => 'K',
		'PHP' => '&#8369;',
		'PKR' => '&#8360;',
		'PLN' => '&#122;&#322;',
		'PRB' => '&#x440;.',
		'PYG' => '&#8370;',
		'QAR' => '&#x631;.&#x642;',
		'RMB' => '&yen;',
		'RON' => 'lei',
		'RSD' => '&#x434;&#x438;&#x43d;.',
		'RUB' => '&#8381;',
		'RWF' => 'Fr',
		'SAR' => '&#x631;.&#x633;',
		'SBD' => '&#36;',
		'SCR' => '&#x20a8;',
		'SDG' => '&#x62c;.&#x633;.',
		'SEK' => '&#107;&#114;',
		'SGD' => '&#36;',
		'SHP' => '&pound;',
		'SLL' => 'Le',
		'SOS' => 'Sh',
		'SRD' => '&#36;',
		'SSP' => '&pound;',
		'STD' => 'Db',
		'SYP' => '&#x644;.&#x633;',
		'SZL' => 'L',
		'THB' => '&#3647;',
		'TJS' => '&#x405;&#x41c;',
		'TMT' => 'm',
		'TND' => '&#x62f;.&#x62a;',
		'TOP' => 'T&#36;',
		'TRY' => '&#8378;',
		'TTD' => '&#36;',
		'TWD' => '&#78;&#84;&#36;',
		'TZS' => 'Sh',
		'UAH' => '&#8372;',
		'UGX' => 'UGX',
		'USD' => '&#36;',
		'UYU' => '&#36;',
		'UZS' => 'UZS',
		'VEF' => 'Bs F',
		'VES' => 'Bs.S',
		'VND' => '&#8363;',
		'VUV' => 'Vt',
		'WST' => 'T',
		'XAF' => 'CFA',
		'XCD' => '&#36;',
		'XOF' => 'CFA',
		'XPF' => 'Fr',
		'YER' => '&#xfdfc;',
		'ZAR' => '&#82;',
		'ZMW' => 'ZK',
	);

	return $symb[ $curr ];
}

function zleadscrm_get_currency_info( $curr ) {

	$info = array(
		'AUD' => array(
			'currency_code' => 'AUD',
			'currency_pos'  => 'left',
			'thousand_sep'  => ',',
			'decimal_sep'   => '.',
			'num_decimals'  => 2,
		),
		'BDT' => array(
			'currency_code' => 'BDT',
			'currency_pos'  => 'left',
			'thousand_sep'  => ',',
			'decimal_sep'   => '.',
			'num_decimals'  => 2,
		),
		'EUR' => array(
			'currency_code' => 'EUR',
			'currency_pos'  => 'left',
			'thousand_sep'  => '.',
			'decimal_sep'   => ',',
			'num_decimals'  => 2,
		),
		'BRL' => array(
			'currency_code' => 'BRL',
			'currency_pos'  => 'left',
			'thousand_sep'  => '.',
			'decimal_sep'   => ',',
			'num_decimals'  => 2
		),
		'CAD' => array(
			'currency_code' => 'CAD',
			'currency_pos'  => 'left',
			'thousand_sep'  => ',',
			'decimal_sep'   => '.',
			'num_decimals'  => 2,
		),
		'DKK' => array(
			'currency_code' => 'DKK',
			'currency_pos'  => 'left_space',
			'thousand_sep'  => '.',
			'decimal_sep'   => ',',
			'num_decimals'  => 2,
		),
		'GBP' => array(
			'currency_code' => 'GBP',
			'currency_pos'  => 'left',
			'thousand_sep'  => ',',
			'decimal_sep'   => '.',
			'num_decimals'  => 2,
		),
		'HUF' => array(
			'currency_code' => 'HUF',
			'currency_pos'  => 'right_space',
			'thousand_sep'  => ' ',
			'decimal_sep'   => ',',
			'num_decimals'  => 0,
		),
		'JPY' => array(
			'currency_code' => 'JPY',
			'currency_pos'  => 'left',
			'thousand_sep'  => ',',
			'decimal_sep'   => '.',
			'num_decimals'  => 0,
		),
		'MDL' => array(
			'currency_code' => 'MDL',
			'currency_pos'  => 'right_space',
			'thousand_sep'  => '.',
			'decimal_sep'   => ',',
			'num_decimals'  => 2,
		),
		'Kr'  => array(
			'currency_code' => 'Kr',
			'currency_pos'  => 'left_space',
			'thousand_sep'  => '.',
			'decimal_sep'   => ',',
			'num_decimals'  => 2,
		),
		'NPR' => array(
			'currency_code' => 'NPR',
			'currency_pos'  => 'left_space',
			'thousand_sep'  => ',',
			'decimal_sep'   => '.',
			'num_decimals'  => 2,
		),
		'PLN' => array(
			'currency_code' => 'PLN',
			'currency_pos'  => 'right_space',
			'thousand_sep'  => ' ',
			'decimal_sep'   => ',',
			'num_decimals'  => 2,
		),
		'PYG' => array(
			'currency_code' => 'PYG',
			'currency_pos'  => 'left',
			'thousand_sep'  => '.',
			'decimal_sep'   => ',',
			'num_decimals'  => 2,
		),
		'RON' => array(
			'currency_code' => 'RON',
			'currency_pos'  => 'right_space',
			'thousand_sep'  => '.',
			'decimal_sep'   => ',',
			'num_decimals'  => 2,
		),
		'RSD' => array(
			'currency_code' => 'RSD',
			'currency_pos'  => 'right_space',
			'thousand_sep'  => '.',
			'decimal_sep'   => ',',
			'num_decimals'  => 2,
		),
		'THB' => array(
			'currency_code' => 'THB',
			'currency_pos'  => 'left',
			'thousand_sep'  => ',',
			'decimal_sep'   => '.',
			'num_decimals'  => 2,
		),
		'TRY' => array(
			'currency_code' => 'TRY',
			'currency_pos'  => 'left_space',
			'thousand_sep'  => '.',
			'decimal_sep'   => ',',
			'num_decimals'  => 2,
		),
		'USD' => array(
			'currency_code' => 'USD',
			'currency_pos'  => 'left',
			'thousand_sep'  => ',',
			'decimal_sep'   => '.',
			'num_decimals'  => 2,
		),
		'ZAR' => array(
			'currency_code' => 'ZAR',
			'currency_pos'  => 'left',
			'thousand_sep'  => ',',
			'decimal_sep'   => '.',
			'num_decimals'  => 2,
		),
	);

	if ( isset( $info[ $curr ] ) ) {
		return $info[ $curr ];
	} else {
		return array(
			'currency_pos' => 'left',
			'thousand_sep' => ',',
			'decimal_sep'  => '.',
			'num_decimals' => 2,
		);
	}
}

function zleadscrm_get_business_types() {
	return array(
		'accounting',
		'airport',
		'amusement_park',
		'aquarium',
		'art_gallery',
		'atm',
		'bakery',
		'bank',
		'bar',
		'beauty_salon',
		'bicycle_store',
		'book_store',
		'bowling_alley',
		'bus_station',
		'cafe',
		'campground',
		'car_dealer',
		'car_rental',
		'car_repair',
		'car_wash',
		'casino',
		'cemetery',
		'church',
		'city_hall',
		'clothing_store',
		'convenience_store',
		'courthouse',
		'dentist',
		'department_store',
		'doctor',
		'electrician',
		'electronics_store',
		'embassy',
		'fire_station',
		'florist',
		'funeral_home',
		'furniture_store',
		'gas_station',
		'gym',
		'hair_care',
		'hardware_store',
		'hindu_temple',
		'home_goods_store',
		'hospital',
		'insurance_agency',
		'jewelry_store',
		'laundry',
		'lawyer',
		'library',
		'liquor_store',
		'local_government_office',
		'locksmith',
		'lodging',
		'meal_delivery',
		'meal_takeaway',
		'mosque',
		'movie_rental',
		'movie_theater',
		'moving_company',
		'museum',
		'night_club',
		'painter',
		'park',
		'parking',
		'pet_store',
		'pharmacy',
		'physiotherapist',
		'plumber',
		'police',
		'post_office',
		'real_estate_agency',
		'restaurant',
		'roofing_contractor',
		'rv_park',
		'school',
		'shoe_store',
		'shopping_mall',
		'spa',
		'stadium',
		'storage',
		'store',
		'subway_station',
		'supermarket',
		'synagogue',
		'taxi_stand',
		'train_station',
		'transit_station',
		'travel_agency',
		'veterinary_care',
		'zoo'
	);
}

function zleadscrm_get_hide_settings_in_menu() {
	return get_option( 'zleadscrm_hide_settings_in_menu', 0 );
}

function zleadscrm_get_user_access_settings() {
	return get_option( 'zleadscrm_user_access_settings', 'administrators' );
}

function zleadscrm_get_all_eligible_users() {
	$selected_roles = zleadscrm_get_selected_roles();

	$query = new WP_User_Query( array(
		'role__in' => $selected_roles
	) );

	$users    = $query->get_results();
	$user_ids = [];

	foreach ( $users as $user ) {
		$user_ids[] = $user->ID;
	}

	return $user_ids;
}

function zleadscrm_get_active_eligible_users() {
	global $wpdb;
	$table_name = $wpdb->prefix . 'zleadscrm_leads_user_analytics';

	if ( isset( $_GET['range'] ) ) {
		$range = sanitize_text_field( $_GET['range'] );
	} else {
		$range = 'all';
	}
	switch ( $range ) {
		case '7day':
			$start_date = date( 'Y-m-d 00:00:00', strtotime( '-7 day' ) );
			$end_date   = date( 'Y-m-d 23:59:59' );
			break;
		case 'month':
			$start_date = date( 'Y-m-01 00:00:00' );
			$end_date   = date( 'Y-m-d 23:59:59' );
			break;
		case 'last_month' :
			$start_date = date( 'Y-m-01 00:00:00', strtotime( '-1 month' ) );
			$end_date   = date( 'Y-m-d 23:59:59', strtotime( 'last day of previous month' ) );
			break;
		case 'year':
			$start_date = date( 'Y-01-01 00:00:00' );
			$end_date   = date( 'Y-m-d 23:59:59' );
			break;
		case 'custom':
			$start_date = sanitize_text_field( $_GET['start_date'] );
			$end_date   = sanitize_text_field( $_GET['end_date'] );
			break;
		default:
			$start_date = date( '1970-01-01 00:00:00' );
			$end_date   = date( 'Y-m-d 23:59:59' );
			break;
	}

	$results = $wpdb->get_results( "SELECT user_id, SUM(basic_results) AS basic_results, SUM(advanced_results) AS advanced_results, SUM(basic_requests) AS search_request FROM $table_name WHERE timestamp<='$end_date' AND timestamp>='$start_date' GROUP BY user_id;" );

	return $results;

}

function zleadscrm_get_roles() {
	global $wp_roles;

	$all_roles      = $wp_roles->roles;
	$editable_roles = apply_filters( 'editable_roles', $all_roles );

	return $editable_roles;
}

function zleadscrm_get_selected_roles() {
	return get_option( 'zleadscrm_selected_roles', [ 'administrator' ] );
}

function zleadscrm_allow_edit_settings() {
	$zleadscrm_user_access_settings = zleadscrm_get_user_access_settings();

	if ( $zleadscrm_user_access_settings == 'administrators' ) {
		return current_user_can( 'administrator' );
	}

	if ( $zleadscrm_user_access_settings == 'manage_options' ) {
		return current_user_can( 'manage_options' );
	}

	if ( is_numeric( $zleadscrm_user_access_settings ) ) {
		return get_current_user_id() == $zleadscrm_user_access_settings;
	}
}

function zleadscrm_get_users_allowed_access_analytics_setting() {
	return get_option( 'zleadscrm_users_allowed_access_analytics_setting', 'all' );
}

function zleadscrm_get_access_analytics_users() {
	return get_option( 'zleadscrm_allowed_access_analytics_users', [] );
}

function zleadscrm_get_users_allowed_company_setting() {
	return get_option( 'zleadscrm_users_allowed_company_setting', 'all' );
}

function zleadscrm_get_company_users() {
	return get_option( 'zleadscrm_allowed_company_users', [] );
}

function zleadscrm_get_number_of_leads_created( $user_id ) {
	$query = new WP_User_Query( array(
		'role'       => 'lead',
		'meta_key'   => 'zleadscrm_lead_author',
		'meta_value' => $user_id
	) );

	return count( $query->get_results() );
}

function zleadscrm_get_number_of_leads( $user_id ) {
	$query = new WP_User_Query( array(
		'role'       => 'lead',
		'meta_key'   => 'zleadscrm_account_manager',
		'meta_value' => $user_id
	) );

	$users = $query->get_results();
	if ( count( $users ) == 0 ) {
		return 0;
	}
	if ( count( $users ) > 1 ) {
		$leads = [];
		foreach ( $users as $user ) {
			array_push( $leads, $user->ID );
		}
		$customer_ids_content  = implode( ",", $leads );
		$customer_ids_codition = ' IN (' . $customer_ids_content . ')';
	} else {
		$customer_ids_codition = '=' . $users[0]->ID;
	}


	global $wpdb;
	$table_name = $wpdb->prefix . 'zacctmgr_acm_assignments_mapping';

	if ( ! isset( $_GET['range'] ) ) {
		$range = 'all';
	} else {
		$range = sanitize_text_field( $_GET['range'] );
	}
	switch ( $range ) {
		case '7day':
			$start_date = date( 'Y-m-d 00:00:00', strtotime( '-7 day' ) );
			$end_date   = date( 'Y-m-d 23:59:59' );
			break;
		case 'month':
			$start_date = date( 'Y-m-01 00:00:00' );
			$end_date   = date( 'Y-m-d 23:59:59' );
			break;
		case 'last_month' :
			$start_date = date( 'Y-m-01 00:00:00', strtotime( '-1 month' ) );
			$end_date   = date( 'Y-m-d 23:59:59', strtotime( 'last day of previous month' ) );
			break;
		case 'year':
			$start_date = date( 'Y-01-01 00:00:00' );
			$end_date   = date( 'Y-m-d 23:59:59' );
			break;
		case 'custom':
			$start_date = sanitize_text_field( $_GET['start_date'] );
			$end_date   = sanitize_text_field( $_GET['end_date'] );
			break;
		default:
			$start_date = date( '1970-01-01 00:00:00' );
			$end_date   = date( 'Y-m-d 23:59:59' );
			break;
	}

	$result = $wpdb->get_results( "SELECT DISTINCT customer_id FROM $table_name WHERE timestamp<='$end_date' AND timestamp>='$start_date' AND manager_id=$user_id AND customer_id$customer_ids_codition;" );

	return isset( $result ) ? count( $result ) : 0;

}

function zleadscrm_get_analytics_data( $user_id ) {
	global $wpdb;
	$table_name = $wpdb->prefix . 'zleadscrm_leads_user_analytics';

	if ( ! isset( $_GET['range'] ) ) {
		$range = 'all';
	} else {
		$range = sanitize_text_field( $_GET['range'] );
	}
	switch ( $range ) {
		case '7day':
			$start_date = date( 'Y-m-d 00:00:00', strtotime( '-7 day' ) );
			$end_date   = date( 'Y-m-d 23:59:59' );
			break;
		case 'month':
			$start_date = date( 'Y-m-01 00:00:00' );
			$end_date   = date( 'Y-m-d 23:59:59' );
			break;
		case 'last_month' :
			$start_date = date( 'Y-m-01 00:00:00', strtotime( '-1 month' ) );
			$end_date   = date( 'Y-m-d 23:59:59', strtotime( 'last day of previous month' ) );
			break;
		case 'year':
			$start_date = date( 'Y-01-01 00:00:00' );
			$end_date   = date( 'Y-m-d 23:59:59' );
			break;
		case 'custom':
			$start_date = sanitize_text_field( $_GET['start_date'] );
			$end_date   = sanitize_text_field( $_GET['end_date'] );
			break;
		default:
			$start_date = date( '1970-01-01 00:00:00' );
			$end_date   = date( 'Y-m-d 23:59:59' );
			break;
	}

	if ( $user_id == 0 ) {
		$results = $wpdb->get_results( "SELECT * FROM $table_name WHERE timestamp<='$end_date' AND timestamp>='$start_date' ORDER BY timestamp DESC;" );
	} else {
		$results = $wpdb->get_results( "SELECT * FROM $table_name WHERE timestamp<='$end_date' AND timestamp>='$start_date' AND user_id=$user_id ORDER BY timestamp DESC;" );
	}

	$total_search_requests  = 0;
	$total_basic_results    = 0;
	$total_advanced_results = 0;

	foreach ( $results as $result ) {
		$total_search_requests  += $result->basic_requests;
		$total_basic_results    += $result->basic_results;
		$total_advanced_results += $result->advanced_results;
	}

	return array(
		'search_requests'  => $total_search_requests,
		'basic_results'    => $total_basic_results,
		'advanced_results' => $total_advanced_results
	);
}

function zleadscrm_sanitize_array( $data = [] ) {
	if ( $data ) {
		foreach ( $data as &$value ) {
			$value = sanitize_text_field( $value );
		}
	}

	return $data;
}

function zleadscrm_get_admins_query_by_key( $search = '' ) {
	$current_page = $offset = 0;
	$per_page     = - 1;

	$query = new WP_User_Query(
		array(
			'number'     => $per_page,
			'offset'     => $offset,
			'role__in'   => zleadscrm_get_selected_roles(),
			'meta_query' => array(
				'relation' => 'OR',
				array(
					'key'     => 'first_name',
					'value'   => $search,
					'compare' => 'LIKE'
				),
				array(
					'key'     => 'last_name',
					'value'   => $search,
					'compare' => 'LIKE'
				)
			)
		)
	);

	return $query;
}

function zleadscrm_get_customer_status_label( $customer_status ) {
	switch ( $customer_status ) {
		case 'prospect':
			return '<span style="color: #1370ca; padding: 6px 0.5vw; border-radius: 4px; background: #c2dbf3;">Prospect</span>';
			break;
		case 'lead':
			return '<span style="color: #585858; padding: 6px 0.5vw; border-radius: 4px; background: #ecee9a;">Lead</span>';
			break;
		case 'customer':
			return '<span style="color: #679b13; padding: 6px 0.5vw;border-radius: 4px; background: #dde8cb;">Customer</span>';
			break;
		case 'flagged':
			return '<span style="color: #d57c14; padding: 6px 0.5vw; border-radius: 4px; background: #fdc98c;">Flagged</span>';
			break;
		case 'closed':
			return '<span style=" color: #ce1818; padding: 6px 0.5vw; border-radius: 4px; background: #dea0a0;">Closed</span>';
			break;
		case 'pipeline':
			return '<span style=" color: #9c3d78; padding: 6px 0.5vw; border-radius: 4px; background: #df9dc6;">Pipeline</span>';
			break;
	}

	return '';
}

function zleadscrm_insert_lead_edit_audit( $audit ) {
	global $wpdb;

	$table_name = $wpdb->prefix . 'zleadscrm_leads_edit_audit';

	$old_value = str_replace( '\\', '', $audit['old_value'] );
	$new_value = str_replace( '\\', '', $audit['new_value'] );
	if ( $old_value != $new_value ) {

		$wpdb->insert( $table_name, array(
			'editor_id' => sanitize_text_field( $audit['editor_id'] ),
			'user_id'   => sanitize_text_field( $audit['user_id'] ),
			'timestamp' => current_time( 'mysql' ),
			'old_value' => sanitize_text_field( $old_value ),
			'new_value' => sanitize_text_field( $new_value ),
			'action'    => sanitize_text_field( $audit['action'] )
		) );
	}
}

function zleadscrm_user_can_view_company_analytics( $user_id ) {
	$roles = zleadscrm_get_selected_roles();

	$user_roles = get_user_by( 'id', $user_id )->roles;

	foreach ( $user_roles as $user_role ) {
		if ( ! in_array( $user_role, $roles ) ) {
			return false;
		}
	}

	if ( zleadscrm_get_users_allowed_company_setting() == 'all' ) {
		return true;
	}

	$allowed_company_users = zleadscrm_get_company_users();

	return in_array( $user_id, $allowed_company_users );
}

function zleadscrm_user_can_access_analytics( $user_id ) {
	$roles = zleadscrm_get_selected_roles();

	$user_roles = get_user_by( 'id', $user_id )->roles;

	foreach ( $user_roles as $user_role ) {
		if ( ! in_array( $user_role, $roles ) ) {
			return false;
		}
	}

	if ( zleadscrm_get_users_allowed_access_analytics_setting() == 'all' ) {
		return true;
	}

	$allowed_access_analytics_users = zleadscrm_get_access_analytics_users();

	return in_array( $user_id, $allowed_access_analytics_users );
}

add_filter( 'wp_new_user_notification_email', 'zleadscrm_new_user_notification_email', 10, 3 );

function zleadscrm_new_user_notification_email( $wp_new_user_notification_email, $user, $blogname ) {
	if ( in_array( 'lead', $user->roles ) ) {
		$send_new_user_notification = get_option( 'zleadscrm_new_user_email_notification', 'off' );
		if ( $send_new_user_notification == 'off' ) {
			$wp_new_user_notification_email['to'] = '';

			return $wp_new_user_notification_email;
		}
	}

	return $wp_new_user_notification_email;
}

function zleadscrm_get_addon_status( $addon ) {
	switch ( $addon ) {
		case 'prospect':
			if ( ! class_exists( 'ZLEADSCRM_PROSPECT_Core' ) ) {
				return 'enable';
			} else {
				$plugin = str_replace( 'plugins' . DIRECTORY_SEPARATOR, '', strstr( ZLEADSCRM_PROSPECT_PLUGIN_DIR, 'plugins' ) ) . 'leads-prospect.php';
				if ( is_plugin_active( $plugin ) ) {
					return 'active';
				} else {
					return 'enable';
				}
			}
		case 'pipeline':
			if ( ! class_exists( 'ZLEADSCRM_PIPELINE_Core' ) ) {
				return 'enable';
			} else {
				$plugin = str_replace( 'plugins' . DIRECTORY_SEPARATOR, '', strstr( ZLEADSCRM_PIPELINE_PLUGIN_DIR, 'plugins' ) ) . 'leads-pipeline.php';
				if ( is_plugin_active( $plugin ) ) {
					return 'active';
				} else {
					return 'enable';
				}
			}
		case 'advanced_analytics':
			if ( ! class_exists( 'ZLEADSCRM_ANALYTICS_Core' ) ) {
				return 'enable';
			} else {
				$plugin = str_replace( 'plugins' . DIRECTORY_SEPARATOR, '', strstr( ZLEADSCRM_ANALYTICS_PLUGIN_DIR, 'plugins' ) ) . 'leads-analytics.php';
				if ( is_plugin_active( $plugin ) ) {
					return 'active';
				} else {
					return 'enable';
				}
			}
		case 'export':
			if ( ! class_exists( 'ZLEADSCRM_EXPORT_Core' ) ) {
				return 'enable';
			} else {
				$plugin = str_replace( 'plugins' . DIRECTORY_SEPARATOR, '', strstr( ZLEADSCRM_EXPORT_PLUGIN_DIR, 'plugins' ) ) . 'leads-export.php';
				if ( is_plugin_active( $plugin ) ) {
					return 'active';
				} else {
					return 'enable';
				}
			}
		case 'import':
			if ( ! class_exists( 'ZLEADSCRM_IMPORT_Core' ) ) {
				return 'enable';
			} else {
				$plugin = str_replace( 'plugins' . DIRECTORY_SEPARATOR, '', strstr( ZLEADSCRM_IMPORT_PLUGIN_DIR, 'plugins' ) ) . 'leads-import.php';
				if ( is_plugin_active( $plugin ) ) {
					return 'active';
				} else {
					return 'enable';
				}
			}
		case 'custom_fields':
			if ( ! class_exists( 'ZLEADSCRM_CUSTOM_FIELDS_Core' ) ) {
				return 'enable';
			} else {
				$plugin = str_replace( 'plugins' . DIRECTORY_SEPARATOR, '', strstr( ZLEADSCRM_CUSTOM_FIELDS_PLUGIN_DIR, 'plugins' ) ) . 'leads-custom-fields.php';
				if ( is_plugin_active( $plugin ) ) {
					return 'active';
				} else {
					return 'enable';
				}
			}
	}
}

function zleadscrm_display_interaction_form() {
	include_once ZLEADSCRM_BASIC_PLUGIN_DIR . 'template/lead/interactions/form.php';
}

function zleadscrm_insert_lead_interaction( $interaction ) {
	global $wpdb;

	$table_name = $wpdb->prefix . 'zleadscrm_leads_interactions';
	$wpdb->insert( $table_name, array(
		'lead_id'   => sanitize_text_field( $interaction['lead_id'] ),
		'author_id' => sanitize_text_field( $interaction['author_id'] ),
		'timestamp' => current_time( 'mysql' ),
		'message'   => sanitize_text_field( $interaction['message'] )
	) );
}