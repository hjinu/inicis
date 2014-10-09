<?php

// ini_set('error_reporting', E_ALL | E_NOTICE);
// ini_set('display_errors', 'Off');

/****************************************************************************************
 **** 지불수단별로 PGID를 다르게 표시한다 (2003.12.19 대리 이종완) ****
 ****************************************************************************************
 *** 하단의 PGID 부분은 지불수단별로 TID를 별도로 표시하도록 하며,  ***
 *** 임의로 수정하는 경우 지불 실패가 발생 될수 있으므로 절대로 수정  ***
 *** 하지 않도록 하시기 바랍니다.     ********************************************* 
 *** 임의로 수정하여 발생된 문제에 대해서는 (주)이니시스에 책임이    ***** 
 *** 없으니 주의 하시기 바랍니다.      ********************************************
 ***************************************************************************************/
extract($_POST);
extract($_GET);
switch($paymethod){

	case(Card): // 신용카드 
		$pgid = "CARD";
		break;
	case(Account): // 은행 계좌 이체
		$pgid = "ACCT";
		break;
	case(DirectBank): // 실시간 계좌 이체
		$pgid = "DBNK";
		break;
	case(OCBPoint): // OCB
		$pgid = "OCBP";
		break;
	case(VCard): // ISP 결제
		$pgid = "ISP_";
		break;
	case(HPP): // 휴대폰 결제
		$pgid = "HPP_";
		break;
	case(ArsBill): // 700 전화결제
		$pgid = "ARSB";
		break;
	case(PhoneBill): // PhoneBill 결제(받는 전화)
		$pgid = "PHNB";
		break;
	case(Ars1588Bill): // 1588 전화결제
		$pgid = "1588";
		break;
	case(VBank):  // 가상계좌 이체
		$pgid = "VBNK";
		break;
	case(Culture):  // 문화상품권 결제
		$pgid = "CULT";
		break;
	case(CMS): // CMS 결제
		$pgid = "CMS_";
		break;
	case(AUTH): // 신용카드 유효성 검사
		$pgid = "AUTH";
		break;	
	case(INIcard): // 네티머니 결제
		$pgid = "INIC";
		break;
	case(MDX):  // 몬덱스카드
		$pgid = "MDX_";
		break;
	default:        // 상기 지불수단 외 추가되는 지불수단의 경우 기본으로 paymethod가 4자리로 넘어온다.
		$pgid = $paymethod;
}

/*************************************************************************************
 *************************************************************************************
   ********************        상기부분 절대 수정 불가      ************************
 *************************************************************************************
 *************************************************************************************/
 
/*----------------------------------------------------------* 
 *무이자 할부거래인 경우 할부개월수 뒤에 무이자할부임을 표시*
 *----------------------------------------------------------*/

if($quotainterest == "1")
{
	$interest = "(무이자할부)";
}
 
