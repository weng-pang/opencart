<?php
class ModelShippingRoyalMail extends Model {
	function getQuote($address) {
		$this->load->language('shipping/royal_mail');

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "zone_to_geo_zone WHERE geo_zone_id = '" . (int)$this->config->get('royal_mail_geo_zone_id') . "' AND country_id = '" . (int)$address['country_id'] . "' AND (zone_id = '" . (int)$address['zone_id'] . "' OR zone_id = '0')");

		if (!$this->config->get('royal_mail_geo_zone_id')) {
			$status = true;
		} elseif ($query->num_rows) {
			$status = true;
		} else {
			$status = false;
		}

		$quote_data = array();

		if ($status) {
			$weight = $this->cart->getWeight();
			$sub_total = $this->cart->getSubTotal();
			
			// Special Delivery > 500
			if ($this->config->get('royal_mail_special_delivery_500_status') && $address['iso_code_2'] == 'GB') {
				$cost = 0;
				$insurance = 0;

				$rates = explode(',', $this->config->get('royal_mail_special_delivery_500_rate'));

				foreach ($rates as $rate) {
					$data = explode(':', $rate);

					if ($data[0] >= $weight) {
						if (isset($data[1])) {
							$cost = $data[1];
						}

						break;
					}
				}

				if ((float)$cost) {
					$title = $this->language->get('text_special_delivery');

					if ($this->config->get('royal_mail_display_weight')) {
						$title .= ' (' . $this->language->get('text_weight') . ' ' . $this->weight->format($weight, $this->config->get('config_weight_class_id')) . ')';
					}

					if ($this->config->get('royal_mail_display_insurance')) {
						$title .= ' (' . $this->language->get('text_insurance') . ' ' . $this->currency->format(500) . ')';
					}

					$quote_data['special_delivery_500'] = array(
						'code'         => 'royal_mail.special_delivery_500',
						'title'        => $title,
						'cost'         => $cost,
						'tax_class_id' => $this->config->get('royal_mail_tax_class_id'),
						'text'         => $this->currency->format($this->tax->calculate($cost, $this->config->get('royal_mail_tax_class_id'), $this->config->get('config_tax')))
					);
				}
			}

			// Special Delivery > 1000
			if ($this->config->get('royal_mail_special_delivery_1000_status') && $address['iso_code_2'] == 'GB') {
				$cost = 0;
				$insurance = 0;

				$rates = explode(',', $this->config->get('royal_mail_special_delivery_1000_rate'));

				foreach ($rates as $rate) {
					$data = explode(':', $rate);

					if ($data[0] >= $weight) {
						if (isset($data[1])) {
							$cost = $data[1];
						}

						break;
					}
				}

				if ((float)$cost) {
					$title = $this->language->get('text_special_delivery');

					if ($this->config->get('royal_mail_display_weight')) {
						$title .= ' (' . $this->language->get('text_weight') . ' ' . $this->weight->format($weight, $this->config->get('config_weight_class_id')) . ')';
					}

					if ($this->config->get('royal_mail_display_insurance')) {
						$title .= ' (' . $this->language->get('text_insurance') . ' ' . $this->currency->format(1000) . ')';
						
					}

					$quote_data['special_delivery_1000'] = array(
						'code'         => 'royal_mail.special_delivery_1000',
						'title'        => $title,
						'cost'         => $cost,
						'tax_class_id' => $this->config->get('royal_mail_tax_class_id'),
						'text'         => $this->currency->format($this->tax->calculate($cost, $this->config->get('royal_mail_tax_class_id'), $this->config->get('config_tax')))
					);
				}
			}

			// Special Delivery > 2500
			if ($this->config->get('royal_mail_special_delivery_2500_status') && $address['iso_code_2'] == 'GB') {
				$cost = 0;
				$insurance = 0;

				$rates = explode(',', $this->config->get('royal_mail_special_delivery_2500_rate'));

				foreach ($rates as $rate) {
					$data = explode(':', $rate);

					if ($data[0] >= $weight) {
						if (isset($data[1])) {
							$cost = $data[1];
						}

						break;
					}
				}

				if ((float)$cost) {
					$title = $this->language->get('text_special_delivery');

					if ($this->config->get('royal_mail_display_weight')) {
						$title .= ' (' . $this->language->get('text_weight') . ' ' . $this->weight->format($weight, $this->config->get('config_weight_class_id')) . ')';
					}

					if ($this->config->get('royal_mail_display_insurance')) {
						$title .= ' (' . $this->language->get('text_insurance') . ' ' . $this->currency->format(2500) . ')';
					}

					$quote_data['special_delivery_2500'] = array(
						'code'         => 'royal_mail.special_delivery_2500',
						'title'        => $title,
						'cost'         => $cost,
						'tax_class_id' => $this->config->get('royal_mail_tax_class_id'),
						'text'         => $this->currency->format($this->tax->calculate($cost, $this->config->get('royal_mail_tax_class_id'), $this->config->get('config_tax')))
					);
				}
			}

			// 1st Class Signed
			if ($this->config->get('royal_mail_1st_class_signed_status') && $address['iso_code_2'] == 'GB') {
				$cost = 0;

				$rates = explode(',', $this->config->get('royal_mail_1st_class_signed_rate'));

				foreach ($rates as $rate) {
					$data = explode(':', $rate);

					if ($data[0] >= $weight) {
						if (isset($data[1])) {
							$cost = $data[1];
						}

						break;
					}
				}

				if ((float)$cost) {
					$title = $this->language->get('text_1st_class_signed');

					if ($this->config->get('royal_mail_display_weight')) {
						$title .= ' (' . $this->language->get('text_weight') . ' ' . $this->weight->format($weight, $this->config->get('config_weight_class_id')) . ')';
					}

					$quote_data['1st_class_signed'] = array(
						'code'         => 'royal_mail.1st_class_signed',
						'title'        => $title,
						'cost'         => $cost,
						'tax_class_id' => $this->config->get('royal_mail_tax_class_id'),
						'text'         => $this->currency->format($this->tax->calculate($cost, $this->config->get('royal_mail_tax_class_id'), $this->config->get('config_tax')))
					);
				}
			}

			// 2nd Class Signed
			if ($this->config->get('royal_mail_2nd_class_signed_status') && $address['iso_code_2'] == 'GB') {
				$cost = 0;

				$rates = explode(',', $this->config->get('royal_mail_2nd_class_signed_rate'));

				foreach ($rates as $rate) {
					$data = explode(':', $rate);

					if ($data[0] >= $weight) {
						if (isset($data[1])) {
							$cost = $data[1];
						}

						break;
					}
				}

				if ((float)$cost) {
					$title = $this->language->get('text_2nd_class_signed');

					if ($this->config->get('royal_mail_display_weight')) {
						$title .= ' (' . $this->language->get('text_weight') . ' ' . $this->weight->format($weight, $this->config->get('config_weight_class_id')) . ')';
					}

					$quote_data['2nd_class_signed'] = array(
						'code'         => 'royal_mail.2nd_class_signed',
						'title'        => $title,
						'cost'         => $cost,
						'tax_class_id' => $this->config->get('royal_mail_tax_class_id'),
						'text'         => $this->currency->format($this->tax->calculate($cost, $this->config->get('royal_mail_tax_class_id'), $this->config->get('config_tax')))
					);
				}
			}

			// 1st Class Standard
			if ($this->config->get('royal_mail_1st_class_standard_status') && $address['iso_code_2'] == 'GB') {
				$cost = 0;

				$rates = explode(',', $this->config->get('royal_mail_1st_class_standard_rate'));

				foreach ($rates as $rate) {
					$data = explode(':', $rate);

					if ($data[0] >= $weight) {
						if (isset($data[1])) {
							$cost = $data[1];
						}

						break;
					}
				}

				if ((float)$cost) {
					$title = $this->language->get('text_1st_class_standard');

					if ($this->config->get('royal_mail_display_weight')) {
						$title .= ' (' . $this->language->get('text_weight') . ' ' . $this->weight->format($weight, $this->config->get('config_weight_class_id')) . ')';
					}

					$quote_data['1st_class_standard'] = array(
						'code'         => 'royal_mail.1st_class_standard',
						'title'        => $title,
						'cost'         => $cost,
						'tax_class_id' => $this->config->get('royal_mail_tax_class_id'),
						'text'         => $this->currency->format($this->tax->calculate($cost, $this->config->get('royal_mail_tax_class_id'), $this->config->get('config_tax')))
					);
				}
			}

			// 2nd Class Standard
			if ($this->config->get('royal_mail_2nd_class_standard_status') && $address['iso_code_2'] == 'GB') {
				$cost = 0;

				$rates = explode(',', $this->config->get('royal_mail_2nd_class_standard_rate'));

				foreach ($rates as $rate) {
					$data = explode(':', $rate);

					if ($data[0] >= $weight) {
						if (isset($data[1])) {
							$cost = $data[1];
						}

						break;
					}
				}

				if ((float)$cost) {
					$title = $this->language->get('text_2nd_class_standard');

					if ($this->config->get('royal_mail_display_weight')) {
						$title .= ' (' . $this->language->get('text_weight') . ' ' . $this->weight->format($weight, $this->config->get('config_weight_class_id')) . ')';
					}

					$quote_data['2nd_class_standard'] = array(
						'code'         => 'royal_mail.2nd_class_standard',
						'title'        => $title,
						'cost'         => $cost,
						'tax_class_id' => $this->config->get('royal_mail_tax_class_id'),
						'text'         => $this->currency->format($this->tax->calculate($cost, $this->config->get('royal_mail_tax_class_id'), $this->config->get('config_tax')))
					);
				}
			}

/*
List of Royal Mail support Coutries

http://www.royalmail.com/sites/default/files/RoyalMail_International_TrackedCoverage_Jan2014.pdf

Afghanistan = AF
Aland Islands = NA
Albania = AL
Algeria = DZ
Andorra = AD
Angola = AO
Anguilla = AI
Antigua / Barbuda = AG
Argentina = AR
Armenia = AM
Aruba = AW
Ascension Island = XA
Australia = AU
Austria = AT
Azerbaijan = AZ
Bahamas = BS
Bahrain = BH
Bangladesh = BD
Barbados = BB
Belarus = BY
Belgium = BE
Belize = BZ
Benin = BJ
Bermuda = BM
Bhutan = BT
Bolivia = BO
Bonaire = BX
Bosnia Hertzegovina = BA
Botswana = BW
Brazil = BR
British Indian Ocean Territory = IO
British Virgin Islands = VG
Brunei = BN
Bulgaria = BG 
Burkina Faso = BF
Burundi = BI
Cambodia = KH
Cameroon = CM
Canada = CA
Canary Islands = XC
Cape Verde = CV
Cayman Islands = KY
Central African Republic = CF
Ceuta = CE
Chad = TD
Chile = CL
China (People's Republic of) = CN
Christmas Island (Indian Ocean) = CX
Christmas Island (Pacific Ocean) = CX
Colombia = CO
Comoros Islands = KM
Congo ( Dem. Rep of) = CD
Congo ( Rep of) = CG
Cook Islands = CK
Costa Rica = CR
Croatia = HR
Cuba = CU
Curacao = CB
Cyprus = CY
Czech Republic = CZ
Denmark = DK
Djibouti = DJ
Dominica = DM
Dominican Republic = DO
Ecuador = EC
Egypt = EG
El Salvador = SV
Equatorial Guinea = GQ
Eritrea = ER
Estonia = EE
Ethiopia = ET
Falkland Islands = FK
Faroe Islands = FO
Fiji = FJ
Finland = FI
France = FR 
French Guiana = GF
French Polynesia = PF
French South Antarctic Territory = TF
Gabon = GA
Gambia = GM
Georgia = GE
Germany = DE 
Ghana = GH
Gibraltar = GI
Greece = GR 
Greenland = GL
Grenada = GD
Guadeloupe = GP
Guatemala = GT
Guinea = GN
Guinea-Bissau = GW
Guyana = GY
Haiti = HT
Honduras = HN
Hong Kong = HK
Hungary = HU 
Iceland = IS
India = IN
Indonesia = ID
Iran (Islamic Republic of) = IR
Iraq = IQ
Ireland (Republic of) = IE
Israel = IL
Italy = IT 
Ivory Coast (Cote D'Ivoire) = CI
Jamaica = JM
Japan = JP
Jordan = JO
Kazakhstan = KZ
Kenya = KE
Kiribati = KI
Kosovo = KV
Kuwait = KW
Kyrgyzstan = KG
Laos (People's Democratic Republic of) = LA
Latvia = LV
Lebanon = LB
Lesotho = LS
Liberia = LR
Libya = LY
Liechtenstein = LI
Lithuania = LT
Luxembourg = LU 
Macau = MO
Macedonia = MK
Madagascar = MG
Mahore (also known as Mayotte) = YT
Malawi = MW
Malaysia = MY
Maldives = MV
Mali = ML
Malta = MT
Martinique = MQ
Mauritania = MR
Mauritius = MU
Melilla = XL 
Mexico = MX
Moldova = MD
Mongolia = MN
Montenegro = CS
Montserrat = MS
Morocco = MA
Mozambique = MZ
Myanmar = MM
Namibia = NA
Nauru Island = NR
Nepal = NP
Netherlands = NL
New Caledonia = NC
New Zealand = NZ
Nicaragua = NI
Niger Republic = NE
Nigeria = NG
Niue Island = NU
North Korea (People's Democratic Republic of ) = KP
Norway = NO
Oman = OM
Pakistan = PK
Palau (known also as Belau) = PW
Panama = PA
Papua New Guinea = PG
Paraguay = PY
Peru = PE
Philippines = PH
Pitcairn Island = PN
Poland = PL 
Portugal = PT 
Puerto Rico = PR
Qatar = QA
Reunion Island = RE
Romania = RO
Russian Federation = RU
Rwanda = RW
San Marino = SM
Sao Tome & Principe = ST
Saudi Arabia = SA
Senegal = SN
Serbia = CS
Seychelles = SC
Sierra Leone = SL
Singapore = SG
Slovak Republic = SK 
Slovenia = SI
Solomon Islands = SB
Somalia = SO
South Africa ( Republic of) = ZA
South Korea (Republic of) = KR
South Sudan = SD
Spain = ES
Sri Lanka = LK
St Eustatius = SX
St Helena = SH
St Kitts & Nevis = KN
St Lucia = LC
St Maarten = SF
St Vincent & The Grenadines = VC
Sudan = SD
Suriname = SR
Swaziland = SZ
Sweden = SE
Switzerland = CH
Syria = SY
Taiwan = TW
Tajikistan = TJ
Tanzania = TZ
Thailand = TH
Timor-Leste = TP
Togo = TG
Tokelau Islands = TK
Tonga = TO
Trinidad & Tobago = TT
Tristan de Cunha = XT
Tunisia = TN
Turkey = TR
Turkish (Republic of Northern Cyprus) = CP
Turkmenistan = TM
Turks & Caicos Islands = TC
Tuvalu = TV
Uganda = UG
Ukraine = UA
United Arab Emirates = AE
Uruguay = UY
USA = US
Uzbekistan = UZ
Vanuatu = VU
Vatican City State = VA
Venezuela = VE
Vietnam = VN
Wallis & Futuna Islands = WF
Western Sahara = EH
Western Samoa = WS
Yemen, Republic of = YE
Zambia = ZM
Zimbabwe = ZW
*/

AF
NA
AL
DZ
AD
AO
AI
AG
AR
AM
AW
XA
AU
AT
AZ
BS
BH
BD
BB
BY
BE
BZ
BJ
BM
BT
BO
BX
BA
BW
BR
IO
VG
BN
BG 
BF
BI
KH
CM
CA
XC
CV
KY
CF
CE
TD
CL
CN
CX
CX
CO
KM
CD
CG
CK
CR
HR
CU
CB
CY
CZ
DK
DJ
DM
DO
EC
EG
SV
GQ
ER
EE
ET
FK
FO
FJ
FI
FR 
GF
PF
TF
GA
GM
GE
DE 
GH
GI
GR 
GL
GD
GP
GT
GN
GW
GY
HT
HN
HK
HU 
IS
IN
ID
IR
IQ
IE
IL
IT 
CI
JM
JP
JO
KZ
KE
KI
KV
KW
KG
LA
LV
LB
LS
LR
LY
LI
LT
LU 
MO
MK
MG
YT
MW
MY
MV
ML
MT
MQ
MR
MU
XL 
MX
MD
MN
CS
MS
MA
MZ
MM
NA
NR
NP
NL
NC
NZ
NI
NE
NG
NU
KP
NO
OM
PK
PW
PA
PG
PY
PE
PH
PN
PL 
PT 
PR
QA
RE
RO
RU
RW
SM
ST
SA
SN
CS
SC
SL
SG
SK 
SI
SB
SO
ZA
KR
SD
ES
LK
SX
SH
KN
LC
SF
VC
SD
SR
SZ
SE
CH
SY
TW
TJ
TZ
TH
TP
TG
TK
TO
TT
XT
TN
TR
CP
TM
TC
TV
UG
UA
AE
UY
US
UZ
VU
VA
VE
VN
WF
EH
WS
YE
ZM
ZW







			$eu = explode(',', 'AL,AD,AM,AT,AZ,BY,BE,BG,XC,HR,CY,CZ,DK,EE,FO,FI,FR,GE,DE,GI,GR,GL,HU,IS,IE,IT,KZ,KV,KG,LV,LI,LT,LU,MK,MT,MD,CS,NL,NO,PL,PT,RO,RU,SM,CS,SK,SI,ES,SE,CH,TJ,TR,TM,UA,UZ,VA');
			
			$non_eu = explode(',', '
			DK,EE,FO,FI,FR,GE,DE,GI,GR,GL,HU,IS,IE,IT,KZ,KG,LV,LI,LT,LU,MK,MT,
			MD,MC,NL,NO,PL,PT,RO,RU,SM,SK,SI,ES,SE,CH,TJ,TR,TM,UA,UZ,VA');


			$zone_1 = explode(',', 'AL,AD,AM,AT,AZ,BY,BE,BA,BG,HR,CY,CZ,DK,EE,FO,FI,FR,GE,DE,GI,GR,GL,HU,IS,IE,IT,KZ,KG,LV,LI,LT,LU,MK,MT,MD,MC,NL,NO,PL,PT,RO,RU,SM,SK,SI,ES,SE,CH,TJ,TR,TM,UA,UZ,VA');
			
			
			
			
			$zone_2 = explode(',', 'AU,IO,CX,CK,FJ,PF,TF,KI,LA,MO,NR,NC,NZ,NU,PW,PG,PN,SG,SB,TK,TO,TV,WS');

			// International Standard
			if ($this->config->get('royal_mail_international_standard_status') && $address['iso_code_2'] != 'GB') {
				$cost = 0;
				
				$rates = array();
				
				// EU
				if (in_array($address['iso_code_2'], $eu)) {
					$rates = explode(',', $this->config->get('royal_mail_international_standard_eu_rate'));
				}
				
				// World Zones 1
				$countries = explode(',', '');

				if (in_array($address['iso_code_2'], $zone_1)) {
					$rates = explode(',', $this->config->get('royal_mail_international_standard_zone_1_rate'));
				}
				
				// World Zones 2
				$countries = explode(',', '');
				
				if (in_array($address['iso_code_2'], $zone_2)) {
					$rates = explode(',', $this->config->get('royal_mail_international_standard_zone_2_rate'));
				}

				foreach ($rates as $rate) {
					$data = explode(':', $rate);

					if ($data[0] >= $weight) {
						if (isset($data[1])) {
							$cost = $data[1];
						}

						break;
					}
				}

				if ((float)$cost) {
					$title = $this->language->get('text_international_standard');

					if ($this->config->get('royal_mail_display_weight')) {
						$title .= ' (' . $this->language->get('text_weight') . ' ' . $this->weight->format($weight, $this->config->get('config_weight_class_id')) . ')';
					}

					$quote_data['international_standard'] = array(
						'code'         => 'royal_mail.international_standard',
						'title'        => $title,
						'cost'         => $cost,
						'tax_class_id' => $this->config->get('royal_mail_tax_class_id'),
						'text'         => $this->currency->format($this->tax->calculate($cost, $this->config->get('royal_mail_tax_class_id'), $this->config->get('config_tax')))
					);
				}
			}
			
			// International Tracked & Signed
			if ($this->config->get('royal_mail_international_tracked_signed_status') && $address['iso_code_2'] != 'GB') {
				$cost = 0;
				
				$rates = array();
				
				// EU
				if (in_array($address['iso_code_2'], $eu)) {
					$rates = explode(',', $this->config->get('royal_mail_international_tracked_signed_eu_rate'));
				}
				
				// World Zones 1
				if (in_array($address['iso_code_2'], $zone_1)) {
					$rates = explode(',', $this->config->get('royal_mail_international_tracked_signed_zone_1_rate'));
				}
				
				// World Zones 2
				if (in_array($address['iso_code_2'], $zone_2)) { {
					$rates = explode(',', $this->config->get('royal_mail_international_tracked_signed_zone_2_rate'));
				}

				foreach ($rates as $rate) {
					$data = explode(':', $rate);

					if ($data[0] >= $weight) {
						if (isset($data[1])) {
							$cost = $data[1];
						}

						break;
					}
				}

				if ((float)$cost) {
					$title = $this->language->get('text_international_tracked_signed');

					if ($this->config->get('royal_mail_display_weight')) {
						$title .= ' (' . $this->language->get('text_weight') . ' ' . $this->weight->format($weight, $this->config->get('config_weight_class_id')) . ')';
					}

					$quote_data['international_tracked_signed'] = array(
						'code'         => 'royal_mail.international_tracked_signed',
						'title'        => $title,
						'cost'         => $cost,
						'tax_class_id' => $this->config->get('royal_mail_tax_class_id'),
						'text'         => $this->currency->format($this->tax->calculate($cost, $this->config->get('royal_mail_tax_class_id'), $this->config->get('config_tax')))
					);
				}
			}

			// International Tracked
			if ($this->config->get('royal_mail_international_tracked_status') && $address['iso_code_2'] != 'GB') {
				$cost = 0;

				$rates = array();

				// EU
				if (in_array($address['iso_code_2'], $eu)) {
					$rates = explode(',', $this->config->get('royal_mail_international_tracked_eu_rate'));
				}
				
				// Non EU
				if (!in_array($address['iso_code_2'], $eu) && !in_array($address['iso_code_2'], $zone_1) && !in_array($address['iso_code_2'], $zone_2)) {
					$rates = explode(',', $this->config->get('royal_mail_international_tracked_non_eu_rate'));
				}
				
				// World Zones 1				
				if (in_array($address['iso_code_2'], $zone_1)) {
					$rates = explode(',', $this->config->get('royal_mail_international_tracked_zone_1_rate'));
				}
				
				// World Zones 2
				if (in_array($address['iso_code_2'], $zone_2)) { {
					$rates = explode(',', $this->config->get('royal_mail_international_tracked_zone_2_rate'));
				}

				foreach ($rates as $rate) {
					$data = explode(':', $rate);

					if ($data[0] >= $weight) {
						if (isset($data[1])) {
							$cost = $data[1];
						}

						break;
					}
				}

				if ((float)$cost) {
					$title = $this->language->get('text_international_tracked');

					if ($this->config->get('royal_mail_display_weight')) {
						$title .= ' (' . $this->language->get('text_weight') . ' ' . $this->weight->format($weight, $this->config->get('config_weight_class_id')) . ')';
					}

					$quote_data['international_tracked'] = array(
						'code'         => 'royal_mail.international_tracked',
						'title'        => $title,
						'cost'         => $cost,
						'tax_class_id' => $this->config->get('royal_mail_tax_class_id'),
						'text'         => $this->currency->format($this->tax->calculate($cost, $this->config->get('royal_mail_tax_class_id'), $this->config->get('config_tax')))
					);
				}
			}
			
			// International Signed
			if ($this->config->get('royal_mail_international_signed_status') && $address['iso_code_2'] != 'GB') {
				$cost = 0;

				$rates = array();
				
				// EU
				if (in_array($address['iso_code_2'], $eu)) {
					$rates = explode(',', $this->config->get('royal_mail_international_signed_eu_rate'));
				}
				
				// World Zones 1	
				if (in_array($address['iso_code_2'], $zone_1)) {
					$rates = explode(',', $this->config->get('royal_mail_international_signed_zones_1_rate'));
				}
				
				// World Zones 2	
				if (in_array($address['iso_code_2'], $zone_2)) {
					$rates = explode(',', $this->config->get('royal_mail_international_signed_zones_2_rate'));
				}
				
				foreach ($rates as $rate) {
					$data = explode(':', $rate);

					if ($data[0] >= $weight) {
						if (isset($data[1])) {
							$cost = $data[1];
						}

						break;
					}
				}

				if ((float)$cost) {
					$title = $this->language->get('text_international_signed');

					if ($this->config->get('royal_mail_display_weight')) {
						$title .= ' (' . $this->language->get('text_weight') . ' ' . $this->weight->format($weight, $this->config->get('config_weight_class_id')) . ')';
					}

					$quote_data['international_signed'] = array(
						'code'         => 'royal_mail.international_signed',
						'title'        => $title,
						'cost'         => $cost,
						'tax_class_id' => $this->config->get('royal_mail_tax_class_id'),
						'text'         => $this->currency->format($this->tax->calculate($cost, $this->config->get('royal_mail_tax_class_id'), $this->config->get('config_tax')))
					);
				}
			}

			// Economy
			if ($this->config->get('royal_mail_international_economy_status') && $address['iso_code_2'] != 'GB') {
				$cost = 0;

				$rates = explode(',', $this->config->get('royal_mail_international_economy_rate'));

				foreach ($rates as $rate) {
					$data = explode(':', $rate);

					if ($data[0] >= $weight) {
						if (isset($data[1])) {
							$cost = $data[1];
						}

						break;
					}
				}

				if ((float)$cost) {
					$title = $this->language->get('text_international_economy');

					if ($this->config->get('royal_mail_display_weight')) {
						$title .= ' (' . $this->language->get('text_weight') . ' ' . $this->weight->format($weight, $this->config->get('config_weight_class_id')) . ')';
					}

					$quote_data['surface'] = array(
						'code'         => 'royal_mail.international_economy',
						'title'        => $title,
						'cost'         => $cost,
						'tax_class_id' => $this->config->get('royal_mail_tax_class_id'),
						'text'         => $this->currency->format($this->tax->calculate($cost, $this->config->get('royal_mail_tax_class_id'), $this->config->get('config_tax')))
					);
				}
			}
		}

		$method_data = array();

		if ($quote_data) {
			$method_data = array(
				'code'       => 'royal_mail',
				'title'      => $this->language->get('text_title'),
				'quote'      => $quote_data,
				'sort_order' => $this->config->get('royal_mail_sort_order'),
				'error'      => false
			);
		}

		return $method_data;
	}
}