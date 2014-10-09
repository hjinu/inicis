<?php


/* INIauth_bill.php
 *
 * 이니페이 플러그인을 통해 요청된 실시간 신용카드 빌링 등록을 처리한다.
 * 코드에 대한 자세한 설명은 매뉴얼을 참조하십시오.
 * <주의> 구매자의 세션을 반드시 체크하도록하여 부정거래를 방지하여 주십시요.
 *  
 * http://www.inicis.com
 * Copyright (C) 2004 Inicis Co., Ltd. All rights reserved.
 */

	$opts = getopt(
    '',
    array('params:')
	);

	// error_log("params : ");
	// error_log($opts['params']);
	$params = (array) json_decode($opts['params']);

	/**************************
	 * 1. 라이브러리 인클루드 *
	 **************************/
	require("INIpay41Lib.php");
	
	/***************************************
	 * 2. INIpay41 클래스의 인스턴스 생성 *
	 ***************************************/
	$inipay = new INIpay41;

	/***********************
	 * 2. 정보 설정 *
	 ***********************/
	$inipay->m_inipayHome = realpath(__DIR__ . '/..'); 	// 이니페이 홈디렉터리
	$inipay->m_keyPw = "1111"; 					// 키패스워드(상점아이디에 따라 변경)
	$inipay->m_type = "auth_bill"; 					// 고정 (절대 수정금지)
	$inipay->m_pgId = "INIpayBill"; 				// 고정 (절대 수정금지)
	$inipay->m_subPgIp = "203.238.3.10"; 				// 고정 (절대 수정금지)
	$inipay->m_payMethod = mb_convert_encoding( $params['paymethod'], 'EUC-KR', 'UTF-8' ); 				// 고정 (절대 수정금지)
	$inipay->m_billtype = "Card";					// 고정 (절대 수정금지)
	$inipay->m_debug = "true"; 					// 로그모드("true"로 설정하면 상세한 로그가 생성됨)
	$inipay->m_mid = mb_convert_encoding( $params['mid'], 'EUC-KR', 'UTF-8' );  						// 상점아이디
	$inipay->m_goodName = mb_convert_encoding( $params['goodname'], 'EUC-KR', 'UTF-8' ); 				// 상품명 (최대 40자)
	$inipay->m_buyerName = mb_convert_encoding( $params['buyername'], 'EUC-KR', 'UTF-8' ); 				// 구매자 (최대 15자)
	$inipay->m_url = "http://mondayapple.com";				// 사이트 URL		
	$inipay->m_merchantReserved3 = mb_convert_encoding( $params['merchantReserved3'], 'EUC-KR', 'UTF-8' ); 		// 회원 ID
	$inipay->m_encrypted = mb_convert_encoding( $params['encrypted'], 'EUC-KR', 'UTF-8' );
	$inipay->m_sessionKey = mb_convert_encoding( $params['sessionKey'], 'EUC-KR', 'UTF-8' );

	/**************************************************************
	 * 3. 본인인증 절차를 통한 실시간 신용카드 빌링 등록 요청처리 *
	 **************************************************************/
	
	$inipay->startAction();

	error_log("m_resultCode : ");
	error_log($inipay->m_resultCode);
	error_log("m_resultMsg : ");
	error_log($inipay->m_resultMsg);

	$result = array();
  $result['m_resultCode'] = mb_convert_encoding( trim($inipay->m_resultCode), 'UTF-8', 'EUC-KR' );
  $result['m_resultMsg'] = 	mb_convert_encoding( trim($inipay->m_resultMsg), 'UTF-8', 'EUC-KR' );
  $result['m_cardCode'] = 	mb_convert_encoding( trim($inipay->m_cardCode), 'UTF-8', 'EUC-KR' );
  $result['m_billKey'] = 		mb_convert_encoding( trim($inipay->m_billKey), 'UTF-8', 'EUC-KR' );
  $result['m_cardPass'] = 	mb_convert_encoding( trim($inipay->m_cardPass), 'UTF-8', 'EUC-KR' );
  $result['m_cardKind'] = 	mb_convert_encoding( trim($inipay->m_cardKind), 'UTF-8', 'EUC-KR' );
  $result['m_tid'] = 				mb_convert_encoding( trim($inipay->m_tid), 'UTF-8', 'EUC-KR' );

  return json_encode($result);
	/********************************************************	******
	 *   4. 본인인증 절차를 통한 실시간 신용카드 빌링 등록 결과   *
	 **************************************************************
	 *                                                   	      *
	 * $inipay->m_resultCode           // "00"이면 빌키생성 성공  *
	 * $inipay->m_resultMsg            // 결과에 대한 설명        *
	 * $inipay->m_cardCode             // 카드사 코드             *
	 * $inipay->m_billKey              // BILL KEY                *
	 * $inipay->m_cardPass             // 카드 비밀번호 앞 두자리 *
	 * $inipay->m_cardKind             // 카드종류(개인-0,법인-1) *
	 * $inipay->m_tid                  // 거래번호                * 
	 **************************************************************/
?>