/*----------------------------------------------------------*/

 
class INIpay41
{
	var $fd;
	var $m_inipayHome; 		//이니페이 홈디렉터리
	var $m_test; 			// "true"면 17번으로 보낸다
	var $m_debug; 			// "true"면 상세한 로그를 남긴다
	var $m_type; 			// 거래 유형
	var $m_pgId; 			// PGID
	var $m_keyPw; 			// keypass.enc의 pass phrase
	var $m_subPgIp; 		// 3번째 예비 PG IP Addr
	var $m_mid; 			// 상점 아이디
	var $m_language; 		// 사용언어
	var $m_oldTid; 			// 부분취소(재승인) 사용시 원거래아이디
	var $m_tid; 			// 거래아이디
	var $m_goodName; 		// 상품명
	var $m_currency; 		// 화폐단위 (WON, USD)
	var $m_price; 			// 금액
	var $m_confirm_price;		// 재승인 요청 금액
	var $m_buyerName; 		// 구매자 성명
	var $m_buyerTel; 		// 구매자 전화번호 (SMS 땜에 반드시 이동전화...)
	var $m_buyerEmail; 		// 구매자 이메일
	var $m_recvName; 		// 수취인 성명
	var $m_recvTel; 		// 수취인 연락처
	var $m_recvAddr; 		// 수취인 주소
	var $m_recvPostNum; 		// 수취인 우편번호
	var $m_recvMsg; 		// 수취인에게 전달할 메시지
	var $m_companyNumber; 		// 사업자 등록번호(10자리 숫자)
	var $m_cardCode; 		// 카드사 코드
	var $m_cardIssuerCode; 		// 카드 발급사(은행) 코드
	var $m_payMethod; 		// 지불방법
	var $m_merchantReserved1; 	// 예비필드 (지불) 
	var $m_merchantReserved2; 	// 예비필드 (지불) 
	var $m_merchantReserved3; 	// 예비필드 (지불) 
	var $m_uip; 			// 지불인 PC IP Addr
	var $m_url; 			// 지불 상점 URL
	var $m_billingPeriod; 		// Billing 기간 (2002/07 현재 사용안함)
	var $m_payOption; 		
	var $m_encrypted; 		// 암호문 (대칭키로 암호화된 PLAIN TEXT)
	var $m_sessionKey; 		// 암호문 (공개키로 암호화된 대칭키)
	var $m_uid; 			// INIpay User ID (2002/07 현재 사용안함)
	var $m_quotaInterest; 		// 무이자할부 FLAG
	var $m_cardNumber;  		// 신용카드 번호
	var $m_price1; 			// OK Cashbag, Netimoney 에서 사용하는 추가 금액정보
	var $m_price2; 			// OK Cashbag, Netimoney 에서 사용하는 추가 금액정보
	var $m_cardQuota; 		// 할부기간
	var $m_bankCode; 		// 은행코드
	var $m_ocbNumber; 		// OK Cashbag 카드 번호
	var $m_ocbPasswd; 		// OK Cashbag 카드 비밀번호
	var $m_authentification; 	// 본인인증 FLAG
	var $m_authField1; 		// 본인인증에 필요한 주민번호 뒤 7자리
	var $m_authField2; 		// 본인인증에 필요한 카드 비밀번호 앞 2자리
	var $m_authField3; 		// 본인인증에 필요한 예비필드
	var $m_passwd; 			// (범용) 비밀번호
	var $m_cardExpy; 		// 신용카드 유효기간-년 (YY)
	var $m_cardExpm; 		// 신용카드 유효기간-월 (MM)
	var $m_cardExpire; 		// 신용카드 유효기간 (YYMM)
	var $m_ocbCardType; 		// OK Cashbag 카드 유형 (자사카드...)
	var $m_merchantReserved; 	// 예비필드 (비지불)
	var $m_cancelMsg; 		// 취소 사유
	var $m_resultCode; 		// 결과 코드 (2 digit)
	var $m_resultMsg; 		// 결과 내용
	var $m_authCode; 		// 신용카드 승인번호
	var $m_ocbResultPoint; 		// OK Cashbag Point 조회시 가용포인트
	var $m_authCertain; 		// PG에서 본인인증을 수행하였는지를 나타내는 FLAG
	var $m_ocbSaveAuthCode; 	// OK Cashbag 적립 승인번호
	var $m_ocbUseAuthCode; 		// OK Cashbag 사용 승인번호
	var $m_ocbAuthDate; 		// OK Cashbag 승인 날짜
	var $m_pgAuthDate; 		// PG 승인 날짜
	var $m_pgAuthTime; 		// PG 승인 시각
	var $m_pgCancelDate; 		// PG 취소 날짜
	var $m_pgCancelTime; 		// PG 취소 시각
	var $m_requestMsg; 		// 보낼 메시지
	var $m_responseMsg; 		// 받은 메시지
	var $m_resulterrcode; 		// 결과메세지 에러코드
	var $m_resultprice; 		// 결제 완료 금액

/* == 틴캐시 추가 필드 (2005.02.01 대리 이종완) == */
	var $m_remain_price;		// 틴캐시 잔액

/* == CMS계좌이체 필드 추가 (2004. 11. 15 대리 이종완) == */
	var $m_bankAccount; 		// 은행 계좌번호
	var $m_regNumber; 		// 주민등록번호 (실시간 빌링용 주민등록 번호 13자리)
	var $m_CMSBankCode;		// 결제완료후 리턴 은행코드
	var $m_price_org;		// 출금총금액
	var $m_cmsday;			// 출금예정일
	var $m_cmsdatefrom;		// 출금시작월
	var $m_cmsdatero;		// 출금종료월
	var $m_cmstype;			// 1-CMS 자동(예약)이체, 2-CMS계좌등록

/* == 부분취소(재승인) 추가 필드 (2004.11.05 대리 이종완) == */
	var $m_tid_org;		// 원거래 TID
	var $m_remains = "";		// 최종결제 금액
	var $m_flg_partcancel = "";	// 부분취소, 재승인 구분값
	var $m_cnt_partcancel = ""; 	// 부분취소(재승인) 요청횟수	
	
/* == 필드추가 (2004.06.23 대리 이종완) == */
	var $m_moid; 		// 상품주문번호
	var $m_codegw; 		// 전화결제 사업자 코드
	var $m_ParentEmail; 	// 보호자 이메일 주소
	var $m_ocbcardnumber; 	// OK CASH BAG 결제 , 적립인 경우 OK CASH BAG 카드 번호
	var $m_cultureid;	// 컬쳐 랜드 ID
	var $m_directbankacc;	// 은행 계좌이체 결제인 경우 은행 계좌 번호
	var $m_directbankcode;	// 은행 계좌이체 결제인 경우 은행 코드 번호
	var $m_billKey;		// 실시간 빌링 빌키
	var $m_cardPass;	// 실시간 빌링용 신용카드 비밀번호 앞 2자리
	var $m_billtype;	// 빌링타입 (신용카드 - card, 휴대폰 - hpp)
	
	
/* ==  가상계좌를 위해 추가 (2003.07.07 대리 이종완)  == */
	var $m_perno; 		// 가상계좌 지불 예약자 주민번호
	var $m_oid; 		// 주문번호(상점에서 전달되는 값)
	var $m_vacct; 		// 가상계좌 번호
	var $m_vcdbank; 	// 채번에 사용된 은행코드
	var $m_dtinput; 	// 입금 예정일
	var $m_nminput; 	// 송금자 명
	var $m_nmvacct; 	// 예금주 명
	var $m_rvacct;		// 환불계좌 번호
	var $m_rvcdbank;	// 환불계좌 은행코드
	var $m_rnminput;	// 환불계좌 예금주명

/* == 현금 영수증 발행 필드 추가 (2003.12.08 대리 이종완) == */
	var $m_cr_price;	// 총 현금결제 금액
	var $m_sup_price;	// 공급가
	var $m_tax;		// 부가세
	var $m_srvc_price;	// 봉사료
	var $m_usepot;		// 영수증 사용용도
	var $m_ocbprice;	// OCB 적립요청금액

/* ==  가상계좌를 위해 추가 (2006.10.18 김용운)  == */
	var $m_tminput; 	// 입금 예정 시간

/* ==  웹페이지 위변조 방지 (2006.12.27 이승국)  == */
	var $m_enc_arr = array();
	var $m_enctype;
	var $m_checkopt;
	var $m_rn;
	var $m_ini_rn;
	var $m_ini_encfield;
	var $m_ini_certid;
    
/* ==  결제요청페이지 데이타 암호화(2007.01.10 이승국)  == */
	var $m_enc_src;
    	
/* ==  통방판 KVP플러그인 처리(2007.01.25 이승국) == */
	var $m_kvp_card_prefix;
	var $m_kvp_noint;
	var $m_kvp_quota;

	var $m_pgn;
/* ==  오픈마켓 현금영수증(2007.06.28 이승국) == */
	var $m_OMFlag;
	var $m_SubCrCnt;
	var $m_om = array();

	function SetField( $key, $val ) //Default Entity
	{
		$this->m_REQUEST[$key] = $val;
	}

	function GetResult( $name ) //Default Entity
	{
		$result = $this->m_RESULT[$name];
		if( $result == "" )
			$result = $this->m_Data->m_RESULT[$name];
		return $result;
	}

	function startAction()
	{
		switch($this->m_type)
		{
			case("securepay") :
				$this->m_requestMsg = 
					"inipayhome=" . $this->m_inipayHome . "\x0B" .
					"rn=" . $this->m_rn . "\x0B" .
					"encfield=" . $this->m_ini_encfield . "\x0B" .
					"pgid=" . $this->m_pgId . "\x0B" .
					"spgip=" . $this->m_subPgIp . "\x0B" .
					"admin=" . $this->m_keyPw . "\x0B" .
					"debug=" . $this->m_debug . "\x0B" .
	        "oid=" . $this->m_oid . "\x0B" .
					"test=" . $this->m_test . "\x0B" .
					"mid=" . $this->m_mid . "\x0B" .
					"uid=" . $this->m_uid . "\x0B" .
					"url=" . $this->m_url . "\x0B" .
					"uip=" . $this->m_uip . "\x0B" .
					"paymethod=" . $this->m_payMethod . "\x0B" .
					"goodname=" . $this->m_goodName . "\x0B" .
					"currency=" . $this->m_currency . "\x0B" .
					"price=" . $this->m_price . "\x0B" .
					"buyername=" . $this->m_buyerName . "\x0B" .
					"buyertel=" . $this->m_buyerTel . "\x0B" .
					"buyeremail=" . $this->m_buyerEmail . "\x0B" .
					"parentemail=" . $this->m_ParentEmail . "\x0B" .
					"recvname=" . $this->m_recvName . "\x0B" .
					"recvtel=" . $this->m_recvTel . "\x0B" .
					"recvaddr=" . $this->m_recvAddr . "\x0B" .
					"recvpostnum=" . $this->m_recvPostNum . "\x0B" .
					"recvmsg=" . $this->m_recvMsg . "\x0B" .
					"sessionkey=" . $this->m_sessionKey . "\x0B" .
					"encrypted=" . $this->m_encrypted . "\x0B" .
					"pgn=" . $this->m_pgn . "\x0B" .
					"enctype=" . $this->m_enctype . "\x0B" .
					"merchantreserved1=" . $this->m_merchantReserved1 . "\x0B" .
					"merchantreserved2=" . $this->m_merchantReserved2 . "\x0B" .
					"merchantreserved3=" . $this->m_merchantReserved3;
				$exec_str = $this->m_inipayHome . "/phpexec/INIsecurepay.phpexec \"" . $this->m_requestMsg . "\"";
				$this->m_responseMsg = exec($exec_str);
				if(strlen($this->m_responseMsg) <= 1)
					$this->m_responseMsg = "ResultCode=01&ResultMsg=[9199]INVOKE ERR : " . $this->m_inipayHome . "/phpexec/INIsecurepay.phpexec";
				break;
		
			case("cancel") :
				$this->m_requestMsg = 
					"inipayhome=" . $this->m_inipayHome . "\x0B" .
					"pgid=" . $this->m_pgId . "\x0B" .
					"spgip=" . $this->m_subPgIp . "\x0B" .
					"admin=" . $this->m_keyPw . "\x0B" .
					"debug=" . $this->m_debug . "\x0B" .
					"test=" . $this->m_test . "\x0B" .
					"mid=" . $this->m_mid . "\x0B" .
					"tid=" . $this->m_tid . "\x0B" .
					"msg=" . $this->m_cancelMsg . "\x0B" .
					"uip=" . $this->m_uip . "\x0B" .
					"merchantreserved=" . $this->m_merchantReserved;
				$this->m_responseMsg = exec($this->m_inipayHome . "/phpexec/INIcancel.phpexec \"" . $this->m_requestMsg . "\"");
				if(strlen($this->m_responseMsg) <= 1)
					$this->m_responseMsg = "ResultCode=01&ResultMsg=[9199]INVOKE ERR : " . $this->m_inipayHome . "/phpexec/INIcancel.phpexec";
				break;

			case("confirm") :
				$this->m_requestMsg = 
					"inipayhome=" . $this->m_inipayHome . "\x0B" .
					"test=" . $this->m_test . "\x0B" .
					"pgid=" . $this->m_pgId . "\x0B" .
					"spgip=" . $this->m_subPgIp . "\x0B" .
					"admin=" . $this->m_keyPw . "\x0B" .
					"mid=" . $this->m_mid . "\x0B" .
					"tid=" . $this->m_tid . "\x0B" .
					"debug=" . $this->m_debug . "\x0B" .
					"merchantreserved=" . $this->m_merchantReserved;
				$this->m_responseMsg = exec($this->m_inipayHome . "/phpexec/INIconfirm.phpexec \"" . $this->m_requestMsg . "\"");
				if(strlen($this->m_responseMsg) <= 1)
					$this->m_responseMsg = "ResultCode=01&ResultMsg=[9199]INVOKE ERR : " . $this->m_inipayHome . "/phpexec/INIconfirm.phpexec";
				break;
		
			case("capture") :
				$this->m_requestMsg = 
					"inipayhome=" . $this->m_inipayHome . "\x0B" .
					"test=" . $this->m_test . "\x0B" .
					"pgid=" . $this->m_pgId . "\x0B" .
					"spgip=" . $this->m_subPgIp . "\x0B" .
					"admin=" . $this->m_keyPw . "\x0B" .
					"mid=" . $this->m_mid . "\x0B" .
					"tid=" . $this->m_tid . "\x0B" .
					"debug=" . $this->m_debug . "\x0B" .
					"merchantreserved=" . $this->m_merchantReserved;
				$this->m_responseMsg = exec($this->m_inipayHome . "/phpexec/INIcapture.phpexec \"" . $this->m_requestMsg . "\"");
				if(strlen($this->m_responseMsg) <= 1)
					$this->m_responseMsg = "ResultCode=01&ResultMsg=[9199]INVOKE ERR : " . $this->m_inipayHome . "/phpexec/INIcapture.phpexec";
				break;
		
			case("formpay") :
				$this->m_requestMsg =
					"inipayhome=" . $this->m_inipayHome . "\x0B" .
					"pgid=" . $this->m_pgId . "\x0B" .
					"spgip=" . $this->m_subPgIp . "\x0B" .
					"admin=" . $this->m_keyPw . "\x0B" .
					"debug=" . $this->m_debug . "\x0B" .
					"test=" . $this->m_test . "\x0B" .
					"mid=" . $this->m_mid . "\x0B" .
					"uid=" . $this->m_uid . "\x0B" .
					"url=" . $this->m_url . "\x0B" .
					"uip=" . $this->m_uip . "\x0B" .
					"paymethod=" . $this->m_payMethod . "\x0B" .
					"goodname=" . $this->m_goodName . "\x0B" .
					"currency=" . $this->m_currency . "\x0B" .
					"price=" . $this->m_price . "\x0B" .
					"buyername=" . $this->m_buyerName . "\x0B" .
					"buyertel=" . $this->m_buyerTel . "\x0B" .
					"buyeremail=" . $this->m_buyerEmail . "\x0B" .
					"recvname=" . $this->m_recvName . "\x0B" .
					"recvtel=" . $this->m_recvTel . "\x0B" .
					"recvaddr=" . $this->m_recvAddr . "\x0B" .
					"recvpostnum=" . $this->m_recvPostNum . "\x0B" .
					"recvmsg=" . $this->m_recvMsg . "\x0B" .
					"cardnumber=" . $this->m_cardNumber . "\x0B" .
					"cardquota=" . $this->m_cardQuota . "\x0B" .
					"cardexpy=" . $this->m_cardExpy . "\x0B" .
					"cardexpm=" . $this->m_cardExpm . "\x0B" .
					"quotainterest=" . $this->m_quotaInterest . "\x0B" .
					"authentification=" . $this->m_authentification . "\x0B" .
					"authfield1=" . $this->m_authfield1 . "\x0B" .
					"authfield2=" . $this->m_authfield2 . "\x0B" .
					"price1=" . $this->m_price1 . "\x0B" .
					"price2=" . $this->m_price2 . "\x0B" .
					"bankcode=" . $this->m_bankCode . "\x0B" .
					"bankaccount=" . $this->m_bankAccount . "\x0B" .
					"regnumber=" . $this->m_regNumber . "\x0B" .
					"price_org=" . $this->m_price_org . "\x0B" .
					"cmsday=" . $this->m_cmsday .  "\x0B" .
					"cmsdatefrom=" . $this->m_cmsdatefrom . "\x0B" .
					"cmsdateto=" . $this->m_cmsdateto . "\x0B" .
					"cmstype=" . $this->m_cmstype . "\x0B" .
					"ocbnumber=" . $this->m_ocbNumber . "\x0B" .
					"ocbpasswd=" . $this->m_ocbPasswd . "\x0B" .
					"passwd=" . $this->m_passwd . "\x0B" .
					"perno=" . $this->m_perno . "\x0B" .
	                "oid=" . $this->m_oid . "\x0B" .
	                "vacct=" . $this->m_vacct . "\x0B" .
	                "vcdbank=" . $this->m_vcdbank . "\x0B" .
	                "dtinput=" . $this->m_dtinput . "\x0B" .
	                "nminput=" . $this->m_nminput . "\x0B" .
					"companynumber=" . $this->m_companyNumber . "\x0B" .
					"merchantreserved1=" . $this->m_merchantReserved1 . "\x0B" .
					"merchantreserved2=" . $this->m_merchantReserved2 . "\x0B" .
					"merchantreserved3=" . $this->m_merchantReserved3;
																			
				$this->m_responseMsg = exec($this->m_inipayHome . "/phpexec/INIformpay.phpexec \"" . $this->m_requestMsg . "\"");
				if(strlen($this->m_responseMsg) <= 1)
					$this->m_responseMsg = "ResultCode=01&ResultMsg=[9199]INVOKE ERR : " . $this->m_inipayHome . "/phpexec/INIformpay.phpexec";
				
				
				break;
		
			case("repay") :
				$this->m_requestMsg =
					"inipayhome=" . $this->m_inipayHome . "\x0B" .
					"pgid=" . $this->m_pgId . "\x0B" .
					"spgip=" . $this->m_subPgIp . "\x0B" .
					"admin=" . $this->m_keyPw . "\x0B" .
					"debug=" . $this->m_debug . "\x0B" .
					"test=" . $this->m_test . "\x0B" .
					"mid=" . $this->m_mid . "\x0B" .
					"oldtid=" . $this->m_oldTid . "\x0B" .
					"url=" . $this->m_url . "\x0B" .
					"uip=" . $this->m_uip . "\x0B" .
					"goodname=" . $this->m_goodName . "\x0B" .
					"currency=" . $this->m_currency . "\x0B" .
					"price=" . $this->m_price . "\x0B" .
					"confirm_price=" . $this->m_confirm_price . "\x0B" .
					"buyername=" . $this->m_buyerName . "\x0B" .
					"buyertel=" . $this->m_buyerTel . "\x0B" .
					"buyeremail=" . $this->m_buyerEmail . "\x0B" .
					"cardquota=" . $this->m_cardQuota . "\x0B" .
					"quotainterest=" . $this->m_quotaInterest . "\x0B" .
					"merchantreserved1=" . $this->m_merchantReserved1 . "\x0B" .
					"merchantreserved2=" . $this->m_merchantReserved2 . "\x0B" .
					"merchantreserved3=" . $this->m_merchantReserved3;
				$this->m_responseMsg = exec($this->m_inipayHome . "/phpexec/INIrepay.phpexec \"" . $this->m_requestMsg . "\"");
				if(strlen($this->m_responseMsg) <= 1)
					$this->m_responseMsg = "ResultCode=01&ResultMsg=[9199]INVOKE ERR : " . $this->m_inipayHome . "/phpexec/INIrepay.phpexec";
				break;

			case("ocbquery") :
				$this->m_requestMsg =
					"inipayhome=" . $this->m_inipayHome . "\x0B" .
					"mid=" . $this->m_mid . "\x0B" .
					"ocbnumber=" . $this->m_ocbNumber;
				$this->m_responseMsg = exec($this->m_inipayHome . "/phpexec/INIocbquery.phpexec \"" . $this->m_requestMsg . "\"");
				if(strlen($this->m_responseMsg) <= 1)
					$this->m_responseMsg = "ResultCode=01&ResultMsg=[9199]INVOKE ERR : " . $this->m_inipayHome . "/phpexec/INIocbquery.phpexec";
				break;
				
			case("auth_bill") :
				$this->m_requestMsg =
					"inipayhome=" . $this->m_inipayHome . "\x0B" .
					"paymethod=" . $this->m_payMethod . "\x0B" .
					"pgid=" . $this->m_pgId . "\x0B" .
					"spgip=" . $this->m_subPgIp . "\x0B" .
					"admin=" . $this->m_keyPw . "\x0B" .
					"debug=" . $this->m_debug . "\x0B" .
					"billtype=" . $this->m_billtype . "\x0B" .
					"mid=" . $this->m_mid . "\x0B" .
					"test=" . $this->m_test . "\x0B" .
					"uip=" . $this->m_uip . "\x0B" .
					"goodname=" . $this->m_goodName . "\x0B" .
					"uid=" . $this->m_uid . "\x0B" .
					"url=" . $this->m_url . "\x0B" .
					"buyername=" . $this->m_buyerName . "\x0B" .
					"encrypted=" . $this->m_encrypted . "\x0B" .
					"sessionkey=" . $this->m_sessionKey . "\x0B" .
					"merchantReserved3=" . $this->m_merchantReserved3;

				$this->m_responseMsg = exec($this->m_inipayHome . "/phpexec/INIauth_bill.phpexec \"" . $this->m_requestMsg . "\"");
				if(strlen($this->m_responseMsg) <= 1)
					$this->m_responseMsg = "ResultCode=01&ResultMsg=[9199]INVOKE ERR : " . $this->m_inipayHome . "/phpexec/INIauth_bill.phpexec";
				
				break;
				
			case("auth") :
				$this->m_requestMsg =
					"inipayhome=" . $this->m_inipayHome . "\x0B" .
					"paymethod=" . $this->m_payMethod . "\x0B" .
					"pgid=" . $this->m_pgId . "\x0B" .
					"spgip=" . $this->m_subPgIp . "\x0B" .
					"admin=" . $this->m_keyPw . "\x0B" .
					"debug=" . $this->m_debug . "\x0B" .
					"billtype=" . $this->m_billtype . "\x0B" .
					"mid=" . $this->m_mid . "\x0B" .
					"test=" . $this->m_test . "\x0B" .
					"uip=" . $this->m_uip . "\x0B" .
					"uid=" . $this->m_uid . "\x0B" .
					"url=" . $this->m_url . "\x0B" .
					"buyername=" . $this->m_buyerName . "\x0B" .
					"encrypted=" . $this->m_encrypted . "\x0B" .
					"sessionkey=" . $this->m_sessionKey . "\x0B" .
					"merchantReserved3=" . $this->m_merchantReserved3;

				$this->m_responseMsg = exec($this->m_inipayHome . "/phpexec/INIauth.phpexec \"" . $this->m_requestMsg . "\"");
				if(strlen($this->m_responseMsg) <= 1)
					$this->m_responseMsg = "ResultCode=01&ResultMsg=[9199]INVOKE ERR : " . $this->m_inipayHome . "/phpexec/INIauth.phpexec";
				
				break;

			case("formauth") :
				$this->m_requestMsg =
					"inipayhome=" . $this->m_inipayHome . "\x0B" .
					"paymethod=" . $this->m_payMethod . "\x0B" .
					"pgid=" . $this->m_pgId . "\x0B" .
					"spgip=" . $this->m_subPgIp . "\x0B" .
					"admin=" . $this->m_keyPw . "\x0B" .
					"debug=" . $this->m_debug . "\x0B" .
					"billtype=" . $this->m_billtype . "\x0B" .
					"mid=" . $this->m_mid . "\x0B" .
					"test=" . $this->m_test . "\x0B" .
					"uip=" . $this->m_uip . "\x0B" .
					"cardnumber=" . $this->m_cardNumber . "\x0B" .
					"cardexpy=" . $this->m_cardExpy . "\x0B" .
					"cardexpm=" . $this->m_cardExpm . "\x0B" .					
					"authfield1=" . $this->m_authfield1 . "\x0B" .
					"authfield2=" . $this->m_authfield2 . "\x0B" .
					"goodname=" . $this->m_goodName . "\x0B" .
					"uid=" . $this->m_uid . "\x0B" .
					"url=" . $this->m_url . "\x0B" .
					"buyername=" . $this->m_buyerName . "\x0B" .
					"merchantReserved3=" . $this->m_merchantReserved3;

				$this->m_responseMsg = exec($this->m_inipayHome . "/phpexec/INIformauth.phpexec \"" . $this->m_requestMsg . "\"");
				if(strlen($this->m_responseMsg) <= 1)
					$this->m_responseMsg = "ResultCode=01&ResultMsg=[9199]INVOKE ERR : " . $this->m_inipayHome . "/phpexec/INIformauth.phpexec";
				
				break;
				
			case("reqrealbill") :
				$this->m_requestMsg = 
					"inipayhome=" . $this->m_inipayHome . "\x0B" .
					"pgid=" . $this->m_pgId . "\x0B" .
					"spgip=" . $this->m_subPgIp . "\x0B" .
					"admin=" . $this->m_keyPw . "\x0B" .
					"debug=" . $this->m_debug . "\x0B" .
					"mid=" . $this->m_mid . "\x0B" .
					"uip=" . $this->m_uip . "\x0B" .
					"paymethod=" . $this->m_payMethod . "\x0B" .
					"url=" . $this->m_url . "\x0B" .
					"test=" . $this->m_test . "\x0B" .
					"goodname=" . $this->m_goodName . "\x0B" .
					"currency=" . $this->m_currency . "\x0B" .
					"price=" . $this->m_price . "\x0B" .
					"billkey=" . $this->m_billKey . "\x0B" .
					"billtype=" . $this->m_billtype . "\x0B" .
					"cardpass=" . $this->m_cardPass . "\x0B" .
					"regnumber=" . $this->m_regNumber . "\x0B" .
					"cardquota=" . $this->m_cardQuota . "\x0B" .
					"authentification=" . $this->m_authentification . "\x0B" .
					"quotainterest=" . $this->m_quotaInterest . "\x0B" .
					"buyername=" . $this->m_buyerName . "\x0B" .
					"buyertel=" . $this->m_buyerTel . "\x0B" .
					"buyeremail=" . $this->m_buyerEmail . "\x0B" .
					"merchantreserved3=" . $this->m_merchantReserved3;
			
				$this->m_responseMsg = exec($this->m_inipayHome . "/phpexec/INIreqrealbill.phpexec \"" . $this->m_requestMsg . "\"");
				if(strlen($this->m_responseMsg) <= 1)
					$this->m_responseMsg = "ResultCode=01&ResultMsg=[9199]INVOKE ERR : " . $this->m_inipayHome . "/phpexec/INIreqrealbill.phpexec";
				
				break;
			
			case("receipt") :
				$this->m_requestMsg =
					"inipayhome=" . $this->m_inipayHome . "\x0B" .
					"pgid=" . $this->m_pgId . "\x0B" .
					"spgip=" . $this->m_subPgIp . "\x0B" .
					"admin=" . $this->m_keyPw . "\x0B" .
					"debug=" . $this->m_debug . "\x0B" .
					"test=" . $this->m_test . "\x0B" .
					"mid=" . $this->m_mid . "\x0B" .
					"uip=" . $this->m_uip . "\x0B" .
					"paymethod=" . $this->m_payMethod . "\x0B" .
					"goodname=" . $this->m_goodName . "\x0B" .
					"currency=" . $this->m_currency . "\x0B" .
					"cr_price=" . $this->m_cr_price . "\x0B" .
					"sup_price=" . $this->m_sup_price . "\x0B" .
					"tax=" . $this->m_tax . "\x0B" .
					"srvc_price=" . $this->m_srvc_price . "\x0B" .
					"buyername=" . $this->m_buyerName . "\x0B" .
					"buyertel=" . $this->m_buyerTel . "\x0B" .
					"buyeremail=" . $this->m_buyerEmail . "\x0B" .
					"ocbnumber=" . $this->m_ocbnumber . "\x0B" .
					"ocbprice=" . $this->m_ocbprice . "\x0B" .
					"reg_num=" . $this->m_reg_num . "\x0B" .
					"useopt=" . $this->m_useopt . "\x0B" .
					"companynumber=" . $this->m_companyNumber. "\x0B";
				//오픈마켓 현금영수증
				if( $this->m_OMFlag == "2" && $this->m_SubCrCnt > 0 )
				{
					$this->m_requestMsg .= "OMFlag=".$this->m_OMFlag."\x0B";
					$this->m_requestMsg .= "SubCrCnt=".$this->m_SubCrCnt."\x0B";
					for( $i=1; $i <= $this->m_SubCrCnt ; $i++ )
					{
						$arr["SubNmComp$i"] .= $this->m_om["Submall_NmComp$i"]	. "\x0C";
						$arr["SubNmComp$i"] .= $this->m_om["Submall_NoComp$i"]	. "\x0C";
						$arr["SubNmComp$i"] .= $this->m_om["Submall_NoCEO$i"]		. "\x0C";
						$arr["SubNmComp$i"] .= $this->m_om["Submall_ID$i"]			. "\x0C";
						$arr["SubNmComp$i"] .= $this->m_om["Submall_price$i"]		. "\x0C";
						$arr["SubNmComp$i"] .= $this->m_om["Submall_srvprice$i"]; 
						$this->m_requestMsg .= "SubNmComp$i=".$arr["SubNmComp$i"]."\x0B";
					}
				}
				$this->m_responseMsg = exec($this->m_inipayHome . "/phpexec/INIreceipt.phpexec \"" . $this->m_requestMsg . "\"");
				if(strlen($this->m_responseMsg) <= 1)
					$this->m_responseMsg = "ResultCode=01&ResultMsg=[9199]INVOKE ERR : " . $this->m_inipayHome . "/phpexec/INIreceipt.phpexec";
				break;
				
			case("chkfake") :
				//배열체크
				if( !is_array( $this->m_enc_arr ) )	
				{
					echo "최소 4개의 항목에 대해선 암호화하셔야 합니다.";
					return;
				} 
				//set parameter
				$this->m_requestMsg =
					"inipayhome=" . $this->m_inipayHome . "\x0B" .
					"debug=" . $this->m_debug. "\x0B".
					"enctype=" . $this->m_enctype. "\x0B".
					"admin=" . $this->m_keyPw . "\x0B" .
					"checkopt=" . $this->m_checkopt . "\x0B";

				foreach ($this->m_enc_arr as $key => $val)
				{
					$this->m_requestMsg = $this->m_requestMsg . $key . "=" . $val . "\x0B";	
				}
				$this->m_requestMsg = substr( $this->m_requestMsg, 0, -1 ); //trim end \x0B

				$exec_str = $this->m_inipayHome . "/phpexec/INIchkfake.phpexec \"" . $this->m_requestMsg . "\"";
        exec( $exec_str, $output );
        if( is_array( $output ) )
        {
            foreach( $output as $out_str )
            {
                $resData.= $out_str."\n";
            }
        		$resData = substr($resData, 0, -1); // Eliminate unnecessary \n
        }
        else
        {
            $resData .= $out_str;
        }
				$this->m_responseMsg = $resData;
				if(strlen($this->m_responseMsg) <= 1)
					$this->m_responseMsg = "ResultCode=01&ResultMsg=[9199]INVOKE ERR : " . $this->m_inipayHome . "/phpexec/INIchkfake.phpexec";
				break;
				
			case("makeenc") :
				$this->m_requestMsg =
					"inipayhome=" . $this->m_inipayHome . "\x0B" .
					"debug=" . $this->m_debug . "\x0B" .
					"mid=" . $this->m_mid . "\x0B" .
					"encsrc=" . $this->m_enc_src;
				$exec_str = $this->m_inipayHome . "/phpexec/INImakeenc.phpexec \"" . $this->m_requestMsg . "\"";
        exec( $exec_str, $output );
        if( is_array( $output ) )
        {
            foreach( $output as $out_str )
            {
                $resData.= $out_str."\n";
            }
        	$resData = substr($resData, 0, -1); // Eliminate unnecessary \n
        } 
        else
        {
          $resData .= $out_str;
        }
				$this->m_responseMsg = $resData;
				if(strlen($this->m_responseMsg) <= 1)
					$this->m_responseMsg = "ResultCode=01&ResultMsg=[9199]INVOKE ERR : " . $this->m_inipayHome . "/phpexec/INImakeenc.phpexec";
				break;

			case("KVPSafeKeyIN") :
				$this->m_requestMsg = 
					"inipayhome=" . $this->m_inipayHome . "\x0B" .
					"pgid=" . $this->m_pgId . "\x0B" .
					"spgip=" . $this->m_subPgIp . "\x0B" .
					"admin=" . $this->m_keyPw . "\x0B" .
					"debug=" . $this->m_debug . "\x0B" .
					"test=" . $this->m_test . "\x0B" .
					"mid=" . $this->m_mid . "\x0B" .
					"uid=" . $this->m_uid . "\x0B" .
					"url=" . $this->m_url . "\x0B" .
					"uip=" . $this->m_uip . "\x0B" .
					"paymethod=" . $this->m_payMethod . "\x0B" .
					"goodname=" . $this->m_goodName . "\x0B" .
					"currency=" . $this->m_currency . "\x0B" .
					"cardcode=" . $this->m_cardCode . "\x0B" .
					"price=" . $this->m_price . "\x0B" .
					"buyername=" . $this->m_buyerName . "\x0B" .
					"buyertel=" . $this->m_buyerTel . "\x0B" .
					"buyeremail=" . $this->m_buyerEmail . "\x0B" .
					"sessionkey=" . $this->m_sessionKey . "\x0B" .
					"encrypted=" . $this->m_encrypted . "\x0B" .
					"kvp_card_prefix=" . $this->m_kvp_card_prefix . "\x0B" .
					"kvp_noint=" . $this->m_kvp_noint . "\x0B" .
					"kvp_quota=" . $this->m_kvp_quota;
				$exec_str = $this->m_inipayHome . "/phpexec/INIKVPSafeKeyIN.phpexec \"" . $this->m_requestMsg . "\"";
				$this->m_responseMsg = exec($exec_str);
				if(strlen($this->m_responseMsg) <= 1)
					$this->m_responseMsg = "ResultCode=01&ResultMsg=[9199]INVOKE ERR : " . $this->m_inipayHome . "/phpexec/INIKVPSafeKeyIN.phpexec";
				break;

			default :
				$this->m_responseMsg = "ResultCode=01&ResultMsg=처리할 수 없는 거래유형입니다 : " . $this->m_type;
		}
		
		parse_str($this->m_responseMsg);
		$this->m_resultCode = $ResultCode;
		$this->m_resultMsg = $ResultMsg;
		$this->m_payMethod = $PayMethod;
		$this->m_authCode = $CardAuthCode;
		$this->m_cardCode = $CardResultCode;
		$this->m_cardIssuerCode = $Detailcode;
		$this->m_tid = $Tid;
		$this->m_price1 = $Price1;
		$this->m_price2 = $Price2;
		$this->m_cardQuota = $CardResultQuota;
		$this->m_quotaInterest = $QuotaInterest;
		$this->m_authCertain = $AuthCertain;
		$this->m_pgAuthDate = $PGauthdate;
		$this->m_pgAuthTime = $PGauthtime;
		$this->m_ocbSaveAuthCode = $OCBauthcode1;
		$this->m_ocbUseAuthCode = $OCBauthcode2;
		$this->m_ocbAuthDate = $OCBauthdate;
		$this->m_ocbResultPoint = $ResultPoint;
		$this->m_cardNumber = $CardResultNumber;
		$this->m_cardExpire = $CardResultExpire;
		$this->m_cardQuota = $CardResultQuota;
		$this->m_perno = $perno;
		$this->m_void = $void;
		$this->m_vacct = $vacct;
		$this->m_vcdbank = $vcdbank;
		$this->m_dtinput = $dtinput;
/* == 가상계좌 관련 추가 2006.10.18 rywkim == */
		$this->m_tminput = $tminput;
		$this->m_nminput = $nminput;
		$this->m_nmvacct = $nmvacct;
		$this->m_rvacct = $rvacct;
		$this->m_rvcdbank = $vcdbank;
		$this->m_rnminput = $nminput;
		$this->m_eventFlag = $EventFlag;
		$this->m_nohpp = $nohpp; 
		$this->m_noars = $noars;
		$this->m_resultprice = $Price;
		$this->m_pgCancelDate = $PGcanceldate;
		$this->m_pgCancelTime = $PGcanceltime;
		$this->m_authCertain = $Authentification;

/* == 달러결제 환율정보 == */
		$this->m_ReqCurrency = $ReqCurrency;		// 해당 통화 코드
		$this->m_RateExchange = $RateExchange;		// 환률

/* == 실시간 빌링 필드 == */
		$this->m_billKey = $BillKey;
    $this->m_cardPass = $CardPass;
    $this->m_cardKind = $CardKind;
		
/* == 틴캐시 추가 필드(2005.02.01 대리 이종완) == */
		$this->m_remain_price = $remain_price;		// 틴캐시 잔액

/* == 현금영수증 발행 리턴 필드 == */
		$this->m_rcr_price = $RCR_Price;		// 총현금결제 금액
		$this->m_rsup_price = $RSup_Price;		// 공급가
		$this->m_rtax = $RTax;				// 부가세
		$this->m_rsrvc_price = $RSrvc_Price;		// 봉사료
		$this->m_ruseopt = $RUseOpt;			// 현금영수증 용도 구분
		$this->m_rcash_noappl = $Rcash_noappl;		// 현금영수증 발행 승인번호
		$this->m_rcash_rslt = $Rcash_rslt;		// 현금영수증 발급코드 (4자리)
		
/* == 현금영수증 취소 승인 번호 리턴 == */		
		$this->m_rcash_cancel_noappl = $Rcash_cancel_noappl;
	
		
/* == CMS 계좌이체 리턴 필드 (2004. 11. 15 대리 이종완) == */
		$this->m_cmsbankcode = $CMSBankCode;		// 은행코드
 		

/* == 부분취소(재승인) 추가 필드 (2004.11.05 대리 이종완) == */
		$this->m_tid_org = $TID_org;			// 원거래 TID
		$this->m_remains = $PR_remains;			// 최종결제 금액
		$this->m_flg_partcancel = $flg_partcancel;	// 부분취소, 재승인 구분값
		$this->m_cnt_partcancel = $cnt_partcancel; 	// 부분취소(재승인) 요청횟수		
		
/* == 추가 필드 (2004.6.23 대리 이종완) == */
		$this->m_moid = $MOID;				// 상점주문번호
		$this->m_codegw = $CodeGW;			// 전화결제 사업자 코드
		$this->m_ocbcardnumber = $OCBcardnumber; 	// OCB 카드번호	
		$this->m_cultureid = $CultureID;		// 컬처랜드 ID, 틴캐시 ID
		$this->m_directbankcode = $DirectBankCode;	// 실시간 은행계좌이체 은행코드

		
/* == 결과메세지 ($m_resultMsg)에서 에러코드 추출 == */
		$str = $ResultMsg ;
		$arr = split("\]+", $str);
		$this->m_resulterrcode = substr($arr[0],1);	// []안의 코드만 표시
		
		
/* == 디지털 문화 상품권 카드 수 (SKT 상품권 공통으로 사용)== */		
		$this->m_dgcl_cardcount = $dgcl_cardcount;


/* == SKT 상품권 필드 (결제 방법 구분) == */
		$this->m_sktg_method = $sktg_method;
		
/* ==  웹페이지 위변조 방지 (2006.12.27 이승국)  == */
		$this->m_ini_rn = $rn;
		if($this->m_enctype == "asym")
		{
			$this->m_ini_encfield = str_replace(" ", "+", $encfield); 
			$this->m_ini_encfield .= "&src=";
			$this->m_ini_encfield .= str_replace(" ", "+", $src); 
		}
		else
		{
			$this->m_ini_encfield = str_replace(" ", "+", $encfield); //원상회복 because parse_str가 "+"->" "
		}
		$this->m_ini_certid = str_replace(" ", "+", $certid);
	}
}

?>
